<?php
/*
 * CRON - Email Parser
 * Process emails and create/update tickets
 */

/*
TODO:
  - Attachments
  - Process unregistered contacts/clients into an inbox to allow a ticket to be created/ignored
  - Better handle replying to closed tickets
  - Support for authenticating with OAuth
  - Documentation
  - Separate Mailbox Account for tickets 2022-12-14 - JQ
  - Properly parse base64 encoded emails (if an Outlook user sends a smiley everything breaks :( - https://electrictoolbox.com/php-imap-message-parts/)

Relate PRs to https://github.com/itflow-org/itflow/issues/225 & https://forum.itflow.org/d/11-road-map & https://forum.itflow.org/d/31-tickets-from-email
*/

// Get ITFlow config & helper functions
include_once("config.php");
include_once("functions.php");

// Get settings for the "default" company
$company_id = 1;
$session_company_id = 1;
include_once("get_settings.php");

// Check setting enabled
if ($config_ticket_email_parse == 0) {
    exit("Feature is not enabled - see Settings > Ticketing > Email-to-ticket parsing");
}

// Check IMAP function exists
if (!function_exists('imap_open')) {
    echo "PHP IMAP extension is not installed, quitting..";
    exit();
}


// Function to raise a new ticket for a given contact and email them confirmation (if configured)
function createTicket($contact_id, $contact_name, $contact_email, $client_id, $company_id, $date, $subject, $message) {

    // Access global variables
    global $mysqli, $config_ticket_next_number, $config_ticket_prefix, $config_ticket_client_general_notifications, $config_base_url, $config_ticket_from_name, $config_ticket_from_email, $config_smtp_host, $config_smtp_port, $config_smtp_encryption, $config_smtp_username, $config_smtp_password;

    // Prep ticket details
    $message = nl2br(htmlentities(strip_tags($message)));
    $message = trim(mysqli_real_escape_string($mysqli,"<i>Email from: $contact_email at $date:-</i> <br><br>$message"));

    // Get the next Ticket Number and add 1 for the new ticket number
    $ticket_number = $config_ticket_next_number;
    $new_config_ticket_next_number = $config_ticket_next_number + 1;
    mysqli_query($mysqli,"UPDATE settings SET config_ticket_next_number = $new_config_ticket_next_number WHERE company_id = $company_id");

    mysqli_query($mysqli,"INSERT INTO tickets SET ticket_prefix = '$config_ticket_prefix', ticket_number = $ticket_number, ticket_subject = '$subject', ticket_details = '$message', ticket_priority = 'Low', ticket_status = 'Open', ticket_created_at = NOW(), ticket_created_by = '0', ticket_contact_id = $contact_id, ticket_client_id = $client_id, company_id = $company_id");
    $id = mysqli_insert_id($mysqli);

    // Logging
    echo "Created new ticket.<br>";
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Ticket', log_action = 'Create', log_description = 'Email parser: Client contact $contact_email created ticket $config_ticket_prefix$ticket_number ($subject)', log_created_at = NOW(), log_client_id = $client_id, company_id = $company_id");

    // Get company name & phone
    $sql = mysqli_query($mysqli,"SELECT company_name, company_phone FROM companies WHERE company_id = $company_id");
    $row = mysqli_fetch_array($sql);
    $company_phone = formatPhoneNumber($row['company_phone']);
    $company_name = $row['company_name'];


    // E-mail client notification that ticket has been created
    if ($config_ticket_client_general_notifications == 1) {

        $email_subject = "Ticket created - [$config_ticket_prefix$ticket_number] - $subject";
        $email_body    = "<i style='color: #808080'>#--itflow--#</i><br><br>Hello, $contact_name<br><br>Thank you for your email. A ticket regarding \"$subject\" has been automatically created for you.<br><br>Ticket: $config_ticket_prefix$ticket_number<br>Subject: $subject<br>Status: Open<br>https://$config_base_url/portal/ticket.php?id=$id<br><br>~<br>$company_name<br>Support Department<br>$config_ticket_from_email<br>$company_phone";

        $mail = sendSingleEmail($config_smtp_host, $config_smtp_username, $config_smtp_password, $config_smtp_encryption, $config_smtp_port,
            $config_ticket_from_email, $config_ticket_from_name,
            $contact_email, $contact_name,
            $email_subject, $email_body);

        if ($mail !== true) {
            mysqli_query($mysqli,"INSERT INTO notifications SET notification_type = 'Mail', notification = 'Failed to send email to $contact_email', notification_timestamp = NOW(), company_id = $company_id");
            mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Mail', log_action = 'Error', log_description = 'Failed to send email to $contact_email regarding $subject. $mail', company_id = $company_id");
        }

    }

    return true;

}

