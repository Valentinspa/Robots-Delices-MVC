<?php

require_once __DIR__ . '/../../model/user-model.php';

if(empty($_GET['token'])) {
    // Redirige vers la page de mot de passe oublié si le token est absent
    header('Location: /mot-de-passe-oublie');
    exit;
}
$token = $_GET['token'];
$user = getUserByResetToken($token);
if(!$user) {
    // Redirige vers la page de mot de passe oublié si le token est invalide
    header('Location: /mot-de-passe-oublie');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if($newPassword !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
        
    } 
    // Vérification présence d'au moins une majuscule avec regex
    // [A-Z] = n'importe quelle lettre majuscule
    elseif (!preg_match('/[A-Z]/', $newPassword)) {
        $error = "Le mot de passe doit contenir au moins une majuscule.";
    }
    // Vérification présence d'au moins une minuscule
    // [a-z] = n'importe quelle lettre minuscule
    elseif (!preg_match('/[a-z]/', $newPassword)) {
        $error = "Le mot de passe doit contenir au moins une minuscule.";
    }
    // Vérification présence d'au moins un chiffre
    // [0-9] = n'importe quel chiffre de 0 à 9
    elseif (!preg_match('/[0-9]/', $newPassword)) {
        $error = "Le mot de passe doit contenir au moins un chiffre.";
    }
    // Vérification présence d'au moins un caractère spécial
    // [\W_] = tout caractère non-alphanumérique (ponctuation, symboles)
    elseif (!preg_match('/[\W_]/', $newPassword)) {
        $error = "Le mot de passe doit contenir au moins un caractère spécial.";
    }
    else {
        // Met à jour le mot de passe de l'utilisateur
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        updateUserPassword($user['id'], $hashedPassword);

        $_SESSION["flash_message"] = "Votre mot de passe a été réinitialisé avec succès.";
        // Redirige vers la page de connexion avec un message de succès
        header('Location: /connexion');
        exit;
    }
}

require_once __DIR__ . "/../../view/mot-de-passe-oublie/mdp-reinitialiser.php";