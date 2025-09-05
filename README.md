# 🍳 Robots-Délices

**Robots-Délices** est une plateforme web de partage de recettes de cuisine développée en PHP vanilla avec une architecture MVC complète. Les utilisateurs peuvent découvrir, partager, rechercher et sauvegarder leurs recettes favorites dans une interface moderne et responsive. Ce projet est réalisé dans le cadre d'une formation professionnelle Développeur Web et Web Mobile.

## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Architecture](#-architecture)
- [API et Points d'accès](#-api-et-points-daccès)
- [Base de données](#-base-de-données)
- [Sécurité](#-sécurité)
- [Structure du projet](#-structure-du-projet)
- [Contribution](#-contribution)
- [To-Do List](#-to-do-list)

## ✨ Fonctionnalités

### 🔐 Authentification et Sécurité
- **Inscription/Connexion** avec validation complète et regex robustes
- **Protection CSRF** sur tous les formulaires avec tokens cryptographiques
- **Protection contre brute force** (5 tentatives max par 15 minutes)
- **Validation reCAPTCHA Google** à l'inscription
- **Hashage sécurisé** des mots de passe (bcrypt avec PASSWORD_DEFAULT)
- **Gestion complète des mots de passe oubliés** avec tokens temporaires et emails
- **Gestion des sessions** sécurisée avec nettoyage automatique

### 🍽️ Gestion des Recettes
- **Ajout de recettes** avec formulaire dynamique et upload d'images
- **Affichage détaillé** des recettes avec slugs SEO-friendly et URLs propres
- **Catégorisation complète** (Entrées, Plats, Desserts, Boissons, Végétarien, Rapide)
- **Système de favoris** AJAX en temps réel pour utilisateurs connectés
- **Recettes populaires** configurables en page d'accueil
- **Liste complète** de toutes les recettes avec pagination
- **Recherche avancée** par titre, description et ingrédients
- **Métadonnées riches** : temps de préparation, difficulté, nombre de personnes

### 👤 Gestion des Profils
- **Page profil utilisateur** avec modification des informations personnelles
- **Changement de mot de passe** avec validation sécurisée
- **Suppression de compte** avec confirmation et protection CSRF
- **Navigation contextuelle** avec dropdown menu dans l'en-tête

### 🌐 Interface Utilisateur
- **Design responsive** compatible mobile/tablette/desktop avec breakpoints optimisés
- **Navigation intuitive** avec menu hamburger et animations fluides
- **Pages dédiées** : accueil, détail recette, ajout, liste, recherche, authentification
- **Messages d'erreur/succès** contextuels et stylés
- **Système de partage** avec Web Share API et fallback clipboard
- **Interface d'impression** pour les recettes

### 🔧 Fonctionnalités Techniques
- **Routage personnalisé** avec support des paramètres dynamiques et méthodes HTTP
- **Architecture MVC** complète avec séparation claire des responsabilités
- **API REST** pour les actions AJAX (favoris, recherche)
- **Système de slugs** automatique pour URLs conviviales
- **Gestion d'erreurs** robuste avec pages 404 personnalisées
- **Upload et gestion d'images** avec validation des types de fichiers

## 📋 Prérequis

- **Docker** et **Docker Compose** installés (version récente)
- **Git** pour le clonage du projet
- Port **15050** libre pour l'application web
- Port **8080** libre pour phpMyAdmin
- **Clés reCAPTCHA Google** (site et secret)

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone https://github.com/Valentinspa/Robots-Delices-MVC.git
cd Robots-Delices-MVC
```

### 2. Configurer les variables d'environnement
```bash
# Le fichier .env existe déjà avec les clés reCAPTCHA
# Modifiez-le si nécessaire avec vos propres clés
```

Contenu du fichier `.env` :
```env
RECAPTCHA_SITE_KEY
RECAPTCHA_SECRET_KEY
```

### 3. Lancer l'environnement Docker
```bash
docker-compose up -d
```

### 4. Importer la base de données
La base de données sera automatiquement créée. Pour les données de test :
```bash
# Via l'interface phpMyAdmin : http://localhost:8080
# - Utilisateur : root
# - Mot de passe : root
# - Importer le fichier : Robots-Délices.sql
```

### 5. Accéder à l'application
- **Application** : http://localhost:15050
- **phpMyAdmin** : http://localhost:8080

## ⚙️ Configuration

### Stack Docker
```yaml
services:
  app:        # PHP 8.4 + Apache + mod_rewrite + PDO MySQL
  db:         # MySQL 9.4 avec base 'Robots-Délices'
  phpmyadmin: # Interface d'administration web
```

### Variables d'environnement
```env
# Base de données
DB_HOST=db
DB_USER=user
DB_PASSWORD=password
DB_NAME=Robots-Délices

# reCAPTCHA (Google)
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
```

## 🗃️ Architecture

### Patron MVC Complet
- **Models** : 3 fichiers (favoris-model.php, recettes-model.php, user-model.php)
- **Views** : Templates PHP modulaires avec composants réutilisables
- **Controllers** : 16 contrôleurs pour toutes les fonctionnalités
- **Services** : Utilitaires (CSRF, connexion BDD, mail)

### Router Personnalisé
- Support des méthodes HTTP : GET, POST, PUT, PATCH, DELETE
- Paramètres dynamiques avec capture automatique
- Réécriture d'URL avec .htaccess
- Gestion des erreurs 404

## 🌐 API et Points d'accès

### Routes Authentification
```php
GET  /connexion               # Page de connexion
POST /connexion               # Traitement connexion
GET  /inscription             # Page d'inscription  
POST /inscription             # Traitement inscription
GET  /deconnexion             # Déconnexion sécurisée
GET  /mot-de-passe-oublie     # Demande reset password
POST /mot-de-passe-oublie     # Envoi email reset
GET  /reinitialiser-mdp       # Formulaire nouveau mot de passe
POST /reinitialiser-mdp       # Traitement nouveau mot de passe
```

### Routes Profil Utilisateur
```php
GET  /profil                  # Page de profil utilisateur
POST /profil                  # Modification profil
GET  /mes-favoris             # Page des favoris
GET  /supprimer-compte        # Suppression de compte avec CSRF
```

### Routes Recettes
```php
GET  /                        # Page d'accueil avec recettes populaires
GET  /recettes                # Liste complète des recettes
GET  /recette/{slug}          # Détail d'une recette par slug
GET  /recherche               # Page de recherche
GET  /ajouter-recette         # Formulaire d'ajout
POST /ajouter-recette         # Traitement ajout recette
```

### API AJAX
```php
POST /api-favoris             # API gestion favoris en temps réel
```

### API Favoris (AJAX)
```javascript
// Endpoint : /api-favoris
// Méthode : POST
// Headers : X-Requested-With: XMLHttpRequest
// Paramètres :
{
  "action": "toggle_favorites",
  "recette_id": 123
}

// Réponses possibles :
{
  "status": "added",          // Recette ajoutée aux favoris
  "status": "removed",        // Recette retirée des favoris
  "status": "error",          // Erreur générale
  "status": "not_logged_in"   // Utilisateur non connecté
}
```

## 🗄️ Base de données

### Schéma Relationnel Complet
```sql
users                    # Utilisateurs (12 comptes de test)
├── id (PK, AUTO_INCREMENT)
├── firstname (VARCHAR 50, NOT NULL)
├── lastname (VARCHAR 50, NOT NULL)
├── email (VARCHAR 255, UNIQUE, NOT NULL)
├── password (VARCHAR 255, hashed)
├── reset_token (VARCHAR 255, NULLABLE)
├── token_expiry (DATETIME, NULLABLE)
└── created_at (TIMESTAMP)

recipes                  # Recettes (11 recettes de test)
├── id (PK, AUTO_INCREMENT)
├── slug (VARCHAR 200, UNIQUE, NOT NULL)
├── user_id (FK -> users.id)
├── title (VARCHAR 255, NOT NULL)
├── description (TEXT)
├── ingredients (TEXT)
├── instructions (TEXT)
├── cooking_time (VARCHAR 50)
├── number_persons (VARCHAR 10)
├── difficulty (VARCHAR 100)
├── category_id (FK -> category.id)
├── photo (VARCHAR 255)
├── image_caption (TEXT)
├── popular (TINYINT 1, DEFAULT 0)
└── created_at (TIMESTAMP)

category                 # Catégories (6 catégories)
├── id (PK, AUTO_INCREMENT)
├── category_name (VARCHAR 50, UNIQUE)
├── category_logo (VARCHAR 10) # Emoji
└── created_at (TIMESTAMP)

favorites               # Favoris utilisateurs
├── user_id (FK -> users.id, CASCADE)
├── recipe_id (FK -> recipes.id, CASCADE)
├── created_at (TIMESTAMP)
└── PRIMARY KEY (user_id, recipe_id)

login_attempts          # Protection brute force
├── id (PK, AUTO_INCREMENT)
├── email (VARCHAR 255)
├── attempt_time (DATETIME)

comments               # Commentaires (structure prête)
├── id (PK, AUTO_INCREMENT)
├── recipe_id (FK -> recipes.id)
├── user_id (FK -> users.id)
├── content (TEXT)
└── created_at (TIMESTAMP)
```

### Données de Test Incluses
- **Compte admin** : admin@robots-delices.fr / admin
- **12 utilisateurs de test** avec mots de passe hashés
- **11 recettes complètes** avec images (tarte aux pommes, salade césar, carbonara, etc.)
- **6 catégories** : Entrées 🥗, Plats 🍖, Desserts 🍰, Boissons 🥤, Végétarien 🌱, Rapide ⚡
- **Données de favoris** et tentatives de connexion pour tests

## 🛡️ Sécurité

### Mesures de Sécurité Implémentées
- **Requêtes préparées PDO** avec paramètres bindés contre l'injection SQL
- **Tokens CSRF** cryptographiques (32 bytes) sur tous les formulaires sensibles
- **Protection XSS** avec `htmlspecialchars()` sur toutes les sorties utilisateur
- **Hashage bcrypt** des mots de passe avec `PASSWORD_DEFAULT`
- **Limitation stricte** des tentatives de connexion (5 max par 15 minutes)
- **Validation reCAPTCHA Google** obligatoire à l'inscription
- **Sessions sécurisées** avec nettoyage automatique et `session_regenerate_id()`
- **Validation robuste** : regex pour mots de passe, emails, noms
- **Protection des uploads** avec validation des types MIME

### Configuration Sécurisée
```php
// PDO sécurisé (service/connexionBDD.php)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// Headers CORS restreints (API)
header('Access-Control-Allow-Origin: http://localhost:15050');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');

// Validation des mots de passe
- Minimum 8 caractères
- Au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial
- Vérification anti-brute force avant validation
```

## 🗂️ Structure du Projet

### Organisation des Fichiers
```
Robots-Delices-MVC/
├── assets/                           # Ressources statiques
│   ├── css/                          # 13 feuilles de style modulaires
│   │   ├── accueil.css              # Page d'accueil + responsive
│   │   ├── ajout-recette.css        # Formulaire d'ajout
│   │   ├── auth-pages.css           # Pages d'authentification
│   │   ├── liste-recettes.css       # Liste et recherche
│   │   ├── navbar.css               # Navigation responsive
│   │   ├── profil.css               # Gestion du profil
│   │   ├── recettes.css             # Détail des recettes
│   │   └── ...                      # Base, footer, variables
│   ├── js/                          # Scripts JavaScript
│   │   ├── api-favoris.js           # AJAX favoris
│   │   ├── share.js                 # Partage recettes
│   │   └── confirmer-suppression.js # Confirmation suppression
│   └── img/                         # Images (logo, recettes)
├── controller/                       # Contrôleurs (16 fichiers)
│   ├── connexion-inscription/        # Authentification
│   │   ├── login.php                # Connexion + brute force
│   │   └── register.php             # Inscription + reCAPTCHA
│   ├── mot-de-passe-oublie/         # Reset password
│   │   ├── mdp-oublie.php           # Demande reset
│   │   └── mdp-reinitialiser.php    # Nouveau mot de passe
│   ├── profil/                      # Gestion profil
│   │   ├── profil.php               # Modification profil
│   │   ├── mes-favoris.php          # Page favoris
│   │   ├── logout.php               # Déconnexion
│   │   └── supprimer.php            # Suppression compte
│   ├── recettes/                    # Gestion recettes
│   │   ├── recette.php              # Détail recette
│   │   ├── liste-recettes.php       # Liste complète
│   │   ├── ajout-recette.php        # Ajout recette
│   │   ├── search.php               # Recherche
│   │   └── api-favoris.php          # API AJAX favoris
│   └── index.php                    # Page d'accueil
├── model/                           # Modèles de données
│   ├── favoris-model.php            # CRUD favoris
│   ├── recettes-model.php           # CRUD recettes + recherche
│   └── user-model.php               # CRUD utilisateurs + sécurité
├── service/                         # Services utilitaires
│   ├── connexionBDD.php             # Connexion PDO sécurisée
│   ├── csrf.php                     # Protection CSRF
│   └── mail.php                     # Envoi emails (PHPMailer)
├── view/                            # Vues et templates
│   ├── connexion-inscription/        # Templates auth
│   │   ├── login.php                # Page connexion
│   │   └── register.php             # Page inscription
│   ├── mail/                        # Templates emails
│   │   └── mail-mdp-oublie.php      # Email reset password
│   ├── module/                      # Composants réutilisables
│   │   └── header.php               # Navigation + menu
│   ├── mot-de-passe-oublie/         # Templates reset
│   ├── profil/                      # Templates profil
│   │   └── profil.php               # Page modification profil
│   ├── recettes/                    # Templates recettes
│   │   ├── recette.php              # Détail recette
│   │   ├── liste-recettes.php       # Liste + recherche
│   │   ├── ajout-recette.php        # Formulaire ajout
│   │   └── search.php               # Page recherche
│   ├── accueil.php                  # Page d'accueil
│   └── 404.php                      # Page erreur 404
├── .env                             # Variables d'environnement
├── .gitignore                       # Exclusions Git
├── .htaccess                        # Réécriture URL
├── routes.php                       # Configuration des routes (20+)
├── router.php                       # Système de routage custom
├── compose.yaml                     # Stack Docker
├── Dockerfile                       # Image PHP 8.4 + Apache
├── Robots-Délices.sql              # Structure + données de test
└── README.md                        # Documentation
```

### Composants Techniques
- **Router custom** avec support complet HTTP et paramètres dynamiques
- **Système de slugs** automatique pour SEO
- **CSS modulaire** avec breakpoints responsive optimisés
- **JavaScript vanilla** pour AJAX et interactions
- **Upload d'images** avec validation et stockage sécurisé

## 🤝 Contribution

### Standards de Développement
1. **Code PHP** : Respect des standards PSR avec documentation française
2. **Base de données** : Nommage en anglais avec relations strictes
3. **CSS** : Architecture modulaire avec variables CSS
4. **JavaScript** : Vanilla JS avec gestion d'erreurs
5. **Git** : Commits descriptifs et atomiques

### Processus de Contribution
1. Fork du projet
2. Branche feature (`git checkout -b feature/NouvelleFonctionnalite`)
3. Développement avec tests locaux
4. Commit descriptif (`git commit -m 'Add: Nouvelle fonctionnalité'`)
5. Push (`git push origin feature/NouvelleFonctionnalite`)
6. Pull Request avec description détaillée

## 📝 To-Do List

### 🚀 Priorité Haute
- [ ] **Page Catégories** avec navigation et filtrage par catégorie
- [ ] **Page "Mes recettes"** complète pour les utilisateurs connectés
- [ ] **Page Favoris** avec gestion et suppression des favoris
- [ ] **Système de commentaires** avec modération basique

### 🔧 Améliorations Fonctionnelles
- [ ] **Système de notation** (5 étoiles) pour les recettes
- [ ] **Upload multiple images** par recette avec galerie
- [ ] **Système de tags** personnalisés pour les recettes
- [ ] **Export PDF** des recettes avec mise en page optimisée
- [ ] **Mode impression** amélioré pour les recettes
- [ ] **Partage social** (Facebook, Twitter, Pinterest)

### 🎨 Interface et UX
- [ ] **Dashboard admin** pour modération des recettes et utilisateurs
- [ ] **Mode sombre** complet avec switch utilisateur
- [ ] **Animations CSS** avancées pour une meilleure UX
- [ ] **Progressive Web App** (PWA) avec service worker
- [ ] **Notifications push** pour nouvelles recettes

### 🧪 Technique et Performance
- [ ] **Tests unitaires** PHPUnit pour modèles et services
- [ ] **API REST** complète avec documentation OpenAPI
- [ ] **Cache Redis** pour requêtes fréquentes
- [ ] **Optimisation images** automatique (WebP, compression)
- [ ] **CDN** pour assets statiques
- [ ] **Monitoring** et logs applicatifs

### 📊 Analytics et SEO
- [ ] **Sitemap XML** automatique
- [ ] **Schema.org** pour recettes (JSON-LD)
- [ ] **Meta tags** dynamiques par page
- [ ] **Analytics** des recettes populaires

---

## 📞 Contact et Support

Pour toute question ou suggestion concernant le projet Robots-Délices :

- **Issues GitHub** pour les bugs et demandes de fonctionnalités
- **Pull Requests** pour les contributions
- **Wiki** pour la documentation technique détaillée

---

*Développé avec passion pour les amateurs de cuisine* 👨‍🍳👩‍🍳