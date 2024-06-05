<?php
// src/Model/Client.php

namespace Twetech\Nestogy\Model;

use PDO;
use Twetech\Nestogy\Model\Support;
use Twetech\Nestogy\Model\Contact;
use Twetech\Nestogy\Model\Location;
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
    public function getClientBalance($client_id) {
        $stmt = $this->pdo->prepare("SELECT SUM(invoice_amount) - COALESCE(SUM(payment_amount), 0) AS balance FROM invoices LEFT JOIN payments ON payments.payment_invoice_id = invoices.invoice_id WHERE invoice_client_id = :client_id");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetch();
    }
    public function getClientPaidAmount($client_id) {
        // Get the total amount paid by the client during the year
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(payment_amount), 0) AS amount_paid
                FROM payments
            LEFT JOIN invoices
                ON payments.payment_invoice_id = invoices.invoice_id
            WHERE invoice_client_id = :client_id
                AND payment_date >= DATE_FORMAT(NOW(), '%Y-01-01')
        ");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetch();
    }
    public function getClientHeader($client_id) {
        $client_id = intval($client_id);

        $support = new Support($this->pdo);
        $client_header_support = $support->getClientHeader($client_id);

        $contact = new Contact($this->pdo);
        $client_header_contact = $contact->getPrimaryContact($client_id);

        $location = new Location($this->pdo);
        $client_header_location = $location->getPrimaryLocation($client_id);

        $stmt = $this->pdo->prepare(
            "SELECT * FROM clients WHERE client_id = :client_id"
        );
        $stmt->execute(['client_id' => $client_id]);

        $return = ['client_header' => $stmt->fetch()];
        $return['client_header']['client_balance'] = $this->getClientBalance($client_id)['balance'];
        $return['client_header']['client_payments'] = $this->getClientPaidAmount($client_id)['amount_paid'];
        $return['client_header']['client_open_tickets'] = $client_header_support['open_tickets']['total_tickets_open'];
        $return['client_header']['client_closed_tickets'] = $client_header_support['closed_tickets']['total_tickets_closed'];
        $return['client_header']['client_primary_contact'] = $client_header_contact;
        $return['client_header']['client_primary_location'] = $client_header_location;
        

        return $return;

    }
    public function getClientLocations($client_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM locations WHERE location_client_id = :client_id");
        $stmt->execute(['client_id' => $client_id]);
        return $stmt->fetchAll();
    }
    
}
