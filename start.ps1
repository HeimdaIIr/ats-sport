# ========================================
# Script de Démarrage ATS-Sport + ChronoFront
# ========================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ATS-Sport + ChronoFront - Démarrage  " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Vérifier que l'installation a été effectuée
if (-Not (Test-Path ".env")) {
    Write-Host "[ERREUR] Le fichier .env n'existe pas !" -ForegroundColor Red
    Write-Host "Veuillez d'abord exécuter le script d'installation :" -ForegroundColor Yellow
    Write-Host "  .\install.ps1" -ForegroundColor White
    Write-Host ""
    Read-Host "Appuyez sur Entrée pour quitter"
    exit 1
}

# Vérifier que vendor existe
if (-Not (Test-Path "vendor")) {
    Write-Host "[ERREUR] Les dépendances ne sont pas installées !" -ForegroundColor Red
    Write-Host "Veuillez d'abord exécuter le script d'installation :" -ForegroundColor Yellow
    Write-Host "  .\install.ps1" -ForegroundColor White
    Write-Host ""
    Read-Host "Appuyez sur Entrée pour quitter"
    exit 1
}

Write-Host "[INFO] Vérification de la connexion à la base de données..." -ForegroundColor Yellow

# Test de connexion DB
php artisan db:show 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "[AVERTISSEMENT] Impossible de se connecter à la base de données" -ForegroundColor Yellow
    Write-Host "Vérifiez vos paramètres dans le fichier .env" -ForegroundColor Yellow
    Write-Host ""
    $continue = Read-Host "Voulez-vous continuer malgré tout ? (y/N)"
    if ($continue -ne "y" -and $continue -ne "Y") {
        exit 1
    }
}

Write-Host "[OK] Configuration validée" -ForegroundColor Green
Write-Host ""

# Afficher les informations d'accès
Write-Host "========================================" -ForegroundColor Green
Write-Host "  DÉMARRAGE DU SERVEUR...              " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Write-Host "Le serveur va démarrer sur :" -ForegroundColor Cyan
Write-Host "  http://localhost:8000" -ForegroundColor White
Write-Host ""

Write-Host "Accès aux modules :" -ForegroundColor Yellow
Write-Host "  - Site ATS-Sport     : http://localhost:8000" -ForegroundColor White
Write-Host "  - ChronoFront        : http://localhost:8000/chronofront" -ForegroundColor White
Write-Host ""

Write-Host "Pour arrêter le serveur, appuyez sur Ctrl+C" -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan

# Démarrer le serveur Laravel
php artisan serve
