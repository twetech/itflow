<?php
require_once "/var/www/portal.twe.tech/includes/inc_all_reports.php";

ini_set(
    'display_errors',
    1
);
ini_set(
    'display_startup_errors',
    1
);
error_reporting(
    E_ALL
);

$invoices_updated = [];

$sql = "SELECT * FROM
    invoice_items
    LEFT JOIN
    taxes ON invoice_items.item_tax_id = taxes.tax_id
    ";

$result = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $invoice_items[] = $row;
    }
}

foreach ($invoice_items as $invoice_item) {
    $item_id = $invoice_item['item_id'];
    $item_quantity = $invoice_item['item_quantity'];
    $item_price = $invoice_item['item_price'];
    $tax_percent = $invoice_item['tax_percent'];
    $item_discount = $invoice_item['item_discount'];
    $item_invoice_id = $invoice_item['item_invoice_id'];

    $item_sub_total = ($item_quantity * $item_price) - $item_discount;
    $tax_owed = $item_sub_total * $tax_percent / 100;
    $item_total = $item_sub_total + $tax_owed;

    $sql = "UPDATE invoice_items SET
        item_subtotal = $item_sub_total,
        item_tax = $tax_owed,
        item_total = $item_total
        WHERE
        item_id = $item_id
        ";
    echo $sql . "<br>";
    $result = mysqli_query($mysqli, $sql);
    $invoices_updated[$item_invoice_id] = true;
}

foreach ($invoices_updated as $invoice_id => $value) {
    $sql = "SELECT
        SUM(item_subtotal) AS invoice_subtotal,
        SUM(item_tax) AS invoice_tax,
        SUM(item_total) AS invoice_total
        FROM invoice_items
        WHERE item_invoice_id = $invoice_id
        ";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);

    $invoice_subtotal = $row['invoice_subtotal'];
    $invoice_tax = $row['invoice_tax'];
    $invoice_total = $row['invoice_total'];

    $sql = "UPDATE invoices SET
        invoice_amount = $invoice_total
        WHERE
        invoice_id = $invoice_id
        ";
    echo $sql . "<br>";
    $result = mysqli_query($mysqli, $sql);
}
