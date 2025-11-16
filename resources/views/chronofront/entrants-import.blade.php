@extends('chronofront.layout')

@section('title', 'Import CSV Participants')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h2 mb-4">
                <i class="bi bi-upload text-success"></i> Import CSV Participants
            </h1>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Event Selection Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-calendar-event"></i> Étape 1 : Sélectionner l'événement
                    </h5>
                    <div class="mb-3">
                        <label for="eventSelect" class="form-label">Événement</label>
                        <select id="eventSelect" class="form-select" required>
                            <option value="">-- Choisir un événement --</option>
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Les participants seront associés à cet événement
                        </div>
                    </div>
                </div>
            </div>

            <!-- CSV Upload Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Étape 2 : Importer le fichier CSV
                    </h5>

                    <!-- CSV Template Download -->
                    <div class="alert alert-light border mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-download text-primary me-2"></i>
                            <div class="flex-grow-1">
                                <strong>Besoin d'un modèle ?</strong>
                                <p class="mb-0 small text-muted">Téléchargez le fichier CSV template avec les bonnes en-têtes</p>
                            </div>
                            <a href="/api/import/download-template" class="btn btn-sm btn-outline-primary" download>
                                <i class="bi bi-download"></i> Télécharger
                            </a>
                        </div>
                    </div>

                    <!-- Drag & Drop Zone -->
                    <div id="dropZone" class="border border-2 border-dashed rounded p-5 text-center mb-3"
                         style="cursor: pointer; transition: all 0.3s;">
                        <input type="file" id="csvFileInput" class="d-none" accept=".csv,.txt">
                        <i class="bi bi-cloud-upload display-4 text-muted mb-3"></i>
                        <h5 class="mb-2">Glissez-déposez votre fichier CSV ici</h5>
                        <p class="text-muted mb-3">ou cliquez pour sélectionner un fichier</p>
                        <button type="button" id="selectFileBtn" class="btn btn-primary">
                            <i class="bi bi-folder2-open"></i> Parcourir les fichiers
                        </button>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-check-circle text-success"></i> Format accepté: CSV (.csv, .txt) - Max 10MB
                            </small>
                        </div>
                    </div>

                    <!-- Selected File Info -->
                    <div id="fileInfo" class="d-none mb-3">
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-file-earmark-check text-success me-2"></i>
                            <div class="flex-grow-1">
                                <strong>Fichier sélectionné :</strong>
                                <span id="fileName"></span>
                                <small class="d-block text-muted" id="fileSize"></small>
                            </div>
                            <button type="button" id="removeFileBtn" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-x-circle"></i> Retirer
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="button" id="validateBtn" class="btn btn-outline-secondary" disabled>
                            <i class="bi bi-check-square"></i> Valider le fichier (sans importer)
                        </button>
                        <button type="button" id="importBtn" class="btn btn-success btn-lg" disabled>
                            <i class="bi bi-upload"></i> Importer les participants
                        </button>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div id="progressContainer" class="d-none mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="bi bi-hourglass-split"></i> Import en cours...
                        </h6>
                        <div class="progress" style="height: 25px;">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                 role="progressbar" style="width: 0%">
                                <span id="progressText">0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Import Statistics -->
            <div id="statsContainer" class="d-none">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-check-circle"></i> Import terminé avec succès !
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="mb-0 text-primary" id="statTotal">0</h3>
                                    <small class="text-muted">Lignes totales</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="mb-0 text-success" id="statImported">0</h3>
                                    <small class="text-muted">Importés</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="mb-0 text-info" id="statRaces">0</h3>
                                    <small class="text-muted">Courses créées</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="mb-0 text-danger" id="statErrors">0</h3>
                                    <small class="text-muted">Erreurs</small>
                                </div>
                            </div>
                        </div>

                        <!-- Error Details -->
                        <div id="errorDetails" class="d-none">
                            <h6 class="text-danger mb-2">
                                <i class="bi bi-exclamation-triangle"></i> Détails des erreurs :
                            </h6>
                            <ul id="errorList" class="list-group mb-3"></ul>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('chronofront.entrants.index') }}" class="btn btn-primary">
                                <i class="bi bi-people"></i> Voir les participants
                            </a>
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Importer un autre fichier
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Format Information -->
            <div class="card shadow-sm mt-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle text-info"></i> Format CSV attendu
                    </h6>
                    <p class="small mb-2">Le fichier CSV doit contenir les colonnes suivantes (en-têtes obligatoires) :</p>
                    <code class="d-block p-2 bg-white rounded small">
                        "DOSSARD","NOM","PRENOM","SEXE","NAISSANCE","PARCOURS","IDPARCOURS"
                    </code>
                    <p class="small mt-2 mb-0 text-muted">
                        <strong>Colonnes optionnelles :</strong> LICENCE, CLUB, EQUIPE, EMAIL, TELEPHONE, ADRESSE, CODEPOSTAL, VILLE, PAYS, CAT
                    </p>
                    <hr>
                    <p class="small mb-1"><strong>Notes importantes :</strong></p>
                    <ul class="small mb-0">
                        <li>Format date de naissance : <code>DD/MM/YYYY</code> (ex: 15/03/1985)</li>
                        <li>SEXE : <code>M</code> ou <code>F</code> (ou <code>H</code> qui sera converti en <code>M</code>)</li>
                        <li>Les tags RFID seront générés automatiquement : <code>2000XXX</code> (ex: dossard 1 → 2000001)</li>
                        <li>Les catégories FFA seront calculées automatiquement depuis la date de naissance</li>
                        <li>Un fichier CSV peut contenir plusieurs courses (différenciées par PARCOURS/IDPARCOURS)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const eventSelect = document.getElementById('eventSelect');
    const dropZone = document.getElementById('dropZone');
    const csvFileInput = document.getElementById('csvFileInput');
    const selectFileBtn = document.getElementById('selectFileBtn');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFileBtn = document.getElementById('removeFileBtn');
    const validateBtn = document.getElementById('validateBtn');
    const importBtn = document.getElementById('importBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const statsContainer = document.getElementById('statsContainer');
    const alertContainer = document.getElementById('alertContainer');
    const resetBtn = document.getElementById('resetBtn');

    let selectedFile = null;

    // Load events
    loadEvents();

    function loadEvents() {
        fetch('/api/events')
            .then(response => response.json())
            .then(data => {
                data.data.forEach(event => {
                    const option = document.createElement('option');
                    option.value = event.id;
                    option.textContent = `${event.name} - ${new Date(event.event_date).toLocaleDateString('fr-FR')}`;
                    eventSelect.appendChild(option);
                });
            })
            .catch(error => {
                showAlert('Erreur lors du chargement des événements', 'danger');
                console.error('Error loading events:', error);
            });
    }

    // Event selection handler
    eventSelect.addEventListener('change', function() {
        checkFormValidity();
    });

    // Drag and drop handlers
    dropZone.addEventListener('click', () => csvFileInput.click());
    selectFileBtn.addEventListener('click', () => csvFileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#0d6efd';
        dropZone.style.backgroundColor = '#f8f9fa';
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '';
        dropZone.style.backgroundColor = '';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '';
        dropZone.style.backgroundColor = '';

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelection(files[0]);
        }
    });

    csvFileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelection(e.target.files[0]);
        }
    });

    function handleFileSelection(file) {
        // Validate file
        if (!file.name.match(/\.(csv|txt)$/i)) {
            showAlert('Format de fichier invalide. Veuillez sélectionner un fichier CSV.', 'danger');
            return;
        }

        if (file.size > 10 * 1024 * 1024) { // 10MB
            showAlert('Le fichier est trop volumineux. Taille maximum : 10 MB.', 'danger');
            return;
        }

        selectedFile = file;
        fileName.textContent = file.name;
        fileSize.textContent = `Taille : ${(file.size / 1024).toFixed(2)} KB`;
        fileInfo.classList.remove('d-none');
        checkFormValidity();
    }

    removeFileBtn.addEventListener('click', () => {
        selectedFile = null;
        csvFileInput.value = '';
        fileInfo.classList.add('d-none');
        validateBtn.disabled = true;
        importBtn.disabled = true;
    });

    function checkFormValidity() {
        const isValid = eventSelect.value && selectedFile;
        validateBtn.disabled = !isValid;
        importBtn.disabled = !isValid;
    }

    // Validate CSV
    validateBtn.addEventListener('click', async () => {
        if (!selectedFile) return;

        const formData = new FormData();
        formData.append('csv_file', selectedFile);

        try {
            showAlert('Validation en cours...', 'info');
            const response = await fetch('/api/import/validate-csv', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (response.ok) {
                showAlert(result.message || 'Fichier CSV valide !', 'success');
            } else {
                showAlert(result.message || 'Erreur de validation', 'danger');
            }
        } catch (error) {
            showAlert('Erreur lors de la validation du fichier', 'danger');
            console.error('Validation error:', error);
        }
    });

    // Import CSV
    importBtn.addEventListener('click', async () => {
        if (!selectedFile || !eventSelect.value) return;

        const formData = new FormData();
        formData.append('csv_file', selectedFile);

        try {
            // Show progress
            progressContainer.classList.remove('d-none');
            statsContainer.classList.add('d-none');
            importBtn.disabled = true;
            validateBtn.disabled = true;

            // Simulate progress (since we can't track real progress easily)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 5;
                if (progress <= 90) {
                    updateProgress(progress);
                }
            }, 200);

            const response = await fetch(`/api/events/${eventSelect.value}/import-csv`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            clearInterval(progressInterval);
            updateProgress(100);

            const result = await response.json();

            setTimeout(() => {
                progressContainer.classList.add('d-none');

                if (response.ok) {
                    showStats(result.stats);
                    showAlert(result.message, 'success');
                } else {
                    showAlert(result.message || 'Erreur lors de l\'import', 'danger');
                    importBtn.disabled = false;
                    validateBtn.disabled = false;
                }
            }, 500);

        } catch (error) {
            progressContainer.classList.add('d-none');
            showAlert('Erreur lors de l\'import du fichier', 'danger');
            console.error('Import error:', error);
            importBtn.disabled = false;
            validateBtn.disabled = false;
        }
    });

    function updateProgress(percent) {
        progressBar.style.width = percent + '%';
        progressText.textContent = percent + '%';
    }

    function showStats(stats) {
        document.getElementById('statTotal').textContent = stats.total_rows || 0;
        document.getElementById('statImported').textContent = stats.imported || 0;
        document.getElementById('statRaces').textContent = stats.races_created || 0;
        document.getElementById('statErrors').textContent = stats.errors || 0;

        // Show error details if any
        if (stats.errors > 0 && stats.error_details) {
            const errorList = document.getElementById('errorList');
            errorList.innerHTML = '';
            stats.error_details.forEach(error => {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-danger small';
                li.textContent = error;
                errorList.appendChild(li);
            });
            document.getElementById('errorDetails').classList.remove('d-none');
        }

        statsContainer.classList.remove('d-none');
    }

    function showAlert(message, type) {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    resetBtn.addEventListener('click', () => {
        selectedFile = null;
        csvFileInput.value = '';
        fileInfo.classList.add('d-none');
        statsContainer.classList.add('d-none');
        alertContainer.innerHTML = '';
        validateBtn.disabled = true;
        importBtn.disabled = true;
        updateProgress(0);
    });
});
</script>
@endpush
@endsection
