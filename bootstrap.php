<?php

// Load Composer's autoloader
require_once 'vendor/autoload.php';

// Set up error reporting (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load configuration settings
$config = require_once 'config.php';

// Set up the database connection
use Twetech\Nestogy\Database;

$database = new Database($config['db']);
$pdo = $database->getConnection();

if (!isset($_SESSION)) {
    // Tell client to only send cookie(s) over HTTPS
    ini_set("session.cookie_secure", true);
    session_start();
}

// Initialize other dependencies or services here
// For example, a logger or cache system

$currency_format = numfmt_create($config['locale'], NumberFormatter::CURRENCY);
$GLOBALS['currency_format'] = $currency_format;


require_once 'includes/functions/functions.php';

// Your application is now bootstrapped and ready to run
