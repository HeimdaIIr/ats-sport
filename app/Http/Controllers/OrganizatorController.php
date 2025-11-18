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
        // Si l'événement est marqué comme vedette, retirer le statut vedette des autres
        if ($request->has('is_featured') && $request->is_featured) {
            Event::where('is_featured', true)->update(['is_featured' => false]);
        }

        $event = Event::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'location' => $request->location,
            'department' => $request->department,
            'event_date' => $request->event_date,
            'registration_deadline' => $request->registration_deadline,
            'max_participants' => $request->max_participants,
            'status' => 'upcoming',
            'is_featured' => $request->has('is_featured') ? true : false
        ]);

        return redirect()->route('organizer.dashboard')->with('success', 'Événement créé avec succès!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('organizer.edit-event', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Si l'événement est marqué comme vedette, retirer le statut vedette des autres
        if ($request->has('is_featured') && $request->is_featured) {
            Event::where('id', '!=', $id)
                 ->where('is_featured', true)
                 ->update(['is_featured' => false]);
        }

        $event->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'location' => $request->location,
            'department' => $request->department,
            'event_date' => $request->event_date,
            'registration_deadline' => $request->registration_deadline,
            'max_participants' => $request->max_participants,
            'status' => $request->status ?? $event->status,
            'is_featured' => $request->has('is_featured') ? true : false
        ]);

        return redirect()->route('organizer.dashboard')->with('success', 'Événement modifié avec succès!');
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