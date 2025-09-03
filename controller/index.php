<?php
// Page d'accueil du site Robots-Délices
// Affiche les recettes populaires et gère les favoris des utilisateurs connectés

require_once __DIR__ . '/../model/recettes-model.php';
require_once __DIR__ . '/../model/favoris-model.php';

// Vérification si un utilisateur est connecté
// $_SESSION['user_id'] contient l'ID de l'utilisateur connecté (défini lors du login)
if (isset($_SESSION['user_id'])) {
    // Si connecté, on récupère l'ID de l'utilisateur
    $userId = $_SESSION['user_id'];

    $favorites = getFavorites($userId);
} else {
    // Si pas connecté, on initialise un tableau vide pour les favoris
    $favorites = [];
};

// Récupération des recettes populaires à afficher sur la page d'accueil
$recipes = getPopularRecipes();

require_once __DIR__ . '/../view/accueil.php';