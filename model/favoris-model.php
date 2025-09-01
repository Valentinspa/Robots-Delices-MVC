<?php
// Inclut le fichier de connexion à la base de données
require_once __DIR__ . '/../service/connexionBDD.php';

/**
 * Récupère les IDs des recettes favorites pour un utilisateur donné
 *
 * @param int $userId
 * @return array Tableau des IDs des recettes favorites
 */
function getFavorites(int $userId): array
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
        $stmt = $pdo->prepare("SELECT recipe_id FROM favorites WHERE user_id = ?");
    $stmt->execute([$userId]); // Exécute la requête avec l'ID utilisateur

    // Récupère tous les IDs des recettes favorites sous forme de tableau simple
    // PDO::FETCH_COLUMN, 0 récupère seulement la première colonne (recipe_id)
    $favorites = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    return $favorites;
}

/**
 * Vérifie si une recette spécifique est dans les favoris d'un utilisateur
 *
 * @param int $userId
 * @param int $recetteId
 * @return array|false Tableau des IDs des recettes favorites (vide si pas en favoris)
 */
function getFavori($userId, $recetteId): array|false
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
        $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ? AND recipe_id = ?");
    $stmt->execute([$userId, $recetteId]); // Exécute la requête avec l'ID utilisateur

    // Récupère tous les IDs des recettes favorites sous forme de tableau simple
    $favoris = $stmt->fetch();
    return $favoris;
}

/**
 * Supprime une recette des favoris d'un utilisateur
 *
 * @param int $userId
 * @param int $recetteId
 * @return void
 */
function deleteFavori($userId, $recetteId): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?");
    $stmt->execute([$userId, $recetteId]); // Exécute la requête avec l'ID utilisateur
}

/**
 * Ajoute une recette aux favoris d'un utilisateur
 *
 * @param int $userId
 * @param int $recetteId
 * @return void
 */
function addFavori($userId, $recetteId): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)");
    $stmt->execute([$userId, $recetteId]); // Exécute la requête avec l'ID utilisateur
}