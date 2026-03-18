<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Models/Menu.php';
require_once __DIR__ . '/../src/Controllers/MenuController.php';

use App\Controllers\MenuController;

// Vérification de l'identifiant du menu
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Menu introuvable');
}

$controller = new MenuController();
$menu = $controller->show((int) $_GET['id']);

if (!$menu) {
    die('Menu introuvable');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($menu['nom']) ?> - Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="form-container">

        <h2><?= htmlspecialchars($menu['nom']) ?></h2>

        <p><strong>Description :</strong><br>
            <?= htmlspecialchars($menu['description']) ?>
        </p>

        <p><strong>Thème :</strong> <?= htmlspecialchars($menu['theme']) ?></p>

        <p><strong>Régime :</strong> <?= htmlspecialchars($menu['regime']) ?></p>

        <p><strong>Prix :</strong> <?= number_format($menu['prix_par_personne'], 2) ?> € / personne</p>

        <p><strong>Nombre minimum :</strong> <?= (int) $menu['nb_personnes_min'] ?> personnes</p>

        <p><strong>Stock disponible :</strong> <?= (int) $menu['stock_disponible'] ?></p>

        <br>

        <hr>

        <h3>Plats inclus :</h3>

        <?php if (!empty($menu['plats'])): ?>
            <ul>
                <?php foreach ($menu['plats'] as $plat): ?>
                    <li>
                        <?= htmlspecialchars($plat['nom']) ?>
                        (<?= htmlspecialchars($plat['type']) ?>)
                        - <?= number_format($plat['prix'], 2) ?> €
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun plat associé.</p>
        <?php endif; ?>

        <?php if ($menu['stock_disponible'] > 0): ?>
            <a href="commande.php?menu_id=<?= $menu['id'] ?>" class="btn-menus">
                Commander ce menu
            </a>
        <?php else: ?>
            <p style="color:red; font-weight:bold;">
                Menu actuellement indisponible
            </p>
        <?php endif; ?>

    </div>

</body>

</html>