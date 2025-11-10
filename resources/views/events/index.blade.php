@extends('layouts.app')

@section('title', 'Événements sportifs')

@section('content')
    <!-- Hero Slider -->
    <div id="hero-slider" style="background: linear-gradient(135deg, var(--primary), var(--accent)); padding: 4rem 0; margin: -2rem -2rem 3rem -2rem; border-radius: 0 0 24px 24px; position: relative; overflow: hidden; min-height: 400px;">
        
        <!-- Slide 1: Statistiques -->
        <div class="hero-slide active" data-slide="1" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; text-align: center; position: absolute; top: 4rem; left: 0; right: 0; transition: all 0.5s ease; opacity: 1; transform: translateX(0);">

            <h1 style="font-size: 3rem; font-weight: 700; color: white; margin-bottom: 1rem; line-height: 1.2;">
                Trouvez votre prochaine <span style="color: #fbbf24;">aventure</span> sportive
            </h1>
            <p style="font-size: 1.25rem; color: rgba(255, 255, 255, 0.9); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Des trails aux courses urbaines, découvrez les événements qui vous correspondent près de chez vous.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 1rem 2rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.2);">
                    <div style="font-size: 2rem; font-weight: 700; color: white;">{{ $events->count() }}</div>
                    <div style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">Événements actifs</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 1rem 2rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.2);">
                    <div style="font-size: 2rem; font-weight: 700; color: white;">5000km+</div>
                    <div style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">Kilomètres parcourues</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 1rem 2rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.2);">
                    <div style="font-size: 2rem; font-weight: 700; color: white;">70k+</div>
                    <div style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">Participants inscrits</div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Course vedette 01 -->
        <div class="hero-slide" data-slide="2" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; text-align: center; position: absolute; top: 4rem; left: 0; right: 0; transition: all 0.5s ease; opacity: 0; transform: translateX(100%);">

            @if($events->first())
            <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 2rem; border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.2); max-width: 800px; margin: 0 auto;">
                <div style="font-size: 1rem; color: #fbbf24; font-weight: 600; text-transform: uppercase; margin-bottom: 1rem;">🏆 Course à l'affiche</div>
                <h1 style="font-size: 2.5rem; font-weight: 700; color: white; margin-bottom: 1rem; line-height: 1.2;">
                    {{ $events->first()->name }}
                </h1>
                <p style="font-size: 1.25rem; color: rgba(255, 255, 255, 0.9); margin-bottom: 2rem;">
                    🐘 {{ $events->first()->location }} • 📅 {{ $events->first()->event_date->format('d/m/Y') }}
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center; align-items: center; flex-wrap: wrap;">
                    <button style="background: #fbbf24; color: #1a1a1a; padding: 1rem 2rem; border: none; border-radius: 12px; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: all 0.2s ease;">
                        🐘 S'inscrire maintenant
                    </button>
                    <button style="background: rgba(255, 255, 255, 0.2); color: white; padding: 1rem 2rem; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;">
                        🐘 En savoir plus
                    </button>
                </div>
            </div>
            @endif
        </div>

        <!-- Slide 3: Boutique -->
        <div class="hero-slide" data-slide="3" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; text-align: center; position: absolute; top: 4rem; left: 0; right: 0; transition: all 0.5s ease; opacity: 0; transform: translateX(100%);">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; max-width: 1000px; margin: 0 auto;">
                <div style="text-align: left;">
                    <div style="font-size: 1rem; color: #fbbf24; font-weight: 600; text-transform: uppercase; margin-bottom: 1rem;">🛒 Boutique Point Course</div>
                    <h1 style="font-size: 2.5rem; font-weight: 700; color: white; margin-bottom: 1rem; line-height: 1.2;">
                        Organisateurs <span style="color: #fbbf24;">rendez vous ici</span>
                    </h1>
                    <p style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.9); margin-bottom: 2rem;">
                        Découvrez nos offres et services.
                    </p>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <button style="background: #fbbf24; color: #1a1a1a; padding: 1rem 2rem; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">
                            🐘 Voir la boutique
                        </button>
                        <button style="background: rgba(255, 255, 255, 0.2); color: white; padding: 1rem 2rem; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 12px; font-weight: 600; cursor: pointer;">
                            🐘 Test WIP
                        </button>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 12px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🐘</div>
                        <div style="color: white; font-weight: 600;">Location</div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">wip</div>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 12px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🐘</div>
                        <div style="color: white; font-weight: 600;">Inscription</div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">wip</div>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 12px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🐘</div>
                        <div style="color: white; font-weight: 600;">Dossards</div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">wip</div>
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 12px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🐘</div>
                        <div style="color: white; font-weight: 600;">Prestation</div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">wip</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 4: Test WIP -->
        <div class="hero-slide" data-slide="4" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem; text-align: center; position: absolute; top: 4rem; left: 0; right: 0; transition: all 0.5s ease; opacity: 0; transform: translateX(100%);">
            <h1 style="font-size: 3rem; font-weight: 700; color: white; margin-bottom: 1rem; line-height: 1.2;">
                wip wip wip <span style="color: #fbbf24;">wip</span> 🐘
            </h1>
            <p style="font-size: 1.25rem; color: rgba(255, 255, 255, 0.9); margin-bottom: 2rem; max-width: 700px; margin-left: auto; margin-right: auto;">
                Plateforme complète pour gérer vos inscriptions, participants et résultats en toute simplicité.
            </p>
            <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; margin-bottom: 2rem;">
                <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.2); text-align: center;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚡</div>
                    <div style="color: white; font-weight: 600; margin-bottom: 0.5rem;">🐘 wip wip</div>
                    <div style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">🐘 wip wip wip</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.2); text-align: center;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🐘</div>
                    <div style="color: white; font-weight: 600; margin-bottom: 0.5rem;">🐘</div>
                    <div style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">🐘</div>
                </div>
                <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.2); text-align: center;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                    <div style="color: white; font-weight: 600; margin-bottom: 0.5rem;">Statistiques</div>
                    <div style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">Temps réel</div>
                </div>
            </div>
            <button style="background: #fbbf24; color: #1a1a1a; padding: 1rem 2rem; border: none; border-radius: 12px; font-weight: 600; font-size: 1.1rem; cursor: pointer;">
                🚀 Créer mon événement
            </button>
        </div>

        <!-- Navigation dots -->
        <div style="position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%); display: flex; gap: 0.5rem;">
            <button class="slide-dot active" data-slide="1" style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; background: white; cursor: pointer; transition: all 0.3s ease;"></button>
            <button class="slide-dot" data-slide="2" style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; background: transparent; cursor: pointer; transition: all 0.3s ease;"></button>
            <button class="slide-dot" data-slide="3" style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; background: transparent; cursor: pointer; transition: all 0.3s ease;"></button>
            <button class="slide-dot" data-slide="4" style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; background: transparent; cursor: pointer; transition: all 0.3s ease;"></button>
        </div>

        <!-- Progress bar -->
        <div style="position: absolute; bottom: 0; left: 0; height: 3px; background: rgba(255, 255, 255, 0.3); width: 100%;">
            <div id="progress-bar" style="height: 100%; background: #fbbf24; width: 0%; transition: width 0.1s linear;"></div>
        </div>
    </div>

    <script>
        let currentSlide = 1;
        let slideInterval;
        let progressInterval;
        const totalSlides = 4;
        const slideDuration = 5000;

        function showSlide(slideNumber) {
            // Hide all slides
            document.querySelectorAll('.hero-slide').forEach(slide => {
                slide.style.opacity = '0';
                slide.style.transform = 'translateX(100%)';
            });
            
            // Show current slide with animation
            setTimeout(() => {
                const activeSlide = document.querySelector(`[data-slide="${slideNumber}"]`);
                activeSlide.style.transform = 'translateX(0)';
                activeSlide.style.opacity = '1';
            }, 100);
            
            // Update dots
            document.querySelectorAll('.slide-dot').forEach(dot => {
                dot.style.background = 'transparent';
                dot.classList.remove('active');
            });
            document.querySelector(`[data-slide="${slideNumber}"].slide-dot`).style.background = 'white';
            document.querySelector(`[data-slide="${slideNumber}"].slide-dot`).classList.add('active');
            
            currentSlide = slideNumber;
        }

        function nextSlide() {
            const next = currentSlide >= totalSlides ? 1 : currentSlide + 1;
            showSlide(next);
        }

        function startSlider() {
            const progressBar = document.getElementById('progress-bar');
            progressBar.style.width = '0%';
            
            let progress = 0;
            progressInterval = setInterval(() => {
                progress += 100 / (slideDuration / 100);
                progressBar.style.width = progress + '%';
            }, 100);

<!-- Filters -->
<div style="background: var(--bg-card); padding: 1.5rem; border-radius: 16px; margin-bottom: 2rem; box-shadow: var(--shadow);">
    <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span style="color: var(--text-secondary); font-weight: 500;">Filtrer :</span>
        </div>
        <button class="filter-btn active" data-filter="all" style="background: var(--primary); color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500; cursor: pointer;">
            Tous
        </button>
        <button class="filter-btn" data-filter="course" style="background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500; cursor: pointer;">
            Course
        </button>
        <button class="filter-btn" data-filter="vtt" style="background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500; cursor: pointer;">
            VTT
        </button>
        <button class="filter-btn" data-filter="triathlon" style="background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500; cursor: pointer;">
            Triathlon
        </button>
        <div style="margin-left: auto; display: flex; gap: 1rem;">
            <input type="text" placeholder="Rechercher par ville..." style="background: var(--bg-secondary); border: 1px solid var(--border); padding: 0.75rem 1rem; border-radius: 8px; color: var(--text-primary); width: 250px;">
            <button style="background: var(--accent); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; cursor: pointer;">
                🔍 Rechercher
            </button>
        </div>
    </div>
</div>

<!-- Events Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
    @forelse($events as $event)
        <div class="event-card" style="background: var(--bg-card); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow); transition: all 0.3s ease; cursor: pointer;" 
             onclick="window.location.href='{{ route('event.show', $event->slug) }}'">
            
            <!-- Event Image -->
            <div style="height: 200px; background: linear-gradient(135deg, var(--primary), var(--accent)); position: relative; display: flex; align-items: center; justify-content: center;">
                <div style="font-size: 3rem; color: white; opacity: 0.8;">🏃</div>
                <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(0, 0, 0, 0.7); color: white; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.8rem; font-weight: 500;">
                    {{ $event->event_date->format('d M') }}
                </div>
            </div>
            
            <!-- Event Content -->
            <div style="padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; line-height: 1.3;">
                            {{ $event->name }}
                        </h3>
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                            <span>📍</span>
                            <span>{{ $event->location }} ({{ $event->department }})</span>
                        </div>
                    </div>
                    <div class="status-badge" style="
                        @if($event->status == 'open') background: var(--success); @endif
                        @if($event->status == 'upcoming') background: var(--warning); @endif
                        @if($event->status == 'closed') background: var(--error); @endif
                        color: white; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
                    ">
                        @if($event->status == 'open') Ouvert @endif
                        @if($event->status == 'upcoming') Bientôt @endif
                        @if($event->status == 'closed') Fermé @endif
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: var(--bg-secondary); border-radius: 8px;">
                    <div style="text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ $event->event_date->format('d') }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase;">{{ $event->event_date->format('M') }}</div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 500; color: var(--text-primary);">{{ $event->event_date->format('l') }}</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Inscription avant le {{ $event->registration_deadline->format('d/m/Y') }}</div>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: 0.5rem;">
                        <span style="background: var(--bg-secondary); color: var(--text-secondary); padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.8rem;">
                            🏃 Course
                        </span>
                        @if($event->max_participants)
                            <span style="background: var(--bg-secondary); color: var(--text-secondary); padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.8rem;">
                                👥 {{ $event->max_participants }} places
                            </span>
                        @endif
                    </div>
                    <button style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; transition: all 0.2s ease;">
                        S'inscrire →
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: var(--text-secondary);">
            <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">🏃</div>
            <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: var(--text-primary);">Aucun événement disponible</h3>
            <p>Les prochains événements seront bientôt annoncés !</p>
        </div>
    @endforelse
</div>

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