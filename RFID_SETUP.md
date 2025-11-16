# 🎯 Guide Simplifié - Chronométrage RFID ChronoFront

## Votre Workflow Simplifié

### 1. TOP Départ ✅ (DÉJÀ FAIT)

**Page:** `/chronofront/top-depart`

- Sélectionner la course
- Cliquer "TOP MAINTENANT" au départ de la course
- L'heure est enregistrée dans `races.start_time`
- Modifiable en cas de faux départ

### 2. Réception RFID Arrivée (À CONFIGURER)

**Matériel:**
- Lecteur SportLab 2.0
- Lecteur RFID Impinj
- Raspberry Pi

**Format des détections:**
```
[TAG]:aYYYYMMDDHHMMSSmmm
```

Exemple:
```
[2000001]:a20251116143025123
```

Signifie: Tag RFID `2000001` détecté le 16/11/2025 à 14:30:25.123

### 3. Calcul Automatique

```
Temps final = Heure RFID arrivée - races.start_time (TOP départ)
```

---

## 📡 Configuration Raspberry Pi

### Script Python pour envoyer les détections RFID

Créer le fichier `/home/pi/rfid_reader.py`:

```python
#!/usr/bin/env python3
"""
Script de lecture RFID pour ChronoFront
Lit les détections du lecteur SportLab 2.0 et les envoie à l'API
"""

import serial
import requests
import time
import json
from datetime import datetime

# ===== CONFIGURATION =====
# URL de votre serveur ChronoFront
API_URL = 'http://192.168.1.100/api/rfid/detection-simple'

# Port série du lecteur RFID
RFID_PORT = '/dev/ttyUSB0'
RFID_BAUDRATE = 9600

# ID de la course (à changer pour chaque course)
RACE_ID = 1

# Timeout lecture série
TIMEOUT = 1

# ===== LOGGING =====
def log(message, level='INFO'):
    timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    print(f"[{timestamp}] [{level}] {message}")

# ===== CONNEXION SÉRIE =====
try:
    ser = serial.Serial(RFID_PORT, RFID_BAUDRATE, timeout=TIMEOUT)
    log(f"Connecté au lecteur RFID sur {RFID_PORT}")
except Exception as e:
    log(f"Erreur connexion série: {e}", 'ERROR')
    exit(1)

# ===== BOUCLE PRINCIPALE =====
log("Démarrage de la lecture RFID...")
log(f"Les détections seront envoyées à: {API_URL}")
log(f"Race ID: {RACE_ID}")
log("En attente de détections...\n")

detection_count = 0

while True:
    try:
        # Lire une ligne du port série
        if ser.in_waiting > 0:
            line = ser.readline().decode('utf-8', errors='ignore').strip()

            # Vérifier que c'est bien une détection RFID
            if line.startswith('[') and ']:a' in line:
                detection_count += 1
                log(f"#{detection_count} Détection RFID: {line}")

                # Envoyer à l'API ChronoFront
                try:
                    response = requests.post(
                        API_URL,
                        json={
                            'rfid': line,
                            'race_id': RACE_ID
                        },
                        timeout=5
                    )

                    if response.status_code == 201:
                        data = response.json()
                        if data.get('success'):
                            entrant = data['data']['entrant']
                            time_str = data['data']['finish_time']
                            log(f"✅ OK - Dossard {entrant['bib_number']} - {entrant['name']} - Temps: {time_str}", 'SUCCESS')
                        else:
                            log(f"⚠️  {data.get('message', 'Erreur inconnue')}", 'WARNING')
                    else:
                        log(f"❌ Erreur HTTP {response.status_code}: {response.text}", 'ERROR')

                except requests.exceptions.Timeout:
                    log("❌ Timeout API - serveur non accessible", 'ERROR')
                except requests.exceptions.ConnectionError:
                    log("❌ Erreur connexion - vérifier l'URL et le réseau", 'ERROR')
                except Exception as e:
                    log(f"❌ Erreur requête: {e}", 'ERROR')

        time.sleep(0.1)  # Petit délai pour éviter de saturer le CPU

    except KeyboardInterrupt:
        log("\nArrêt du script (Ctrl+C)")
        break
    except Exception as e:
        log(f"Erreur inattendue: {e}", 'ERROR')
        time.sleep(1)

# ===== FERMETURE =====
ser.close()
log(f"Script arrêté. {detection_count} détections traitées.")
```

