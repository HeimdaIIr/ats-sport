@extends('chronofront.layout')

@section('title', 'Gestion des Vagues')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">
            <i class="bi bi-flag text-primary"></i> Gestion des Vagues
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWaveModal">
            <i class="bi bi-plus-circle"></i> Nouvelle Vague
        </button>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Événement</label>
                    <select id="filterEvent" class="form-select">
                        <option value="">Tous les événements</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <select id="filterRace" class="form-select">
                        <option value="">Toutes les courses</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Vagues -->
    <div class="row" id="wavesContainer">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Création Vague -->
<div class="modal fade" id="createWaveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Vague</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createWaveForm">
                    <div class="mb-3">
                        <label class="form-label">Course *</label>
                        <select id="waveRaceId" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom de la vague *</label>
                        <input type="text" id="waveName" class="form-control" placeholder="Vague 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Heure de départ *</label>
                        <input type="time" id="waveStartTime" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacité maximale</label>
                        <input type="number" id="waveMaxCapacity" class="form-control" placeholder="500">
                        <small class="text-muted">Laisser vide pour illimité</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="waveDescription" class="form-control" rows="3" placeholder="Ex: Départ élites"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveWaveBtn">Créer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Édition Vague -->
<div class="modal fade" id="editWaveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la Vague</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editWaveForm">
                    <input type="hidden" id="editWaveId">
                    <div class="mb-3">
                        <label class="form-label">Nom de la vague *</label>
                        <input type="text" id="editWaveName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Heure de départ *</label>
                        <input type="time" id="editWaveStartTime" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacité maximale</label>
                        <input type="number" id="editWaveMaxCapacity" class="form-control">
                        <small class="text-muted">Laisser vide pour illimité</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="editWaveDescription" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateWaveBtn">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let allWaves = [];
let allRaces = [];
let allEvents = [];
let entrantsCount = {};

document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
    loadRaces();
    loadWaves();
    loadEntrantsCounts();

    document.getElementById('saveWaveBtn').addEventListener('click', createWave);
    document.getElementById('updateWaveBtn').addEventListener('click', updateWave);
    document.getElementById('filterEvent').addEventListener('change', onEventFilterChange);
    document.getElementById('filterRace').addEventListener('change', filterWaves);
});

async function loadEvents() {
    try {
        const response = await fetch('/api/events');
        const data = await response.json();
        allEvents = data.data || data;

        const select = document.getElementById('filterEvent');
        allEvents.forEach(event => {
            const option = new Option(
                `${event.name} (${new Date(event.event_date).toLocaleDateString('fr-FR')})`,
                event.id
            );
            select.add(option);
        });
    } catch (error) {
        console.error('Error loading events:', error);
    }
}

async function loadRaces() {
    try {
        const response = await fetch('/api/races');
        allRaces = await response.json();
        populateRaceSelects();
    } catch (error) {
        console.error('Error loading races:', error);
    }
}

function populateRaceSelects(eventId = null) {
    const select1 = document.getElementById('waveRaceId');
    const select2 = document.getElementById('filterRace');

    // Clear existing options
    select1.innerHTML = '<option value="">-- Sélectionner --</option>';
    select2.innerHTML = '<option value="">Toutes les courses</option>';

    const filteredRaces = eventId
        ? allRaces.filter(r => r.event_id == eventId)
        : allRaces;

    filteredRaces.forEach(race => {
        const label = race.event?.name
            ? `${race.name} - ${race.event.name}`
            : race.name;

        const option1 = new Option(label, race.id);
        const option2 = option1.cloneNode(true);
        select1.add(option1);
        select2.add(option2);
    });
}

function onEventFilterChange() {
    const eventId = document.getElementById('filterEvent').value;
    populateRaceSelects(eventId);
    filterWaves();
}

async function loadWaves() {
    try {
        const response = await fetch('/api/waves');
        allWaves = await response.json();
        displayWaves(allWaves);
    } catch (error) {
        console.error('Error loading waves:', error);
        document.getElementById('wavesContainer').innerHTML = `
            <div class="col-12 text-center py-5">
                <p class="text-danger">Erreur lors du chargement des vagues</p>
            </div>
        `;
    }
}

async function loadEntrantsCounts() {
    try {
        const response = await fetch('/api/entrants');
        const entrants = await response.json();

        // Compter les participants par vague
        entrantsCount = {};
        entrants.forEach(entrant => {
            if (entrant.wave_id) {
                entrantsCount[entrant.wave_id] = (entrantsCount[entrant.wave_id] || 0) + 1;
            }
        });

        // Rafraîchir l'affichage
        if (allWaves.length > 0) {
            displayWaves(getCurrentFilteredWaves());
        }
    } catch (error) {
        console.error('Error loading entrants counts:', error);
    }
}

