@extends('chronofront.layout')

@section('title', 'TOP Départ')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0"><i class="bi bi-flag-fill text-success"></i> TOP Départ des Courses</h1>
            <p class="text-muted">Enregistrez le top départ de chaque course</p>
        </div>
        <div class="text-end">
            <h3 id="currentTime" class="mb-0 text-primary"></h3>
            <small class="text-muted">Heure actuelle</small>
        </div>
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

    <!-- Liste des courses avec TOP -->
    <div class="row" id="racesContainer">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modification TOP -->
<div class="modal fade" id="editTopModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le TOP Départ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editRaceId">
                <div class="mb-3">
                    <label class="form-label">Course</label>
                    <input type="text" id="editRaceName" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Heure de départ</label>
                    <input type="datetime-local" id="editStartTime" class="form-control" step="1" required>
                    <small class="text-muted">Format: JJ/MM/AAAA HH:MM:SS</small>
                </div>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Attention:</strong> La modification du TOP départ affectera tous les temps calculés pour cette course.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateTopBtn">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
console.log('🟠 top-depart.blade.php loaded');

let allRaces = [];
let allEvents = [];
let currentTimeInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
    loadRaces();
    startCurrentTimeClock();

    document.getElementById('filterEvent').addEventListener('change', filterRaces);
    document.getElementById('searchRace').addEventListener('input', filterRaces);
    document.getElementById('updateTopBtn').addEventListener('click', updateTop);
});

// Afficher l'heure actuelle en temps réel
function startCurrentTimeClock() {
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('fr-FR', { hour12: false });
        const dateStr = now.toLocaleDateString('fr-FR');
        document.getElementById('currentTime').textContent = `${dateStr} ${timeStr}`;
    }

    updateClock();
    currentTimeInterval = setInterval(updateClock, 1000);
}

async function loadEvents() {
    console.log('🟠 Loading events...');
    try {
        const response = await axios.get('/events');
        allEvents = response.data;
        console.log('🟠 Events loaded:', allEvents.length);

        const select = document.getElementById('filterEvent');
        allEvents.forEach(event => {
            const option = new Option(
                `${event.name} (${new Date(event.date_start).toLocaleDateString('fr-FR')})`,
                event.id
            );
            select.add(option);
        });
    } catch (error) {
        console.error('❌ Error loading events:', error);
    }
}

