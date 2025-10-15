<?php
// Inclut le fichier de connexion à la base de données
require_once __DIR__ . '/../service/connexionBDD.php';

/**
 * Vérifie les tentatives de connexion récentes pour détecter les attaques par force brute
 * Protection : bloque temporairement après plus de 5 tentatives en 15 minutes
 *
 * @param string $email L'adresse email à vérifier
 * @return array Tableau des tentatives de connexion récentes
 */
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

/**
 * Enregistre une tentative de connexion échouée dans la base de données
 *
 * @param string $email L'adresse email de la tentative de connexion
 * @return void
 */
function bruteForceAdd($email): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Enregistrement de cette tentative de connexion dans la BDD
    // NOW() insère la date/heure actuelle
    $stmt = $pdo->prepare("INSERT INTO login_attempts (email, attempt_time) VALUES (?, NOW())");
    $stmt->execute([$email]);
}

/**
 * Récupère un utilisateur par son adresse email
 *
 * @param string $email L'adresse email de l'utilisateur
 * @return array|false Tableau des données de l'utilisateur ou false si non trouvé
 */
function getUserByEmail($email)
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Recherche de l'utilisateur dans la base de données par email
    // On utilise une requête préparée pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(); // Récupère l'utilisateur ou false si non trouvé
}

/**
 * Récupère un utilisateur par son ID
 *
 * @param int $id L'ID de l'utilisateur
 * @return array|false Tableau des données de l'utilisateur ou false si non trouvé
 */
function getUserById($id)
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Recherche de l'utilisateur dans la base de données par email
    // On utilise une requête préparée pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(); // Récupère l'utilisateur ou false si non trouvé
}

/**
 * Supprime toutes les tentatives de connexion échouées pour un email donné
 * Utilisé après une connexion réussie pour réinitialiser le compteur
 *
 * @param string $email L'adresse email à nettoyer
 * @return void
 */
function clearLoginAttempts($email): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Nettoyage des tentatives de connexion échouées précédentes
    $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE email = ?");
    $stmt->execute([$email]);
}

/**
 * Ajoute un nouvel utilisateur dans la base de données
 *
 * @param string $prenom Le prénom de l'utilisateur
 * @param string $nom Le nom de l'utilisateur
 * @param string $email L'adresse email de l'utilisateur
 * @param string $hashedPassword Le mot de passe haché (hashé avec password_hash)
 * @return string|int L'ID du nouvel utilisateur si succès, 0 si échec
 */
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

/**
 * Met à jour les informations d'un utilisateur existant
 *
 * @param int $id L'ID de l'utilisateur à modifier
 * @param string $prenom Le nouveau prénom
 * @param string $nom Le nouveau nom
 * @param string $email La nouvelle adresse email
 * @param string $hashedPassword Le nouveau mot de passe haché
 * @return void
 */
function updateUser($id, $prenom, $nom, $email, $hashedPassword): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Mise à jour des informations de l'utilisateur dans la base de données
    $stmt = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$prenom, $nom, $email, $hashedPassword, $id]);
}

/**
 * Supprime un utilisateur de la base de données
 *
 * @param int $id L'ID de l'utilisateur à supprimer
 * @return void
 */
function deleteUser($id): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Suppression de l'utilisateur de la base de données
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$id]);
}

/**
 * Génère et enregistre un token de réinitialisation de mot de passe
 * Le token expire après 1 heure
 *
 * @param string $email L'adresse email de l'utilisateur
 * @param string $token Le token de réinitialisation généré
 * @return void
 */
function updatePasswordToken($email, $token): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Mise à jour du token de réinitialisation du mot de passe pour l'utilisateur
    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$token, $email]);
}

/**
 * Récupère un utilisateur par son token de réinitialisation de mot de passe
 * Vérifie également que le token n'est pas expiré
 *
 * @param string $token Le token de réinitialisation
 * @return array|false Tableau des données de l'utilisateur ou false si token invalide/expiré
 */
function getUserByResetToken($token)
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Recherche de l'utilisateur dans la base de données par token de réinitialisation
    // On utilise une requête préparée pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->execute([$token]);
    return $stmt->fetch(); // Récupère l'utilisateur ou false si non trouvé
}

/**
 * Met à jour le mot de passe d'un utilisateur et supprime le token de réinitialisation
 * Utilisé après une réinitialisation de mot de passe réussie
 *
 * @param int $id L'ID de l'utilisateur
 * @param string $hashedPassword Le nouveau mot de passe haché
 * @return void
 */
function updateUserPassword($id, $hashedPassword): void
{
    $pdo = connexionBDD(); // Établit la connexion à la base de données
    // Mise à jour du mot de passe de l'utilisateur dans la base de données
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?"); // Requête préparée pour insérer les données de façon sécurisé            
    $stmt->execute([$hashedPassword, $id]);
}