# 🐛 Guide de Débogage - Événements invisibles dans les dropdowns

## ✅ Corrections déjà appliquées

Les bugs JavaScript suivants ont été corrigés dans les fichiers:

- ✅ `resources/views/chronofront/entrants-import.blade.php:236`
- ✅ `resources/views/chronofront/races.blade.php:148`
- ✅ `resources/views/chronofront/waves.blade.php:155`

**Changement:** `event.event_date` → `event.date_start`

## 🔍 Étapes de diagnostic

### 1. Vérifier que le serveur est démarré

**Après chaque `git pull`, vous DEVEZ redémarrer le serveur!**

```bash
# Arrêter le serveur actuel (Ctrl+C si en cours)
# Puis redémarrer:
./start-chronofront.sh
```

Ou manuellement:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan serve --port=8000
```

### 2. Vider le cache du navigateur

**TRÈS IMPORTANT:** Les fichiers JavaScript sont souvent mis en cache par le navigateur.

- **Chrome/Edge:** `Ctrl+Shift+R` (Windows/Linux) ou `Cmd+Shift+R` (Mac)
- **Firefox:** `Ctrl+F5` (Windows/Linux) ou `Cmd+Shift+R` (Mac)

Ou ouvrir DevTools → Onglet Network → Cocher "Disable cache"

### 3. Vérifier la console JavaScript

1. Ouvrir DevTools (`F12`)
2. Aller dans l'onglet **Console**
3. Recharger la page (`Ctrl+Shift+R`)
4. Chercher des erreurs en rouge

**Erreurs possibles:**

❌ `Uncaught ReferenceError: event is not defined`
❌ `event.event_date is undefined`
❌ `Failed to load resource: the server responded with a status of 500`

### 4. Vérifier les requêtes API

1. Ouvrir DevTools (`F12`)
2. Aller dans l'onglet **Network**
3. Recharger la page
4. Chercher la requête `events` dans la liste
5. Cliquer dessus et voir la **Response**

**Réponse attendue:**
```json
[
  {
    "id": 1,
    "name": "Semi-Marathon de Sète 2025",
    "date_start": "2025-12-16T00:00:00.000000Z",
    "date_end": "2025-12-16T00:00:00.000000Z",
    "location": "Sète, France",
    "is_active": 1
  }
]
```

**Si la réponse est vide `[]`:**
→ Il n'y a pas d'événements dans la base de données!

### 5. Vérifier la base de données

```bash
php artisan tinker
```

Puis dans tinker:
```php
// Compter les événements
\App\Models\ChronoFront\Event::count();
// Doit afficher un nombre > 0

// Afficher tous les événements
\App\Models\ChronoFront\Event::all();

// Si count() = 0, créer des données de test:
exit
```

Créer des données de test:
```bash
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=TestDataSeeder
```

### 6. Tester l'API directement

Ouvrir dans le navigateur ou avec curl:

```bash
# Tester l'API events
curl http://localhost:8000/api/events

# Devrait retourner un JSON avec les événements
```

Si l'API retourne `[]` → Pas d'événements dans la DB → Exécuter les seeders (étape 5)

### 7. Vérifier les fichiers JavaScript (manuel)

Si après tout cela ça ne fonctionne toujours pas, vérifiez manuellement:

**Fichier: `resources/views/chronofront/races.blade.php`**
```javascript
// Ligne 148 doit contenir:
const option1 = new Option(`${event.name} (${new Date(event.date_start).toLocaleDateString('fr-FR')})`, event.id);
// PAS event.event_date ❌
```

**Fichier: `resources/views/chronofront/waves.blade.php`**
```javascript
// Ligne 155 doit contenir:
`${event.name} (${new Date(event.date_start).toLocaleDateString('fr-FR')})`,
// PAS event.event_date ❌
```

**Fichier: `resources/views/chronofront/entrants-import.blade.php`**
```javascript
// Ligne 236 doit contenir:
option.textContent = `${event.name} - ${new Date(event.date_start).toLocaleDateString('fr-FR')}`;
// PAS event.event_date ❌
```

## 🎯 Checklist de vérification

- [ ] MySQL est démarré et accessible sur le port 3012
- [ ] Le serveur Laravel est démarré (`php artisan serve`)
- [ ] Le serveur a été **redémarré** après le `git pull`
- [ ] Le cache Laravel a été vidé (`php artisan cache:clear`)
- [ ] Le cache du navigateur a été vidé (`Ctrl+Shift+R`)
- [ ] Il y a au moins 1 événement dans la base de données
- [ ] L'API `/api/events` retourne des données (pas `[]`)
- [ ] La console JavaScript ne montre pas d'erreurs
- [ ] Les fichiers `.blade.php` contiennent bien `event.date_start`

## 📸 Si ça ne fonctionne toujours pas

Envoyez-moi:

1. **Capture d'écran de la console JavaScript** (onglet Console dans DevTools)
2. **Capture d'écran de l'onglet Network** montrant la requête `/api/events`
3. **Résultat de cette commande:**
   ```bash
   php artisan tinker --execute="echo 'Events: ' . \App\Models\ChronoFront\Event::count();"
   ```

## 🚀 Démarrage rapide pour tester

```bash
# 1. Démarrer MySQL (si pas déjà fait)
# Voir la documentation de votre installation MySQL

# 2. Exécuter le script de démarrage
./start-chronofront.sh

# 3. Ouvrir le navigateur en mode navigation privée (pas de cache)
# Chrome: Ctrl+Shift+N
# Firefox: Ctrl+Shift+P

# 4. Aller sur http://localhost:8000/chronofront

# 5. Créer un événement via la page "Événements"

# 6. Aller sur la page "Courses" et vérifier que l'événement apparaît dans le dropdown
```
