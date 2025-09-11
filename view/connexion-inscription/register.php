<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://www.google.com/recaptcha/api.js" defer></script>
    <script defer>
        function onSubmit(token) {
            document.getElementById("register-form").submit();
        }
    </script>

    <title>Inscription - Robots-Délices</title>
</head>

<body class="register-page">
    <?php
    require_once __DIR__ . '/../../view/module/header.php';
    ?>
    <main>
        <div id="section-container">
            <!-- Section gauche -->
            <div id="login-container">
                <img alt="Logo Robots-Délices" id="logo" src="/assets/img/logo_robots_delices.png" />
                <p>Rejoignez notre communauté de passionnés de cuisine et partagez vos meilleures recettes</p>
            </div>

            <!-- Section droite avec formulaire d'inscription -->
            <div class="right-section">
                <div class="tabs-container">
                    <a href="/connexion">Connexion</a>
                    <a href="/inscription" class="active">Inscription</a>
                </div>
                <div id="form-container">
                    <form id="register-form" action="/inscription" method="POST">
                        <div>
                            <label for="prenom">Prénom :</label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                        <div>
                            <label for="nom">Nom :</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        <div>
                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div>
                            <label for="password">Mot de passe :</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div>
                            <label for="confirm-password">Confirmer le mot de passe :</label>
                            <input type="password" id="confirm-password" name="confirm-password" required>
                        </div>
                        <?php if (isset($error)): ?>
                            <p class="error-message"><?php echo $error; ?></p>
                        <?php endif; ?>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <button type="submit" class="g-recaptcha" data-sitekey="<?php echo $_ENV['RECAPTCHA_SITE_KEY'] ?>" data-callback='onSubmit' data-action='submit'>S'inscrire</button>
                    </form>
                    <p>Déjà inscrit ? <a href="/connexion">Se connecter</a></p>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>© 2025 Robots-Délices. Tous droits réservés.</p>
    </footer>
</body>

</html>