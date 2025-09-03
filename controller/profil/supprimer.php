<?php 
// Page de suppression de compte
require_once __DIR__ . '/../../model/user-model.php'; // Modèle utilisateur
require_once __DIR__ . '/../../service/csrf.php'; // Protection CSRF
// Vérification si l'utilisateur est connecté
handleAuthRedirect(true);
// Vérification du token CSRF
if (!isset($_GET['csrf_token']) || !verifyCsrfToken($_GET['csrf_token'])) {
    // Token invalide, redirection ou message d'erreur
    die("Requête invalide ou expirée. Veuillez réessayer.");
}
// Suppression de l'utilisateur
try {
    deleteUser($_SESSION['user_id']);
    // Destruction de la session
    session_unset();
    session_destroy();
    // Redirection vers la page d'accueil avec un message de succès
    header("Location: /?message=compte_supprime");
    exit();
} catch (PDOException $e) {
    // Gestion des erreurs de suppression
    die("Erreur lors de la suppression du compte : " . $e->getMessage());
}