<?php
// src/Model/Documentation.php

namespace Twetech\Nestogy\Model;

use PDO;
use Twetech\Nestogy\Model\Support;
use Twetech\Nestogy\Model\Contact;
use Twetech\Nestogy\Model\Location;

class Documentation {
    private $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAssets($client_id = false) {
        $sql = "SELECT * FROM assets";
        if ($client_id) {
            $sql .= " WHERE asset_client_id = :client_id";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($client_id) {
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLicenses($client_id = false) {
        $sql = "SELECT * FROM software";
        if ($client_id) {
            $sql .= " WHERE software_client_id = :client_id";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($client_id) {
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogins($client_id = false) { // This method will return encrypted (not readable) passwords
        $sql = "SELECT * FROM logins";
        if ($client_id) {
            $sql .= " WHERE login_client_id = :client_id";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($client_id) {
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getNetworks($client_id = false) {
        $sql = "SELECT * FROM networks";
        if ($client_id) {
            $sql .= " WHERE network_client_id = :client_id";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($client_id) {
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getServices($client_id = false) {
        $sql = "SELECT * FROM services";
        if ($client_id) {
            $sql .= " WHERE service_client_id = :client_id";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($client_id) {
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getVendors($client_id = false) {
        $sql = "SELECT * FROM vendors";
        if ($client_id) {
            $sql .= " WHERE vendor_client_id = :client_id";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($client_id) {
            $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}