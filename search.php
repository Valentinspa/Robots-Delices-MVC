<?php
// Page de recherche pour Robots-Délices
// Permet de rechercher des recettes par titre, description ou ingrédients

session_start();
require_once __DIR__ . '/service/connexionBDD.php';

// Récupération du terme de recherche
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$recipes = [];
$searchPerformed = false;

// Vérification des favoris si utilisateur connecté
$favorites = [];
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT recipe_id FROM favorites WHERE user_id = ?");
    $stmt->execute([$userId]);
    $favorites = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
}

// Si une recherche a été effectuée
if (!empty($searchTerm)) {
    $searchPerformed = true;
    
    // Requête de recherche dans titre, description et ingrédients
    $searchQuery = "SELECT recipes.*, category.category_name, category.category_logo 
                   FROM recipes 
                   LEFT JOIN category ON recipes.category_id = category.id 
                   WHERE recipes.title LIKE ? 
                   OR recipes.description LIKE ? 
                   OR recipes.ingredients LIKE ? 
                   ORDER BY recipes.created_at DESC";
    
    $searchPattern = '%' . $searchTerm . '%';
    $stmt = $pdo->prepare($searchQuery);
    $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
    $recipes = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/search.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <script src="./assets/js/api-favoris.js" defer></script>
    <title><?php echo $searchPerformed ? 'Recherche : ' . htmlspecialchars($searchTerm) : 'Recherche'; ?> - Robots-Délices</title>
</head>

<body>
    <?php require_once __DIR__ . '/view/module/header.php'; ?>
    
    <main>
        <div id="section-container">
            <section id="search-section">
                <div class="search-header">
                    <h1>Recherche de recettes</h1>
                    
                    <!-- Formulaire de recherche -->
                    <form method="GET" action="/recherche" class="search-form">
                        <div class="search-input-container">
                            <input type="text" 
                                   name="search" 
                                   value="<?php echo htmlspecialchars($searchTerm); ?>" 
                                   placeholder="Rechercher une recette, un ingrédient..." 
                                   required />
                            <button type="submit">Rechercher</button>
                        </div>
                    </form>
                </div>

                <!-- Section des résultats avec fond blanc -->
                <div class="search-results-section">
                    <!-- Résultats de recherche -->
                    <?php if ($searchPerformed): ?>
                        <div class="search-results">
                            <h2>
                                <?php if (count($recipes) > 0): ?>
                                    <?php echo count($recipes); ?> résultat(s) pour "<?php echo htmlspecialchars($searchTerm); ?>"
                                <?php else: ?>
                                    Aucun résultat pour "<?php echo htmlspecialchars($searchTerm); ?>"
                                <?php endif; ?>
                            </h2>
                            
                            <?php if (count($recipes) > 0): ?>
                                <div id="recettes-grid">
                                    <?php foreach ($recipes as $recipe): ?>
                                        <div class="recette-card">
                                            <div class="recette-image">
                                                <a href="/recette/<?php echo $recipe['slug']; ?>">
                                                    <img src="/<?php echo $recipe['photo']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" />
                                                </a>
                                            </div>
                                            <div class="recettes-content">
                                                <div class="recette-summarize">
                                                    <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                                    <p><?php echo htmlspecialchars($recipe['description']); ?></p>
                                                    
                                                    <!-- Bouton favoris -->
                                                    <?php if (isset($_SESSION['user_id'])): ?>
                                                        <span class="bouton-favoris" data-id="<?php echo $recipe['id']; ?>">
                                                            <?php echo in_array($recipe['id'], $favorites) ? '❤️' : '🤍'; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="recette-meta">
                                                    <span>⏱️ <?php echo $recipe['cooking_time']; ?></span>
                                                    <span>👥 <?php echo $recipe['number_persons']; ?> pers</span>
                                                    <?php if ($recipe['category_name']): ?>
                                                        <span><?php echo $recipe['category_logo']; ?> <?php echo $recipe['category_name']; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="no-results">
                                    <p>Désolé, aucune recette ne correspond à votre recherche.</p>
                                    <p>Essayez avec d'autres mots-clés ou parcourez nos catégories :</p>
                                    <div class="search-suggestions">
                                        <a href="/?category=entrees">🥗 Entrées</a>
                                        <a href="/?category=plats">🍖 Plats</a>
                                        <a href="/?category=desserts">🍰 Desserts</a>
                                        <a href="/?category=boissons">🥤 Boissons</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="search-placeholder">
                            <p>Utilisez la barre de recherche ci-dessus pour trouver des recettes par nom, description ou ingrédients.</p>
                            
                            <div class="search-tips">
                                <h3>Conseils de recherche :</h3>
                                <ul>
                                    <li>Recherchez par nom de recette : "carbonara", "tarte aux pommes"</li>
                                    <li>Recherchez par ingrédient : "pommes", "poulet", "chocolat"</li>
                                    <li>Utilisez des mots simples pour de meilleurs résultats</li>
                                </ul>
                            </div>
                        </div>
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