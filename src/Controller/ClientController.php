<?php
// src/Controller/ClientController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\Model\Client;
use Twetech\Nestogy\Model\Contact;
use Twetech\Nestogy\View\View;
use Twetech\Nestogy\Auth\Auth;

class ClientController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;

        if (!Auth::check()) {
            // Redirect to login page or handle unauthorized access
            header('Location: login.php');
            exit;
        }
    }

    public function index() {
        $clientModel = new Client($this->pdo);
        $clients = $clientModel->getClients(true);

        // Add Additional Data for Each Client
        foreach ($clients as &$client) {
            $client['client_balance'] = $clientModel->getClientBalance($client['client_id'])['balance'];
            $client['client_payments'] = $clientModel->getClientPaidAmount($client['client_id'])['amount_paid'];
        }
        
        $view = new View();
        $view->render('clients', ['clients' => $clients]);
    }
    public function show($client_id) {
        $clientModel = new Client($this->pdo);
        $client = $clientModel->getClient($client_id);

        $contactModel = new Contact($this->pdo);
        $client['client_contacts'] = $contactModel->getContacts($client_id);

        $data = [
            'client' => $client,
            'client_header' => $clientModel->getClientHeader($client_id)['client_header'],
        ];

        $view = new View();
        $view->render('client', $data, true);
    }
}
