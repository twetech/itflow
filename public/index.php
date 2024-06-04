<?php

// public/index.php

require '../bootstrap.php';

use Twetech\Nestogy\Auth\Auth;
use Twetech\Nestogy\Controller\HomeController;
use Twetech\Nestogy\Controller\ClientController;
use Twetech\Nestogy\Controller\SupportController;
use Twetech\Nestogy\Controller\AccountingController;


// Simple routing logic
$page = $_GET['page'] ?? 'home';

if (!Auth::check()) {
    header('Location: login.php');
    exit;
}

switch ($page) {

    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'client':
        $controller = new ClientController($pdo);
        // Check if the action is to show a single client
        if (isset($_GET['action']) && $_GET['action'] === 'show') {

            if (!isset($_GET['client_id'])) {
                http_response_code(404);
                echo "Client not found.";
                exit;
            }

            // Get the client_id from the query string
            $client_id = intval($_GET['client_id']);

            // Call the show method to display the client details
            $controller->show($client_id);
        } else {
            $controller->index();
        }
        break;

    case 'ticket':
        if (isset($_GET['ticket_id'])) {
            $controller = new SupportController($pdo);
            $controller->show($_GET['ticket_id']);
        } 
        if (isset($_GET['client_id'])) {
            $controller = new SupportController($pdo);
            $client_id = intval($_GET['client_id']);
            $controller->index($client_id);
        } else {
            $controller = new SupportController($pdo);
            $controller->index();
        }
        break;



    default:
        http_response_code(404);
        echo "Page not found.";
        break;
}
