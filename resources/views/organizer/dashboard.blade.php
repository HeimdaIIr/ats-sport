@extends('layouts.app')

@section('content')
<div class="admin-dashboard" style="display: flex; min-height: 80vh;">
    <!-- Sidebar -->
    <div class="sidebar" style="width: 250px; background: #2c3e50; color: white; padding: 20px;">
        <div class="admin-profile" style="text-align: center; margin-bottom: 30px; border-bottom: 1px solid #34495e; padding-bottom: 20px;">
            <div style="width: 50px; height: 50px; background: #3498db; border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center;">
                <span style="font-weight: bold;">AA</span>
            </div>
            <div><strong>ADMIN ADMIN</strong></div>
            <div style="font-size: 12px; color: #bdc3c7;">Admin</div>
        </div>
        
        <nav class="admin-nav">
            <div style="margin-bottom: 20px;">
                <div style="color: #bdc3c7; font-size: 12px; margin-bottom: 10px;">NAVIGATION</div>
                <div style="background: #3498db; padding: 10px; border-radius: 4px; margin-bottom: 5px;">
                    💻 Gestion de vos épreuves
                </div>
                <div style="padding: 8px; color: #bdc3c7; font-size: 14px;">
                    <div style="margin: 5px 0;">◦ Administration</div>
                    <div style="margin: 5px 0;">◦ Import calendriers</div>
                    <div style="margin: 5px 0;">◦ Annonces</div>
                    <div style="margin: 5px 0;">◦ Gestion des catégories</div>
                    <div style="margin: 5px 0;">◦ Gestion des bannières</div>
                    <div style="margin: 5px 0;">◦ Gestion des fichiers</div>
                    <div style="margin: 5px 0;">◦ <a href="{{ route('organizer.create') }}" style="color: #bdc3c7; text-decoration: none;">Création d'une épreuve</a></div>
                    <div style="margin: 5px 0;">◦ Liste de vos épreuves</div>
                    <div style="margin: 5px 0;">◦ Épreuves passées</div>
                    <div style="margin: 5px 0;">◦ Épreuves > 1 an</div>
                    <div style="margin: 5px 0;">◦ Épreuves en cours</div>
                    <div style="margin: 5px 0;">◦ Épreuves à venir</div>
                    <div style="margin: 5px 0;">◦ Traceurs GPS</div>
                    <div style="margin: 5px 0;">◦ Pass FFTri</div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" style="flex: 1; padding: 20px; background: #ecf0f1;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h2 style="margin: 0; color: #2c3e50;">LISTE DE MES ÉPREUVES</h2>
                <small style="color: #7f8c8d;">WIP</small>
            </div>
            <div style="display: flex; gap: 10px;">
                <span style="background: #95a5a6; color: white; padding: 5px 10px; border-radius: 3px; font-size: 12px;">Accueil</span>
                <span style="background: #3498db; color: white; padding: 5px 10px; border-radius: 3px; font-size: 12px;">WIP</span>
            </div>
        </div>

        <!-- Accés rapide ligne -->
        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <!-- Accés rapide -->
            <div style="flex: 2; background: #636e72; color: white; padding: 15px; border-radius: 5px;">
                <h4 style="margin: 0 0 10px 0;">Accès rapide épreuves (ADMIN)</h4>
                <select style="width: 100%; padding: 8px; border: none; border-radius: 3px;">
                    <option>Choisir ...</option>
                </select>
            </div>
            
            <!-- Actions rapides -->
            <div style="flex: 1; background: #636e72; color: white; padding: 15px; border-radius: 5px;">
                <h4 style="margin: 0 0 10px 0;">Actions rapides (ADMIN)</h4>
                <div style="display: flex; gap: 5px;">
                    <button style="background: #3498db; color: white; border: none; padding: 8px; border-radius: 3px; font-size: 12px;">💰</button>
                    <button style="background: #2ecc71; color: white; border: none; padding: 8px; border-radius: 3px; font-size: 12px;">💰</button>
                    <button style="background: #e74c3c; color: white; border: none; padding: 8px; border-radius: 3px; font-size: 12px;">💰</button>
                    <button style="background: #f39c12; color: white; border: none; padding: 8px; border-radius: 3px; font-size: 12px;">📊</button>
                    <button style="background: #9b59b6; color: white; border: none; padding: 8px; border-radius: 3px; font-size: 12px;">✉️</button>
                </div>
            </div>
        </div>

        <!-- Recherche participant -->
        <div style="background: #636e72; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h4 style="margin: 0 0 10px 0;">Recherche rapide d'un participant</h4>
            <input type="text" placeholder="Entrer son nom et/ou prénom" style="width: 300px; padding: 8px; border: none; border-radius: 3px;">
        </div>

        <!-- Liste de courses -->
        <div style="background: white; border-radius: 5px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="background: #636e72; color: white; padding: 10px;">
                <h4 style="margin: 0;">Épreuves</h4>
            </div>
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">#</th>
                        <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Nom de l'épreuve</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6;">Vérifications</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6;">Date de départ</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6;">Date de fin</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 1px solid #dee2e6;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
                    @forelse($events as $event)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px;">{{ $event->id }}</td>
                            <td style="padding: 12px;">
                                <strong>{{ $event->name }}</strong><br>
                                <small style="color: #6c757d;">{{ $event->location }} ({{ $event->department }})</small><br>
                                @if($event->status == 'upcoming')
                                    <span style="background: #e74c3c; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">Épreuve non ouverte</span>
                                @elseif($event->status == 'open')
                                    <span style="background: #28a745; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">Inscriptions ouvertes</span>
                                @elseif($event->status == 'closed')
                                    <span style="background: #ffc107; color: #212529; padding: 2px 6px; border-radius: 3px; font-size: 11px;">Inscriptions fermées</span>
                                @endif
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">INSCRITS<br>0 0</span>
                                    <span style="background: #6c757d; color: white; padding: 2px 8px; border-radius: 3px; font-size: 11px;">CERTIFICATS<br>0 0</span>
                                </div>
                            </td>
                            <td style="padding: 12px; text-align: center;">{{ $event->event_date->format('d/m/Y') }}</td>
                            <td style="padding: 12px; text-align: center;">{{ $event->event_date->format('d/m/Y') }}</td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; flex-direction: column; gap: 12px; align-items: center;">
                                    <div style="display: flex; gap: 12px;">
                                        <button style="background: #17a2b8; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 13px;">🔍</button>
                                        <button style="background: #28a745; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 13px;">🔧</button>
                                        <button style="background: #6f42c1; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 13px;">🚹</button>
                                    </div>
                                    <div style="display: flex; gap: 12px;">
                                        <button style="background: #6f42c1; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 13px;">📶</button>
                                        <button style="background: #6f42c1; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 13px;">📶</button>
                                        <button style="background: #6f42c1; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 13px;">📶</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 20px; text-align: center; color: #6c757d;">
                                Aucune épreuve créée. <a href="#" style="color: #007bff;">Créer votre première épreuve</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection