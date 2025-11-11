@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div style="min-height: calc(100vh - 80px); display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="max-width: 500px; width: 100%;">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 3px;">
                INSCRIPTION
            </h1>
            <p style="color: #cccccc; font-size: 1.1rem;">
                Rejoignez la communauté ATS Sport
            </p>
        </div>

        <!-- Form Card -->
        <div style="background: #111111; border: 1px solid #333333; padding: 3rem;">
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- First Name -->
                <div style="margin-bottom: 2rem;">
                    <label for="first_name" style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Prénom
                    </label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus 
                        style="width: 100%; padding: 1rem 1.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                    
                    @error('first_name')
                        <span style="color: #ef4444; font-size: 0.9rem; margin-top: 0.5rem; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Last Name -->
                <div style="margin-bottom: 2rem;">
                    <label for="last_name" style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Nom
                    </label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name"
                        style="width: 100%; padding: 1rem 1.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                    
                    @error('last_name')
                        <span style="color: #ef4444; font-size: 0.9rem; margin-top: 0.5rem; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Email -->
                <div style="margin-bottom: 2rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Adresse email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           style="width: 100%; padding: 1rem 1.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                    
                    @error('email')
                        <span style="color: #ef4444; font-size: 0.9rem; margin-top: 0.5rem; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Password -->
                <div style="margin-bottom: 2rem;">
                    <label for="password" style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Mot de passe
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           style="width: 100%; padding: 1rem 1.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                    
                    @error('password')
                        <span style="color: #ef4444; font-size: 0.9rem; margin-top: 0.5rem; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div style="margin-bottom: 2rem;">
                    <label for="password-confirm" style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Confirmer le mot de passe
                    </label>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                           style="width: 100%; padding: 1rem 1.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                </div>

                <!-- Terms -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: flex; align-items: start; gap: 0.75rem; color: #cccccc; cursor: pointer;">
                        <input type="checkbox" name="terms" required 
                               style="width: 18px; height: 18px; accent-color: #0ea5e9; margin-top: 0.2rem; flex-shrink: 0;">
                        <span style="font-size: 0.9rem; line-height: 1.4;">
                            J'accepte les <a href="#" style="color: #0ea5e9; text-decoration: none;">conditions d'utilisation</a> et la <a href="#" style="color: #0ea5e9; text-decoration: none;">politique de confidentialité</a>
                        </span>
                    </label>
                </div>

                <!-- Actions -->
                <div style="margin-bottom: 2rem;">
                    <button type="submit" style="width: 100%; background: #0ea5e9; color: #000000; border: none; padding: 1.25rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: all 0.2s ease; margin-bottom: 1rem;">
                        CRÉER MON COMPTE
                    </button>
                </div>

                <!-- Login Link -->
                <div style="text-align: center; padding-top: 2rem; border-top: 1px solid #333333;">
                    <p style="color: #cccccc; margin-bottom: 1rem;">Déjà un compte ?</p>
                    <a href="{{ route('login') }}" style="background: transparent; color: #0ea5e9; border: 1px solid #0ea5e9; padding: 0.75rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease; display: inline-block;">
                        SE CONNECTER
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
input:focus {
    outline: none;
    border-color: #0ea5e9;
}

button:hover {
    background: #0284c7 !important;
    transform: translateY(-1px);
}

a:hover {
    color: #ffffff !important;
}

.btn-outline:hover {
    background: #0ea5e9 !important;
}
</style>
@endsection