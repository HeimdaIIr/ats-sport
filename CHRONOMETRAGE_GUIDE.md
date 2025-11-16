# 📊 Guide du Chronométrage ChronoFront

## 🎯 Workflow complet

### Étape 1: Préparation (AVANT la course)

#### 1.1 Créer l'événement ✅
- Aller sur `/chronofront/events`
- Cliquer "Nouvel événement"
- Remplir: nom, date début/fin, lieu

#### 1.2 Créer les courses ✅
- Aller sur `/chronofront/races`
- Cliquer "Nouvelle Course"
- Sélectionner l'événement
- Remplir: nom (ex: "10 km"), distance, heure de départ

#### 1.3 Importer les participants 📝
- Aller sur `/chronofront/entrants-import`
- Sélectionner l'événement
- Upload fichier CSV au format SportLab
- Le système génère automatiquement:
  - Tags RFID (format: 2XXXXXX à partir du dossard)
  - Attribution des catégories FFA selon âge/sexe
  - Affectation aux courses

**Format CSV requis:**
```csv
"DOSSARD","NOM","PRENOM","SEXE","NAISSANCE","PARCOURS","IDPARCOURS","CLUB","LICENCE"
"1","DUPONT","Jean","M","15/03/1985","Semi-Marathon","1","AS Sète","PB12345"
```

#### 1.4 Créer les points de chronométrage
- **Option A:** Via l'interface (à développer si nécessaire)
- **Option B:** Via API POST `/api/timing-points`

**Points requis minimum:**
- **START** (Départ) - `type: 'start'`
- **FINISH** (Arrivée) - `type: 'finish'`

**Points optionnels:**
- **INTERMEDIATE** - Points de passage intermédiaires

**Exemple création via API:**
```javascript
// Point de départ
await axios.post('/timing-points', {
  race_id: 1,
  name: 'Départ',
  type: 'start',
  distance: 0
});

// Point d'arrivée
await axios.post('/timing-points', {
  race_id: 1,
  name: 'Arrivée',
  type: 'finish',
  distance: 21.1
});
```

---

### Étape 2: Chronométrage (PENDANT la course)

#### 2.1 Chronométrage RFID automatique (RECOMMANDÉ)

**Page:** `/chronofront/timing`

