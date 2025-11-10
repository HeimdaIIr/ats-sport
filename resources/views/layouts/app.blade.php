<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ATS Sport - Plateforme d\'inscription sportive')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #6b7280;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --bg-main: #1a1a1a; /* Noir pur */
            --bg-secondary: #0a0a0a; /* Noir très légèrement gris */
            --bg-card: #2a2a2a; /* Gris très sombre pour les cartes */
            --text-primary: #ffffff; /* Blanc pur */
            --text-secondary: #a3a3a3; /* Gris clair */
            --border: #2a2a2a; /* Bordures gris sombre */
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }

        [data-theme="light"] {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --bg-main: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            line-height: 1.6;
            transition: all 0.3s ease;
        }

        .header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: none;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .nav-main {
            display: flex;
            gap: 2rem;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle:hover {
            background: var(--primary);
            color: white;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .container {
            max-width: none;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .main-content {
            min-height: calc(100vh - 72px);
            padding: 2rem 2rem;
        }

        @media (max-width: 768px) {
            .nav-main {
                display: none;
            }
            
            .header-content {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">
                <div class="logo-icon">🏃</div>
                <span>ATS Sport</span>
            </a>
            
            <nav class="nav-main">
                <a href="/" class="nav-link">Événements</a>
                <a href="/resultats" class="nav-link">Résultats</a>
                <a href="/organisateur" class="nav-link">Organisateurs</a>
            </nav>
            
            <div class="nav-actions">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <span class="theme-icon">🌙</span>
                </button>
                
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Inscription</a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-outline">Mon compte</a>
                    <a href="{{ route('logout') }}" class="btn btn-primary"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Déconnexion
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const icon = document.querySelector('.theme-icon');
            icon.textContent = newTheme === 'dark' ? '☀️' : '🌙';
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'dark'; // Sombre par défaut
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            const icon = document.querySelector('.theme-icon');
            icon.textContent = savedTheme === 'light' ? '🌙' : '☀️';
        });
    </script>
</body>
</html>