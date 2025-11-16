#!/bin/bash

# ChronoFront - Commandes de Configuration
# Exécutez ces commandes pour finaliser l'installation

echo "🚀 ChronoFront - Configuration Laravel"
echo "========================================"
echo ""

# 1. Migrations ChronoFront
echo "📦 1. Exécution des migrations ChronoFront..."
php artisan migrate --database=chronofront --path=database/migrations/chronofront
echo "✅ Migrations terminées!"
echo ""

# 2. Initialiser catégories FFA (via API)
echo "🏃 2. Initialisation des catégories FFA..."
echo "   Appelez: POST http://localhost:8000/api/categories/init-ffa"
echo "   Ou utilisez cette commande curl:"
echo ""
echo "   curl -X POST http://localhost:8000/api/categories/init-ffa \\"
echo "        -H 'Content-Type: application/json' \\"
echo "        -H 'Accept: application/json'"
echo ""

# 3. Créer un événement de test
echo "🎯 3. Créer un événement de test..."
echo "   Appelez: POST http://localhost:8000/api/events"
echo "   Exemple JSON:"
echo '   {'
echo '     "name": "Semi-Marathon de SÈTE 2025",'
echo '     "event_date": "2025-03-16",'
echo '     "location": "SÈTE",'
echo '     "description": "Course de trail et semi-marathon"'
echo '   }'
echo ""

# 4. Configuration WebSockets (optionnel)
echo "📡 4. Configuration WebSockets (OPTIONNEL)..."
echo "   Si vous voulez le temps réel, installez Laravel WebSockets:"
echo ""
echo "   composer require beyondcode/laravel-websockets"
echo "   php artisan vendor:publish --provider=\"BeyondCode\LaravelWebSockets\WebSocketsServiceProvider\""
echo "   php artisan migrate"
echo ""
echo "   Puis configurez .env:"
echo "   BROADCAST_DRIVER=pusher"
echo "   PUSHER_APP_ID=chronofront"
echo "   PUSHER_APP_KEY=chronofront-key"
echo "   PUSHER_APP_SECRET=chronofront-secret"
echo "   PUSHER_HOST=127.0.0.1"
echo "   PUSHER_PORT=6001"
echo "   PUSHER_SCHEME=http"
echo ""
echo "   Lancer le serveur:"
echo "   php artisan websockets:serve"
echo ""

# 5. Démarrer le serveur
echo "🌐 5. Démarrer le serveur Laravel..."
echo "   php artisan serve"
echo ""

# 6. URLs importantes
echo "📌 6. URLs Importantes"
echo "   ==================="
echo ""
echo "   Dashboard ChronoFront:"
echo "   → http://localhost:8000/chronofront"
echo ""
echo "   Import CSV:"
echo "   → http://localhost:8000/chronofront/entrants/import"
echo ""
echo "   Saisie Manuelle:"
echo "   → http://localhost:8000/chronofront/manual-timing"
echo ""
echo "   API Health Check:"
echo "   → http://localhost:8000/api/health"
echo ""
echo "   WebSocket Dashboard (si installé):"
echo "   → http://localhost:8000/laravel-websockets"
echo ""

# 7. Tests
echo "🧪 7. Tests Rapides"
echo "   ==============="
echo ""
echo "   Parser RFID:"
echo '   curl -X POST http://localhost:8000/api/rfid/parse \'
echo '        -H "Content-Type: application/json" \'
echo '        -d '"'"'{"rfid": "[2000001]:a20250316143025123"}'"'"
echo ""
echo "   Télécharger template CSV:"
echo "   curl -O http://localhost:8000/api/import/download-template"
echo ""

echo "✅ Configuration terminée!"
echo ""
echo "📖 Pour plus d'infos, consultez: CHRONOFRONT_SETUP.md"
echo ""
echo "🎉 ChronoFront est prêt à l'emploi!"
