<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChronoFront\Event;
use App\Services\ChronoFront\ImportCsvService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ImportController extends Controller
{
    protected ImportCsvService $importService;

    public function __construct(ImportCsvService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Importer un fichier CSV de participants
     *
     * @param Request $request
     * @param Event $event
     * @return JsonResponse
     */
    public function importCsv(Request $request, Event $event): JsonResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240' // Max 10MB
        ]);

        try {
            $file = $request->file('csv_file');
            $filePath = $file->getRealPath();

            // Importer le CSV
            $stats = $this->importService->import($filePath, $event);

            return response()->json([
                'success' => true,
                'message' => "Import terminé : {$stats['imported']} participants importés",
                'stats' => $stats
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import CSV',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Télécharger un template CSV vierge
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadTemplate()
    {
        $headers = [
            'DOSSARD',
            'NOM',
            'PRENOM',
            'SEXE',
            'NAISSANCE',
            'PARCOURS',
            'IDPARCOURS',
            'CLUB',
            'LICENCE',
            'Email',
            'TEL'
        ];

        $exampleRow = [
            '1',
            'DUPONT',
            'Jean',
            'M',
            '15/05/1985',
            'Semi Marathon de Sète',
            '22809',
            'AS SÈTE',
            'PB12345678',
            'jean.dupont@example.com',
            '0612345678'
        ];

        $csv = '"' . implode('","', $headers) . "\"\n";
        $csv .= '"' . implode('","', $exampleRow) . "\"\n";

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="template_import_chronofront.csv"');
    }

    /**
     * Valider un fichier CSV sans l'importer
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function validateCsv(Request $request): JsonResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        try {
            $file = $request->file('csv_file');

            // Vérifier le format
            $content = file_get_contents($file->getRealPath());
            $lines = explode("\n", $content);

            if (count($lines) < 2) {
                throw new Exception('Le fichier CSV est vide ou invalide');
            }

            // Vérifier les en-têtes
            $headers = str_getcsv($lines[0], ',', '"');
            $requiredHeaders = ['DOSSARD', 'NOM', 'PRENOM', 'SEXE', 'NAISSANCE'];

            $missingHeaders = [];
            foreach ($requiredHeaders as $required) {
                if (!in_array($required, $headers)) {
                    $missingHeaders[] = $required;
                }
            }

            if (!empty($missingHeaders)) {
                throw new Exception('En-têtes manquants : ' . implode(', ', $missingHeaders));
            }

            return response()->json([
                'success' => true,
                'message' => 'Fichier CSV valide',
                'stats' => [
                    'total_rows' => count($lines) - 1,
                    'headers' => $headers
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier CSV invalide',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
