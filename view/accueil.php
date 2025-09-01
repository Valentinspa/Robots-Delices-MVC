<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <script src="./assets/js/api-favoris.js" defer></script>
    <title>Robots-Délices</title>
</head>

<body>
    <?php
    require_once 'view/module/header.php';
    ?>
    <main>
        <div id="section-container">
            <section id="accueil">
                <div>
                    <h1>Bienvenue sur Robots-Délices</h1>
                    <p>Découvrez et partagez des recettes délicieuses avec notre communauté passionnée</p>
                </div>
                <div id="search-bar">
                    <form method="GET" action="/recherche">
                        <input type="text" name="search" placeholder="Rechercher une recette, un ingrédient..." required />
                        <button type="submit">Rechercher</button>
                    </form>
                </div>
            </section>
            <section id="categories">
                <div id="categories-title">
                    <h2>Explorer par Catégorie</h2>
                    <p>Trouvez l'inspiration pour votre prochain repas</p>
                </div>
                <div id="categories-grid">
                    <div class="categories-card">
                        <div class="categories-icons">🥗</div>
                        <h3>Entrées</h3>
                        <p>Salades et apéritifs</p>
                    </div>
                    <div class="categories-card">
                        <div class="categories-icons">🍖</div>
                        <h3>Plats</h3>
                        <p>Viandes et poissons</p>
                    </div>
                    <div class="categories-card">
                        <div class="categories-icons">🍰</div>
                        <h3>Desserts</h3>
                        <p>Douceurs sucrées</p>
                    </div>
                    <div class="categories-card">
                        <div class="categories-icons">🥤</div>
                        <h3>Boissons</h3>
                        <p>Cocktails et smoothies</p>
                    </div>
                    <div class="categories-card">
                        <div class="categories-icons">🌱</div>
                        <h3>Végétarien</h3>
                        <p>Sans viande</p>
                    </div>
                    <div class="categories-card">
                        <div class="categories-icons">⚡</div>
                        <h3>Rapide</h3>
                        <p> Moins de 30 minutes</p>
                    </div>
                </div>
            </section>
            <section id="recettes">
                <div id="recettes-title">
                    <h2>Recettes Populaires</h2>
                    <p>Les favoris de notre communauté</p>
                </div>
                <div id="recettes-grid">
                    <?php
                    // Boucle à travers chaque recette récupérée de la base de données
                    // $recipe contient toutes les informations d'une recette (titre, description, photo, etc.)
                    foreach ($recipes as $recipe): ?>
                        <div class="recette-card">
                            <div class="recette-image">
                                <!-- Lien vers la page détail de la recette avec son slug (nom simplifié pour URL) -->
                                <a href="/recette/<?php echo $recipe['slug']; ?>">
                                    <img src="/<?php echo $recipe['photo']; ?>" alt="<?php echo $recipe['title']; ?>" />
                                </a>
                            </div>
                            <div class="recettes-content">
                                <div class="recette-summarize">
                                    <!-- Affiche le titre de la recette -->
                                    <h3><?php echo $recipe['title']; ?></h3>
                                    <!-- Affiche la description courte de la recette -->
                                    <p><?php echo $recipe['description']; ?></p>

                                    <!-- Bouton favoris : affiche un cœur rouge si la recette est en favoris, blanc sinon -->
                                    <!-- data-id contient l'ID de la recette pour le JavaScript -->
                                    <span class="bouton-favoris" data-id="<?php echo $recipe['id']; ?>">
                                        <?php
                                        // Vérifie si l'ID de cette recette est dans le tableau des favoris
                                        // Si oui : cœur rouge ❤️, sinon : cœur blanc 🤍
                                        echo in_array($recipe['id'], $favorites) ? '❤️' : '🤍';
                                        ?>
                                    </span>
                                </div>
                                <div class="recette-meta">
                                    <!-- Affiche les métadonnées de la recette -->
                                    <span>⏱️ <?php echo $recipe['cooking_time']; ?></span> <!-- Temps de cuisson -->
                                    <span>👥 <?php echo $recipe['number_persons']; ?> pers</span> <!-- Nombre de personnes -->
                                    <span>⭐ 4.5</span> <!-- Note fixe (à améliorer avec vraie note) -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; // Fin de la boucle 
                    ?>
                </div>
            </section>
        </div>
    </main>
    <footer>
        <p>© 2025 Robots-Délices. Tous droits réservés.</p>
    </footer>
</body>

</html>