// Prepare connection string with encryption (TLS/SSL/<blank>)
$imap_mailbox = "$config_imap_host:$config_imap_port/imap/$config_imap_encryption";

// Connect to host via IMAP
$imap = imap_open("{{$imap_mailbox}}INBOX", $config_smtp_username, $config_smtp_password);

// Check connection
if (!$imap) {
    // Logging
    $extended_log_description = var_export(imap_errors(), true);
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Mail', log_action = 'Error', log_description = 'Email parser: Failed to connect to IMAP. Details: $extended_log_description', company_id = $company_id");
    exit("Could not connect to IMAP");
}

// Check for the ITFlow_Processed mailbox that we move messages to once processed
$imap_folder = 'INBOX/ITFlow_Processed';
$list = imap_list($imap, "{{$imap_mailbox}}", "*");
if (array_search("{{$imap_mailbox}}$imap_folder", $list) === false) {
    imap_createmailbox($imap, imap_utf7_encode("{{$imap_mailbox}}$imap_folder"));
}

// Search for unread ("UNSEEN") emails
$emails = imap_search($imap,'UNSEEN');

if ($emails) {

    // Sort
    rsort($emails);

    // Loop through each email
    foreach($emails as $email) {

        // Default false
        $email_processed = false;

        // Get message details
        $metadata = imap_fetch_overview($imap, $email,0); // Date, Subject, Size
        $header = imap_headerinfo($imap, $email); // To get the From as an email, not a contact name
        $message = (imap_fetchbody($imap, $email, 1)); // Body

        $from = trim(mysqli_real_escape_string($mysqli, htmlentities(strip_tags($header->from[0]->mailbox . "@" . $header->from[0]->host))));
        $subject = trim(mysqli_real_escape_string($mysqli, htmlentities(strip_tags($metadata[0]->subject))));
        $date = trim(mysqli_real_escape_string($mysqli, htmlentities(strip_tags($metadata[0]->date))));

        $domain = trim(mysqli_real_escape_string($mysqli, $header->from[0]->host));
        $from_name = trim(mysqli_real_escape_string($mysqli, $header->from[0]->mailbox));

        // Check if we can identify a ticket number (in square brackets)
        if (preg_match("/\[$config_ticket_prefix\d+\]/", $subject, $ticket_number)) {

            // Get the actual ticket number (without the brackets)
            preg_match('/\d+/', $ticket_number[0], $ticket_number);
            $ticket_number = intval($ticket_number[0]);

            // Split the email into just the latest reply, with some metadata
            //  We base this off the string "#--itflow--#" that we prepend the outgoing emails with (similar to the old school --reply above this line--)
            $message = explode("#--itflow--#", $message);
            $message = nl2br(htmlentities(strip_tags($message[0])));
            $message = "<i>Email from: $from at $date:-</i> <br><br>$message";

            // Lookup the ticket ID to add the reply to (just to check in-case the ID is different from the number).
            $ticket_sql = mysqli_query($mysqli, "SELECT * FROM tickets WHERE ticket_number = '$ticket_number' LIMIT 1");
            $row = mysqli_fetch_array($ticket_sql);
            $ticket_id = $row['ticket_id'];
            $ticket_reply_contact = $row['ticket_contact_id'];
            $ticket_assigned_to = $row['ticket_assigned_to'];
            $client_id = $row['ticket_client_id'];
            $company_id = $row['company_id'];
            $ticket_reply_type = 'Client'; // Setting to client as a default value

            // Check the ticket ID is valid
            if (intval($ticket_id) && $ticket_id !== '0') {

                // Check that ticket is open
                if ($row['ticket_status'] == "Closed") {

                    // It's closed - let's notify someone that a client tried to reply
                    mysqli_query($mysqli,"INSERT INTO notifications SET notification_type = 'Ticket', notification = 'Email parser: $from attempted to re-open ticket ID $ticket_id ($config_ticket_prefix$ticket_number) - check inbox manually to see email', notification_timestamp = NOW(), notification_client_id = '$client_id', company_id = '$company_id'");

                } else {

                    // Ticket is open, proceed.

                    // Check the email matches the contact's email - if it doesn't then mark the reply as internal (so the contact doesn't see it, and the tech can edit/delete if needed)
                    // Niche edge case - possibly where CC's on an email reply to a ticket?
                    $contact_sql = mysqli_query($mysqli, "SELECT contact_email FROM contacts WHERE contact_id = '$ticket_reply_contact'");
                    $row = mysqli_fetch_array($contact_sql);
                    if ($from !== $row['contact_email']) {
                        $ticket_reply_type = 'Internal';
                        $ticket_reply_contact = '0';
                        $message = "<b>WARNING: Contact email mismatch</b><br>$message"; // Add a warning at the start of the message - for the techs benefit (think phishing/scams)
                    }

                    // Sanitize ticket reply
                    $comment = trim(mysqli_real_escape_string($mysqli, $message));

                    // Add the comment
                    mysqli_query($mysqli, "INSERT INTO ticket_replies SET ticket_reply = '$comment', ticket_reply_type = '$ticket_reply_type', ticket_reply_time_worked = '00:00:00', ticket_reply_created_at = NOW(), ticket_reply_by = '$ticket_reply_contact', ticket_reply_ticket_id = '$ticket_id', company_id = '$company_id'");

                    // Update Ticket Last Response Field & set ticket to open as client has replied
                    mysqli_query($mysqli,"UPDATE tickets SET ticket_status = 'Open', ticket_updated_at = NOW() WHERE ticket_id = $ticket_id AND ticket_client_id = '$client_id' LIMIT 1");

                    echo "Updated existing ticket.<br>";
                    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Ticket', log_action = 'Update', log_description = 'Email parser: Client contact $from updated ticket $config_ticket_prefix$ticket_number ($subject)', log_created_at = NOW(), log_client_id = $client_id, company_id = $company_id");

                    $email_processed = true;
                }

            }


        } else {
            // Couldn't match this email to an existing ticket

            // Check if we can match the sender to a pre-existing contact
            $any_contact_sql = mysqli_query($mysqli, "SELECT * FROM contacts WHERE contact_email = '$from' LIMIT 1");
            $row = mysqli_fetch_array($any_contact_sql);

            $contact_name = $row['contact_name'];
            $contact_id = $row['contact_id'];
            $contact_email = $row['contact_email'];
            $client_id = $row['contact_client_id'];
            $company_id = $row['company_id'];

            if ($from == $contact_email) {

                createTicket($contact_id, $contact_name, $contact_email, $client_id, $company_id, $date, $subject, $message);
                $email_processed = true;

            } else {

                // Couldn't match this email to an existing ticket or an existing client contact
                // Checking to see if the sender domain matches a client website

                $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT * FROM clients WHERE client_website = '$domain' LIMIT 1"));

                if ($row && $domain == $row['client_website']) {

                    // We found a match - create a contact under this client and raise a ticket for them

                    // Client details
                    $client_id = $row['client_id'];
                    $company_id = $row['company_id'];

                    // Contact details
                    $password = password_hash(randomString(), PASSWORD_DEFAULT);
                    $contact_name = $from_name;
                    $contact_email = $from;
                    mysqli_query($mysqli,"INSERT INTO contacts SET contact_name = '$contact_name', contact_email = '$contact_email', contact_notes = 'Added automatically via email parsing.', contact_password_hash = '$password', contact_client_id = $client_id, company_id = $company_id");
                    $contact_id = mysqli_insert_id($mysqli);

                    // Logging for contact creation
                    echo "Created new contact.<br>";
                    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Contact', log_action = 'Create', log_description = 'Email parser: created contact $contact_name', log_client_id = $client_id, company_id = $company_id");

                    createTicket($contact_id, $contact_name, $contact_email, $client_id, $company_id, $date, $subject, $message);

                    $email_processed = true;

                } else {

                    // Couldn't match this email to an existing ticket, existing contact or an existing client via the "from" domain
                    //  In the future we might make a page where these can be nicely viewed / managed, but for now we'll just flag them as needing attention

                }

            }

        }

        // Deal with the message
        if ($email_processed) {
            imap_mail_move($imap, $email, $imap_folder);
        } else {
            imap_setflag_full($imap, $email, "\\Flagged");
        }

    }


}


imap_expunge($imap);
imap_close($imap);
