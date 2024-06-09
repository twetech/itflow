<?php

// public/index.php

require '../bootstrap.php';

use Twetech\Nestogy\Auth\Auth;
use Twetech\Nestogy\Controller\HomeController;
use Twetech\Nestogy\Controller\ClientController;
use Twetech\Nestogy\Controller\SupportController;
use Twetech\Nestogy\Controller\DocumentationController;
use Twetech\Nestogy\View\View;


// Simple routing logic
$page = $_GET['page'] ?? 'home';

if (!Auth::check()) {
    header('Location: login.php');
    exit;
}

switch ($page) {

    case 'home': {
        $home = new HomeController();
        $home->index();
        break;
    }

    case 'client': {
        $client = new ClientController($pdo);
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
            $client->show($client_id);
        } else {
            $client->index();
        }
    break;
    }

    case 'ticket': {
        if (isset($_GET['ticket_id'])) {
            $support = new SupportController($pdo);
            $support->show($_GET['ticket_id']);
        } 
        if (isset($_GET['client_id'])) {
            $support = new SupportController($pdo);
            $client_id = intval($_GET['client_id']);
            $support->index($client_id);
        } else {
            $support = new SupportController($pdo);
            $support->index();
        }
        break;
    }
    
    case 'contact': {
        $client = new ClientController($pdo);
        if (!isset($_GET['client_id'])) {
            http_response_code(404);
            echo "Client not found.";
            exit;
        }
        $client_id = intval($_GET['client_id']);
        $client->showContacts($client_id);
        break;
    }

    case 'location': {
        $client = new ClientController($pdo);
        if (!isset($_GET['client_id'])) {
            http_response_code(404);
            echo "Client not found.";
            exit;
        }
        $client_id = intval($_GET['client_id']);
        $client->showLocations($client_id);
        break;
    }

    case 'documentation': {
        $documentation = new DocumentationController($pdo);
        if (isset($_GET['documentation_type'])) {
            $documentation->show($_GET['documentation_type'], $_GET['client_id'] ?? false);
        } else {
            $documentation->index();
        }
        break;
    }

    default: {
        $view = new View();
        $messages = [ // comedic messages to display when the page is not found
            "Well, this is awkward. The page you're looking for ran away with the circus. Try searching for something else or double-check that URL!",
            "Oh no! The page you're looking for is on vacation. Try searching for something else or double-check that URL!",
            "Oh dear! The page you're looking for must be taking a nap. Try searching for something else or double-check that URL!",
            "Oh snap! The page you're looking for is on a coffee break. Try searching for something else or double-check that URL!",
            "Oh my! The page you're looking for must be in a meeting. Try searching for something else or double-check that URL!",
            "Oh brother! The page you're looking for is at the gym. Try searching for something else or double-check that URL!",
            "Yee Yee, the page you're looking for is at the rodeo. Try searching for something else or double-check that URL!"
        ];
        $message = $messages[array_rand($messages)];
        $view->error([
            'title' => 'Oops! Page "'.$page.'" not found',
            'message' => $message
        ]);
    }
}