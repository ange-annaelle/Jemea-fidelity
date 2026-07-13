<!DOCTYPE html>
<html lang="fr">
<head>
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