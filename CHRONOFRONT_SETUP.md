# ChronoFront - Guide de Configuration Complète

## Vue d'ensemble

ChronoFront est un système de chronométrage RFID intégré dans Laravel 12, conçu pour fonctionner avec le lecteur RFID SportLab 2.0 sur Raspberry Pi.

## État actuel de l'application

✅ **Composants complétés:**
- Base de données architecture (dual database: ats_sport + ats_sport_chronofront)
- 10 modèles Eloquent complets
- 10 contrôleurs API REST
- 3 services métier (Import CSV, RFID, Results)
- 6 interfaces web complètes
- Migrations database complètes
- Seeder catégories FFA (14 catégories standard)
- Routes web et API configurées

## Architecture Technique

### Base de données
- **Base principale:** \`ats_sport\` (données générales)
- **Base ChronoFront:** \`ats_sport_chronofront\` (chronométrage RFID)
- **Port MySQL:** 3012 (configuré dans .env)

### Tables ChronoFront
- \`events\` - Événements sportifs
- \`races\` - Épreuves/courses
- \`categories\` - Catégories FFA (SEM, V1M, V1F, etc.)
- \`waves\` - Vagues de départ
- \`entrants\` - Participants inscrits
- \`timing_points\` - Points de chronométrage (départ, intermédiaires, arrivée)
- \`race_times\` - Détections RFID brutes
- \`results\` - Résultats calculés avec classements
- \`classements\` - Classements archivés
- \`screens\` - Configuration écrans d'affichage

---

## Étapes d'installation (IMPORTANT - À FAIRE AVANT DE TESTER)

### 1. Démarrer MySQL sur le port 3012

\`\`\`bash
# Vérifier que MySQL est démarré
sudo systemctl status mysql

# Si MySQL n'est pas sur le port 3012, modifier la configuration
sudo nano /etc/mysql/my.cnf
# Ajouter: port = 3012

# Redémarrer MySQL
sudo systemctl restart mysql
\`\`\`

### 2. Exécuter les migrations ChronoFront

\`\`\`bash
# Exécuter toutes les migrations ChronoFront
php artisan migrate --path=database/migrations/chronofront

# Vérifier que toutes les tables sont créées
php artisan tinker
>>> \\DB::connection('chronofront')->table('events')->count();
\`\`\`

### 3. Initialiser les catégories FFA

\`\`\`bash
# Via le seeder
php artisan db:seed --class=CategorySeeder

# Vérifier
php artisan tinker
>>> \\App\\Models\\ChronoFront\\Category::count();
# Devrait retourner 14
\`\`\`

### 4. Démarrer le serveur Laravel

\`\`\`bash
php artisan serve --port=8000
\`\`\`

### 5. Accéder à ChronoFront

**Interface web:** http://localhost:8000/chronofront

**Pages disponibles:**
- \`/chronofront\` - Tableau de bord
- \`/chronofront/events\` - Gestion événements
- \`/chronofront/races\` - Gestion courses
- \`/chronofront/entrants\` - Participants
- \`/chronofront/entrants/import\` - Import CSV
- \`/chronofront/waves\` - Vagues de départ
- \`/chronofront/categories\` - Catégories FFA
- \`/chronofront/timing\` - Chronométrage temps réel
- \`/chronofront/results\` - Résultats et classements

---

## Utilisation - Workflow complet

### Étape 1: Créer un événement

1. Aller sur \`/chronofront/events\`
2. Cliquer "Nouvel événement"
3. Remplir:
   - Nom de l'événement
   - Date de début
   - Date de fin
   - Lieu
   - Description
4. Sauvegarder

### Étape 2: Créer les courses/épreuves

1. Aller sur \`/chronofront/races\`
2. Sélectionner l'événement
3. Créer chaque course (ex: 10km, Semi-marathon, etc.)
4. Pour chaque course, remplir:
   - Nom de la course
   - Distance (en km)
   - Heure de départ (optionnel)
   - Description

### Étape 3: Importer les participants (CSV)

1. Aller sur \`/chronofront/entrants/import\`
2. Sélectionner l'événement
3. Télécharger le template CSV si besoin
4. Préparer le fichier CSV avec ces colonnes:
   \`\`\`csv
   "DOSSARD","NOM","PRENOM","SEXE","NAISSANCE","PARCOURS","IDPARCOURS"
   "1","DUPONT","Jean","M","15/03/1985","Semi Marathon","1"
   \`\`\`
5. Importer le fichier
6. Vérifier les statistiques d'import

**Note:** Tags RFID générés automatiquement: 2 + dossard sur 6 chiffres (ex: dossard 1 → 2000001)

---

## API REST - Endpoints principaux

### Import CSV
- \`POST /api/events/{eventId}/import-csv\` - Importer un CSV
- \`POST /api/import/validate-csv\` - Valider un CSV sans importer
- \`GET /api/import/download-template\` - Télécharger le template

### RFID Detections
- \`POST /api/rfid/detection\` - Enregistrer une détection RFID
- \`POST /api/rfid/batch\` - Enregistrer un batch de détections
- \`POST /api/rfid/stream/{timingPointId}\` - Stream continu (Raspberry Pi)
- \`GET /api/rfid/timing-point/{id}/recent?limit=50\` - Détections récentes

### Results
- \`POST /api/results/race/{raceId}/calculate\` - Calculer résultats
- \`GET /api/results/race/{raceId}/scratch\` - Classement général
- \`GET /api/results/race/{raceId}/gender/{M|F}\` - Classement par genre
- \`GET /api/results/race/{raceId}/statistics\` - Statistiques course

---

## Dépannage

### Erreur "Connection refused" MySQL

\`\`\`bash
# Vérifier que MySQL écoute sur le bon port
sudo netstat -tlnp | grep 3012

# Vérifier .env
DB_PORT=3012
CHRONOFRONT_DB_PORT=3012
\`\`\`

### Erreur "Route not found"

\`\`\`bash
php artisan route:clear
php artisan route:cache
\`\`\`

---

**Version:** 1.0.0  
**Date:** 16 novembre 2025  
**Laravel:** 12.x
