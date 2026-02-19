<?php

namespace App\Controllers;

use App\Models\Menu;

/**
 * Contrôleur Menu
 * Fait le lien entre la vue et le modèle
 */
class MenuController
{
    private Menu $menuModel;

    /**
     * Initialisation du modèle Menu
     */
    public function __construct()
    {
        $this->menuModel = new Menu();
    }

    /**
     * Retourne tous les menus (vue globale)
     */
    public function index(): array
    {
        return $this->menuModel->getAll();
    }

    /**
     * Filtre les menus par thème (AJAX)
     * Si aucun thème n'est sélectionné, retourne tous les menus
     */
    public function filterByTheme(): void
    {
        $theme = $_GET['theme'] ?? '';

        header('Content-Type: application/json');

        // Si aucun thème sélectionné → tous les menus
        if ($theme === '') {
            echo json_encode($this->menuModel->getAll());
            return;
        }

        // Sinon → menus filtrés
        echo json_encode($this->menuModel->getByTheme($theme));
    }


    /**
     * Filtre les menus selon le nombre minimum de personnes
     */
    public function filterByMinPersons(): void
    {
        if (!isset($_GET['minPersons'])) {
            echo json_encode([]);
            return;
        }

        $minPersons = (int) $_GET['minPersons'];
        $menus = $this->menuModel->getByMinPersons($minPersons);

        header('Content-Type: application/json');
        echo json_encode($menus);
    }

    /**
     * Filtre les menus par prix maximum (AJAX)
     */
    public function filterByMaxPrice(): void
    {
        if (!isset($_GET['maxPrice']) || $_GET['maxPrice'] === '') {
            echo json_encode([]);
            return;
        }

        $maxPrice = (float) $_GET['maxPrice'];

        header('Content-Type: application/json');
        echo json_encode($this->menuModel->getByMaxPrice($maxPrice));
    }

    /**
     * Filtre les menus selon le régime alimentaire
     * (classique, vegetarien, vegan)
     * Appelé en AJAX depuis la vue
     */
    public function filterByRegime(): void
    {
        $regime = $_GET['regime'] ?? '';

        header('Content-Type: application/json');

        // Si aucun régime sélectionné → on renvoie tous les menus
        if ($regime === '') {
            echo json_encode($this->menuModel->getAll());
            return;
        }

        // Sinon → menus filtrés par régime
        echo json_encode($this->menuModel->getByRegime($regime));
    }

    /**
     * Filtre les menus selon une fourchette de prix
     * (prix minimum et prix maximum)
     */
    public function filterByPriceRange(): void
    {
        if (!isset($_GET['minPrice'], $_GET['maxPrice'])) {
            echo json_encode([]);
            return;
        }

        $min = (float) $_GET['minPrice'];
        $max = (float) $_GET['maxPrice'];

        header('Content-Type: application/json');
        echo json_encode($this->menuModel->getByPriceRange($min, $max));
    }

    /**
     * Retourne un menu + ses plats associés
     */
    public function show(int $id): ?array
    {
        $menu = $this->menuModel->getById($id);

        if (!$menu) {
            return null;
        }

        $menu['plats'] = $this->menuModel->getPlatsByMenu($id);

        return $menu;
    }
}
