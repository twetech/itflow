<?php

// Default Column Sortby/Order Filter
$sort = "payment_date";
$order = "DESC";

require_once "/var/www/develop.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM payments
    LEFT JOIN invoices ON payment_invoice_id = invoice_id
    LEFT JOIN clients ON invoice_client_id = client_id
    LEFT JOIN accounts ON payment_account_id = account_id
    AND (CONCAT(invoice_prefix,invoice_number) LIKE '%$q%' OR client_name LIKE '%$q%' OR account_name LIKE '%$q%' OR payment_method LIKE '%$q%' OR payment_reference LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

// Credits SQL
$sql_credits = mysqli_query(
    $mysqli,
    "SELECT * FROM credits
    WHERE credit_archived_at IS NULL"
);

$credits_num_rows = mysqli_num_rows($sql_credits);
?>

<div class="card">
    <div class="card-header py-3">
        <h3 class="card-title"><i class="fas fa-fw fa-credit-card mr-2"></i>Payments</h3>
    </div>

    <div class="card-body">
        <div class="card-datatable table-responsive container-fluid  pt-0">               
            <table class="datatables-basic table border-top">
                <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark"
                                href="?<?php echo $url_query_strings_sort; ?>&sort=payment_date&order=<?php echo $disp; ?>">Payment
                                Date</a></th>
                        <th><a class="text-dark"
                                href="?<?php echo $url_query_strings_sort; ?>&sort=invoice_date&order=<?php echo $disp; ?>">Invoice
                                Date</a></th>
                        <th><a class="text-dark"
                                href="?<?php echo $url_query_strings_sort; ?>&sort=invoice_number&order=<?php echo $disp; ?>">Invoice</a>
                        </th>
                        <th><a class="text-dark"
                                href="?<?php echo $url_query_strings_sort; ?>&sort=client_name&order=<?php echo $disp; ?>">Client</a>
                        </th>
                        <th class="text-right"><a class="text-dark"
                                href="?<?php echo $url_query_strings_sort; ?>&sort=payment_amount&order=<?php echo $disp; ?>">Amount</a>
                        </th>
                        <th><a class="text-dark"
                                href="?<?php echo $url_query_strings_sort; ?>&sort=payment_method&order=<?php echo $disp; ?>">Payment
                                Method</a></th>
                        <th><a class="text-dark"
                                href="?<?php echo $url_query_strings_sort; ?>&sort=payment_reference&order=<?php echo $disp; ?>">Reference</a>
                        </th>
                        <th><a class="text-dark"
                                href="?<?php echo $url_query_strings_sort; ?>&sort=account_name&order=<?php echo $disp; ?>">Account</a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $invoice_id = intval($row['invoice_id']);
                        $invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
                        $invoice_number = intval($row['invoice_number']);
                        $invoice_status = nullable_htmlentities($row['invoice_status']);
                        $invoice_date = nullable_htmlentities($row['invoice_date']);
                        $payment_date = nullable_htmlentities($row['payment_date']);
                        $payment_method = nullable_htmlentities($row['payment_method']);
                        $payment_amount = floatval($row['payment_amount']);
                        $payment_currency_code = nullable_htmlentities($row['payment_currency_code']);
                        $payment_reference = nullable_htmlentities($row['payment_reference']);
                        if (empty($payment_reference)) {
                            $payment_reference_display = "-";
                        } else {
                            $payment_reference_display = $payment_reference;
                        }
                        $client_id = intval($row['client_id']);
                        $client_name = nullable_htmlentities($row['client_name']);
                        $account_name = nullable_htmlentities($row['account_name']);
                        $account_archived_at = nullable_htmlentities($row['account_archived_at']);
                        if (empty($account_archived_at)) {
                            $account_archived_display = "";
                        } else {
                            $account_archived_display = "Archived - ";
                        }

                        ?>

                    <tr>
                        <td><?php echo $payment_date; ?></td>
                        <td><?php echo $invoice_date; ?></td>
                        <td><a
                                href="invoice.php?invoice_id=<?php echo $invoice_id; ?>"><?php echo "$invoice_prefix$invoice_number"; ?></a>
                        </td>
                        <td><a
                                href="client_payments.php?client_id=<?php echo $client_id; ?>"><?php echo $client_name; ?></a>
                        </td>
                        <td class="text-right">
                            <?php echo numfmt_format_currency($currency_format, $payment_amount, $payment_currency_code); ?>
                        </td>
                        <td><?php echo $payment_method; ?></td>
                        <td><?php echo $payment_reference_display; ?></td>
                        <td><?php echo "$account_archived_display$account_name"; ?></td>
                    </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
        <?php require_once '/var/www/develop.twe.tech/includes/pagination.php';
 ?>
    </div>
</div>

<?php require_once '/var/www/develop.twe.tech/includes/footer.php';
 ?>
 