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
    //Remove Client_ from the page name and uc the first letter
    $page_name = ucfirst(str_replace('Client_', '', $page_name));
}

//if pagename starts with ticket, set page_is_ticket to true
if (strpos($page_name, 'Ticket') !== false && strpos($page_name, 'Tickets') === false){
    $page_is_ticket = true;
}

$client_id = $_GET['client_id'] ? intval($_GET['client_id']) : 0;
$ticket_id = $_GET['ticket_id'] ? intval($_GET['ticket_id']) : 0;

if ($ticket_id) {
    // Get the client_name from the tickets table for breadcrumbs
    $ticket_sql = "SELECT client_name, client_id FROM tickets
    LEFT JOIN clients ON tickets.ticket_client_id = clients.client_id
    WHERE ticket_id = $ticket_id";
    $ticket_row = mysqli_fetch_assoc(mysqli_query($mysqli, $ticket_sql));
    $client_name = $ticket_row['client_name'];
    $client_id = $ticket_row['client_id'];
}

$datatable_order = "[[0, 'desc']]";

if ($client_id) {
    require_once "/var/www/develop.twe.tech/includes/inc_all_client.php";
}

//Get user shortcuts from the database
$shortcuts_sql = "SELECT * FROM user_shortcuts WHERE user_shortcut_user_id = $session_user_id ORDER BY user_shortcut_order ASC";
$shortcuts_result = mysqli_query($mysqli, $shortcuts_sql);
while ($row = mysqli_fetch_assoc($shortcuts_result)) {
    $shortcutsData[] = [
        'shortcut_key' => $row['user_shortcut_key']
    ];
}

require_once "/var/www/develop.twe.tech/includes/top_nav.php";

require_once "/var/www/develop.twe.tech/includes/inc_alert_feedback.php";


?>
