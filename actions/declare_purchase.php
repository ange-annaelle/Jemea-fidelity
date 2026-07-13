<?php
// actions/declare_purchase.php

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : 0;
    
    // On récupère le numéro de téléphone pour pouvoir rediriger le client sur sa page ensuite
    $phone = isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'])) : '';

    if ($customer_id <= 0) {
        die("Erreur : Client invalide.");
    }

    try {
        // 1. Vérifier si le client n'a pas déjà une demande en attente (pour éviter les spams)
        $checkStmt = $pdo->prepare("
            SELECT id FROM purchase_declarations 
            WHERE customer_id = :customer_id AND status = 'pending'
        ");
        $checkStmt->execute(['customer_id' => $customer_id]);
        
        if ($checkStmt->fetch()) {
            // Déjà une demande en cours, on le renvoie juste sur sa carte
            header("Location: ../card.php?phone=" . urlencode($phone) . "&status=already_pending");
            exit();
        }

        // 2. Insertion de la déclaration manuelle
        $insertStmt = $pdo->prepare("
            INSERT INTO purchase_declarations (customer_id, status) 
            VALUES (:customer_id, 'pending')
        ");
        $insertStmt->execute(['customer_id' => $customer_id]);

        // 3. Redirection réussie vers la carte
        header("Location: ../card.php?phone=" . urlencode($phone) . "&status=requested");
        exit();

    } catch (PDOException $e) {
        die("Erreur technique lors de la déclaration : " . $e->getMessage());
    }
} else {
    header("Location: ../inscription.php");
    exit();
}