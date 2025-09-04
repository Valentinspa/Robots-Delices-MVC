<?php
// Importer le service de mail (à adapter selon votre structure)
require_once __DIR__ . '/../../service/mail.php';
// Importer le modèle utilisateur (à adapter selon votre structure)
require_once __DIR__ . '/../../model/user-model.php';

// Traitement du formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Vérification basique de l'email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Ici, vérifiez si l'email existe dans la base de données
        $user = getUserByEmail($email);
        if ($user) {
            // Générer un token de réinitialisation (exemple simple)
            $token = bin2hex(random_bytes(32));

            // Enregistrer le token en base de données avec expiration (à implémenter)
            updatePasswordToken($email, $token);

            // Préparer et envoyer le mail
            $resetLink = 'http://localhost:15050/reinitialiser-mdp?token=' . $token;
            $subject = 'Réinitialisation de votre mot de passe';
            
            // Charger le template d'email
            ob_start();
            include __DIR__ . '/../../view/mail/mail-mdp-oublie.php';
            $message = ob_get_clean();


            $mailSent = sendMail($email, $subject, $message, "robot@delice.fr");

            if ($mailSent) {
                $success = "Un email de réinitialisation a été envoyé.";
            } else {
                $error = "Erreur lors de l'envoi de l'email.";
            }
        } else {
            // Pour des raisons de sécurité, on n'indique pas que l'email n'existe pas
            $error = "Utilisateur Inconnu.";
        }
    } else {
        $error = "Adresse email invalide.";
    }

}
// Afficher la vue avec message
include __DIR__ . '/../../view/mot-de-passe-oublie/mdp-oublie.php';
exit;
?>