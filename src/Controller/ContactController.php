<?php
// src/Controller/ContactController.php

namespace Twetech\Nestogy\Controller;

use Twetech\Nestogy\Model\Contact;
use Twetech\Nestogy\View\View;

class ContactController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index($client_id)
    {
        $contactModel = new Contact($this->pdo);
        $contacts = $contactModel->getContacts($client_id);

        $view = new View();
        $view->render('contacts', ['contacts' => $contacts]);
    }

    public function show($contact_id)
    {
        $contactModel = new Contact($this->pdo);
        $contact = $contactModel->getContact($contact_id);

        $view = new View();
        $view->render('contact', ['contact' => $contact]);
    }
}