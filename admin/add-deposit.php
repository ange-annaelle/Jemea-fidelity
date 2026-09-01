<?php
// admin/add-deposit.php
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

// Étape 1 : recherche du client par téléphone
if (isset($_POST['search_phone'])) {
    $phone = trim($_POST['phone']);
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE phone = :phone");
    $stmt->execute(['phone' => $phone]);
    $customer = $stmt->fetch();
    if (!$customer) {
        $error = "Aucun client trouvé avec ce numéro.";
    }
}

// Étape 2 : enregistrement du dépôt
if (isset($_POST['confirm_deposit'])) {
    $customer_id = (int)$_POST['customer_id'];
    $amount = (float)str_replace(',', '.', $_POST['amount']);
    $method = $_POST['method'] === 'mtn_momo' ? 'mtn_momo' : 'orange_money';

    if ($amount <= 0) {
        $error = "Montant invalide.";
    } else {
        try {
            $pdo->beginTransaction();

            $bonusRate = getDepositBonusRate($amount);
            $bonusAmount = round($amount * $bonusRate / 100, 2);
            $totalCredited = $amount + $bonusAmount;
            $expiresAt = getExpiryDate();

            $insertDeposit = $pdo->prepare("
                INSERT INTO deposits (customer_id, amount, bonus_rate, bonus_amount, total_credited, method, created_by_admin)
                VALUES (:customer_id, :amount, :bonus_rate, :bonus_amount, :total_credited, :method, :admin_id)
            ");
            $insertDeposit->execute([
                'customer_id' => $customer_id,
                'amount' => $amount,
                'bonus_rate' => $bonusRate,
                'bonus_amount' => $bonusAmount,
                'total_credited' => $totalCredited,
                'method' => $method,
                'admin_id' => $_SESSION['admin_id'] ?? null,
            ]);
            $depositId = $pdo->lastInsertId();

            $updateCustomer = $pdo->prepare("
                UPDATE customers SET savings_balance = savings_balance + :credited WHERE id = :id
            ");
            $updateCustomer->execute(['credited' => $totalCredited, 'id' => $customer_id]);

            $ledger = $pdo->prepare("
                INSERT INTO savings_ledger (customer_id, source_type, source_id, amount, remaining, expires_at)
                VALUES (:customer_id, 'deposit', :source_id, :amount, :amount, :expires_at)
            ");
            $ledger->execute([
                'customer_id' => $customer_id,
                'source_id' => $depositId,
                'amount' => $totalCredited,
                'expires_at' => $expiresAt,
            ]);

            $pdo->commit();
            $success = "Dépôt enregistré : {$amount} FCFA + bonus {$bonusRate}% ({$bonusAmount} FCFA) = {$totalCredited} FCFA crédités.";
        } catch (PDOException $e) {
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
    <title>Jemea Admin - Dépôt Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container py-5" style="max-width: 560px;">
        <a href="dashboard.php" class="text-decoration-none small mb-3 d-inline-block"><i class="fa-solid fa-arrow-left me-1"></i> Retour au tableau de bord</a>
        <h4 class="fw-bold mb-4">Enregistrer un dépôt Mobile Money</h4>

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
                <p class="text-muted small mb-4"><?php echo htmlspecialchars($customer['phone']); ?> — Solde actuel : <?php echo number_format($customer['savings_balance'], 0, ',', ' '); ?> FCFA</p>

                <form method="POST">
                    <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">

                    <label class="form-label small fw-bold">Méthode</label>
                    <select name="method" class="form-select mb-3" required>
                        <option value="orange_money">Orange Money</option>
                        <option value="mtn_momo">MTN Mobile Money</option>
                    </select>

                    <label class="form-label small fw-bold">Montant déposé (FCFA)</label>
                    <input type="number" step="0.01" name="amount" class="form-control mb-2" required>
                    <p class="small text-muted mb-3">
                        Bonus appliqué automatiquement : <strong>3%</strong> si &lt; 20 000 FCFA,
                        <strong>5%</strong> entre 20 000 et 50 000 FCFA, <strong>8%</strong> au-delà.
                    </p>

                    <button type="submit" name="confirm_deposit" class="btn btn-jemea w-100">Confirmer le dépôt et créditer</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>