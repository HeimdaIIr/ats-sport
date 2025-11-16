@extends('chronofront.layout')

@section('title', 'Chronométrage Temps Réel')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h2 mb-4"><i class="bi bi-stopwatch text-warning"></i> Chrono Temps Réel</h1>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Course</label>
                            <select id="raceSelect" class="form-select">
                                <option value="">-- Sélectionner --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Point de Chronométrage</label>
                            <select id="timingPointSelect" class="form-select" disabled>
                                <option value="">-- Sélectionner --</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button id="startBtn" class="btn btn-success" disabled>
                            <i class="bi bi-play-fill"></i> Démarrer
                        </button>
                        <button id="stopBtn" class="btn btn-danger" disabled>
                            <i class="bi bi-stop-fill"></i> Arrêter
                        </button>
                        <span id="status" class="ms-3 text-muted">Inactif</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <h2 id="detectionCount" class="display-4 mb-0">0</h2>
                    <p class="mb-0">Détections</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history"></i> Dernières Détections</span>
            <button id="clearBtn" class="btn btn-sm btn-outline-light">
                <i class="bi bi-trash"></i> Effacer
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-sm table-hover mb-0">
                    <thead class="sticky-top bg-white">
                        <tr>
                            <th>Heure</th>
                            <th>Dossard</th>
                            <th>Nom</th>
                            <th>Sexe</th>
                            <th>Type</th>
                            <th>Tag RFID</th>
                        </tr>
                    </thead>
                    <tbody id="detectionsBody">
                        <tr><td colspan="6" class="text-center py-4 text-muted">Aucune détection</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let races = [];
let timingPoints = [];
let detections = [];
let refreshInterval = null;
let currentTimingPointId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadRaces();

    document.getElementById('raceSelect').addEventListener('change', function() {
        const raceId = this.value;
        document.getElementById('timingPointSelect').disabled = !raceId;
        if (raceId) loadTimingPoints(raceId);
    });

    document.getElementById('timingPointSelect').addEventListener('change', function() {
        currentTimingPointId = this.value;
        document.getElementById('startBtn').disabled = !currentTimingPointId;
    });

    document.getElementById('startBtn').addEventListener('click', startMonitoring);
    document.getElementById('stopBtn').addEventListener('click', stopMonitoring);
    document.getElementById('clearBtn').addEventListener('click', clearDetections);
});

async function loadRaces() {
    const response = await fetch('/api/races');
    races = await response.json();
    const select = document.getElementById('raceSelect');
    races.forEach(race => {
        select.add(new Option(race.name, race.id));
    });
}

async function loadTimingPoints(raceId) {
    const response = await fetch(`/api/timing-points/race/${raceId}`);
    timingPoints = await response.json();
    const select = document.getElementById('timingPointSelect');
    select.innerHTML = '<option value="">-- Sélectionner --</option>';
    timingPoints.forEach(tp => {
        select.add(new Option(`${tp.name} (${tp.point_type})`, tp.id));
    });
}

function startMonitoring() {
    if (!currentTimingPointId) return;

    document.getElementById('startBtn').disabled = true;
    document.getElementById('stopBtn').disabled = false;
    document.getElementById('status').innerHTML = '<span class="text-success"><i class="bi bi-circle-fill blink"></i> Actif</span>';

    loadDetections();
    refreshInterval = setInterval(loadDetections, 3000); // Refresh every 3s
}

function stopMonitoring() {
    clearInterval(refreshInterval);
    refreshInterval = null;

    document.getElementById('startBtn').disabled = false;
    document.getElementById('stopBtn').disabled = true;
    document.getElementById('status').innerHTML = '<span class="text-muted">Inactif</span>';
}

async function loadDetections() {
    if (!currentTimingPointId) return;

    const response = await fetch(`/api/rfid/timing-point/${currentTimingPointId}/recent?limit=50`);
    const data = await response.json();

    if (data.success) {
        detections = data.detections;
        displayDetections();
    }
}

function displayDetections() {
    const tbody = document.getElementById('detectionsBody');
    document.getElementById('detectionCount').textContent = detections.length;

    if (detections.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Aucune détection</td></tr>';
        return;
    }

    tbody.innerHTML = detections.map(d => `
        <tr>
            <td class="small">${new Date(d.detection_time).toLocaleTimeString('fr-FR')}</td>
            <td><span class="badge bg-dark">${d.bib_number}</span></td>
            <td>${d.name}</td>
            <td><span class="badge bg-${d.gender === 'M' ? 'primary' : 'danger'}">${d.gender}</span></td>
            <td><span class="badge bg-${d.detection_type === 'rfid' ? 'info' : 'warning'}">${d.detection_type.toUpperCase()}</span></td>
            <td class="small"><code>${d.rfid_tag_read || '-'}</code></td>
        </tr>
    `).join('');
}

function clearDetections() {
    if (confirm('Effacer l\'affichage des détections ?')) {
        detections = [];
        displayDetections();
    }
}
</script>
<style>
.blink { animation: blink 1s linear infinite; }
@keyframes blink { 50% { opacity: 0.5; } }
</style>
@endpush
@endsection
