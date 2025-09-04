<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de votre mot de passe</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; }
        .container { max-width: 500px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);}
        h2 { color: #2c3e50; }
        .btn { display: inline-block; padding: 12px 24px; background: #3498db; color: #fff; text-decoration: none; border-radius: 4px; margin-top: 20px;}
        .footer { margin-top: 30px; font-size: 12px; color: #888;}
    </style>
</head>
<body>
    <div class="container">
        <h2>Réinitialisation de votre mot de passe</h2>
        <p>Bonjour,</p>
        <p>Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>
        <p>
            <a href="<?= htmlspecialchars($resetLink) ?>" class="btn">Réinitialiser mon mot de passe</a>
        </p>
        <p>Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet e-mail.</p>
        <div class="footer">
            &copy; <?= date('Y') ?> Robots Délices. Tous droits réservés.
        </div>
    </div>
</body>
</html>