<?php
// admin/apply-discount.php
session_start();
require_once '../config/database.php';
require_once '../includes/fidelity-rules.php';

if (!isset($_SESSION['admin_logged'])) {
    header('Location: index.php');
    exit();
}

$customer = null;
$error = '';
$success = '';

if (isset($_POST['search_phone'])) {
    $phone = trim($_POST['phone']);
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE phone = :phone");
    $stmt->execute(['phone' => $phone]);
    $customer = $stmt->fetch();
    if ($customer) {
        // On rafraîchit le solde (supprime les crédits expirés) avant d'afficher/utiliser quoi que ce soit
        $customer['savings_balance'] = refreshCustomerBalance($pdo, $customer['id']);
    } else {
        $error = "Aucun client trouvé avec ce numéro.";
    }
}

if (isset($_POST['confirm_discount'])) {
    $customer_id = (int)$_POST['customer_id'];
    $purchaseAmount = (float)str_replace(',', '.', $_POST['purchase_amount']);
    $requestedDiscount = (float)str_replace(',', '.', $_POST['discount_amount']);

    $maxAllowed = $purchaseAmount * 0.5; // Plafond de 50% (Article 4)

    $stmt = $pdo->prepare("SELECT savings_balance FROM customers WHERE id = :id");
    $stmt->execute(['id' => $customer_id]);
    $row = $stmt->fetch();
    $available = $row ? (float)$row['savings_balance'] : 0;

    if ($purchaseAmount <= 0) {
        $error = "Montant d'achat invalide.";
    } elseif ($requestedDiscount > $maxAllowed) {
        $error = "La remise ne peut pas dépasser 50% du montant de l'achat, soit " . number_format($maxAllowed, 0, ',', ' ') . " FCFA maximum.";
    } elseif ($requestedDiscount > $available) {
        $error = "Le client n'a que " . number_format($available, 0, ',', ' ') . " FCFA de solde disponible.";
    } else {
        try {
            $pdo->beginTransaction();

            consumeSavings($pdo, $customer_id, $requestedDiscount);

            $insertRedemption = $pdo->prepare("
                INSERT INTO redemptions (customer_id, purchase_amount, discount_applied, created_by_admin)
                VALUES (:customer_id, :purchase_amount, :discount, :admin_id)
            ");
            $insertRedemption->execute([
                'customer_id' => $customer_id,
                'purchase_amount' => $purchaseAmount,
                'discount' => $requestedDiscount,
                'admin_id' => $_SESSION['admin_id'] ?? null,
            ]);

            $pdo->commit();
            $success = "Remise de " . number_format($requestedDiscount, 0, ',', ' ') . " FCFA appliquée avec succès.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Erreur technique : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jemea Admin - Appliquer une remise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5" style="max-width: 560px;">
        <a href="dashboard.php" class="text-decoration-none small mb-3 d-inline-block"><i class="fa-solid fa-arrow-left me-1"></i> Retour au tableau de bord</a>
        <h4 class="fw-bold mb-4">Appliquer une remise sur un achat</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (!$customer): ?>
            <form method="POST" class="bg-white p-4 rounded-4 shadow-sm border">
                <label class="form-label small fw-bold">Numéro de téléphone du client</label>
                <input type="text" name="phone" class="form-control mb-3" placeholder="694992229" required>
                <button type="submit" name="search_phone" class="btn btn-jemea w-100">Rechercher le client</button>
            </form>
        <?php else: ?>
            <div class="bg-white p-4 rounded-4 shadow-sm border">
                <p class="mb-1"><strong>Client :</strong> <?php echo htmlspecialchars($customer['fullname']); ?></p>
                <p class="text-muted small mb-4">
                    Solde disponible : <strong><?php echo number_format($customer['savings_balance'], 0, ',', ' '); ?> FCFA</strong>
                </p>

                <form method="POST">
                    <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">

                    <label class="form-label small fw-bold">Montant total de l'achat (FCFA)</label>
                    <input type="number" step="0.01" name="purchase_amount" class="form-control mb-3" required>

                    <label class="form-label small fw-bold">Montant de la remise à appliquer (FCFA)</label>
                    <input type="number" step="0.01" name="discount_amount" class="form-control mb-2" required>
                    <p class="small text-muted mb-3">Plafond légal : 50% du montant de l'achat.</p>

                    <button type="submit" name="confirm_discount" class="btn btn-jemea w-100">Confirmer la remise</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>