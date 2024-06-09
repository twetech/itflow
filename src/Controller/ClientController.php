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
        $view = new View();
        $auth = new Auth($this->pdo);
        // Check if user has access to the client class
        if (!$auth->checkClassAccess($_SESSION['user_id'], 'client', 'view')) {
        // If user does not have access, display an error message
            $view->error([
                'title' => 'Access Denied',
                'message' => 'You do not have permission to view clients.'
            ]);
            return;
        }
        $clientModel = new Client($this->pdo);
        $clients = $clientModel->getClients(true);

        // Add Additional Data for Each Client
        foreach ($clients as &$client) {
            $client['client_balance'] = $clientModel->getClientBalance($client['client_id'])['balance'];
            $client['client_payments'] = $clientModel->getClientPaidAmount($client['client_id'])['amount_paid'];
        }
        
        $view->render('clients', ['clients' => $clients]);
    }
    public function show($client_id) {
        $view = new View();
        $auth = new Auth($this->pdo);
        // Check if user has access to the client class
        if (!$auth->checkClassAccess($_SESSION['user_id'], 'client', 'view') || !$auth->checkClientAccess($_SESSION['user_id'], $client_id, 'view')) {
            // If user does not have access, display an error message
            $view->error([
                'title' => 'Access Denied',
                'message' => 'You do not have permission to view this client.'
            ]);
            return;
        }
        $clientModel = new Client($this->pdo);
        $client = $clientModel->getClient($client_id);

        $contactModel = new Contact($this->pdo);
        $client['client_contacts'] = $contactModel->getContacts($client_id);

        $data = [
            'client' => $client,
            'client_header' => $clientModel->getClientHeader($client_id)['client_header'],
        ];

        $view->render('client', $data, true);
    }
    public function showContacts($client_id) {
        $contactModel = new Contact($this->pdo);
        $clientModel = new Client($this->pdo);
        $auth = new Auth($this->pdo);
        $view = new View();

        // Check if user has access to the client class
        if (!$auth->checkClassAccess($_SESSION['user_id'], 'contact', 'view')) {
            // If user does not have access, display an error message
            $view->error([
                'title' => 'Access Denied',
                'message' => 'You do not have permission to view client contacts.'
            ]);
            return;
        }
        // Check if user has access to client
        if (!$auth->checkClientAccess($_SESSION['user_id'], $client_id, 'view')) {
            // If user does not have access, display an error message
            $view->error([
                'title' => 'Access Denied',
                'message' => 'You do not have permission to view this client\'s contacts.'
            ]);
            return;
        }

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
        $view->render('simpleTable', $data, true);
    }
    public function showLocations($client_id) {
        $clientModel = new Client($this->pdo);
        $auth = new Auth($this->pdo);
        $view = new View();

        // Check if user has access to the client class
        if (!$auth->checkClassAccess($_SESSION['user_id'], 'client', 'view')) {
            // If user does not have access, display an error message
            $view->error([
                'title' => 'Access Denied',
                'message' => 'You do not have permission to view client locations.'
            ]);
            return;
        }
        // Check if user has access to client
        if (!$auth->checkClientAccess($_SESSION['user_id'], $client_id, 'view')) {
            // If user does not have access, display an error message
            $view->error([
                'title' => 'Access Denied',
                'message' => 'You do not have permission to view this client\'s locations.'
            ]);
            return;
        }
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

        $view->render('simpleTable', $data, true);
    }
}
