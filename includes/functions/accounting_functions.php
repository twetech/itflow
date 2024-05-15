<?php 

// Accounting related functions

function getMonthlyTax($tax_name, $month, $year, $mysqli)
{
    // SQL to calculate monthly tax
    $sql = "SELECT SUM(item_tax) AS monthly_tax FROM invoice_items 
            LEFT JOIN invoices ON invoice_items.item_invoice_id = invoices.invoice_id
            LEFT JOIN payments ON invoices.invoice_id = payments.payment_invoice_id
            WHERE YEAR(payments.payment_date) = $year AND MONTH(payments.payment_date) = $month
            AND invoice_items.item_tax_id = (SELECT tax_id FROM taxes WHERE tax_name = '$tax_name')";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['monthly_tax'] ?? 0;
}

function getQuarterlyTax($tax_name, $quarter, $year, $mysqli)
{
    // Calculate start and end months for the quarter
    $start_month = ($quarter - 1) * 3 + 1;
    $end_month = $start_month + 2;

    // SQL to calculate quarterly tax
    $sql = "SELECT SUM(item_tax) AS quarterly_tax FROM invoice_items 
            LEFT JOIN invoices ON invoice_items.item_invoice_id = invoices.invoice_id
            LEFT JOIN payments ON invoices.invoice_id = payments.payment_invoice_id
            WHERE YEAR(payments.payment_date) = $year AND MONTH(payments.payment_date) BETWEEN $start_month AND $end_month
            AND invoice_items.item_tax_id = (SELECT tax_id FROM taxes WHERE tax_name = '$tax_name')";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['quarterly_tax'] ?? 0;
}

function getTotalTax($tax_name, $year, $mysqli)
{
    // SQL to calculate total tax
    $sql = "SELECT SUM(item_tax) AS total_tax FROM invoice_items 
            LEFT JOIN invoices ON invoice_items.item_invoice_id = invoices.invoice_id
            LEFT JOIN payments ON invoices.invoice_id = payments.payment_invoice_id
            WHERE YEAR(payments.payment_date) = $year
            AND invoice_items.item_tax_id = (SELECT tax_id FROM taxes WHERE tax_name = '$tax_name')";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total_tax'] ?? 0;
}

function getMonthlyIncome($year, $month)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(payment_date) = $month";

    $sql = "SELECT SUM(payment_amount) AS total_income FROM payments WHERE YEAR(payment_date) = $year $sql_month_query";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return floatval($row['total_income']) ?? 0;
}

function getMonthlyPayments($year, $month)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(payment_date) = $month";

    $sql = "SELECT COUNT(payment_id) AS number_payments FROM payments WHERE YEAR(payment_date) = $year $sql_month_query";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return intval($row['number_payments']) ?? 0;
}

function getMonthlyReceivables($year, $month)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(invoice_date) = $month";

    $sql = "SELECT SUM(invoice_amount) AS total_receivables FROM invoices WHERE YEAR(invoice_date) = $year $sql_month_query";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return floatval($row['total_receivables']) ?? 0;
}

function getMonthlyOutstandingInvoices($year, $month)
{
    global $mysqli;

    // Corrected typo in the SQL query for 'invoice status' to 'invoice_status'
    $sql_month_query = $month == 13 ? "" : "AND MONTH(invoice_date) = $month";

    $sql = "SELECT COUNT(invoice_id) AS number_outstanding_invoices FROM invoices WHERE YEAR(invoice_date) = $year AND (invoice_status = 'Unpaid' OR invoice_status = 'Partial') $sql_month_query";
    $result = mysqli_query($mysqli, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return intval($row['number_outstanding_invoices']);
    }

    // Return 0 if the query fails or no result is found
    return 0;
}

function getUnbilledHours($year, $month)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(ticket_created_at) = $month";

    $sql = "SELECT SUM(ticket_reply_time_worked) AS total_unbilled_hours FROM ticket_replies
        LEFT JOIN tickets ON ticket_replies.ticket_reply_ticket_id = tickets.ticket_id
        WHERE YEAR(ticket_created_at) = $year
        AND ticket_status = 5
        AND ticket_billable = '1'
        AND ticket_invoice_id IS NULL $sql_month_query
    ";

    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return floatval($row['total_unbilled_hours']) ?? 0;
}

