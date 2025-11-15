@extends('chronofront.layout')

@section('title', 'Import CSV Participants')

@section('content')
<div class="text-center py-5">
    <h1 class="h2 mb-4"><i class="bi bi-upload text-success"></i> Import CSV Participants</h1>
    <p class="lead text-muted mb-4">Page en cours de développement</p>

    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h5>Format CSV supporté</h5>
            <p class="small text-muted">Colonnes : dossard,nom,prenom,sexe,date_naissance,email,club</p>
            
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i> API : <code>POST /api/entrants/import</code>
            </div>
        </div>
    </div>
</div>
@endsection
