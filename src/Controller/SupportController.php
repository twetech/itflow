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
    public function index($client_id = null) {
        $view = new View();
        if (isset($client_id)) {
            $clientModel = new Client($this->pdo);
            $supportModel = new Support($this->pdo);

            $client = $clientModel->getClient($client_id);

            $data = [
                'client' => $client,
                'client_header' => $clientModel->getClientHeader($client_id)['client_header'],
                'tickets' => $supportModel->getTickets($client_id),
                'support_header_numbers' => $supportModel->getSupportHeaderNumbers($client_id)
            ];

            error_log(print_r($data, true));
            
            $view->render('tickets', $data, true);
        }

    }
    public function show($ticket_id) {
        $view = new View();
        $view->render('ticket', ['ticket_id' => $ticket_id]);
    }
}
