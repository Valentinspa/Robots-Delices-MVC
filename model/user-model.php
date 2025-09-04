<?php
// Inclut le fichier de connexion à la base de données
require_once __DIR__ . '/../service/connexionBDD.php';


function bruteForceProtection($email): array
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // On vérifie combien de tentatives ont été faites dans les 15 dernières minutes
    $stmt = $pdo->prepare("SELECT * FROM login_attempts WHERE email = ? AND attempt_time > NOW() - INTERVAL 15 MINUTE");
    $stmt->execute([$email]);
    $attempts = $stmt->fetchAll(); // Récupère toutes les tentatives récentes

    // Si plus de 5 tentatives en 15 minutes, on bloque temporairement
    return $attempts;
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
function getUserById($id)
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Recherche de l'utilisateur dans la base de données par email
    // On utilise une requête préparée pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(); // Récupère l'utilisateur ou false si non trouvé
}

function clearLoginAttempts($email): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Nettoyage des tentatives de connexion échouées précédentes
    $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE email = ?");
    $stmt->execute([$email]);
}

function addUser($prenom, $nom, $email, $hashedPassword):string|int
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Insertion du nouvel utilisateur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$prenom, $nom, $email, $hashedPassword]);
    
    $rowAffected = $stmt->rowCount(); // Nombre de lignes affectées par la requête (1 si succès, 0 sinon)
    if ($rowAffected > 0) {
        // retourne l'ID du nouvel utilisateur
        return $pdo->lastInsertId(); 
    } else {
        return 0; // Retourne 0 en cas d'échec
    }
    
}

function updateUser($id, $prenom, $nom, $email, $hashedPassword): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Mise à jour des informations de l'utilisateur dans la base de données
    $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$prenom, $nom, $email, $hashedPassword, $id]);
}

function deleteUser($id): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Suppression de l'utilisateur de la base de données
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$id]);
}

function updatePasswordToken($email, $token): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Mise à jour du token de réinitialisation du mot de passe pour l'utilisateur
    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$token, $email]);
}

function getUserByResetToken($token)
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Recherche de l'utilisateur dans la base de données par token de réinitialisation
    // On utilise une requête préparée pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->execute([$token]);
    return $stmt->fetch(); // Récupère l'utilisateur ou false si non trouvé
}

function updateUserPassword($id, $hashedPassword): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Mise à jour du mot de passe de l'utilisateur dans la base de données
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$hashedPassword, $id]);
}