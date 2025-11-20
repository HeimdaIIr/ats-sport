<?php $__env->startSection('title', 'Catégories FFA'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">
            <i class="bi bi-grid text-info"></i> Catégories FFA
        </h1>
        <button class="btn btn-success" id="initCategoriesBtn">
            <i class="bi bi-download"></i> Initialiser Catégories FFA
        </button>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Catégories</h6>
                    <h3 class="mb-0" id="totalCategories">-</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Catégories Hommes</h6>
                    <h3 class="mb-0 text-primary" id="totalMen">-</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Catégories Femmes</h6>
                    <h3 class="mb-0 text-danger" id="totalWomen">-</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Catégories Mixtes</h6>
                    <h3 class="mb-0 text-info" id="totalMixed">-</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filtrer par sexe</label>
                    <select id="filterGender" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <option value="M">Hommes uniquement</option>
                        <option value="F">Femmes uniquement</option>
                        <option value="">Mixtes</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Recherche</label>
                    <input type="text" id="searchCategory" class="form-control" placeholder="Nom ou code de la catégorie...">
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Catégories -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Nom Complet</th>
                            <th>Sexe</th>
                            <th>Âge Min</th>
                            <th>Âge Max</th>
                            <th>Participants</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let allCategories = [];
let entrantsCount = {};

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadEntrantsCounts();

    document.getElementById('initCategoriesBtn').addEventListener('click', initCategories);
    document.getElementById('filterGender').addEventListener('change', filterCategories);
    document.getElementById('searchCategory').addEventListener('input', filterCategories);
});

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();
        allCategories = data.data || data;
        updateStats();
        displayCategories(allCategories);
    } catch (error) {
        console.error('Error loading categories:', error);
        document.getElementById('categoriesTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4 text-danger">
                    Erreur lors du chargement des catégories
                </td>
            </tr>
        `;
    }
}

async function loadEntrantsCounts() {
    try {
        const response = await fetch('/api/entrants');
        const entrants = await response.json();

        // Compter les participants par catégorie
        entrantsCount = {};
        entrants.forEach(entrant => {
            if (entrant.category_id) {
                entrantsCount[entrant.category_id] = (entrantsCount[entrant.category_id] || 0) + 1;
            }
        });

        // Rafraîchir l'affichage
        displayCategories(getCurrentFilteredCategories());
    } catch (error) {
        console.error('Error loading entrants counts:', error);
    }
}

function updateStats() {
    const total = allCategories.length;
    const men = allCategories.filter(c => c.gender === 'M').length;
    const women = allCategories.filter(c => c.gender === 'F').length;
    const mixed = allCategories.filter(c => !c.gender || c.gender === '').length;

    document.getElementById('totalCategories').textContent = total;
    document.getElementById('totalMen').textContent = men;
    document.getElementById('totalWomen').textContent = women;
    document.getElementById('totalMixed').textContent = mixed;
}

function getCurrentFilteredCategories() {
    const genderFilter = document.getElementById('filterGender').value;
    const searchTerm = document.getElementById('searchCategory').value.toLowerCase();

    let filtered = allCategories;

    if (genderFilter) {
        filtered = filtered.filter(c => c.gender === genderFilter);
    }

    if (searchTerm) {
        filtered = filtered.filter(c =>
            (c.code && c.code.toLowerCase().includes(searchTerm)) ||
            (c.name && c.name.toLowerCase().includes(searchTerm))
        );
    }

    return filtered;
}

function displayCategories(categories) {
    const tbody = document.getElementById('categoriesTableBody');

    if (categories.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="bi bi-grid" style="font-size: 3rem; color: #ddd;"></i>
                    <p class="text-muted mt-3">Aucune catégorie trouvée</p>
                    <button class="btn btn-success btn-sm" id="initCategoriesBtn2">
                        <i class="bi bi-download"></i> Initialiser Catégories FFA
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('initCategoriesBtn2')?.addEventListener('click', initCategories);
        return;
    }

    tbody.innerHTML = categories.map(category => {
        const count = entrantsCount[category.id] || 0;
        const genderBadge = category.gender === 'M'
            ? '<span class="badge bg-primary">M</span>'
            : category.gender === 'F'
            ? '<span class="badge bg-danger">F</span>'
            : '<span class="badge bg-secondary">-</span>';

        return `
            <tr>
                <td><strong>${category.code || '-'}</strong></td>
                <td>${category.name || '-'}</td>
                <td>${genderBadge}</td>
                <td class="text-center">${category.min_age !== null ? category.min_age : '-'}</td>
                <td class="text-center">${category.max_age !== null ? category.max_age : '-'}</td>
                <td class="text-center">
                    ${count > 0 ? `<span class="badge bg-info">${count}</span>` : '<span class="text-muted">0</span>'}
                </td>
            </tr>
        `;
    }).join('');
}

function filterCategories() {
    const filtered = getCurrentFilteredCategories();
    displayCategories(filtered);
}

async function initCategories() {
    if (!confirm('Initialiser toutes les catégories FFA (SE, M0-M9, F0-F9, etc.) ?\n\nCela va créer environ 20 catégories standard.')) {
        return;
    }

    const btn = document.getElementById('initCategoriesBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Initialisation...';

    try {
        const response = await fetch('/api/categories/init-ffa', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            alert(`✅ ${data.message}\n\nCatégories créées : ${data.count || 0}`);
            loadCategories();
        } else {
            alert('❌ ' + (data.message || 'Erreur lors de l\'initialisation'));
        }
    } catch (error) {
        console.error('Error initializing categories:', error);
        alert('❌ Erreur réseau lors de l\'initialisation');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('chronofront.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH U:\DEV\ats-sport\resources\views/chronofront/categories.blade.php ENDPATH**/ ?>