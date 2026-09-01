<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jemea Loyalty - Créer ma Carte</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Feuille de style personnalisée -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- NAVBAR -->
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

    <!-- ZONE DE L'INTERFACE FORMULAIRE -->
    <main class="inscription-container">
        <div class="form-shadow-wrapper">
            <div class="form-main-card">
                
                <!-- Logo de la marque centré dans le formulaire -->
                <div class="text-center mb-4">
                    <img src="assets/image/jemea.jpeg" alt="logo jemea" style="height: 55px; width: auto; object-fit: contain;">
                </div>

                <!-- Petite ligne verte style étoile filante -->
                <div class="laser-line-container">
                    <div class="laser-line"></div>
                </div>

                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Rejoignez le Club</h4>
                    <p class="text-muted small">Activez votre carte numérique en quelques secondes.</p>
                </div>

                <!-- Formulaire connecté à notre futur traitement PHP -->
                <form action="process-register.php" method="POST">
                    
                    <!-- Champ Nom Complet -->
                    <div class="mb-3">
                        <label for="fullname" class="form-label small fw-bold text-secondary">Nom complet</label>
                        <div class="position-relative">
                            <input type="text" class="form-control form-custom-input" id="fullname" name="fullname" placeholder="Ex: Josue Lobe" required>
                        </div>
                    </div>
                    
                    <!-- Champ Téléphone avec indicatif pays du Cameroun -->
                    <div class="mb-4">
                        <label for="phone" class="form-label small fw-bold text-secondary">Numéro de téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom">+237</span>
                            <input type="tel" class="form-control form-custom-input" id="phone" name="phone" placeholder="694992229" pattern="[0-9]{9}" title="Veuillez entrer un numéro à 9 chiffres sans l'indicatif" required>
                        </div>
                        <div class="form-text text-muted small mt-1" style="font-size: 0.75rem;">
                            Indispensable pour identifier votre compte lors de vos passages ou livraisons.
                        </div>
                    </div>

                    <!-- Bouton de soumission stylé -->
                    <button type="submit" class="btn btn-jemea w-100 py-3 rounded-3 mt-2">
                        Créer ma carte fidélité <i class="fa-solid fa-arrow-right ms-2 small"></i>
                    </button>
                </form>

            </div>
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

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>