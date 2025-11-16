#!/bin/bash

echo "🚀 Démarrage de ChronoFront..."
echo ""

# Vérifier que MySQL est accessible
echo "📊 Vérification de MySQL sur le port 3012..."
if ! nc -z 127.0.0.1 3012 2>/dev/null; then
    echo "❌ MySQL n'est pas accessible sur le port 3012"
    echo "   Veuillez démarrer MySQL d'abord:"
    echo "   - Si vous utilisez XAMPP: démarrez MySQL depuis le panneau de contrôle"
    echo "   - Si vous utilisez MySQL natif: sudo systemctl start mysql"
    echo "   - Si vous utilisez Docker: docker-compose up -d mysql"
    echo ""
    exit 1
fi
echo "✅ MySQL est accessible"
echo ""

# Vérifier les migrations
echo "🔧 Vérification des migrations..."
php artisan migrate:status --database=chronofront || {
    echo "⚠️  Les migrations doivent être exécutées"
    echo "   Exécution des migrations..."
    php artisan migrate --path=database/migrations/chronofront --database=chronofront
}
echo ""

# Vérifier s'il y a des données
echo "📈 Vérification des données..."
EVENT_COUNT=$(php artisan tinker --execute="echo \App\Models\ChronoFront\Event::count();" 2>/dev/null | tail -1)
if [ "$EVENT_COUNT" = "0" ] || [ -z "$EVENT_COUNT" ]; then
    echo "⚠️  Aucun événement trouvé, chargement des données de test..."
    php artisan db:seed --class=CategorySeeder
    php artisan db:seed --class=TestDataSeeder
else
    echo "✅ $EVENT_COUNT événement(s) trouvé(s)"
fi
echo ""

# Nettoyer le cache Laravel
echo "🧹 Nettoyage du cache Laravel..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo "✅ Cache nettoyé"
echo ""

# Démarrer le serveur
echo "🌐 Démarrage du serveur Laravel sur http://localhost:8000..."
echo "   ChronoFront accessible sur: http://localhost:8000/chronofront"
echo ""
echo "   Appuyez sur Ctrl+C pour arrêter le serveur"
echo ""
php artisan serve --port=8000

