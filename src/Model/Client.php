<?php
// src/Model/Client.php

namespace Twetech\Nestogy\Model;

use PDO;

class Client {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getClients($home = false) {

        if ($home) {
            $stmt = $this->pdo->query(
                "SELECT SQL_CALC_FOUND_ROWS clients.*, contacts.*, locations.*, GROUP_CONCAT(tags.tag_name) AS tag_names
                FROM clients
                LEFT JOIN contacts ON clients.client_id = contacts.contact_client_id AND contact_primary = 1
                LEFT JOIN locations ON clients.client_id = locations.location_client_id AND location_primary = 1
                LEFT JOIN client_tags ON client_tags.client_tag_client_id = clients.client_id
                LEFT JOIN tags ON tags.tag_id = client_tags.client_tag_tag_id
                WHERE clients.client_archived_at IS NULL
                GROUP BY clients.client_id
                ORDER BY clients.client_accessed_at DESC
            ");
            return $stmt->fetchAll();
        } else {
            $stmt = $this->pdo->query("SELECT * FROM clients");
            return $stmt->fetchAll();
        }
    }

    public function getClient($client_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE client_id = :client_id");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetch();
    }
    
}
