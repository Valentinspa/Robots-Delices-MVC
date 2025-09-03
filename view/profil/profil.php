<?php $csrf_token = generateCsrfToken(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/profil.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <title>Mon Profil - Robots-Délices</title>
</head>
<body>
    <?php
    // require_once 'view/module/header.php';
    ?>
    
    <main>
        <section class="update-hero">
            <h1>Paramètres du Profil</h1>
            <p>Modifier vos informations personnelles</p>
        </section>

        <div class="form-container">
            <form id="update-form" action="/profil" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-section">
                        <h3>Vos Informations Personnelles</h3>
                        <?php if (isset($success)): ?>
                            <div class="success-message"><?= htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="firstname">Prénom :</label>
                            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['firstname']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="lastname">Nom :</label>
                            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse Email :</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Nouveau Mot de Passe :</label>
                            <input type="password" id="password" name="password" placeholder="Laissez vide pour ne pas changer">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmer le Mot de Passe :</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez le nouveau mot de passe">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?>">
                <div class="form-actions">
                        <button type="submit" class="form-btn btn-modifier">Modifier</button>
                        <!-- TODO ajouter js pour confirmer le clique sur le bouton -->
                        <a href="/supprimer-compte?csrf_token=<?php echo $csrf_token ?>" class="form-btn btn-supprimer">Supprimer mon compte</a>
                </div>    
            </form>
        </div>

    </main>
</body>
