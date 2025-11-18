@extends('layouts.app')

@section('title', 'Créer une épreuve')

@section('content')
<div style="min-height: calc(100vh - 80px); background: #000000; padding: 2rem 0;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 2rem;">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <div>
                <h1 style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #ffffff; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 3px;">
                    CRÉER UNE <span style="color: #0ea5e9;">ÉPREUVE</span>
                </h1>
                <p style="color: #cccccc; font-size: 1.1rem;">Suivez les étapes pour configurer votre événement sportif</p>
            </div>
            <a href="{{ route('organizer.dashboard') }}" style="background: #1a1a1a; color: #cccccc; border: 1px solid #333333; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                ← RETOUR
            </a>
        </div>
        <!-- Formulaire principal -->
        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div style="background: #0c2e1a; border-left: 4px solid #22c55e; padding: 1rem 1.5rem; margin-bottom: 2rem;">
                <p style="color: #22c55e; margin: 0; font-family: 'Oswald', sans-serif;">✓ {{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div style="background: #2e0c0c; border-left: 4px solid #ef4444; padding: 1rem 1.5rem; margin-bottom: 2rem;">
                <p style="color: #ef4444; margin: 0 0 0.5rem 0; font-family: 'Oswald', sans-serif;">⚠ Erreurs détectées :</p>
                <ul style="color: #ef4444; margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('organizer.store') }}" id="create-event-form">
            @csrf


        <!-- Tabs Navigation -->
        <div style="background: #111111; border: 1px solid #333333; overflow: hidden; margin-bottom: 0;">
            <div class="tabs-nav" style="display: flex; background: #1a1a1a;">
                <div class="tab active" data-tab="epreuve" style="flex: 1; padding: 1.5rem; text-align: center; cursor: pointer; background: #0ea5e9; color: #000000; border-right: 1px solid #333333; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    1. ÉPREUVE
                </div>
                <div class="tab" data-tab="parcours" style="flex: 1; padding: 1.5rem; text-align: center; cursor: pointer; color: #cccccc; border-right: 1px solid #333333; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    2. PARCOURS
                </div>
                <div class="tab" data-tab="contact" style="flex: 1; padding: 1.5rem; text-align: center; cursor: pointer; color: #cccccc; border-right: 1px solid #333333; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    3. CONTACT
                </div>
                <div class="tab" data-tab="reglement" style="flex: 1; padding: 1.5rem; text-align: center; cursor: pointer; color: #cccccc; border-right: 1px solid #333333; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    4. RÈGLEMENT
                </div>
                <div class="tab" data-tab="inscription" style="flex: 1; padding: 1.5rem; text-align: center; cursor: pointer; color: #cccccc; border-right: 1px solid #333333; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    5. INSCRIPTION
                </div>
                <div class="tab" data-tab="autre" style="flex: 1; padding: 1.5rem; text-align: center; cursor: pointer; color: #cccccc; border-right: 1px solid #333333; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    6. AUTRE
                </div>
                <div class="tab" data-tab="validation" style="flex: 1; padding: 1.5rem; text-align: center; cursor: pointer; color: #cccccc; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    7. VALIDATION
                </div>
            </div>
        </div>

        <!-- Tabs Content -->
        <div style="background: #111111; border: 1px solid #333333; border-top: none; padding: 3rem; min-height: 600px;">
            
            <!-- Tab 1: Épreuve -->
            <div class="tab-content active" id="tab-epreuve">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
                    <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                    <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        INFORMATIONS GÉNÉRALES
                    </h3>
                </div>
                
                    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 4rem; align-items: start;">
                        
                        <!-- Colonne gauche -->
                        <div>
                            <div style="margin-bottom: 2rem;">
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Nom de l'épreuve *</label>
                                <input type="text" name="name" required style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                                <small style="color: #cccccc; font-size: 0.9rem; margin-top: 0.5rem; display: block;">Le nom qui apparaîtra sur le site</small>
                            </div>

                            <div style="margin-bottom: 2rem;">
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Description de l'épreuve</label>
                                <textarea name="description" rows="6" style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; resize: vertical; transition: all 0.2s ease;" placeholder="Décrivez votre épreuve, l'ambiance, les parcours..."></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Lieu *</label>
                                    <input type="text" name="location" required style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;" placeholder="Ville">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Département *</label>
                                    <input type="text" name="department" required maxlength="3" style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;" placeholder="34">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Date de l'épreuve *</label>
                                    <input type="date" name="event_date" required style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Heure de départ</label>
                                    <input type="time" name="start_time" style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;" value="09:00">
                                </div>
                            </div>

                            <div style="margin-bottom: 2rem;">
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Type d'épreuve *</label>
                                <select name="event_type" required style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
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
                            <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ AFFICHE DE L'ÉPREUVE</h4>
                                <div style="border: 2px dashed #333333; padding: 3rem; text-align: center; background: #111111; transition: all 0.2s ease;">
                                    <div style="font-size: 3rem; color: #0ea5e9; margin-bottom: 1rem;">■</div>
                                    <p style="color: #cccccc; margin: 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">Glissez votre affiche ici</p>
                                    <p style="color: #666666; font-size: 0.9rem; margin: 0.5rem 0 0 0;">ou cliquez pour parcourir</p>
                                    <input type="file" name="poster" accept="image/*" style="display: none;">
                                </div>
                                <small style="color: #cccccc; font-size: 0.9rem; margin-top: 1rem; display: block;">Formats acceptés : JPG, PNG (max 2Mo)</small>
                            </div>

                            <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1.5rem; margin-bottom: 1.5rem;">
                                <h5 style="color: #0ea5e9; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ CONSEILS</h5>
                                <ul style="color: #cccccc; font-size: 0.9rem; margin: 0; padding-left: 1.5rem; line-height: 1.6;">
                                    <li>Choisissez un nom accrocheur et mémorable</li>
                                    <li>Ajoutez une description détaillée</li>
                                    <li>L'affiche attire les participants</li>
                                    <li>Vérifiez bien les dates et horaires</li>
                                </ul>
                            </div>

                            <div style="background: #2e1a0c; border-left: 4px solid #f59e0b; padding: 1.5rem;">
                                <h5 style="color: #f59e0b; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ IMPORTANT</h5>
                                <p style="color: #cccccc; font-size: 0.9rem; margin: 0; line-height: 1.6;">Ces informations seront visibles par tous les participants. Vous pourrez les modifier plus tard dans votre dashboard.</p>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Tab 2: Parcours -->
            <div class="tab-content" id="tab-parcours" style="display: none;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
                    <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                    <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        PARCOURS ET DISTANCES
                    </h3>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 4rem;">
                    
                    <!-- Colonne principale -->
                    <div>
                        <!-- Définir les parcours -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ DÉFINIR LES PARCOURS</h4>
                            <p style="color: #cccccc; margin-bottom: 2rem;">Ajoutez les différents parcours proposés aux participants</p>
                            
                            <div id="parcours-list">
                                <!-- Parcours 1 par défaut -->
                                <div class="parcours-item" style="background: #111111; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                                        <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px;">■ PARCOURS 1</h5>
                                        <button type="button" class="remove-parcours" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;" onclick="removeParcours(this)">
                                            SUPPRIMER
                                        </button>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                        <div>
                                            <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Nom du parcours *</label>
                                            <input type="text" name="parcours_name[]" required style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="Ex: Trail 21km">
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Distance (km) *</label>
                                            <input type="number" name="parcours_distance[]" step="0.1" required style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="21.0">
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Dénivelé (m)</label>
                                            <input type="number" name="parcours_elevation[]" style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="500">
                                        </div>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                                        <div>
                                            <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Places max</label>
                                            <input type="number" name="parcours_max[]" style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="500">
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Âge minimum</label>
                                            <input type="number" name="parcours_age_min[]" style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="16">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="add-parcours" style="background: #22c55e; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1rem; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                                ➕ AJOUTER UN PARCOURS
                            </button>
                        </div>
                        
                        <!-- Informations complémentaires -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ INFORMATIONS PARCOURS</h4>
                            
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Point de départ</label>
                                <input type="text" name="start_point" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="Adresse complète du départ">
                            </div>
                            
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Point d'arrivée</label>
                                <input type="text" name="end_point" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="Si différent du départ">
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Description du parcours</label>
                                <textarea name="parcours_description" rows="4" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; resize: vertical; transition: all 0.2s ease;" placeholder="Décrivez le parcours, le terrain, les difficultés..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite - Aide et conseils -->
                    <div>
                        <div style="background: #0c2e1a; border-left: 4px solid #22c55e; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #22c55e; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ BONNES PRATIQUES</h5>
                            <ul style="color: #cccccc; font-size: 0.9rem; margin: 0; padding-left: 1.5rem; line-height: 1.6;">
                                <li>Proposez plusieurs distances pour tous niveaux</li>
                                <li>Indiquez précisément le dénivelé</li>
                                <li>Limitez les places si nécessaire</li>
                                <li>Vérifiez les âges minimum requis</li>
                                <li>Donnez des noms de parcours explicites</li>
                            </ul>
                        </div>
                        
                        <div style="background: #2e1a0c; border-left: 4px solid #f59e0b; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #f59e0b; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ À PRÉVOIR</h5>
                            <ul style="color: #cccccc; font-size: 0.9rem; margin: 0; padding-left: 1.5rem; line-height: 1.6;">
                                <li>Traces GPX des parcours</li>
                                <li>Balisage sur le terrain</li>
                                <li>Points de ravitaillement</li>
                                <li>Équipes de secours</li>
                                <li>Système de chronométrage</li>
                            </ul>
                        </div>
                        
                        <div style="background: #2e0c2e; border-left: 4px solid #a855f7; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #a855f7; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ EXEMPLES</h5>
                            <div style="color: #cccccc; font-size: 0.9rem; line-height: 1.6;">
                                <div style="margin-bottom: 0.5rem;"><strong style="color: #a855f7;">Trail :</strong> 10km, 21km, 42km</div>
                                <div style="margin-bottom: 0.5rem;"><strong style="color: #a855f7;">VTT :</strong> 30km, 50km, 80km</div>
                                <div><strong style="color: #a855f7;">Rando :</strong> 8km, 15km, 25km</div>
                            </div>
                        </div>

                        <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1.5rem;">
                            <h5 style="color: #0ea5e9; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">💰 TARIFS</h5>
                            <p style="color: #cccccc; font-size: 0.9rem; margin: 0; line-height: 1.6;">
                                Les tarifs de chaque parcours seront configurés dans l'onglet <strong>Inscription</strong> avec les tarifs progressifs par période.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Contact -->
            <div class="tab-content" id="tab-contact" style="display: none;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
                    <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                    <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        INFORMATIONS DE CONTACT
                    </h3>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 4rem;">
                    
                    <!-- Colonne principale -->
                    <div>
                        <!-- Organisateur principal -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ ORGANISATEUR PRINCIPAL</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Nom de l'organisateur *</label>
                                    <input type="text" name="organizer_name" required style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="Nom du club ou organisation">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Type d'organisation</label>
                                    <select name="organizer_type" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;">
                                        <option value="">Sélectionner...</option>
                                        <option value="club">Club sportif</option>
                                        <option value="association">Association</option>
                                        <option value="entreprise">Entreprise</option>
                                        <option value="collectivite">Collectivité</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div style="margin-bottom: 2rem;">
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Description de l'organisateur</label>
                                <textarea name="organizer_description" rows="3" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; resize: vertical; transition: all 0.2s ease;" placeholder="Présentez votre organisation, son histoire, ses valeurs..."></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Site web</label>
                                    <input type="url" name="organizer_website" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="https://votre-site.com">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Facebook / Instagram</label>
                                    <input type="text" name="organizer_social" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="@votre_page">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact principal -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ PERSONNE DE CONTACT</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Nom *</label>
                                    <input type="text" name="contact_name" required style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="Prénom NOM">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Fonction</label>
                                    <input type="text" name="contact_role" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="Président, Organisateur, etc.">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Email *</label>
                                    <input type="email" name="contact_email" required style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="contact@exemple.com">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Téléphone</label>
                                    <input type="tel" name="contact_phone" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="06 12 34 56 78">
                                </div>
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Adresse postale</label>
                                <textarea name="contact_address" rows="3" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; resize: vertical; transition: all 0.2s ease;" placeholder="Adresse complète de l'organisateur"></textarea>
                            </div>
                        </div>

                        <!-- Contacts supplémentaires -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 1px;">■ CONTACTS SPÉCIALISÉS</h4>
                                <span style="background: #6b7280; color: #ffffff; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">OPTIONNEL</span>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Contact inscriptions</label>
                                    <input type="email" name="contact_registration" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="inscriptions@exemple.com">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Contact presse</label>
                                    <input type="email" name="contact_press" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="presse@exemple.com">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Contact partenaires</label>
                                    <input type="email" name="contact_partners" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="partenaires@exemple.com">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Contact technique</label>
                                    <input type="email" name="contact_technical" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="technique@exemple.com">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite - Conseils -->
                    <div>
                        <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #0ea5e9; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ CONSEILS</h5>
                            <ul style="color: #cccccc; font-size: 0.9rem; margin: 0; padding-left: 1.5rem; line-height: 1.6;">
                                <li>Renseignez une adresse email dédiée à l'événement</li>
                                <li>Ajoutez un numéro de téléphone accessible</li>
                                <li>Présentez brièvement votre organisation</li>
                                <li>Les contacts spécialisés améliorent le professionnalisme</li>
                            </ul>
                        </div>
                        
                        <div style="background: #2e1a0c; border-left: 4px solid #f59e0b; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #f59e0b; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ RÉACTIVITÉ</h5>
                            <ul style="color: #cccccc; font-size: 0.9rem; margin: 0; padding-left: 1.5rem; line-height: 1.6;">
                                <li>Répondez rapidement aux demandes</li>
                                <li>Créez une FAQ pour les questions courantes</li>
                                <li>Prévoyez des créneaux d'accueil téléphonique</li>
                                <li>Surveillez vos réseaux sociaux</li>
                            </ul>
                        </div>

                        <div style="background: #2e0c2e; border-left: 4px solid #a855f7; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #a855f7; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ PROTECTION DONNÉES</h5>
                            <p style="color: #cccccc; font-size: 0.9rem; margin: 0; line-height: 1.6;">
                                Ces informations seront visibles publiquement. Assurez-vous de respecter le RGPD et n'exposez que les contacts professionnels nécessaires.
                            </p>
                        </div>

                        <div style="background: #0c2e1a; border-left: 4px solid #22c55e; padding: 1.5rem;">
                            <h5 style="color: #22c55e; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ COMMUNICATION</h5>
                            <div style="color: #cccccc; font-size: 0.9rem; line-height: 1.6;">
                                <div style="margin-bottom: 0.5rem;"><strong style="color: #22c55e;">Avant :</strong> Info pratiques, parcours</div>
                                <div style="margin-bottom: 0.5rem;"><strong style="color: #22c55e;">Pendant :</strong> Urgences, logistique</div>
                                <div><strong style="color: #22c55e;">Après :</strong> Résultats, photos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Règlement -->
            <div class="tab-content" id="tab-reglement" style="display: none;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
                    <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                    <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        RÈGLEMENT DE L'ÉPREUVE
                    </h3>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 4rem;">
                    
                    <!-- Colonne principale -->
                    <div>
                        <!-- Upload du règlement -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 3rem; margin-bottom: 2rem; text-align: center;">
                            <div style="border: 2px dashed #333333; padding: 4rem; background: #111111; transition: all 0.2s ease;" id="pdf-upload-zone">
                                <div style="font-size: 4rem; color: #0ea5e9; margin-bottom: 1.5rem;">📄</div>
                                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">IMPORTEZ VOTRE RÈGLEMENT</h4>
                                <p style="color: #cccccc; margin-bottom: 2rem; font-size: 1.1rem;">Glissez votre fichier PDF ici ou cliquez pour parcourir</p>
                                
                                <input type="file" name="reglement_pdf" accept=".pdf" style="display: none;" id="pdf-input">
                                <button type="button" onclick="document.getElementById('pdf-input').click()" style="background: #0ea5e9; color: #000000; border: none; padding: 1.5rem 3rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.1rem; cursor: pointer; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1rem;">
                                    CHOISIR UN FICHIER PDF
                                </button>
                                
                                <div style="color: #666666; font-size: 0.9rem;">
                                    <div>Formats acceptés : PDF uniquement</div>
                                    <div>Taille maximum : 10 Mo</div>
                                </div>
                            </div>
                            
                            <!-- Zone d'affichage du fichier uploadé -->
                            <div id="pdf-preview" style="display: none; background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 2rem; margin-top: 2rem; text-align: left;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="color: #0ea5e9; font-family: 'Oswald', sans-serif; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">■ RÈGLEMENT IMPORTÉ</div>
                                        <div id="pdf-filename" style="color: #ffffff; font-size: 1.1rem; margin-bottom: 0.5rem;"></div>
                                        <div id="pdf-filesize" style="color: #cccccc; font-size: 0.9rem;"></div>
                                    </div>
                                    <button type="button" onclick="removePdf()" style="background: #ef4444; color: white; border: none; padding: 0.75rem 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;">
                                        SUPPRIMER
                                    </button>
                                </div>
                            </div>
                        </div>

                        
                        
                        <!-- Informations complémentaires -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ INFORMATIONS COMPLÉMENTAIRES</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Âge minimum</label>
                                    <input type="number" name="min_age" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="16">
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Certificat médical</label>
                                    <select name="medical_certificate" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;">
                                        <option value="required">Obligatoire</option>
                                        <option value="recommended">Recommandé</option>
                                        <option value="not_required">Non requis</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Licence sportive</label>
                                    <select name="sport_license" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;">
                                        <option value="not_required">Non requise</option>
                                        <option value="recommended">Recommandée</option>
                                        <option value="required">Obligatoire</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Assurance</label>
                                    <select name="insurance" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;">
                                        <option value="included">Incluse dans l'inscription</option>
                                        <option value="personal">Assurance personnelle</option>
                                        <option value="license">Via licence sportive</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Résumé des points clés du règlement</label>
                                <textarea name="rules_summary" rows="4" style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; resize: vertical; transition: all 0.2s ease;" placeholder="Résumez en quelques lignes les points essentiels :
- Équipement obligatoire
- Conditions de sécurité
- Points importants du règlement"></textarea>
                                <small style="color: #cccccc; font-size: 0.9rem; display: block; margin-top: 0.5rem;">Ce résumé apparaîtra sur la page d'inscription</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite - Conseils -->
                    <div>
                        <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #0ea5e9; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">📄 CONSEILS PDF</h5>
                            <ul style="color: #cccccc; font-size: 0.9rem; margin: 0; padding-left: 1.5rem; line-height: 1.6;">
                                <li>Fichier PDF lisible et bien structuré</li>
                                <li>Maximum 10 pages recommandé</li>
                                <li>Police claire et suffisamment grande</li>
                                <li>Numérotation des articles</li>
                                <li>Informations de contact visibles</li>
                            </ul>
                        </div>
                        
                        <div style="background: #2e1a0c; border-left: 4px solid #f59e0b; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #f59e0b; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">⚖️ OBLIGATIONS</h5>
                            <ul style="color: #cccccc; font-size: 0.9rem; margin: 0; padding-left: 1.5rem; line-height: 1.6;">
                                <li>Déclaration en préfecture</li>
                                <li>Assurance responsabilité civile</li>
                                <li>Plan de secours si nécessaire</li>
                                <li>Respect du code du sport</li>
                            </ul>
                        </div>

                        <div style="background: #0c2e1a; border-left: 4px solid #22c55e; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h5 style="color: #22c55e; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">✅ CONTENU TYPE</h5>
                            <div style="color: #cccccc; font-size: 0.9rem; line-height: 1.6;">
                                <div style="margin-bottom: 0.5rem;"><strong style="color: #22c55e;">• Article 1 :</strong> Organisation</div>
                                <div style="margin-bottom: 0.5rem;"><strong style="color: #22c55e;">• Article 2 :</strong> Parcours</div>
                                <div style="margin-bottom: 0.5rem;"><strong style="color: #22c55e;">• Article 3 :</strong> Équipement</div>
                                <div style="margin-bottom: 0.5rem;"><strong style="color: #22c55e;">• Article 4 :</strong> Sécurité</div>
                                <div><strong style="color: #22c55e;">• Article 5 :</strong> Responsabilité</div>
                            </div>
                        </div>

                        <div style="background: #2e0c2e; border-left: 4px solid #a855f7; padding: 1.5rem;">
                            <h5 style="color: #a855f7; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">🔒 SÉCURITÉ</h5>
                            <p style="color: #cccccc; font-size: 0.9rem; margin: 0; line-height: 1.6;">
                                Votre règlement sera accessible publiquement. Vérifiez qu'il ne contient aucune information confidentielle.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 5: Inscription -->
                <div class="tab-content" id="tab-inscription" style="display: none;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
                        <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                        <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                            INSCRIPTION EN LIGNE
                        </h3>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 280px; gap: 3rem; align-items: start;">
                        
                        <!-- Colonne principale -->
                        <div>
                            <!-- Tarifs des parcours -->
                            <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ TARIFS DES PARCOURS</h4>
                                <p style="color: #cccccc; margin-bottom: 2rem;">Configurez les tarifs pour chaque parcours défini précédemment</p>
                                
                                <div id="pricing-parcours-list">
                                    <!-- Les parcours seront synchronisés ici depuis l'onglet 2 -->
                                    <div style="background: #111111; border: 1px solid #333333; padding: 2rem; margin-bottom: 1.5rem;">
                                        <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ PARCOURS PRINCIPAL</h5>
                                        
                                        <div style="display: grid; grid-template-columns: 1fr auto; gap: 2rem; align-items: end;">
                                            <div>
                                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Prix de base (€)</label>
                                                <input type="number" name="parcours_base_price[]" step="0.01" style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="25.00">
                                            </div>
                                            <button type="button" style="background: #0ea5e9; color: #000000; border: none; padding: 1.25rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;" onclick="openPricingModal(this)">
                                                TARIFS PROGRESSIFS
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Périodes d'inscription -->
                            <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ PÉRIODES D'INSCRIPTION</h4>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                    <div>
                                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Ouverture des inscriptions *</label>
                                        <input type="datetime-local" name="registration_start" required style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;">
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Fermeture des inscriptions *</label>
                                        <input type="datetime-local" name="registration_end" required style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;">
                                    </div>
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Période de reversement des inscriptions *</label>
                                    <select name="payment_schedule" required style="width: 100%; padding: 1.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;">
                                        <option value="">Choisir la période...</option>
                                        <option value="after_event">Après l'épreuve</option>
                                        <option value="quarterly">Trimestriel</option>
                                        <option value="monthly">Mensuel</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Visibilité et intégration -->
                            <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ VISIBILITÉ ET INTÉGRATION</h4>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                    <div style="background: #111111; border: 1px solid #333333; padding: 1.5rem;">
                                        <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">■ CALENDRIER ATS-SPORT</h5>
                                        <label style="display: flex; align-items: center; gap: 1rem; color: #cccccc; cursor: pointer;">
                                            <input type="radio" name="visible_calendar" value="yes" style="width: 20px; height: 20px; accent-color: #0ea5e9;">
                                            <span style="font-weight: 600;">OUI</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 1rem; color: #cccccc; cursor: pointer; margin-top: 0.75rem;">
                                            <input type="radio" name="visible_calendar" value="no" style="width: 20px; height: 20px; accent-color: #0ea5e9;">
                                            <span style="font-weight: 600;">NON</span>
                                        </label>
                                    </div>
                                    
                                    <div style="background: #111111; border: 1px solid #333333; padding: 1.5rem;">
                                        <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">■ IFRAME INTÉGRATION</h5>
                                        <label style="display: flex; align-items: center; gap: 1rem; color: #cccccc; cursor: pointer;">
                                            <input type="radio" name="iframe_integration" value="yes" style="width: 20px; height: 20px; accent-color: #0ea5e9;">
                                            <span style="font-weight: 600;">OUI</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 1rem; color: #cccccc; cursor: pointer; margin-top: 0.75rem;">
                                            <input type="radio" name="iframe_integration" value="no" style="width: 20px; height: 20px; accent-color: #0ea5e9;">
                                            <span style="font-weight: 600;">NON</span>
                                        </label>
                                    </div>
                                </div>

                                <div id="iframe-details" style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1.5rem; display: none;">
                                    <h6 style="color: #0ea5e9; margin: 0 0 1rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">■ DÉTAILS IFRAME</h6>
                                    <div>
                                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">URL de votre site web</label>
                                        <input type="url" name="website_url" style="width: 100%; padding: 1rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="https://votre-site.com">
                                    </div>
                                </div>
                            </div>

                            <!-- Questions supplémentaires -->
                            <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                                    <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 1px;">■ QUESTIONS SUPPLÉMENTAIRES</h4>
                                    <button type="button" id="add-question" style="background: #22c55e; color: #000000; border: none; padding: 0.75rem 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                                        ➕ AJOUTER
                                    </button>
                                </div>
                                <p style="color: #cccccc; margin-bottom: 2rem; font-size: 0.9rem;">Ajoutez des options payantes ou des questions spécifiques</p>
                                
                                <div id="questions-list">
                                    <!-- Questions prédéfinies -->
                                    
                                    <!-- Question T-Shirt -->
                                    <div class="question-item" style="background: #111111; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 1.5rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                            <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ T-SHIRT TECHNIQUE</h5>
                                            <div style="display: flex; gap: 1rem; align-items: center;">
                                                <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                                    <input type="radio" name="tshirt_enabled" value="yes" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                                    <span style="font-weight: 600; font-size: 0.9rem;">OUI</span>
                                                </label>
                                                <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                                    <input type="radio" name="tshirt_enabled" value="no" checked style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                                    <span style="font-weight: 600; font-size: 0.9rem;">NON</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="question-details" style="display: none;">
                                            <div style="margin-bottom: 1rem;">
                                                <input type="number" name="tshirt_price" step="0.01" style="width: 120px; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="15.00">
                                                <span style="color: #cccccc; margin-left: 0.5rem;">€</span>
                                            </div>
                                            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.5rem;">
                                                <div style="background: #1a1a1a; padding: 0.5rem; text-align: center;">
                                                    <label style="color: #0ea5e9; font-size: 0.8rem; display: block;">XS</label>
                                                    <input type="number" name="tshirt_xs" min="0" style="width: 100%; padding: 0.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; text-align: center; font-size: 0.8rem;" placeholder="0">
                                                </div>
                                                <div style="background: #1a1a1a; padding: 0.5rem; text-align: center;">
                                                    <label style="color: #0ea5e9; font-size: 0.8rem; display: block;">S</label>
                                                    <input type="number" name="tshirt_s" min="0" style="width: 100%; padding: 0.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; text-align: center; font-size: 0.8rem;" placeholder="0">
                                                </div>
                                                <div style="background: #1a1a1a; padding: 0.5rem; text-align: center;">
                                                    <label style="color: #0ea5e9; font-size: 0.8rem; display: block;">M</label>
                                                    <input type="number" name="tshirt_m" min="0" style="width: 100%; padding: 0.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; text-align: center; font-size: 0.8rem;" placeholder="0">
                                                </div>
                                                <div style="background: #1a1a1a; padding: 0.5rem; text-align: center;">
                                                    <label style="color: #0ea5e9; font-size: 0.8rem; display: block;">L</label>
                                                    <input type="number" name="tshirt_l" min="0" style="width: 100%; padding: 0.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; text-align: center; font-size: 0.8rem;" placeholder="0">
                                                </div>
                                                <div style="background: #1a1a1a; padding: 0.5rem; text-align: center;">
                                                    <label style="color: #0ea5e9; font-size: 0.8rem; display: block;">XL</label>
                                                    <input type="number" name="tshirt_xl" min="0" style="width: 100%; padding: 0.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; text-align: center; font-size: 0.8rem;" placeholder="0">
                                                </div>
                                                <div style="background: #1a1a1a; padding: 0.5rem; text-align: center;">
                                                    <label style="color: #0ea5e9; font-size: 0.8rem; display: block;">XXL</label>
                                                    <input type="number" name="tshirt_xxl" min="0" style="width: 100%; padding: 0.25rem; background: #111111; border: 1px solid #333333; color: #ffffff; text-align: center; font-size: 0.8rem;" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Question Assurance -->
                                    <div class="question-item" style="background: #111111; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 1.5rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                            <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ ASSURANCE ANNULATION</h5>
                                            <div style="display: flex; gap: 1rem; align-items: center;">
                                                <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                                    <input type="radio" name="insurance_enabled" value="yes" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                                    <span style="font-weight: 600; font-size: 0.9rem;">OUI</span>
                                                </label>
                                                <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                                    <input type="radio" name="insurance_enabled" value="no" checked style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                                    <span style="font-weight: 600; font-size: 0.9rem;">NON</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="question-details" style="display: none;">
                                            <div style="display: grid; grid-template-columns: 120px 1fr; gap: 1rem; align-items: start;">
                                                <div>
                                                    <input type="number" name="insurance_price" step="0.01" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="5.00">
                                                    <span style="color: #cccccc; font-size: 0.8rem; display: block; margin-top: 0.25rem;">€</span>
                                                </div>
                                                <textarea name="insurance_description" rows="2" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; resize: vertical;" placeholder="Couvre l'annulation pour maladie, blessure..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Question Livraison -->
                                    <div class="question-item" style="background: #111111; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 1.5rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                            <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ LIVRAISON DOSSARD</h5>
                                            <div style="display: flex; gap: 1rem; align-items: center;">
                                                <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                                    <input type="radio" name="delivery_enabled" value="yes" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                                    <span style="font-weight: 600; font-size: 0.9rem;">OUI</span>
                                                </label>
                                                <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                                    <input type="radio" name="delivery_enabled" value="no" checked style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                                    <span style="font-weight: 600; font-size: 0.9rem;">NON</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="question-details" style="display: none;">
                                            <div style="display: grid; grid-template-columns: 120px 1fr; gap: 1rem; align-items: start;">
                                                <div>
                                                    <input type="number" name="delivery_price" step="0.01" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="8.00">
                                                    <span style="color: #cccccc; font-size: 0.8rem; display: block; margin-top: 0.25rem;">€</span>
                                                </div>
                                                <textarea name="delivery_description" rows="2" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; resize: vertical;" placeholder="Livraison sous 5-7 jours ouvrés..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #333333;">
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Message d'accueil inscription</label>
                                    <textarea name="registration_message" rows="3" style="width: 100%; padding: 1rem; background: #111111; border: 1px solid #333333; color: #ffffff; resize: vertical;" placeholder="Message affiché au début du formulaire d'inscription..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Colonne droite - Guide -->
                        <div style="position: sticky; top: 1rem;">
                            <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1rem; margin-bottom: 1rem;">
                                <h5 style="color: #0ea5e9; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">💰 REVERSEMENTS</h5>
                                <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                    <div style="margin-bottom: 0.25rem;"><strong style="color: #0ea5e9;">Après :</strong> Paiement unique</div>
                                    <div style="margin-bottom: 0.25rem;"><strong style="color: #0ea5e9;">Trimestriel :</strong> Tous les 3 mois</div>
                                    <div><strong style="color: #0ea5e9;">Mensuel :</strong> Chaque mois</div>
                                </div>
                            </div>
                            
                            <div style="background: #2e1a0c; border-left: 4px solid #f59e0b; padding: 1rem; margin-bottom: 1rem;">
                                <h5 style="color: #f59e0b; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">📅 CALENDRIER</h5>
                                <p style="color: #cccccc; font-size: 0.85rem; margin: 0; line-height: 1.5;">
                                    Visible sur ats-sport.com et référencé par Google.
                                </p>
                            </div>

                            <div style="background: #0c2e1a; border-left: 4px solid #22c55e; padding: 1rem; margin-bottom: 1rem;">
                                <h5 style="color: #22c55e; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">🔗 IFRAME</h5>
                                <p style="color: #cccccc; font-size: 0.85rem; margin: 0; line-height: 1.5;">
                                    Intégrez les inscriptions sur votre site.
                                </p>
                            </div>

                            <div style="background: #2e0c2e; border-left: 4px solid #a855f7; padding: 1rem; margin-bottom: 1rem;">
                                <h5 style="color: #a855f7; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">⚡ TARIFS</h5>
                                <p style="color: #cccccc; font-size: 0.85rem; margin: 0; line-height: 1.5;">
                                    Tarifs progressifs pour encourager les inscriptions précoces.
                                </p>
                            </div>

                            <div style="background: #0c2e2e; border-left: 4px solid #06b6d4; padding: 1rem;">
                                <h5 style="color: #06b6d4; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">➕ OPTIONS</h5>
                                <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                    <div style="margin-bottom: 0.25rem;"><strong style="color: #06b6d4;">T-Shirt :</strong> Tailles et stock</div>
                                    <div style="margin-bottom: 0.25rem;"><strong style="color: #06b6d4;">Assurance :</strong> Annulation</div>
                                    <div><strong style="color: #06b6d4;">Livraison :</strong> Dossard domicile</div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- FIN de la grille - IMPORTANT ! -->
                </div>

            <!-- Tab 6: Autre -->
            <div class="tab-content" id="tab-autre" style="display: none;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
                    <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                    <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        AUTRES PARAMÈTRES
                    </h3>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 280px; gap: 3rem; align-items: start;">
                    
                    <!-- Colonne principale -->
                    <div>
                        
                        <!-- Médias et Communication -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ MÉDIAS ET COMMUNICATION</h4>
                            
                            <!-- Photos de l'événement -->
                            <div style="margin-bottom: 2rem;">
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Photos de l'événement</label>
                                <div style="border: 2px dashed #333333; padding: 2rem; text-align: center; background: #111111; margin-bottom: 1rem;">
                                    <div style="font-size: 2rem; color: #0ea5e9; margin-bottom: 1rem;">📸</div>
                                    <p style="color: #cccccc; margin: 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Glissez vos photos ici</p>
                                    <p style="color: #666666; font-size: 0.8rem; margin: 0.5rem 0 0 0;">Éditions précédentes, parcours, ambiance... (max 5 photos)</p>
                                    <input type="file" name="event_photos[]" accept="image/*" multiple style="display: none;">
                                </div>
                            </div>

                            <!-- Vidéo et réseaux -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Vidéo de présentation</label>
                                    <input type="url" name="presentation_video" style="width: 100%; padding: 1rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="https://youtube.com/watch?v=...">
                                    <small style="color: #cccccc; font-size: 0.8rem; display: block; margin-top: 0.5rem;">YouTube, Vimeo, etc.</small>
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Hashtags officiels</label>
                                    <input type="text" name="official_hashtags" style="width: 100%; padding: 1rem; background: #111111; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="#TrailMontpellier2024 #Running">
                                    <small style="color: #cccccc; font-size: 0.8rem; display: block; margin-top: 0.5rem;">Séparés par des espaces</small>
                                </div>
                            </div>

                            <!-- Réseaux sociaux -->
                            <div style="background: #111111; border: 1px solid #333333; padding: 1.5rem;">
                                <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ RÉSEAUX SOCIAUX</h5>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <input type="url" name="facebook_link" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="Facebook (URL complète)">
                                    <input type="url" name="instagram_link" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="Instagram (URL complète)">
                                    <input type="url" name="strava_link" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="Strava (URL complète)">
                                    <input type="url" name="website_link" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="Site web officiel">
                                </div>
                            </div>
                        </div>

                        <!-- Partenaires et Sponsors -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                                <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 1px;">■ PARTENAIRES ET SPONSORS</h4>
                                <button type="button" id="add-partner" style="background: #22c55e; color: #000000; border: none; padding: 0.75rem 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                                    ➕ AJOUTER
                                </button>
                            </div>

                            <div id="partners-list">
                                <!-- Partenaire exemple -->
                                <div class="partner-item" style="background: #111111; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 1.5rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                        <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ PARTENAIRE 1</h5>
                                        <button type="button" onclick="removePartner(this)" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; cursor: pointer; text-transform: uppercase;">SUPPRIMER</button>
                                    </div>
                                    
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                        <input type="text" name="partner_name[]" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="Nom du partenaire">
                                        <input type="url" name="partner_website[]" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="Site web">
                                    </div>
                                    
                                    <div style="border: 2px dashed #333333; padding: 1.5rem; text-align: center; background: #1a1a1a;">
                                        <div style="font-size: 1.5rem; color: #0ea5e9; margin-bottom: 0.5rem;">🏢</div>
                                        <p style="color: #cccccc; margin: 0; font-size: 0.9rem;">Logo du partenaire</p>
                                        <input type="file" name="partner_logo[]" accept="image/*" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Services et Commodités -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ SERVICES ET COMMODITÉS</h4>
                            
                            <!-- Services de base -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <!-- Parking -->
                                <div style="background: #111111; border: 1px solid #333333; padding: 1.5rem;">
                                    <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ PARKING</h5>
                                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="radio" name="parking_type" value="gratuit" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                            <span style="font-size: 0.9rem;">Gratuit</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="radio" name="parking_type" value="payant" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                            <span style="font-size: 0.9rem;">Payant</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="radio" name="parking_type" value="none" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                            <span style="font-size: 0.9rem;">Pas de parking</span>
                                        </label>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <input type="number" name="parking_places" style="width: 100%; padding: 0.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 0.9rem;" placeholder="Nombre places">
                                        <input type="number" name="parking_price" step="0.01" style="width: 100%; padding: 0.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 0.9rem;" placeholder="Prix (€)">
                                    </div>
                                    <input type="text" name="parking_address" style="width: 100%; padding: 0.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 0.9rem;" placeholder="Adresse du parking">
                                </div>

                                <!-- Vestiaires -->
                                <div style="background: #111111; border: 1px solid #333333; padding: 1.5rem;">
                                    <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ VESTIAIRES/DOUCHES</h5>
                                    <div style="display: flex; gap: 2rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="checkbox" name="vestiaires_available" value="1" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                            <span style="font-size: 0.9rem;">Vestiaires</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="checkbox" name="douches_available" value="1" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                            <span style="font-size: 0.9rem;">Douches</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Autres services -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                <!-- Consignes -->
                                <div style="background: #111111; border: 1px solid #333333; padding: 1.5rem;">
                                    <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ CONSIGNES</h5>
                                    <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="radio" name="consignes_enabled" value="yes" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                            <span style="font-size: 0.9rem;">Oui</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="radio" name="consignes_enabled" value="no" checked style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                            <span style="font-size: 0.9rem;">Non</span>
                                        </label>
                                    </div>
                                    <input type="number" name="consignes_price" step="0.01" style="width: 100%; padding: 0.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 0.9rem;" placeholder="Prix (€)">
                                </div>

                                <!-- Ravitaillements -->
                                <div style="background: #111111; border: 1px solid #333333; padding: 1.5rem;">
                                    <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ RAVITAILLEMENTS</h5>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                        <input type="number" name="ravitos_number" style="width: 100%; padding: 0.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 0.9rem;" placeholder="Nombre">
                                        <select name="ravitos_type" style="width: 100%; padding: 0.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 0.9rem;">
                                            <option value="">Type...</option>
                                            <option value="solide_liquide">Solide + Liquide</option>
                                            <option value="liquide">Liquide uniquement</option>
                                            <option value="solide">Solide uniquement</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chronométrage et Résultats -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ CHRONOMÉTRAGE ET RÉSULTATS</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Chronométrage par ATS</label>
                                    <div style="display: flex; gap: 1rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="radio" name="chronometry_ats" value="yes" style="width: 18px; height: 18px; accent-color: #0ea5e9;">
                                            <span style="font-weight: 600;">OUI</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                            <input type="radio" name="chronometry_ats" value="no" style="width: 18px; height: 18px; accent-color: #0ea5e9;">
                                            <span style="font-weight: 600;">NON</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Délai publication résultats</label>
                                    <select name="results_delay" style="width: 100%; padding: 1rem; background: #111111; border: 1px solid #333333; color: #ffffff;">
                                        <option value="">Sélectionner...</option>
                                        <option value="1h">Dans l'heure</option>
                                        <option value="2h">Sous 2 heures</option>
                                        <option value="24h">Sous 24h</option>
                                        <option value="48h">Sous 48h</option>
                                    </select>
                                </div>
                            </div>

                            <div style="background: #111111; border: 1px solid #333333; padding: 1.5rem;">
                                <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ RÉCOMPENSES</h5>
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                        <input type="checkbox" name="reward_1er_scratch" value="1" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                        <span style="font-size: 0.9rem;">1er au scratch</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                        <input type="checkbox" name="reward_3_scratch" value="1" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                        <span style="font-size: 0.9rem;">3 premiers au scratch</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                        <input type="checkbox" name="reward_1er_categories" value="1" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                        <span style="font-size: 0.9rem;">1er par catégorie</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                        <input type="checkbox" name="reward_3_categories" value="1" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                        <span style="font-size: 0.9rem;">3 premiers par catégorie</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                        <input type="checkbox" name="reward_equipes" value="1" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                        <span style="font-size: 0.9rem;">Par équipes</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                                        <input type="checkbox" name="reward_club_represente" value="1" style="width: 16px; height: 16px; accent-color: #0ea5e9;">
                                        <span style="font-size: 0.9rem;">Club le plus représenté</span>
                                    </label>
                                </div>
                                <div style="margin-top: 1rem;">
                                    <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem;">Autre récompense</label>
                                    <input type="text" name="reward_other" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 0.9rem;" placeholder="Précisez...">
                                </div>
                            </div>
                        </div>

                        <!-- Informations Pratiques -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ INFORMATIONS PRATIQUES</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Navettes/Transport</label>
                                    <textarea name="transport_info" rows="3" style="width: 100%; padding: 1rem; background: #111111; border: 1px solid #333333; color: #ffffff; resize: vertical;" placeholder="Transport depuis gare, aéroport, navettes..."></textarea>
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Hébergement</label>
                                    <textarea name="accommodation_info" rows="3" style="width: 100%; padding: 1rem; background: #111111; border: 1px solid #333333; color: #ffffff; resize: vertical;" placeholder="Hôtels partenaires, camping, tarifs préférentiels..."></textarea>
                                </div>
                            </div>

                            <div style="margin-top: 2rem;">
                                <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Restauration sur site</label>
                                <textarea name="food_info" rows="3" style="width: 100%; padding: 1rem; background: #111111; border: 1px solid #333333; color: #ffffff; resize: vertical;" placeholder="Food trucks, buvette, repas d'après-course, produits locaux..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite - Guide -->
                    <div style="position: sticky; top: 1rem;">
                        <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1rem; margin-bottom: 1rem;">
                            <h5 style="color: #0ea5e9; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">📸 MÉDIAS</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Photos d'ambiance augmentent les inscriptions</div>
                                <div>Vidéo = +30% de visibilité</div>
                            </div>
                        </div>
                        
                        <div style="background: #2e1a0c; border-left: 4px solid #f59e0b; padding: 1rem; margin-bottom: 1rem;">
                            <h5 style="color: #f59e0b; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">🤝 PARTENAIRES</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Logos visibles sur la page événement</div>
                                <div>Niveaux de sponsoring personnalisables</div>
                            </div>
                        </div>

                        <div style="background: #0c2e1a; border-left: 4px solid #22c55e; padding: 1rem; margin-bottom: 1rem;">
                            <h5 style="color: #22c55e; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">⚙️ SERVICES</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Services visibles sur page inscription</div>
                                <div>Info pratiques = moins d'emails</div>
                            </div>
                        </div>

                        <div style="background: #2e0c2e; border-left: 4px solid #a855f7; padding: 1rem; margin-bottom: 1rem;">
                            <h5 style="color: #a855f7; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">🏆 RÉSULTATS</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Chronométrage pro recommandé</div>
                                <div>Publication rapide = satisfaction</div>
                            </div>
                        </div>

                        <div style="background: #0c2e2e; border-left: 4px solid #06b6d4; padding: 1rem;">
                            <h5 style="color: #06b6d4; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">📍 PRATIQUE</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Infos transport essentielles</div>
                                <div>Faciliter l'accès = plus de participants</div>
                            </div>
                        </div>
                    </div>
                </div> <!-- FIN de la grille -->
            </div>

            <script>
            // Fonctions pour les partenaires
            let partnerCount = 1;

            function addPartner() {
                partnerCount++;
                const partnersList = document.getElementById('partners-list');
                
                const partnerDiv = document.createElement('div');
                partnerDiv.className = 'partner-item';
                partnerDiv.style.cssText = 'background: #111111; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 1.5rem;';
                
                partnerDiv.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ PARTENAIRE ${partnerCount}</h5>
                        <button type="button" onclick="removePartner(this)" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; cursor: pointer; text-transform: uppercase;">SUPPRIMER</button>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <input type="text" name="partner_name[]" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="Nom du partenaire">
                        <input type="url" name="partner_website[]" style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff;" placeholder="Site web">
                    </div>
                    
                    <div style="border: 2px dashed #333333; padding: 1.5rem; text-align: center; background: #1a1a1a;">
                        <div style="font-size: 1.5rem; color: #0ea5e9; margin-bottom: 0.5rem;">🏢</div>
                        <p style="color: #cccccc; margin: 0; font-size: 0.9rem;">Logo du partenaire</p>
                        <input type="file" name="partner_logo[]" accept="image/*" style="display: none;">
                    </div>
                `;
                
                partnersList.appendChild(partnerDiv);
            }

            function removePartner(button) {
                const partnerItem = button.closest('.partner-item');
                if (document.querySelectorAll('.partner-item').length > 1) {
                    partnerItem.remove();
                } else {
                    alert('Vous devez garder au moins un partenaire !');
                }
            }

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                const addPartnerBtn = document.getElementById('add-partner');
                if (addPartnerBtn) {
                    addPartnerBtn.addEventListener('click', addPartner);
                }
            });
            </script>

            <!-- Tab 7: Validation -->
            <div class="tab-content" id="tab-validation" style="display: none;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;">
                    <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                    <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                        VALIDATION ET PUBLICATION
                    </h3>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 280px; gap: 3rem; align-items: start;">
                    
                    <!-- Colonne principale -->
                    <div>
                        
                        <!-- Résumé de l'événement -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ RÉSUMÉ DE VOTRE ÉVÉNEMENT</h4>
                            
                            <div style="background: #111111; border: 1px solid #333333; padding: 2rem;">
                                <div style="display: grid; grid-template-columns: auto 1fr; gap: 2rem; margin-bottom: 2rem;">
                                    <!-- Affiche miniature -->
                                    <div style="width: 120px; height: 160px; background: #1a1a1a; border: 2px dashed #333333; display: flex; align-items: center; justify-content: center;">
                                        <div style="text-align: center; color: #666666;">
                                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📄</div>
                                            <div style="font-size: 0.8rem;">Affiche</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Infos principales -->
                                    <div>
                                        <div style="margin-bottom: 1.5rem;">
                                            <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0 0 0.5rem 0; text-transform: uppercase; letter-spacing: 1px; font-size: 1rem;">NOM DE L'ÉPREUVE</h5>
                                            <div id="summary-event-name" style="color: #ffffff; font-size: 1.2rem; font-weight: 600;">-</div>
                                        </div>
                                        
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
                                            <div>
                                                <h6 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0 0 0.5rem 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">DATE</h6>
                                                <div id="summary-event-date" style="color: #ffffff; font-size: 1.1rem;">-</div>
                                            </div>
                                            <div>
                                                <h6 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0 0 0.5rem 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">LIEU</h6>
                                                <div id="summary-event-location" style="color: #ffffff; font-size: 1.1rem;">-</div>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <h6 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0 0 0.5rem 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">PARCOURS</h6>
                                            <div id="summary-parcours-count" style="color: #ffffff; font-size: 1.1rem;">-</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Liste des parcours -->
                                <div style="background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem;">
                                    <h6 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">■ DÉTAIL DES PARCOURS</h6>
                                    <div id="summary-parcours-list" style="display: grid; gap: 0.75rem;">
                                        <!-- Parcours seront ajoutés dynamiquement -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Options de validation -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ OPTIONS DE PUBLICATION</h4>
                            
                            <!-- Mode brouillon -->
                            <div style="background: #111111; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                    <input type="checkbox" name="save_as_draft" id="save-draft" style="width: 20px; height: 20px; accent-color: #f59e0b;">
                                    <h5 style="font-family: 'Oswald', sans-serif; color: #f59e0b; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 1rem;">■ ENREGISTRER COMME BROUILLON</h5>
                                </div>
                                <p style="color: #cccccc; margin: 0; font-size: 0.9rem; line-height: 1.5;">
                                    Votre événement sera sauvegardé et accessible dans votre dashboard administrateur, mais <strong>ne sera pas visible</strong> sur le calendrier public et les inscriptions ne seront <strong>pas ouvertes</strong>. Vous pourrez le modifier et le publier plus tard.
                                </p>
                            </div>

                            <!-- Publication immédiate -->
                            <div style="background: #111111; border: 1px solid #333333; padding: 2rem;">
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                    <input type="checkbox" name="publish_now" id="publish-now" style="width: 20px; height: 20px; accent-color: #22c55e;">
                                    <h5 style="font-family: 'Oswald', sans-serif; color: #22c55e; margin: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 1rem;">■ PUBLIER IMMÉDIATEMENT</h5>
                                </div>
                                <p style="color: #cccccc; margin: 0; font-size: 0.9rem; line-height: 1.5;">
                                    Votre événement sera immédiatement <strong>visible sur le calendrier</strong> ATS-Sport.com et les <strong>inscriptions seront ouvertes</strong> selon les dates que vous avez configurées.
                                </p>
                            </div>
                        </div>

                        <!-- Conditions d'utilisation -->
                        <div style="background: #1a1a1a; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ CONDITIONS D'UTILISATION</h4>
                            
                            <div style="background: #111111; border: 1px solid #333333; padding: 2rem; margin-bottom: 1.5rem;">
                                <div style="max-height: 300px; overflow-y: auto; padding-right: 1rem;">
                                    <div style="color: #cccccc; font-size: 0.9rem; line-height: 1.6;">
                                        <h6 style="color: #0ea5e9; font-family: 'Oswald', sans-serif; text-transform: uppercase; margin-bottom: 1rem;">Conditions générales d'utilisation des services Pointcourse</h6>
                                        
                                        <p style="margin-bottom: 1rem;"><strong>Les présentes conditions générales régissent les relations entre les organisateurs d'événement sportifs (les organisateurs) et le service dédié de gestion des inscriptions en ligne (l'application) mise à disposition des organisateurs par la SAS Pointcourse.</strong></p>
                                        
                                        <h6 style="color: #0ea5e9; margin: 1.5rem 0 1rem 0;">1. Accès aux services ats-sport.com</h6>
                                        <p style="margin-bottom: 1rem;">En acceptant les présentes conditions générales, vous attestez de votre position de représentant légal de l'événement sportif, certifiez l'exactitude des informations transmises et attestez de la conformité de vos actes aux lois françaises.</p>
                                        
                                        <h6 style="color: #0ea5e9; margin: 1.5rem 0 1rem 0;">3. Conditions financières</h6>
                                        <p style="margin-bottom: 1rem;"><strong>Tarifs au 1er Janvier 2025 :</strong></p>
                                        <ul style="margin: 0 0 1rem 1.5rem; padding: 0;">
                                            <li>0,75€ TTC par transaction si frais < 8€</li>
                                            <li>1€ TTC par transaction si frais < 30€</li>
                                            <li>1,5€ TTC par transaction si frais < 50€</li>
                                            <li>2€ TTC par transaction si frais < 80€</li>
                                            <li>2,5€ TTC par transaction si frais < 100€</li>
                                            <li>2,5% par transaction si frais > 100€</li>
                                        </ul>
                                        <p style="margin-bottom: 1rem;"><em>Majoration des frais à hauteur de 3% sur tous les achats annexes + 3% par remboursement</em></p>
                                        
                                        <h6 style="color: #0ea5e9; margin: 1.5rem 0 1rem 0;">4. Remboursement</h6>
                                        <p style="margin-bottom: 1rem;">L'organisateur est seul décisionnaire quant à l'acceptation d'un remboursement. Les frais perçus par Pointcourse ne pourront faire l'objet d'aucun remboursement.</p>
                                        
                                        <h6 style="color: #0ea5e9; margin: 1.5rem 0 1rem 0;">5. Protection des données</h6>
                                        <p style="margin-bottom: 1rem;">Conformément au RGPD, les informations personnelles recueillies font l'objet d'un traitement sécurisé. Vous disposez d'un droit d'accès et de rectification.</p>
                                        
                                        <div style="background: #2e1a0c; border-left: 4px solid #f59e0b; padding: 1rem; margin: 1.5rem 0;">
                                            <p style="margin: 0; font-size: 0.85rem; color: #f59e0b;"><strong>📄 Conditions complètes :</strong> <a href="#" onclick="openFullConditions()" style="color: #f59e0b; text-decoration: underline;">Cliquez ici pour consulter l'intégralité des conditions d'utilisation</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acceptation obligatoire -->
                            <div style="background: #0c2e1a; border-left: 4px solid #22c55e; padding: 1.5rem;">
                                <label style="display: flex; align-items: start; gap: 1rem; color: #ffffff; cursor: pointer;">
                                    <input type="checkbox" name="accept_conditions" id="accept-conditions" required style="width: 20px; height: 20px; accent-color: #22c55e; margin-top: 0.25rem; flex-shrink: 0;">
                                    <span style="font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                                        ✓ J'AI LU ET J'ACCEPTE LES CONDITIONS GÉNÉRALES D'UTILISATION DES SERVICES POINTCOURSE
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Validation finale -->
                        <div style="background: #0c2e3e; border: 2px solid #0ea5e9; padding: 3rem; text-align: center;">
                            <div style="margin-bottom: 2rem;">
                                <h4 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 2px; font-size: 1.5rem;">■ FINALISER LA CRÉATION</h4>
                                <p style="color: #ffffff; margin: 0; font-size: 1.1rem; line-height: 1.5;">
                                    Vérifiez que toutes les informations sont correctes avant de valider.<br>
                                    Vous pourrez modifier votre événement depuis votre dashboard.
                                </p>
                            </div>
                            
                            <div style="display: flex; gap: 2rem; justify-content: center; align-items: center;">
                                <button type="submit" name="action" value="draft" id="save-draft-btn" style="background: #f59e0b; color: #000000; border: none; padding: 1.5rem 3rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.1rem; cursor: pointer; text-transform: uppercase; letter-spacing: 2px; transition: all 0.2s ease;">
                                    💾 ENREGISTRER BROUILLON
                                </button>
                                
                                <button type="submit" name="action" value="publish" id="publish-btn" style="background: #22c55e; color: #000000; border: none; padding: 1.5rem 3rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.1rem; cursor: pointer; text-transform: uppercase; letter-spacing: 2px; transition: all 0.2s ease;">
                                    🚀 PUBLIER L'ÉVÉNEMENT
                                </button>
                            </div>
                            
                            <div style="margin-top: 1.5rem;">
                                <small style="color: #cccccc; font-size: 0.9rem;">
                                    ⚠️ Vous devez accepter les conditions d'utilisation et choisir au moins une option de publication
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite - Guide -->
                    <div style="position: sticky; top: 1rem;">
                        <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1rem; margin-bottom: 1rem;">
                            <h5 style="color: #0ea5e9; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">🔍 VÉRIFICATION</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Relisez attentivement le résumé</div>
                                <div>Vérifiez les dates et parcours</div>
                            </div>
                        </div>
                        
                        <div style="background: #2e1a0c; border-left: 4px solid #f59e0b; padding: 1rem; margin-bottom: 1rem;">
                            <h5 style="color: #f59e0b; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">💾 BROUILLON</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Sauvegarde sans publication</div>
                                <div>Modification possible avant publication</div>
                            </div>
                        </div>

                        <div style="background: #0c2e1a; border-left: 4px solid #22c55e; padding: 1rem; margin-bottom: 1rem;">
                            <h5 style="color: #22c55e; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">🚀 PUBLICATION</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Événement visible immédiatement</div>
                                <div>Inscriptions ouvertes selon vos dates</div>
                            </div>
                        </div>

                        <div style="background: #2e0c2e; border-left: 4px solid #a855f7; padding: 1rem;">
                            <h5 style="color: #a855f7; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">📋 CONDITIONS</h5>
                            <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.5;">
                                <div style="margin-bottom: 0.25rem;">Lecture obligatoire</div>
                                <div>Tarification transparente</div>
                            </div>
                        </div>
                    </div>
                </div> <!-- FIN de la grille -->
            </div>

            <script>
            // Variables pour la validation
            let conditionsAccepted = false;
            let publishOptionSelected = false;

            // Fonction pour mettre à jour le résumé
            function updateSummary() {
                // Nom de l'événement
                const eventName = document.querySelector('input[name="name"]');
                if (eventName && eventName.value) {
                    document.getElementById('summary-event-name').textContent = eventName.value;
                }
                
                // Date de l'événement
                const eventDate = document.querySelector('input[name="event_date"]');
                if (eventDate && eventDate.value) {
                    const date = new Date(eventDate.value);
                    const options = { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    };
                    document.getElementById('summary-event-date').textContent = date.toLocaleDateString('fr-FR', options);
                }
                
                // Lieu
                const location = document.querySelector('input[name="location"]');
                const department = document.querySelector('input[name="department"]');
                if (location && location.value) {
                    const locationText = location.value + (department && department.value ? ` (${department.value})` : '');
                    document.getElementById('summary-event-location').textContent = locationText;
                }
                
                // Parcours
                const parcours = document.querySelectorAll('#tab-parcours .parcours-item');
                const parcoursCount = parcours.length;
                document.getElementById('summary-parcours-count').textContent = `${parcoursCount} parcours configuré${parcoursCount > 1 ? 's' : ''}`;
                
                // Détail des parcours
                const parcoursList = document.getElementById('summary-parcours-list');
                parcoursList.innerHTML = '';
                
                parcours.forEach((parcours, index) => {
                    const nameInput = parcours.querySelector('input[name="parcours_name[]"]');
                    const distanceInput = parcours.querySelector('input[name="parcours_distance[]"]');
                    
                    const name = nameInput && nameInput.value ? nameInput.value : `Parcours ${index + 1}`;
                    const distance = distanceInput && distanceInput.value ? distanceInput.value + ' km' : '';
                    
                    const parcoursDiv = document.createElement('div');
                    parcoursDiv.style.cssText = 'background: #111111; padding: 1rem; border-left: 3px solid #0ea5e9; display: flex; justify-content: space-between; align-items: center;';
                    parcoursDiv.innerHTML = `
                        <span style="color: #ffffff; font-weight: 600;">${name}</span>
                        <span style="color: #0ea5e9; font-size: 0.9rem;">${distance}</span>
                    `;
                    parcoursList.appendChild(parcoursDiv);
                });
            }

            // Fonction pour ouvrir les conditions complètes
            function openFullConditions() {
                const modal = document.createElement('div');
                modal.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.9); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 2rem;';
                
                modal.innerHTML = \`
                    <div style="background: #111111; border: 1px solid #333333; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
                        <div style="background: #1a1a1a; padding: 1.5rem; border-bottom: 1px solid #333333; position: sticky; top: 0; z-index: 10;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin: 0; text-transform: uppercase;">CONDITIONS GÉNÉRALES D'UTILISATION</h3>
                                <button onclick="this.closest('.modal').remove()" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; cursor: pointer;">FERMER</button>
                            </div>
                        </div>
                        <div style="padding: 2rem; color: #cccccc; line-height: 1.6; font-size: 0.9rem;">
                            <!-- Ici on mettrait le texte complet des conditions -->
                            <p><strong>Conditions générales d'utilisation des services Pointcourse</strong></p>
                            <p>Les présentes conditions générales régissent les relations entre les organisateurs d'événement sportifs (les organisateurs) et le service dédié de gestion des inscriptions en ligne (l'application) mise à disposition des organisateurs par la SAS Pointcourse...</p>
                            <!-- Le reste du texte des conditions -->
                        </div>
                    </div>
                \`;
                modal.className = 'modal';
                document.body.appendChild(modal);
            }

            // Gestion des options de publication
            function updatePublishButtons() {
                const acceptConditions = document.getElementById('accept-conditions').checked;
                const saveDraft = document.getElementById('save-draft').checked;
                const publishNow = document.getElementById('publish-now').checked;
                
                const saveDraftBtn = document.getElementById('save-draft-btn');
                const publishBtn = document.getElementById('publish-btn');
                
                conditionsAccepted = acceptConditions;
                publishOptionSelected = saveDraft || publishNow;
                
                // Activer/désactiver les boutons
                saveDraftBtn.disabled = !conditionsAccepted || !saveDraft;
                publishBtn.disabled = !conditionsAccepted || !publishNow;
                
                // Changer l'apparence des boutons
                if (saveDraftBtn.disabled) {
                    saveDraftBtn.style.opacity = '0.5';
                    saveDraftBtn.style.cursor = 'not-allowed';
                } else {
                    saveDraftBtn.style.opacity = '1';
                    saveDraftBtn.style.cursor = 'pointer';
                }
                
                if (publishBtn.disabled) {
                    publishBtn.style.opacity = '0.5';
                    publishBtn.style.cursor = 'not-allowed';
                } else {
                    publishBtn.style.opacity = '1';
                    publishBtn.style.cursor = 'pointer';
                }
            }

            // Fonction de sauvegarde en brouillon
            function saveAsDraft() {
                if (!conditionsAccepted || !document.getElementById('save-draft').checked) return;
                
                // Ici on ajouterait la logique de sauvegarde
                alert('Événement sauvegardé en brouillon !\\n\\nVous pouvez le retrouver dans votre dashboard et le publier plus tard.');
            }

            // Fonction de publication
            function publishEvent() {
                if (!conditionsAccepted || !document.getElementById('publish-now').checked) return;
                
                // Ici on ajouterait la logique de publication
                alert('Événement publié avec succès !\\n\\nVotre événement est maintenant visible sur le calendrier ATS-Sport.com et les inscriptions sont ouvertes selon vos paramètres.');
            }

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                // Gérer les checkboxes mutuellement exclusives pour les options de publication
                const saveDraftCheck = document.getElementById('save-draft');
                const publishNowCheck = document.getElementById('publish-now');
                
                if (saveDraftCheck) {
                    saveDraftCheck.addEventListener('change', function() {
                        if (this.checked) {
                            publishNowCheck.checked = false;
                        }
                        updatePublishButtons();
                    });
                }
                
                if (publishNowCheck) {
                    publishNowCheck.addEventListener('change', function() {
                        if (this.checked) {
                            saveDraftCheck.checked = false;
                        }
                        updatePublishButtons();
                    });
                }
                
                // Gérer l'acceptation des conditions
                const acceptConditions = document.getElementById('accept-conditions');
                if (acceptConditions) {
                    acceptConditions.addEventListener('change', updatePublishButtons);
                }
                
                // Mettre à jour le résumé quand on arrive sur cet onglet
                updateSummary();
                updatePublishButtons();
            });

            // Mettre à jour le résumé quand on change d'onglet
            function updateValidationTab() {
                updateSummary();
            }
            `;
            </script>

        </div>

        <!-- Navigation Buttons -->
        <div style="display: flex; justify-content: space-between; margin-top: 2rem;">
            <button id="prev-btn" style="background: #1a1a1a; color: #cccccc; border: 1px solid #333333; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;" disabled>
                ← PRÉCÉDENT
            </button>
            <button id="next-btn" style="background: #0ea5e9; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 700; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                SUIVANT →
            </button>
        </div>
    </div>
</div>

<script>
let currentTab = 0;
let parcoursCount = 1;
const tabs = ['epreuve', 'parcours', 'contact', 'reglement', 'inscription', 'autre', 'validation'];

function showTab(index) {
    // Hide all tabs and disable required fields
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
        // Désactiver les champs required dans les onglets cachés
        content.querySelectorAll('[required]').forEach(field => {
            field.removeAttribute('required');
            field.setAttribute('data-was-required', 'true');
        });
    });

    // Reset all tab buttons
    document.querySelectorAll('.tab').forEach(tab => {
        tab.style.background = '#1a1a1a';
        tab.style.color = '#cccccc';
    });

    // Show current tab and re-enable required fields
    const tabToShow = document.getElementById('tab-' + tabs[index]);
    if (tabToShow) {
        tabToShow.style.display = 'block';
        // Réactiver les champs required dans l'onglet actif
        tabToShow.querySelectorAll('[data-was-required="true"]').forEach(field => {
            field.setAttribute('required', 'required');
        });
    }

    // Highlight current tab button
    const tabButton = document.querySelectorAll('.tab')[index];
    if (tabButton) {
        tabButton.style.background = '#0ea5e9';
        tabButton.style.color = '#000000';
    }
    
    // Update navigation buttons
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    if (prevBtn) {
        prevBtn.disabled = index === 0;
    }
    
    if (nextBtn) {
        nextBtn.textContent = index === tabs.length - 1 ? 'TERMINER' : 'SUIVANT →';
    }
    
    // Special initializations
    if (index === 1) {
        setTimeout(initParcoursFunctionality, 100);
    }
    
    if (index === 4) {
        setTimeout(syncParcoursToInscription, 100);
    }
    
    currentTab = index;
}

function nextTab() {
    if (currentTab < tabs.length - 1) {
        showTab(currentTab + 1);
    } else {
        alert('Formulaire terminé !');
    }
}

function prevTab() {
    if (currentTab > 0) {
        showTab(currentTab - 1);
    }
}

function syncParcoursToInscription() {
    const parcoursItems = document.querySelectorAll('#tab-parcours .parcours-item');
    const pricingContainer = document.getElementById('pricing-parcours-list');
    
    if (!pricingContainer || parcoursItems.length === 0) return;
    
    pricingContainer.innerHTML = '';
    
    parcoursItems.forEach((parcours, index) => {
        const nameInput = parcours.querySelector('input[name="parcours_name[]"]');
        const distanceInput = parcours.querySelector('input[name="parcours_distance[]"]');
        
        const parcoursName = nameInput && nameInput.value ? nameInput.value : `Parcours ${index + 1}`;
        const parcoursDistance = distanceInput && distanceInput.value ? distanceInput.value : '';
        
        const pricingDiv = document.createElement('div');
        pricingDiv.style.cssText = 'background: #111111; border: 1px solid #333333; padding: 2rem; margin-bottom: 1.5rem;';
        
        pricingDiv.innerHTML = `
            <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">■ ${parcoursName.toUpperCase()}${parcoursDistance ? ` (${parcoursDistance}km)` : ''}</h5>
            
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 2rem; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Prix de base (€)</label>
                    <input type="number" name="parcours_base_price[]" step="0.01" style="width: 100%; padding: 1.25rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="25.00">
                </div>
                <button type="button" class="manage-pricing" style="background: #0ea5e9; color: #000000; border: none; padding: 1.25rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;" onclick="openPricingModal(this)">
                    TARIFS PROGRESSIFS
                </button>
            </div>
        `;
        
        pricingContainer.appendChild(pricingDiv);
    });
}

function initParcoursFunctionality() {
    const addButton = document.getElementById('add-parcours');
    if (addButton && !addButton.dataset.initialized) {
        addButton.dataset.initialized = 'true';
        addButton.addEventListener('click', function() {
            parcoursCount++;
            const newParcours = document.createElement('div');
            newParcours.className = 'parcours-item';
            newParcours.style.cssText = 'background: #111111; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;';
            
            newParcours.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h5 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px;">■ PARCOURS ${parcoursCount}</h5>
                    <button type="button" class="remove-parcours" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;" onclick="removeParcours(this)">SUPPRIMER</button>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Nom du parcours *</label>
                        <input type="text" name="parcours_name[]" required style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="Ex: Trail 21km">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Distance (km) *</label>
                        <input type="number" name="parcours_distance[]" step="0.1" required style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="21.0">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Dénivelé (m)</label>
                        <input type="number" name="parcours_elevation[]" style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="500">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Places max</label>
                        <input type="number" name="parcours_max[]" style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="500">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Âge minimum</label>
                        <input type="number" name="parcours_age_min[]" style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="16">
                    </div>
                </div>
            `;
            
            document.getElementById('parcours-list').appendChild(newParcours);
        });
    }
}

function removeParcours(button) {
    if (document.querySelectorAll('.parcours-item').length > 1) {
        button.closest('.parcours-item').remove();
    } else {
        alert('Vous devez garder au moins un parcours !');
    }
}

function showPdfPreview(file) {
    const filename = document.getElementById('pdf-filename');
    const filesize = document.getElementById('pdf-filesize');
    const preview = document.getElementById('pdf-preview');
    
    if (filename && filesize && preview) {
        filename.textContent = file.name;
        filesize.textContent = `Taille: ${(file.size / 1024 / 1024).toFixed(2)} Mo`;
        preview.style.display = 'block';
    }
}

function removePdf() {
    const pdfInput = document.getElementById('pdf-input');
    const preview = document.getElementById('pdf-preview');
    
    if (pdfInput && preview) {
        pdfInput.value = '';
        preview.style.display = 'none';
    }
}

let currentPricingButton = null;
let pricingPeriodCount = 1;

function openPricingModal(button) {
    currentPricingButton = button;
    
    // Créer le modal s'il n'existe pas
    let modal = document.getElementById('pricing-modal');
    if (!modal) {
        modal = createPricingModal();
        document.body.appendChild(modal);
    }
    
    modal.style.display = 'flex';
    loadExistingPricing(button);
}

function createPricingModal() {
    const modal = document.createElement('div');
    modal.id = 'pricing-modal';
    modal.style.cssText = 'display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); z-index: 1000; align-items: center; justify-content: center;';
    
    modal.innerHTML = `
        <div style="background: #111111; border: 1px solid #333333; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
            <div style="background: #1a1a1a; padding: 1.5rem; border-bottom: 1px solid #333333;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-family: 'Oswald', sans-serif; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 1px;">■ GESTION DES TARIFS</h3>
                    <button type="button" onclick="closePricingModal()" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; cursor: pointer; text-transform: uppercase;">FERMER</button>
                </div>
            </div>
            
            <div style="padding: 2rem;">
                <div id="pricing-periods">
                    <div class="pricing-period" style="background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h4 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px;">■ PÉRIODE 1</h4>
                            <button type="button" class="remove-period" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; cursor: pointer; text-transform: uppercase;" onclick="removePricingPeriod(this)">SUPPRIMER</button>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; font-size: 0.9rem;">Date de début *</label>
                                <input type="datetime-local" class="start-date" style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; font-size: 0.9rem;">Date de fin *</label>
                                <input type="datetime-local" class="end-date" style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;">
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; font-size: 0.9rem;">Prix (€) *</label>
                                <input type="number" step="0.01" class="period-price" style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;" placeholder="25.00">
                            </div>
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; font-size: 0.9rem;">Description (optionnel)</label>
                            <input type="text" class="period-description" style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;" placeholder="Ex: Tarif early bird">
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <button type="button" id="add-pricing-period" style="background: #22c55e; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 700; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;" onclick="addPricingPeriod()">
                        ➕ AJOUTER UNE PÉRIODE
                    </button>
                </div>
            </div>
            
            <div style="background: #1a1a1a; padding: 1.5rem; border-top: 1px solid #333333; display: flex; justify-content: space-between;">
                <button type="button" onclick="clearAllPricing()" style="background: #6b7280; color: white; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase;">
                    EFFACER TOUT
                </button>
                <button type="button" onclick="savePricing()" style="background: #0ea5e9; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 700; cursor: pointer; text-transform: uppercase;">
                    SAUVEGARDER
                </button>
            </div>
        </div>
    `;
    
    return modal;
}

function closePricingModal() {
    const modal = document.getElementById('pricing-modal');
    if (modal) {
        modal.style.display = 'none';
    }
    currentPricingButton = null;
}

function loadExistingPricing(button) {
    const parcoursItem = button.closest('.pricing-parcours-item') || button.closest('[style*="background: #111111"]');
    if (!parcoursItem) return;
    
    const pricingData = parcoursItem.querySelector('.pricing-data');
    if (!pricingData || !pricingData.value) return;
    
    const periods = JSON.parse(pricingData.value);
    const container = document.getElementById('pricing-periods');
    container.innerHTML = '';
    
    periods.forEach((period, index) => {
        addPricingPeriodElement(index + 1, period);
    });
    pricingPeriodCount = periods.length;
}

function addPricingPeriod() {
    pricingPeriodCount++;
    addPricingPeriodElement(pricingPeriodCount);
}

function addPricingPeriodElement(number, data = null) {
    const container = document.getElementById('pricing-periods');
    const periodDiv = document.createElement('div');
    periodDiv.className = 'pricing-period';
    periodDiv.style.cssText = 'background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 1rem;';
    
    periodDiv.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h4 style="font-family: 'Oswald', sans-serif; color: #0ea5e9; margin: 0; text-transform: uppercase; letter-spacing: 1px;">■ PÉRIODE ${number}</h4>
            <button type="button" class="remove-period" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; cursor: pointer; text-transform: uppercase;" onclick="removePricingPeriod(this)">SUPPRIMER</button>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; font-size: 0.9rem;">Date de début *</label>
                <input type="datetime-local" class="start-date" value="${data?.start_date || ''}" style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; font-size: 0.9rem;">Date de fin *</label>
                <input type="datetime-local" class="end-date" value="${data?.end_date || ''}" style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; font-size: 0.9rem;">Prix (€) *</label>
                <input type="number" step="0.01" class="period-price" value="${data?.price || ''}" style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;" placeholder="25.00">
            </div>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; font-size: 0.9rem;">Description (optionnel)</label>
            <input type="text" class="period-description" value="${data?.description || ''}" style="width: 100%; padding: 0.75rem; background: #111111; border: 1px solid #333333; color: #ffffff;" placeholder="Ex: Tarif early bird">
        </div>
    `;
    
    container.appendChild(periodDiv);
}

function removePricingPeriod(button) {
    if (document.querySelectorAll('.pricing-period').length > 1) {
        button.closest('.pricing-period').remove();
    } else {
        alert('Vous devez garder au moins une période de tarification !');
    }
}

function clearAllPricing() {
    if (confirm('Êtes-vous sûr de vouloir effacer tous les tarifs ?')) {
        const container = document.getElementById('pricing-periods');
        container.innerHTML = '';
        addPricingPeriodElement(1);
        pricingPeriodCount = 1;
    }
}

function savePricing() {
    const periods = [];
    const periodElements = document.querySelectorAll('.pricing-period');
    
    let isValid = true;
    periodElements.forEach(period => {
        const startDate = period.querySelector('.start-date').value;
        const endDate = period.querySelector('.end-date').value;
        const price = period.querySelector('.period-price').value;
        const description = period.querySelector('.period-description').value;
        
        if (!startDate || !endDate || !price) {
            isValid = false;
            return;
        }
        
        periods.push({
            start_date: startDate,
            end_date: endDate,
            price: parseFloat(price),
            description: description
        });
    });
    
    if (!isValid) {
        alert('Veuillez remplir tous les champs obligatoires !');
        return;
    }
    
    // Sauvegarder dans le parcours
    const parcoursItem = currentPricingButton.closest('[style*="background: #111111"]');
    if (!parcoursItem) return;
    
    // Créer les champs cachés s'ils n'existent pas
    let pricingData = parcoursItem.querySelector('.pricing-data');
    let pricingDisplay = parcoursItem.querySelector('.pricing-display');
    let pricingList = parcoursItem.querySelector('.pricing-list');
    
    if (!pricingData) {
        pricingData = document.createElement('textarea');
        pricingData.style.display = 'none';
        pricingData.className = 'pricing-data';
        pricingData.name = 'parcours_pricing[]';
        parcoursItem.appendChild(pricingData);
    }
    
    if (!pricingDisplay) {
        pricingDisplay = document.createElement('div');
        pricingDisplay.className = 'pricing-display';
        pricingDisplay.style.cssText = 'margin-top: 1.5rem; display: none;';
        pricingDisplay.innerHTML = `
            <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1rem;">
                <div style="color: #0ea5e9; font-family: 'Oswald', sans-serif; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">■ TARIFS CONFIGURÉS</div>
                <div class="pricing-list" style="color: #cccccc; font-size: 0.9rem;"></div>
            </div>
        `;
        parcoursItem.appendChild(pricingDisplay);
        pricingList = pricingDisplay.querySelector('.pricing-list');
    }
    
    pricingData.value = JSON.stringify(periods);
    
    // Afficher un résumé
    let displayHtml = '';
    periods.forEach((period) => {
        const startDate = new Date(period.start_date).toLocaleDateString('fr-FR');
        const endDate = new Date(period.end_date).toLocaleDateString('fr-FR');
        displayHtml += `<div style="margin-bottom: 0.25rem;">• ${startDate} → ${endDate} : ${period.price}€${period.description ? ' (' + period.description + ')' : ''}</div>`;
    });
    
    pricingList.innerHTML = displayHtml;
    pricingDisplay.style.display = 'block';
    
    closePricingModal();
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Tab clicks
    document.querySelectorAll('.tab').forEach((tab, index) => {
        tab.addEventListener('click', () => {
            showTab(index);
        });
    });

    // Gestion des questions supplémentaires
    const addQuestionBtn = document.getElementById('add-question');
    if (addQuestionBtn) {
        addQuestionBtn.addEventListener('click', addCustomQuestion);
    }

    document.querySelectorAll('.question-item').forEach(attachQuestionEvents);

    // Navigation buttons
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    if (prevBtn) {
        prevBtn.addEventListener('click', prevTab);
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', nextTab);
    }
    
    // PDF upload functionality
    const pdfInput = document.getElementById('pdf-input');
    const uploadZone = document.getElementById('pdf-upload-zone');
    
    if (pdfInput && uploadZone) {
        pdfInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type === 'application/pdf') {
                showPdfPreview(file);
            } else {
                alert('Veuillez sélectionner un fichier PDF valide.');
            }
        });

        // Drag & Drop
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadZone.style.borderColor = '#0ea5e9';
            uploadZone.style.backgroundColor = '#0c2e3e';
        });

        uploadZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadZone.style.borderColor = '#333333';
            uploadZone.style.backgroundColor = '#111111';
        });

        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadZone.style.borderColor = '#333333';
            uploadZone.style.backgroundColor = '#111111';
            
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type === 'application/pdf') {
                pdfInput.files = files;
                showPdfPreview(files[0]);
            } else {
                alert('Veuillez déposer un fichier PDF valide.');
            }
        });
    }
    
    // Iframe integration toggle
    const iframeRadios = document.querySelectorAll('input[name="iframe_integration"]');
    const iframeDetails = document.getElementById('iframe-details');
    
    if (iframeRadios.length > 0 && iframeDetails) {
        iframeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'yes' && this.checked) {
                    iframeDetails.style.display = 'block';
                } else {
                    iframeDetails.style.display = 'none';
                }
            });
        });
    }

    // Gestionnaire de soumission du formulaire
    const form = document.getElementById('create-event-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Réactiver tous les champs required avant la soumission
            document.querySelectorAll('[data-was-required="true"]').forEach(field => {
                field.setAttribute('required', 'required');
            });
        });
    }

    // Initialize first tab
    showTab(0);
});

