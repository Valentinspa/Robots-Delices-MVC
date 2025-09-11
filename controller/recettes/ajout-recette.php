<?php
// Page d'ajout de recette - Permet aux utilisateurs connectés d'ajouter une nouvelle recette
// Inclut validation des données, upload d'image et protection CSRF

require_once __DIR__ . '/../../model/recettes-model.php'; // Modèle pour interagir avec les recettes
require_once __DIR__. "/../../service/csrf.php"; // Protection contre les attaques CSRF

// VÉRIFICATION DE LA CONNEXION UTILISATEUR
// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
handleAuthRedirect(true);

// Récupération de l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];


// Récupération de toutes les catégories disponibles pour le formulaire
$categories = getCategories();


// Traitement du formulaire d'ajout de recette (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et sécurisation des données du formulaire
    // htmlspecialchars() protège contre les attaques XSS
    // ?? '' définit une valeur par défaut si le champ est vide
    $titre = htmlspecialchars($_POST['titre']) ?? ''; // Titre de la recette
    $categorie = htmlspecialchars($_POST['categorie']) ?? ''; // ID de catégorie
    $difficulte = htmlspecialchars($_POST['difficulte']) ?? 'facile'; // Niveau de difficulté
    $temps_preparation = htmlspecialchars($_POST['temps-preparation']) ?? ''; // Temps en minutes
    $portions = htmlspecialchars($_POST['portions']) ?? 1; // Nombre de portions
    $description = htmlspecialchars($_POST['description']) ?? ''; // Description courte
    $ingredients = htmlspecialchars($_POST['ingredients']) ?? ""; // Liste des ingrédients
    $instructions = htmlspecialchars($_POST['instructions']) ?? ""; // Étapes de préparation

    // Génération du slug (URL-friendly) à partir du titre
    // Convertit en minuscules et remplace caractères spéciaux par _
    $slug = strtolower(trim(preg_replace("/[^A-Za-z0-9à-üÀ-Ü-]+/", '_', $titre)));

    // Vérification de l'unicité du slug et génération d'un suffixe si nécessaire
    // Compte combien de recettes ont un slug similaire

    $count = countRecipesBySlug($slug);

    // Ajoute un numéro pour rendre le slug unique (ex: tarte-pommes_2)
    $slug .= '_' . ($count + 1);

    // Handle file upload
    $photo_path = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "assets/img/";
        $photo_path = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path);
    }
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        // Insert recipe into database
        $success = addRecipe($slug, $user_id, $titre, $description, $ingredients, $instructions, $temps_preparation, $portions, $difficulte, $categorie, $photo_path);
        // Redirect to the recipe page
        header("Location: /recette/" . urlencode($slug));
        exit();
    }
}

require_once __DIR__ . '/../../view/recettes/ajout-recette.php'; // Vue pour afficher le formulaire d'ajout de recette