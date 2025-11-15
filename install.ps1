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
# ETAPE 4 : Configuration Base de Données PRINCIPALE (Site ATS-Sport)
# ========================================
Write-Step "Configuration de la base de données PRINCIPALE (Site ATS-Sport)..."

Write-Host "`nBase de données pour le site ATS-Sport (inscriptions, résultats, etc.) :" -ForegroundColor Cyan
$dbName = Read-Host "  Nom de la base (défaut: ats_sport)"
if ([string]::IsNullOrWhiteSpace($dbName)) { $dbName = "ats_sport" }

$dbUser = Read-Host "  Utilisateur MySQL (défaut: root)"
if ([string]::IsNullOrWhiteSpace($dbUser)) { $dbUser = "root" }

$dbPass = Read-Host "  Mot de passe MySQL (laisser vide si aucun)" -AsSecureString
$dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))

Write-Success "Configuration BD principale enregistrée"

# ========================================
# ETAPE 5 : Configuration Base de Données CHRONOFRONT (Chronométrage)
# ========================================
Write-Step "Configuration de la base de données CHRONOFRONT (Chronométrage)..."

Write-Host "`nCette base contiendra uniquement les données de chronométrage (1000 courses/an)" -ForegroundColor Cyan
Write-Host "Pour utiliser la MÊME connexion MySQL, appuyez simplement sur Entrée" -ForegroundColor Gray

$chronoDbName = Read-Host "  Nom de la base ChronoFront (défaut: ats_sport_chronofront)"
if ([string]::IsNullOrWhiteSpace($chronoDbName)) { $chronoDbName = "ats_sport_chronofront" }

$chronoDbUser = Read-Host "  Utilisateur MySQL (défaut: même que principal = $dbUser)"
if ([string]::IsNullOrWhiteSpace($chronoDbUser)) { $chronoDbUser = $dbUser }

$chronoDbPass = Read-Host "  Mot de passe MySQL (défaut: même que principal)" -AsSecureString
$chronoDbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($chronoDbPass))
if ([string]::IsNullOrWhiteSpace($chronoDbPassPlain)) { $chronoDbPassPlain = $dbPassPlain }

Write-Success "Configuration BD ChronoFront enregistrée"

# ========================================
# ETAPE 6 : Mise à jour du fichier .env
# ========================================
Write-Step "Mise à jour du fichier .env avec les deux bases de données..."

# Lire le contenu actuel
$envContent = Get-Content .env -Raw

# Mettre à jour la connexion principale
$envContent = $envContent -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=mysql'
$envContent = $envContent -replace 'DB_HOST=.*', 'DB_HOST=127.0.0.1'
$envContent = $envContent -replace 'DB_PORT=.*', 'DB_PORT=3306'
$envContent = $envContent -replace 'DB_DATABASE=.*', "DB_DATABASE=$dbName"
$envContent = $envContent -replace 'DB_USERNAME=.*', "DB_USERNAME=$dbUser"
$envContent = $envContent -replace 'DB_PASSWORD=.*', "DB_PASSWORD=$dbPassPlain"

# Ajouter la configuration ChronoFront si elle n'existe pas
if ($envContent -notmatch "CHRONOFRONT_DB_") {
    $chronoConfig = @"

# Configuration Base de Données ChronoFront (Chronométrage)
CHRONOFRONT_DB_HOST=127.0.0.1
CHRONOFRONT_DB_PORT=3306
CHRONOFRONT_DB_DATABASE=$chronoDbName
CHRONOFRONT_DB_USERNAME=$chronoDbUser
CHRONOFRONT_DB_PASSWORD=$chronoDbPassPlain
"@
    $envContent = $envContent + $chronoConfig
} else {
    # Mettre à jour si existe déjà
    $envContent = $envContent -replace 'CHRONOFRONT_DB_DATABASE=.*', "CHRONOFRONT_DB_DATABASE=$chronoDbName"
    $envContent = $envContent -replace 'CHRONOFRONT_DB_USERNAME=.*', "CHRONOFRONT_DB_USERNAME=$chronoDbUser"
    $envContent = $envContent -replace 'CHRONOFRONT_DB_PASSWORD=.*', "CHRONOFRONT_DB_PASSWORD=$chronoDbPassPlain"
}

$envContent | Set-Content .env

Write-Success "Fichier .env configuré avec les 2 bases de données"

