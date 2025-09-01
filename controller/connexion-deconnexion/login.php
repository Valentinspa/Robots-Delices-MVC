<?php
// Page de connexion - Permet aux utilisateurs de se connecter à leur compte
// Inclut des protections contre le brute force et les attaques CSRF

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../../model/login-model.php'; // Modèle pour interagir avec les utilisateurs
require_once __DIR__ . "/../../service/csrf.php"; // Protection contre les attaques CSRF

// Démarrage de la session pour gérer l'état de connexion
session_start();

// Vérification si l'utilisateur est déjà connecté
// Si oui, on le redirige vers la page d'accueil (pas besoin de se reconnecter)
if (isset($_SESSION['user_id'])) {
    header('Location: /'); // Redirection HTTP vers index.php
    exit(); // Arrête l'exécution du script après la redirection
}
// Traitement du formulaire seulement si la méthode est POST
// $_SERVER['REQUEST_METHOD'] contient la méthode HTTP utilisée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    // trim() supprime les espaces en début et fin de chaîne
    $email = trim($_POST['email']); // Email saisi par l'utilisateur
    $password = trim($_POST['password']); // Mot de passe saisi
    // Protection contre les attaques par force brute
    bruteForceProtection($email);

    // Si plus de 5 tentatives en 15 minutes, on bloque temporairement
    if (count($attempts) >= 5) {
        $error = "Trop de tentatives de connexion échouées. Veuillez réessayer plus tard.";
    } else {
        bruteForceAdd($email); // Enregistre cette tentative de connexion
    }
    // Validation des données reçues du formulaire
    // Vérification que les champs obligatoires ne sont pas vides
    if (empty($email) || empty($password)) {
        $error = "Tous les champs sont requis.";
    }
    // Vérification du token CSRF pour éviter les attaques cross-site
    // Le token CSRF est généré côté serveur et vérifié à chaque soumission
    elseif (verifyCsrfToken($_POST['csrf_token']) === false) {
        $error = "Token CSRF invalide. Veuillez réessayer.";
    }
    // Si pas d'erreur jusqu'ici, on procède à l'authentification
    elseif (empty($error)) {
        // Tentative d'authentification avec gestion d'erreurs
        try {
            getUserByEmail($email); // Récupère l'utilisateur par email

            // Vérification de l'existence de l'utilisateur ET du mot de passe
            // password_verify() compare le mot de passe en clair avec le hash stocké
            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie !
                // On stocke l'ID de l'utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['firstname'] = $user['firstname']; // Prénom pour affichage navbar
                $_SESSION['lastname'] = $user['lastname'];   // Nom pour affichage navbar
                $_SESSION['email'] = $user['email'];         // Email
                clearLoginAttempts($email); // Nettoie les tentatives de connexion échouées
                // Redirection vers la page d'accueil
                header('Location: /');
                exit(); // Important : arrêter le script après redirection
            } else {
                // Identifiants incorrects
                $error = "Identifiants incorrects.";
                // Délai d'1 seconde pour ralentir les attaques par force brute
                sleep(1);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            $error = "Erreur lors de la connexion : " . $e->getMessage();
        }
    }
}
// Inclusion de la vue (formulaire de connexion)
include_once __DIR__ . '/../../view/connexion-deconnexion/login.php';
?>
