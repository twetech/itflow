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
        $controller->index();
        break;

    case 'support':
        $controller = new SupportController();
        $controller->index();
        break;

    case 'accounting':
        $controller = new AccountingController();
        $controller->index();
        break;
    


    default:
        http_response_code(404);
        echo "Page not found.";
        break;
}
