<?php
// src/Controller/HomeController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\Model\Client;
use Twetech\Nestogy\View\View;

class HomeController {
    public function index() {
        $view = new View();
        $view->render('home');
    }
}
