<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/assets/css/style.css">
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
                <div id="search-bar">
                    <form method="GET" action="/recherche">
                        <input type="text" name="search" placeholder="Rechercher une recette, un ingrédient..." required />
                        <button type="submit">Rechercher</button>
                    </form>
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