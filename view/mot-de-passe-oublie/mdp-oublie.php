<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/assets/css/style.css">

    <title>Mot de passe oublié - Robots-Délices</title>
</head>

<body class="password-reset-page">
    <?php
    require_once 'view/module/header.php';
    ?>

    <main>
        <div id="section-container">
            <div id="login-container">
                <img alt="Logo Robots-Délices" id="logo" src="/assets/img/logo_robots_delices.png" />
                <p>Récupérez l'accès à votre compte et retrouvez vos recettes favorites</p>
            </div>

            <div class="right-section">
                <div id="form-container">
                    <?php if (isset($success)) : ?>
                        <div class="success-message">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    <h2>Mot de passe oublié</h2>
                    <p class="form-description">
                        Entrez votre adresse e-mail pour recevoir un lien de réinitialisation de votre mot de passe.
                    </p>

                    <form id="reset-password-form" action="/mot-de-passe-oublie" method="POST">
                        <div>
                            <label for="email">Adresse e-mail :</label>
                            <input type="email" id="email" name="email" required
                                placeholder="votre.email@exemple.com">
                            <?php if (isset($error)) : ?>
                                <div class="error-message">
                                    <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit">Envoyer le lien de réinitialisation</button>
                    </form>

                    <p>
                        Vous vous souvenez de votre mot de passe ?
                        <a href="/connexion">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>© 2025 Robots-Délices. Tous droits réservés.</p>
    </footer>
</body>

</html>