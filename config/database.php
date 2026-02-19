<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private string $host = "localhost";
    private string $dbName = "vite_gourmand";
    private string $username = "root";
    private string $password = "";
    private ?PDO $connection = null;

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbName};charset=utf8",
                    $this->username,
                    $this->password
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données");
            }
        }

        return $this->connection;
    }
}