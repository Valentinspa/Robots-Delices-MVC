<?php
// Inclut le fichier de connexion à la base de données
require_once __DIR__ . '/../service/connexionBDD.php';

/**
 * Récupère les recettes populaires (ex: pour la page d'accueil)
 *
 * @return array Tableau des recettes populaires
 */
function getPopularRecipes(): array
{
    $pdo = connexionBDD(); 
    // WHERE popular = 1 : sélectionne seulement les recettes marquées comme populaires
    // ORDER BY created_at DESC : trie par date de création (plus récentes en premier)
    // LIMIT 3 : limite à 3 recettes maximum
    $stmt = $pdo->prepare("SELECT recipes.* FROM recipes WHERE popular = 1 ORDER BY created_at DESC LIMIT 3");
    $stmt->execute(); // Exécute la requête
    $recipes = $stmt->fetchAll(); // Récupère toutes les recettes trouvées dans un tableau
    return $recipes;
}
/**
 * Récupère toutes les recettes avec les informations de catégorie
 *
 * @return array Tableau de toutes les recettes avec leurs catégories
 */
function getRecipes(): array
{
    $pdo = connexionBDD();
    // LEFT JOIN pour récupérer les informations de catégorie
    // ORDER BY created_at DESC pour afficher les plus récentes en premier
    $stmt = $pdo->prepare("
    SELECT recipes.*, category.category_name, category.category_logo 
    FROM recipes 
    LEFT JOIN category ON recipes.category_id = category.id 
    ORDER BY created_at DESC
    ");
    $stmt->execute();
    $recipes = $stmt->fetchAll();
    return $recipes;
}

/**
 * Récupère toutes les catégories de recettes
 *
 * @return array Tableau de toutes les catégories
 */
function getCategories (): array
{
    $pdo = connexionBDD();
    $stmt = $pdo->prepare("SELECT * FROM category ORDER BY category_name ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll();
    return $categories;
}

/**
 * Compte combien de recettes ont un slug similaire pour garantir l'unicité
 *
 * @param string $slug Le slug à vérifier
 * @return int Le nombre de recettes avec un slug similaire
 */
function countRecipesBySlug(string $slug): int
{
    $pdo = connexionBDD();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM recipes WHERE slug LIKE ?");
    $stmt->execute([$slug . '%']);
    return (int)$stmt->fetchColumn();
}

/**
 * Ajoute une nouvelle recette à la base de données
 *
 * @param string $slug
 * @param int $user_id
 * @param string $titre
 * @param string $description
 * @param string $ingredients
 * @param string $instructions
 * @param string $temps_preparation
 * @param string $portions
 * @param string $difficulte
 * @param string $categorie
 * @param string $photo_path
 * @return bool Vrai si l'insertion a réussi, faux sinon
 */
function addRecipe(string $slug, int $user_id, string $titre, string $description, string $ingredients, string $instructions, string $temps_preparation, string $portions, string $difficulte, string $categorie, string $photo_path): bool
{
    $pdo = connexionBDD();
    $stmt = $pdo->prepare("INSERT INTO recipes (slug, user_id, title, description, ingredients, instructions, cooking_time, number_persons, difficulty, category_id, photo, image_caption) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$slug, $user_id, $titre, $description, $ingredients, $instructions, $temps_preparation, $portions, $difficulte, $categorie, $photo_path, 'Photo de la recette']);
    return $stmt->rowCount() > 0;
}

/**
 * Récupère les détails d'une recette par son slug, incluant les infos de catégorie et si elle est en favoris
 *
 * @param int $userId ID de l'utilisateur (0 si non connecté)
 * @param string $slug Le slug unique de la recette
 * @return array|false Tableau des détails de la recette ou false si non trouvée
 */
function getRecipe(int $userId, string $slug): array|false
{
    $pdo = connexionBDD();
    // LEFT JOIN pour récupérer les informations de catégorie
    // ORDER BY created_at DESC pour afficher les plus récentes en premier
   $stmt = $pdo->prepare("SELECT recipes.*, category.category_name, category.category_logo, (recipes.id IN (SELECT recipe_id FROM favorites WHERE user_id = ?) ) as isFavorite FROM recipes LEFT JOIN category ON recipes.category_id = category.id WHERE slug = ?");
$stmt->execute([$userId, $slug]); // Premier ? = userId, deuxième ? = slug
$recipe = $stmt->fetch(); // Récupère la recette ou false si pas trouvée

    return $recipe;   
}

/**
 * Recherche des recettes par titre, description ou ingrédients
 *
 * @param string $searchTerm Le terme de recherche
 * @return array Tableau des recettes correspondant à la recherche
 */
function searchRecipes(string $searchTerm): array
{
    $pdo = connexionBDD();
    $searchPattern = '%' . $searchTerm . '%';
    $stmt = $pdo->prepare("SELECT recipes.*, category.category_name, category.category_logo 
                           FROM recipes 
                           LEFT JOIN category ON recipes.category_id = category.id 
                           WHERE recipes.title LIKE ? 
                           OR recipes.description LIKE ? 
                           OR recipes.ingredients LIKE ? 
                           ORDER BY recipes.created_at DESC");
    $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
    return $stmt->fetchAll();

}

