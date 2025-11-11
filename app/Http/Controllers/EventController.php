<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::whereIn('status', ['upcoming', 'open', 'closed'])
                    ->orderBy('event_date', 'asc')
                    ->get();
        
        $completedEvents = Event::where('status', 'completed')
                            ->orderBy('event_date', 'desc')
                            ->limit(6)
                            ->get();
        
        return view('events.index', compact('events', 'completedEvents'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('events.show', compact('event'));
    }

    public function results()
    {
        $events = Event::where('status', 'completed')
                    ->orderBy('event_date', 'desc')
                    ->get();
        return view('events.results', compact('events'));
    }       
}