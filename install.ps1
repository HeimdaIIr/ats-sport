# ========================================
# Script d'Installation ATS-Sport + ChronoFront
# ========================================

Write-Host "=================================" -ForegroundColor Cyan
Write-Host "  ATS-Sport + ChronoFront Setup  " -ForegroundColor Cyan
Write-Host "=================================" -ForegroundColor Cyan
Write-Host ""

# Fonction pour afficher les étapes
function Write-Step {
    param($message)
    Write-Host "`n[ETAPE] $message" -ForegroundColor Yellow
}

function Write-Success {
    param($message)
    Write-Host "  [OK] $message" -ForegroundColor Green
}

function Write-Error {
    param($message)
    Write-Host "  [ERREUR] $message" -ForegroundColor Red
}

# ========================================
# ETAPE 1 : Vérification des prérequis
# ========================================
Write-Step "Vérification des prérequis..."

# Vérifier PHP
try {
    $phpVersion = php -v 2>&1 | Select-String "PHP (\d+\.\d+)" | ForEach-Object { $_.Matches.Groups[1].Value }
    if ($phpVersion) {
        Write-Success "PHP $phpVersion détecté"
    }
} catch {
    Write-Error "PHP n'est pas installé ou n'est pas dans le PATH"
    exit 1
}

# Vérifier Composer
try {
    $composerVersion = composer --version 2>&1 | Out-String
    if ($composerVersion -match "Composer") {
        Write-Success "Composer détecté"
    }
} catch {
    Write-Error "Composer n'est pas installé"
    exit 1
}

# Vérifier MySQL
try {
    $mysqlVersion = mysql --version 2>&1 | Out-String
    if ($mysqlVersion -match "mysql") {
        Write-Success "MySQL détecté"
    }
} catch {
    Write-Warning "MySQL CLI non détecté (pas grave si vous utilisez XAMPP/WAMP)"
}

# ========================================
# ETAPE 2 : Installation des dépendances
# ========================================
Write-Step "Installation des dépendances PHP..."

composer install --no-interaction
if ($LASTEXITCODE -eq 0) {
    Write-Success "Dépendances PHP installées"
} else {
    Write-Error "Erreur lors de l'installation des dépendances"
    exit 1
}

# ========================================
# ETAPE 3 : Configuration .env
# ========================================
Write-Step "Configuration de l'environnement..."

if (-Not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Success "Fichier .env créé"

    # Générer la clé
    php artisan key:generate
    Write-Success "Clé d'application générée"
} else {
    Write-Success "Fichier .env déjà existant"
}

# ========================================
# ETAPE 4 : Configuration Base de Données
# ========================================
Write-Step "Configuration de la base de données..."

Write-Host "`nVeuillez configurer votre base de données :" -ForegroundColor Cyan
$dbName = Read-Host "  Nom de la base de données (défaut: ats_sport)"
if ([string]::IsNullOrWhiteSpace($dbName)) { $dbName = "ats_sport" }

$dbUser = Read-Host "  Utilisateur MySQL (défaut: root)"
if ([string]::IsNullOrWhiteSpace($dbUser)) { $dbUser = "root" }

$dbPass = Read-Host "  Mot de passe MySQL (laisser vide si aucun)" -AsSecureString
$dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))

# Mise à jour du .env
(Get-Content .env) | ForEach-Object {
    $_ -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=mysql' `
       -replace 'DB_DATABASE=.*', "DB_DATABASE=$dbName" `
       -replace 'DB_USERNAME=.*', "DB_USERNAME=$dbUser" `
       -replace 'DB_PASSWORD=.*', "DB_PASSWORD=$dbPassPlain"
} | Set-Content .env

Write-Success "Configuration .env mise à jour"

# Créer la base de données
Write-Host "`nCréation de la base de données..." -ForegroundColor Cyan
$createDb = "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if ([string]::IsNullOrWhiteSpace($dbPassPlain)) {
    mysql -u $dbUser -e $createDb 2>&1 | Out-Null
} else {
    mysql -u $dbUser -p"$dbPassPlain" -e $createDb 2>&1 | Out-Null
}

if ($LASTEXITCODE -eq 0) {
    Write-Success "Base de données '$dbName' créée"
} else {
    Write-Warning "Impossible de créer la base automatiquement. Créez-la manuellement via phpMyAdmin"
    Write-Host "  Nom de la base : $dbName" -ForegroundColor Yellow
    Read-Host "`nAppuyez sur Entrée quand la base est créée..."
}

# ========================================
# ETAPE 5 : Migrations
# ========================================
Write-Step "Création des tables..."

php artisan migrate --force
if ($LASTEXITCODE -eq 0) {
    Write-Success "Tables créées avec succès (8 tables ChronoFront)"
} else {
    Write-Error "Erreur lors de la création des tables"
    Write-Host "  Vérifiez vos paramètres de connexion dans .env" -ForegroundColor Yellow
    exit 1
}

# ========================================
# ETAPE 6 : Seeders
# ========================================
Write-Step "Initialisation des catégories FFA..."

php artisan db:seed --class=CategorySeeder --force
if ($LASTEXITCODE -eq 0) {
    Write-Success "14 catégories FFA créées"
} else {
    Write-Warning "Erreur lors de la création des catégories"
}

# ========================================
# ETAPE 7 : Compilation Assets (optionnel)
# ========================================
Write-Step "Compilation des assets (optionnel)..."

if (Test-Path "package.json") {
    $compileAssets = Read-Host "Voulez-vous compiler les assets ? (y/N)"
    if ($compileAssets -eq "y" -or $compileAssets -eq "Y") {
        npm install
        npm run dev
        Write-Success "Assets compilés"
    } else {
        Write-Host "  Assets non compilés (pas nécessaire pour ChronoFront)" -ForegroundColor Gray
    }
}

# ========================================
# INSTALLATION TERMINÉE
# ========================================
Write-Host "`n=================================" -ForegroundColor Green
Write-Host "  INSTALLATION TERMINÉE !" -ForegroundColor Green
Write-Host "=================================" -ForegroundColor Green

Write-Host "`nVotre application est prête !" -ForegroundColor Cyan
Write-Host "`nPour démarrer le serveur :" -ForegroundColor Yellow
Write-Host "  .\start.ps1" -ForegroundColor White
Write-Host "`nOu manuellement :" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor White

Write-Host "`nAccès à ChronoFront :" -ForegroundColor Yellow
Write-Host "  http://localhost:8000/chronofront" -ForegroundColor White

Write-Host "`nAccès au site ATS-Sport :" -ForegroundColor Yellow
Write-Host "  http://localhost:8000" -ForegroundColor White

Write-Host "`n" -ForegroundColor Green
