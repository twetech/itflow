<?php
// src/Controller/ClientController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\Model\Client;
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
        
        $view = new View();
        $view->render('clients', ['clients' => $clients]);
    }
}
