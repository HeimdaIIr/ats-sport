# ChronoFront - Guide de Test Rapide

## ⚠️ PROBLÈMES RÉSOLUS!

**Bugs trouvés et corrigés:**

### 1. Import CSV - JavaScript fixes (`entrants-import.blade.php`)
- ❌ `data.data.forEach` → ✅ `events.forEach`
- ❌ `event.event_date` → ✅ `event.date_start`

### 2. Dropdowns Événements - Champ date incorrect
- ❌ `event.event_date` → ✅ `event.date_start`
- **Fichiers corrigés:**
  - `races.blade.php` (ligne 148)
  - `waves.blade.php` (ligne 155)
- **Impact:** Les événements créés apparaissent maintenant dans tous les dropdowns!

## 🚀 Setup Rapide (3 minutes)

### Étape 1: Démarrer MySQL (si pas déjà fait)

```bash
# Vérifier si MySQL tourne
sudo systemctl status mysql

# Si pas démarré
sudo systemctl start mysql
```

### Étape 2: Exécuter les migrations

```bash
cd /home/user/ats-sport

# Exécuter TOUTES les migrations ChronoFront
php artisan migrate --path=database/migrations/chronofront

# Vous devriez voir 13 migrations s'exécuter
```

### Étape 3: Créer les données de test

```bash
# 1. Créer les catégories FFA (14 catégories)
php artisan db:seed --class=CategorySeeder

# 2. Créer un événement et 3 courses de test
php artisan db:seed --class=TestDataSeeder
```

### Étape 4: Démarrer Laravel

```bash
php artisan serve --port=8000
```

### Étape 5: Accéder à l'application

Ouvrir dans le navigateur: **http://localhost:8000/chronofront**

---

## 🧪 Test de l'Import CSV

### Option A: Utiliser le fichier CSV de test fourni

1. Aller sur: http://localhost:8000/chronofront/entrants/import

2. Sélectionner l'événement: **"Semi-Marathon de Sète 2025"**

3. Télécharger le fichier de test:
   ```bash
   # Le fichier est déjà créé ici:
   http://localhost:8000/test_import.csv
   ```

4. Glisser-déposer le fichier dans la zone de drop

5. Cliquer sur **"Importer les participants"**

6. ✅ Vous devriez voir:
   - 10 participants importés
   - 0 erreurs
   - 3 courses détectées (Semi-Marathon, 10 km, Trail 15 km)

### Option B: Créer votre propre CSV

1. Télécharger le template depuis l'interface: bouton **"Télécharger le template"**

2. Ouvrir avec Excel/LibreOffice

3. Ajouter vos données selon ce format:
   ```csv
   "DOSSARD","NOM","PRENOM","SEXE","NAISSANCE","PARCOURS","IDPARCOURS"
   "1","DUPONT","Jean","M","15/03/1985","Semi-Marathon","1"
   ```

4. Sauvegarder en CSV

5. Importer

---

## 📋 Tests Complets

### Test 1: Événements ✅

```bash
# URL: http://localhost:8000/chronofront/events

# Actions à tester:
1. Cliquer "Nouvel événement"
2. Remplir le formulaire
3. Sauvegarder
4. Vérifier qu'il apparaît dans la liste
```

### Test 2: Courses ✅

```bash
# URL: http://localhost:8000/chronofront/races

# Actions à tester:
1. Sélectionner un événement dans le filtre
2. Cliquer "Nouvelle course"
3. Remplir: nom, distance, heure départ
4. Sauvegarder
5. Vérifier la carte s'affiche
```

### Test 3: Import CSV ✅ (CORRIGÉ)

```bash
# URL: http://localhost:8000/chronofront/entrants/import

# Actions à tester:
1. Sélectionner événement
2. Glisser-déposer test_import.csv
3. Boutons "Valider" et "Importer" doivent s'activer
4. Cliquer "Importer"
5. Vérifier les statistiques
```

### Test 4: Participants ✅

```bash
# URL: http://localhost:8000/chronofront/entrants

# Actions à tester:
1. Vérifier la liste des 10 participants importés
2. Tester les filtres (Course, Sexe, Catégorie)
3. Tester la recherche
4. Vérifier les statistiques en haut
```

### Test 5: Catégories FFA ✅

```bash
# URL: http://localhost:8000/chronofront/categories

# Actions à tester:
1. Vérifier les 14 catégories FFA
2. Vérifier les codes (SEM, V1M, V1F, etc.)
3. Tester le filtre par sexe
4. Vérifier le compteur de participants
```

### Test 6: Vagues ✅

```bash
# URL: http://localhost:8000/chronofront/waves

# Actions à tester:
1. Sélectionner une course
2. Créer une vague avec capacité max
3. Vérifier la barre de progression
4. Supprimer/modifier une vague
```

### Test 7: Chronométrage (nécessite setup RFID)

```bash
# URL: http://localhost:8000/chronofront/timing

# Note: Nécessite de créer des timing_points d'abord
# Voir CHRONOFRONT_SETUP.md pour la configuration RFID
```

### Test 8: Résultats (nécessite détections RFID)

```bash
# URL: http://localhost:8000/chronofront/results

# Note: Nécessite des détections RFID d'abord
# Voir CHRONOFRONT_SETUP.md pour le calcul des résultats
```

---

## 🐛 Si quelque chose ne marche pas

### Erreur: "Aucun événement trouvé"

```bash
# Créer des données de test
php artisan db:seed --class=TestDataSeeder
```

### Erreur: "Connection refused"

```bash
# Vérifier que MySQL tourne sur le bon port
sudo netstat -tlnp | grep 3012

# Vérifier .env
cat .env | grep DB_PORT
# Devrait afficher: DB_PORT=3012
```

### Les boutons d'import restent désactivés

```bash
# 1. Vérifier qu'un événement est sélectionné
# 2. Vérifier qu'un fichier CSV est sélectionné
# 3. Ouvrir la console navigateur (F12) pour voir les erreurs JavaScript
```

### Erreur lors de l'import

```bash
# Vider le cache
php artisan cache:clear
php artisan route:clear

# Redémarrer le serveur
php artisan serve --port=8000
```

---

## 📊 Vérifications Rapides

### Vérifier les tables créées

```bash
php artisan tinker

>>> DB::connection('chronofront')->table('events')->count();
# Devrait retourner >= 1

>>> DB::connection('chronofront')->table('categories')->count();
# Devrait retourner 14

>>> DB::connection('chronofront')->table('entrants')->count();
# Devrait retourner 10 (après import CSV de test)
```

### Vérifier les routes API

```bash
php artisan route:list | grep -i import
# Devrait montrer 4 routes d'import
```

### Vérifier que le serveur tourne

```bash
curl http://localhost:8000/api/events
# Devrait retourner du JSON avec les événements
```

---

## ✅ Checklist Fonctionnalités

- [x] Événements - CRUD complet
- [x] Courses - CRUD complet avec filtres
- [x] Participants - Liste avec filtres
- [x] Import CSV - **CORRIGÉ** ✅
- [x] Catégories FFA - 14 catégories
- [x] Vagues - CRUD avec capacité
- [ ] Chronométrage RFID - Nécessite config Raspberry Pi
- [ ] Résultats - Nécessite détections RFID

---

## 🎯 Prochaines Étapes

1. **Tester l'import CSV** (maintenant fonctionnel!)
2. Vérifier que les participants sont bien créés
3. Configurer le Raspberry Pi + SportLab 2.0 (pour RFID)
4. Tester le chronométrage en temps réel
5. Calculer les résultats

---

**Date:** 16 novembre 2025
**Version:** 1.1 (Bug fixes)
