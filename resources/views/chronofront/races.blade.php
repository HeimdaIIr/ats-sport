@extends('chronofront.layout')

@section('title', 'Gestion des Courses')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">
            <i class="bi bi-trophy text-warning"></i> Gestion des Courses
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRaceModal">
            <i class="bi bi-plus-circle"></i> Nouvelle Course
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
                    <label class="form-label">Recherche</label>
                    <input type="text" id="searchRace" class="form-control" placeholder="Nom de la course...">
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Courses -->
    <div class="row" id="racesContainer">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Création Course -->
<div class="modal fade" id="createRaceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createRaceForm">
                    <div class="mb-3">
                        <label class="form-label">Événement *</label>
                        <select id="raceEventId" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom de la course *</label>
                        <input type="text" id="raceName" class="form-control" placeholder="Semi-Marathon" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Distance (km) *</label>
                        <input type="number" id="raceDistance" class="form-control" step="0.1" placeholder="21.1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Heure de départ</label>
                        <input type="time" id="raceStartTime" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="raceDescription" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveRaceBtn">Créer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Édition Course -->
<div class="modal fade" id="editRaceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editRaceForm">
                    <input type="hidden" id="editRaceId">
                    <div class="mb-3">
                        <label class="form-label">Nom de la course *</label>
                        <input type="text" id="editRaceName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Distance (km) *</label>
                        <input type="number" id="editRaceDistance" class="form-control" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Heure de départ</label>
                        <input type="time" id="editRaceStartTime" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="editRaceDescription" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateRaceBtn">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let allRaces = [];
let allEvents = [];

document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
    loadRaces();

    document.getElementById('saveRaceBtn').addEventListener('click', createRace);
    document.getElementById('updateRaceBtn').addEventListener('click', updateRace);
    document.getElementById('filterEvent').addEventListener('change', filterRaces);
    document.getElementById('searchRace').addEventListener('input', filterRaces);
});

async function loadEvents() {
    console.log('🔵 races.blade.php - loadEvents() v2.0 - AXIOS VERSION');
    try {
        console.log('🔵 Calling axios.get(/events)...');
        const response = await axios.get('/events');
        allEvents = response.data;
        console.log('🔵 Events loaded:', allEvents.length, 'événements', allEvents);

        const select1 = document.getElementById('raceEventId');
        const select2 = document.getElementById('filterEvent');

        allEvents.forEach(event => {
            const option1 = new Option(`${event.name} (${new Date(event.date_start).toLocaleDateString('fr-FR')})`, event.id);
            const option2 = option1.cloneNode(true);
            select1.add(option1);
            select2.add(option2);
            console.log('✅ Option ajoutée:', option1.text);
        });
        console.log('✅ Total options ajoutées:', allEvents.length);
    } catch (error) {
        console.error('❌ Error loading events:', error);
    }
}

async function loadRaces() {
    try {
        const response = await fetch('/api/races');
        allRaces = await response.json();
        displayRaces(allRaces);
    } catch (error) {
        console.error('Error loading races:', error);
        document.getElementById('racesContainer').innerHTML = `
            <div class="col-12 text-center py-5">
                <p class="text-danger">Erreur lors du chargement des courses</p>
            </div>
        `;
    }
}

function displayRaces(races) {
    const container = document.getElementById('racesContainer');

    if (races.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="bi bi-trophy" style="font-size: 4rem; color: #ddd;"></i>
                <p class="text-muted mt-3">Aucune course créée</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRaceModal">
                    <i class="bi bi-plus-circle"></i> Créer une course
                </button>
            </div>
        `;
        return;
    }

    container.innerHTML = races.map(race => `
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">${race.name}</h5>
                        <span class="badge bg-primary">${race.distance || '?'} km</span>
                    </div>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-calendar-event"></i> ${race.event?.name || 'Événement inconnu'}
                    </p>
                    ${race.start_time ? `<p class="small"><i class="bi bi-clock"></i> Départ: ${race.start_time}</p>` : ''}
                    ${race.description ? `<p class="small text-muted">${race.description}</p>` : ''}

                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-sm btn-outline-primary flex-fill" onclick="editRace(${race.id})">
                            <i class="bi bi-pencil"></i> Modifier
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRace(${race.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function filterRaces() {
    const eventFilter = document.getElementById('filterEvent').value;
    const searchTerm = document.getElementById('searchRace').value.toLowerCase();

    let filtered = allRaces;

    if (eventFilter) {
        filtered = filtered.filter(r => r.event_id == eventFilter);
    }

    if (searchTerm) {
        filtered = filtered.filter(r => r.name.toLowerCase().includes(searchTerm));
    }

    displayRaces(filtered);
}

async function createRace() {
    const data = {
        event_id: document.getElementById('raceEventId').value,
        name: document.getElementById('raceName').value,
        distance: document.getElementById('raceDistance').value,
        start_time: document.getElementById('raceStartTime').value || null,
        description: document.getElementById('raceDescription').value || null
    };

    try {
        const response = await fetch('/api/races', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            bootstrap.Modal.getInstance(document.getElementById('createRaceModal')).hide();
            document.getElementById('createRaceForm').reset();
            loadRaces();
            alert('Course créée avec succès !');
        } else {
            alert('Erreur lors de la création');
        }
    } catch (error) {
        console.error('Error creating race:', error);
        alert('Erreur réseau');
    }
}

async function editRace(id) {
    const race = allRaces.find(r => r.id === id);
    if (!race) return;

    document.getElementById('editRaceId').value = race.id;
    document.getElementById('editRaceName').value = race.name;
    document.getElementById('editRaceDistance').value = race.distance || '';
    document.getElementById('editRaceStartTime').value = race.start_time || '';
    document.getElementById('editRaceDescription').value = race.description || '';

    new bootstrap.Modal(document.getElementById('editRaceModal')).show();
}

async function updateRace() {
    const id = document.getElementById('editRaceId').value;
    const data = {
        name: document.getElementById('editRaceName').value,
        distance: document.getElementById('editRaceDistance').value,
        start_time: document.getElementById('editRaceStartTime').value || null,
        description: document.getElementById('editRaceDescription').value || null
    };

    try {
        const response = await fetch(`/api/races/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            bootstrap.Modal.getInstance(document.getElementById('editRaceModal')).hide();
            loadRaces();
            alert('Course mise à jour !');
        } else {
            alert('Erreur lors de la mise à jour');
        }
    } catch (error) {
        console.error('Error updating race:', error);
        alert('Erreur réseau');
    }
}

async function deleteRace(id) {
    if (!confirm('Supprimer cette course ? Cette action est irréversible.')) return;

    try {
        const response = await fetch(`/api/races/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (response.ok) {
            loadRaces();
            alert('Course supprimée');
        } else {
            alert('Erreur lors de la suppression');
        }
    } catch (error) {
        console.error('Error deleting race:', error);
        alert('Erreur réseau');
    }
}
</script>
@endpush
@endsection
