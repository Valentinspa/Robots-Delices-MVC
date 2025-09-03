# 🍳 Robots-Délices

**Robots-Délices** est une plateforme web de partage de recettes de cuisine développée en PHP vanilla avec une architecture MVC simplifiée. Les utilisateurs peuvent découvrir, partager et sauvegarder leurs recettes favorites dans une interface moderne et responsive. Je réalise ce projet dans le cadre de ma formation professionnelle Développeur Web et Web Mobile.


## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Architecture](#-architecture)
- [API et Points d'accès](#-api-et-points-daccès)
- [Base de données](#-base-de-données)
- [Sécurité](#-sécurité)
- [Contribution](#-contribution)
- [To-Do List](#-to-do-list)

## ✨ Fonctionnalités

### 🔐 Authentification et Sécurité
- **Inscription/Connexion** avec validation complète
- **Protection CSRF** sur tous les formulaires
- **Protection contre brute force** (limite de tentatives)
- **Validation reCAPTCHA** à l'inscription
- **Hashage sécurisé** des mots de passe (bcrypt)
- **Gestion des sessions** sécurisée

### 🍽️ Gestion des Recettes
- **Ajout de recettes** avec upload d'images
- **Affichage détaillé** des recettes avec slug SEO-friendly
- **Catégorisation** des recettes (Entrées, Plats, Desserts, etc.)
- **Système de favoris** pour les utilisateurs connectés
- **Recettes populaires** en page d'accueil

### 🌐 Interface Utilisateur
- **Design responsive** compatible mobile/desktop
- **Navigation intuitive** avec menu hamburger
- **Pages dédiées** : accueil, détail recette, ajout, authentification
- **Messages d'erreur/succès** contextuels

### 🔧 Fonctionnalités Techniques
- **Routage personnalisé** avec support des paramètres dynamiques
- **Architecture MVC** simplifiée
- **API REST** pour les actions AJAX (favoris)
- **Système de slugs** pour URLs conviviales
- **Gestion d'erreurs** robuste

## 📋 Prérequis

- **Docker** et **Docker Compose** installés
- **Git** pour le clonage du projet
- Port **15050** libre pour l'application
- Port **8080** libre pour phpMyAdmin

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone https://github.com/Valentinspa/Robots-Delices-MVC.git
cd Robots-Delices
```

### 2. Configurer les variables d'environnement
```bash
# Créer le fichier .env à la racine du projet
cp .env.example .env
```

Éditer le fichier `.env` avec vos clés reCAPTCHA :
```env
RECAPTCHA_SITE_KEY=votre_cle_site_recaptcha
RECAPTCHA_SECRET_KEY=votre_cle_secrete_recaptcha
```

### 3. Lancer l'environnement Docker
```bash
docker-compose up -d
```

### 4. Importer la base de données
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

### Structure Docker
```yaml
services:
  app:        # Serveur PHP 8.4 + Apache
  db:         # Base de données MySQL 9.3
  phpmyadmin: # Interface d'administration
```

### Variables d'environnement requises
```env
DB_HOST=db
DB_USER=user  
DB_PASSWORD=password
DB_NAME=database
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
```

## 🏗️ Architecture

### Structure des fichiers
```
Robots-Delices/
├── assets/                    # Ressources statiques
│   ├── css/                   # Feuilles de style
│   ├── js/                    # Scripts JavaScript
│   └── img/                   # Images
├── controller/                # Contrôleurs MVC
│   ├── connexion-deconnexion/ # Authentification
│   └── mot-de-passe-oublie/   # Reset password
├── model/                     # Requêtes SQL
│   ├── favoris-model.php      # Requêtes API favoris
│   ├── login-model.php        # Requêtes login
│   └── recettes-model.php     # Requêtes Recettes
├── service/                   # Services utilitaires
│   ├── connexionBDD.php       # Connexion PDO
│   └── csrf.php               # Protection CSRF
├── view/                      # Vues et composants
│   ├── connexion-deconnexion/ # Authentification 
│   ├── module/                # Composants réutilisables
│   └── recettes/              # Affichage des recettes
├── routes.php                 # Configuration des routes
├── router.php                 # Système de routage
└── index.php                  # Page d'accueil

```

### Patron MVC Simplifié
- **Models** : Logique métier dans les fichiers de service
- **Views** : Templates PHP avec HTML/CSS
- **Controllers** : Fichiers de contrôle dans `/controller/`

## 🌐 API et Points d'accès

### Routes principales
```php
GET  /                        # Page d'accueil
GET  /connexion               # Page de connexion
POST /connexion               # Traitement connexion
GET  /inscription             # Page d'inscription  
POST /inscription             # Traitement inscription
GET  /deconnexion             # Déconnexion
GET  /recette/{slug}          # Détail d'une recette
GET  /ajouter-recette         # Formulaire d'ajout
POST /ajouter-recette         # Traitement ajout recette
POST /api-favoris             # API gestion favoris
```

### API Favoris (AJAX)
```javascript
// Endpoint : /api-favoris
// Méthode : POST
// Paramètres :
{
  "action": "toggle_favorites",
  "recette_id": 123
}

// Réponses :
{
  "status": "added|removed|error|not_logged_in",
  "error": "message d'erreur si applicable"
}
```

## 🗄️ Base de données

### Schéma principal
```sql
users           # Utilisateurs du site
├── id (PK)
├── firstname
├── lastname  
├── email (UNIQUE)
├── password (hashed)
└── created_at

recipes         # Recettes
├── id (PK)
├── slug (UNIQUE)
├── user_id (FK)
├── title
├── description
├── ingredients
├── instructions
├── cooking_time
├── number_persons
├── difficulty
├── category_id (FK)
├── photo
├── popular
└── created_at

category        # Catégories de recettes
├── id (PK)
├── category_name
├── category_logo (emoji)
└── created_at

favorites       # Favoris utilisateurs
├── user_id (FK)
├── recipe_id (FK)
└── created_at

comments        # Commentaires (prévu)
login_attempts  # Protection brute force
```

### Données de test
Le fichier `Robots-Délices.sql` contient :
- **Compte admin** : admin@robots-delices.fr / admin
- **3 recettes populaires** avec images
- **6 catégories** prédéfinies

## 🔐 Sécurité

### Mesures implémentées
- **Requêtes préparées PDO** contre l'injection SQL
- **Tokens CSRF** sur tous les formulaires
- **Protection XSS** avec `htmlspecialchars()`
- **Hashage bcrypt** des mots de passe
- **Limitation tentatives** de connexion (5/15min)
- **Validation reCAPTCHA** à l'inscription
- **Sessions sécurisées** avec nettoyage
- **Validation complète** des données utilisateur

### Configuration sécurisée
```php
// PDO sécurisé
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// Headers CORS restreints
header('Access-Control-Allow-Origin: http://localhost:15050');
```

## 🤝 Contribution

### Standards de développement
1. **Code PHP** : PSR-12 recommandé
2. **Base de données** : Nommage en anglais
3. **Commentaires** : Documentation complète en français
4. **Git** : Commits descriptifs

### Processus de contribution
1. Fork du projet
2. Branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit (`git commit -m 'Add: Amazing Feature'`)
4. Push (`git push origin feature/AmazingFeature`)
5. Pull Request

## 📝 To-Do List

### 🚀 Priorité Haute
- [ ] **Page Profil utilisateur** avec modification des données
- [ ] **Système de recherche** de recettes avec filtres
- [ ] **Page Catégories** avec navigation
- [ ] **Page "Mes recettes"** pour les utilisateurs connectés
- [ ] **Page Favoris** avec gestion complète

### 🔧 Améliorations
- [ ] **Système de notation** des recettes
- [ ] **Commentaires** sur les recettes
- [ ] **Reset password** fonctionnel
- [ ] **Upload multiple images** par recette
- [ ] **Système de tags** pour les recettes
- [ ] **Export PDF** des recettes

### 🎨 Interface
- [ ] **Dashboard admin** pour modération
- [ ] **Mode sombre** pour l'interface
- [ ] **Animations CSS** pour une meilleure UX

### 🧪 Technique
- [ ] **Tests unitaires** PHP
- [ ] **Migration vers MVC** complet
- [ ] **API REST** complète
- [ ] **Cache** des requêtes fréquentes
- [ ] **Optimisation images** automatique

---

## 📞 Contact et Support

Pour toute question ou suggestion concernant le projet Robots-Délices :

- **Issues GitHub** pour les bugs et demandes de fonctionnalités
- **Pull Requests** pour les contributions

---

*Développé avec ❤️ pour les passionnés de cuisine* 👨‍🍳👩‍🍳