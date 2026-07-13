<?php
// card.php
require_once 'config/database.php';

$phone = isset($_GET['phone']) ? trim(htmlspecialchars($_GET['phone'])) : '';
$customer = null;
$error_msg = "";

// Si un numéro est soumis ou présent dans l'URL
if (!empty($phone)) {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE phone = :phone");
    $stmt->execute(['phone' => $phone]);
    $customer = $stmt->fetch();
    
    // Si le numéro n'existe pas en BDD, on génère un message d'erreur
    if (!$customer) {
        $error_msg = "Ce numéro de téléphone n'est pas associé à une carte Jemea.";
        $customer = null; // Force le retour au petit formulaire
    }
}

// Extraction des données si le client est trouvé
if ($customer) {
    $fullname = $customer['fullname'];
    $matricule = "JM-" . str_pad($customer['id'], 4, "0", STR_PAD_LEFT);
    $points = (int)$customer['points_balance']; 
    $display_points = min($points, 10);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Carte Jemea Loyalty 3D</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/image/jemea.jpeg" alt="logo jemea" class="logo-jemea">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-3">
                    <li class="nav-item"><a class="nav-link" href="https://jemeaproducts.com">Boutique</a></li>
                    <li class="nav-item"><a class="nav-link active" href="card.php">Voir ma carte</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="card-page-bg">
        <div class="container">

            <?php if (!$customer): ?>
                <div class="form-shadow-wrapper mx-auto">
                    <div class="form-main-card">
                        
                        <div class="text-center mb-4">
                            <img src="assets/image/jemea.jpeg" alt="logo jemea" style="height: 50px; width: auto; object-fit: contain;">
                        </div>

                        <div class="laser-line-container">
                            <div class="laser-line"></div>
                        </div>

                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-1">Accéder à ma carte</h4>
                            <p class="text-muted small">Entrez votre numéro pour afficher vos points.</p>
                        </div>

                        <?php if(!empty($error_msg)): ?>
                            <div class="alert alert-danger d-flex align-items-center gap-2 small py-2 rounded-3" role="alert">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <div><?php echo $error_msg; ?></div>
                            </div>
                        <?php endif; ?>

                        <form action="card.php" method="GET">
                            <div class="mb-4">
                                <label for="phone" class="form-label small fw-bold text-secondary">Numéro de téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-custom">+237</span>
                                    <input type="tel" class="form-control form-custom-input" id="phone" name="phone" placeholder="694992229" pattern="[0-9]{9}" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-jemea w-100 py-3 rounded-3">
                                Afficher mon Pass <i class="fa-solid fa-eye ms-2 small"></i>
                            </button>
                            
                            <div class="text-center mt-3">
                                <a href="inscription.php" class="text-decoration-none small" style="color: var(--jemea-green); font-weight: 500;">Je n'ai pas encore de carte</a>
                            </div>
                        </form>
                    </div>
                </div>

            <?php else: ?>
                <div class="card-3d-wrapper">
                    <div class="jemea-card-inner">
                        
                        <div class="card-front">
                            <div class="card-top-row">
                                <img src="assets/image/jemea.jpeg" alt="logo jemea" style="height: 28px; filter: brightness(0) invert(1); object-fit: contain;">
                                <span class="card-matricule"><?php echo $matricule; ?></span>
                            </div>

                            <div class="card-middle-row mb-2">
                                <div>
                                    <div class="card-owner-name text-truncate" style="max-width: 320px;"><?php echo $fullname; ?></div>
                                    <span class="small text-white-50"><i class="fa-solid fa-star text-warning me-1"></i> Membre Privilège</span>
                                </div>
                                <div class="text-white-50 opacity-50"><i class="fa-solid fa-rotate-y fa-lg"></i></div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1 small text-white-50" style="font-size: 0.75rem;">
                                    <span>Progression Fidélité</span>
                                    <span><strong><?php echo $points; ?></strong> / 10 Points</span>
                                </div>
                                
                                <div class="stamps-row">
                                    <?php for($i = 1; $i <= 10; $i++): ?>
                                        <?php if($i <= $display_points): ?>
                                            <div class="stamp-slot checked"><i class="fa-solid fa-check" style="font-size: 0.6rem;"></i></div>
                                        <?php else: ?>
                                            <div class="stamp-slot"><?php echo $i; ?></div>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-back">
                            <div class="card-magnetic-strip"></div>
                            
                            <div class="d-flex justify-content-between align-items-end w-100 mt-2">
                                <div class="card-tech-chip"></div>
                                
                                <div class="card-qr-box">
                                    <?php 
                                    $qr_data = $customer['qr_code_token'];
                                    $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=" . urlencode($qr_data) . "&color=000000";
                                    ?>
                                    <img src="<?php echo $qr_url; ?>" alt="QR Code" style="width: 70px; height: 70px; display: block;">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center small text-white-50 mt-3" style="font-size: 0.7rem;">
                                <span>Survolez la carte pour voir le recto</span>
                                <span class="fw-bold">JEMEA LOYALTY</span>
                            </div>
                        </div>

                    </div>
                </div>


<!-- BLOC DÉCLARATION MANUELLE (À insérer sous la jauge de progression) -->
<div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
    <h6 class="fw-bold text-dark text-center mb-2">Vous êtes en boutique et le scanner ne marche pas ?</h6>
    <p class="text-muted small text-center mb-3">Déclarez votre achat manuellement pour que le vendeur valide vos points.</p>
    
    <!-- Zone dynamique selon l'état de la demande -->
    <div class="text-center">
        <!-- État 1 : Par défaut, le bouton pour déclarer -->
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-toggle="collapse" data-bs-target="#demoDeclarer">
            <i class="fa-solid fa-hand-holding-dollar me-2"></i> Déclarer mon achat actuel
        </button>

        <!-- Formulaire caché qui se déroule au clic -->
        <div class="collapse mt-3 text-start" id="demoDeclarer">
            <div class="p-3 bg-light rounded-3 border">
                <form action="actions/declare_purchase.php" method="POST">
                    <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">
                    <div class="mb-2">
                        <span class="small text-muted d-block mb-1">Une notification sera envoyée instantanément à la caisse Jemea.</span>
                    </div>
                    <button type="submit" class="btn btn-jemea btn-sm w-100 py-2">
                        Envoyer la demande de validation <i class="fa-solid fa-paper-plane ms-1"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- État 2 : Si le client a déjà cliqué (Exemple visuel du mode attente) -->
        <!-- 
        <div class="alert alert-warning d-flex align-items-center justify-content-center gap-2 small py-2 rounded-3 mb-0" role="alert">
            <i class="fa-solid fa-spinner fa-spin"></i>
            <div>Validation en attente côté caisse...</div>
        </div> 
        -->
    </div>
</div>



                <div class="perks-section">
                    <div class="bg-white p-4 rounded-4 shadow-sm border mb-4 text-center">
                        <h5 class="fw-bold text-dark mb-3">Statut global de vos avantages</h5>
                        <div class="progress mb-2" style="height: 12px; border-radius: 6px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?php echo min(($points/10)*100, 100); ?>%; background-color: var(--jemea-green);" 
                                 aria-valuenow="<?php echo $points; ?>" aria-valuemin="0" aria-valuemax="10"></div>
                        </div>
                        <p class="small text-muted mb-0">
                            <?php if($points >= 10): ?>
                                🎉 Félicitations ! Vous avez débloqué votre cadeau. Présentez votre carte au staff Jemea.
                            <?php else: ?>
                                Plus que <strong><?php echo (10 - $points); ?></strong> achats avant votre prochain pack ou produit offert !
                            <?php endif; ?>
                        </p>
                    </div>

                    <h6 class="fw-bold text-secondary text-uppercase tracking-wider mb-3" style="font-size: 0.8rem;">Vos Cadeaux Disponibles</h6>
                    <div class="d-flex flex-column gap-3">
                        
                        <div class="perk-item d-flex align-items-center justify-content-between shadow-sm <?php echo ($points >= 3) ? 'unlocked bg-light-subtle' : ''; ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 bg-warning-subtle text-warning rounded-3"><i class="fa-solid fa-mortar-pestle fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">Un sachet d'épices au choix</h6>
                                    <span class="small text-muted">Débloqué à 3 points</span>
                                </div>
                            </div>
                            <?php echo ($points >= 3) ? '<span class="badge bg-success text-white rounded-pill"><i class="fa-solid fa-circle-check"></i> Acquis</span>' : '<span class="badge bg-secondary text-white-50 rounded-pill">Verrouillé</span>'; ?>
                        </div>

                        <div class="perk-item d-flex align-items-center justify-content-between shadow-sm <?php echo ($points >= 7) ? 'unlocked bg-light-subtle' : ''; ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 bg-success-subtle text-success rounded-3"><i class="fa-solid fa-bottle-water fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">Un Pur Jus Ananas Curcuma offert</h6>
                                    <span class="small text-muted">Débloqué à 7 points</span>
                                </div>
                            </div>
                            <?php echo ($points >= 7) ? '<span class="badge bg-success text-white rounded-pill"><i class="fa-solid fa-circle-check"></i> Acquis</span>' : '<span class="badge bg-secondary text-white-50 rounded-pill">Verrouillé</span>'; ?>
                        </div>

                        <div class="perk-item d-flex align-items-center justify-content-between shadow-sm <?php echo ($points >= 10) ? 'unlocked bg-light-subtle' : ''; ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 bg-info-subtle text-info rounded-3"><i class="fa-solid fa-jar fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">Le Grand Pot de Miel Blanc d'Oku</h6>
                                    <span class="small text-muted">Débloqué à 10 points</span>
                                </div>
                            </div>
                            <?php echo ($points >= 10) ? '<span class="badge bg-success text-white rounded-pill"><i class="fa-solid fa-circle-check"></i> Acquis</span>' : '<span class="badge bg-secondary text-white-50 rounded-pill">Verrouillé</span>'; ?>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </main>

    <footer class="border-top">
        <div class="container text-center py-2">
            <p class="mb-1 fw-bold text-white">JEMEA PRODUCTS</p>
            <p class="small mb-0 text-white-50">&copy; 2026 Jemea. Tous droits réservés. Produit local, impact global.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>