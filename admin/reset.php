<?php
// admin/reset.php
require_once '../config/database.php';

try {
    // 1. On vide proprement la table des administrateurs
    $pdo->exec("TRUNCATE TABLE admins");
    echo "🧹 Table 'admins' vidée avec succès.<br>";

    // 2. On définit tes identifiants (Tu peux changer le mot de passe ici si tu veux)
    $username = 'jemea';
    $password_clair = 'jemea2026'; // C'est ce que tu taperas dans le formulaire
    
    // On crée le hash ultra-sécurisé que PHP et password_verify attendent
    $password_hash = password_hash($password_clair, PASSWORD_DEFAULT);

    // 3. On insère le compte tout neuf
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
    $stmt->execute([
        'username' => $username,
        'password' => $password_hash
    ]);

    echo "✨ Compte Administrateur créé avec succès !<br><br>";
    echo "--- Vos identifiants de test ---<br>";
    echo "👤 Identifiant : <strong>" . $username . "</strong><br>";
    echo "🔑 Mot de passe : <strong>" . $password_clair . "</strong><br><br>";
    echo "<a href='index.php'>Aller à la page de connexion</a>";

} catch (PDOException $e) {
    die("❌ Erreur lors de la réinitialisation : " . $e->getMessage());
}