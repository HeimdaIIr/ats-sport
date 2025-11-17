@extends('chronofront.layout')

@section('title', 'Saisie Manuelle')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Panel de Saisie -->
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-keyboard"></i> Saisie Manuelle
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Sélection Course -->
                    <div class="mb-3">
                        <label for="raceSelect" class="form-label">Course</label>
                        <select id="raceSelect" class="form-select" required>
                            <option value="">-- Sélectionner une course --</option>
                        </select>
                    </div>

                    <!-- Sélection Point de Chronométrage -->
                    <div class="mb-3">
                        <label for="timingPointSelect" class="form-label">Point de Chronométrage</label>
                        <select id="timingPointSelect" class="form-select" required disabled>
                            <option value="">-- Sélectionner un point --</option>
                        </select>
                    </div>

                    <hr>

                    <!-- Formulaire de Saisie -->
                    <form id="manualTimingForm">
                        <div class="mb-3">
                            <label for="bibNumberInput" class="form-label fw-bold">
                                Numéro de Dossard
                            </label>
                            <input
                                type="number"
                                class="form-control form-control-lg text-center"
                                id="bibNumberInput"
                                placeholder="Ex: 123"
                                min="1"
                                autocomplete="off"
                                disabled
                                style="font-size: 2rem; font-weight: bold;"
                            >
                            <div class="form-text">Appuyez sur Entrée pour valider</div>
                        </div>

                        <!-- Info Participant -->
                        <div id="participantInfo" class="d-none">
                            <div class="alert alert-info">
                                <strong id="participantName"></strong><br>
                                <small class="text-muted">
                                    <span id="participantGender"></span> -
                                    <span id="participantCategory"></span> -
                                    <span id="participantClub"></span>
                                </small>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-grid gap-2">
                            <button type="submit" id="submitBtn" class="btn btn-success btn-lg" disabled>
                                <i class="bi bi-check-circle"></i> Enregistrer (Entrée)
                            </button>
                            <button type="button" id="clearBtn" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Effacer
                            </button>
                        </div>
                    </form>

                    <hr>

                    <!-- Statistiques -->
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <h4 class="mb-0 text-success" id="statRecorded">0</h4>
                                <small class="text-muted">Enregistrés</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <h4 class="mb-0 text-danger" id="statErrors">0</h4>
                                <small class="text-muted">Erreurs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle text-info"></i> Instructions
                    </h6>
                    <ul class="small mb-0">
                        <li>Sélectionnez la course et le point de chronométrage</li>
                        <li>Entrez le numéro de dossard</li>
                        <li>Appuyez sur <kbd>Entrée</kbd> pour valider</li>
                        <li>Le champ est automatiquement réinitialisé pour la saisie suivante</li>
                        <li>Utilisez <kbd>Échap</kbd> pour effacer le champ</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Panel des Dernières Détections -->
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Dernières Détections
                    </h5>
                    <button id="refreshBtn" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-clockwise"></i> Actualiser
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="sticky-top bg-white">
                                <tr>
                                    <th style="width: 80px;">Heure</th>
                                    <th style="width: 80px;">Dossard</th>
                                    <th>Participant</th>
                                    <th style="width: 60px;">Sexe</th>
                                    <th style="width: 100px;">Type</th>
                                    <th style="width: 80px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="detectionsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> Aucune détection
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const raceSelect = document.getElementById('raceSelect');
    const timingPointSelect = document.getElementById('timingPointSelect');
    const bibNumberInput = document.getElementById('bibNumberInput');
    const participantInfo = document.getElementById('participantInfo');
    const participantName = document.getElementById('participantName');
    const participantGender = document.getElementById('participantGender');
    const participantCategory = document.getElementById('participantCategory');
    const participantClub = document.getElementById('participantClub');
    const submitBtn = document.getElementById('submitBtn');
    const clearBtn = document.getElementById('clearBtn');
    const refreshBtn = document.getElementById('refreshBtn');
    const detectionsTableBody = document.getElementById('detectionsTableBody');
    const statRecorded = document.getElementById('statRecorded');
    const statErrors = document.getElementById('statErrors');
    const manualTimingForm = document.getElementById('manualTimingForm');

    let currentRaceId = null;
    let currentTimingPointId = null;
    let stats = { recorded: 0, errors: 0 };
    let currentParticipant = null;

    // Load races
    loadRaces();

    function loadRaces() {
        fetch('/api/races')
            .then(response => response.json())
            .then(data => {
                data.forEach(race => {
                    const option = document.createElement('option');
                    option.value = race.id;
                    option.textContent = `${race.name} - ${race.event?.name || ''}`;
                    raceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading races:', error);
                showToast('Erreur lors du chargement des courses', 'danger');
            });
    }

    // Race selection
    raceSelect.addEventListener('change', function() {
        currentRaceId = this.value;
        timingPointSelect.innerHTML = '<option value="">-- Sélectionner un point --</option>';
        timingPointSelect.disabled = !currentRaceId;
        bibNumberInput.disabled = true;
        submitBtn.disabled = true;

        if (currentRaceId) {
            loadTimingPoints(currentRaceId);
        }
    });

    function loadTimingPoints(raceId) {
        fetch(`/api/timing-points/race/${raceId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(point => {
                    const option = document.createElement('option');
                    option.value = point.id;
                    option.textContent = `${point.name} (${point.point_type})`;
                    timingPointSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading timing points:', error);
                showToast('Erreur lors du chargement des points', 'danger');
            });
    }

    // Timing point selection
    timingPointSelect.addEventListener('change', function() {
        currentTimingPointId = this.value;
        bibNumberInput.disabled = !currentTimingPointId;
        submitBtn.disabled = !currentTimingPointId;

        if (currentTimingPointId) {
            bibNumberInput.focus();
            loadRecentDetections();
        }
    });

    // Bib number input
    bibNumberInput.addEventListener('input', async function() {
        const bibNumber = parseInt(this.value);

        if (!bibNumber || !currentRaceId) {
            participantInfo.classList.add('d-none');
            currentParticipant = null;
            return;
        }

        // Lookup participant
        try {
            const response = await fetch(`/api/manual-timing/lookup/bib/${bibNumber}/race/${currentRaceId}`);
            const result = await response.json();

            if (response.ok && result.success) {
                currentParticipant = result.entrant;
                participantName.textContent = `${result.entrant.firstname} ${result.entrant.lastname}`;
                participantGender.textContent = result.entrant.gender;
                participantCategory.textContent = result.entrant.category || 'N/A';
                participantClub.textContent = result.entrant.club || 'Aucun club';
                participantInfo.classList.remove('d-none');
            } else {
                participantInfo.classList.add('d-none');
                currentParticipant = null;
            }
        } catch (error) {
            console.error('Error looking up participant:', error);
            participantInfo.classList.add('d-none');
            currentParticipant = null;
        }
    });

    // Keyboard shortcuts
    bibNumberInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            clearForm();
        }
    });

    // Form submission
    manualTimingForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const bibNumber = parseInt(bibNumberInput.value);

        if (!bibNumber || !currentTimingPointId) {
            return;
        }

        try {
            const response = await fetch('/api/manual-timing/record', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    bib_number: bibNumber,
                    timing_point_id: currentTimingPointId
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                stats.recorded++;
                statRecorded.textContent = stats.recorded;
                showToast(`Dossard ${bibNumber} enregistré !`, 'success');
                clearForm();
                loadRecentDetections();
                playBeep();
            } else {
                stats.errors++;
                statErrors.textContent = stats.errors;
                showToast(result.message || 'Erreur lors de l\'enregistrement', 'danger');
            }
        } catch (error) {
            stats.errors++;
            statErrors.textContent = stats.errors;
            console.error('Error recording time:', error);
            showToast('Erreur réseau', 'danger');
        }
    });

    // Clear button
    clearBtn.addEventListener('click', clearForm);

    function clearForm() {
        bibNumberInput.value = '';
        participantInfo.classList.add('d-none');
        currentParticipant = null;
        bibNumberInput.focus();
    }

    // Refresh detections
    refreshBtn.addEventListener('click', loadRecentDetections);

    function loadRecentDetections() {
        if (!currentTimingPointId) return;

        fetch(`/api/manual-timing/timing-point/${currentTimingPointId}/recent?limit=50`)
            .then(response => response.json())
            .then(result => {
                if (result.success && result.detections) {
                    displayDetections(result.detections);
                }
            })
            .catch(error => {
                console.error('Error loading detections:', error);
            });
    }

    function displayDetections(detections) {
        if (detections.length === 0) {
            detectionsTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-inbox"></i> Aucune détection
                    </td>
                </tr>
            `;
            return;
        }

        detectionsTableBody.innerHTML = detections.map(detection => {
            const time = new Date(detection.detection_time);
            const typeClass = detection.detection_type === 'manual' ? 'warning' : 'info';
            const typeIcon = detection.detection_type === 'manual' ? 'keyboard' : 'broadcast';

            return `
                <tr>
                    <td class="small">${time.toLocaleTimeString('fr-FR')}</td>
                    <td class="fw-bold">${detection.bib_number}</td>
                    <td>${detection.name}</td>
                    <td><span class="badge bg-${detection.gender === 'M' ? 'primary' : 'danger'}">${detection.gender}</span></td>
                    <td><span class="badge bg-${typeClass}"><i class="bi bi-${typeIcon}"></i> ${detection.detection_type.toUpperCase()}</span></td>
                    <td>
                        ${detection.detection_type === 'manual' ? `
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteDetection(${detection.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        ` : ''}
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Delete detection (global function)
    window.deleteDetection = async function(detectionId) {
        if (!confirm('Supprimer cette détection ?')) return;

        try {
            const response = await fetch(`/api/manual-timing/detection/${detectionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showToast('Détection supprimée', 'success');
                loadRecentDetections();
            } else {
                showToast('Erreur lors de la suppression', 'danger');
            }
        } catch (error) {
            console.error('Error deleting detection:', error);
            showToast('Erreur réseau', 'danger');
        }
    };

    // Toast notification
    function showToast(message, type) {
        // Simple console log for now, could be replaced with toast library
        console.log(`[${type.toUpperCase()}] ${message}`);

        // You could also use Bootstrap toast or a library like toastr
        alert(message);
    }

    // Play beep sound
    function playBeep() {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.value = 800;
        oscillator.type = 'sine';

        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }

    // Auto-refresh detections every 10 seconds
    setInterval(() => {
        if (currentTimingPointId) {
            loadRecentDetections();
        }
    }, 10000);
});
</script>
@endpush
@endsection