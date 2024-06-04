<?php
// src/Database.php

namespace Twetech\Nestogy;

use PDO;
use PDOException;

class Database {
    private $pdo;
    private $error;

    public function __construct($config) {
        $host = $config['host'];
        $db = $config['dbname'];
        $user = $config['user'];
        $pass = $config['pass'];
        $charset = $config['charset'];

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
