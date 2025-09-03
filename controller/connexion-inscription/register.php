<?php
// Page d'inscription - Permet aux nouveaux utilisateurs de créer un compte
// Inclut validation des données, vérification reCAPTCHA et protection CSRF

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../../service/csrf.php'; // Protection contre les attaques CSRF
require_once __DIR__ . '/../../model/user-model.php'; // Fonctions liées à la connexion

// Vérification si l'utilisateur est déjà connecté
// Si oui, pas besoin de s'inscrire, redirection vers l'accueil
handleAuthRedirect(false);

// Traitement du formulaire d'inscription (seulement en méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    // htmlspecialchars() échappe les caractères HTML pour éviter les attaques XSS
    // trim() supprime les espaces en début et fin
    $prenom = htmlspecialchars(trim($_POST['prenom'])); // Prénom de l'utilisateur
    $nom = htmlspecialchars(trim($_POST['nom'])); // Nom de famille
    $email = htmlspecialchars(trim($_POST['email'])); // Adresse email
    $password = trim($_POST['password']); // Mot de passe (pas d'échappement, sera hashé)
    $confirmPassword = trim($_POST['confirm-password']); // Confirmation du mot de passe

    // Vérification reCAPTCHA pour éviter les robots/spam
    // Google reCAPTCHA vérifie que l'utilisateur est bien humain
    $recaptchaResponse = $_POST['g-recaptcha-response']; // Token reçu du widget reCAPTCHA
    $secret = $_ENV['RECAPTCHA_SECRET_KEY']; // Clé secrète (dans variables d'environnement)
    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify"; // URL de l'API Google

    // Envoi d'une requête POST à l'API Google reCAPTCHA avec CURL
    $ch = curl_init(); // Initialise une session cURL
    curl_setopt($ch, CURLOPT_URL, $recaptchaUrl); // Définit l'URL de destination
    curl_setopt($ch, CURLOPT_POST, true); // Méthode POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ // Données à envoyer
        'secret' => $secret, // Clé secrète de votre site
        'response' => $recaptchaResponse // Token du widget reCAPTCHA
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retourner la réponse au lieu de l'afficher
    $response = curl_exec($ch); // Exécuter la requête
    curl_close($ch); // Fermer la session cURL
    $responseData = json_decode($response, true); // Décoder la réponse JSON

    // Si reCAPTCHA échoue, définir une erreur
    if ($responseData['success'] === false) {
        $error = "La vérification reCAPTCHA a échoué. Veuillez réessayer.";
    }
    // Validation complète des données reçues du formulaire
    // Chaque condition vérifie un critère différent

    // Vérification que tous les champs obligatoires sont remplis
    if (empty($prenom) || empty($nom) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "Tous les champs sont requis.";
    }
    // Vérification que les deux mots de passe sont identiques
    elseif ($password !== $confirmPassword) {
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
    } else {
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
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try {
            $id = addUser($prenom, $nom, $email, $hashedPassword); // Ajout de l'utilisateur en base
            if ($id === 0) {
                $error = "Erreur lors de l'inscription. Veuillez réessayer.";
            } else {
                $_SESSION['user_id'] = $id;
                $_SESSION['firstname'] = $prenom; // Prénom pour affichage navbar
                $_SESSION['lastname'] = $nom;   // Nom pour affichage navbar
                $_SESSION['email'] = $email;         // Email
                header('Location: /connexion'); // Inscription réussie : redirection vers la page de connexion        
                exit(); // Important : arrêter le script apres redirection    
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de l'inscription : " . $e->getMessage(); // Gestion des erreurs d'insertion en base       
        }
    }
}

require_once __DIR__ . '/../../view/connexion-inscription/register.php'; // Affichage du formulaire

?>