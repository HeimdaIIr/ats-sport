<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChronoFront\Entrant;
use App\Models\ChronoFront\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EntrantController extends Controller
{
    /**
     * Display a listing of entrants
     */
    public function index(Request $request): JsonResponse
    {
        $query = Entrant::with(['category', 'race', 'wave']);

        // Search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('bib_number', 'like', "%{$search}%")
                  ->orWhere('rfid_tag', 'like', "%{$search}%");
            });
        }

        // Race filter
        if ($request->has('race_id')) {
            $query->where('race_id', $request->input('race_id'));
        }

        $entrants = $query->orderBy('lastname')->orderBy('firstname')->get();

        return response()->json($entrants);
    }

    /**
     * Store a newly created entrant
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'gender' => 'required|in:M,F',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|max:200',
            'phone' => 'nullable|string|max:50',
            'bib_number' => 'nullable|string|max:20',
            'race_id' => 'nullable|exists:races,id',
            'wave_id' => 'nullable|exists:waves,id',
            'club' => 'nullable|string|max:200',
            'team' => 'nullable|string|max:200',
        ]);

        // Generate RFID tag if bib_number is provided
        if (isset($validated['bib_number']) && !isset($validated['rfid_tag'])) {
            $validated['rfid_tag'] = '2' . str_pad($validated['bib_number'], 6, '0', STR_PAD_LEFT);
        }

        $entrant = Entrant::create($validated);

        // Assign category based on age and gender
        if ($entrant->birth_date && $entrant->gender) {
            $entrant->assignCategory();
            $entrant->load('category');
        }

        return response()->json($entrant, 201);
    }

    /**
     * Display the specified entrant
     */
    public function show(Entrant $entrant): JsonResponse
    {
        $entrant->load(['category', 'race', 'wave', 'results']);
        return response()->json($entrant);
    }

    /**
     * Update the specified entrant
     */
    public function update(Request $request, Entrant $entrant): JsonResponse
    {
        $validated = $request->validate([
            'firstname' => 'sometimes|string|max:100',
            'lastname' => 'sometimes|string|max:100',
            'gender' => 'sometimes|in:M,F',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|max:200',
            'phone' => 'nullable|string|max:50',
            'bib_number' => 'nullable|string|max:20',
            'rfid_tag' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'race_id' => 'nullable|exists:races,id',
            'wave_id' => 'nullable|exists:waves,id',
            'club' => 'nullable|string|max:200',
            'team' => 'nullable|string|max:200',
        ]);

        $entrant->update($validated);

        // Re-assign category if birth_date or gender changed
        if ((isset($validated['birth_date']) || isset($validated['gender'])) && $entrant->birth_date && $entrant->gender) {
            $entrant->assignCategory();
        }

        $entrant->load('category');

        return response()->json($entrant);
    }

    /**
     * Remove the specified entrant
     */
    public function destroy(Entrant $entrant): JsonResponse
    {
        $entrant->delete();
        return response()->json(['message' => 'Entrant deleted successfully']);
    }

    /**
     * Import entrants from CSV file
     * Automatically creates races from PARCOURS column and assigns waves
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'event_id' => 'required|exists:events,id',
        ]);

        $file = $request->file('file');
        $eventId = $request->input('event_id');

        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_map('strtolower', array_shift($csvData));

        $imported = 0;
        $errors = [];
        $createdRaces = [];
        $raceWaveMap = []; // Map: parcours_name => wave_number

        DB::beginTransaction();

        try {
            // First pass: identify unique PARCOURS and their VAGUE assignments
            $parcoursData = [];
            foreach ($csvData as $row) {
                if (count($row) !== count($headers)) {
                    continue;
                }
                $data = array_combine($headers, $row);

                $parcours = $data['parcours'] ?? null;
                $vague = $data['vague'] ?? null;

                if ($parcours) {
                    if (!isset($parcoursData[$parcours])) {
                        $parcoursData[$parcours] = [
                            'name' => $parcours,
                            'wave' => $vague ?: null // null if empty
                        ];
                    }
                }
            }

            // Sort parcours alphabetically for auto wave assignment
            ksort($parcoursData);

            // Assign waves: use specified VAGUE or auto-assign by alphabetical order
            $autoWaveCounter = 1;
            foreach ($parcoursData as $parcours => &$info) {
                if ($info['wave']) {
                    // Use specified wave number
                    $raceWaveMap[$parcours] = (int) $info['wave'];
                } else {
                    // Auto-assign by alphabetical order
                    $raceWaveMap[$parcours] = $autoWaveCounter;
                    $autoWaveCounter++;
                }
            }

            // Create races for each unique PARCOURS
            foreach ($parcoursData as $parcours => $info) {
                $race = \App\Models\ChronoFront\Race::create([
                    'event_id' => $eventId,
                    'name' => $parcours,
                    'distance' => null, // Can be extracted from parcours name if needed
                    'distance_unit' => 'km',
                ]);

                $createdRaces[$parcours] = $race->id;
            }

            // Second pass: create entrants and assign to races
            foreach ($csvData as $index => $row) {
                if (count($row) !== count($headers)) {
                    continue;
                }

                $data = array_combine($headers, $row);

                $parcours = $data['parcours'] ?? null;

                if (!$parcours || !isset($createdRaces[$parcours])) {
                    $errors[] = "Ligne " . ($index + 2) . ": PARCOURS manquant ou invalide";
                    continue;
                }

                // Get wave number for this parcours
                $waveNumber = $raceWaveMap[$parcours] ?? 1;

                // Map common CSV column names
                $entrantData = [
                    'firstname' => $data['prenom'] ?? $data['firstname'] ?? $data['prénom'] ?? '',
                    'lastname' => $data['nom'] ?? $data['lastname'] ?? $data['name'] ?? '',
                    'gender' => strtoupper($data['sexe'] ?? $data['gender'] ?? $data['sex'] ?? 'M'),
                    'birth_date' => $data['naissance'] ?? $data['date_naissance'] ?? $data['birth_date'] ?? $data['dob'] ?? null,
                    'email' => $data['email'] ?? $data['mail'] ?? null,
                    'phone' => $data['telephone'] ?? $data['phone'] ?? $data['tel'] ?? null,
                    'bib_number' => $data['dossard'] ?? $data['bib'] ?? $data['bib_number'] ?? null,
                    'club' => $data['club'] ?? $data['association'] ?? null,
                    'team' => $data['equipe'] ?? $data['team'] ?? null,
                    'race_id' => $createdRaces[$parcours],
                    'wave' => $waveNumber, // Store wave number directly in entrant
                ];

                // Handle category: use CAT column if provided, otherwise auto-assign
                $category = $data['cat'] ?? $data['categorie'] ?? $data['category'] ?? null;
                if ($category) {
                    $categoryModel = Category::where('code', $category)
                        ->orWhere('name', 'like', "%{$category}%")
                        ->first();
                    if ($categoryModel) {
                        $entrantData['category_id'] = $categoryModel->id;
                    }
                }

                // Generate RFID tag from bib number: 2000000 + DOSSARD
                if ($entrantData['bib_number']) {
                    $entrantData['rfid_tag'] = '2' . str_pad($entrantData['bib_number'], 6, '0', STR_PAD_LEFT);
                }

                // Clean data
                $entrantData['gender'] = in_array($entrantData['gender'], ['M', 'F']) ? $entrantData['gender'] : 'M';

                if ($entrantData['birth_date']) {
                    try {
                        $entrantData['birth_date'] = \Carbon\Carbon::parse($entrantData['birth_date'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $entrantData['birth_date'] = null;
                    }
                }

                $entrant = Entrant::create($entrantData);

                // Auto-assign category if not already set and birth_date is available
                if (!$entrant->category_id && $entrant->birth_date && $entrant->gender) {
                    $entrant->assignCategory();
                }

                $imported++;
            }

            DB::commit();

            return response()->json([
                'message' => "Import réussi",
                'imported' => $imported,
                'races_created' => count($createdRaces),
                'races' => array_map(function($parcours, $raceId) use ($raceWaveMap) {
                    return [
                        'name' => $parcours,
                        'race_id' => $raceId,
                        'wave' => $raceWaveMap[$parcours]
                    ];
                }, array_keys($createdRaces), $createdRaces),
                'total_rows' => count($csvData),
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Import échoué',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search entrants by various criteria
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $entrants = Entrant::with(['category', 'race'])
            ->where(function ($q) use ($query) {
                $q->where('firstname', 'like', "%{$query}%")
                  ->orWhere('lastname', 'like', "%{$query}%")
                  ->orWhere('bib_number', 'like', "%{$query}%")
                  ->orWhere('rfid_tag', 'like', "%{$query}%")
                  ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$query}%"]);
            })
            ->limit(50)
            ->get();

        return response()->json($entrants);
    }
}
