<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Assurez-vous d'avoir installé PHPMailer via Composer
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Envoie un email avec PHPMailer.
 *
 * @param string $to      Adresse email du destinataire
 * @param string $subject Sujet de l'email
 * @param string $body    Corps du message (HTML accepté)
 * @param string $from    Adresse email de l'expéditeur
 * @param string $fromName Nom de l'expéditeur
 * @return bool           true si l'email a été envoyé, false sinon
 */
function sendMail($to, $subject, $body, $from, $fromName = '') {
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io'; // À remplacer par votre serveur SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = '5775f7f33ede50'; // À remplacer
        $mail->Password   = 'a0b9074998f3a0'; // À remplacer
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 2525;
        $mail->CharSet    = 'UTF-8';

        // Expéditeur et destinataire
        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Vous pouvez logger l'erreur ici : $mail->ErrorInfo
        return false;
    }
}