async function loadRaces() {
    console.log('🟠 Loading races...');
    try {
        const response = await axios.get('/races');
        allRaces = response.data;
        console.log('🟠 Races loaded:', allRaces.length);
        displayRaces(allRaces);
    } catch (error) {
        console.error('❌ Error loading races:', error);
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
                <p class="text-muted mt-3">Aucune course trouvée</p>
            </div>
        `;
        return;
    }

    container.innerHTML = races.map(race => {
        const hasStartTime = race.start_time !== null;
        const startTimeDisplay = hasStartTime
            ? new Date(race.start_time).toLocaleString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
              })
            : 'Non démarré';

        const cardClass = hasStartTime ? 'border-success' : 'border-secondary';
        const badgeClass = hasStartTime ? 'bg-success' : 'bg-secondary';
        const badgeText = hasStartTime ? 'Démarré' : 'En attente';

        return `
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100 ${cardClass}" style="border-width: 2px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">${race.name}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar-event"></i> ${race.event?.name || 'Événement inconnu'}
                                </p>
                            </div>
                            <span class="badge ${badgeClass}">${badgeText}</span>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">TOP Départ</small>
                                    <strong class="${hasStartTime ? 'text-success' : 'text-muted'}">
                                        ${hasStartTime ? startTimeDisplay : 'Non défini'}
                                    </strong>
                                </div>
                                ${hasStartTime ? `
                                    <button class="btn btn-sm btn-outline-warning" onclick="editTop(${race.id})" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </div>

                        <div class="row g-2">
                            ${!hasStartTime ? `
                                <div class="col-12">
                                    <button class="btn btn-success w-100" onclick="setTopNow(${race.id})">
                                        <i class="bi bi-stopwatch"></i> TOP Départ MAINTENANT
                                    </button>
                                </div>
                            ` : ''}
                            <div class="col-6">
                                <button class="btn btn-sm btn-outline-primary w-100" onclick="editTop(${race.id})">
                                    <i class="bi bi-clock-history"></i> ${hasStartTime ? 'Modifier' : 'Définir'}
                                </button>
                            </div>
                            ${hasStartTime ? `
                                <div class="col-6">
                                    <button class="btn btn-sm btn-outline-danger w-100" onclick="resetTop(${race.id})">
                                        <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                                    </button>
                                </div>
                            ` : ''}
                        </div>

                        ${race.distance ? `<p class="small text-muted mt-2 mb-0"><i class="bi bi-speedometer"></i> ${race.distance} km</p>` : ''}
                    </div>
                </div>
            </div>
        `;
    }).join('');
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

// Enregistrer le TOP au moment actuel
async function setTopNow(raceId) {
    console.log('🟠 Setting TOP NOW for race:', raceId);

    if (!confirm('Enregistrer le TOP départ MAINTENANT ?')) return;

    try {
        const response = await axios.post(`/races/${raceId}/start`);
        console.log('✅ TOP départ enregistré:', response.data);

        alert(response.data.message);
        loadRaces(); // Recharger pour afficher l'heure
    } catch (error) {
        console.error('❌ Error setting TOP:', error);
        alert('Erreur lors de l\'enregistrement du TOP');
    }
}

// Ouvrir le modal pour modifier le TOP
function editTop(raceId) {
    const race = allRaces.find(r => r.id === raceId);
    if (!race) return;

    document.getElementById('editRaceId').value = race.id;
    document.getElementById('editRaceName').value = race.name;

    // Convertir la date au format datetime-local
    let dateValue = '';
    if (race.start_time) {
        const date = new Date(race.start_time);
        // Format: YYYY-MM-DDTHH:mm:ss
        dateValue = date.getFullYear() + '-' +
            String(date.getMonth() + 1).padStart(2, '0') + '-' +
            String(date.getDate()).padStart(2, '0') + 'T' +
            String(date.getHours()).padStart(2, '0') + ':' +
            String(date.getMinutes()).padStart(2, '0') + ':' +
            String(date.getSeconds()).padStart(2, '0');
    } else {
        // Utiliser l'heure actuelle par défaut
        const now = new Date();
        dateValue = now.getFullYear() + '-' +
            String(now.getMonth() + 1).padStart(2, '0') + '-' +
            String(now.getDate()).padStart(2, '0') + 'T' +
            String(now.getHours()).padStart(2, '0') + ':' +
            String(now.getMinutes()).padStart(2, '0') + ':' +
            String(now.getSeconds()).padStart(2, '0');
    }

    document.getElementById('editStartTime').value = dateValue;

    new bootstrap.Modal(document.getElementById('editTopModal')).show();
}

// Enregistrer la modification du TOP
async function updateTop() {
    const raceId = document.getElementById('editRaceId').value;
    const startTime = document.getElementById('editStartTime').value;

    if (!startTime) {
        alert('Veuillez saisir une heure de départ');
        return;
    }

    console.log('🟠 Updating TOP for race:', raceId, 'to:', startTime);

    try {
        const response = await axios.post(`/races/${raceId}/start`, {
            start_time: startTime
        });

        console.log('✅ TOP modifié:', response.data);
        alert(response.data.message);

        bootstrap.Modal.getInstance(document.getElementById('editTopModal')).hide();
        loadRaces();
    } catch (error) {
        console.error('❌ Error updating TOP:', error);
        alert('Erreur lors de la modification du TOP');
    }
}

// Réinitialiser le TOP (mettre à null)
async function resetTop(raceId) {
    if (!confirm('Réinitialiser le TOP départ ? Cette action supprimera l\'heure de départ enregistrée.')) return;

    const race = allRaces.find(r => r.id === raceId);
    if (!race) return;

    try {
        // Utiliser l'endpoint update pour mettre start_time à null
        const response = await axios.put(`/races/${raceId}`, {
            start_time: null
        });

        console.log('✅ TOP réinitialisé');
        alert('TOP départ réinitialisé avec succès');
        loadRaces();
    } catch (error) {
        console.error('❌ Error resetting TOP:', error);
        alert('Erreur lors de la réinitialisation du TOP');
    }
}

// Cleanup au démontage
window.addEventListener('beforeunload', function() {
    if (currentTimeInterval) {
        clearInterval(currentTimeInterval);
    }
});
</script>
@endpush
@endsection
