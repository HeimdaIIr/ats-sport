# 🚀 Guide d'Installation Rapide - ATS-Sport + ChronoFront

## 🗄️ Architecture des Bases de Données

**IMPORTANT:** Ce projet utilise **DEUX bases de données séparées** :

1. **Base principale `ats_sport`**
   - Données du site ATS-Sport (utilisateurs, inscriptions, résultats des compétitions, etc.)
   - Tables : users, cache, jobs, sessions, etc.

2. **Base ChronoFront `ats_sport_chronofront`**
   - Données de chronométrage uniquement (événements, courses, participants, temps)
   - Tables : events, races, entrants, results, categories, waves, screens, classements
   - Cette séparation permet de gérer 1000+ courses par an sans impacter le site principal

**Pourquoi 2 bases ?** Les données de chronométrage sont volumineuses et fréquemment manipulées. Les séparer garantit :
- 🔒 Aucun risque de mélange avec les données du site
- ⚡ Meilleures performances
- 🧹 Maintenance facilitée (nettoyage, backup séparés)

---

## ⚡ Installation Automatique (Recommandé)

### Prérequis

Avant de commencer, assurez-vous d'avoir installé :
- **PHP 8.2+** (avec les extensions : pdo_mysql, mbstring, openssl, xml)
- **Composer** (gestionnaire de dépendances PHP)
- **MySQL** ou **MariaDB**
- **Git**

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/HeimdaIIr/ats-sport.git
   cd ats-sport
   ```

2. **Lancer l'installation automatique**
   ```powershell
   .\install.ps1
   ```

   Le script va :
   - ✅ Vérifier les prérequis (PHP, Composer, MySQL)
   - ✅ Installer les dépendances PHP
   - ✅ Créer et configurer le fichier `.env`
   - ✅ Vous demander les informations pour **la base principale** (site)
   - ✅ Vous demander les informations pour **la base ChronoFront** (chrono)
   - ✅ Créer automatiquement les 2 bases de données
   - ✅ Créer les tables du site principal (users, cache, etc.)
   - ✅ Créer les 8 tables ChronoFront (dans base séparée)
   - ✅ Initialiser les 14 catégories FFA

3. **Démarrer le serveur**
   ```powershell
   .\start.ps1
   ```

4. **Accéder à l'application**
   - Site ATS-Sport : http://localhost:8000
   - ChronoFront : http://localhost:8000/chronofront

---

## 🛠️ Installation Manuelle

Si vous préférez installer manuellement, voici les étapes détaillées :

### 1. Cloner le projet
```bash
git clone https://github.com/HeimdaIIr/ats-sport.git
cd ats-sport
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configuration de l'environnement
```bash
# Copier le fichier d'exemple
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Créer les 2 bases de données

Dans MySQL/phpMyAdmin, exécutez :
```sql
-- Base de données principale (site)
CREATE DATABASE ats_sport CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Base de données ChronoFront (chronométrage)
CREATE DATABASE ats_sport_chronofront CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Configurer le fichier .env

Éditez le fichier `.env` et configurez vos paramètres MySQL :

```env
# Base de données PRINCIPALE (Site ATS-Sport)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ats_sport
DB_USERNAME=root
DB_PASSWORD=

# Base de données CHRONOFRONT (Chronométrage)
CHRONOFRONT_DB_HOST=127.0.0.1
CHRONOFRONT_DB_PORT=3306
CHRONOFRONT_DB_DATABASE=ats_sport_chronofront
CHRONOFRONT_DB_USERNAME=root
CHRONOFRONT_DB_PASSWORD=
```

### 6. Exécuter les migrations - Base principale
```bash
php artisan migrate --database=mysql
```

Cela créera les tables du site (users, cache, jobs, etc.).

### 7. Exécuter les migrations - Base ChronoFront
```bash
php artisan migrate --database=chronofront
```

Cela créera les 8 tables ChronoFront :
- `events` - Événements sportifs
- `categories` - Catégories FFA
- `races` - Épreuves/courses
- `waves` - Vagues de départ
- `entrants` - Participants
- `results` - Résultats chronométrés
- `screens` - Écrans d'affichage
- `classements` - Classements

### 8. Initialiser les catégories FFA
```bash
php artisan db:seed --class=CategorySeeder
```

Cela créera les 14 catégories FFA 2025 **dans la base ChronoFront** :
- Hommes : SEM, V1M, V2M, V3M, V4M, ESM, JUM, CAM
- Femmes : SEF, V1F, V2F, V3F, V4F, ESF, JUF, CAF

