<?php
// actions/process-declaration.php
session_start();
// Correction du chemin : on remonte d'un seul cran pour trouver config/
require_once '../config/database.php';
require_once '../includes/fidelity-rules.php';

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

        $stmt = $pdo->prepare("SELECT customer_id, amount FROM purchase_declarations WHERE id = :id AND status = 'pending' FOR UPDATE");
        $stmt->execute(['id' => $declaration_id]);
        $declaration = $stmt->fetch();

        if ($declaration) {
            $customer_id = $declaration['customer_id'];

            if ($action === 'approve') {
                $amount = (float)$declaration['amount'];

                // Le palier applicable est celui du cumul d'achats AVANT ce nouvel achat
                // (Article 3 : le passage de palier s'applique "dès l'achat suivant")
                $customerStmt = $pdo->prepare("SELECT total_purchases FROM customers WHERE id = :id FOR UPDATE");
                $customerStmt->execute(['id' => $customer_id]);
                $customerRow = $customerStmt->fetch();
                $totalPurchasesBefore = (float)$customerRow['total_purchases'];

                $tier = getTier($totalPurchasesBefore);
                $rate = $tier['rate'];
                $amountCredited = round($amount * $rate / 100, 2);
                $expiresAt = getExpiryDate();

                $updateDec = $pdo->prepare("
                    UPDATE purchase_declarations
                    SET status = 'approved', rate_applied = :rate, amount_credited = :credited
                    WHERE id = :id
                ");
                $updateDec->execute(['rate' => $rate, 'credited' => $amountCredited, 'id' => $declaration_id]);

                $updateCustomer = $pdo->prepare("
                    UPDATE customers
                    SET savings_balance = savings_balance + :credited,
                        total_purchases = total_purchases + :amount
                    WHERE id = :id
                ");
                $updateCustomer->execute(['credited' => $amountCredited, 'amount' => $amount, 'id' => $customer_id]);

                $ledger = $pdo->prepare("
                    INSERT INTO savings_ledger (customer_id, source_type, source_id, amount, remaining, expires_at)
                    VALUES (:customer_id, 'purchase', :source_id, :amount, :amount, :expires_at)
                ");
                $ledger->execute([
                    'customer_id' => $customer_id,
                    'source_id' => $declaration_id,
                    'amount' => $amountCredited,
                    'expires_at' => $expiresAt,
                ]);
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