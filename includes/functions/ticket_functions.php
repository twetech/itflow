<?php


// Create Ticket
function createTicket(
    $parameters
){

    global $mysqli, $session_user_id, $session_name, $session_ip, $session_user_agent, $config_ticket_next_number, $config_ticket_prefix, $config_ticket_from_name, $config_ticket_from_email, $config_base_url, $config_smtp_host, $config_ticket_client_general_notifications;

    $client_id = intval($parameters['ticket_client_id']);
    $assigned_to = intval($parameters['ticket_assigned_to']);
    $contact = intval($parameters['ticket_contact']);
    $subject = sanitizeInput($parameters['ticket_subject']);
    $priority = sanitizeInput($parameters['ticket_priority']);
    $details = mysqli_real_escape_string($mysqli, $parameters['ticket_details']);
    $vendor_ticket_number = sanitizeInput($parameters['ticket_vendor_ticket_number']);
    $vendor_id = intval($parameters['ticket_vendor']);
    $asset_id = intval($parameters['ticket_asset']);
    $use_primary_contact = intval($parameters['ticket_use_primary_contact']);

    if ($assigned_to == 0) {
        $ticket_status = 'New';
    } else {
        $ticket_status = 'Open';
    }

    // Add the primary contact as the ticket contact if "Use primary contact" is checked
    if ($use_primary_contact == 1) {
        $sql = mysqli_query($mysqli, "SELECT contact_id FROM contacts WHERE contact_client_id = $client_id AND contact_primary = 1");
        $row = mysqli_fetch_array($sql);
        $contact = intval($row['contact_id']);
    }

    if (!isset($parameters['ticket_billable'])) {
        $billable = 1;
    } else {
        $billable = intval($parameters['ticket_billable']);
    }

    //Get the next Ticket Number and add 1 for the new ticket number
    $ticket_number = $config_ticket_next_number;
    $new_config_ticket_next_number = $config_ticket_next_number + 1;

    // Sanitize Config Vars from get_settings.php and Session Vars from check_login.php
    $config_ticket_prefix = sanitizeInput($config_ticket_prefix);
    $config_ticket_from_name = sanitizeInput($config_ticket_from_name);
    $config_ticket_from_email = sanitizeInput($config_ticket_from_email);
    $config_base_url = sanitizeInput($config_base_url);

    mysqli_query($mysqli, "UPDATE settings SET config_ticket_next_number = $new_config_ticket_next_number WHERE company_id = 1");

    mysqli_query($mysqli, "INSERT INTO tickets SET ticket_prefix = '$config_ticket_prefix', ticket_number = $ticket_number, ticket_subject = '$subject', ticket_details = '$details', ticket_priority = '$priority', ticket_billable = '$billable', ticket_status = '$ticket_status', ticket_vendor_ticket_number = '$vendor_ticket_number', ticket_vendor_id = $vendor_id, ticket_asset_id = $asset_id, ticket_created_by = $session_user_id, ticket_assigned_to = $assigned_to, ticket_contact_id = $contact, ticket_client_id = $client_id, ticket_invoice_id = 0");

    $ticket_id = mysqli_insert_id($mysqli);

    // Add Watchers
    if (!empty($parameters['ticket_watchers'])) {
        foreach ($parameters['ticket_watchers'] as $watcher) {
            $watcher_email = sanitizeInput($watcher);
            mysqli_query($mysqli, "INSERT INTO ticket_watchers SET watcher_email = '$watcher_email', watcher_ticket_id = $ticket_id");
        }
    }

    // E-mail client
    if (!empty($config_smtp_host) && $config_ticket_client_general_notifications == 1) {

        // Get contact/ticket details
        $sql = mysqli_query($mysqli, "SELECT contact_name, contact_email, ticket_prefix, ticket_number, ticket_category, ticket_subject, ticket_details, ticket_priority, ticket_status, ticket_created_by, ticket_assigned_to, ticket_client_id FROM tickets 
              LEFT JOIN clients ON ticket_client_id = client_id 
              LEFT JOIN contacts ON ticket_contact_id = contact_id
              WHERE ticket_id = $ticket_id");
        $row = mysqli_fetch_array($sql);

        $contact_name = sanitizeInput($row['contact_name']);
        $contact_email = sanitizeInput($row['contact_email']);
        $ticket_prefix = sanitizeInput($row['ticket_prefix']);
        $ticket_number = intval($row['ticket_number']);
        $ticket_category = sanitizeInput($row['ticket_category']);
        $ticket_subject = sanitizeInput($row['ticket_subject']);
        $ticket_details = mysqli_escape_string($mysqli, $row['ticket_details']);
        $ticket_priority = sanitizeInput($row['ticket_priority']);
        $ticket_status = sanitizeInput($row['ticket_status']);
        $client_id = intval($row['ticket_client_id']);
        $ticket_created_by = intval($row['ticket_created_by']);
        $ticket_assigned_to = intval($row['ticket_assigned_to']);

        // Get Company Phone Number
        $sql = mysqli_query($mysqli, "SELECT company_name, company_phone FROM companies WHERE company_id = 1");
        $row = mysqli_fetch_array($sql);
        $company_name = sanitizeInput($row['company_name']);
        $company_phone = sanitizeInput(formatPhoneNumber($row['company_phone']));

        // Verify contact email is valid
        if (filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {

            $subject = "Ticket created [$ticket_prefix$ticket_number] - $ticket_subject";
            $body = "<i style=\'color: #808080\'>##- Please type your reply above this line -##</i><br><br>Hello $contact_name,<br><br>A ticket regarding \"$ticket_subject\" has been created for you.<br><br>--------------------------------<br>$ticket_details--------------------------------<br><br>Ticket: $ticket_prefix$ticket_number<br>Subject: $ticket_subject<br>Status: Open<br>Portal: https://$config_base_url/portal/ticket.php?id=$ticket_id<br><br>--<br>$company_name - Support<br>$config_ticket_from_email<br>$company_phone";

            // Email Ticket Contact
            // Queue Mail
            $data = [];

            $data[] = [
                'from' => $config_ticket_from_email,
                'from_name' => $config_ticket_from_name,
                'recipient' => $contact_email,
                'recipient_name' => $contact_name,
                'subject' => $subject,
                'body' => $body
            ];

            // Also Email all the watchers
            $sql_watchers = mysqli_query($mysqli, "SELECT watcher_email FROM ticket_watchers WHERE watcher_ticket_id = $ticket_id");
            $body .= "<br><br>----------------------------------------<br>DO NOT REPLY - YOU ARE RECEIVING THIS EMAIL BECAUSE YOU ARE A WATCHER";
            while ($row = mysqli_fetch_array($sql_watchers)) {
                $watcher_email = sanitizeInput($row['watcher_email']);

                // Queue Mail
                $data[] = [
                    'from' => $config_ticket_from_email,
                    'from_name' => $config_ticket_from_name,
                    'recipient' => $watcher_email,
                    'recipient_name' => $watcher_email,
                    'subject' => $subject,
                    'body' => $body
                ];
            }
            addToMailQueue($mysqli, $data);
        }
    }

    // Logging
    mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Ticket', log_action = 'Create', log_description = '$session_name created ticket $config_ticket_prefix$ticket_number - $ticket_subject', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $ticket_id");

    $return_data = [
        'ticket_id' => $ticket_id,
        'ticket_number' => $ticket_number,
        'ticket_prefix' => $config_ticket_prefix,
        'ticket_subject' => $subject,
        'status' => 'success',
        'message' => "Ticket $config_ticket_prefix$ticket_number has been created"
    ];
    
    return $return_data;
}

function readTicket(
    $parameters
) {
    global $mysqli;

    if (empty($parameters['ticket_id'])) {
        return [
            'status' => 'error',
            'message' => 'Ticket ID is required'
    ];}

    $ticket_id = intval($parameters['ticket_id']);

    $columns = isset($parameters['columns']) ? sanitizeInput($parameters['columns']) : '*';

    // Check if there is an API Key Client ID parameter, if so, use it. Otherwise, default to 'all'
    $api_client_id = isset($parameters['api_key_client_id']) ? sanitizeInput($parameters['api_key_client_id']) : 0;

    // Get the where clause for the query
    $where_clause = getAPIWhereClause("ticket", $ticket_id, $api_client_id);

    $sql = mysqli_query($mysqli, "SELECT $columns FROM tickets $where_clause");
    $tickets = [];

    while ($row = mysqli_fetch_assoc($sql)) {
        $tickets[$row['ticket_id']] = $row;
    }

    return $tickets;
}
function updateTicket(
    $parameters
) {

    global $mysqli, $session_user_id, $session_name, $session_ip, $session_user_agent;

    if (!isset($session_ip) || !isset($session_user_agent) || !isset($session_user_id) || !isset($session_name)){
        //Assume API is making changes
        $session_ip = "API";
        $session_user_agent = "API";
        $session_user_id = 0;
        $session_name = "API";
    }

    //if in parameters, set the new value, add to update_sql 
    $update_sql = "UPDATE tickets SET";
    foreach ($parameters as $key => $value) {
        $update_sql .= " $key = '$value'";
        // if not the last key in the array, add a comma
        if ($key !== array_key_last($parameters)) {
            $update_sql .= ",";
        }
    }
    $ticket_id = intval($parameters['ticket_id']);
    $update_sql .= " WHERE ticket_id = $ticket_id";


    mysqli_query($mysqli, $update_sql);


    $ticket_reply = "Ticket updated by $session_name, changes: ";
    foreach ($parameters as $key => $value) {
        if ($key !== 'ticket_id') {
            //remove ticket_ from key
            $key = ucfirst(str_replace('ticket_', '', $key));
            if ($key === 'Contact_id') {
                $sql = mysqli_query($mysqli, "SELECT contact_name FROM contacts WHERE contact_id = $value");
                $row = mysqli_fetch_array($sql);
                $key = 'Contact';
                $value = sanitizeInput($row['contact_name']);
            }
            if ($key === 'Client_id') {
                $sql = mysqli_query($mysqli, "SELECT client_name FROM clients WHERE client_id = $value");
                $row = mysqli_fetch_array($sql);
                $value = sanitizeInput($row['client_name']);
            }
            if ($key === 'Assigned_to') {
                $sql = mysqli_query($mysqli, "SELECT user_name FROM users WHERE user_id = $value");
                $row = mysqli_fetch_array($sql);
                $value = sanitizeInput($row['user_name']);
            }
            if ($key === 'Invoice_id') {
                $sql = mysqli_query($mysqli, "SELECT invoice_number,invoice_prefix FROM invoices WHERE invoice_id = $value");
                $row = mysqli_fetch_array($sql);
                $value = sanitizeInput($row['invoice_number']);
            }
            if ($key === 'Billable') {
                if ($value == 1) {
                    $value = 'Yes';
                } else {
                    $value = 'No';
                }
            }

            $ticket_reply .= "$key: $value, ";
        }
    }

    $ticket_data = readTicket(['ticket_id' => $ticket_id]);
    $ticket_number = $ticket_data[$ticket_id]['ticket_prefix'] . $ticket_data[$ticket_id]['ticket_number'];
    $subject = $ticket_data[$ticket_id]['ticket_subject'];
    $client_id = $ticket_data[$ticket_id]['ticket_client_id'];


    mysqli_query($mysqli, "INSERT INTO ticket_replies SET ticket_reply = '$ticket_reply', ticket_reply_type = 'Internal', ticket_reply_time_worked = '00:01:00', ticket_reply_by = $session_user_id, ticket_reply_ticket_id = $ticket_id");

    // Logging
    mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Ticket', log_action = 'Modify', log_description = '$session_name modified ticket $ticket_number - $subject', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $ticket_id");

    //drop ticket_id from parameters for return data message
    unset($parameters['ticket_id']);

    $return_data = [
        'status' => 'success',
        'message' => 'Ticket ' . $ticket_number . ' has been updated. \nUpdated '. implode(", ", array_keys($parameters)),
        'ticket' => readTicket(['ticket_id' => $ticket_id])
    ];

    return $return_data;
}

function deleteTicket(
    $parameters
) {

    global $mysqli, $session_user_id, $session_name, $session_ip, $session_user_agent;

    $ticket_id = intval($parameters['ticket_id']);

    // Get Ticket and Client ID for logging and alert message
    $sql = mysqli_query($mysqli, "SELECT ticket_prefix, ticket_number, ticket_subject, ticket_status, ticket_client_id FROM tickets WHERE ticket_id = $ticket_id");
    $row = mysqli_fetch_array($sql);
    $ticket_prefix = sanitizeInput($row['ticket_prefix']);
    $ticket_number = sanitizeInput($row['ticket_number']);
    $ticket_subject = sanitizeInput($row['ticket_subject']);
    $ticket_status = sanitizeInput($row['ticket_status']);
    $client_id = intval($row['ticket_client_id']);

    if ($ticket_status !== 'Closed') {
        mysqli_query($mysqli, "DELETE FROM tickets WHERE ticket_id = $ticket_id");

        // Delete all ticket replies
        mysqli_query($mysqli, "DELETE FROM ticket_replies WHERE ticket_reply_ticket_id = $ticket_id");

        // Delete all ticket views
        mysqli_query($mysqli, "DELETE FROM ticket_views WHERE view_ticket_id = $ticket_id");

        // Logging
        mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Ticket', log_action = 'Delete', log_description = '$session_name deleted ticket $ticket_prefix$ticket_number - $ticket_subject along with all replies', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $ticket_id");

        $return_data = [
            'status' => 'success',
            'message' => "Ticket $ticket_prefix$ticket_number has been deleted"
        ];
    } else {
        $return_data = [
            'status' => 'error',
            'message' => "Ticket $ticket_prefix$ticket_number is closed and cannot be deleted"
        ];
    }

    return $return_data;
}

function getTicketStatusColor($status) {
    switch ($status) {

        case 'New':
            return 'danger';

        case 'Assigned':
            return 'danger';

        case 'Open':
            return 'warning';

        case 'On Hold':
            return 'success';

        case 'Closed':
            return 'dark';

        case 'Auto Close':
            return 'dark';
        
        case 'In-Progress':
            return 'primary';
            
        default:
            return 'secondary';
    }
}

function getUnassignedTickets() {
    global $mysqli;

    //Count the number of unassigned tickets

    $sql = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS unassigned_tickets FROM tickets WHERE ticket_assigned_to = 0 AND ticket_status != 'Closed'");
    $row = mysqli_fetch_array($sql);
    $unassigned_tickets = intval($row['unassigned_tickets']);

    return $unassigned_tickets;

}

function getStaleTickets() {
    global $mysqli;

    //Count the number of tickets with a reply older than 3 days

    $sql = mysqli_query($mysqli,
    "SELECT COUNT(ticket_id) AS stale_tickets, ticket_status FROM tickets
    LEFT JOIN ticket_replies ON ticket_id = ticket_reply_ticket_id
    WHERE ticket_status != 'Closed'
    GROUP BY ticket_id HAVING MAX(ticket_reply_created_at) < DATE_SUB(NOW(), INTERVAL 3 DAY)
    ");
    $row = mysqli_fetch_array($sql);
    $stale_tickets = intval($row['stale_tickets']);

    // Count the number of tickets without a reply older than 3 days
    $sql = mysqli_query($mysqli,
    "SELECT COUNT(ticket_id) AS stale_tickets, ticket_status, ticket_id, ticket_created_at FROM tickets
    WHERE ticket_status != 'Closed'
    AND ticket_id NOT IN (SELECT ticket_reply_ticket_id FROM ticket_replies)
    AND ticket_created_at < DATE_SUB(NOW(), INTERVAL 3 DAY)
    ");
    $row = mysqli_fetch_array($sql);
    $stale_tickets += intval($row['stale_tickets']);


    return $stale_tickets;

}