<?php
// src/Model/Location.php

namespace Twetech\Nestogy\Model;

use PDO;

class Location {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getLocations($client_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM locations WHERE location_client_id = $client_id");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLocation($location_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM locations WHERE location_id = :location_id");
        $stmt->execute(['location_id' => $location_id]);
        return $stmt->fetch();
    }

    public function getPrimaryLocation($client_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM locations WHERE location_client_id = :client_id AND location_primary = 1");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetch();
    }
    
}