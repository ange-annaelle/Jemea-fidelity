<?php
// card.php
require_once 'config/database.php';
require_once 'includes/fidelity-rules.php';

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
    // Purge les crédits expirés (12 mois glissants) avant d'afficher quoi que ce soit
    $savings_balance = refreshCustomerBalance($pdo, $customer['id']);
    $total_purchases = (float)$customer['total_purchases'];
    $tier = getTier($total_purchases);

    $fullname = $customer['fullname'];
    $matricule = "JM-" . str_pad($customer['id'], 4, "0", STR_PAD_LEFT);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
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
                                    <span class="small text-white-50"><i class="fa-solid fa-star text-warning me-1"></i> Palier <?php echo $tier['name']; ?></span>
                                </div>
                                <div class="text-white-50 opacity-50"><i class="fa-solid fa-rotate-y fa-lg"></i></div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1 small text-white-50" style="font-size: 0.75rem;">
                                    <span>Solde épargne</span>
                                    <span><strong><?php echo number_format($savings_balance, 0, ',', ' '); ?></strong> FCFA</span>
                                </div>

                                <?php if ($tier['next_threshold']): ?>
                                    <?php $tierProgress = min(($total_purchases / $tier['next_threshold']) * 100, 100); ?>
                                    <div class="progress" style="height: 8px; border-radius: 6px; background: rgba(255,255,255,0.2);">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $tierProgress; ?>%;"></div>
                                    </div>
                                    <div class="small text-white-50 mt-1" style="font-size: 0.7rem;">
                                        <?php echo number_format($tier['next_threshold'] - $total_purchases, 0, ',', ' '); ?> FCFA d'achats avant le palier suivant
                                    </div>
                                <?php else: ?>
                                    <div class="small text-warning mt-1" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-crown me-1"></i> Palier maximum atteint — taux d'épargne 7%
                                    </div>
                                <?php endif; ?>
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
                    <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    <div class="mb-3 text-start">
                        <label class="small fw-bold text-secondary mb-1">Montant de votre achat (FCFA)</label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Ex: 5000" required>
                    </div>
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
                        <h5 class="fw-bold text-dark mb-3">Votre palier : <?php echo $tier['name']; ?> (<?php echo $tier['rate']; ?>% d'épargne sur chaque achat)</h5>
                        <?php if ($tier['next_threshold']): ?>
                            <?php $tierProgress = min(($total_purchases / $tier['next_threshold']) * 100, 100); ?>
                            <div class="progress mb-2" style="height: 12px; border-radius: 6px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width: <?php echo $tierProgress; ?>%; background-color: var(--jemea-green);"></div>
                            </div>
                            <p class="small text-muted mb-0">
                                Plus que <strong><?php echo number_format($tier['next_threshold'] - $total_purchases, 0, ',', ' '); ?> FCFA</strong>
                                d'achats cumulés avant votre prochain palier.
                            </p>
                        <?php else: ?>
                            <p class="small text-muted mb-0">🎉 Vous êtes au palier maximum, votre taux d'épargne restera à 7% sur tous vos achats.</p>
                        <?php endif; ?>
                    </div>

                    <h6 class="fw-bold text-secondary text-uppercase tracking-wider mb-3" style="font-size: 0.8rem;">Les paliers du programme</h6>
                    <div class="d-flex flex-column gap-3 mb-4">

                        <div class="perk-item d-flex align-items-center justify-content-between shadow-sm <?php echo ($tier['name'] === 'Bronze') ? 'unlocked bg-light-subtle' : ''; ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 bg-warning-subtle text-warning rounded-3"><i class="fa-solid fa-medal fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">Bronze — 3% d'épargne</h6>
                                    <span class="small text-muted">Dès votre premier achat</span>
                                </div>
                            </div>
                            <?php echo ($tier['name'] === 'Bronze') ? '<span class="badge bg-success text-white rounded-pill"><i class="fa-solid fa-circle-check"></i> Actuel</span>' : ''; ?>
                        </div>

                        <div class="perk-item d-flex align-items-center justify-content-between shadow-sm <?php echo ($tier['name'] === 'Argent') ? 'unlocked bg-light-subtle' : ''; ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 bg-secondary-subtle text-secondary rounded-3"><i class="fa-solid fa-medal fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">Argent — 5% d'épargne</h6>
                                    <span class="small text-muted">Dès 100 000 FCFA d'achats cumulés</span>
                                </div>
                            </div>
                            <?php echo ($tier['name'] === 'Argent') ? '<span class="badge bg-success text-white rounded-pill"><i class="fa-solid fa-circle-check"></i> Actuel</span>' : (($total_purchases >= 100000) ? '<span class="badge bg-success text-white rounded-pill"><i class="fa-solid fa-circle-check"></i> Acquis</span>' : '<span class="badge bg-secondary text-white-50 rounded-pill">Verrouillé</span>'); ?>
                        </div>

                        <div class="perk-item d-flex align-items-center justify-content-between shadow-sm <?php echo ($tier['name'] === 'Or') ? 'unlocked bg-light-subtle' : ''; ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 bg-info-subtle text-info rounded-3"><i class="fa-solid fa-crown fa-lg"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">Or — 7% d'épargne</h6>
                                    <span class="small text-muted">Dès 300 000 FCFA d'achats cumulés</span>
                                </div>
                            </div>
                            <?php echo ($tier['name'] === 'Or') ? '<span class="badge bg-success text-white rounded-pill"><i class="fa-solid fa-circle-check"></i> Actuel</span>' : '<span class="badge bg-secondary text-white-50 rounded-pill">Verrouillé</span>'; ?>
                        </div>
                    </div>

                    <h6 class="fw-bold text-secondary text-uppercase tracking-wider mb-3" style="font-size: 0.8rem;">Boostez votre épargne</h6>
                    <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-2 bg-success-subtle text-success rounded-3"><i class="fa-solid fa-mobile-screen-button fa-lg"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">Dépôt volontaire Orange Money / MTN MoMo</h6>
                                <span class="small text-muted">Alimentez votre solde même sans achat</span>
                            </div>
                        </div>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>Moins de 20 000 FCFA déposés : bonus de <strong>3%</strong></li>
                            <li>Entre 20 000 et 50 000 FCFA : bonus de <strong>5%</strong></li>
                            <li>Plus de 50 000 FCFA : bonus de <strong>8%</strong></li>
                        </ul>
                    </div>

                    <div class="bg-white p-4 rounded-4 shadow-sm border">
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;"><i class="fa-solid fa-circle-info me-2 text-muted"></i>Comment utiliser votre solde</h6>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>Votre solde se convertit en <strong>remise</strong> sur un futur achat, jusqu'à <strong>50%</strong> du montant de cet achat</li>
                            <li>Non convertible en espèces, non transférable à un autre client</li>
                            <li>Valable <strong>12 mois</strong> à partir de chaque crédit</li>
                        </ul>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </main>

   <!-- FOOTER -->
    <footer>
        <div class="upper-footer" style="background-color: rgb(0,60,10);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12"><hr style="border-color: rgba(255,255,255,0.15);" /></div>
                </div>
            </div>
            <div class="container">
                <div class="row pt-5 pb-5">
                    <div class="col-md-6 col-xs-12 sm-mb-3">
                        <h3 class="text-white mb-3">À propos</h3>
                        <p class="text-white font-xssss lh-26">
                            Jemea est une marque familiale qui propose des produits frais, directement de la ferme à votre table. Ancrés dans le respect de la terre, la qualité et le savoir-faire, nous cultivons et transformons nos ingrédients avec soin et intention. Chaque produit est récolté et préparé avec attention afin d'offrir fraîcheur, saveur et authenticité, pour une alimentation saine et digne de confiance.
                        </p>
                    </div>
                    <div class="col-md-3 col-xs-12 sm-mb-3">
                        <h3 class="text-white mb-3">Contact</h3>
                        <p class="text-white font-xssss lh-26">
                            +237 694 992 229 <br/>
                            +237 677 090 155 <br/>
                            3 Rue Dorot, Lobe, Bekoko Littoral.
                        </p>
                    </div>
                    <div class="col-md-3 col-xs-12 sm-mb-3">
                        <p class="font-xssss">
                            <strong style="color: #ffffff; font-weight: 600;">Condition de paiement :Momo 677090155 ou OM 694994229</strong>
                        </p>
                        <br>
                        <p>
                            <img src="assets/image/momo.jpg" width="50" alt="Momo" />
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <img src="assets/image/om.png" width="50" alt="OM" />
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lower-footer pb-3 pt-3" style="background-color: rgb(0,40,7); border-top: 1px solid rgba(255,255,255,0.15);">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-sm-start xs-mb-3">
                        <p class="fw-500 font-xssss mb-0" style="color: rgba(255,255,255,0.6);">&copy; Copyright <?php echo date('Y'); ?> Jemea Products, Jojo's Farms. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-sm-end">
                        <ul class="list-inline m-0">
                            <li class="list-inline-item"><a href="https://www.facebook.com/share/1GgEFeg6zd/?mibextid=wwXIfr" target="_blank"><i class="fa-brands fa-facebook text-white"></i></a></li>
                            <li class="list-inline-item"><a href="https://www.instagram.com/jemeaproducts?igsh=cnd3am9maXRzZ29s" target="_blank"><i class="fa-brands fa-instagram text-white"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>