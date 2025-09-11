<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <title>Connexion - Robots-Délices</title>
</head>

<body class="login-page">
    <?php
    require_once 'view/module/header.php';
    ?>
    <main>
        <div id="section-container">
            <!-- Section gauche avec logo et texte -->
            <div id="login-container">
                <img alt="Logo Robots-Délices" id="logo" src="/assets/img/logo_robots_delices.png" />
                <p>Rejoignez notre communauté de passionnés de cuisine et partagez vos meilleures recettes</p>
            </div>

            <!-- Section droite avec formulaire de connexion -->
            <div class="right-section">
                <div class="tabs-container">
                    <a href="/connexion" class="active">Connexion</a>
                    <a href="/inscription">Inscription</a>
                </div>
                <div id="form-container">
                    <?php // Affiche un message flash s'il existe
                    if (isset($_SESSION["flash_message"])): ?>
                        <p class="flash-message"><?php echo $_SESSION["flash_message"]; ?></p>
                    <?php
                        unset($_SESSION["flash_message"]); // Supprime le message après l'affichage
                    endif; ?>
                    <form id="login-form" action="/connexion" method="POST">
                        <div>
                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div>
                            <label for="password">Mot de passe :</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <?php if (isset($error)): ?>
                            <p class="error-message"><?php echo $error; ?></p>
                        <?php endif; ?>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <button type="submit">Se connecter</button>
                    </form>
                    <a href="/mot-de-passe-oublie">Mot de passe oublié ?</a>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>© 2025 Robots-Délices. Tous droits réservés.</p>
    </footer>
</body>

</html>