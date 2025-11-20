<?php $__env->startSection('title', 'Chronométrage Temps Réel'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="timingInterface()" class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0"><i class="bi bi-stopwatch text-warning"></i> Chronométrage Temps Réel</h1>
            <p class="text-muted">Interface unifiée de chronométrage RFID SportLab 2.0</p>
        </div>
        <div class="text-end">
            <div class="display-6" x-text="currentTime"></div>
            <small class="text-muted">Heure actuelle</small>
        </div>
    </div>

    <!-- Event Selection -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-calendar-event"></i> Sélection de l'événement
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Événement</label>
                    <select x-model="selectedEventId" @change="onEventChange" class="form-select">
                        <option value="">-- Sélectionner un événement --</option>
                        <template x-for="event in events" :key="event.id">
                            <option :value="event.id" x-text="`${event.name} - ${new Date(event.date_start).toLocaleDateString('fr-FR')}`"></option>
                        </template>
                    </select>
                </div>
                <div class="col-md-6" x-show="selectedEventId">
                    <label class="form-label">Informations</label>
                    <div class="alert alert-info mb-0">
                        <strong x-text="selectedEvent?.name"></strong><br>
                        <span x-text="`${races.length} course(s) - ${waves.length} vague(s)`"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reader Selection -->
    <div class="card mb-4" x-show="selectedEventId">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-hdd-network"></i> Lecteurs SportLab 2.0</span>
            <span class="badge bg-primary" x-text="`${activeReaders.length} actif(s)`"></span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Ajouter un lecteur SportLab 2.0</label>
                    <div class="input-group">
                        <span class="input-group-text">Lecteur 1</span>
                        <input type="number" x-model="newReaderNumber" class="form-control" placeholder="01 à 99" min="1" max="99">
                        <button @click="addReader" class="btn btn-success btn-lg" :disabled="!newReaderNumber">
                            <i class="bi bi-plus-circle"></i> Ajouter
                        </button>
                    </div>
                    <div class="alert alert-info mt-2 mb-0">
                        <small>
                            <strong>IP = 192.168.10.(150 + numéro)</strong><br>
                            Lecteur 101 → entrez <strong>1</strong> → IP 192.168.10.151<br>
                            Lecteur 120 → entrez <strong>20</strong> → IP 192.168.10.170
                        </small>
                    </div>
                </div>
            </div>

            <!-- Active Readers List -->
            <template x-if="activeReaders.length > 0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>IP Lecteur</th>
                                <th>Statut</th>
                                <th>Détections</th>
                                <th>Dernière activité</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="reader in activeReaders" :key="reader.ip">
                                <tr>
                                    <td><code x-text="reader.ip"></code></td>
                                    <td>
                                        <span class="badge" :class="reader.status === 'active' ? 'bg-success' : 'bg-secondary'" x-text="reader.status === 'active' ? 'Actif' : 'Inactif'"></span>
                                    </td>
                                    <td><span x-text="reader.detectionCount || 0"></span></td>
                                    <td><small x-text="reader.lastActivity || 'Jamais'"></small></td>
                                    <td>
                                        <button @click="removeReader(reader.ip)" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </template>

            <div x-show="activeReaders.length === 0" class="text-center text-muted py-3">
                <i class="bi bi-hdd-network" style="font-size: 2rem;"></i>
                <p class="mb-0">Aucun lecteur configuré</p>
            </div>
        </div>
    </div>

    <!-- Statistics & TOP Départ -->
    <div class="row mb-4" x-show="selectedEventId">
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0" x-text="stats.totalDetections"></h2>
                    <p class="mb-0">Détections totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #3B82F6 0%, #2563eb 100%);">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0" x-text="stats.uniqueParticipants"></h2>
                    <p class="mb-0">Participants uniques</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0" x-text="currentWaveIndex + 1 + '/' + waves.length"></h2>
                    <p class="mb-0">Vague actuelle</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0" x-text="stats.detectionRate"></h2>
                    <p class="mb-0">Détections/min</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Chrono & TOP Button -->
    <div class="card mb-4" x-show="selectedEventId">
        <div class="card-header">
            <i class="bi bi-alarm"></i> Chronomètre et TOP Départ
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6 text-center">
                    <div class="display-1 fw-bold" x-text="chronoDisplay" :class="chronoRunning ? 'text-success' : 'text-muted'"></div>
                    <p class="text-muted mb-0">Chronomètre en direct</p>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2">
                        <button @click="handleTopDepart"
                                class="btn btn-lg"
                                :class="currentWaveIndex < waves.length ? 'btn-success' : 'btn-secondary'"
                                :disabled="currentWaveIndex >= waves.length || !selectedEventId">
                            <i class="bi bi-flag-fill"></i>
                            <span x-show="currentWaveIndex < waves.length" x-text="`TOP DÉPART - Vague ${currentWaveIndex + 1}`"></span>
                            <span x-show="currentWaveIndex >= waves.length">Toutes les vagues démarrées</span>
                        </button>
                        <div x-show="currentWaveIndex > 0" class="alert alert-success mb-0">
                            <strong>Dernière vague démarrée:</strong>
                            <span x-text="`Vague ${currentWaveIndex} - ${lastTopTime}`"></span>
                        </div>
                        <div x-show="currentWaveIndex < waves.length && waves[currentWaveIndex]" class="alert alert-info mb-0">
                            <strong>Prochaine vague:</strong>
                            <span x-text="waves[currentWaveIndex] ? `Vague ${currentWaveIndex + 1} - ${waves[currentWaveIndex].name}` : 'N/A'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Detections Table -->
    <div class="card" x-show="selectedEventId">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul"></i> Dernières détections RFID</span>
            <div>
                <button @click="refreshDetections" class="btn btn-sm btn-outline-primary me-2">
                    <i class="bi bi-arrow-clockwise"></i> Actualiser
                </button>
                <button @click="clearDetections" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> Effacer
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-sm table-hover mb-0">
                    <thead class="sticky-top bg-light">
                        <tr>
                            <th>Heure détection</th>
                            <th>Dossard</th>
                            <th>Nom</th>
                            <th>Sexe</th>
                            <th>Catégorie</th>
                            <th>Temps course</th>
                            <th>Vague</th>
                            <th>Tag RFID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="detections.length === 0">
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Aucune détection pour le moment</p>
                                </td>
                            </tr>
                        </template>
                        <template x-for="detection in detections" :key="detection.id">
                            <tr>
                                <td class="small" x-text="new Date(detection.finish_timestamp).toLocaleTimeString('fr-FR')"></td>
                                <td><span class="badge bg-dark" x-text="detection.entrant.bib_number"></span></td>
                                <td x-text="detection.entrant.name"></td>
                                <td>
                                    <span class="badge" :class="detection.entrant.gender === 'M' ? 'bg-primary' : 'bg-danger'" x-text="detection.entrant.gender"></span>
                                </td>
                                <td><small x-text="detection.entrant.category || '-'"></small></td>
                                <td><strong x-text="detection.finish_time"></strong></td>
                                <td><span class="badge bg-info" x-text="'Vague ' + (detection.entrant.wave || '?')"></span></td>
                                <td><code class="small" x-text="detection.rfid_tag || '-'"></code></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function timingInterface() {
    return {
        // State
        events: [],
        races: [],
        waves: [],
        detections: [],
        activeReaders: [],
        selectedEventId: '',
        selectedEvent: null,
        newReaderNumber: '',
        currentWaveIndex: 0,
        lastTopTime: '',
        currentTime: '',
        chronoDisplay: '00:00:00',
        chronoRunning: false,
        chronoStartTime: null,
        stats: {
            totalDetections: 0,
            uniqueParticipants: 0,
            detectionRate: 0
        },
        pollingInterval: null,
        chronoInterval: null,
        clockInterval: null,

        // Initialize
        async init() {
            await this.loadEvents();
            this.startClock();
            this.updateChrono();
        },

        // Start clock
        startClock() {
            this.updateClock();
            this.clockInterval = setInterval(() => this.updateClock(), 1000);
        },

        updateClock() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('fr-FR');
        },

        // Load events
        async loadEvents() {
            try {
                const response = await axios.get('/events');
                this.events = response.data;
            } catch (error) {
                console.error('Error loading events:', error);
                alert('Erreur lors du chargement des événements');
            }
        },

        // Event change handler
        async onEventChange() {
            if (!this.selectedEventId) {
                this.selectedEvent = null;
                this.races = [];
                this.waves = [];
                this.detections = [];
                this.currentWaveIndex = 0;
                this.stopPolling();
                return;
            }

            this.selectedEvent = this.events.find(e => e.id == this.selectedEventId);
            await this.loadEventData();
        },

        // Load races and waves for selected event
        async loadEventData() {
            try {
                // Load races
                const racesResponse = await axios.get('/races');
                this.races = racesResponse.data.filter(r => r.event_id == this.selectedEventId);

                // Load waves - we'll use entrants to determine waves
                const entrantsResponse = await axios.get('/entrants');
                const eventEntrants = entrantsResponse.data.filter(e => {
                    const raceIds = this.races.map(r => r.id);
                    return raceIds.includes(e.race_id);
                });

                // Group by wave number
                const waveMap = new Map();
                eventEntrants.forEach(entrant => {
                    const waveNum = entrant.wave || 1;
                    if (!waveMap.has(waveNum)) {
                        waveMap.set(waveNum, {
                            wave_number: waveNum,
                            name: `Vague ${waveNum}`,
                            race_id: entrant.race_id,
                            entrants: []
                        });
                    }
                    waveMap.get(waveNum).entrants.push(entrant);
                });

                // Convert to array and sort
                this.waves = Array.from(waveMap.values()).sort((a, b) => a.wave_number - b.wave_number);

                // Reset wave index
                this.currentWaveIndex = 0;

                // Start polling if readers are active
                if (this.activeReaders.length > 0) {
                    this.startPolling();
                }

            } catch (error) {
                console.error('Error loading event data:', error);
                alert('Erreur lors du chargement des données');
            }
        },

        // Add reader
        addReader() {
            if (!this.newReaderNumber) return;

            const ip = `192.168.10.${150 + parseInt(this.newReaderNumber)}`;

            // Check if already exists
            if (this.activeReaders.find(r => r.ip === ip)) {
                alert('Ce lecteur est déjà configuré');
                return;
            }

            this.activeReaders.push({
                ip: ip,
                status: 'active',
                detectionCount: 0,
                lastActivity: null
            });

            this.newReaderNumber = '';

            // Start polling if event selected
            if (this.selectedEventId && !this.pollingInterval) {
                this.startPolling();
            }
        },

        // Remove reader
        removeReader(ip) {
            if (confirm(`Supprimer le lecteur ${ip} ?`)) {
                this.activeReaders = this.activeReaders.filter(r => r.ip !== ip);

                // Stop polling if no readers
                if (this.activeReaders.length === 0) {
                    this.stopPolling();
                }
            }
        },

        // Handle TOP départ
        async handleTopDepart() {
            if (this.currentWaveIndex >= this.waves.length) {
                alert('Toutes les vagues ont déjà été démarrées');
                return;
            }

            const currentWave = this.waves[this.currentWaveIndex];
            const race = this.races.find(r => r.id === currentWave.race_id);

            if (!race) {
                alert('Course introuvable pour cette vague');
                return;
            }

            if (!confirm(`Lancer le TOP départ pour la vague ${this.currentWaveIndex + 1} (${currentWave.name}) ?`)) {
                return;
            }

            try {
                const response = await axios.post(`/races/${race.id}/start`);

                this.lastTopTime = new Date().toLocaleTimeString('fr-FR');

                // Start chrono if first wave
                if (this.currentWaveIndex === 0) {
                    this.chronoStartTime = new Date();
                    this.chronoRunning = true;
                    this.startChrono();
                }

                this.currentWaveIndex++;

                alert(`✅ ${response.data.message}\nVague ${this.currentWaveIndex} - ${race.name}\nHeure: ${this.lastTopTime}`);

            } catch (error) {
                console.error('Error recording TOP:', error);
                alert('Erreur lors de l\'enregistrement du TOP départ');
            }
        },

        // Start chrono
        startChrono() {
            if (this.chronoInterval) clearInterval(this.chronoInterval);
            this.chronoInterval = setInterval(() => this.updateChrono(), 100);
        },

        // Update chrono display
        updateChrono() {
            if (!this.chronoRunning || !this.chronoStartTime) {
                this.chronoDisplay = '00:00:00';
                return;
            }

            const elapsed = Math.floor((new Date() - this.chronoStartTime) / 1000);
            const hours = Math.floor(elapsed / 3600);
            const minutes = Math.floor((elapsed % 3600) / 60);
            const seconds = elapsed % 60;

            this.chronoDisplay = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        },

        // Start polling readers
        startPolling() {
            if (this.pollingInterval) return;

            this.pollReaders(); // Initial poll
            this.pollingInterval = setInterval(() => this.pollReaders(), 2000); // Poll every 2 seconds
        },

        // Stop polling
        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
        },

        // Poll readers for new detections
        async pollReaders() {
            // For now, we'll fetch from our API since we don't have direct reader access
            // In production, this would poll each reader IP
            await this.refreshDetections();
        },

        // Refresh detections
        async refreshDetections() {
            if (!this.selectedEventId) return;

            try {
                // Get all results for races in this event
                const resultsResponse = await axios.get('/results');
                const raceIds = this.races.map(r => r.id);
                const eventResults = resultsResponse.data.filter(r => raceIds.includes(r.race_id));

                // Sort by detection time (newest first)
                this.detections = eventResults.sort((a, b) => {
                    return new Date(b.finish_timestamp || b.created_at) - new Date(a.finish_timestamp || a.created_at);
                }).slice(0, 50); // Limit to 50 most recent

                // Update stats
                this.updateStats();

            } catch (error) {
                console.error('Error refreshing detections:', error);
            }
        },

        // Update statistics
        updateStats() {
            this.stats.totalDetections = this.detections.length;

            const uniqueEntrants = new Set(this.detections.map(d => d.entrant_id));
            this.stats.uniqueParticipants = uniqueEntrants.size;

            // Calculate detection rate (detections per minute in last 5 minutes)
            const fiveMinutesAgo = new Date(Date.now() - 5 * 60 * 1000);
            const recentDetections = this.detections.filter(d => {
                const detectionTime = new Date(d.finish_timestamp || d.created_at);
                return detectionTime >= fiveMinutesAgo;
            });
            this.stats.detectionRate = Math.round(recentDetections.length / 5);
        },

        // Clear detections display
        clearDetections() {
            if (confirm('Effacer l\'affichage des détections ? (Les données restent en base)')) {
                this.detections = [];
                this.stats.totalDetections = 0;
                this.stats.uniqueParticipants = 0;
                this.stats.detectionRate = 0;
            }
        },

        // Cleanup on destroy
        destroy() {
            this.stopPolling();
            if (this.chronoInterval) clearInterval(this.chronoInterval);
            if (this.clockInterval) clearInterval(this.clockInterval);
        }
    }
}
</script>

<style>
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

code {
    background-color: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('chronofront.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH U:\DEV\ats-sport\resources\views/chronofront/timing.blade.php ENDPATH**/ ?>