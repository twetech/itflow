<?php
// src/Model/Support.php

namespace Twetech\Nestogy\Model;

use PDO;
class Support {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getTickets($client_id = false) {
        if ($client_id) {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM tickets
                LEFT JOIN clients ON tickets.ticket_client_id = clients.client_id
                LEFT JOIN users ON tickets.ticket_assigned_to = users.user_id
                LEFT JOIN ticket_statuses ON tickets.ticket_status = ticket_statuses.ticket_status_id
                LEFT JOIN contacts ON tickets.ticket_contact_id = contacts.contact_id
                WHERE ticket_client_id = :client_id
            ');
            $stmt->execute(['client_id' => $client_id]);
            return $stmt->fetchAll();
        } else {
            $stmt = $this->pdo->prepare('SELECT * FROM tickets');
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }


    public function getSupportHeaderNumbers($client_id = false) {
        $open_tickets = $this->getTotalTicketsOpen($client_id);
        $closed_tickets = $this->getTotalTicketsClosed($client_id);
        $unassigned_tickets = $this->getTotalTicketsUnassigned($client_id);
        $scheduled_tickets = $this->getTotalRecurringTickets($client_id);

        return [
            'open_tickets' => $open_tickets['total_tickets_open'],
            'closed_tickets' => $closed_tickets['total_tickets_closed'],
            'unassigned_tickets' => $unassigned_tickets['total_tickets_unassigned'],
            'scheduled_tickets' => $scheduled_tickets['total_scheduled_tickets']
        ];
    }

    public function getClientHeader($client_id) {
        return [
            'open_tickets' => $this->getTotalTicketsOpen($client_id),
            'closed_tickets' => $this->getTotalTicketsClosed($client_id)
        ];
    }

    private function getTotalTicketsOpen($client_id = false) {
        if ($client_id) {
            $stmt = $this->pdo->prepare('SELECT COUNT(ticket_id) AS total_tickets_open FROM tickets WHERE ticket_status = :status AND ticket_client_id = :client_id');
            $stmt->execute(['status' => 1, 'client_id' => $client_id]);
            return $stmt->fetch();
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(ticket_id) AS total_tickets_open FROM tickets WHERE ticket_status = :status');
            $stmt->execute(['status' => 1]);
            return $stmt->fetch();
        }
    }
    private function getTotalTicketsClosed($client_id = false) {
        if ($client_id) {
            $stmt = $this->pdo->prepare('SELECT COUNT(ticket_id) AS total_tickets_closed FROM tickets WHERE ticket_status = :status AND ticket_client_id = :client_id');
            $stmt->execute(['status' => 5, 'client_id' => $client_id]);
            return $stmt->fetch();
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(ticket_id) AS total_tickets_closed FROM tickets WHERE ticket_status = :status');
            $stmt->execute(['status' => 5]);
            return $stmt->fetch();
        }
    }
    private function getTotalTicketsUnassigned($client_id = false) {
        if ($client_id) {
            $stmt = $this->pdo->prepare('SELECT COUNT(ticket_id) AS total_tickets_unassigned FROM tickets WHERE ticket_status = :status AND ticket_client_id = :client_id');
            $stmt->execute(['status' => 1, 'client_id' => $client_id]);
            return $stmt->fetch();
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(ticket_id) AS total_tickets_unassigned FROM tickets WHERE ticket_status = :status');
            $stmt->execute(['status' => 1]);
            return $stmt->fetch();
        }
    }

    private function getTotalRecurringTickets($client_id = false) {
        if ($client_id) {
            $stmt = $this->pdo->prepare('SELECT COUNT(scheduled_ticket_id) AS total_scheduled_tickets FROM scheduled_tickets WHERE scheduled_ticket_client_id = :client_id');
            $stmt->execute(['client_id' => $client_id]);
            return $stmt->fetch();
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(scheduled_ticket_id) AS total_scheduled_tickets FROM scheduled_tickets');
            $stmt->execute();
            return $stmt->fetch();
        }
    }
}