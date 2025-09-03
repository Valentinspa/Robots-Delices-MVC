<?php
// Page Login - Permet aux utilisateurs d'accéder à leur profils
// Inclut des protections contre le brute force et les attaques CSRF

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../../service/csrf.php'; // Protection contre les attaques CSRF
require_once __DIR__ . '/../../model/user-model.php'; // Fonctions liées à la connexion

// Vérification si l'utilisateur est déjà connecté
// Si oui, pas besoin de s'inscrire, redirection vers l'accueil
handleAuthRedirect(true);

$user = getUserById($_SESSION['user_id']); // Récupération des infos utilisateur
// Traitement du formulaire d'inscription (seulement en méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    // htmlspecialchars() échappe les caractères HTML pour éviter les attaques XSS
    // trim() supprime les espaces en début et fin
    $prenom = htmlspecialchars(trim($_POST['firstname'])); // Prénom de l'utilisateur
    $nom = htmlspecialchars(trim($_POST['lastname'])); // Nom de famille
    $email = htmlspecialchars(trim($_POST['email'])); // Adresse email
    $password = trim($_POST['password']); // Mot de passe (pas d'échappement, sera hashé)
    $confirmPassword = trim($_POST['confirm_password']); // Confirmation du mot de passe


    // Validation complète des données reçues du formulaire
    // Chaque condition vérifie un critère différent
   if(!empty($password))
    {
        // Vérification que les deux mots de passe sont identiques
        if ($password !== $confirmPassword) {
            $error = "Les mots de passe ne correspondent pas.";
        }
        // Vérification de la longueur minimale du mot de passe
        elseif (strlen($password) < 8) {
            $error = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        // Vérification présence d'au moins une majuscule avec regex
        // [A-Z] = n'importe quelle lettre majuscule
        elseif (!preg_match('/[A-Z]/', $password)) {
            $error = "Le mot de passe doit contenir au moins une majuscule.";
        }
        // Vérification présence d'au moins une minuscule
        // [a-z] = n'importe quelle lettre minuscule
        elseif (!preg_match('/[a-z]/', $password)) {
            $error = "Le mot de passe doit contenir au moins une minuscule.";
        }
        // Vérification présence d'au moins un chiffre
        // [0-9] = n'importe quel chiffre de 0 à 9
        elseif (!preg_match('/[0-9]/', $password)) {
            $error = "Le mot de passe doit contenir au moins un chiffre.";
        }
        // Vérification présence d'au moins un caractère spécial
        // [\W_] = tout caractère non-alphanumérique (ponctuation, symboles)
        elseif (!preg_match('/[\W_]/', $password)) {
            $error = "Le mot de passe doit contenir au moins un caractère spécial.";
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }
    else {
        $hashedPassword = $user['password']; // Si pas de nouveau mot de passe, garder l'ancien
    }
    if (empty($prenom) || empty($nom) || empty($email)) {
        $error = "Tous les champs marqués d'un * sont obligatoires.";
    }
    // Vérification longueur minimale du prénom et nom
    elseif (strlen($prenom) < 2 || strlen($nom) < 2) {
        $error = "Le prénom et le nom doivent contenir au moins 2 caractères.";
    }
    // Vérification que prénom et nom ne contiennent que des lettres/tirets
    // [a-zA-ZÀ-ÿ-] = lettres (avec accents) et tirets seulement
    elseif (!preg_match('/^[a-zA-ZÀ-ÿ-]+$/', $prenom) || !preg_match('/^[a-zA-ZÀ-ÿ-]+$/', $nom)) {
        $error = "Le prénom et le nom ne doivent contenir que des lettres ou des tirets.";
    }
    // Vérification du token CSRF pour éviter les attaques cross-site
    elseif (verifyCsrfToken($_POST['csrf_token']) === false) {
        $error = "Token CSRF invalide. Veuillez réessayer.";
    }
    // Vérification que l'email a un format valide
    // filter_var avec FILTER_VALIDATE_EMAIL vérifie le format d'email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse e-mail n'est pas valide.";
    } elseif ($email !== $user['email']) {
        // Vérification de l'unicité de l'email en base de données
        // Un utilisateur ne peut pas avoir le même email qu'un autre
        try {
            // Recherche d'un utilisateur avec cet email
            $user = getUserByEmail($email);
            // Si au moins un utilisateur existe avec cet email
            if ($user) {
                $error = "Un compte avec cette adresse e-mail existe déjà.";
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            $error = "Erreur lors de la vérification de l'email : " . $e->getMessage();
        }
    }

    // Si aucune erreur de validation, procéder à la création du compte   
    if (!isset($error)) {
        try {
            updateUser($_SESSION["user_id"],$prenom, $nom, $email, $hashedPassword); // Ajout de l'utilisateur en base
            $_SESSION['firstname'] = $prenom; // Prénom pour affichage navbar
            $_SESSION['lastname'] = $nom;   // Nom pour affichage navbar
            $_SESSION['email'] = $email;         // Email

            $success = "Profil mis à jour avec succès."; // Message de succès
            $user = getUserById($_SESSION['user_id']); // Récupération des infos utilisateur mises à jour
                
        } catch (PDOException $e) {
            $error = "Erreur lors de la modification : " . $e->getMessage(); // Gestion des erreurs d'insertion en base       
        }
    }
}


require_once __DIR__ . '/../../view/profil/profil.php'; // Affichage du profil utilisateur