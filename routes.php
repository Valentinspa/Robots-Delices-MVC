<?php
require_once __DIR__ . '/router.php';

// Page d'accueil
get('/', 'index.php');

// Recherche de recettes
get('/recherche', 'search.php');

// AUTHENTIFICATION
get('/connexion', 'controller/connexion-deconnexion/login.php');
post('/connexion', 'controller/connexion-deconnexion/login.php');

get('/inscription', 'controller/connexion-deconnexion/register.php');
post('/inscription', 'controller/connexion-deconnexion/register.php');

get('/deconnexion', 'controller/profil/logout.php');

// Mot de passe oublié
get('/mot-de-passe-oublie', 'controller/mot-de-passe-oublie/mdp-oublié.php');

// Profil
get('/profil', 'controller/profil/profil.php');
get('/mes-favoris', 'controller/profil/mes-favoris.php');

// RECETTES

// Détail d'une recette par slug
get('/recette/$slug', 'recette.php');

// Liste des recettes
get('/recettes', 'liste-recettes.php');


// Ajouter une recette
get('/ajouter-recette', 'ajout-recette.php');
post('/ajouter-recette', 'ajout-recette.php');

// API
post('/api-favoris', 'api-favoris.php');

// PAGE 404
get('/404', '404.php');

// Route par défaut pour les pages non trouvées (DOIT ÊTRE EN DERNIER)
any('$path', function ($path) {
    http_response_code(404);
    include_once __DIR__ . '/404.php';
    exit();
});