### 9. Démarrer le serveur
```bash
php artisan serve
```

---

## 🎯 Démarrage Rapide - Workflow

Une fois l'installation terminée, voici comment utiliser ChronoFront :

### 1. Créer un événement
- Accédez à : http://localhost:8000/chronofront/events
- Cliquez sur "Nouvel événement"
- Remplissez les informations (nom, dates, lieu)

### 2. Créer une épreuve
Via l'API REST :
```bash
POST http://localhost:8000/api/races
{
  "event_id": 1,
  "name": "10 km",
  "type": "1_passage",
  "distance": 10.0,
  "laps": 1
}
```

### 3. Importer les participants
- Accédez à : http://localhost:8000/chronofront/entrants/import
- Importez votre fichier CSV

**Format CSV attendu :**
```csv
dossard,nom,prenom,sexe,date_naissance,email,club
3,DUPONT,Jean,M,1985-05-15,jean@email.com,AS SETE
4,MARTIN,Marie,F,1990-03-20,marie@email.com,RC TOULOUSE
```

✅ Les tags RFID seront générés automatiquement (format: 2000003, 2000004...)
✅ Les catégories FFA seront attribuées automatiquement selon l'âge et le sexe

### 4. Démarrer l'épreuve
```bash
POST http://localhost:8000/api/races/1/start
```

### 5. Chronométrer
```bash
POST http://localhost:8000/api/results/time
{
  "race_id": 1,
  "rfid_tag": "2000003",
  "raw_time": "2024-11-15 10:15:30"
}
```

### 6. Recalculer les positions
```bash
POST http://localhost:8000/api/results/race/1/recalculate
```

### 7. Exporter les résultats
```bash
GET http://localhost:8000/api/results/race/1/export
```

---

## 📡 Documentation API

Pour la documentation complète de l'API REST (30+ endpoints), consultez :
- `CHRONOFRONT_LARAVEL_README.md`
- README.md (section API)

---

## ❗ Dépannage

### Erreur : "PHP n'est pas reconnu"
- Vérifiez que PHP est installé et ajouté au PATH système
- Téléchargez PHP : https://windows.php.net/download/

### Erreur : "Composer n'est pas reconnu"
- Installez Composer : https://getcomposer.org/download/

### Erreur : "Connection refused" (MySQL)
- Vérifiez que MySQL/XAMPP/WAMP est démarré
- Vérifiez les paramètres dans `.env` (DB_* et CHRONOFRONT_DB_*)

### Erreur : "Table doesn't exist"
- Pour le site : Exécutez `php artisan migrate --database=mysql`
- Pour ChronoFront : Exécutez `php artisan migrate --database=chronofront`

### Erreur : "No categories found"
- Exécutez le seeder : `php artisan db:seed --class=CategorySeeder`

### Erreur : "Database 'ats_sport_chronofront' doesn't exist"
- Créez la base manuellement via phpMyAdmin
- Ou via MySQL CLI : `CREATE DATABASE ats_sport_chronofront CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`

---

## 🔧 Commandes Utiles

### Migrations
```bash
# Voir l'état des migrations
php artisan migrate:status

# Voir l'état pour ChronoFront
php artisan migrate:status --database=chronofront

# Rollback dernière migration ChronoFront
php artisan migrate:rollback --database=chronofront

# Réinitialiser toutes les migrations ChronoFront
php artisan migrate:fresh --database=chronofront
```

### Base de données
```bash
# Afficher les infos de connexion
php artisan db:show

# Afficher les infos de connexion ChronoFront
php artisan db:show --database=chronofront
```

---

## 📞 Support

- **Documentation** : README.md et CHRONOFRONT_LARAVEL_README.md
- **Repository** : https://github.com/HeimdaIIr/ats-sport
- **Issues** : https://github.com/HeimdaIIr/ats-sport/issues

---

## ✅ Checklist de Vérification

Après l'installation, vérifiez que :

- [ ] Le fichier `.env` contient les paramètres pour les 2 bases de données
- [ ] Les 2 bases de données existent dans MySQL
- [ ] Le serveur démarre sans erreur
- [ ] http://localhost:8000 affiche le site ATS-Sport
- [ ] http://localhost:8000/chronofront affiche le tableau de bord ChronoFront
- [ ] Les 4 statistiques s'affichent (Événements, Épreuves, Participants, Résultats)
- [ ] Vous pouvez créer un nouvel événement
- [ ] La page d'import CSV est accessible

---

**Bon chronométrage ! 🏃‍♂️⏱️**