function getCurrentFilteredWaves() {
    const eventFilter = document.getElementById('filterEvent').value;
    const raceFilter = document.getElementById('filterRace').value;

    let filtered = allWaves;

    if (raceFilter) {
        filtered = filtered.filter(w => w.race_id == raceFilter);
    } else if (eventFilter) {
        const eventRaceIds = allRaces
            .filter(r => r.event_id == eventFilter)
            .map(r => r.id);
        filtered = filtered.filter(w => eventRaceIds.includes(w.race_id));
    }

    return filtered;
}

function displayWaves(waves) {
    const container = document.getElementById('wavesContainer');

    if (waves.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="bi bi-flag" style="font-size: 4rem; color: #ddd;"></i>
                <p class="text-muted mt-3">Aucune vague créée</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWaveModal">
                    <i class="bi bi-plus-circle"></i> Créer une vague
                </button>
            </div>
        `;
        return;
    }

    container.innerHTML = waves.map(wave => {
        const race = allRaces.find(r => r.id === wave.race_id);
        const count = entrantsCount[wave.id] || 0;
        const capacity = wave.max_capacity || '∞';
        const percentFull = wave.max_capacity ? Math.round((count / wave.max_capacity) * 100) : 0;

        let capacityBadge = 'bg-success';
        if (percentFull >= 90) capacityBadge = 'bg-danger';
        else if (percentFull >= 70) capacityBadge = 'bg-warning';

        return `
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">${wave.name}</h5>
                            <span class="badge ${capacityBadge}">${count} / ${capacity}</span>
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-trophy"></i> ${race?.name || 'Course inconnue'}
                        </p>
                        <p class="small mb-2">
                            <i class="bi bi-clock"></i> <strong>Départ:</strong> ${wave.start_time || '-'}
                        </p>
                        ${wave.description ? `<p class="small text-muted mb-3">${wave.description}</p>` : ''}

                        ${wave.max_capacity && percentFull > 0 ? `
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar ${capacityBadge}" role="progressbar"
                                     style="width: ${percentFull}%" aria-valuenow="${percentFull}"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        ` : ''}

                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="editWave(${wave.id})">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteWave(${wave.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function filterWaves() {
    const filtered = getCurrentFilteredWaves();
    displayWaves(filtered);
}

async function createWave() {
    const data = {
        race_id: document.getElementById('waveRaceId').value,
        name: document.getElementById('waveName').value,
        start_time: document.getElementById('waveStartTime').value,
        max_capacity: document.getElementById('waveMaxCapacity').value || null,
        description: document.getElementById('waveDescription').value || null
    };

    try {
        const response = await fetch('/api/waves', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            bootstrap.Modal.getInstance(document.getElementById('createWaveModal')).hide();
            document.getElementById('createWaveForm').reset();
            loadWaves();
            alert('Vague créée avec succès !');
        } else {
            alert('Erreur lors de la création');
        }
    } catch (error) {
        console.error('Error creating wave:', error);
        alert('Erreur réseau');
    }
}

async function editWave(id) {
    const wave = allWaves.find(w => w.id === id);
    if (!wave) return;

    document.getElementById('editWaveId').value = wave.id;
    document.getElementById('editWaveName').value = wave.name;
    document.getElementById('editWaveStartTime').value = wave.start_time || '';
    document.getElementById('editWaveMaxCapacity').value = wave.max_capacity || '';
    document.getElementById('editWaveDescription').value = wave.description || '';

    new bootstrap.Modal(document.getElementById('editWaveModal')).show();
}

async function updateWave() {
    const id = document.getElementById('editWaveId').value;
    const data = {
        name: document.getElementById('editWaveName').value,
        start_time: document.getElementById('editWaveStartTime').value,
        max_capacity: document.getElementById('editWaveMaxCapacity').value || null,
        description: document.getElementById('editWaveDescription').value || null
    };

    try {
        const response = await fetch(`/api/waves/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            bootstrap.Modal.getInstance(document.getElementById('editWaveModal')).hide();
            loadWaves();
            alert('Vague mise à jour !');
        } else {
            alert('Erreur lors de la mise à jour');
        }
    } catch (error) {
        console.error('Error updating wave:', error);
        alert('Erreur réseau');
    }
}

async function deleteWave(id) {
    if (!confirm('Supprimer cette vague ? Les participants seront désassignés.')) return;

    try {
        const response = await fetch(`/api/waves/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (response.ok) {
            loadWaves();
            alert('Vague supprimée');
        } else {
            alert('Erreur lors de la suppression');
        }
    } catch (error) {
        console.error('Error deleting wave:', error);
        alert('Erreur réseau');
    }
}
</script>
@endpush
@endsection
