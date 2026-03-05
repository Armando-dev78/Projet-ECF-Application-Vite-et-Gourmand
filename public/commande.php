<?php
session_start();
require_once __DIR__ . '/../config/Database.php';

use App\Config\Database;

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$user = $_SESSION['user'];
$errors = [];
$success = false;

if (!isset($_GET['menu_id'])) {
    die("Menu non spécifié.");
}

$menu_id = (int) $_GET['menu_id'];

$nb_personnes = 0;
$date_prestation = '';
$heure_livraison = '';
$adresse_livraison = '';
$ville = '';
$distance_km = 0;
$action = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    $nb_personnes = (int) $_POST['nb_personnes'];
    $date_prestation = $_POST['date_prestation'];
    $heure_livraison = $_POST['heure_livraison'];
    $adresse_livraison = trim($_POST['adresse_livraison']);
    $ville = trim($_POST['ville']);
    $distance_km = isset($_POST['distance_km']) ? (float) $_POST['distance_km'] : 0;
}

// Récupération du menu
$stmtMenu = $db->prepare("SELECT * FROM menus WHERE id = ?");
$stmtMenu->execute([$menu_id]);
$menu = $stmtMenu->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    $errors[] = "Menu introuvable.";
} elseif ($menu['stock_disponible'] <= 0) {
    $errors[] = "Ce menu est actuellement indisponible (stock épuisé).";
} elseif ($nb_personnes < $menu['nb_personnes_min']) {
    $errors[] = "Nombre minimum de personnes requis : " . $menu['nb_personnes_min'] . " personnes.";
} else {

    // ================= RÈGLES MÉTIER ECF =================

    // Calcul du prix de base
    $prix_base = $menu['prix_par_personne'] * $nb_personnes;

    // Application réduction 10% si +5 personnes minimum
    $reduction = 0;
    if ($nb_personnes >= ($menu['nb_personnes_min'] + 5)) {
        $reduction = $prix_base * 0.10;
    }

    // ================= CALCUL LIVRAISON =================

    // Règle ECF :
    // 5 € fixes + 0.59 €/km si hors Bordeaux

    $distance_km = isset($_POST['distance_km']) ? (float) $_POST['distance_km'] : 0;

    $livraison = 0;

    if (strtolower($ville) !== 'bordeaux') {
        $livraison = 5 + (0.59 * $distance_km);
    }

    // ================= TOTAL FINAL =================

    $total = $prix_base - $reduction + $livraison;


    // Insertion commande
    if ($action === 'valider') {
        $stmt = $db->prepare("
            INSERT INTO commandes 
            (user_id, menu_id, nb_personnes, date_prestation, heure_livraison, adresse_livraison, ville, total, reduction)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $user['id'],
            $menu_id,
            $nb_personnes,
            $date_prestation,
            $heure_livraison,
            $adresse_livraison,
            $ville,
            $total,
            $reduction
        ]);

        $commande_id = $db->lastInsertId();

        // Insertion premier statut
        $stmtStatut = $db->prepare("
            INSERT INTO suivi_commandes (commande_id, statut)
            VALUES (?, 'en_attente')
        ");
        $stmtStatut->execute([$commande_id]);

        // Décrémenter le stock
        $stmtStock = $db->prepare("
            UPDATE menus 
            SET stock_disponible = stock_disponible - 1
            WHERE id = ?
        ");
        $stmtStock->execute([$menu_id]);

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Commande</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="form-container">

        <h2>Passer une commande</h2>

        <?php if (!empty($errors)): ?>
            <div class="form-errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color:green;">Commande enregistrée avec succès !</p>
        <?php endif; ?>

        <?php if (isset($total) && $action === 'calculer'): ?>
            <div style="background:#f4f4f4;padding:15px;margin-bottom:20px;border-radius:8px;">
                <h3>Détail du prix :</h3>
                <p>Prix de base : <?= number_format($prix_base, 2) ?> €</p>
                <p>Réduction : -<?= number_format($reduction, 2) ?> €</p>
                <p>Livraison : <?= number_format($livraison, 2) ?> €</p>
                <hr>
                <strong>Total : <?= number_format($total, 2) ?> €</strong>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">

            <input type="text" value="<?= htmlspecialchars($user['nom']) ?>" disabled>
            <input type="text" value="<?= htmlspecialchars($user['prenom']) ?>" disabled>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>

            <input type="number" name="nb_personnes" placeholder="Nombre de personnes" required>

            <input type="date" name="date_prestation" required>

            <input type="time" name="heure_livraison" required>

            <textarea name="adresse_livraison" placeholder="Adresse de livraison" required></textarea>

            <input type="text" name="ville" placeholder="Ville" required>

            <input type="number" step="0.1" name="distance_km" placeholder="Distance depuis Bordeaux (km)">

            <button type="submit" name="action" value="calculer">Calculer le prix</button>
            <button type="submit" name="action" value="valider">Valider la commande</button>

        </form>

    </div>

</body>

</html>