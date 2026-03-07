<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';

use App\Config\Database;

// ================= SÉCURITÉ =================
// Accès réservé aux employés
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employe') {
    header("Location: ../index.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

/* ================= MISE À JOUR DU STATUT ================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $commande_id = (int) $_POST["commande_id"];
    $nouveau_statut = $_POST["statut"];

    $stmtUpdate = $db->prepare("
        INSERT INTO suivi_commandes (commande_id, statut)
        VALUES (?, ?)
    ");

    $stmtUpdate->execute([$commande_id, $nouveau_statut]);
}

// ================= RÉCUPÉRATION DE TOUTES LES COMMANDES =================
$stmt = $db->query("
    SELECT 
        c.id,
        u.nom,
        u.prenom,
        m.nom AS menu_nom,
        c.nb_personnes,
        c.total,
        c.date_prestation,
        c.created_at
    FROM commandes c
    JOIN users u ON c.user_id = u.id
    JOIN menus m ON c.menu_id = m.id
    ORDER BY c.created_at DESC
");

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Commandes - Espace Employé</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="form-container">

        <h2>Espace Employé – Gestion des commandes</h2>

        <?php if (empty($commandes)): ?>

            <p>Aucune commande trouvée.</p>

        <?php else: ?>

            <?php foreach ($commandes as $commande): ?>

                <?php
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

                    <p><strong>Client :</strong> <?= htmlspecialchars($commande['prenom']) ?> <?= htmlspecialchars($commande['nom']) ?></p>

                    <p><strong>Menu :</strong> <?= htmlspecialchars($commande['menu_nom']) ?></p>

                    <p><strong>Nombre de personnes :</strong> <?= (int)$commande['nb_personnes'] ?></p>

                    <p><strong>Total :</strong> <?= number_format($commande['total'], 2) ?> €</p>

                    <p><strong>Date prestation :</strong> <?= htmlspecialchars($commande['date_prestation']) ?></p>

                    <p><strong>Statut :</strong> <?= htmlspecialchars($statut) ?></p>

                    <form method="POST" style="margin-top:10px;">

                        <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">

                        <select name="statut">

                            <option value="en_attente" <?= $statut === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="acceptee" <?= $statut === 'acceptee' ? 'selected' : '' ?>>Acceptée</option>
                            <option value="en_preparation" <?= $statut === 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                            <option value="en_livraison" <?= $statut === 'en_livraison' ? 'selected' : '' ?>>En livraison</option>
                            <option value="livree" <?= $statut === 'livree' ? 'selected' : '' ?>>Livrée</option>
                            <option value="annulee" <?= $statut === 'annulee' ? 'selected' : '' ?>>Annulée</option>

                        </select>

                        <button type="submit">Mettre à jour</button>

                    </form>

                    <p><strong>Commande passée le :</strong> <?= htmlspecialchars($commande['created_at']) ?></p>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

        <a href="../index.php">← Retour accueil</a>

    </div>

</body>

</html>