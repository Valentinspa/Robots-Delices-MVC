<?php
// Page Liste Recettes - Affiche toutes les recettes de la base de données
// Inclut des protections contre le brute force et les attaques CSRF

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../../model/recettes-model.php'; // Modèle pour interagir avec les recettes
require_once __DIR__ . '/../../model/favoris-model.php'; // Modèle pour interagir avec les favoris
require_once __DIR__. "/../../service/csrf.php"; // Protection contre les attaques CSRF


// Vérification si un utilisateur est connecté pour gérer les favoris
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    $favorites = getFavorites($userId); // Récupère les IDs des recettes favorites de l'utilisateur
} else {
    // Si pas connecté, tableau vide pour les favoris
    $favorites = [];
}

// Récupération de TOUTES les recettes avec les informations de catégorie

$recipes = getRecipes();

require_once __DIR__ . '/../../view/recettes/liste-recettes.php'; // Vue pour afficher la liste des recettes