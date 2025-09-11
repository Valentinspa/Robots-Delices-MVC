<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Reinitialiser votre mot de passe</title>
</head>

<body>
    <div class="container">
        <section class="mdp-reinitialiser-hero">
                <h1>Reinitialiser votre mot de passe</h1>
                <p>Veuillez entrer votre nouveau mot de passe ci-dessous.</p>
        </section>
        <form class="mdp-reinitialiser-form" action="/reinitialiser-mdp?token=<?= $token ?>" method="POST">
            <label for="new_password">Nouveau mot de passe:</label>
            <input type="password" id="new_password" name="new_password" required>
            <br>
            <label for="confirm_password">Confirmer le mot de passe:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br>
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <button type="submit">Réinitialiser le mot de passe</button>
        </form>
    </div>

</body>
</html>