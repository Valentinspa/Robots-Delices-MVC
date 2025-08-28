<?php
// Page Liste Recettes - Affiche toutes les recettes de la base de données
// Inclut des protections contre le brute force et les attaques CSRF

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/service/connexionBDD.php'; // Connexion à la base de données
require_once __DIR__ . "/service/csrf.php"; // Protection contre les attaques CSRF

// Démarrage de la session pour gérer l'état de connexion et les favoris
session_start();

// Vérification si un utilisateur est connecté pour gérer les favoris
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    // Récupération des favoris de l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT recipe_id FROM favorites WHERE user_id = ?");
    $stmt->execute([$userId]);
    $favorites = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
} else {
    // Si pas connecté, tableau vide pour les favoris
    $favorites = [];
}

// Récupération de TOUTES les recettes avec les informations de catégorie
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

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/liste-recettes.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <script src="/assets/js/api-favoris.js" defer></script>
    <title>Liste des Recettes - Robots-Délices</title>
</head>

<body>
    <?php
    require_once 'view/module/header.php';
    ?>
    
    <main>
        <div id="section-container">
            <!-- Section hero de la page -->
            <section id="accueil">
                <div>
                    <h1>Toutes nos Recettes</h1>
                    <p>Découvrez notre collection complète de recettes délicieuses créées avec passion</p>
                </div>
            </section>

            <!-- Section principale avec la grille de recettes -->
            <section id="recettes">
                <div id="recettes-title">
                    <h2>Nos Recettes</h2>
                    <p><?php echo count($recipes); ?> recette<?php echo count($recipes) > 1 ? 's' : ''; ?> disponible<?php echo count($recipes) > 1 ? 's' : ''; ?></p>
                </div>
                <div id="recettes-grid">
                    <?php if (empty($recipes)): ?>
                        <!-- Message si aucune recette n'est trouvée -->
                        <div class="no-recipes">
                            <h2>Aucune recette trouvée</h2>
                            <p>Il n'y a pas encore de recettes dans notre base de données.</p>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="/ajouter-recette" class="btn-primary">Ajouter la première recette</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recipes as $recipe): ?>
                            <div class="recette-card">
                                <div class="recette-image">
                                    <a href="/recette/<?php echo htmlspecialchars($recipe['slug']); ?>">
                                        <img src="/<?php echo htmlspecialchars($recipe['photo']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" />
                                    </a>
                                </div>
                                
                                <div class="recettes-content">
                                    <div class="recette-summarize">
                                        <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                        <p><?php echo htmlspecialchars($recipe['description']); ?></p>

                                        <!-- Bouton favoris -->
                                        <span class="bouton-favoris" data-id="<?php echo $recipe['id']; ?>">
                                            <?php echo in_array($recipe['id'], $favorites) ? '❤️' : '🤍'; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="recette-meta">
                                        <span>⏱️ <?php echo htmlspecialchars($recipe['cooking_time']); ?></span>
                                        <span>👥 <?php echo htmlspecialchars($recipe['number_persons']); ?> pers</span>
                                        <span>⭐ 4.5</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>© 2025 Robots-Délices. Tous droits réservés.</p>
    </footer>
</body>

</html>