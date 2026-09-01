<?php
// includes/fidelity-rules.php
// Centralise les règles du Règlement du programme Épargne JEMEA
// (paliers, taux d'épargne, bonus de dépôt, expiration)

/**
 * Détermine le palier de fidélité selon le cumul d'achats du client
 * (Article 3 du règlement)
 */
function getTier(float $totalPurchases): array
{
    if ($totalPurchases >= 300000) {
        return ['name' => 'Or', 'rate' => 7.0, 'next_threshold' => null];
    }
    if ($totalPurchases >= 100000) {
        return ['name' => 'Argent', 'rate' => 5.0, 'next_threshold' => 300000];
    }
    return ['name' => 'Bronze', 'rate' => 3.0, 'next_threshold' => 100000];
}

/**
 * Détermine le taux de bonus applicable à un dépôt Mobile Money
 * (Article 6 du règlement)
 */
function getDepositBonusRate(float $amount): float
{
    if ($amount > 50000) {
        return 8.0;
    }
    if ($amount >= 20000) {
        return 5.0;
    }
    return 3.0;
}

/**
 * Calcule la date d'expiration (12 mois glissants) d'un crédit
 * (Article 5 et 6 du règlement)
 */
function getExpiryDate(): string
{
    return date('Y-m-d H:i:s', strtotime('+12 months'));
}

/**
 * Nettoie les crédits expirés d'un client et retourne son solde réel à jour.
 * À appeler avant d'afficher ou d'utiliser le solde d'un client.
 */
function refreshCustomerBalance(PDO $pdo, int $customerId): float
{
    // 1. Récupérer les lignes expirées et non encore traitées
    $stmt = $pdo->prepare("
        SELECT id, remaining FROM savings_ledger
        WHERE customer_id = :customer_id
          AND expired_and_cleared = 0
          AND expires_at <= NOW()
          AND remaining > 0
    ");
    $stmt->execute(['customer_id' => $customerId]);
    $expired = $stmt->fetchAll();

    if ($expired) {
        $pdo->beginTransaction();
        try {
            $totalExpired = 0;
            foreach ($expired as $row) {
                $totalExpired += $row['remaining'];
                $mark = $pdo->prepare("
                    UPDATE savings_ledger
                    SET remaining = 0, expired_and_cleared = 1
                    WHERE id = :id
                ");
                $mark->execute(['id' => $row['id']]);
            }

            $updateBalance = $pdo->prepare("
                UPDATE customers
                SET savings_balance = GREATEST(savings_balance - :expired, 0)
                WHERE id = :customer_id
            ");
            $updateBalance->execute(['expired' => $totalExpired, 'customer_id' => $customerId]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    $stmt = $pdo->prepare("SELECT savings_balance FROM customers WHERE id = :id");
    $stmt->execute(['id' => $customerId]);
    $result = $stmt->fetch();

    return $result ? (float)$result['savings_balance'] : 0.0;
}

/**
 * Consomme le solde épargne d'un client selon la méthode FIFO
 * (les crédits les plus anciens sont utilisés en premier).
 * Utilisé lors de l'application d'une remise.
 */
function consumeSavings(PDO $pdo, int $customerId, float $amountToConsume): void
{
    $stmt = $pdo->prepare("
        SELECT id, remaining FROM savings_ledger
        WHERE customer_id = :customer_id
          AND expired_and_cleared = 0
          AND remaining > 0
        ORDER BY credited_at ASC
        FOR UPDATE
    ");
    $stmt->execute(['customer_id' => $customerId]);
    $rows = $stmt->fetchAll();

    $remainingToConsume = $amountToConsume;

    foreach ($rows as $row) {
        if ($remainingToConsume <= 0) {
            break;
        }
        $take = min($row['remaining'], $remainingToConsume);
        $newRemaining = $row['remaining'] - $take;

        $update = $pdo->prepare("UPDATE savings_ledger SET remaining = :remaining WHERE id = :id");
        $update->execute(['remaining' => $newRemaining, 'id' => $row['id']]);

        $remainingToConsume -= $take;
    }

    $updateBalance = $pdo->prepare("
        UPDATE customers
        SET savings_balance = GREATEST(savings_balance - :amount, 0)
        WHERE id = :customer_id
    ");
    $updateBalance->execute(['amount' => $amountToConsume, 'customer_id' => $customerId]);
}