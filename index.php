<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jemea Fidélité - Épargnez à chaque achat</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Feuille de style personnalisée -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR AVEC TON LOGO EN IMAGE ET MENU ÉPURÉ -->
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
                    <li class="nav-item"><a class="nav-link" href="card.php">Voir ma carte</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-5">
                    <span class="jf-kicker"><i class="fa-solid fa-seedling me-2"></i>Programme Épargne JEMEA</span>
                    <h1 class="hero-title mb-4">
                        Chaque achat JEMEA <span>vous fait épargner</span>, pour de vraies remises.
                    </h1>
                    <p class="lead text-muted mb-5" style="font-size: 1.1rem; max-width: 600px;">
                        À chaque achat, une partie du montant est automatiquement créditée sur votre carte
                        fidélité digitale — et se transforme en remise sur vos prochains achats.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="inscription.php" class="btn btn-jemea py-3 px-4">Créer ma Carte Fidélité</a>
                        <a href="card.php" class="btn btn-outline-jemea py-3 px-4">Voir ma carte</a>
                    </div>
                </div>
                <div class="col-lg-7 text-center order-lg-1 order-2">
                    <img src="assets/image/header.png" alt="Programme fidélité Jemea" class="img-fluid" style="max-height: 680px; width: 100%; filter: drop-shadow(0 15px 20px rgba(0,0,0,0.1));">
                </div>
            </div>
        </div>
    </header>

    <!-- PETITES CARTES VALEURS -->
    <section class="py-5">
        <div class="container py-3">
            <div class="row g-3 justify-content-center">
                <div class="col-lg-3 col-6">
                    <div class="jf-value-card">
                        <i class="fa-solid fa-shield-halved fa-lg mb-3"></i>
                        <span>Fiabilité</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="jf-value-card">
                        <i class="fa-solid fa-eye fa-lg mb-3"></i>
                        <span>Transparence</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="jf-value-card">
                        <i class="fa-solid fa-bolt fa-lg mb-3"></i>
                        <span>Simplicité</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="jf-value-card">
                        <i class="fa-solid fa-gift fa-lg mb-3"></i>
                        <span>Récompense réelle</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VOTRE CARTE FIDÉLITÉ -->
    <section class="py-5 bg-light-subtle">
        <div class="container py-4">
            <div class="row align-items-center gy-5">
                <div class="col-lg-7 text-center order-lg-1 order-2">
                    <img src="assets/image/card.png" alt="Carte fidélité Jemea" class="img-fluid" style="max-height: 680px; width: 100%; filter: drop-shadow(0 20px 30px rgba(0,40,0,0.15));">
                </div>
                <div class="col-lg-5 order-lg-2 order-1">
                    <h2 class="fw-bold mb-3">Votre carte, votre épargne, toujours avec vous</h2>
                    <p class="text-muted mb-4" style="font-size: 1.05rem;">
                        Une carte fidélité 100% digitale : votre solde, votre palier et votre historique
                        sont toujours accessibles, à tout moment, sur votre téléphone.
                    </p>
                    <a href="card.php" class="btn btn-jemea py-3 px-4">Consulter ma carte</a>
                </div>
            </div>
        </div>
    </section>

    <!-- COMMENT FONCTIONNE L'ÉPARGNE -->
    <section class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Comment fonctionne votre épargne</h2>
                <p class="text-muted">Un système simple, basé sur vos achats réels</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="jf-step-card">
                        <div class="jf-step-num">1</div>
                        <h6 class="fw-bold mb-2">Vous achetez</h6>
                        <p class="small text-muted mb-0">Chez JEMEA, en boutique. Le montant de votre achat détermine ce que vous allez épargner.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="jf-step-card">
                        <div class="jf-step-num">2</div>
                        <h6 class="fw-bold mb-2">Vous épargnez</h6>
                        <p class="small text-muted mb-0">3% Bronze, 5% Argent (dès 100 000 FCFA cumulés), 7% Or (dès 300 000 FCFA) — automatiquement crédité.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="jf-step-card">
                        <div class="jf-step-num">3</div>
                        <h6 class="fw-bold mb-2">Vous pouvez déposer</h6>
                        <p class="small text-muted mb-0">Alimentez aussi votre solde via Orange Money / MTN MoMo, avec un bonus allant jusqu'à 8%.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="jf-step-card">
                        <div class="jf-step-num">4</div>
                        <h6 class="fw-bold mb-2">Vous utilisez</h6>
                        <p class="small text-muted mb-0">Votre solde devient une remise sur un futur achat, jusqu'à 50% du montant — valable 12 mois.</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="inscription.php" class="btn btn-jemea py-3 px-4">Rejoindre le programme</a>
            </div>
        </div>
    </section>

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

 <!----CSS----->
    <style>
        .jf-kicker{ display:inline-block; font-size:12px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color: rgb(0,127,22); background: rgba(0,127,22,0.08); padding:6px 14px; border-radius:99px; margin-bottom:18px; }

        .jf-value-card{ background: rgb(0,60,10); color:#fff; border-radius:16px; padding:28px 16px; text-align:center; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; transition: transform .2s ease; }
        .jf-value-card:hover{ transform: translateY(-4px); }
        .jf-value-card i{ color: #9BE38A; }
        .jf-value-card span{ font-weight:600; font-size:0.95rem; }

        .jf-step-card{ background:#fff; border:1px solid #eef1ea; border-radius:16px; padding:28px 22px; height:100%; box-shadow: 0 6px 18px rgba(0,40,0,0.04); }
        .jf-step-num{ width:36px; height:36px; border-radius:50%; background: rgb(0,127,22); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; margin-bottom:16px; }
    </style>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>