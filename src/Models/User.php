<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Création d'un utilisateur
     */
    public function create(
        string $nom,
        string $prenom,
        string $email,
        string $password,
        string $role = 'utilisateur'
    ): bool {
        $sql = "
            INSERT INTO users (nom, prenom, email, password, role)
            VALUES (:nom, :prenom, :email, :password, :role)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nom'      => $nom,
            'prenom'   => $prenom,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => $role
        ]);
    }

    /**
     * Récupération d'un utilisateur par email
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}