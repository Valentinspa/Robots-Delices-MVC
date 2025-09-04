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
get('/mot-de-passe-oublie', '/controller/mot-de-passe-oublie/mdp-oublie.php');
post('/mot-de-passe-oublie', '/controller/mot-de-passe-oublie/mdp-oublie.php');
get('/reinitialiser-mdp', '/controller/mot-de-passe-oublie/mdp-reinitialiser.php');
post('/reinitialiser-mdp', '/controller/mot-de-passe-oublie/mdp-reinitialiser.php');

// Profil
get('/profil', '/controller/profil/profil.php');
post('/profil', '/controller/profil/profil.php');
get('/mes-favoris', '/controller/profil/mes-favoris.php');
get('/supprimer-compte', '/controller/profil/supprimer.php');

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
any('/404', '/view/404.php');
