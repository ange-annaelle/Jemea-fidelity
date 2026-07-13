<?php
// actions/process-declaration.php
session_start();
// Correction du chemin : on remonte d'un seul cran pour trouver config/
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged'])) {
    // Correction du chemin vers la page de connexion admin
    header('Location: ../admin/index.php');
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$declaration_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($declaration_id > 0 && ($action === 'approve' || $action === 'reject')) {
    
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT customer_id FROM purchase_declarations WHERE id = :id AND status = 'pending'");
        $stmt->execute(['id' => $declaration_id]);
        $declaration = $stmt->fetch();

        if ($declaration) {
            $customer_id = $declaration['customer_id'];

            if ($action === 'approve') {
                $updateDec = $pdo->prepare("UPDATE purchase_declarations SET status = 'approved' WHERE id = :id");
                $updateDec->execute(['id' => $declaration_id]);

                $updatePoints = $pdo->prepare("UPDATE customers SET points_balance = points_balance + 1 WHERE id = :id");
                $updatePoints->execute(['id' => $customer_id]);
            } else {
                $updateDec = $pdo->prepare("UPDATE purchase_declarations SET status = 'rejected' WHERE id = :id");
                $updateDec->execute(['id' => $declaration_id]);
            }

            $pdo->commit();
            // Correction du chemin de redirection vers le dashboard admin
            header('Location: ../admin/dashboard.php?msg=success');
            exit();
        } else {
            $pdo->rollBack();
            // Correction du chemin de redirection vers le dashboard admin
            header('Location: ../admin/dashboard.php?error=not_found');
            exit();
        }

    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erreur technique lors du traitement : " . $e->getMessage());
    }

} else {
    // Correction du chemin de redirection vers le dashboard admin
    header('Location: ../admin/dashboard.php');
    exit();
}