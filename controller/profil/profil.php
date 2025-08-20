<?php
// Page Login - Permet aux utilisateurs d'accéder à leur profils
// Inclut des protections contre le brute force et les attaques CSRF

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../../service/connexionBDD.php'; // Connexion à la base de données
require_once __DIR__ . "/../../service/csrf.php"; // Protection contre les attaques CSRF

// Démarrage de la session pour gérer l'état de connexion
session_start();
?>
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
    require_once 'view/module/header.php';
    ?>
</body>