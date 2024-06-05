<?php
// src/Controller/AccountingController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\View\View;
use Twetech\Nestogy\Auth\Auth;

class AccountingController {
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
        //Redirect to /public/?page=home temporarily
        header('Location: /public/?page=home');
        exit;
    }
}