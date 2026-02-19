<?php

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Modèle Menu
 * Gère l'accès aux données liées aux menus
 */
class Menu
{
    private PDO $db;

    /**
     * Connexion à la base de données via la classe Database
     */
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Récupère l'ensemble des menus disponibles
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM menus";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les menus correspondant à un thème donné
     * (mariage, noel, paques, entreprise, boisson)
     */
    public function getByTheme(string $theme): array
    {
        $sql = "SELECT * FROM menus WHERE theme = :theme";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['theme' => $theme]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les menus compatibles avec un nombre minimum de personnes
     */
    public function getByMinPersons(int $minPersons): array
    {
        $sql = "
        SELECT *
        FROM menus
        WHERE nb_personnes_min <= :minPersons
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['minPersons' => $minPersons]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les menus dont le prix par personne
     * est inférieur ou égal au prix maximum fourni
     */
    public function getByMaxPrice(float $maxPrice): array
    {
        $sql = "
        SELECT *
        FROM menus
        WHERE prix_par_personne <= :maxPrice
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['maxPrice' => $maxPrice]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Récupère les menus selon le régime alimentaire
     * (classique, vegetarien, vegan)
     */
    public function getByRegime(string $regime): array
    {
        $sql = "
        SELECT *
        FROM menus
        WHERE regime = :regime
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['regime' => $regime]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les menus dont le prix est compris
     * entre un minimum et un maximum
     */
    public function getByPriceRange(float $min, float $max): array
    {
        $sql = "
            SELECT *
            FROM menus
            WHERE prix_par_personne BETWEEN :min AND :max
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'min' => $min,
            'max' => $max
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un menu par son identifiant
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM menus WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $menu = $stmt->fetch(PDO::FETCH_ASSOC);

        return $menu ?: null;
    }

    /**
     * Récupère les plats associés à un menu via la table pivot menu_plat
     */
    public function getPlatsByMenu(int $menu_id): array
    {
        $sql = "
        SELECT p.*
        FROM plats p
        INNER JOIN menu_plat mp ON p.id = mp.plat_id
        WHERE mp.menu_id = :menu_id
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['menu_id' => $menu_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
