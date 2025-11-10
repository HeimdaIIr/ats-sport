<!DOCTYPE html>
<html>
<head>
    <title>{{ $event->name }} - ATS Sport</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .back-link { color: #007bff; text-decoration: none; }
        .event-detail { background: #f9f9f9; padding: 20px; border-radius: 8px; }
        .btn { background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
    <body>
        @extends('layouts.app')

        @section('title', $event->name)

        @section('content')
            <a href="/" style="color: #007bff; text-decoration: none;">← Retour aux courses</a>
            
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 15px;">
                <h1>{{ $event->name }}</h1>
                <p><strong>Lieu :</strong> {{ $event->location }} ({{ $event->department }})</p>
                <p><strong>Date :</strong> {{ $event->event_date->format('d/m/Y') }}</p>
                <p><strong>Inscription avant :</strong> {{ $event->registration_deadline->format('d/m/Y') }}</p>
                
                <br>
                <a href="#" style="background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">S'INSCRIRE</a>
            </div>
        @endsection
    </body>
</html>