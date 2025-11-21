@extends('chronofront.layout')

@section('title', 'Chronométrage')

@section('content')
<div class="container-fluid" x-data="timingManager()">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2"><i class="bi bi-stopwatch text-warning"></i> Chronométrage Temps Réel</h1>
            <p class="text-muted">Enregistrez les temps de passage des participants</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-success" @click="recalculatePositions" :disabled="!selectedRace || recalculating">
                <i class="bi bi-calculator"></i>
                <span x-show="!recalculating">Recalculer positions</span>
                <span x-show="recalculating">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Calcul...
                </span>
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <div x-show="successMessage" x-transition class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> <span x-text="successMessage"></span>
        <button type="button" class="btn-close" @click="successMessage = null"></button>
    </div>

    <div x-show="errorMessage" x-transition class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i> <span x-text="errorMessage"></span>
        <button type="button" class="btn-close" @click="errorMessage = null"></button>
    </div>

    <div class="row">
        <!-- Left Column: Race Selection & Quick Entry -->
        <div class="col-lg-4">
            <!-- Race Selection -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-trophy"></i> Sélection Épreuve</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Événement</label>
                        <select class="form-select" x-model="selectedEvent" @change="onEventChange">
                            <option value="">-- Sélectionnez --</option>
                            <template x-for="event in events" :key="event.id">
                                <option :value="event.id" x-text="event.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Épreuve</label>
                        <select class="form-select" x-model="selectedRace" @change="onRaceChange">
                            <option value="">-- Sélectionnez --</option>
                            <template x-for="race in filteredRaces" :key="race.id">
                                <option :value="race.id" x-text="race.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Active Waves -->
            <div x-show="selectedRace && waves.length > 0" class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-flag-fill"></i> Vagues Actives</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <template x-for="wave in waves" :key="wave.id">
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong x-text="wave.name"></strong>
                                        <span x-show="wave.is_started && !wave.end_time" class="badge bg-success ms-2">
                                            <i class="bi bi-play-fill"></i> En cours
                                        </span>
                                        <span x-show="!wave.is_started" class="badge bg-warning ms-2">
                                            <i class="bi bi-clock"></i> Pas démarrée
                                        </span>
                                        <div class="small text-muted" x-show="wave.start_time">
                                            Départ: <span x-text="formatTime(wave.start_time)"></span>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary" x-text="(wave.entrants?.length || 0)"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Quick Time Entry -->
            <div x-show="selectedRace" class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Saisie Rapide</h5>
                </div>
                <div class="card-body">
                    <form @submit.prevent="addTime">
                        <div class="mb-3">
                            <label class="form-label">Numéro de dossard</label>
                            <input
                                type="text"
                                class="form-control form-control-lg"
                                x-model="bibNumber"
                                placeholder="Ex: 2113"
                                autofocus
                                :disabled="!selectedRace || saving"
                            >
                        </div>
                        <button
                            type="submit"
                            class="btn btn-warning btn-lg w-100"
                            :disabled="!bibNumber || !selectedRace || saving"
                        >
                            <span x-show="!saving">
                                <i class="bi bi-stopwatch"></i> Enregistrer Temps
                            </span>
                            <span x-show="saving">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Enregistrement...
                            </span>
                        </button>
                        <div class="form-text">
                            Entrez le dossard et appuyez sur Entrée ou cliquez sur le bouton
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Results Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check"></i> Dernières Détections
                        <span x-show="results.length > 0" class="badge bg-primary ms-2" x-text="results.length"></span>
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" @click="loadResults" :disabled="!selectedRace">
                            <i class="bi bi-arrow-clockwise"></i> Actualiser
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div x-show="!selectedRace" class="text-center py-5 text-muted">
                        <i class="bi bi-info-circle" style="font-size: 3rem;"></i>
                        <p class="mt-3">Veuillez sélectionner une épreuve</p>
                    </div>

                    <div x-show="selectedRace && loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>

                    <div x-show="selectedRace && !loading && results.length === 0" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-3">Aucune détection enregistrée</p>
                    </div>

                    <div x-show="selectedRace && !loading && results.length > 0" class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Heure</th>
                                    <th>Dossard</th>
                                    <th>Participant</th>
                                    <th>Vague</th>
                                    <th>Tour</th>
                                    <th>Temps</th>
                                    <th>Vitesse</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="result in results" :key="result.id">
                                    <tr>
                                        <td>
                                            <span class="small" x-text="formatTime(result.raw_time)"></span>
                                            <span x-show="result.is_manual" class="badge bg-info ms-1" title="Saisie manuelle">
                                                <i class="bi bi-pencil-fill"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <strong x-text="result.entrant?.bib_number"></strong>
                                        </td>
                                        <td>
                                            <span x-text="result.entrant?.firstname + ' ' + result.entrant?.lastname"></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary" x-text="result.wave?.name"></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary" x-text="'Tour ' + result.lap_number"></span>
                                        </td>
                                        <td>
                                            <strong x-text="formatDuration(result.calculated_time)"></strong>
                                        </td>
                                        <td>
                                            <span x-text="result.speed ? result.speed + ' km/h' : 'N/A'"></span>
                                        </td>
                                        <td>
                                            <select
                                                class="form-select form-select-sm"
                                                :class="{
                                                    'badge-status-v': result.status === 'V',
                                                    'badge-status-dns': result.status === 'DNS',
                                                    'badge-status-dnf': result.status === 'DNF',
                                                    'badge-status-dsq': result.status === 'DSQ',
                                                    'badge-status-ns': result.status === 'NS'
                                                }"
                                                :value="result.status"
                                                @change="updateStatus(result, $event.target.value)"
                                            >
                                                <option value="V">V - Validé</option>
                                                <option value="DNS">DNS - Non parti</option>
                                                <option value="DNF">DNF - Abandon</option>
                                                <option value="DSQ">DSQ - Disqualifié</option>
                                                <option value="NS">NS - Non classé</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button
                                                class="btn btn-sm btn-outline-danger"
                                                @click="deleteResult(result)"
                                                title="Supprimer"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function timingManager() {
    return {
        events: [],
        races: [],
        filteredRaces: [],
        waves: [],
        results: [],
        selectedEvent: '',
        selectedRace: '',
        bibNumber: '',
        loading: false,
        saving: false,
        recalculating: false,
        successMessage: null,
        errorMessage: null,
        autoRefreshInterval: null,

        init() {
            this.loadEvents();
            this.loadRaces();
        },

        async loadEvents() {
            try {
                const response = await axios.get('/events');
                this.events = response.data;
            } catch (error) {
                console.error('Erreur lors du chargement des événements', error);
            }
        },

        async loadRaces() {
            try {
                const response = await axios.get('/races');
                this.races = response.data;
            } catch (error) {
                console.error('Erreur lors du chargement des épreuves', error);
            }
        },

        onEventChange() {
            if (this.selectedEvent) {
                this.filteredRaces = this.races.filter(race => race.event_id == this.selectedEvent);
            } else {
                this.filteredRaces = this.races;
            }
            this.selectedRace = '';
            this.results = [];
            this.waves = [];
        },

        async onRaceChange() {
            if (this.selectedRace) {
                await this.loadWaves();
                await this.loadResults();
                this.startAutoRefresh();
            } else {
                this.stopAutoRefresh();
                this.results = [];
                this.waves = [];
            }
        },

        async loadWaves() {
            try {
                const response = await axios.get(`/waves/race/${this.selectedRace}`);
                this.waves = response.data;
            } catch (error) {
                console.error('Erreur lors du chargement des vagues', error);
            }
        },

        async loadResults() {
            if (!this.selectedRace) return;

            this.loading = true;
            try {
                const response = await axios.get(`/results/race/${this.selectedRace}`);
                this.results = response.data.sort((a, b) => {
                    return new Date(b.raw_time) - new Date(a.raw_time);
                });
            } catch (error) {
                console.error('Erreur lors du chargement des résultats', error);
            } finally {
                this.loading = false;
            }
        },

        async addTime() {
            if (!this.bibNumber || !this.selectedRace) return;

            this.saving = true;
            this.errorMessage = null;

            try {
                const response = await axios.post('/results/time', {
                    race_id: this.selectedRace,
                    bib_number: this.bibNumber,
                    is_manual: true
                });

                this.successMessage = `Temps enregistré pour le dossard ${this.bibNumber}`;
                this.bibNumber = '';
                await this.loadResults();

                // Auto-clear success message after 3 seconds
                setTimeout(() => {
                    this.successMessage = null;
                }, 3000);

            } catch (error) {
                this.errorMessage = error.response?.data?.message || 'Erreur lors de l\'enregistrement du temps';
            } finally {
                this.saving = false;
            }
        },

        async updateStatus(result, newStatus) {
            try {
                await axios.put(`/results/${result.id}`, {
                    status: newStatus
                });
                result.status = newStatus;
                this.successMessage = 'Statut mis à jour';
                setTimeout(() => {
                    this.successMessage = null;
                }, 2000);
            } catch (error) {
                this.errorMessage = 'Erreur lors de la mise à jour du statut';
            }
        },

        async deleteResult(result) {
            if (!confirm(`Supprimer la détection du dossard ${result.entrant?.bib_number} ?`)) return;

            try {
                await axios.delete(`/results/${result.id}`);
                this.successMessage = 'Détection supprimée';
                await this.loadResults();
            } catch (error) {
                this.errorMessage = 'Erreur lors de la suppression';
            }
        },

        async recalculatePositions() {
            if (!this.selectedRace) return;

            this.recalculating = true;
            try {
                const response = await axios.post(`/results/race/${this.selectedRace}/recalculate`);
                this.successMessage = response.data.message;
                await this.loadResults();
            } catch (error) {
                this.errorMessage = 'Erreur lors du recalcul des positions';
            } finally {
                this.recalculating = false;
            }
        },

        startAutoRefresh() {
            this.stopAutoRefresh();
            this.autoRefreshInterval = setInterval(() => {
                this.loadResults();
            }, 5000); // Refresh every 5 seconds
        },

        stopAutoRefresh() {
            if (this.autoRefreshInterval) {
                clearInterval(this.autoRefreshInterval);
                this.autoRefreshInterval = null;
            }
        },

        formatTime(datetime) {
            if (!datetime) return 'N/A';
            const date = new Date(datetime);
            return date.toLocaleTimeString('fr-FR');
        },

        formatDuration(seconds) {
            if (!seconds) return 'N/A';
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }
    }
}
</script>

<style>
.badge-status-v { background-color: #10B981 !important; color: white; }
.badge-status-dns { background-color: #F59E0B !important; color: white; }
.badge-status-dnf { background-color: #EF4444 !important; color: white; }
.badge-status-dsq { background-color: #DC2626 !important; color: white; }
.badge-status-ns { background-color: #6B7280 !important; color: white; }
</style>
@endsection
