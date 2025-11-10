@extends('layouts.app')

@section('content')
<div class="container">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="color: #2c3e50;">➕ Créer une nouvelle épreuve</h2>
            <a href="{{ route('organizer.dashboard') }}" style="background: #6c757d; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">← Retour</a>
        </div>

        <!-- Tabs Navigation -->
        <div style="background: white; border-radius: 8px 8px 0 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
            <div class="tabs-nav" style="display: flex; background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                <div class="tab active" data-tab="epreuve" style="flex: 1; padding: 15px 20px; text-align: center; cursor: pointer; background: #007bff; color: white; border-right: 1px solid #dee2e6;">
                    <strong>1. Épreuve</strong>
                </div>
                <div class="tab" data-tab="parcours" style="flex: 1; padding: 15px 20px; text-align: center; cursor: pointer; border-right: 1px solid #dee2e6;">
                    <strong>2. Parcours</strong>
                </div>
                <div class="tab" data-tab="contact" style="flex: 1; padding: 15px 20px; text-align: center; cursor: pointer; border-right: 1px solid #dee2e6;">
                    <strong>3. Contact</strong>
                </div>
                <div class="tab" data-tab="reglement" style="flex: 1; padding: 15px 20px; text-align: center; cursor: pointer; border-right: 1px solid #dee2e6;">
                    <strong>4. Règlement</strong>
                </div>
                <div class="tab" data-tab="inscription" style="flex: 1; padding: 15px 20px; text-align: center; cursor: pointer; border-right: 1px solid #dee2e6;">
                    <strong>5. Inscription</strong>
                </div>
                <div class="tab" data-tab="autre" style="flex: 1; padding: 15px 20px; text-align: center; cursor: pointer; border-right: 1px solid #dee2e6;">
                    <strong>6. Autre</strong>
                </div>
                <div class="tab" data-tab="validation" style="flex: 1; padding: 15px 20px; text-align: center; cursor: pointer;">
                    <strong>7. Validation</strong>
                </div>
            </div>
        </div>

        <!-- Onglet Content -->
        <div style="background: white; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); min-height: 500px;">
            
            <!-- Onglet Épreuve -->
            <div class="tab-content active" id="tab-epreuve">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">📋 Informations générales de l'épreuve</h3>
                
                <form id="epreuve-form">
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                        
                        <!-- Colonne gauche -->
                        <div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50;">Nom de l'épreuve *</label>
                                <input type="text" name="name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                                <small style="color: #6c757d;">Le nom qui apparaîtra sur le site</small>
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50;">Description de l'épreuve</label>
                                <textarea name="description" rows="6" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;" placeholder="Décrivez votre épreuve, l'ambiance, les parcours..."></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px; margin-bottom: 20px;">
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50;">Lieu *</label>
                                    <input type="text" name="location" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" placeholder="Ville">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50;">Département *</label>
                                    <input type="text" name="department" required maxlength="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" placeholder="34">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50;">Date de l'épreuve *</label>
                                    <input type="date" name="event_date" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50;">Heure de départ</label>
                                    <input type="time" name="start_time" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" value="09:00">
                                </div>
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50;">Type d'épreuve *</label>
                                <select name="event_type" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                                    <option value="">Choisir le type...</option>
                                    <option value="course">Course à pied / Trail</option>
                                    <option value="vtt">VTT / Cyclisme</option>
                                    <option value="triathlon">Triathlon</option>
                                    <option value="marche">Marche / Randonnée</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>

                        <!-- Colonne droite -->
                        <div>
                            <div style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
                                <h4 style="color: #2c3e50; margin-bottom: 15px;">📸 Affiche de l'épreuve</h4>
                                <div style="border: 2px dashed #dee2e6; padding: 30px; text-align: center; border-radius: 4px; background: white;">
                                    <div style="color: #6c757d; margin-bottom: 10px;">📎</div>
                                    <p style="color: #6c757d; margin: 0;">Glissez votre affiche ici</p>
                                    <p style="color: #6c757d; font-size: 12px; margin: 5px 0 0 0;">ou cliquez pour parcourir</p>
                                    <input type="file" name="poster" accept="image/*" style="display: none;">
                                </div>
                                <small style="color: #6c757d;">Formats acceptés : JPG, PNG (max 2Mo)</small>
                            </div>

                            <div style="background: #e3f2fd; padding: 15px; border-radius: 6px; border-left: 4px solid #2196f3;">
                                <h5 style="color: #1976d2; margin: 0 0 10px 0;">💡 Conseils</h5>
                                <ul style="color: #1976d2; font-size: 13px; margin: 0; padding-left: 20px;">
                                    <li>Choisissez un nom accrocheur</li>
                                    <li>Ajoutez une belle description</li>
                                    <li>L'affiche attire les participants</li>
                                    <li>Vérifiez bien les dates</li>
                                </ul>
                            </div>

                            <div style="background: #fff3e0; padding: 15px; border-radius: 6px; border-left: 4px solid #ff9800; margin-top: 15px;">
                                <h5 style="color: #f57c00; margin: 0 0 10px 0;">⚠️ Important</h5>
                                <p style="color: #f57c00; font-size: 13px; margin: 0;">Ces informations seront visibles par tous les participants. Vous pourrez les modifier plus tard.</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Onglet Parcours -->
            <div class="tab-content" id="tab-parcours" style="display: none;">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">🗺️ Parcours et distances</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px;">
                    
                    <!-- Colonne principale -->
                    <div>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
                            <h4 style="color: #2c3e50; margin-bottom: 15px;">📏 Définir les parcours</h4>
                            <p style="color: #6c757d; margin-bottom: 20px;">Ajoutez les différents parcours proposés aux participants</p>
                            
                            <div id="parcours-list">
                                <!-- Parcours 1 par défaut -->
                                <div class="parcours-item" style="background: white; padding: 20px; border-radius: 6px; border: 1px solid #dee2e6; margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                                        <h5 style="color: #2c3e50; margin: 0;">Parcours 1</h5>
                                        <button type="button" class="remove-parcours" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; font-size: 12px;" onclick="removeParcours(this)">Supprimer</button>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                                        <div>
                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nom du parcours *</label>
                                            <input type="text" name="parcours_name[]" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Ex: Trail 21km">
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Distance (km) *</label>
                                            <input type="number" name="parcours_distance[]" step="0.1" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="21.0">
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Dénivelé (m)</label>
                                            <input type="number" name="parcours_elevation[]" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="500">
                                        </div>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Prix (€)</label>
                                            <input type="number" name="parcours_price[]" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="25.00">
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Places max</label>
                                            <input type="number" name="parcours_max[]" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="500">
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Âge minimum</label>
                                            <input type="number" name="parcours_age_min[]" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="16">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="add-parcours" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-size: 14px;">➕ Ajouter un parcours</button>
                        </div>
                        
                        <!-- Informations complémentaires -->
                        <div style="background: white; padding: 20px; border-radius: 6px; border: 1px solid #dee2e6;">
                            <h4 style="color: #2c3e50; margin-bottom: 15px;">📍 Informations parcours</h4>
                            
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Point de départ</label>
                                <input type="text" name="start_point" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Adresse complète du départ">
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Point d'arrivée</label>
                                <input type="text" name="end_point" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Si différent du départ">
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Description du parcours</label>
                                <textarea name="parcours_description" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Décrivez le parcours, le terrain, les difficultés..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite - Aide -->
                    <div>
                        <div style="background: #e8f5e8; padding: 15px; border-radius: 6px; border-left: 4px solid #28a745; margin-bottom: 15px;">
                            <h5 style="color: #155724; margin: 0 0 10px 0;">✅ Bonnes pratiques</h5>
                            <ul style="color: #155724; font-size: 13px; margin: 0; padding-left: 20px;">
                                <li>Proposez plusieurs distances</li>
                                <li>Indiquez le dénivelé</li>
                                <li>Fixez des prix justes</li>
                                <li>Limitez si nécessaire</li>
                                <li>Vérifiez les âges minimum</li>
                            </ul>
                        </div>
                        
                        <div style="background: #fff3e0; padding: 15px; border-radius: 6px; border-left: 4px solid #ff9800;">
                            <h5 style="color: #f57c00; margin: 0 0 10px 0;">📝 À prévoir</h5>
                            <ul style="color: #f57c00; font-size: 13px; margin: 0; padding-left: 20px;">
                                <li>Traces GPX</li>
                                <li>Balisage terrain</li>
                                <li>Ravitaillements</li>
                                <li>Secours</li>
                                <li>Chronométrage</li>
                            </ul>
                        </div>
                        
                        <div style="background: #f3e5f5; padding: 15px; border-radius: 6px; border-left: 4px solid #9c27b0; margin-top: 15px;">
                            <h5 style="color: #7b1fa2; margin: 0 0 10px 0;">🏃 Exemples</h5>
                            <div style="color: #7b1fa2; font-size: 12px;">
                                <strong>Trail :</strong> 10km, 21km, 42km<br>
                                <strong>VTT :</strong> 30km, 50km, 80km<br>
                                <strong>Rando :</strong> 8km, 15km, 25km
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                let currentTab = 0;
                let parcoursCount = 1;
                const tabs = ['epreuve', 'parcours', 'contact', 'reglement', 'inscription', 'autre', 'validation'];

                function showTab(index) {
                    // Hide all tabs
                    document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
                    document.querySelectorAll('.tab').forEach(tab => {
                        tab.style.background = '#f8f9fa';
                        tab.style.color = '#495057';
                    });
                    
                    // Show current tab
                    document.getElementById('tab-' + tabs[index]).style.display = 'block';
                    document.querySelectorAll('.tab')[index].style.background = '#007bff';
                    document.querySelectorAll('.tab')[index].style.color = 'white';
                    
                    // Update buttons
                    document.getElementById('prev-btn').disabled = index === 0;
                    document.getElementById('next-btn').textContent = index === tabs.length - 1 ? 'Terminer' : 'Suivant →';
                    
                    // Initialize parcours functionality when showing parcours tab
                    if (index === 1) { // Onglet parcours
                        initParcoursFunctionality();
                    }
                }

                function initParcoursFunctionality() {
                    const addButton = document.getElementById('add-parcours');
                    if (addButton && !addButton.dataset.initialized) {
                        addButton.dataset.initialized = 'true';
                        addButton.addEventListener('click', function() {
                            parcoursCount++;
                            const newParcours = document.createElement('div');
                            newParcours.className = 'parcours-item';
                            newParcours.style.cssText = 'background: white; padding: 20px; border-radius: 6px; border: 1px solid #dee2e6; margin-bottom: 15px;';
                            
                            newParcours.innerHTML = `
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <h5 style="color: #2c3e50; margin: 0;">Parcours ${parcoursCount}</h5>
                                    <button type="button" class="remove-parcours" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; font-size: 12px;" onclick="removeParcours(this)">Supprimer</button>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                                    <div>
                                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nom du parcours *</label>
                                        <input type="text" name="parcours_name[]" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Ex: Trail 21km">
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Distance (km) *</label>
                                        <input type="number" name="parcours_distance[]" step="0.1" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="21.0">
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Dénivelé (m)</label>
                                        <input type="number" name="parcours_elevation[]" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="500">
                                    </div>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                                    <div>
                                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Prix (€)</label>
                                        <input type="number" name="parcours_price[]" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="25.00">
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Places max</label>
                                        <input type="number" name="parcours_max[]" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="500">
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Âge minimum</label>
                                        <input type="number" name="parcours_age_min[]" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="16">
                                    </div>
                                </div>
                            `;
                            
                            document.getElementById('parcours-list').appendChild(newParcours);
                        });
                    }
                }

                // Initialize when page loads
                document.addEventListener('DOMContentLoaded', function() {
                    // Tab clicks
                    document.querySelectorAll('.tab').forEach((tab, index) => {
                        tab.addEventListener('click', () => {
                            currentTab = index;
                            showTab(currentTab);
                        });
                    });

                    // Navigation buttons
                    document.getElementById('prev-btn').addEventListener('click', () => {
                        if (currentTab > 0) {
                            currentTab--;
                            showTab(currentTab);
                        }
                    });

                    document.getElementById('next-btn').addEventListener('click', () => {
                        if (currentTab < tabs.length - 1) {
                            currentTab++;
                            showTab(currentTab);
                        } else {
                            alert('Formulaire terminé !');
                        }
                    });
                });

                function removeParcours(button) {
                    if (document.querySelectorAll('.parcours-item').length > 1) {
                        button.closest('.parcours-item').remove();
                    } else {
                        alert('Vous devez garder au moins un parcours !');
                    }
                }
            </script>

            <!-- Onglet Contact -->
            <div class="tab-content" id="tab-contact" style="display: none;">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">📞 Informations de contact</h3>
                <p style="color: #6c757d;">Coordonnées de l'organisateur.</p>
            </div>

            <!-- Onglet Règlement -->
            <div class="tab-content" id="tab-reglement" style="display: none;">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">📋 Règlement de l'épreuve</h3>
                <p style="color: #6c757d;">Règles et conditions de participation.</p>
            </div>

            <!-- Onglet Inscription -->
            <div class="tab-content" id="tab-inscription" style="display: none;">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">💳 Inscription en ligne</h3>
                <p style="color: #6c757d;">Configuration des inscriptions et tarifs.</p>
            </div>

            <!-- Onglet Autre -->
            <div class="tab-content" id="tab-autre" style="display: none;">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">⚙️ Autres paramètres</h3>
                <p style="color: #6c757d;">Paramètres additionnels.</p>
            </div>

            <!-- Onglet Validation -->
            <div class="tab-content" id="tab-validation" style="display: none;">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">✅ Validation et publication</h3>
                <p style="color: #6c757d;">Vérification finale avant publication.</p>
            </div>

        </div>

        <!-- Navigation Buttons -->
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <button id="prev-btn" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;" disabled>← Précédent</button>
            <button id="next-btn" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Suivant →</button>
        </div>
    </div>
