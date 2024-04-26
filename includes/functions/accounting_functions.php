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
        AND ticket_status = 'Closed'
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

function calculateInvoiceBalance($invoice_id)
{
    global $mysqli;

    $invoice_id_int = intval($invoice_id);
    $sql_invoice = mysqli_query($mysqli, "SELECT * FROM invoices WHERE invoice_id = $invoice_id_int");
    $row = mysqli_fetch_array($sql_invoice);
    $invoice_amount = floatval($row['invoice_amount']);

    $sql_payments = mysqli_query(
        $mysqli,
        "SELECT SUM(payment_amount) AS total_payments FROM payments
        WHERE payment_invoice_id = $invoice_id
        "
    );

    $row = mysqli_fetch_array($sql_payments);
    $total_payments = floatval($row['total_payments']);

    $balance = $invoice_amount - $total_payments;

    if ($balance == '') {
        $balance = '0.00';
    }

    return $balance;
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
    
    return $total_amount / $total_cost;
}
