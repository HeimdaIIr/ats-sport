@extends('layouts.app')

@section('title', 'Modifier l\'épreuve')

@section('content')
<div style="min-height: calc(100vh - 80px); background: #000000; padding: 2rem 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">

        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
            <div>
                <h1 style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #ffffff; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 3px;">
                    MODIFIER <span style="color: #0ea5e9;">L'ÉPREUVE</span>
                </h1>
                <p style="color: #cccccc; font-size: 1.1rem;">{{ $event->name }}</p>
            </div>
            <a href="{{ route('organizer.dashboard') }}" style="background: #1a1a1a; color: #cccccc; border: 1px solid #333333; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                ← RETOUR
            </a>
        </div>

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

        <!-- Formulaire -->
        <form method="POST" action="{{ route('organizer.update', $event->id) }}" style="background: #111111; border: 1px solid #333333; padding: 3rem;">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">

                <!-- Colonne gauche -->
                <div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                        <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                        <h3 style="font-family: 'Oswald', sans-serif; font-size: 1.5rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                            INFORMATIONS PRINCIPALES
                        </h3>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">
                            Nom de l'épreuve *
                        </label>
                        <input type="text" name="name" value="{{ old('name', $event->name) }}" required
                            style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;"
                            placeholder="Ex: Trail de Bédarieux 2025">
                        <small style="color: #cccccc; font-size: 0.9rem; margin-top: 0.5rem; display: block;">Le nom qui apparaîtra sur le site</small>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">
                            Description
                        </label>
                        <textarea name="description" rows="6"
                            style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; resize: vertical; transition: all 0.2s ease;"
                            placeholder="Décrivez votre épreuve, l'ambiance, les parcours...">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">
                                Lieu *
                            </label>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}" required
                                style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;"
                                placeholder="Ville">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">
                                Département *
                            </label>
                            <input type="text" name="department" value="{{ old('department', $event->department) }}" required maxlength="3"
                                style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;"
                                placeholder="34">
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">
                            Nombre maximum de participants
                        </label>
                        <input type="number" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1"
                            style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;"
                            placeholder="500">
                        <small style="color: #cccccc; font-size: 0.9rem; margin-top: 0.5rem; display: block;">Laissez vide pour illimité</small>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                        <div style="width: 4px; height: 40px; background: #0ea5e9;"></div>
                        <h3 style="font-family: 'Oswald', sans-serif; font-size: 1.5rem; font-weight: 700; color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">
                            DATES ET STATUT
                        </h3>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">
                            Date de l'épreuve *
                        </label>
                        <input type="date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required
                            style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">
                            Date limite d'inscription *
                        </label>
                        <input type="date" name="registration_deadline" value="{{ old('registration_deadline', $event->registration_deadline->format('Y-m-d')) }}" required
                            style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                        <small style="color: #cccccc; font-size: 0.9rem; margin-top: 0.5rem; display: block;">Date après laquelle les inscriptions seront fermées</small>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; font-family: 'Oswald', sans-serif; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">
                            Statut de l'épreuve
                        </label>
                        <select name="status"
                            style="width: 100%; padding: 1rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                            <option value="upcoming" {{ old('status', $event->status) == 'upcoming' ? 'selected' : '' }}>À venir (non ouverte)</option>
                            <option value="open" {{ old('status', $event->status) == 'open' ? 'selected' : '' }}>Inscriptions ouvertes</option>
                            <option value="closed" {{ old('status', $event->status) == 'closed' ? 'selected' : '' }}>Inscriptions fermées</option>
                            <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Terminée</option>
                        </select>
                    </div>

                    <div style="background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 2rem;">
                        <label style="display: flex; align-items: center; gap: 1rem; cursor: pointer;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                                style="width: 20px; height: 20px; accent-color: #0ea5e9; cursor: pointer;">
                            <div>
                                <div style="font-family: 'Oswald', sans-serif; font-weight: 600; color: #0ea5e9; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">
                                    ⭐ METTRE EN VEDETTE
                                </div>
                                <small style="color: #cccccc; font-size: 0.9rem;">L'épreuve sera affichée en slider sur la page d'accueil</small>
                            </div>
                        </label>
                    </div>

                    <!-- Info box -->
                    <div style="background: #0c2e3e; border-left: 4px solid #0ea5e9; padding: 1.5rem;">
                        <h5 style="color: #0ea5e9; margin: 0 0 0.75rem 0; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">💡 CONSEIL</h5>
                        <div style="color: #cccccc; font-size: 0.85rem; line-height: 1.6;">
                            <div style="margin-bottom: 0.5rem;">• Utilisez un nom clair et descriptif</div>
                            <div style="margin-bottom: 0.5rem;">• Fixez une date limite d'inscription raisonnable</div>
                            <div>• Les modifications sont sauvegardées immédiatement</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #333333; display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('organizer.dashboard') }}"
                    style="background: #1a1a1a; color: #cccccc; border: 1px solid #333333; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    ← ANNULER
                </a>

                <button type="submit"
                    style="background: #0ea5e9; color: #000000; border: none; padding: 1.25rem 3rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.1rem; cursor: pointer; text-transform: uppercase; letter-spacing: 2px; transition: all 0.2s ease;">
                    💾 ENREGISTRER LES MODIFICATIONS
                </button>
            </div>
        </form>
    </div>
</div>

<style>
input:focus, textarea:focus, select:focus {
    outline: none;
    border-color: #0ea5e9 !important;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
}

a:hover {
    background: #333333 !important;
}

input::placeholder, textarea::placeholder {
    color: #666666;
}
</style>
@endsection
