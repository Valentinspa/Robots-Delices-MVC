<?php
// Page de détail d'une recette - Affiche les informations complètes d'une recette


// Inclusion du modèle de recettes pour interagir avec la base de données
require_once __DIR__ . '/../../model/recettes-model.php'; // Modèle pour interagir avec les recettes


// Vérification de la présence du slug (injecté par le router)
if (!isset($slug) || empty($slug)) {
    // Si pas de slug, rediriger vers l'accueil
    header('Location: /');
    exit();
}

// Vérification de l'état de connexion de l'utilisateur
// Si connecté : récupère son ID pour les favoris
// Si non connecté : utilise 0 comme ID par défaut
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// Récupération des détails de la recette depuis la base de données
// $slug est maintenant injecté par le système de routage, pas depuis $_GET

// Requête complexe qui récupère :
// - Toutes les colonnes de la table recipes
// - Le nom et logo de la catégorie (LEFT JOIN)
// - Si la recette est en favoris pour cet utilisateur (sous-requête)

$recipe = getRecipe($userId, $slug);

// Si la recette n'existe pas, rediriger vers l'accueil
if (!$recipe) {
    header('Location: /');
    exit();
}

// Traitement des ingrédients : conversion de chaîne en tableau
// Les ingrédients sont stockés comme "ingrédient1,ingrédient2,ingrédient3"
$ingredients = explode(',', $recipe['ingredients']); // Sépare par virgules

// Traitement des instructions de préparation
// Conversion des différents types de retours à la ligne en \n standard
$preparation = str_replace(["\r\n", "\r"], "\n", $recipe['instructions']);
$preparation = explode("\n", $preparation); // Sépare chaque ligne
$preparation = array_map('trim', $preparation); // Supprime espaces début/fin de chaque ligne

// Supprime les lignes vides
$preparation = array_filter($preparation, function ($step) {
    return !empty($step); // Garde seulement les lignes non-vides
});
$preparation = array_values($preparation); // Réindexe le tableau (0,1,2...)

require_once __DIR__ . '/../../view/recettes/recette.php'; // Vue pour afficher la recette
?>
