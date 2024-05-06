<?php

require_once "/var/www/portal.twe.tech/includes/tenant_db.php";

require_once "/var/www/portal.twe.tech/includes/config.php";

require_once "/var/www/portal.twe.tech/includes/functions/functions.php";

require_once "/var/www/portal.twe.tech/includes/check_login.php";

require_once "/var/www/portal.twe.tech/includes/header.php";

require_once "/var/www/portal.twe.tech/includes/inc_alert_feedback.php";

$page_name = $_SERVER['REQUEST_URI'];
//remove /pages/ from the page name
$page_name = str_replace('/pages/', '', $page_name);
//remove .php and anything after it from the page name, and save in new variable
$page_name_exploded = explode('.php', $page_name);
$page_name_no_ext = $page_name_exploded[0];
$page_vars = $page_name_exploded[1];
$page_name = $page_name_no_ext;

// Remove ? from page vars
$page_vars = str_replace('?', '', $page_vars);

// If pagename starts with client/client_, the set client_page to true
if (strpos($page_name, 'client/client_') !== false) {
    $client_page = true;
    // remove client/client_ from the page name
    $page_name = str_replace('client/client_', '', $page_name);
    // replace _ with spaces in the page name
    $page_name = str_replace('_', ' ', $page_name);
    // Capitalize the first letter of each word in the page name
    $page_name = ucwords($page_name);

} else if (strpos($page_name, 'report/report_') !== false) {
    // set page name to the name of the report
    $page_name = str_replace('report/report_', '', $page_name);
    // replace _ with spaces in the page name
    $page_name = str_replace('_', ' ', $page_name);
    // Capitalize the first letter of each word in the page name
    $page_name = ucwords($page_name);
    $report_page = true;

} else if ($page_name == 'ticket') {
    $client_page = true;
}
$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;


if ($ticket_id) {
    // Get the client_name from the tickets table for breadcrumbs
    $ticket_sql = "SELECT client_name, client_id FROM tickets
    LEFT JOIN clients ON tickets.ticket_client_id = clients.client_id
    WHERE ticket_id = $ticket_id";
    $ticket_row = mysqli_fetch_assoc(mysqli_query($mysqli, $ticket_sql));
    $client_name = $ticket_row['client_name'];
    $client_id = $ticket_row['client_id'];
}

if (isset($invoice_id)) {
    // Get the client_name from the invoices table for breadcrumbs
    $invoice_sql = "SELECT client_name, client_id FROM invoices
    LEFT JOIN clients ON invoices.invoice_client_id = clients.client_id
    WHERE invoice_id = $invoice_id";
    $invoice_row = mysqli_fetch_assoc(mysqli_query($mysqli, $invoice_sql));
    $client_name = $invoice_row['client_name'];
    $client_id = $invoice_row['client_id'];
    $client_page = true;
}

if (isset($_GET['client_id'])) {
    $client_id = intval($_GET['client_id']);
}

if (isset($client_page)) {
    if ($client_page){
        require_once "/var/www/portal.twe.tech/includes/inc_all_client.php";
    }
}

// Default datatable settings
$datatable_order = "[[0, 'desc']]";

//Get user shortcuts from the database
$shortcuts_sql = "SELECT * FROM user_shortcuts WHERE user_shortcut_user_id = $session_user_id ORDER BY user_shortcut_order ASC";
$shortcuts_result = mysqli_query($mysqli, $shortcuts_sql);
while ($row = mysqli_fetch_assoc($shortcuts_result)) {
    $shortcutsData[] = [
        'shortcut_key' => $row['user_shortcut_key']
    ];
}

require_once "/var/www/portal.twe.tech/includes/top_nav.php";



?>