let questionCount = 3; // On a déjà 3 questions prédéfinies

function addCustomQuestion() {
    questionCount++;
    const questionsList = document.getElementById('questions-list');
    
    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-item';
    questionDiv.style.cssText = 'background: #111111; border: 1px solid #333333; padding: 2rem; margin-bottom: 2rem;';
    
    questionDiv.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <input type="text" placeholder="TITRE DE LA QUESTION" style="background: #1a1a1a; border: 1px solid #333333; padding: 1rem; color: #0ea5e9; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; width: 300px;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                    <input type="radio" name="custom_${questionCount}_enabled" value="yes" style="width: 18px; height: 18px; accent-color: #0ea5e9;">
                    <span style="font-weight: 600;">OUI</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc;">
                    <input type="radio" name="custom_${questionCount}_enabled" value="no" checked style="width: 18px; height: 18px; accent-color: #0ea5e9;">
                    <span style="font-weight: 600;">NON</span>
                </label>
                <button type="button" onclick="removeQuestion(this)" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; cursor: pointer; text-transform: uppercase;">SUPPRIMER</button>
            </div>
        </div>
        
        <div class="question-details" style="display: none;">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Prix (€)</label>
                    <input type="number" name="custom_${questionCount}_price" step="0.01" style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; transition: all 0.2s ease;" placeholder="0.00">
                    <small style="color: #cccccc; font-size: 0.9rem; display: block; margin-top: 0.5rem;">Laisser 0 si gratuit</small>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Description / Options</label>
                    <textarea name="custom_${questionCount}_description" rows="3" style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; resize: vertical; transition: all 0.2s ease;" placeholder="Décrivez l'option ou listez les choix disponibles..."></textarea>
                </div>
            </div>
        </div>
    `;
    
    questionsList.appendChild(questionDiv);
    
    // Ajouter les événements pour cette nouvelle question
    attachQuestionEvents(questionDiv);
}

function removeQuestion(button) {
    const questionItem = button.closest('.question-item');
    if (document.querySelectorAll('.question-item').length > 1) {
        questionItem.remove();
    } else {
        alert('Vous devez garder au moins une question !');
    }
}

function attachQuestionEvents(questionDiv) {
    const radios = questionDiv.querySelectorAll('input[type="radio"]');
    const details = questionDiv.querySelector('.question-details');
    
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'yes' && this.checked) {
                details.style.display = 'block';
            } else if (this.value === 'no' && this.checked) {
                details.style.display = 'none';
            }
        });
    });
}
</script>

<style>
input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: #0ea5e9;
}

.tab:hover {
    background: #333333 !important;
}



button:hover {
    transform: translateY(-1px);
}

button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

input::placeholder, textarea::placeholder {
    color: #666666;
}

.upload-zone:hover {
    border-color: #0ea5e9;
}
</style>
        </form>
    </div>
@endsection