<?php
// Inclut le fichier de connexion à la base de données
require_once __DIR__ . '/../service/connexionBDD.php';


function bruteForceProtection($email): bool
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // On vérifie combien de tentatives ont été faites dans les 15 dernières minutes
    $stmt = $pdo->prepare("SELECT * FROM login_attempts WHERE email = ? AND attempt_time > NOW() - INTERVAL 15 MINUTE");
    $stmt->execute([$email]);
    $attempts = $stmt->fetchAll(); // Récupère toutes les tentatives récentes

    // Si plus de 5 tentatives en 15 minutes, on bloque temporairement
    return count($attempts) < 5;
}

function bruteForceAdd($email): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Enregistrement de cette tentative de connexion dans la BDD
    // NOW() insère la date/heure actuelle
    $stmt = $pdo->prepare("INSERT INTO login_attempts (email, attempt_time) VALUES (?, NOW())");
    $stmt->execute([$email]);
}

function getUserByEmail($email)
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Recherche de l'utilisateur dans la base de données par email
    // On utilise une requête préparée pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(); // Récupère l'utilisateur ou false si non trouvé
}

function clearLoginAttempts($email): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Nettoyage des tentatives de connexion échouées précédentes
    $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE email = ?");
    $stmt->execute([$email]);
}