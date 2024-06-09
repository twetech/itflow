<?php
// src/Controller/SupportController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\Auth\Auth;
use Twetech\Nestogy\View\View;
use Twetech\Nestogy\Model\Support;
use Twetech\Nestogy\Model\Client;

class SupportController {
    private $pdo;
    private $auth;
    private $view;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index($client_id = null) {
    //View all tickets, or view tickets for a specific client
        $supportModel = new Support($this->pdo);
        $view = new View();
        $auth = new Auth($this->pdo);
        // Check if user has access to the support class
        if (!$auth->checkClassAccess($_SESSION['user_id'], 'support', 'view')) {
        // If user does not have access, display an error message
            $view->error([
                'title' => 'Access Denied',
                'message' => 'You do not have permission to view support tickets.'
            ]);
            return;
        }

        if (isset($client_id)) {
        // Check if client_id is set to view tickets for a specific client
            // Check if user has access to client
            if (!$auth->checkClientAccess($_SESSION['user_id'], $client_id, 'view')) {
            // If user does not have access, display an error message
                $view->error([
                    'title' => 'Access Denied',
                    'message' => 'You do not have permission to view this client\'s tickets.'
                ]);
                return;
            }
            // Get client details
            $clientModel = new Client($this->pdo);
            $client = $clientModel->getClient($client_id);
            // Assemble data to pass to the view
            $data = [
                'client' => $client,
                'client_header' => $clientModel->getClientHeader($client_id)['client_header'],
                'tickets' => $supportModel->getOpenTickets($client_id),
                'support_header_numbers' => $supportModel->getSupportHeaderNumbers($client_id)
            ];
            // Render the view
            $view->render('tickets', $data, true);
        } else {
        // If client_id is not set, view all tickets
            // Assemble data to pass to the view
            $data = [
                'tickets' => $supportModel->getOpenTickets(),
                'support_header_numbers' => $supportModel->getSupportHeaderNumbers()
            ];
            // Render the view
            $view->render('tickets', $data);
        }
    }

    //View a specific ticket
    public function show($ticket_id) {
        $view = new View();
        $auth = new Auth($this->pdo);
        // Check if user has access to the support class
        if (!$auth->checkClassAccess($_SESSION['user_id'], 'support', 'view')) {
        // If user does not have access, display an error message
            $view->error([
                'title' => 'Access Denied',
                'message' => 'You do not have permission to view support tickets.'
            ]);
            return;
        }
        $supportModel = new Support($this->pdo);
        $clientModel = new Client($this->pdo);
        $ticket = $supportModel->getTicket($ticket_id);

        $data = [
            'ticket' => $ticket,
            'ticket_replies' => $supportModel->getTicketReplies($ticket_id),
        ];
        $data['ticket']['ticket_collaborators'] = $supportModel->getTicketCollaborators($ticket_id);


        if (!empty($ticket['ticket_client_id'])) {
            $client_id = $ticket['ticket_client_id'];
            $data['client'] = $clientModel->getClient($client_id);
            $data['client_header'] = $clientModel->getClientHeader($client_id)['client_header'];
            $client_page = true;
        } else {
            $client_page = false;
        }

        $view->render('ticket', $data, $client_page);
    }
}
