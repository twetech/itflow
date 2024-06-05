<?php
// src/View/View.php

namespace Twetech\Nestogy\View;

class View {
    public function render($template, $data = [], $client_page = false) {
        extract($data);
        require "../src/View/header.php";
        require "../src/View/navbar.php";
        require "../src/View/$template.php";
        require "../src/View/footer.php";
    }
    public function error($message) {
        extract($message);
        require "../src/View/error.php";
    }
}
