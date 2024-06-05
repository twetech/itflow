<?php
// src/Model/Support.php

namespace Twetech\Nestogy\Model;

use PDO;
class Support {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getOpenTickets($client_id = false) {
        if ($client_id) {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM tickets
                LEFT JOIN clients ON tickets.ticket_client_id = clients.client_id
                LEFT JOIN users ON tickets.ticket_assigned_to = users.user_id
                LEFT JOIN ticket_statuses ON tickets.ticket_status = ticket_statuses.ticket_status_id
                LEFT JOIN contacts ON tickets.ticket_contact_id = contacts.contact_id
                WHERE ticket_client_id = :client_id
                AND ticket_status != 5
                ORDER BY ticket_created_at DESC
            ');
            $stmt->execute(['client_id' => $client_id]);
            $tickets = $stmt->fetchAll();
            foreach ($tickets as $key => $ticket) {
                $tickets[$key]['ticket_last_response'] = $this->getLastResponse($ticket['ticket_id']);
            }
            return $tickets;
        } else {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM tickets
                LEFT JOIN clients ON tickets.ticket_client_id = clients.client_id
                LEFT JOIN users ON tickets.ticket_assigned_to = users.user_id
                LEFT JOIN ticket_statuses ON tickets.ticket_status = ticket_statuses.ticket_status_id
                LEFT JOIN contacts ON tickets.ticket_contact_id = contacts.contact_id
                WHERE ticket_status != 5
                ORDER BY ticket_created_at DESC
            ');
            $stmt->execute();
            $tickets = $stmt->fetchAll();
            foreach ($tickets as $key => $ticket) {
                $tickets[$key]['ticket_last_response'] = $this->getLastResponse($ticket['ticket_id']);
            }
            return $tickets;
        }
    }
    private function getLastResponse($ticket_id) {
        $stmt = $this->pdo->prepare('SELECT ticket_reply_created_at FROM ticket_replies WHERE ticket_reply_ticket_id = :ticket_id ORDER BY ticket_reply_created_at DESC LIMIT 1');
        $stmt->execute(['ticket_id' => $ticket_id]);
        return $stmt->fetchColumn();
    }
    public function getSupportHeaderNumbers($client_id = false) {
        return [
            'open_tickets' => $this->getTotalTicketsOpen($client_id)['total_tickets_open'],
            'closed_tickets' => $this->getTotalTicketsClosed($client_id)['total_tickets_closed'],
            'unassigned_tickets' => $this->getTotalTicketsUnassigned($client_id)['total_tickets_unassigned'],
            'scheduled_tickets' => $this->getTotalRecurringTickets($client_id)['total_scheduled_tickets']
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
