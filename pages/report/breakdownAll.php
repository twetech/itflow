<?php
// Include your database setup
require_once "/var/www/portal.twe.tech/includes/inc_all_reports.php";

// Get parameters
$month = (int)$_GET['month'];
$year = (int)$_GET['year'];
$type = mysqli_real_escape_string($mysqli, $_GET['type']);

$company_currency = getSettingValue('company_currency');
$currency_format = numfmt_create('en_US', NumberFormatter::CURRENCY);

// Query to get detailed breakdown without filtering by tax name
$sql = "
    SELECT 
        invoices.invoice_id,
        payments.payment_id,
        clients.client_name,
        invoice_items.item_id,
        invoice_items.item_price,
        invoice_items.item_quantity,
        IFNULL(taxes.tax_name, 'No Tax') AS tax_name,
        IFNULL(taxes.tax_percent, 0) AS tax_percent,
        (invoice_items.item_price * invoice_items.item_quantity) AS item_total,
        ((invoice_items.item_price * invoice_items.item_quantity) / invoice_totals.total_amount) * payments.payment_amount AS fractional_payment_amount,
        (((invoice_items.item_price * invoice_items.item_quantity) / invoice_totals.total_amount) * payments.payment_amount * IFNULL(taxes.tax_percent, 0)) / 100 AS tax_owed
    FROM 
        payments
    LEFT JOIN 
        invoices ON payments.payment_invoice_id = invoices.invoice_id
    LEFT JOIN 
        (
            SELECT 
                item_invoice_id,
                SUM(item_price * item_quantity) AS total_amount
            FROM 
                invoice_items
            GROUP BY 
                item_invoice_id
        ) AS invoice_totals ON invoices.invoice_id = invoice_totals.item_invoice_id
    LEFT JOIN 
        invoice_items ON invoices.invoice_id = invoice_items.item_invoice_id
    LEFT JOIN 
        taxes ON invoice_items.item_tax_id = taxes.tax_id
    LEFT JOIN 
        clients ON invoices.invoice_client_id = clients.client_id
    WHERE 
        MONTH(payments.payment_date) = $month AND 
        YEAR(payments.payment_date) = $year
    ORDER BY clients.client_name, invoices.invoice_id, payments.payment_id, invoice_items.item_id
";

$result = mysqli_query($mysqli, $sql);

$total_item_price = 0;
$total_fractional_payment = 0;
$total_tax_owed = 0;

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Detailed Breakdown for All Taxes</h1>";
    echo "<table border='1'>
    <tr>
        <th>Invoice ID</th>
        <th>Payment ID</th>
        <th>Client Name</th>
        <th>Item ID</th>
        <th>Item Total Price</th>
        <th>Tax Name</th>
        <th>Tax Percent</th>
        <th>Fractional Payment</th>
        <th>Tax Owed</th>
    </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $item_price = $row["item_price"];
        $item_quantity = $row["item_quantity"];
        $tax_percent = $row["tax_percent"];
        $fractional_payment_amount = $row["fractional_payment_amount"];
        $tax_owed = $row["tax_owed"];

        // Accumulate totals
        $total_item_price += $item_price * $item_quantity;
        $total_fractional_payment += $fractional_payment_amount;
        $total_tax_owed += $tax_owed;

        echo "<tr>
            <td><a href='/pages/invoice.php?invoice_id=" . htmlspecialchars($row["invoice_id"]) . "'>" . htmlspecialchars($row["invoice_id"]) . "</a></td>
            <td><a href='/pages/payment.php?payment_id=" . htmlspecialchars($row["payment_id"]) . "'>" . htmlspecialchars($row["payment_id"]) . "</a></td>
            <td>" . htmlspecialchars($row["client_name"]) . "</td>
            <td>" . htmlspecialchars($row["item_id"]) . "</td>
            <td>" . numfmt_format_currency($currency_format, $item_price * $item_quantity, $company_currency) . "</td>
            <td>" . htmlspecialchars($row["tax_name"]) . "</td>
            <td>" . htmlspecialchars($tax_percent) . "%</td>
            <td>" . numfmt_format_currency($currency_format, $fractional_payment_amount, $company_currency) . "</td>
            <td>" . numfmt_format_currency($currency_format, $tax_owed, $company_currency) . "</td>
        </tr>";
    }

    // Display totals at the bottom of the table
    echo "<tr>
        <td colspan='5'><strong>Total</strong></td>
        <td></td>
        <td></td>
        <td><strong>" . numfmt_format_currency($currency_format, $total_fractional_payment, $company_currency) . "</strong></td>
        <td><strong>" . numfmt_format_currency($currency_format, $total_tax_owed, $company_currency) . "</strong></td>
    </tr>";
    echo "</table>";
} else {
    echo "No results found.";
}
echo "<p>Total Fractional Payment + Total Tax Owed: " . numfmt_format_currency($currency_format, $total_fractional_payment + $total_tax_owed, $company_currency) . "</p>";

mysqli_close($mysqli);
?>