</div>

<script>
let currentTab = 0;
const tabs = ['epreuve', 'parcours', 'contact', 'reglement', 'inscription', 'autre', 'validation'];

function showTab(index) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
    document.querySelectorAll('.tab').forEach(tab => {
        tab.style.background = '#f8f9fa';
        tab.style.color = '#495057';
    });
    
    // Show current tab
    document.getElementById('tab-' + tabs[index]).style.display = 'block';
    document.querySelectorAll('.tab')[index].style.background = '#007bff';
    document.querySelectorAll('.tab')[index].style.color = 'white';
    
    // Update buttons
    document.getElementById('prev-btn').disabled = index === 0;
    document.getElementById('next-btn').textContent = index === tabs.length - 1 ? 'Terminer' : 'Suivant →';
}

// Tab clicks
document.querySelectorAll('.tab').forEach((tab, index) => {
    tab.addEventListener('click', () => {
        currentTab = index;
        showTab(currentTab);
    });
});

// Navigation buttons
document.getElementById('prev-btn').addEventListener('click', () => {
    if (currentTab > 0) {
        currentTab--;
        showTab(currentTab);
    }
});

document.getElementById('next-btn').addEventListener('click', () => {
    if (currentTab < tabs.length - 1) {
        currentTab++;
        showTab(currentTab);
    } else {
        alert('Formulaire terminé !');
    }
});
</script>
@endsection