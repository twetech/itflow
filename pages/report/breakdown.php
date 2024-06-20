<?php
// Include your database setup
require_once "/var/www/portal.twe.tech/includes/inc_all_reports.php";



// Get parameters
$month = (int)$_GET['month'];
$year = (int)$_GET['year'];
$tax_name = isset($_GET['tax_name']) ? mysqli_real_escape_string($mysqli, $_GET['tax_name']) : null;

$total_payments = 0;
$payments_added = [];

$total_fractional_payment = 0;
$total_tax_owed = 0;
$invoice_fractionals = [];
$invoice_taxes = [];
$invoice_payments = []; // New array to track payments per invoice

$company_currency = getSettingValue('company_currency');
$currency_format = numfmt_create('en_US', NumberFormatter::CURRENCY);

if ($tax_name) {
    $tax_query = "AND taxes.tax_name = '$tax_name'";
} else {
    $tax_query = "";
    
    // Get all tax names
    $sql = "SELECT DISTINCT tax_name FROM taxes";
    $result = mysqli_query($mysqli, $sql);
    $tax_names = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tax_names[] = $row['tax_name'];
        }
    }
}

$sql = "
    SELECT
        *
    FROM
        payments
    LEFT JOIN
        invoices ON payments.payment_invoice_id = invoices.invoice_id
    LEFT JOIN
        invoice_items ON invoices.invoice_id = invoice_items.item_invoice_id
    LEFT JOIN
        taxes ON invoice_items.item_tax_id = taxes.tax_id
    LEFT JOIN
        clients ON invoices.invoice_client_id = clients.client_id
    WHERE
        MONTH(payments.payment_date) = $month AND
        YEAR(payments.payment_date) = $year
        $tax_query
    ORDER BY payments.payment_id, invoices.invoice_id, invoice_items.item_id
";

$result = mysqli_query($mysqli, $sql);

$errors = [];

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Detailed Breakdown for $tax_name in " . date('F Y', strtotime($year . '-' . $month . '-01')) . "</h1>";
    echo "<table border='1' id='responsive' class='responsive table table-hover table-striped'>
    <tr>
        <th>Item ID</th>
        <th>Invoice ID</th>
        <th>Payment ID</th>
        <th>Tax Percent</th>
        <th>Invoice Amount</th>
        <th>Payment Amount</th>
        <th>Percent Paid</th>
        <th>Item Line Total</th>
        <th>Fractional Payment</th>
        <th>Tax Owed</th>
    </tr>";

    $debug_info = []; // Initialize debug info array

    while ($row = mysqli_fetch_assoc($result)) {
        $item_id = $row['item_id'];
        $invoice_id = $row['invoice_id'];
        $payment_id = $row['payment_id'];
        $invoice_amount = $row['invoice_amount'] + $row['invoice_discount_amount'];
        $payment_amount = $row['payment_amount'];
        $percent_paid = $invoice_amount > 0 ? $payment_amount / $invoice_amount : 0;
        $item_price = $row['item_price'];
        $item_quantity = $row['item_quantity'];
        $item_discount = $row['item_discount'];
        $item_total = ($item_price * $item_quantity) - $item_discount;
        $fractional_payment_amount = $item_total * $percent_paid;
        $tax_rate = $row['tax_percent'];
        $tax_owed = $fractional_payment_amount * $tax_rate / 100;

        // Check if payment has already been added to the total
        if (!in_array($payment_id, $payments_added)) {
            $total_payments += $payment_amount;
            $payments_added[] = $payment_id;

            // Track payments per invoice
            if (!isset($invoice_payments[$invoice_id])) {
                $invoice_payments[$invoice_id] = 0;
            }
            $invoice_payments[$invoice_id] += $payment_amount;
        }

        // Add to invoice fractionals and taxes arrays
        if (!isset($invoice_fractionals[$invoice_id])) {
            $invoice_fractionals[$invoice_id] = 0;
        }
        $invoice_fractionals[$invoice_id] += $fractional_payment_amount;

        if (!isset($invoice_taxes[$invoice_id])) {
            $invoice_taxes[$invoice_id] = 0;
        }
        $invoice_taxes[$invoice_id] += $tax_owed;

        // Accumulate totals
        $total_fractional_payment += $fractional_payment_amount;
        $total_tax_owed += $tax_owed;

        if ($tax_rate == 0) {
            $tax_display = "No Tax";
        } else {
            $tax_display = $row['tax_name'] . " (" . $tax_rate . "%)";
        }

        echo "<tr>
            <td>$item_id</td>
            <td><a href='/pages/invoice.php?invoice_id=$invoice_id'>$invoice_id</a></td>
            <td>$payment_id</td>
            <td>$tax_display</td>
            <td>" . numfmt_format_currency($currency_format, $invoice_amount, $company_currency) . "</td>
            <td>" . numfmt_format_currency($currency_format, $payment_amount, $company_currency) . "</td>
            <td>" . number_format($percent_paid * 100, 2) . "%</td>
            <td>" . numfmt_format_currency($currency_format, $item_total, $company_currency) . "</td>
            <td>" . numfmt_format_currency($currency_format, $fractional_payment_amount, $company_currency) . "</td>
            <td>" . numfmt_format_currency($currency_format, $tax_owed, $company_currency) . "</td>
        </tr>";

        // Store debug info
        $debug_info[] = [
            'payment_id' => $payment_id,
            'invoice_id' => $invoice_id,
            'item_id' => $item_id,
            'invoice_amount' => $invoice_amount,
            'payment_amount' => $payment_amount,
            'fractional_payment' => $fractional_payment_amount,
            'tax_owed' => $tax_owed
        ];
    }

    // Display totals at the bottom of the table
    echo "<tr>
        <td colspan='5'><b>Totals:</b></td>
        <td><b>" . numfmt_format_currency($currency_format, $total_payments, $company_currency) . "</b></td>
        <td></td>
        <td></td>
        <td><b>" . numfmt_format_currency($currency_format, $total_fractional_payment, $company_currency) . "</b></td>
        <td><b>" . numfmt_format_currency($currency_format, $total_tax_owed, $company_currency) . "</b></td>
    </tr>";
    echo "</table>";

    // Check discrepancies per invoice
    foreach ($invoice_payments as $invoice_id => $payment_total) {
        $fractional_total = $invoice_fractionals[$invoice_id];
        $tax_total = $invoice_taxes[$invoice_id];
        $combined_total = $fractional_total + $tax_total;
        
        if (abs($combined_total - $payment_total) > 1) {
            $errors[] = "Discrepancy for 
            Invoice ID = <a href='/pages/invoice.php?invoice_id=$invoice_id'>$invoice_id</a>,
            Payments Total = " . numfmt_format_currency($currency_format, $payment_total, $company_currency) . ", Fractional Total + Tax Total = " . numfmt_format_currency($currency_format, $combined_total, $company_currency);
        }
    }

    // Display any errors found
    if (!empty($errors) && $tax_name == null) {
        echo "<h2>Errors Found:</h2><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else {
        echo "<h2>No discrepancies found.</h2>";
    }
} else {
    echo "No results found.";
}

echo "<p>Total Fractional Payment + Total Tax Owed: " . numfmt_format_currency($currency_format, $total_fractional_payment + $total_tax_owed, $company_currency) . "</p>";

mysqli_close($mysqli);
?>
