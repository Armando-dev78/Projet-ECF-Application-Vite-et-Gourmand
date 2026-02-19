<?php
session_start();
require_once __DIR__ . '/../config/Database.php';

use App\Config\Database;

// ================= SÉCURITÉ =================
// Accès réservé aux utilisateurs authentifiés
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_SESSION['user']['id'];

// ================= RÉCUPÉRATION DES COMMANDES =================
// On récupère les commandes de l'utilisateur connecté
$stmt = $db->prepare("
    SELECT 
        c.id,
        m.nom AS menu_nom,
        c.nb_personnes,
        c.total,
        c.date_prestation,
        c.created_at
    FROM commandes c
    JOIN menus m ON c.menu_id = m.id
    WHERE c.user_id = ?
    ORDER BY c.created_at DESC
");

$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mes commandes</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="form-container">

        <h2>Mes commandes</h2>

        <?php if (empty($commandes)): ?>
            <p>Aucune commande trouvée.</p>
        <?php else: ?>

            <?php foreach ($commandes as $commande): ?>

                <?php
                // ================= RÉCUPÉRATION DU STATUT ACTUEL =================
                $stmtStatut = $db->prepare("
                SELECT statut
                FROM suivi_commandes
                WHERE commande_id = ?
                ORDER BY date_statut DESC
                LIMIT 1
            ");
                $stmtStatut->execute([$commande['id']]);
                $statut = $stmtStatut->fetchColumn();
                ?>

                <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px;">

                    <p><strong>Menu :</strong> <?= htmlspecialchars($commande['menu_nom']) ?></p>

                    <p><strong>Nombre de personnes :</strong> <?= (int)$commande['nb_personnes'] ?></p>

                    <p><strong>Total payé :</strong> <?= number_format($commande['total'], 2) ?> €</p>

                    <p><strong>Date prestation :</strong> <?= htmlspecialchars($commande['date_prestation']) ?></p>

                    <p><strong>Statut :</strong> <?= htmlspecialchars($statut) ?></p>

                    <p><strong>Commande passée le :</strong> <?= htmlspecialchars($commande['created_at']) ?></p>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

        <a href="index.php">← Retour accueil</a>

    </div>

</body>

</html>