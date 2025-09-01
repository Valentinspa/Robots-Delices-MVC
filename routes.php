<?php
require_once __DIR__ . '/router.php';

// Page d'accueil
get('/', '/controller/index.php');

// Recherche de recettes
get('/recherche', '/controller/recettes/search.php');

// AUTHENTIFICATION
get('/connexion', '/controller/connexion-inscription/login.php');
post('/connexion', '/controller/connexion-inscription/login.php');

get('/inscription', '/controller/connexion-inscription/register.php');
post('/inscription', '/controller/connexion-inscription/register.php');

get('/deconnexion', '/controller/profil/logout.php');

// Mot de passe oublié
get('/mot-de-passe-oublie', '/controller/mot-de-passe-oublie/mdp-oublié.php');

// Profil
get('/profil', '/controller/profil/profil.php');
get('/mes-favoris', '/controller/profil/mes-favoris.php');

// RECETTES

// Détail d'une recette par slug
get('/recette/$slug', '/controller/recettes/recette.php');

// Liste des recettes
get('/recettes', '/controller/recettes/liste-recettes.php');


// Ajouter une recette
get('/ajouter-recette', '/controller/recettes/ajout-recette.php');
post('/ajouter-recette', '/controller/recettes/ajout-recette.php');

// API
post('/api-favoris', '/controller/recettes/api-favoris.php');

// PAGE 404
get('/404', '404.php');

// Route par défaut pour les pages non trouvées (DOIT ÊTRE EN DERNIER)
any('$path', function ($path) {
    http_response_code(404);
    include_once __DIR__ . '/404.php';
    exit();
});