function getMonthlyProfit($year, $month)
{
    global $mysqli;

    $sql_payment_month_query = $month == 13 ? "" : "AND MONTH(payment_date) = $month";
    $sql_expense_month_query = $month == 13 ? "" : "AND MONTH(expense_date) = $month";


    $payment_sql = "SELECT SUM(payment_amount) AS total_income FROM payments WHERE YEAR(payment_date) = $year $sql_payment_month_query";
    $payment_result = mysqli_query($mysqli, $payment_sql);
    $payment_row = mysqli_fetch_assoc($payment_result);
    $total_income = floatval($payment_row['total_income']) ?? 0;

    $expense_sql = "SELECT SUM(expense_amount) AS total_expenses FROM expenses WHERE YEAR(expense_date) = $year $sql_expense_month_query";
    $expense_result = mysqli_query($mysqli, $expense_sql);
    $expense_row = mysqli_fetch_assoc($expense_result);
    $total_expenses = floatval($expense_row['total_expenses']) ?? 0;

    return $total_income - $total_expenses;
}

//Get account currency code
function getAccountCurrencyCode($account_id)
{
    global $mysqli;

    $sql = mysqli_query($mysqli, "SELECT account_currency_code FROM accounts WHERE account_id = $account_id");
    $row = mysqli_fetch_array($sql);
    $account_currency_code = nullable_htmlentities($row['account_currency_code']);
    return $account_currency_code;
}

function calculateAccountBalance($account_id)
{
    global $mysqli;

    $sql_account = mysqli_query($mysqli, "SELECT * FROM accounts LEFT JOIN account_types ON accounts.account_type = account_types.account_type_id WHERE account_archived_at  IS NULL AND account_id = $account_id ORDER BY account_name ASC; ");
    $row = mysqli_fetch_array($sql_account);
    $opening_balance = floatval($row['opening_balance']);
    $account_id = intval($row['account_id']);

    $sql_payments = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS total_payments FROM payments WHERE payment_account_id = $account_id");
    $row = mysqli_fetch_array($sql_payments);
    $total_payments = floatval($row['total_payments']);

    $sql_revenues = mysqli_query($mysqli, "SELECT SUM(revenue_amount) AS total_revenues FROM revenues WHERE revenue_account_id = $account_id");
    $row = mysqli_fetch_array($sql_revenues);
    $total_revenues = floatval($row['total_revenues']);

    $sql_expenses = mysqli_query($mysqli, "SELECT SUM(expense_amount) AS total_expenses FROM expenses WHERE expense_account_id = $account_id");
    $row = mysqli_fetch_array($sql_expenses);
    $total_expenses = floatval($row['total_expenses']);

    $balance = $opening_balance + $total_payments + $total_revenues - $total_expenses;

    if ($balance == '') {
        $balance = '0.00';
    }

    if ($balance < 0) {
        $balance = 0;
    }

    return $balance;
}
function getClientRecurringInvoicesTotal($client_id)
{
    global $mysqli;

    $sql = "SELECT SUM(recurring_amount) AS recurring_total FROM recurring WHERE recurring_client_id = $client_id AND recurring_frequency = 'month'";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    $total = floatval($row['recurring_total']) ?? 0;

    $year_sql = "SELECT SUM(recurring_amount) AS recurring_total FROM recurring WHERE recurring_client_id = $client_id AND recurring_frequency = 'year'";
    $year_result = mysqli_query($mysqli, $year_sql);
    $year_row = mysqli_fetch_assoc($year_result);
    $year_total = floatval($year_row['recurring_total']) ?? 0;

    $monthly_total = ($year_total / 12) + $total;
    return $monthly_total;
}

function getClientBalance($client_id, $credits = false) {

    global $mysqli;

    //Add up all the payments for the invoice and get the total amount paid to the invoice
    $sql_invoice_amounts = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS invoice_amounts FROM invoices WHERE invoice_client_id = $client_id AND invoice_status NOT LIKE 'Draft' AND invoice_status NOT LIKE 'Cancelled'");
    $row = mysqli_fetch_array($sql_invoice_amounts);

    $invoice_amounts = floatval($row['invoice_amounts']);

    $sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments, invoices WHERE payment_invoice_id = invoice_id AND invoice_client_id = $client_id");
    $row = mysqli_fetch_array($sql_amount_paid);

    $amount_paid = floatval($row['amount_paid']);

    if ($credits) {
        $sql_credits = mysqli_query($mysqli, "SELECT SUM(credit_amount) AS credit_amounts FROM credits WHERE credit_client_id = $client_id");
        $row = mysqli_fetch_array($sql_credits);
        $credit_amounts = floatval($row['credit_amounts']);

        $balance = $invoice_amounts - ($amount_paid + $credit_amounts);

        if ($balance < 0) {
            $balance = 0;
        }
        return $balance;
    } else {
        $balance = $invoice_amounts - $amount_paid;

        if ($balance < 0) {
            $balance = 0;
        }
        return $balance;
    }
}

