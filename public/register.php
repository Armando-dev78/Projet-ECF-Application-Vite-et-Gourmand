<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../config/Database.php';

use App\Config\Database;

$database = new Database();
$db = $database->getConnection();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("Requête invalide.");
    }

    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $gsm = trim($_POST["gsm"]);
    $email = trim($_POST["email"]);
    $adresse = trim($_POST["adresse"]);
    $password = $_POST["password"];

    // Validation mot de passe ECF
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W]).{10,}$/', $password)) {
        $errors[] = "Le mot de passe doit contenir 10 caractères minimum, une majuscule, une minuscule, un chiffre et un caractère spécial.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }

    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (nom, prenom, gsm, email, adresse, password, role) 
                        VALUES (?, ?, ?, ?, ?, ?, 'utilisateur')");

        try {
            $stmt->execute([$nom, $prenom, $gsm, $email, $adresse, $hashedPassword]);
            $_SESSION["success"] = "Compte créé avec succès.";
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Cet email est déjà utilisé.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription - Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="form-container">

        <h2>Création de compte</h2>

        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form" autocomplete="off">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <input type="text" name="nom" placeholder="Nom" required>

            <input type="text" name="prenom" placeholder="Prénom" required>

            <input type="text" name="gsm" placeholder="GSM" required>

            <input type="email" name="email" placeholder="Email" required autocomplete="off">

            <textarea name="adresse" placeholder="Adresse" required></textarea>

            <div style="position:relative;">
                <input type="password" name="password" id="password" placeholder="Mot de passe sécurisé" required autocomplete="new-password">
                <span onclick="togglePassword()" style="position:absolute; right:15px; top:12px; cursor:pointer;">👁</span>
            </div>

            <button type="submit">S'inscrire</button>

        </form>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>

</body>

</html>