### Rendre le script exécutable:

```bash
chmod +x /home/pi/rfid_reader.py
```

### Test du script:

```bash
python3 /home/pi/rfid_reader.py
```

Vous devriez voir:
```
[2025-11-16 14:30:25] [INFO] Connecté au lecteur RFID sur /dev/ttyUSB0
[2025-11-16 14:30:25] [INFO] Démarrage de la lecture RFID...
[2025-11-16 14:30:25] [INFO] Les détections seront envoyées à: http://192.168.1.100/api/rfid/detection-simple
[2025-11-16 14:30:25] [INFO] Race ID: 1
[2025-11-16 14:30:25] [INFO] En attente de détections...

[2025-11-16 14:30:26] [INFO] #1 Détection RFID: [2000001]:a20251116143025123
[2025-11-16 14:30:26] [SUCCESS] ✅ OK - Dossard 1 - Jean DUPONT - Temps: 00:45:12
```

### Lancer automatiquement au démarrage:

Créer le service systemd `/etc/systemd/system/chronofront-rfid.service`:

```ini
[Unit]
Description=ChronoFront RFID Reader
After=network.target

[Service]
Type=simple
User=pi
WorkingDirectory=/home/pi
ExecStart=/usr/bin/python3 /home/pi/rfid_reader.py
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

Activer le service:

```bash
sudo systemctl enable chronofront-rfid
sudo systemctl start chronofront-rfid
sudo systemctl status chronofront-rfid
```

---

## 🔧 Endpoint API à créer

Je vais créer un nouveau endpoint **simplifié** dans le RfidController:

**POST** `/api/rfid/detection-simple`

**Body:**
```json
{
  "rfid": "[2000001]:a20251116143025123",
  "race_id": 1
}
```

**Fonctionnement:**
1. Parse le format SportLab 2.0
2. Trouve le participant par son tag RFID `2000001`
3. Vérifie qu'il est inscrit à la course ID 1
4. Vérifie que la course a un TOP départ (`race.start_time`)
5. Calcule: `temps = timestamp_rfid - race.start_time`
6. Enregistre le résultat

**Réponse:**
```json
{
  "success": true,
  "message": "Passage enregistré",
  "data": {
    "entrant": {
      "id": 1,
      "bib_number": "1",
      "name": "Jean DUPONT"
    },
    "finish_time": "00:45:12",
    "timestamp": "2025-11-16 14:30:25"
  }
}
```

---

## 🎯 Avantages de ce système simplifié

✅ **Pas besoin de timing points** - Juste TOP départ + RFID arrivée
✅ **Configuration minimale** - Un seul endpoint API
✅ **Script Python simple** - Lecture série + POST HTTP
✅ **Calcul automatique** - Le serveur calcule le temps final
✅ **Logs détaillés** - Suivi en temps réel sur le Raspberry Pi
✅ **Restart automatique** - Le service redémarre en cas d'erreur

---

## ⚙️ Configuration réseau

### Trouver l'IP de votre serveur XAMPP:

```bash
ipconfig  # Sur Windows
ifconfig  # Sur Linux/Mac
```

Cherchez l'adresse IP locale (ex: `192.168.1.100`)

### Modifier le script Python:

Ligne 12, remplacer par votre IP:
```python
API_URL = 'http://192.168.1.100/api/rfid/detection-simple'
```

### Tester la connexion depuis le Raspberry Pi:

```bash
ping 192.168.1.100
```

Si ça fonctionne, le réseau est OK!

---

## 🧪 Test sans matériel RFID

Vous pouvez simuler une détection RFID avec curl:

```bash
curl -X POST http://localhost/api/rfid/detection-simple \
  -H "Content-Type: application/json" \
  -d '{
    "rfid": "[2000001]:a20251116143025123",
    "race_id": 1
  }'
```

---

## Prochaines étapes

1. **Je vais créer l'endpoint simplifié** `/api/rfid/detection-simple`
2. **Vous configurez le Raspberry Pi** avec le script Python
3. **On teste** avec des détections réelles

**Voulez-vous que je crée cet endpoint maintenant?**
