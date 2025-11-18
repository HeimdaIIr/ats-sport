<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizatorController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')->get();
        return view('organizer.dashboard', compact('events'));
    }

    public function create()
    {
        return view('organizer.create-event');
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:100',
            'department' => 'required|string|max:3',
            'event_date' => 'required|date|after_or_equal:today',
            'registration_deadline' => 'required|date|before_or_equal:event_date',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'nullable|in:upcoming,open,closed,completed',
        ], [
            'name.required' => 'Le nom de l\'épreuve est obligatoire',
            'location.required' => 'Le lieu est obligatoire',
            'department.required' => 'Le département est obligatoire',
            'department.max' => 'Le département ne peut pas dépasser 3 caractères',
            'event_date.required' => 'La date de l\'épreuve est obligatoire',
            'event_date.after_or_equal' => 'La date de l\'épreuve doit être aujourd\'hui ou dans le futur',
            'registration_deadline.required' => 'La date limite d\'inscription est obligatoire',
            'registration_deadline.before_or_equal' => 'La date limite d\'inscription doit être avant ou égale à la date de l\'épreuve',
            'status.in' => 'Le statut sélectionné n\'est pas valide',
        ]);

        // Si l'événement est marqué comme vedette, retirer le statut vedette des autres
        if ($request->has('is_featured') && $request->is_featured) {
            Event::where('is_featured', true)->update(['is_featured' => false]);
        }

        // Créer l'événement
        $event = Event::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'],
            'department' => $validated['department'],
            'event_date' => $validated['event_date'],
            'registration_deadline' => $validated['registration_deadline'],
            'max_participants' => $validated['max_participants'] ?? null,
            'status' => $validated['status'] ?? 'upcoming',
            'is_featured' => $request->has('is_featured') ? true : false
        ]);

        return redirect()->route('organizer.dashboard')->with('success', 'Épreuve "' . $event->name . '" créée avec succès!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('organizer.edit-event', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:100',
            'department' => 'required|string|max:3',
            'event_date' => 'required|date',
            'registration_deadline' => 'required|date|before_or_equal:event_date',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'nullable|in:upcoming,open,closed,completed',
        ], [
            'name.required' => 'Le nom de l\'épreuve est obligatoire',
            'location.required' => 'Le lieu est obligatoire',
            'department.required' => 'Le département est obligatoire',
            'department.max' => 'Le département ne peut pas dépasser 3 caractères',
            'event_date.required' => 'La date de l\'épreuve est obligatoire',
            'registration_deadline.required' => 'La date limite d\'inscription est obligatoire',
            'registration_deadline.before_or_equal' => 'La date limite d\'inscription doit être avant ou égale à la date de l\'épreuve',
            'status.in' => 'Le statut sélectionné n\'est pas valide',
        ]);

        // Si l'événement est marqué comme vedette, retirer le statut vedette des autres
        if ($request->has('is_featured') && $request->is_featured) {
            Event::where('id', '!=', $id)
                 ->where('is_featured', true)
                 ->update(['is_featured' => false]);
        }

        // Mettre à jour l'événement
        $event->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'],
            'department' => $validated['department'],
            'event_date' => $validated['event_date'],
            'registration_deadline' => $validated['registration_deadline'],
            'max_participants' => $validated['max_participants'] ?? null,
            'status' => $validated['status'] ?? $event->status,
            'is_featured' => $request->has('is_featured') ? true : false
        ]);

        return redirect()->route('organizer.dashboard')->with('success', 'Épreuve "' . $event->name . '" modifiée avec succès!');
    }

    public function toggleFeatured($id)
    {
        $event = Event::findOrFail($id);
        
        // Si on met cet événement en vedette, retirer le statut des autres
        if (!$event->is_featured) {
            Event::where('is_featured', true)->update(['is_featured' => false]);
            $event->is_featured = true;
            $message = 'Événement mis en vedette!';
        } else {
            $event->is_featured = false;
            $message = 'Événement retiré de la vedette!';
        }
        
        $event->save();

        return redirect()->back()->with('success', $message);
    }
}