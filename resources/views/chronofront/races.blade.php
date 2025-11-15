@extends('chronofront.layout')

@section('title', 'Gestion des Épreuves')

@section('content')
<div class="text-center py-5">
    <h1 class="h2 mb-4"><i class="bi bi-trophy text-warning"></i> Gestion des Épreuves</h1>
    <p class="lead text-muted mb-4">Cette page est en cours de développement</p>

    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h5 class="card-title">Fonctionnalités à venir</h5>
            <ul class="list-unstyled text-start mt-3">
                <li class="mb-2"><i class="bi bi-check text-success"></i> Créer des épreuves</li>
                <li class="mb-2"><i class="bi bi-check text-success"></i> Configurer types de parcours (1 passage, N tours, boucle)</li>
                <li class="mb-2"><i class="bi bi-check text-success"></i> Démarrer/terminer les épreuves</li>
                <li class="mb-2"><i class="bi bi-check text-success"></i> Gérer distance et tours</li>
            </ul>

            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i> En attendant, utilisez l'API REST : <code>GET /api/races</code>
            </div>

            <a href="{{ route('chronofront.events') }}" class="btn btn-primary mt-2">
                <i class="bi bi-calendar-event"></i> Aller aux événements
            </a>
        </div>
    </div>
</div>
@endsection
