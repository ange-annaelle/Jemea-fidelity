<?php
// admin/dashboard.php
session_start();
require_once '../config/database.php';

// Sécurité : Si l'admin n'est pas connecté, retour à la case départ
if (!isset($_SESSION['admin_logged'])) {
    header('Location: index.php');
    exit();
}

// 1. Récupération des statistiques globales pour les compteurs
try {
    // Nombre total de clients
    $totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
    
    // Nombre total de demandes en attente
    $totalPending = $pdo->query("SELECT COUNT(*) FROM purchase_declarations WHERE status = 'pending'")->fetchColumn();
    
    // 2. Récupération des déclarations en attente avec les infos du client associé
    $stmt = $pdo->query("
        SELECT pd.id AS declaration_id, pd.created_at, c.id AS customer_id, c.fullname, c.phone, c.points_balance 
        FROM purchase_declarations pd
        JOIN customers c ON pd.customer_id = c.id
        WHERE pd.status = 'pending'
        ORDER BY pd.created_at DESC
    ");
    $declarations = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Jemea Loyalty</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --jemea-green: #004d0d; --jemea-dark: #0b130c; }
        body { background-color: #f4f6f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-admin { background-color: var(--jemea-dark); color: white; }
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .table-container { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-accept { background-color: #198754; color: white; border: none; }
        .btn-accept:hover { background-color: #146c43; color: white; }
        .btn-reject { background-color: #dc3545; color: white; border: none; }
        .btn-reject:hover { background-color: #bb2d3b; color: white; }
    </style>
</head>
<body>

    <!-- NAVBAR ADMIN -->
    <nav class="navbar navbar-expand-lg navbar-admin sticky-top py-3">
        <div class="container">
            <span class="navbar-brand fw-bold text-white d-flex align-items-center gap-2">
                <i class="fa-solid fa-gauge-high text-success"></i> Jemea BackOffice
            </span>
            <div class="d-flex align-items-center gap-3">
                <span class="small text-white-50"><i class="fa-solid fa-user me-1"></i> <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3"><i class="fa-solid fa-power-off"></i></a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        
        <!-- SECTION COMPTEURS/STATISTIQUES -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card p-4 bg-white text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Clients</h6>
                            <h2 class="fw-bold mb-0"><?php echo $totalCustomers; ?></h2>
                        </div>
                        <div class="p-3 bg-success-subtle text-success rounded-3"><i class="fa-solid fa-users fa-xl"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card p-4 bg-white text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold mb-1">Demandes en attente</h6>
                            <h2 class="fw-bold mb-0 text-danger"><?php echo $totalPending; ?></h2>
                        </div>
                        <div class="p-3 bg-danger-subtle text-danger rounded-3 <?php echo ($totalPending > 0) ? 'animate-pulse' : ''; ?>"><i class="fa-solid fa-bell fa-xl"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LISTE DES DEMANDES DE VALIDATION D'ACHAT -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Déclarations d'achats manuelles</h5>
                    <p class="text-muted small mb-0">Validez les demandes après vérification en caisse.</p>
                </div>
                <?php if($totalPending > 0): ?>
                    <span class="badge bg-danger rounded-pill px-3 py-2"><?php echo $totalPending; ?> à traiter</span>
                <?php endif; ?>
            </div>

            <?php if (count($declarations) === 0): ?>
                <div class="text-center py-5">
                    <div class="text-muted mb-3"><i class="fa-solid fa-circle-check fa-3x text-success-subtle"></i></div>
                    <h6 class="fw-bold text-secondary">Aucune demande en attente</h6>
                    <p class="text-muted small">Toutes les cartes clients Jemea sont parfaitement à jour !</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th>Téléphone</th>
                                <th>Solde Actuel</th>
                                <th>Date de demande</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($declarations as $dec): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($dec['fullname']); ?></div>
                                        <span class="text-muted style" style="font-size: 0.75rem;">ID Client: #<?php echo $dec['customer_id']; ?></span>
                                    </td>
                                    <td><i class="fa-solid fa-phone me-1 text-muted small"></i> +237 <?php echo htmlspecialchars($dec['phone']); ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-bold">
                                            <i class="fa-solid fa-star text-warning me-1"></i> <?php echo $dec['points_balance']; ?> pts
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        <?php echo date('d/m/Y à H:i', strtotime($dec['created_at'])); ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <!-- Bouton Accepter -->
                                            <a href="process-declaration.php?action=approve&id=<?php echo $dec['declaration_id']; ?>" 
                                               class="btn btn-accept btn-sm rounded-3 px-3 py-2 fw-medium">
                                                <i class="fa-solid fa-check me-1"></i> Accepter
                                            </a>
                                            <!-- Bouton Refuser -->
                                            <a href="process-declaration.php?action=reject&id=<?php echo $dec['declaration_id']; ?>" 
                                               class="btn btn-reject btn-sm rounded-3 px-3 py-2 fw-medium"
                                               onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette demande ?');">
                                                <i class="fa-solid fa-xmark me-1"></i> Refuser
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>