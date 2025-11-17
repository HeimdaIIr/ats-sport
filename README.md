# 🏃 ATS Sport - Plateforme de Gestion d'Événements Sportifs

Application web Laravel complète pour la gestion d'événements sportifs avec module de chronométrage professionnel **ChronoFront** intégré.

---

## 📊 Vue d'Ensemble

**ATS Sport** est une plateforme complète qui permet aux organisateurs d'événements sportifs de :
- Créer et gérer des événements sportifs
- Gérer les inscriptions de participants
- Chronométrer les courses en temps réel
- Générer des classements automatiques
- Exporter les résultats

### 🎯 Modules Principaux

1. **Site Public ATS Sport** - Consultation des événements et résultats
2. **Espace Organisateur** - Création et gestion d'événements
3. **ChronoFront** - Module de chronométrage professionnel ⚡ NOUVEAU !

---

## 🏁 ChronoFront - Module de Chronométrage

ChronoFront est un module professionnel de chronométrage sportif intégré, migré depuis .NET Blazor vers Laravel.

### ✅ Fonctionnalités ChronoFront

- **Import CSV massif** (testé avec 2000+ participants)
- **Génération automatique tags RFID** (format: 2000XXX)
- **Attribution automatique catégories FFA** selon âge/sexe
- **Chronométrage temps réel** avec calculs automatiques
- **14 catégories FFA 2025** pré-configurées
- **Export CSV résultats**
- **API REST complète** (30+ endpoints)

---

## 🚀 Installation

```bash
# Cloner le repository
git clone https://github.com/HeimdaIIr/ats-sport.git
cd ats-sport

# Installer dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données (configurer .env d'abord)
php artisan migrate
php artisan db:seed --class=CategorySeeder

# Lancer serveur
php artisan serve
```

Application accessible sur `http://localhost:8000`

---

## 🌐 Accès aux Modules

### ChronoFront - Module Chronométrage
- **Dashboard** : http://localhost:8000/chronofront
- **Événements** : http://localhost:8000/chronofront/events
- **Participants** : http://localhost:8000/chronofront/entrants
- **Chronométrage** : http://localhost:8000/chronofront/timing
- **Résultats** : http://localhost:8000/chronofront/results

### Site Public ATS Sport
- **Accueil** : http://localhost:8000
- **Résultats** : http://localhost:8000/resultats

### Espace Organisateur
- **Dashboard** : http://localhost:8000/organisateur

---

## 📡 API REST ChronoFront

API complète disponible sur `/api`

```
GET    /api/events                      # Liste événements
POST   /api/events                      # Créer événement
GET    /api/races                       # Liste épreuves
POST   /api/races/{id}/start            # Démarrer épreuve
GET    /api/entrants                    # Liste participants
POST   /api/entrants/import             # Import CSV massif
POST   /api/results/time                # Ajouter temps
POST   /api/results/race/{id}/recalculate  # Recalculer positions
GET    /api/results/race/{id}/export    # Export CSV
POST   /api/categories/init-ffa         # Init catégories FFA
GET    /api/health                      # Health check
```

**Documentation complète** : Voir `CHRONOFRONT_LARAVEL_README.md`

---

## 📝 Import CSV Participants

Format supporté :
```csv
dossard,nom,prenom,sexe,date_naissance,email,club
3,DUPONT,Jean,M,1985-05-15,jean@email.com,AS SETE
```

✅ Génération auto tags RFID  
✅ Attribution auto catégories FFA  
✅ Support 2000+ participants

---

## 🔧 Workflow Rapide

1. Créer événement → `POST /api/events`
2. Créer épreuve → `POST /api/races`
3. Importer participants → `POST /api/entrants/import`
4. Démarrer épreuve → `POST /api/races/{id}/start`
5. Chronométrer → `POST /api/results/time`
6. Exporter résultats → `GET /api/results/race/{id}/export`

---

## 📊 État du Projet

✅ **API REST complète** (100%)  
✅ **Base de données** (100%)  
✅ **Modèles Eloquent** (100%)  
✅ **Import CSV** (100%)  
✅ **Calculs automatiques** (100%)  
⏳ **Frontend web** (30%)  
⏳ **WebSockets** (0%)

---

## 🎉 Prêt pour Production

L'API REST ChronoFront est **100% fonctionnelle** et prête à gérer des événements avec 2000+ participants !

---

## 📞 Support

**Documentation ChronoFront** : `CHRONOFRONT_LARAVEL_README.md`  
**Repository** : https://github.com/HeimdaIIr/ats-sport
