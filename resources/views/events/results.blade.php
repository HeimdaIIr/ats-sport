@extends('layouts.app')

@section('title', 'Résultats')

@section('content')
    <h1>📊 Résultats des courses</h1>
    
    @forelse($events as $event)
        <div style="border: 1px solid #ddd; padding: 20px; margin: 15px 0; border-radius: 8px; background: #f9f9f9;">
            <h3>{{ $event->name }}</h3>
            <p>📍 {{ $event->location }} ({{ $event->department }})</p>
            <p>📅 {{ $event->event_date->format('d/m/Y') }}</p>
            <p style="color: #666;">XXX coureurs classés</p>
            <a href="#" style="background: #17a2b8; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">VOS RÉSULTATS</a>
        </div>
    @empty
        <p>Aucun résultat disponible pour le moment.</p>
    @endforelse
@endsection