<?php

require_once "/var/www/develop.twe.tech/includes/tenant_db.php";

require_once "/var/www/develop.twe.tech/includes/config.php";

require_once "/var/www/develop.twe.tech/includes/functions/functions.php";

require_once "/var/www/develop.twe.tech/includes/check_login.php";

require_once "/var/www/develop.twe.tech/includes/header.php";

$page_name = basename($_SERVER['PHP_SELF']);

if (strpos($page_name, 'index') !== false) {
    $page_name = 'Dashboard';
} else {
    $page_name = ucfirst(str_replace('.php', '', $page_name));
}

//if pagename starts with client_, set page_is_client to true
if (strpos($page_name, 'Client_') !== false) {
    $page_is_client = true;
    //remove the client_ from the page name
    $page_name = ucfirst(str_replace('Client_', '', $page_name));

} else {
    $page_is_client = false;
}

$client_id = $_GET['client_id'] ? intval($_GET['client_id']) : 0;

if ($client_id) {
    require_once "/var/www/develop.twe.tech/includes/inc_all_client.php";
}

require_once "/var/www/develop.twe.tech/includes/top_nav.php";


?>
