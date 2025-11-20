<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'ATS SPORT'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #000000;
            color: #ffffff;
            line-height: 1.4;
        }

        .header {
            background: #000000;
            border-bottom: 1px solid #333333;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 24px;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 2px;
        }

        .nav-main {
            display: flex;
            gap: 3rem;
        }

        .nav-link {
            font-family: 'Oswald', sans-serif;
            color: #cccccc;
            text-decoration: none;
            font-weight: 400;
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: color 0.2s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #0ea5e9;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 1px;
            background: #0ea5e9;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .theme-toggle {
            background: transparent;
            border: 1px solid #333333;
            color: #cccccc;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle:hover {
            border-color: #0ea5e9;
            color: #0ea5e9;
        }

        .btn {
            font-family: 'Oswald', sans-serif;
            padding: 12px 24px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
            min-width: 120px;
            justify-content: center;
        }

        .btn-primary {
            background: #0ea5e9;
            color: #000000;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: #0ea5e9;
            border: 1px solid #0ea5e9;
        }

        .btn-outline:hover {
            background: #0ea5e9;
            color: #000000;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .main-content {
            min-height: calc(100vh - 80px);
            padding: 0;
        }

        /* Theme light */
        [data-theme="light"] {
            --bg-main: #ffffff;
            --text-primary: #000000;
        }

        [data-theme="light"] body {
            background: #ffffff;
            color: #000000;
        }

        [data-theme="light"] .header {
            background: #ffffff;
            border-bottom-color: #e5e5e5;
        }

        [data-theme="light"] .logo {
            color: #000000;
        }

        [data-theme="light"] .nav-link {
            color: #666666;
        }

        [data-theme="light"] .theme-toggle {
            border-color: #e5e5e5;
            color: #666666;
        }

        [data-theme="light"] .btn-primary {
            background: #0ea5e9;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .nav-main {
                display: none;
            }
            
            .header-content {
                padding: 0 1rem;
            }
            
            .nav-actions {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo">ATS SPORT</a>
            
            <nav class="nav-main">
                <a href="/" class="nav-link <?php echo e(request()->is('/') ? 'active' : ''); ?>">Événements</a>
                <a href="/resultats" class="nav-link <?php echo e(request()->is('resultats') ? 'active' : ''); ?>">Résultats</a>
                <a href="/organisateur" class="nav-link <?php echo e(request()->is('organisateur*') ? 'active' : ''); ?>">Organisateurs</a>
            </nav>
            
            <div class="nav-actions">
                <button class="theme-toggle" onclick="toggleTheme()">
                    <span class="theme-icon">◐</span>
                </button>
                
                <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline">Connexion</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Inscription</a>
                <?php else: ?>
                    <a href="<?php echo e(route('home')); ?>" class="btn btn-outline">Compte</a>
                    <a href="<?php echo e(route('logout')); ?>" class="btn btn-primary"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Déconnexion
                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const icon = document.querySelector('.theme-icon');
            icon.textContent = newTheme === 'light' ? '◑' : '◐';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            const icon = document.querySelector('.theme-icon');
            icon.textContent = savedTheme === 'light' ? '◑' : '◐';
        });

        .nav-link.active {
            color: #0ea5e9 !important;
        }

        .nav-link.active::after {
            width: 100% !important;
        }
    </script>
</body>
</html><?php /**PATH U:\DEV\ats-sport\resources\views/layouts/app.blade.php ENDPATH**/ ?>