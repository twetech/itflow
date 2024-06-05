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
    public function showContacts($client_id) {
        $contactModel = new Contact($this->pdo);
        $clientModel = new Client($this->pdo);
        $rawContacts = $contactModel->getContacts($client_id);

        $contacts = [];
        foreach ($rawContacts as $contact) {
            $contacts[] = [
                $contact['contact_name'],
                $contact['contact_email'],
                $contact['contact_phone']
            ];
        }
        $data = [
            'card' => [
                'title' => 'Contacts'
            ],
            'client_header' => $clientModel->getClientHeader($client_id)['client_header'],
            'table' => [
                'header_rows' => ['Name', 'Email', 'Phone'],
                'body_rows' => $contacts
            ]
        ];

        $view = new View();
        $view->render('simpleTable', $data, true);
    }
    public function showLocations($client_id) {
        $clientModel = new Client($this->pdo);
        $rawLocations = $clientModel->getClientLocations($client_id);

        $locations = [];
        foreach ($rawLocations as $location) {
            $locationAdress = $location['location_address'] . ', ' . $location['location_city'] . ', ' . $location['location_state'] . ' ' . $location['location_zip'];
            $locations[] = [
                $location['location_name'],
                $locationAdress,
                $location['location_phone'],
                $location['location_hours']
            ];
        }
        
        $data = [
            'card' => [
                'title' => 'Locations'
            ],
            'client_header' => $clientModel->getClientHeader($client_id)['client_header'],
            'table' => [
                'header_rows' => ['Location Name', 'Address', 'Phone', 'Hours'],
                'body_rows' => $locations
            ]
        ];

        $view = new View();
        $view->render('simpleTable', $data, true);
    }
}