# ========================================
# ETAPE 7 : Création des bases de données
# ========================================
Write-Step "Création des bases de données..."

# Créer la base de données principale
Write-Host "`n  Création de la base '$dbName' (Site ATS-Sport)..." -ForegroundColor Cyan
$createDb = "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if ([string]::IsNullOrWhiteSpace($dbPassPlain)) {
    mysql -u $dbUser -e $createDb 2>&1 | Out-Null
} else {
    mysql -u $dbUser -p"$dbPassPlain" -e $createDb 2>&1 | Out-Null
}

if ($LASTEXITCODE -eq 0) {
    Write-Success "Base de données principale '$dbName' créée"
} else {
    Write-Warning "Impossible de créer la base automatiquement. Créez-la manuellement via phpMyAdmin"
    Write-Host "  Nom de la base : $dbName" -ForegroundColor Yellow
    Read-Host "`nAppuyez sur Entrée quand la base est créée..."
}

# Créer la base de données ChronoFront
Write-Host "`n  Création de la base '$chronoDbName' (ChronoFront)..." -ForegroundColor Cyan
$createChronoDb = "CREATE DATABASE IF NOT EXISTS $chronoDbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if ([string]::IsNullOrWhiteSpace($chronoDbPassPlain)) {
    mysql -u $chronoDbUser -e $createChronoDb 2>&1 | Out-Null
} else {
    mysql -u $chronoDbUser -p"$chronoDbPassPlain" -e $createChronoDb 2>&1 | Out-Null
}

if ($LASTEXITCODE -eq 0) {
    Write-Success "Base de données ChronoFront '$chronoDbName' créée"
} else {
    Write-Warning "Impossible de créer la base automatiquement. Créez-la manuellement via phpMyAdmin"
    Write-Host "  Nom de la base : $chronoDbName" -ForegroundColor Yellow
    Read-Host "`nAppuyez sur Entrée quand la base est créée..."
}

# ========================================
# ETAPE 8 : Migrations - Base Principale
# ========================================
Write-Step "Création des tables - Base PRINCIPALE (Site ATS-Sport)..."

php artisan migrate --database=mysql --force
if ($LASTEXITCODE -eq 0) {
    Write-Success "Tables principales créées (users, cache, jobs, etc.)"
} else {
    Write-Warning "Erreur lors de la création des tables principales"
    Write-Host "  Vérifiez vos paramètres de connexion dans .env" -ForegroundColor Yellow
}

# ========================================
# ETAPE 9 : Migrations - Base ChronoFront
# ========================================
Write-Step "Création des tables - Base CHRONOFRONT (Chronométrage)..."

php artisan migrate --database=chronofront --force
if ($LASTEXITCODE -eq 0) {
    Write-Success "Tables ChronoFront créées (events, races, entrants, results, etc.)"
} else {
    Write-Error "Erreur lors de la création des tables ChronoFront"
    Write-Host "  Vérifiez vos paramètres CHRONOFRONT_DB_* dans .env" -ForegroundColor Yellow
    exit 1
}

# ========================================
# ETAPE 10 : Seeders
# ========================================
Write-Step "Initialisation des catégories FFA (dans base ChronoFront)..."

php artisan db:seed --class=CategorySeeder --force
if ($LASTEXITCODE -eq 0) {
    Write-Success "14 catégories FFA créées dans la base ChronoFront"
} else {
    Write-Warning "Erreur lors de la création des catégories"
}

# ========================================
# ETAPE 11 : Compilation Assets (optionnel)
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

Write-Host "`n✅ Configuration réussie avec 2 bases de données séparées :" -ForegroundColor Cyan
Write-Host "  • Base principale (site)     : $dbName" -ForegroundColor White
Write-Host "  • Base ChronoFront (chrono)  : $chronoDbName" -ForegroundColor White

Write-Host "`nPour démarrer le serveur :" -ForegroundColor Yellow
Write-Host "  .\start.ps1" -ForegroundColor White
Write-Host "`nOu manuellement :" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor White

Write-Host "`nAccès à ChronoFront :" -ForegroundColor Yellow
Write-Host "  http://localhost:8000/chronofront" -ForegroundColor White

Write-Host "`nAccès au site ATS-Sport :" -ForegroundColor Yellow
Write-Host "  http://localhost:8000" -ForegroundColor White

Write-Host "`n" -ForegroundColor Green
