<?php

namespace App\Services\ChronoFront;

use App\Models\ChronoFront\Event;
use App\Models\ChronoFront\Race;
use App\Models\ChronoFront\Entrant;
use App\Models\ChronoFront\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use Exception;

class ImportCsvService
{
    /**
     * Importer un fichier CSV avec le format exact SportLab
     *
     * Format attendu : "DOSSARD","NOM","PRENOM","SEXE","NAISSANCE","PARCOURS","IDPARCOURS",...
     *
     * @param string $filePath Chemin du fichier CSV
     * @param Event $event Événement parent
     * @return array Statistiques d'import
     * @throws Exception
     */
    public function import(string $filePath, Event $event): array
    {
        if (!file_exists($filePath)) {
            throw new Exception("Fichier CSV introuvable : {$filePath}");
        }

        // Statistiques
        $stats = [
            'total_rows' => 0,
            'imported' => 0,
            'errors' => 0,
            'races_created' => 0,
            'error_details' => []
        ];

        DB::beginTransaction();

        try {
            // 1. Charger et parser le CSV
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setDelimiter(','); // Virgule
            $csv->setEnclosure('"'); // Guillemets doubles
            $csv->setHeaderOffset(0); // Première ligne = en-têtes

            // 2. Récupérer et normaliser les en-têtes en MAJUSCULES
            $originalHeaders = $csv->getHeader();
            $this->validateHeaders($originalHeaders);

            // Créer une map de normalisation : header original -> header normalisé
            $headerMap = [];
            foreach ($originalHeaders as $header) {
                $headerMap[$header] = strtoupper(trim($header));
            }

            $headers = array_values($headerMap);

            // 3. Identifier et créer les parcours uniques
            $racesMap = $this->identifyAndCreateRaces($csv, $event, $headerMap);
            $stats['races_created'] = count($racesMap);

            // 4. Importer chaque participant
            foreach ($csv->getRecords() as $index => $row) {
                $stats['total_rows']++;

                try {
                    // Normaliser les clés de la ligne en MAJUSCULES
                    $normalizedRow = [];
                    foreach ($row as $key => $value) {
                        $normalizedKey = $headerMap[$key] ?? strtoupper($key);
                        $normalizedRow[$normalizedKey] = $value;
                    }

                    // Valider la ligne
                    $validated = $this->validateRow($normalizedRow, $index + 2); // +2 car ligne 1 = headers

                    // Trouver la course et la vague correspondantes
                    $raceKey = $this->getRaceKey($normalizedRow);
                    if (!isset($racesMap[$raceKey])) {
                        throw new Exception("Parcours introuvable pour : {$raceKey}");
                    }
                    $raceData = $racesMap[$raceKey];
                    $race = $raceData['race'];
                    $waveNumber = $raceData['wave'];

                    // Générer le tag RFID (format 2000XXX)
                    $rfidTag = $this->generateRfidTag($validated['bib_number']);

                    // Calculer la catégorie FFA (ou utiliser CAT du CSV si présent)
                    $categoryId = null;
                    if (!empty($normalizedRow['CAT'])) {
                        $catModel = Category::where('code', $normalizedRow['CAT'])
                            ->orWhere('name', 'like', "%{$normalizedRow['CAT']}%")
                            ->first();
                        $categoryId = $catModel?->id;
                    }

                    // Sinon calculer automatiquement
                    if (!$categoryId) {
                        $category = $this->calculateCategory(
                            $validated['birth_date'],
                            $validated['gender']
                        );
                        $categoryId = $category?->id;
                    }

                    // Créer le participant avec numéro de vague
                    Entrant::create([
                        'race_id' => $race->id,
                        'wave' => $waveNumber, // Numéro de vague
                        'bib_number' => $validated['bib_number'],
                        'rfid_tag' => $rfidTag,
                        'lastname' => $validated['last_name'],
                        'firstname' => $validated['first_name'],
                        'gender' => $validated['gender'],
                        'birth_date' => $validated['birth_date'],
                        'category_id' => $categoryId,
                        'club' => $validated['club'] ?? null,
                        'license_number' => $validated['license_number'] ?? null,
                        'email' => $validated['email'] ?? null,
                        'phone' => $validated['phone'] ?? null,
                        'team' => $validated['team'] ?? null,
                    ]);

                    $stats['imported']++;

                } catch (Exception $e) {
                    $stats['errors']++;
                    $stats['error_details'][] = [
                        'row' => $index + 2,
                        'bib_number' => $normalizedRow['DOSSARD'] ?? 'N/A',
                        'name' => ($normalizedRow['NOM'] ?? '') . ' ' . ($normalizedRow['PRENOM'] ?? ''),
                        'parcours' => $normalizedRow['PARCOURS'] ?? 'N/A',
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Erreur lors de l'import CSV : " . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Valider la présence des en-têtes obligatoires
     */
    private function validateHeaders(array $headers): void
    {
        // Normaliser les headers en majuscules pour comparaison
        $headersUpper = array_map('strtoupper', $headers);

        $required = ['DOSSARD', 'NOM', 'PRENOM', 'SEXE', 'NAISSANCE', 'PARCOURS'];

        foreach ($required as $header) {
            if (!in_array($header, $headersUpper)) {
                throw new Exception("En-tête obligatoire manquant : {$header}. Headers trouvés : " . implode(', ', $headers));
            }
        }
    }

    /**
     * Identifier et créer les parcours uniques depuis le CSV
     * Gère aussi la colonne VAGUE pour assigner les vagues automatiquement
     */
    private function identifyAndCreateRaces(Reader $csv, Event $event, array $headerMap): array
    {
        $racesMap = [];
        $parcours = [];
        $waveMap = []; // Map: raceKey => wave_number

        // Collecter tous les parcours uniques avec leurs vagues
        foreach ($csv->getRecords() as $row) {
            // Normaliser les clés en MAJUSCULES
            $normalizedRow = [];
            foreach ($row as $key => $value) {
                $normalizedKey = $headerMap[$key] ?? strtoupper($key);
                $normalizedRow[$normalizedKey] = $value;
            }

            $raceKey = $this->getRaceKey($normalizedRow);
            if (!isset($parcours[$raceKey])) {
                $parcours[$raceKey] = [
                    'name' => $normalizedRow['PARCOURS'] ?? 'Parcours',
                    'id_parcours' => $normalizedRow['IDPARCOURS'] ?? null,
                    'wave' => !empty($normalizedRow['VAGUE']) ? (int) $normalizedRow['VAGUE'] : null
                ];
            }
        }

        // Trier par ordre alphabétique des noms de parcours pour attribution auto
        uksort($parcours, function($a, $b) use ($parcours) {
            return strcmp($parcours[$a]['name'], $parcours[$b]['name']);
        });

        // Attribuer les numéros de vague
        $autoWaveCounter = 1;
        foreach ($parcours as $key => $data) {
            if ($data['wave']) {
                // Utiliser la vague spécifiée dans le CSV
                $waveMap[$key] = $data['wave'];
            } else {
                // Attribution automatique par ordre alphabétique
                $waveMap[$key] = $autoWaveCounter;
                $autoWaveCounter++;
            }
        }

        // Créer ou récupérer chaque course
        foreach ($parcours as $key => $data) {
            $race = Race::firstOrCreate(
                [
                    'event_id' => $event->id,
                    'name' => trim($data['name'])
                ],
                [
                    'type' => $this->guessRaceType($data['name']),
                    'distance' => $this->guessDistance($data['name']),
                    'laps' => 1
                ]
            );

            $racesMap[$key] = [
                'race' => $race,
                'wave' => $waveMap[$key]
            ];
        }

        return $racesMap;
    }

    /**
     * Obtenir la clé unique d'un parcours (pour grouper)
     */
    private function getRaceKey(array $row): string
    {
        // Utiliser IDPARCOURS en priorité, sinon PARCOURS
        return $row['IDPARCOURS'] ?? $row['PARCOURS'] ?? 'default';
    }

    /**
     * Deviner le type de course depuis le nom
     */
    private function guessRaceType(string $name): string
    {
        $name = strtolower($name);

        if (str_contains($name, 'marathon') && !str_contains($name, 'semi')) {
            return '1_passage'; // Marathon
        }
        if (str_contains($name, 'semi')) {
            return '1_passage'; // Semi-marathon
        }
        if (str_contains($name, '10') || str_contains($name, 'dix')) {
            return '1_passage'; // 10km
        }
        if (str_contains($name, '5') || str_contains($name, 'cinq')) {
            return '1_passage'; // 5km
        }
        if (str_contains($name, 'enfant') || str_contains($name, 'kid')) {
            return '1_passage'; // Course enfants
        }

        return '1_passage';
    }

    /**
     * Deviner la distance depuis le nom
     */
    private function guessDistance(string $name): float
    {
        $name = strtolower($name);

        if (str_contains($name, 'marathon') && !str_contains($name, 'semi')) {
            return 42.195;
        }
        if (str_contains($name, 'semi')) {
            return 21.0975;
        }
        if (str_contains($name, '10')) {
            return 10.0;
        }
        if (str_contains($name, '5')) {
            return 5.0;
        }
        if (str_contains($name, 'enfant')) {
            return 1.0;
        }

        return 0.0;
    }

    /**
     * Valider une ligne du CSV
     */
    private function validateRow(array $row, int $lineNumber): array
    {
        // Validation minimale - DOSSARD et NOM/PRENOM
        if (empty($row['DOSSARD'])) {
            throw new Exception("Ligne {$lineNumber} : DOSSARD manquant");
        }
        if (empty($row['NOM']) && empty($row['PRENOM'])) {
            throw new Exception("Ligne {$lineNumber} : NOM et PRENOM manquants");
        }

        // Parser la date de naissance - supporter plusieurs formats
        $birthDate = null;
        if (!empty($row['NAISSANCE'])) {
            $dateString = trim($row['NAISSANCE']);
            $formats = [
                'd/m/Y',      // 15/05/1985
                'd-m-Y',      // 15-05-1985
                'Y-m-d',      // 1985-05-15
                'd/m/y',      // 15/05/85
                'd-m-y',      // 15-05-85
                'Y/m/d',      // 1985/05/15
            ];

            foreach ($formats as $format) {
                try {
                    $birthDate = Carbon::createFromFormat($format, $dateString);
                    if ($birthDate) {
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Si aucun format ne marche, essayer le parser automatique
            if (!$birthDate) {
                try {
                    $birthDate = Carbon::parse($dateString);
                } catch (\Exception $e) {
                    // Date invalide, on log mais on continue
                    \Log::warning("Ligne {$lineNumber} : Date invalide '{$dateString}', participant créé sans date");
                }
            }
        }

        // Normaliser le sexe (H → M, défaut M si vide)
        $gender = strtoupper(trim($row['SEXE'] ?? 'M'));
        if ($gender === 'H' || $gender === 'HOMME') {
            $gender = 'M';
        }
        if ($gender === 'FEMME') {
            $gender = 'F';
        }
        // Si toujours invalide, mettre M par défaut
        if (!in_array($gender, ['M', 'F'])) {
            $gender = 'M';
        }

        return [
            'bib_number' => (int) trim($row['DOSSARD']),
            'last_name' => strtoupper(trim($row['NOM'] ?? '')),
            'first_name' => ucwords(strtolower(trim($row['PRENOM'] ?? ''))),
            'gender' => $gender,
            'birth_date' => $birthDate ? $birthDate->format('Y-m-d') : null,
            'club' => !empty($row['CLUB']) ? trim($row['CLUB']) : null,
            'license_number' => !empty($row['LICENCE']) ? trim($row['LICENCE']) : null,
            'email' => !empty($row['Email']) ? trim($row['Email']) : null,
            'phone' => !empty($row['TEL']) ? trim($row['TEL']) : null,
            'team' => !empty($row['EQUIPE']) ? trim($row['EQUIPE']) : null,
        ];
    }

    /**
     * Générer le tag RFID au format 2000XXX
     *
     * Exemples :
     * - Dossard 1 → 2000001
     * - Dossard 157 → 2000157
     * - Dossard 1999 → 2001999
     */
    private function generateRfidTag(int $bibNumber): string
    {
        return '2' . str_pad($bibNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calculer la catégorie FFA automatiquement
     *
     * Catégories FFA 2025 :
     * - Hommes : SE, M0, M1, M2, M3, M4, M5, M6, M7, M8, M9
     * - Femmes : FSE, FM0, FM1, FM2, FM3, FM4, FM5, FM6, FM7, FM8, FM9
     * - Jeunes : BB, PO, EA, MI, CA, JU (avec préfixe F pour femmes)
     */
    private function calculateCategory(string $birthDate, string $gender): ?Category
    {
        $birthYear = Carbon::parse($birthDate)->year;
        $age = now()->year - $birthYear;

        // Déterminer la catégorie de base
        if ($age < 8) $cat = 'BB';
        elseif ($age < 10) $cat = 'PO';
        elseif ($age < 12) $cat = 'EA';
        elseif ($age < 14) $cat = 'MI';
        elseif ($age < 16) $cat = 'CA';
        elseif ($age < 20) $cat = 'JU';
        elseif ($age < 35) $cat = 'SE';
        elseif ($age < 40) $cat = 'M0';
        elseif ($age < 45) $cat = 'M1';
        elseif ($age < 50) $cat = 'M2';
        elseif ($age < 55) $cat = 'M3';
        elseif ($age < 60) $cat = 'M4';
        elseif ($age < 65) $cat = 'M5';
        elseif ($age < 70) $cat = 'M6';
        elseif ($age < 75) $cat = 'M7';
        elseif ($age < 80) $cat = 'M8';
        else $cat = 'M9';

        // Préfixe F pour les femmes
        $categoryName = $gender === 'F' ? 'F' . $cat : $cat;

        // Chercher dans la table categories
        return Category::where('name', 'LIKE', "%{$categoryName}%")->first();
    }
}
