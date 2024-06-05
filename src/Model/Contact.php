<?php
// src/Moel/Contact.php

namespace Twetech\Nestogy\Model;

use PDO;
class Contact
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getContacts($client_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contacts WHERE contact_client_id = :client_id");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetchAll();
    }

    public function getContact($contact_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contacts WHERE contact_id = :contact_id");
        $stmt->execute(['contact_id' => $contact_id]);
        return $stmt->fetch();
    }

    public function getPrimaryContact($client_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contacts WHERE contact_client_id = :client_id AND contact_primary = 1");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetch();
    }

}