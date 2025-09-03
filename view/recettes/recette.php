<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/recettes.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <script src="/assets/js/api-favoris.js" defer></script>
    <script src="/assets/js/share.js" defer></script>
    <title><?php echo htmlspecialchars($recipe['title']); ?> - Robots-Délices</title>
</head>

<body>
    <?php
    require_once 'view/module/header.php';
    ?>
    <main>
        <div class="container">

            <!-- Recipe Hero -->
            <section class="recipe-hero">
                <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
                <p><?php echo htmlspecialchars($recipe['description']); ?></p>
            </section>

            <!-- Recipe Image -->
            <section class="recipe-image-section">
                <div class="recipe-image">
                    <img src="/<?php echo htmlspecialchars($recipe['photo']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                </div>
                <p class="image-caption"><?php echo htmlspecialchars($recipe['image_caption']); ?></p>
            </section>

            <!-- Recipe Meta -->
            <section class="recipe-meta-grid">
                <div class="meta-item">
                    <span class="meta-icon">⏱️</span>
                    <span class="meta-value"><?php echo htmlspecialchars($recipe['cooking_time']); ?></span>
                    <span class="meta-label">Temps total</span>
                </div>
                <div class="meta-item">
                    <span class="meta-icon">👥</span>
                    <span class="meta-value"><?php echo htmlspecialchars($recipe['number_persons']); ?></span>
                    <span class="meta-label">Portions</span>
                </div>
                <div class="meta-item">
                    <span class="meta-icon">📈</span>
                    <span class="meta-value"><?php echo htmlspecialchars($recipe['difficulty']); ?></span>
                    <span class="meta-label">Difficulté</span>
                </div>
                <div class="meta-item">
                    <span class="meta-icon"><?php echo $recipe['category_logo']; ?></span>
                    <span class="meta-value"><?php echo htmlspecialchars($recipe['category_name']); ?></span>
                    <span class="meta-label">Catégorie</span>
                </div>
            </section>

            <!-- Recipe Content -->
            <div class="recipe-content-grid">
                <!-- Ingredients Sidebar -->
                <aside class="ingredients-sidebar">
                    <h2 class="ingredients-title">Ingrédients</h2>
                    <ul class="ingredients-list">
                        <?php foreach ($ingredients as $ingredient): ?>
                            <li class="ingredient-item">
                                <div class="ingredient-checkbox"></div>
                                <span><?php echo htmlspecialchars(trim($ingredient)); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>

                <!-- Instructions Content -->
                <section class="instructions-content">
                    <h2 class="instructions-title">Préparation</h2>
                    <?php foreach ($preparation as $index => $step): ?>
                        <div class="instruction-step">
                            <div class="step-number"><?php echo $index + 1; ?></div>
                            <div class="step-content">
                                <?php echo htmlspecialchars($step); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>
            </div>

            <!-- Recipe Footer -->
            <section class="recipe-footer">
                <div class="rating-section">
                    <div class="rating-stars">★★★★★</div>
                    <p class="rating-text">4.8/5 basé sur 127 avis</p>
                </div>

                <div class="recipe-actions">
                    <a href="#" class="action-btn" onclick="event.preventDefault();window.print()">🖨️ Imprimer</a>
                    <a href="#" class="action-btn share-btn">📤 Partager</a>
                    <a href="#" class="action-btn bouton-favoris" data-id="<?php echo $recipe['id']; ?>">
                        <?php echo $recipe["isFavorite"] ? "❤️" : "🤍"; ?>
                        <span> Favoris</span>
                    </a>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>© 2025 Robots-Délices. Tous droits réservés.</p>
    </footer>
</body>

</html>