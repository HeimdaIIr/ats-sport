@extends('layouts.app')

@section('title', 'Événements sportifs')

@section('content')
    <!-- Hero Slider -->
    <div id="hero-slider" style="background: #000000; padding: 0; margin: 0; position: relative; overflow: hidden; height: 500px;">
        
        <!-- Slide 1: Statistiques -->
        <div class="hero-slide" data-slide="1" style="max-width: 1400px; margin: 0 auto; padding: 0 2rem; text-align: center; display: block; height: 100%; align-items: center; justify-content: center;">
            <div>
                <h1 style="font-family: 'Oswald', sans-serif; font-size: 4rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 3px; line-height: 1;">
                    DÉPASSEZ VOS <span style="color: #0ea5e9;">LIMITES</span>
                </h1>
                <p style="font-size: 1.2rem; color: #cccccc; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto; font-weight: 300;">
                    Rejoignez la communauté des athlètes qui repoussent leurs limites
                </p>
                <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap;">
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; min-width: 150px; transition: all 0.3s ease;">
                        <div style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #0ea5e9; margin-bottom: 0.5rem;">{{ $events->count() }}</div>
                        <div style="color: #cccccc; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 500;">ÉVÉNEMENTS ACTIFS</div>
                    </div>
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; min-width: 150px; transition: all 0.3s ease;">
                        <div style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #0ea5e9; margin-bottom: 0.5rem;">500+</div>
                        <div style="color: #cccccc; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 500;">ÉVÉNEMENTS TERMINÉS</div>
                    </div>
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; min-width: 150px; transition: all 0.3s ease;">
                        <div style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #0ea5e9; margin-bottom: 0.5rem;">70K+</div>
                        <div style="color: #cccccc; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 500;">PARTICIPANTS INSCRITS</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Course vedette -->
        <div class="hero-slide" data-slide="2" style="max-width: 1400px; margin: 0 auto; padding: 0 2rem; text-align: center; display: none; height: 100%; align-items: center; justify-content: center;">
            @if($events->first())
            <div style="max-width: 800px;">
                <div style="background: #111111; border: 2px solid #0ea5e9; padding: 3rem; position: relative;">
                    <div style="position: absolute; top: -15px; left: 2rem; background: #0ea5e9; color: #000000; padding: 0.5rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem;">
                        COURSE VEDETTE
                    </div>
                    <h1 style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 2px; line-height: 1;">
                        {{ $events->first()->name }}
                    </h1>
                    <div style="display: flex; justify-content: center; gap: 3rem; margin-bottom: 2rem; color: #cccccc;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="color: #0ea5e9; font-size: 1.2rem;">●</span>
                            <span style="font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">{{ $events->first()->location }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="color: #0ea5e9; font-size: 1.2rem;">●</span>
                            <span style="font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">{{ $events->first()->event_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 1.5rem; justify-content: center;">
                        <button style="background: #0ea5e9; color: #000000; border: none; padding: 1rem 2.5rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1rem; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                            S'INSCRIRE MAINTENANT
                        </button>
                        <button style="background: transparent; color: #0ea5e9; border: 2px solid #0ea5e9; padding: 1rem 2.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; font-size: 1rem; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                            EN SAVOIR PLUS
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Slide 3: Boutique -->
        <div class="hero-slide" data-slide="3" style="max-width: 1400px; margin: 0 auto; padding: 0 2rem; text-align: center; display: none; height: 100%; align-items: center; justify-content: center;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; max-width: 1100px;">
                <div style="text-align: left;">
                    <div style="background: #0ea5e9; color: #000000; display: inline-block; padding: 0.5rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 2rem; font-size: 0.9rem;">
                        BOUTIQUE ATS SPORT
                    </div>
                    <h1 style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #ffffff; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 2px; line-height: 1;">
                        DECOUVREZ NOTRE BOUTIQUE <span style="color: #0ea5e9;">POINT COURSE</span>
                    </h1>
                    <p style="font-size: 1.1rem; color: #cccccc; margin-bottom: 2rem; line-height: 1.6;">
                        Organisateurs : rendez vous ici pour découvrir nos services!
                    </p>
                    <div style="display: flex; gap: 1.5rem;">
                        <button style="background: #0ea5e9; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 700; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;">
                            VOIR LA BOUTIQUE
                        </button>
                        <button style="background: transparent; color: #0ea5e9; border: 2px solid #0ea5e9; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;">
                            WIP
                        </button>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; text-align: center; transition: all 0.3s ease;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: #0ea5e9;">■</div>
                        <div style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">DOSSARDS</div>
                        <div style="color: #0ea5e9; font-size: 1.2rem; font-weight: 700;">wip</div>
                    </div>
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; text-align: center; transition: all 0.3s ease;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: #0ea5e9;">■</div>
                        <div style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">GESTION D'INSCRIPTIONS</div>
                        <div style="color: #0ea5e9; font-size: 1.2rem; font-weight: 700;">wip</div>
                    </div>
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; text-align: center; transition: all 0.3s ease;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: #0ea5e9;">■</div>
                        <div style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">LOCATION</div>
                        <div style="color: #0ea5e9; font-size: 1.2rem; font-weight: 700;">wip</div>
                    </div>
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; text-align: center; transition: all 0.3s ease;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: #0ea5e9;">■</div>
                        <div style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">PRESTATIONS</div>
                        <div style="color: #0ea5e9; font-size: 1.2rem; font-weight: 700;">wip</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 4: Organisateurs -->
        <!-- <div class="hero-slide" data-slide="4" style="max-width: 1400px; margin: 0 auto; padding: 0 2rem; text-align: center; display: none; height: 100%; align-items: center; justify-content: center;">
            <div>
                <h1 style="font-family: 'Oswald', sans-serif; font-size: 4rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 3px; line-height: 1;">
                    ORGANISEZ VOTRE <span style="color: #0ea5e9;">ÉVÉNEMENT</span>
                </h1>
                <p style="font-size: 1.2rem; color: #cccccc; margin-bottom: 3rem; max-width: 700px; margin-left: auto; margin-right: auto; font-weight: 300;">
                    Plateforme complète pour gérer vos inscriptions, participants et résultats en toute simplicité.
                </p>
                <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; text-align: center; min-width: 200px;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: #0ea5e9;">■</div>
                        <div style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">Location</div>
                        <div style="color: #cccccc; font-size: 0.9rem;">En 10 minutes</div>
                    </div>
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; text-align: center; min-width: 200px;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: #0ea5e9;">■</div>
                        <div style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">PAIEMENTS SÉCURISÉS</div>
                        <div style="color: #cccccc; font-size: 0.9rem;">Stripe & PayPal</div>
                    </div>
                    <div style="background: #111111; border: 2px solid #333333; padding: 2rem; text-align: center; min-width: 200px;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem; color: #0ea5e9;">■</div>
                        <div style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 600; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">Statistiques</div>
                        <div style="color: #cccccc; font-size: 0.9rem;">Temps réel</div>
                    </div>
                </div>
                <button style="background: #0ea5e9; color: #000000; border: none; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.1rem; cursor: pointer; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 2rem;">
                    CRÉER MON ÉVÉNEMENT
                </button>
            </div>
        </div> 
        -->
        <!-- Navigation dots -->
        <div style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); display: flex; gap: 1rem; z-index: 100;">
            <button class="slide-dot active" data-slide="1" style="width: 15px; height: 15px; background: #0ea5e9; border: 2px solid #0ea5e9; cursor: pointer; transition: all 0.3s ease;"></button>
            <button class="slide-dot" data-slide="2" style="width: 15px; height: 15px; background: transparent; border: 2px solid #333333; cursor: pointer; transition: all 0.3s ease;"></button>
            <button class="slide-dot" data-slide="3" style="width: 15px; height: 15px; background: transparent; border: 2px solid #333333; cursor: pointer; transition: all 0.3s ease;"></button>
        </div>

        <!-- Progress bar -->
        <div style="position: absolute; bottom: 0; left: 0; height: 3px; background: #333333; width: 100%;">
            <div id="progress-bar" style="height: 100%; background: #0ea5e9; width: 0%; transition: width 0.1s linear;"></div>
        </div>
    </div>
    
    <!-- Script Slider -->
    <script src="{{ asset('js/slider.js') }}"></script>

<!-- Filtres -->
<div style="background: #111111; padding: 2rem; border: 1px solid #333333; margin-bottom: 3rem;">
    <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap; justify-content: space-between;">
        
        <!-- Filtres gauche -->
        <div style="display: flex; gap: 2rem; align-items: center;">
            <span style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Filtrer :</span>
            
            <button class="filter-btn active" style="background: #0ea5e9; color: #000000; border: none; padding: 0.75rem 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                Tous
            </button>
            <button class="filter-btn" style="background: #1a1a1a; color: #cccccc; border: 1px solid #333333; padding: 0.75rem 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 500; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                Course
            </button>
            <button class="filter-btn" style="background: #1a1a1a; color: #cccccc; border: 1px solid #333333; padding: 0.75rem 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 500; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                VTT
            </button>
            <button class="filter-btn" style="background: #1a1a1a; color: #cccccc; border: 1px solid #333333; padding: 0.75rem 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 500; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                Triathlon
            </button>
        </div>
        
        <!-- Recherche droite -->
        <div style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" placeholder="RECHERCHER PAR VILLE..." style="background: #1a1a1a; border: 1px solid #333333; padding: 1rem 1.5rem; color: #ffffff; width: 300px; font-family: 'Roboto', sans-serif; font-size: 0.9rem; letter-spacing: 0.5px;">
            <button style="background: #0ea5e9; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                RECHERCHER
            </button>
        </div>
    </div>
</div>

<!-- Events Grid - Prochains événements -->
<div style="max-width: 1900px; margin: 0 auto; padding: 2rem;">
@if($events->count() > 0)
    <div style="margin-bottom: 4rem;">
        <h2 style="font-family: 'Oswald', sans-serif; font-size: 2rem; color: #ffffff; margin-bottom: 2rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 2px solid #0ea5e9; padding-bottom: 1rem; display: inline-block;">
            ■ PROCHAINS ÉVÉNEMENTS
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 2rem;">
            @foreach($events as $event)
                <div class="event-card" style="background: #111111; border: 1px solid #333333; border-radius: 0; overflow: hidden; transition: all 0.3s ease; cursor: pointer;" 
                     onclick="window.location.href='{{ route('event.show', $event->slug) }}'">
                    
                    <!-- Event Image -->
                    <div style="height: 200px; background: #0ea5e9; position: relative; display: flex; align-items: center; justify-content: center;">
                        <div style="font-size: 3rem; color: #000000;">🏃</div>
                        
                        <!-- Status Badge -->
                        <div style="position: absolute; top: 1rem; left: 1rem;">
                            @if($event->status == 'open')
                                <span style="background: #22c55e; color: #000000; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                                    OUVERT
                                </span>
                            @elseif($event->status == 'upcoming')
                                <span style="background: #eab308; color: #000000; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                                    BIENTÔT
                                </span>
                            @elseif($event->status == 'closed')
                                <span style="background: #ef4444; color: #ffffff; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                                    FERMÉ
                                </span>
                            @endif
                        </div>
                        
                        <!-- Date Badge -->
                        <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(0, 0, 0, 0.8); color: white; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">
                            {{ $event->event_date->format('d M') }}
                        </div>
                    </div>
                    
                    <!-- Event Content -->
                    <div style="padding: 2rem; background: #111111;">
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="font-family: 'Oswald', sans-serif; font-size: 1.4rem; font-weight: 600; color: #ffffff; margin-bottom: 0.5rem; letter-spacing: 1px; text-transform: uppercase;">
                                {{ $event->name }}
                            </h3>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc; font-size: 0.9rem;">
                                <span style="color: #0ea5e9;">●</span>
                                <span>{{ $event->location }} ({{ $event->department }})</span>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem; padding: 1.5rem; background: #1a1a1a; border: 1px solid #333333;">
                            <div style="text-align: center;">
                                <div style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #0ea5e9;">{{ $event->event_date->format('d') }}</div>
                                <div style="font-size: 0.8rem; color: #cccccc; text-transform: uppercase; letter-spacing: 1px;">{{ $event->event_date->format('M') }}</div>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-family: 'Oswald', sans-serif; font-weight: 500; color: #ffffff; margin-bottom: 0.25rem; text-transform: uppercase;">{{ $event->event_date->format('l') }}</div>
                                <div style="font-size: 0.9rem; color: #cccccc;">Inscription avant le {{ $event->registration_deadline->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 0.5rem;">
                                <span style="background: #1a1a1a; color: #0ea5e9; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border: 1px solid #333333;">
                                    COURSE
                                </span>
                            </div>
                            <button style="background: #0ea5e9; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; transition: all 0.2s ease; text-transform: uppercase; letter-spacing: 1px;">
                                S'INSCRIRE
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Derniers résultats -->
    @if($completedEvents->count() > 0)
        <div style="margin-bottom: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 style="font-family: 'Oswald', sans-serif; font-size: 2rem; color: #ffffff; text-transform: uppercase; letter-spacing: 2px; border-bottom: 2px solid #0ea5e9; padding-bottom: 1rem; display: inline-block;">
                    ■ DERNIERS RÉSULTATS
                </h2>
                <a href="/resultats" style="background: transparent; color: #0ea5e9; border: 1px solid #0ea5e9; padding: 0.75rem 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    VOIR TOUS LES RÉSULTATS
                </a>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 2rem;">
                @foreach($completedEvents as $event)
                    <div class="result-card" style="background: #111111; border: 1px solid #333333; overflow: hidden; transition: all 0.3s ease; cursor: pointer;" onclick="window.location.href='#'">
                        
                        <!-- Event Image -->
                        <div style="height: 200px; background: #6b7280; position: relative; display: flex; align-items: center; justify-content: center;">
                            <div style="font-size: 3rem; color: #000000;">🏆</div>
                            
                            <!-- Completed Badge -->
                            <div style="position: absolute; top: 1rem; left: 1rem;">
                                <span style="background: #6b7280; color: #ffffff; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                                    TERMINÉ
                                </span>
                            </div>
                            
                            <!-- Date Badge -->
                            <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(0, 0, 0, 0.8); color: white; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px;">
                                {{ $event->event_date->format('d M') }}
                            </div>
                        </div>
                        
                        <!-- Event Content -->
                        <div style="padding: 2rem; background: #111111;">
                            <div style="margin-bottom: 1.5rem;">
                                <h3 style="font-family: 'Oswald', sans-serif; font-size: 1.4rem; font-weight: 600; color: #ffffff; margin-bottom: 0.5rem; letter-spacing: 1px; text-transform: uppercase;">
                                    {{ $event->name }}
                                </h3>
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc; font-size: 0.9rem;">
                                    <span style="color: #0ea5e9;">●</span>
                                    <span>{{ $event->location }} ({{ $event->department }})</span>
                                </div>
                            </div>
                            
                            <!-- Stats rapides -->
                            <div style="display: flex; gap: 1rem; margin-bottom: 2rem; padding: 1.5rem; background: #1a1a1a; border: 1px solid #333333;">
                                <div style="text-align: center; flex: 1;">
                                    <div style="font-family: 'Oswald', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0ea5e9;">{{ rand(150, 500) }}</div>
                                    <div style="font-size: 0.7rem; color: #cccccc; text-transform: uppercase; letter-spacing: 1px;">PARTICIPANTS</div>
                                </div>
                                <div style="text-align: center; flex: 1;">
                                    <div style="font-family: 'Oswald', sans-serif; font-size: 1.5rem; font-weight: 700; color: #22c55e;">{{ rand(140, 490) }}</div>
                                    <div style="font-size: 0.7rem; color: #cccccc; text-transform: uppercase; letter-spacing: 1px;">CLASSÉS</div>
                                </div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <span style="background: #1a1a1a; color: #6b7280; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border: 1px solid #333333;">
                                        TERMINÉ
                                    </span>
                                </div>
                                <button style="background: #0ea5e9; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; transition: all 0.2s ease; text-transform: uppercase; letter-spacing: 1px;">
                                    VOIR RÉSULTATS
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($events->count() == 0 && $completedEvents->count() == 0)
        <div style="text-align: center; padding: 4rem; color: #cccccc;">
            <div style="font-size: 4rem; margin-bottom: 2rem; color: #333333;">■</div>
            <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; margin-bottom: 1rem; color: #ffffff; text-transform: uppercase; letter-spacing: 2px;">AUCUN ÉVÉNEMENT DISPONIBLE</h3>
            <p style="font-size: 1.1rem;">Les prochains événements seront bientôt annoncés !</p>
        </div>
    @endif

<style>
.event-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.filter-btn {
    transition: all 0.2s ease;
}

.filter-btn:hover:not(.active) {
    background: var(--primary);
    color: white;
}

.filter-btn.active {
    background: var(--primary) !important;
    color: white !important;
}

.filter-btn:hover:not(.active) {
    background: #0ea5e9 !important;
    color: #000000 !important;
}

.filter-btn.active {
    background: #0ea5e9 !important;
    color: #000000 !important;
}

input::placeholder {
    color: #666666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.8rem;
}

input:focus {
    outline: none;
    border-color: #0ea5e9;
}

button:hover {
    transform: translateY(-1px);
}

.hero-slide div:hover {
    transform: translateY(-2px);
}

.slide-dot.active {
    background: #0ea5e9 !important;
    border-color: #0ea5e9 !important;
}

.slide-dot:hover {
    border-color: #0ea5e9 !important;
}

.hero-slide {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: 500px !important;
}

.hero-slide[style*="display: none"] {
    display: none !important;
}

</style>

<script>
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>
@endsection