<?php
// src/Controller/SupportController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\View\View;
use Twetech\Nestogy\Model\Support;
use Twetech\Nestogy\Model\Client;

class SupportController {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    

    //View all tickets, or view tickets for a specific client
    public function index($client_id = null) {
        $view = new View();
        if (isset($client_id)) {
            $clientModel = new Client($this->pdo);
            $supportModel = new Support($this->pdo);

            $client = $clientModel->getClient($client_id);

            $data = [
                'client' => $client,
                'client_header' => $clientModel->getClientHeader($client_id)['client_header'],
                'tickets' => $supportModel->getOpenTickets($client_id),
                'support_header_numbers' => $supportModel->getSupportHeaderNumbers($client_id)
            ];
            
            $view->render('tickets', $data, true);
        } else {
            $supportModel = new Support($this->pdo);
            $data = [
                'tickets' => $supportModel->getOpenTickets(),
                'support_header_numbers' => $supportModel->getSupportHeaderNumbers()
            ];
            $view->render('tickets', $data);
        }

    }

    //View a specific ticket
    public function show($ticket_id) {
        $view = new View();
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
