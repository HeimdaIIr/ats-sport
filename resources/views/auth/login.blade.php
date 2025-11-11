@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div style="min-height: calc(100vh - 80px); display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="max-width: 500px; width: 100%;">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-family: 'Oswald', sans-serif; font-size: 3rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 3px;">
                CONNEXION
            </h1>
            <p style="color: #cccccc; font-size: 1.1rem;">
                Accédez à votre espace personnel
            </p>
        </div>

        <!-- Form Card -->
        <div style="background: #111111; border: 1px solid #333333; padding: 3rem;">
            
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div style="margin-bottom: 2rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: #ffffff; font-family: 'Oswald', sans-serif; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Adresse email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
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
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           style="width: 100%; padding: 1rem 1.5rem; background: #1a1a1a; border: 1px solid #333333; color: #ffffff; font-size: 1rem; transition: all 0.2s ease;">
                    
                    @error('password')
                        <span style="color: #ef4444; font-size: 0.9rem; margin-top: 0.5rem; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #cccccc; cursor: pointer;">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} 
                               style="width: 18px; height: 18px; accent-color: #0ea5e9;">
                        <span style="font-size: 0.9rem;">Se souvenir de moi</span>
                    </label>
                </div>

                <!-- Actions -->
                <div style="margin-bottom: 2rem;">
                    <button type="submit" style="width: 100%; background: #0ea5e9; color: #000000; border: none; padding: 1.25rem; font-family: 'Oswald', sans-serif; font-weight: 700; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: all 0.2s ease; margin-bottom: 1rem;">
                        SE CONNECTER
                    </button>

                    @if (Route::has('password.request'))
                        <div style="text-align: center;">
                            <a href="{{ route('password.request') }}" style="color: #0ea5e9; text-decoration: none; font-size: 0.9rem; transition: color 0.2s ease;">
                                Mot de passe oublié ?
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Register Link -->
                <div style="text-align: center; padding-top: 2rem; border-top: 1px solid #333333;">
                    <p style="color: #cccccc; margin-bottom: 1rem;">Pas encore de compte ?</p>
                    <a href="{{ route('register') }}" style="background: transparent; color: #0ea5e9; border: 1px solid #0ea5e9; padding: 0.75rem 2rem; font-family: 'Oswald', sans-serif; font-weight: 600; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease; display: inline-block;">
                        CRÉER UN COMPTE
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
    background: #0ea5e9 !important;
}
</style>
@endsection