**Matériel requis:**
- Lecteur RFID **SportLab 2.0**
- Tags RFID sur les dossards (générés automatiquement à l'import)

**Procédure:**

1. **Connecter le lecteur RFID SportLab 2.0** au Raspberry Pi
   - Le lecteur envoie les détections au format: `[TAG]:aYYYYMMDDHHMMSSmmm`
   - Exemple: `[TAG]:a20251116143025123` = Tag détecté le 16/11/2025 à 14:30:25.123

2. **Sur la page Chronométrage:**
   - Sélectionner la **Course**
   - Sélectionner le **Point de chronométrage** (Départ ou Arrivée)
   - Cliquer **"Démarrer"**

3. **Le système:**
   - Écoute les détections RFID via l'endpoint `/api/rfid/detection`
   - Associe automatiquement le tag RFID au participant (via `entrants.rfid_tag`)
   - Enregistre le temps dans la table `race_times`
   - Affiche en temps réel les passages dans le tableau

4. **Monitoring en temps réel:**
   - La page se rafraîchit automatiquement
   - Affiche: heure, dossard, nom, sexe, tag RFID
   - Compteur de détections

#### 2.2 Chronométrage manuel (BACKUP)

**Page:** `/chronofront/manual-timing`

**Si le système RFID tombe en panne:**

1. Sélectionner la course et le point de chronométrage
2. Saisir manuellement:
   - Numéro de dossard
   - Heure de passage (ou utiliser l'heure actuelle)
3. Le système recherche le participant par son dossard
4. Enregistre le temps dans `race_times` avec `detection_method: 'manual'`

**Avantages:**
- Pas de dépendance matérielle
- Utilisable en backup
- Peut être utilisé pour corriger des erreurs

---

### Étape 3: Calcul des résultats (APRÈS la course)

#### 3.1 Calcul automatique des temps

**Page:** `/chronofront/results`

**Procédure:**

1. Sélectionner la **Course**
2. Cliquer **"Calculer les résultats"**

**Le système calcule automatiquement:**

```
Temps de course = Temps FINISH - Temps START
```

**Pour chaque participant:**
- Trouve le temps au point START (départ)
- Trouve le temps au point FINISH (arrivée)
- Calcule le temps de course
- Génère 3 classements:
  - **Scratch** (général)
  - **Genre** (Hommes / Femmes)
  - **Catégorie** (V1M, SEF, etc.)

#### 3.2 Affichage des résultats

**3 types de classement:**

1. **Classement Scratch** (général)
   - Tous les participants
   - Classés du plus rapide au plus lent

2. **Classement par Genre**
   - Hommes séparément
   - Femmes séparément
   - Position dans leur catégorie de sexe

3. **Classement par Catégorie FFA**
   - Par catégorie d'âge (SEM, V1M, V2F, etc.)
   - 14 catégories au total

**Colonnes affichées:**
- Position (scratch, genre, catégorie)
- Dossard
- Nom + Prénom
- Sexe
- Catégorie
- Club
- Temps de course
- Vitesse moyenne

#### 3.3 Export des résultats

**Formats disponibles:**
- CSV (Excel)
- PDF
- Affichage web temps réel

---

## 🔧 Configuration RFID SportLab 2.0

### Format des détections SportLab

Le lecteur RFID SportLab 2.0 envoie les détections au format:

```
[TAG]:aYYYYMMDDHHMMSSmmm
```

**Exemple:**
```
[TAG]:a20251116143025123
```

**Décodage:**
- `[TAG]` = Préfixe fixe
- `a` = Indicateur
- `2025` = Année
- `11` = Mois
- `16` = Jour
- `14` = Heure
- `30` = Minutes
- `25` = Secondes
- `123` = Millisecondes

### Endpoint API pour réception

**POST** `/api/rfid/detection`

**Body:**
```json
{
  "timing_point_id": 1,
  "rfid_data": "[TAG]:a20251116143025123",
  "raw_timestamp": "2025-11-16 14:30:25.123"
}
```

**Le service RfidService:**
1. Parse le format SportLab
2. Extrait la date/heure précise
3. Trouve le participant via `entrants.rfid_tag`
4. Enregistre dans `race_times`

### Configuration Raspberry Pi

**Script Python pour lire le lecteur RFID:**

```python
import serial
import requests
import time

# Configuration
RFID_PORT = '/dev/ttyUSB0'  # Port série du lecteur
API_URL = 'http://localhost/api/rfid/detection'
TIMING_POINT_ID = 1  # ID du point de chronométrage

ser = serial.Serial(RFID_PORT, 9600, timeout=1)

while True:
    if ser.in_waiting:
        rfid_data = ser.readline().decode('utf-8').strip()

        if rfid_data.startswith('[TAG]:'):
            # Envoyer à l'API
            requests.post(API_URL, json={
                'timing_point_id': TIMING_POINT_ID,
                'rfid_data': rfid_data
            })
            print(f"Détection envoyée: {rfid_data}")

    time.sleep(0.1)
```

---

## 📝 À FAIRE pour que tout fonctionne

### 1. Tester l'import CSV ⚠️

**Action:** Importer le fichier `public/test_import.csv`

**Vérification:**
- [ ] Les 10 participants sont créés
- [ ] Les tags RFID sont générés (2000001, 2000002, etc.)
- [ ] Les catégories sont assignées automatiquement
- [ ] Les participants sont liés aux bonnes courses

### 2. Interface de création des Timing Points ⚠️

**Actuellement:** Pas d'interface web pour créer les points

**Options:**

**A. Ajouter une page simple** `/chronofront/timing-points`
- Liste des points de chronométrage
- Bouton "Nouveau point"
- Formulaire: course, nom, type (start/finish/intermediate), distance

**B. Les créer automatiquement** lors de la création d'une course
- Auto-créer "Départ" (type: start, distance: 0)
- Auto-créer "Arrivée" (type: finish, distance: X km)

**C. Les créer via Tinker** (temporaire)
```php
php artisan tinker
$race = \App\Models\ChronoFront\Race::find(1);
\App\Models\ChronoFront\TimingPoint::create([
    'race_id' => $race->id,
    'name' => 'Départ',
    'type' => 'start',
    'distance' => 0
]);
\App\Models\ChronoFront\TimingPoint::create([
    'race_id' => $race->id,
    'name' => 'Arrivée',
    'type' => 'finish',
    'distance' => $race->distance
]);
```

### 3. Vérifier les pages de chronométrage ⚠️

**timing.blade.php:**
- [ ] Les courses se chargent dans le dropdown
- [ ] Les timing points se chargent
- [ ] Le bouton "Démarrer" lance le monitoring

**manual-timing.blade.php:**
- [ ] Saisie manuelle fonctionne
- [ ] Recherche par dossard fonctionne

**results.blade.php:**
- [ ] Affichage des 3 classements
- [ ] Calcul des temps correct
- [ ] Export CSV/PDF

### 4. Test complet bout en bout 🎯

**Scénario de test:**

1. ✅ Créer événement "Test Marathon"
2. ✅ Créer course "10 km"
3. ⚠️ Créer 2 timing points (Départ + Arrivée)
4. ⚠️ Importer 10 participants via CSV
5. ⚠️ Simuler des détections RFID via API:
   ```bash
   # Départ participant dossard 1
   curl -X POST http://localhost/api/rfid/detection \
     -H "Content-Type: application/json" \
     -d '{"timing_point_id":1,"rfid_data":"[TAG]:a20251116100000000"}'

   # Arrivée participant dossard 1 (40 minutes plus tard)
   curl -X POST http://localhost/api/rfid/detection \
     -H "Content-Type: application/json" \
     -d '{"timing_point_id":2,"rfid_data":"[TAG]:a20251116104000000"}'
   ```
6. ⚠️ Calculer les résultats
7. ⚠️ Vérifier que le temps est bien 40:00

---

## 🚀 Quelle partie voulez-vous implémenter en premier?

**Option 1: Tester l'import CSV** (le plus simple)
- Vérifier que l'import fonctionne
- Voir les participants créés

**Option 2: Créer l'interface Timing Points** (nécessaire)
- Page pour gérer les points de chronométrage
- Sans ça, impossible de chronométrer

**Option 3: Tester le chronométrage manuel** (indépendant du RFID)
- Tester sans matériel RFID
- Saisie manuelle des temps

**Option 4: Configurer le RFID** (si vous avez le matériel)
- Script Raspberry Pi
- Test avec le lecteur SportLab

**Dites-moi par quoi vous voulez commencer!**