function getClientPastDueBalance($client_id, $credits = false) {

    global $mysqli;

     //Add up all the payments for the invoice and get the total amount paid to the invoice
    $sql_invoice_amounts = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS invoice_amounts FROM invoices WHERE invoice_client_id = $client_id AND invoice_status NOT LIKE 'Draft' AND invoice_status NOT LIKE 'Cancelled' AND invoice_due < CURDATE()");
    $row = mysqli_fetch_array($sql_invoice_amounts);

    $invoice_amounts = floatval($row['invoice_amounts']);

    $sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments, invoices WHERE payment_invoice_id = invoice_id AND invoice_client_id = $client_id");
    $row = mysqli_fetch_array($sql_amount_paid);

    $amount_paid = floatval($row['amount_paid']);

    if ($credits) {
        $sql_credits = mysqli_query($mysqli, "SELECT SUM(credit_amount) AS credit_amounts FROM credits WHERE credit_client_id = $client_id");
        $row = mysqli_fetch_array($sql_credits);
        $credit_amounts = floatval($row['credit_amounts']);

        $balance = $invoice_amounts - ($amount_paid + $credit_amounts);

    } else {
        $balance = $invoice_amounts - $amount_paid;
    }

    if ($balance < 0) {
        $balance = 0;
    }
    
    return $balance;
}

function getClientPastDueMonths($client_id) {

    $monthly = getClientRecurringInvoicesTotal($client_id);
    $balance = getClientPastDueBalance($client_id);

    if ($monthly == 0) {
        return 0;
    }

    return $balance / $monthly;

}

function getMonthlyExpenses($year, $month, $number = false)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(expense_date) = $month";

    if ($number) {
        $sql = "SELECT COUNT(expense_id) AS total_expenses FROM expenses WHERE YEAR(expense_date) = $year $sql_month_query";
    } else {
        $sql = "SELECT SUM(expense_amount) AS total_expenses FROM expenses WHERE YEAR(expense_date) = $year $sql_month_query";
    }

    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result); 
    return $row['total_expenses'] ?? 0;
}

function getMonthlyUnbilledHours($year, $month, $number = false)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(ticket_reply_created_at) = $month";

    $sql = 
    "SELECT SUM(ticket_reply_time_worked) AS total_unbilled_hours FROM ticket_replies
    WHERE YEAR(ticket_reply_created_at) = $year
    ";
    $sql .= $sql_month_query;
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    $number_unbilled = $row['total_unbilled_hours']/10000;

    if ($number) {
        return $number_unbilled;
    } else {
        return $number_unbilled * 125;
    }

}

function getMonthlyCalendarEvents($year, $month)
{
    return 0;
}

function getMonthlyUnassignedTickets($year, $month)
{
    return 0;
}

function getMonthlyInvoices($year, $month, $number = false)

{
    switch ($number) {
        case true:
            return getMonthlyInvoicesNumber($year, $month);
        case false:
            return getMonthlyInvoicesAmount($year, $month);
    }
}

function getMonthlyInvoicesAmount($year, $month)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(invoice_date) = $month";

    $sql = "SELECT SUM(invoice_amount) AS total_invoices FROM invoices WHERE YEAR(invoice_date) = $year $sql_month_query";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total_invoices'] ?? 0;
}

function getMonthlyInvoicesNumber($year, $month)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(invoice_date) = $month";

    $sql = "SELECT COUNT(invoice_id) AS number_invoices FROM invoices WHERE YEAR(invoice_date) = $year $sql_month_query";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['number_invoices'] ?? 0;
}

function getMonthlyMarkup($year, $month)
{
    global $mysqli;

    $sql_month_query = $month == 13 ? "" : "AND MONTH(invoice_date) = $month";

    $sql_invoices = "SELECT * FROM invoices WHERE YEAR(invoice_date) = $year $sql_month_query";
    $invoices_result = mysqli_query($mysqli, $sql_invoices);
    $invoices_row = mysqli_fetch_assoc($invoices_result);
    $total_amount = 0;
    $total_cost = 0;
    foreach ($invoices_result as $invoices_row) {
        $invoice_amount = $invoices_row['invoice_amount'];

        $total_amount += $invoice_amount;

        $sql_items = "SELECT * FROM invoice_items
        LEFT JOIN products ON invoice_items.item_product_id = products.product_id
        WHERE item_invoice_id = " . $invoices_row['invoice_id'];
        $items_result = mysqli_query($mysqli, $sql_items);
        foreach ($items_result as $items_row) {
            $total_cost += $items_row['product_cost'] * $items_row['item_quantity'];
        }
    }
    $total_cost == 0 ? $total_cost = 1 : $total_cost;
    
    return $total_amount / $total_cost;
}



