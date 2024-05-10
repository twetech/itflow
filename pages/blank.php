<?php
require "/var/www/portal.twe.tech/includes/inc_all.php";

$subject = "[URGENT] $company_name Collections Notice";
$body = "Hello $client_name,<br><br>Our records indicate that you have $past_due_invoices_num past due invoices. This has become an urgent matter based on the status of your account. Please review the details below and make payment as soon as possible to avoid service interruption.<br><br>";
$body .= "<table border='1'><tr><th>Invoice</th><th>Issue Date</th><th>Total</th><th>Due Date</th></tr>";
foreach ($past_due_invoices as $invoice) {
    $body .= "<tr><td>$invoice[invoice_prefix]$invoice[invoice_number]</td><td>$invoice[invoice_date]</td><td>" . numfmt_format_currency($currency_format, $invoice['invoice_amount'], $invoice['invoice_currency_code']) . "</td><td>$invoice[invoice_due]</td></tr>";
}
$body .= "</table><br><br>";
$body .= "To view your invoices, please click <a href='https://$config_base_url/portal/invoices.php'>here</a>.<br><br>";

echo $subject;
echo $body;


require "/var/www/portal.twe.tech/includes/footer.php";
