<?php
// admin/index.php
session_start();
require_once '../config/database.php';

$error = "";

// Si l'admin est déjà connecté, on le redirige directement vers son tableau de bord
if (isset($_SESSION['admin_logged'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Vérification de l'administrateur en BDD
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        // Remplacement temporaire si tu n'as pas encore hashé tes mots de passe : password_verify($password, $admin['password'])
        // Si tu as mis le mot de passe en texte brut pour tes tests : $password === $admin['password']
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Identifiants incorrects.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Staff - Jemea Loyalty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0b130c; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .admin-login-card { background: #ffffff; border-radius: 16px; width: 100%; max-width: 400px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .btn-admin { background-color: #004d0d; color: white; border: none; }
        .btn-admin:hover { background-color: #003308; color: white; }
    </style>
</head>
<body>

    <div class="admin-login-card">
        <div class="text-center mb-4">
            <img src="../assets/images/logo.png" alt="Logo Jemea" style="height: 45px; object-fit: contain;">
            <h5 class="fw-bold mt-3 mb-1">Espace Staff & Caisse</h5>
            <p class="text-muted small">Connectez-vous pour valider les points clients</p>
        </div>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger small py-2 text-center" role="alert">
                <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label small fw-bold text-secondary">Identifiant</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user-tie"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="admin_jemea" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label small fw-bold text-secondary">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-admin w-100 py-2.5 rounded-3 fw-bold">
                Se connecter <i class="fa-solid fa-arrow-right-to-bracket ms-1 small"></i>
            </button>
        </form>
    </div>

</body>
</html>