function clientSendDisconnect($client_id){


    // Get the primary contact
    $sql_contact = mysqli_query($mysqli, "SELECT * FROM contacts WHERE contact_client_id = $client_id AND contact_primary = 1");
    $contact = mysqli_fetch_array($sql_contact);
    $contact_id = intval($contact['contact_id']);
    $contact_name = sanitizeInput($contact['contact_name']);
    $contact_email = sanitizeInput($contact['contact_email']);


    $past_due_invoices = [];
    $past_due_invoices_num = 0;

    $sql_past_due_invoices = mysqli_query($mysqli, "SELECT * FROM invoices WHERE invoice_status != 'Draft' AND invoice_status != 'Paid' AND invoice_status != 'Cancelled' AND invoice_client_id = $client_id AND invoice_due < CURDATE()");
    while ($row = mysqli_fetch_array($sql_past_due_invoices)) {
        $past_due_invoices[] = [
            'invoice_id' => intval($row['invoice_id']),
            'invoice_prefix' => sanitizeInput($row['invoice_prefix']),
            'invoice_number' => intval($row['invoice_number']),
            'invoice_scope' => sanitizeInput($row['invoice_scope']),
            'invoice_date' => sanitizeInput($row['invoice_date']),
            'invoice_due' => sanitizeInput($row['invoice_due']),
            'invoice_amount' => floatval($row['invoice_amount']),
            'invoice_currency_code' => sanitizeInput($row['invoice_currency_code']),
            'invoice_url_key' => sanitizeInput($row['invoice_url_key'])
        ];
        $past_due_invoices_num++;
    }
    // Send collections email
    $subject = "[URGENT] $company_name Collections Notice";
    $body = "Hello $client_name,<br><br>Our records indicate that you have $past_due_invoices_num past due invoices. This has become an urgent matter based on the status of your account. Please review the details below and make payment as soon as possible to avoid service interruption.<br><br>";
    $body .= '<table border="1"><tr><th>Invoice</th><th>Issue Date</th><th>Total</th><th>Due Date</th></tr>';
    foreach ($past_due_invoices as $invoice) {
        $body .= "<tr><td>$invoice[invoice_prefix]$invoice[invoice_number]</td><td>$invoice[invoice_date]</td><td>" . numfmt_format_currency($currency_format, $invoice['invoice_amount'], $invoice['invoice_currency_code']) . "</td><td>$invoice[invoice_due]</td></tr>";
    }
    $body .= "</table><br><br>";
    $body .= "To view your invoices, please click <a href='https://$config_base_url/portal/invoices.php'>here</a>.<br><br>";

    // Mysqli escape body

    $body = mysqli_real_escape_string($mysqli, $body);

    $data = [
        [
            'from' => $config_invoice_from_email,
            'from_name' => $config_invoice_from_name,
            'recipient' => $contact_email,
            'recipient_name' => $contact_name,
            'subject' => $subject,
            'body' => $body
        ]
    ];
    $mail = addToMailQueue($mysqli, $data);

    if ($mail === true) {
        mysqli_query($mysqli, "INSERT INTO history SET history_status = 'Sent', history_description = 'Collections Email Sent', history_client_id = $client_id");
        echo "Collections email sent to $contact_email\n";
    } else {
        mysqli_query($mysqli, "INSERT INTO history SET history_status = 'Draft', history_description = 'Collections Email Failed to Send', history_client_id = $client_id");

        mysqli_query($mysqli, "INSERT INTO notifications SET notification_type = 'Mail', notification = 'Failed to send email to $contact_email'");
        mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Mail', log_action = 'Error', log_description = 'Failed to send email to $contact_email regarding $subject. $mail'");
    }

    // Check if client has open collections ticket
    $sql_open_collections_ticket = mysqli_query($mysqli, "SELECT * FROM tickets WHERE ticket_client_id = $client_id AND ticket_status != 5 AND ticket_subject = 'Collections Notice'");
    if (mysqli_num_rows($sql_open_collections_ticket) == 0) {
        // Create a collections ticket
        $ticket_subject = "Collections Notice";
        $ticket_details = "This is a collections notice for $client_name. Please review the past due invoices and make payment as soon as possible to avoid service interruption.";
        $ticket_priority = "High";
        $ticket_client_id = $client_id;

        echo "Creating collections ticket for $client_name\n";

        $ticket_parameters = [
            'ticket_subject' => $ticket_subject,
            'ticket_details' => $ticket_details,
            'ticket_priority' => $ticket_priority,
            'ticket_client_id' => $ticket_client_id
        ];

        $ticket_id = createTicket($mysqli, $ticket_parameters)['ticket_id'];

        // Add notification for new collections ticket
        mysqli_query($mysqli, "INSERT INTO notifications SET notification_type = 'Collections Ticket Created', notification = 'Collections Notice Ticket Created for $client_name', notification_action = '/pages/ticket.php?id=$ticket_id', notification_client_id = $client_id, notification_entity_id = $ticket_id");
    }
}