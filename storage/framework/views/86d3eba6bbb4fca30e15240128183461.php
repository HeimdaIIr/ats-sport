<?php $__env->startSection('title', 'Liste des Participants'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">
            <i class="bi bi-people text-info"></i> Participants
        </h1>
        <a href="<?php echo e(route('chronofront.entrants.import')); ?>" class="btn btn-success">
            <i class="bi bi-upload"></i> Importer CSV
        </a>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Course</label>
                    <select id="filterRace" class="form-select">
                        <option value="">Toutes les courses</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sexe</label>
                    <select id="filterGender" class="form-select">
                        <option value="">Tous</option>
                        <option value="M">Hommes</option>
                        <option value="F">Femmes</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Catégorie</label>
                    <select id="filterCategory" class="form-select">
                        <option value="">Toutes</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Recherche</label>
                    <input type="text" id="searchEntrant" class="form-control" placeholder="Nom, dossard...">
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4" id="statsRow">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-primary" id="statTotal">0</h3>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-info" id="statMen">0</h3>
                    <small class="text-muted">Hommes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-danger" id="statWomen">0</h3>
                    <small class="text-muted">Femmes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h3 class="mb-0 text-success" id="statCategories">0</h3>
                    <small class="text-muted">Catégories</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Dossard</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Sexe</th>
                            <th>Naissance</th>
                            <th>Catégorie</th>
                            <th>Club</th>
                            <th>RFID</th>
                            <th>Course</th>
                        </tr>
                    </thead>
                    <tbody id="entrantsTableBody">
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="pagination" class="mt-3"></div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let allEntrants = [];
let allRaces = [];
let allCategories = [];
const perPage = 50;
let currentPage = 1;

document.addEventListener('DOMContentLoaded', function() {
    loadRaces();
    loadCategories();
    loadEntrants();

    document.getElementById('filterRace').addEventListener('change', filterEntrants);
    document.getElementById('filterGender').addEventListener('change', filterEntrants);
    document.getElementById('filterCategory').addEventListener('change', filterEntrants);
    document.getElementById('searchEntrant').addEventListener('input', filterEntrants);
});

async function loadRaces() {
    try {
        const response = await fetch('/api/races');
        allRaces = await response.json();

        const select = document.getElementById('filterRace');
        allRaces.forEach(race => {
            select.add(new Option(race.name, race.id));
        });
    } catch (error) {
        console.error('Error loading races:', error);
    }
}

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        allCategories = await response.json();

        const select = document.getElementById('filterCategory');
        allCategories.forEach(cat => {
            select.add(new Option(cat.name, cat.id));
        });
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

async function loadEntrants() {
    try {
        const response = await fetch('/api/entrants');
        allEntrants = await response.json();
        updateStats(allEntrants);
        displayEntrants(allEntrants);
    } catch (error) {
        console.error('Error loading entrants:', error);
        document.getElementById('entrantsTableBody').innerHTML = `
            <tr><td colspan="9" class="text-center text-danger">Erreur de chargement</td></tr>
        `;
    }
}

function updateStats(entrants) {
    document.getElementById('statTotal').textContent = entrants.length;
    document.getElementById('statMen').textContent = entrants.filter(e => e.gender === 'M').length;
    document.getElementById('statWomen').textContent = entrants.filter(e => e.gender === 'F').length;

    const uniqueCategories = [...new Set(entrants.filter(e => e.category_id).map(e => e.category_id))];
    document.getElementById('statCategories').textContent = uniqueCategories.length;
}

function filterEntrants() {
    const raceFilter = document.getElementById('filterRace').value;
    const genderFilter = document.getElementById('filterGender').value;
    const categoryFilter = document.getElementById('filterCategory').value;
    const searchTerm = document.getElementById('searchEntrant').value.toLowerCase();

    let filtered = allEntrants;

    if (raceFilter) {
        filtered = filtered.filter(e => e.race_id == raceFilter);
    }

    if (genderFilter) {
        filtered = filtered.filter(e => e.gender === genderFilter);
    }

    if (categoryFilter) {
        filtered = filtered.filter(e => e.category_id == categoryFilter);
    }

    if (searchTerm) {
        filtered = filtered.filter(e =>
            e.lastname.toLowerCase().includes(searchTerm) ||
            e.firstname.toLowerCase().includes(searchTerm) ||
            (e.bib_number && e.bib_number.toString().includes(searchTerm)) ||
            (e.club && e.club.toLowerCase().includes(searchTerm))
        );
    }

    currentPage = 1;
    updateStats(filtered);
    displayEntrants(filtered);
}

function displayEntrants(entrants) {
    const tbody = document.getElementById('entrantsTableBody');

    if (entrants.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">Aucun participant</td></tr>';
        return;
    }

    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const pageEntrants = entrants.slice(start, end);

    tbody.innerHTML = pageEntrants.map(entrant => {
        const race = allRaces.find(r => r.id === entrant.race_id);
        const category = allCategories.find(c => c.id === entrant.category_id);

        return `
            <tr>
                <td><span class="badge bg-secondary">${entrant.bib_number || '-'}</span></td>
                <td class="fw-bold">${entrant.lastname || '-'}</td>
                <td>${entrant.firstname || '-'}</td>
                <td><span class="badge bg-${entrant.gender === 'M' ? 'primary' : 'danger'}">${entrant.gender}</span></td>
                <td class="small">${entrant.birth_date ? new Date(entrant.birth_date).toLocaleDateString('fr-FR') : '-'}</td>
                <td class="small">${category?.name || '-'}</td>
                <td class="small text-muted">${entrant.club || '-'}</td>
                <td class="small"><code>${entrant.rfid_tag || '-'}</code></td>
                <td class="small">${race?.name || '-'}</td>
            </tr>
        `;
    }).join('');

    renderPagination(entrants.length);
}

function renderPagination(total) {
    const totalPages = Math.ceil(total / perPage);
    const paginationDiv = document.getElementById('pagination');

    if (totalPages <= 1) {
        paginationDiv.innerHTML = '';
        return;
    }

    let html = '<nav><ul class="pagination pagination-sm justify-content-center">';

    for (let i = 1; i <= totalPages; i++) {
        html += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>
        `;
    }

    html += '</ul></nav>';
    paginationDiv.innerHTML = html;
}

function changePage(page) {
    currentPage = page;
    filterEntrants();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('chronofront.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH U:\DEV\ats-sport\resources\views/chronofront/entrants.blade.php ENDPATH**/ ?>