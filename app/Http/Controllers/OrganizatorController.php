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
        $event = Event::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'location' => $request->location,
            'department' => $request->department,
            'event_date' => $request->event_date,
            'registration_deadline' => $request->registration_deadline,
            'max_participants' => $request->max_participants,
            'status' => 'upcoming'
        ]);

        return redirect()->route('organizer.dashboard')->with('success', 'Événement créé avec succès!');
    }
}