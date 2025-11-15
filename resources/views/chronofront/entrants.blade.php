@extends('chronofront.layout')

@section('title', 'Gestion des Participants')

@section('content')
<div class="text-center py-5">
    <h1 class="h2 mb-4"><i class="bi bi-people text-info"></i> Gestion des Participants</h1>
    <p class="lead text-muted mb-4">Cette page est en cours de développement</p>

    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <a href="{{ route('chronofront.entrants.import') }}" class="btn btn-success btn-lg mb-3">
                <i class="bi bi-upload"></i> Import CSV
            </a>
            
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i> API disponible : <code>POST /api/entrants/import</code>
            </div>
        </div>
    </div>
</div>
@endsection
