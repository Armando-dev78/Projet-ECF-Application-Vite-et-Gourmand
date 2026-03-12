<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/../config/Database.php';

use App\Config\Database;

$database = new Database();
$db = $database->getConnection();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user["password"])) {
        $errors[] = "Email ou mot de passe incorrect.";
    } else {
        $_SESSION["user"] = [
            "id" => $user["id"],
            "nom" => $user["nom"],
            "prenom" => $user["prenom"],
            "email" => $user["email"],
            "role" => $user["role"]
        ];

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion - Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="form-container">
        <h2>Connexion</h2>

        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>

</body>

</html>