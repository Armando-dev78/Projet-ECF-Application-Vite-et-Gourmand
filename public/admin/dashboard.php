<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';

use App\Config\Database;

// accès admin seulement
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Commandes par mois
$stmt = $db->query("
SELECT DATE_FORMAT(created_at,'%Y-%m') as mois,
COUNT(*) as total
FROM commandes
GROUP BY mois
ORDER BY mois
");

$stats_commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* nombre total de commandes */
$totalCommandes = $db->query("
    SELECT COUNT(*) FROM commandes
")->fetchColumn();

/* commandes en attente */
$commandesAttente = $db->query("
    SELECT COUNT(*)
    FROM suivi_commandes
    WHERE statut = 'en_attente'
")->fetchColumn();

/* commandes livrées */
$commandesLivrees = $db->query("
    SELECT COUNT(*)
    FROM suivi_commandes
    WHERE statut = 'livree'
")->fetchColumn();

/* chiffre d'affaires */
$chiffreAffaires = $db->query("
    SELECT SUM(total) FROM commandes
")->fetchColumn();

/* chiffre d'affaires par mois */

$stmt = $db->query("
SELECT DATE_FORMAT(created_at,'%Y-%m') as mois,
SUM(total) as chiffre
FROM commandes
GROUP BY mois
ORDER BY mois
");

$stats_ca = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* menu le plus commandé */

$stmt = $db->query("
SELECT m.nom, COUNT(*) as total
FROM commandes c
JOIN menus m ON c.menu_id = m.id
GROUP BY c.menu_id
ORDER BY total DESC
LIMIT 1
");

$menu_populaire = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="form-container">

        <h2>Tableau de bord administrateur</h2>

        <p><strong>Total commandes :</strong> <?= $totalCommandes ?></p>

        <p><strong>Commandes en attente :</strong> <?= $commandesAttente ?></p>

        <p><strong>Commandes livrées :</strong> <?= $commandesLivrees ?></p>

        <p><strong>Chiffre d'affaires :</strong> <?= number_format($chiffreAffaires, 2) ?> €</p>

        <h3>Commandes par mois</h3>

        <ul>
            <?php foreach ($stats_commandes as $s): ?>
                <li>
                    <?= htmlspecialchars($s['mois']) ?> : <?= $s['total'] ?> commandes
                </li>
            <?php endforeach; ?>
        </ul>

        <h3>Chiffre d'affaires par mois</h3>

        <ul>
            <?php foreach ($stats_ca as $s): ?>
                <li>
                    <?= htmlspecialchars($s['mois']) ?> : <?= number_format($s['chiffre'], 2) ?> €
                </li>
            <?php endforeach; ?>
        </ul>


        <h3>Menu le plus commandé</h3>

        <p>
            <?= htmlspecialchars($menu_populaire['nom']) ?> (<?= $menu_populaire['total'] ?> commandes)
        </p>

        <a href="../index.php">← Retour accueil</a>

    </div>

</body>

</html>