<?php
// Chargement des dépendances nécessaires
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Models/Menu.php';
require_once __DIR__ . '/../src/Controllers/MenuController.php';

use App\Controllers\MenuController;

// Initialisation du contrôleur
$controller = new MenuController();

/**
 * Routes AJAX
 * Permettent le filtrage dynamique sans rechargement de page
 */

// Filtre par thème
if (isset($_GET['action']) && $_GET['action'] === 'filter-theme') {
    $controller->filterByTheme();
    exit;
}

// Filtre par nombre minimum de personnes
if (isset($_GET['action']) && $_GET['action'] === 'filter-min-persons') {
    $controller->filterByMinPersons();
    exit;
}

// Filtre par prix maximum
if (isset($_GET['action']) && $_GET['action'] === 'filter-max-price') {
    $controller->filterByMaxPrice();
    exit;
}

// Filtre par régime
if (isset($_GET['action']) && $_GET['action'] === 'filter-regime') {
    $controller->filterByRegime();
    exit;
}

// Filtre par fourchette de prix
if (isset($_GET['action']) && $_GET['action'] === 'filter-price-range') {
    $controller->filterByPriceRange();
    exit;
}

//Récupération des menus pour l'affichage initial
$menus = $controller->index();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- ================= VUE GLOBALE DES MENUS ================= -->

    <header>
        <h1>Nos menus</h1>
        <a href="index.php">← Retour à l’accueil</a>
    </header>

    <!-- Filtre par thème (exigence ECF) -->
    <section>

        <label for="theme">Filtrer par thème :</label>
        <select id="theme">
            <option value="">-- Tous les thèmes --</option>
            <option value="mariage">Mariage</option>
            <option value="noel">Noël</option>
            <option value="paques">Pâques</option>
            <option value="entreprise">Entreprise</option>
            <option value="boisson">Boisson</option>
        </select>

        <br><br>

        <!-- Filtre par nombre minimum de personnes (exigence ECF) -->
        <label for="minPersons">Nombre minimum de personnes :</label>
        <input type="number" id="minPersons" min="1" value="1">

        <br><br>

        <!-- Filtre par prix maximum de personnes (exigence ECF) -->
        <label for="maxPrice">Prix maximum (€) :</label>
        <input type="number" id="maxPrice" min="0" step="1">

        <br><br>

        <!-- Filtre par fourchette de prix (exigence ECF) -->
        <label>Fourchette de prix (€) :</label>
        <input type="number" id="minPrice" min="0" placeholder="Min">
        <input type="number" id="maxPriceRange" min="0" placeholder="Max">

        <br><br>

        <!-- Filtre par régime (exigence ECF) -->
        <label for="regime">Régime :</label>
        <select id="regime">
            <option value="">-- Tous les régimes --</option>
            <option value="classique">Classique</option>
            <option value="vegetarien">Végétarien</option>
            <option value="vegan">Vegan</option>
        </select>

    </section>

    <hr>

    <!-- Conteneur des menus (mis à jour dynamiquement en JavaScript) -->
    <div id="menus-container">
        <?php foreach ($menus as $menu): ?>
            <article class="menu-card">

                <!-- Image principale du menu -->
                <img
                    src="images/menus/menu_<?= (int)$menu['id'] ?>.jpg"
                    alt="Menu <?= htmlspecialchars($menu['nom']) ?>"
                    class="menu-image">

                <!-- Informations principales du menu -->
                <div class="menu-content">
                    <h2><?= htmlspecialchars($menu['nom']) ?></h2>

                    <p><?= htmlspecialchars($menu['description']) ?></p>

                    <p>
                        <strong><?= number_format($menu['prix_par_personne'], 2) ?> €</strong> / personne
                    </p>

                    <p>
                        Minimum <?= (int)$menu['nb_personnes_min'] ?> personnes
                    </p>

                    <!-- Accès à la vue détaillée -->
                    <a href="menu.php?id=<?= (int)$menu['id'] ?>" class="btn-details">
                        Voir le détail
                    </a>
                </div>

            </article>
        <?php endforeach; ?>
    </div>

    <!-- Script JS pour le filtrage dynamique -->
    <script src="js/filters.js"></script>

</body>

</html>