@extends('chronofront.layout')

@section('title', 'Résultats')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h2 mb-4"><i class="bi bi-bar-chart text-success"></i> Résultats</h1>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Course</label>
                            <select id="raceSelect" class="form-select">
                                <option value="">-- Sélectionner une course --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type de classement</label>
                            <select id="rankingType" class="form-select">
                                <option value="scratch">Scratch (Général)</option>
                                <option value="gender-M">Hommes</option>
                                <option value="gender-F">Femmes</option>
                            </select>
                        </div>
                    </div>
                    <button id="loadBtn" class="btn btn-primary mt-3" disabled>
                        <i class="bi bi-search"></i> Afficher
                    </button>
                    <button id="calculateBtn" class="btn btn-warning mt-3" disabled>
                        <i class="bi bi-calculator"></i> Recalculer
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center" id="stats">
                    <p class="text-muted">Sélectionnez une course</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr id="tableHeader">
                            <th colspan="8" class="text-center text-muted">Sélectionnez une course et un classement</th>
                        </tr>
                    </thead>
                    <tbody id="resultsBody">
                        <tr><td colspan="8" class="text-center py-4 text-muted">Aucun résultat</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let races = [];
let currentRaceId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadRaces();

    document.getElementById('raceSelect').addEventListener('change', function() {
        currentRaceId = this.value;
        document.getElementById('loadBtn').disabled = !currentRaceId;
        document.getElementById('calculateBtn').disabled = !currentRaceId;
        if (currentRaceId) loadStats();
    });

    document.getElementById('loadBtn').addEventListener('click', loadResults);
    document.getElementById('calculateBtn').addEventListener('click', calculateResults);
});

async function loadRaces() {
    const response = await fetch('/api/races');
    races = await response.json();
    const select = document.getElementById('raceSelect');
    races.forEach(race => {
        select.add(new Option(race.name + (race.event ? ' - ' + race.event.name : ''), race.id));
    });
}

async function loadStats() {
    const response = await fetch(`/api/results/race/${currentRaceId}/statistics`);
    const data = await response.json();
    if (data.success) {
        const s = data.statistics;
        document.getElementById('stats').innerHTML = `
            <h5>${s.finishers || 0} arrivés</h5>
            <p class="small mb-1">DNF: ${s.dnf || 0} (${s.finish_rate || 0}%)</p>
            <p class="small mb-1">Temps moyen: ${s.avg_time || '-'}</p>
            <p class="small mb-0">Meilleur: ${s.fastest_time || '-'}</p>
        `;
    }
}

async function calculateResults() {
    if (!currentRaceId) return;
    if (!confirm('Recalculer tous les résultats de cette course ?')) return;

    const response = await fetch(`/api/results/race/${currentRaceId}/calculate?force=true`, { method: 'POST' });
    const data = await response.json();
    if (data.success) {
        alert(data.message);
        loadStats();
        loadResults();
    } else {
        alert('Erreur: ' + data.message);
    }
}

async function loadResults() {
    if (!currentRaceId) return;

    const type = document.getElementById('rankingType').value;
    let url = `/api/results/race/${currentRaceId}/`;

    if (type === 'scratch') {
        url += 'scratch';
    } else if (type.startsWith('gender-')) {
        url += 'gender/' + type.split('-')[1];
    }

    const response = await fetch(url);
    const data = await response.json();

    if (data.success) {
        displayResults(data.results, type);
    }
}

function displayResults(results, type) {
    const header = document.getElementById('tableHeader');
    const body = document.getElementById('resultsBody');

    const posCol = type === 'scratch' ? 'Position' : (type.startsWith('gender') ? 'Pos. Sexe' : 'Pos. Cat.');

    header.innerHTML = `
        <th>${posCol}</th>
        <th>Dossard</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Sexe</th>
        <th>Catégorie</th>
        <th>Club</th>
        <th>Temps</th>
    `;

    if (results.length === 0) {
        body.innerHTML = '<tr><td colspan="8" class="text-center py-4">Aucun résultat calculé</td></tr>';
        return;
    }

    body.innerHTML = results.map(r => `
        <tr>
            <td class="fw-bold">${type === 'scratch' ? r.position : (type.startsWith('gender') ? r.position_gender : r.position_category)}</td>
            <td><span class="badge bg-secondary">${r.bib_number}</span></td>
            <td>${r.lastname}</td>
            <td>${r.firstname}</td>
            <td><span class="badge bg-${r.gender === 'M' ? 'primary' : 'danger'}">${r.gender}</span></td>
            <td class="small">${r.category || '-'}</td>
            <td class="small text-muted">${r.club || '-'}</td>
            <td class="fw-bold">${r.race_time}</td>
        </tr>
    `).join('');
}
</script>
@endpush
@endsection
