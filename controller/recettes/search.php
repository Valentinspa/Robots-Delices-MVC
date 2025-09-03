<?php
// Page de recherche pour Robots-Délices
// Permet de rechercher des recettes par titre, description ou ingrédients


// require model favoris et recettes
require_once __DIR__ . '/../../model/favoris-model.php';
require_once __DIR__ . '/../../model/recettes-model.php';

// Récupération du terme de recherche
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$recipes = [];
$searchPerformed = false;

// Vérification des favoris si utilisateur connecté
$favorites = [];
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $favorites = getFavorites($userId);
}

// Si une recherche a été effectuée
if (!empty($searchTerm)) {
    $searchPerformed = true;
    
    // Requête de recherche dans titre, description et ingrédients
    $recipes = searchRecipes($searchTerm);
}

// Inclusion de la vue
require_once __DIR__ . '/../../view/recettes/search.php';
?>

