<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jemea Products - Le Goût Pur de la Nature</title>
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
                <div class="col-lg-7">
                    <h1 class="hero-title mb-4">
                        Achetez nos produits naturels camerounais <span>et cumulez des tampons</span> pour recevoir des produits offerts .
                    </h1>
                    <p class="lead text-muted mb-5" style="font-size: 1.1rem; max-width: 600px;">
                        Découvrez une sélection d'épices d'exception, de purs jus de fruits frais et de miels du terroir, rigoureusement cultivés et transformés au Cameroun.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <!-- Redirection externe vers le vrai site marchand -->
                        <a href="https://jemeaproducts.com" class="btn btn-jemea py-3 px-4" target="_blank">Découvrir la boutique</a>
                        <a href="inscription.php" class="btn btn-outline-jemea py-3 px-4">Créer ma Carte Fidélité</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-block">
                    <div class="p-4 rounded-5" style="background: rgba(0, 127, 22, 0.04); border: 2px dashed rgba(0, 127, 22, 0.1);">
                        <img src="assets/image/jemworld.jpg" alt="Jemea Hero" class="img-fluid" style="max-height: 380px; filter: drop-shadow(0 15px 20px rgba(0,0,0,0.1));">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- SECTION DES 3 IMAGES PRODUITS -->
    <section id="products" class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Nos Spécialités du Terroir</h2>
                <p class="text-muted">Des produits authentiques, sains et 100% naturels</p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- 1. Pur Jus Ananas Curcuma -->
                <div class="col-lg-4 col-md-6">
                    <div class="card product-card text-center h-100 p-3">
                        <div class="product-img-wrapper d-flex align-items-center justify-content-center" style="height: 280px;">
                            <img src="assets/image/jemjus.png" alt="Pur Jus Ananas Curcuma" class="img-fluid mh-100" style="object-fit: contain;">
                        </div>
                        <div class="card-body">
                            <span class="badge bg-success-subtle text-success mb-2 px-3 py-2 rounded-pill small fw-bold">Jus de Fruits</span>
                            <h5 class="fw-bold mb-2">Pur Jus Ananas Curcuma</h5>
                            <p class="text-muted small">Le mariage parfait entre la douceur de l'ananas et les vertus du curcuma local.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Curcuma Bio -->
                <div class="col-lg-4 col-md-6">
                    <div class="card product-card text-center h-100 p-3">
                        <div class="product-img-wrapper d-flex align-items-center justify-content-center" style="height: 280px;">
                            <img src="assets/image/jemepices.png" alt="Curcuma Bio Jemea" class="img-fluid mh-100" style="object-fit: contain;">
                        </div>
                        <div class="card-body">
                            <span class="badge bg-warning-subtle text-warning-emphasis mb-2 px-3 py-2 rounded-pill small fw-bold">Épices</span>
                            <h5 class="fw-bold mb-2">Curcuma Bio</h5>
                            <p class="text-muted small">Cultivé traditionnellement, idéal pour sublimer vos plats et booster votre système immunitaire.</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Miel Blanc d'Oku -->
                <div class="col-lg-4 col-md-6">
                    <div class="card product-card text-center h-100 p-3">
                        <div class="product-img-wrapper d-flex align-items-center justify-content-center" style="height: 280px;">
                            <img src="assets/image/jemmiel.png" alt="Miel Blanc d'Oku" class="img-fluid mh-100" style="object-fit: contain;">
                        </div>
                        <div class="card-body">
                            <span class="badge bg-info-subtle text-info-emphasis mb-2 px-3 py-2 rounded-pill small fw-bold">Miel</span>
                            <h5 class="fw-bold mb-2">Miel Blanc d'Oku</h5>
                            <p class="text-muted small">Un produit d'exception à Indication Géographique Protégée, à la texture crémeuse unique.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER AUX COULEURS DE JEMEA -->
    <footer class="border-top">
        <div class="container text-center py-2">
            <p class="mb-1 fw-bold text-white">JEMEA PRODUCTS</p>
            <p class="small mb-0 text-white-50">&copy; 2026 Jemea. Tous droits réservés. Produit local, impact global.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>