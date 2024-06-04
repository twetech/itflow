<?php
// src/Controller/SupportController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\View\View;

class SupportController {
    public function index() {
        $view = new View();
        $view->render('support');
    }
}
