<?php
// process-register.php

// 1. Inclusion de la connexion à la base de données
require_once 'config/database.php';

// 2. Vérification que les données viennent bien d'une soumission POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Récupération et nettoyage des données pour éviter les failles XSS
    $fullname = trim(htmlspecialchars($_POST['fullname']));
    $phone = trim(htmlspecialchars($_POST['phone']));

    // Vérification rapide que les champs ne sont pas vides
    if (empty($fullname) || empty($phone)) {
        die("Erreur : Tous les champs sont obligatoires.");
    }

    try {
        // 4. Vérification si le numéro de téléphone existe déjà dans la base
        $checkStmt = $pdo->prepare("SELECT id FROM customers WHERE phone = :phone");
        $checkStmt->execute(['phone' => $phone]);
        $existingCustomer = $checkStmt->fetch();

        if ($existingCustomer) {
            // Si le client existe déjà, on le redirige directement vers sa carte existante
            // Idéalement, on pourrait ajouter un message, mais pour l'instant on fluidifie le parcours
            header("Location: card.php?phone=" . urlencode($phone));
            exit();
        }

        // 5. Génération d'un token unique pour le QR Code du client
        // On combine un identifiant unique basé sur le temps et une chaîne aléatoire
        $qr_code_token = bin2hex(random_bytes(16)) . time();

        // 6. Insertion du nouveau client dans la base de données
        // Le solde de points est à 0 par défaut grâce à la structure SQL
        $insertStmt = $pdo->prepare("
            INSERT INTO customers (fullname, phone, qr_code_token) 
            VALUES (:fullname, :phone, :qr_code_token)
        ");
        
        $insertStmt->execute([
            'fullname' => $fullname,
            'phone'    => $phone,
            'qr_code_token' => $qr_code_token
        ]);

        // 7. Redirection magique vers la page de la carte avec le numéro de téléphone en paramètre
        header("Location: card.php?phone=" . urlencode($phone));
        exit();

    } catch (PDOException $e) {
        // En cas de pépin avec MySQL, on affiche l'erreur proprement pour le débug
        die("Erreur technique lors de l'enregistrement : " . $e->getMessage());
    }

} else {
    // Si quelqu'un tente d'accéder au fichier directement sans passer par le formulaire
    header("Location: inscription.php");
    exit();
}