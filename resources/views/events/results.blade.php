@extends('layouts.app')

@section('title', 'Résultats des courses')

@section('content')
<div class="container" style="padding: 3rem 0;">
    <!-- Header Section -->
    <div style="text-align: center; margin-bottom: 4rem;">
        <h1 style="font-family: 'Oswald', sans-serif; font-size: 4rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 3px;">
            RÉSULTATS <span style="color: #0ea5e9;">DES COURSES</span>
        </h1>
        <p style="font-size: 1.1rem; color: #cccccc; max-width: 600px; margin: 0 auto; font-weight: 300;">
            Consultez les résultats et performances des évènements terminés
        </p>
    </div>

    <!-- Filters -->
    <div style="background: #111111; padding: 2rem; border: 1px solid #333333; margin-bottom: 3rem;">
        <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap; justify-content: space-between;">
            <div style="display: flex; gap: 2rem; align-items: center;">
                <span style="color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Filtrer par :</span>
                <select style="background: #1a1a1a; border: 1px solid #333333; padding: 0.75rem 1.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                    <option>TOUTES LES ANNÉES</option>
                    <option>2025</option>
                    <option>2024</option>
                </select>
                <select style="background: #1a1a1a; border: 1px solid #333333; padding: 0.75rem 1.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                    <option>TOUS LES DÉPARTEMENTS</option>
                    <option>34 - HÉRAULT</option>
                    <option>30 - GARD</option>
                    <option>66 - PYRÉNÉES-ORIENTALES</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 1rem; align-items: center;">
                <input type="text" placeholder="RECHERCHER PAR COURSE OU LIEU..." style="background: #1a1a1a; border: 1px solid #333333; padding: 1rem 1.5rem; color: #ffffff; width: 300px; font-family: 'Roboto', sans-serif; font-size: 0.9rem; letter-spacing: 0.5px;">
                <button style="background: #0ea5e9; color: #000000; border: none; padding: 1rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    RECHERCHER
                </button>
            </div>
        </div>
    </div>

    <!-- Results Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(450px, 1fr)); gap: 2rem;">
        @forelse($events as $event)
            <div class="result-card" style="background: #111111; border: 1px solid #333333; overflow: hidden; transition: all 0.3s ease; cursor: pointer;" onclick="window.location.href='#'">
                
                <!-- Card Header -->
                <div style="background: #0ea5e9; padding: 2rem; color: #000000; position: relative;">
                    <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(0, 0, 0, 0.8); color: #0ea5e9; padding: 0.5rem 1rem; font-family: 'Oswald', sans-serif; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                        {{ $event->event_date->format('d/m/Y') }}
                    </div>
                    <h3 style="font-family: 'Oswald', sans-serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px; padding-right: 4rem;">
                        {{ $event->name }}
                    </h3>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                        <span style="color: #000000;">●</span>
                        <span>{{ $event->location }} ({{ $event->department }})</span>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div style="padding: 2rem;">
                    <!-- Stats Row -->
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem;">
                        <div style="text-align: center; background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem;">
                            <div style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #0ea5e9; margin-bottom: 0.5rem;">
                                {{ rand(540, 580) }}
                            </div>
                            <div style="font-size: 0.8rem; color: #cccccc; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">PARTICIPANTS</div>
                        </div>
                        <div style="text-align: center; background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem;">
                            <div style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #22c55e; margin-bottom: 0.5rem;">
                                {{ rand(530, 540) }}
                            </div>
                            <div style="font-size: 0.8rem; color: #cccccc; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">CLASSÉS</div>
                        </div>
                        <div style="text-align: center; background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem;">
                            <div style="font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; color: #0ea5e9; margin-bottom: 0.5rem;">
                                {{ rand(4, 4) }}
                            </div>
                            <div style="font-size: 0.8rem; color: #cccccc; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">PARCOURS</div>
                        </div>
                    </div>
                    
                    <!-- Podium Section -->
                    <div style="background: #1a1a1a; border: 1px solid #333333; padding: 1.5rem; margin-bottom: 2rem;">
                        <h4 style="color: #ffffff; margin-bottom: 1.5rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                            ■ PODIUM DU SEMI-MARATHON
                        </h4>
                        <div style="display: flex; justify-content: space-between; align-items: end; text-align: center; gap: 1rem;">
                            <!-- 2nd place -->
                            <div style="flex: 1;">
                                <div style="background: #666666; color: #000000; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.2rem;">2</div>
                                <div style="font-family: 'Oswald', sans-serif; font-size: 0.9rem; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Y. TATARD</div>
                                <div style="font-size: 0.8rem; color: #cccccc; font-family: 'Oswald', sans-serif;">1H23'45</div>
                            </div>
                            <!-- 1st place -->
                            <div style="flex: 1;">
                                <div style="background: #0ea5e9; color: #000000; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.3rem;">1</div>
                                <div style="font-family: 'Oswald', sans-serif; font-size: 0.9rem; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Y. SCOTTE</div>
                                <div style="font-size: 0.8rem; color: #cccccc; font-family: 'Oswald', sans-serif;">1H18'32</div>
                            </div>
                            <!-- 3rd place -->
                            <div style="flex: 1;">
                                <div style="background: #cd7f32; color: #000000; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1.2rem;">3</div>
                                <div style="font-family: 'Oswald', sans-serif; font-size: 0.9rem; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">K. MBAPPE</div>
                                <div style="font-size: 0.8rem; color: #cccccc; font-family: 'Oswald', sans-serif;">1H25'12</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 1rem;">
                        <button style="flex: 1; background: #0ea5e9; color: #000000; border: none; padding: 1rem; font-family: 'Oswald', sans-serif; font-weight: 700; transition: all 0.2s ease; text-transform: uppercase; letter-spacing: 1px;">
                            VOIR LES RÉSULTATS
                        </button>
                        <button style="background: #1a1a1a; color: #0ea5e9; border: 1px solid #333333; padding: 1rem; transition: all 0.2s ease; font-family: 'Oswald', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                            EXPORT
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem; color: #cccccc;">
                <div style="font-size: 4rem; margin-bottom: 2rem; color: #333333;">■</div>
                <h3 style="font-family: 'Oswald', sans-serif; font-size: 2rem; margin-bottom: 1rem; color: #ffffff; text-transform: uppercase; letter-spacing: 2px;">AUCUN RÉSULTAT DISPONIBLE</h3>
                <p style="font-size: 1.1rem;">Les résultats des prochaines courses apparaîtront ici.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
.result-card:hover {
    transform: translateY(-2px);
    border-color: #0ea5e9;
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

select:focus {
    outline: none;
    border-color: #0ea5e9;
}
</style>
@endsection