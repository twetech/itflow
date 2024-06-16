<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
 * ITFlow - Main GET/POST request handler
 */

require_once "/var/www/portal.twe.tech/includes/tenant_db.php";

require_once "/var/www/portal.twe.tech/includes/config/config.php";

require_once "/var/www/portal.twe.tech/includes/functions/functions.php";

require_once "/var/www/portal.twe.tech/includes/check_login.php";

requireOnceAll("/var/www/portal.twe.tech/post");



?>
