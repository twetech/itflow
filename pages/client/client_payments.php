<?php

// Default Column Sortby Filter
$sort = "payment_date";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM payments
    LEFT JOIN invoices ON payment_invoice_id = invoice_id
    LEFT JOIN accounts ON payment_account_id = account_id
    WHERE invoice_client_id = $client_id
    AND (CONCAT(invoice_prefix,invoice_number) LIKE '%$q%' OR account_name LIKE '%$q%' OR payment_method LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

<div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fa fa-fw fa-credit-card mr-2"></i>Payments</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#exportPaymentModal"><i class="fa fa-fw fa-download mr-2"></i>Export</button>
        </div>
    </div>
    <div class="card-body">
        <form autocomplete="off">
            <input type="hidden" name="client_id" value="<?= $client_id; ?>">
            <div class="row">

                <div class="col-md-4">
                    <div class="input-group mb-3 mb-md-0">
                        <input type="search" class="form-control" name="q" value="<?php if (isset($q)) { echo stripslashes(nullable_htmlentities($q)); } ?>" placeholder="Search Payments">
                        <div class="input-group-append">
                            <button class="btn btn-dark"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="float-right">
                    </div>
                </div>

            </div>
        </form>
        <hr>
        <div class="card-datatable table-responsive container-fluid  pt-0">               
<table class="datatables-basic table border-top">
                <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                <tr>
                    <th><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=payment_date&order=<?= $disp; ?>">Payment Date</a></th>
                    <th><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=invoice_date&order=<?= $disp; ?>">Invoice Date</a></th>
                    <th><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=invoice_number&order=<?= $disp; ?>">Invoice</a></th>
                    <th class="text-right"><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=invoice_amount&order=<?= $disp; ?>">Invoice Amount</a></th>
                    <th class="text-right"><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=payment_amount&order=<?= $disp; ?>">Payment Amount</a></th>
                    <th><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=payment_method&order=<?= $disp; ?>">Method</a></th>
                    <th><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=payment_reference&order=<?= $disp; ?>">Reference</a></th>
                    <th><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=account_name&order=<?= $disp; ?>">Account</a></th>
                </tr>
                </thead>
                <tbody>
                <?php

                while ($row = mysqli_fetch_array($sql)) {
                    $invoice_id = intval($row['invoice_id']);
                    $invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
                    $invoice_number = intval($row['invoice_number']);
                    $invoice_status = nullable_htmlentities($row['invoice_status']);
                    $invoice_amount = floatval($row['invoice_amount']);
                    $invoice_currency_code = nullable_htmlentities($row['invoice_currency_code']);
                    $invoice_date = nullable_htmlentities($row['invoice_date']);
                    $payment_date = nullable_htmlentities($row['payment_date']);
                    $payment_method = nullable_htmlentities($row['payment_method']);
                    $payment_reference = nullable_htmlentities($row['payment_reference']);
                    if (empty($payment_reference)) {
                        $payment_reference_display = "-";
                    } else {
                        $payment_reference_display = $payment_reference;
                    }
                    $payment_amount = floatval($row['payment_amount']);
                    $payment_currency_code = nullable_htmlentities($row['payment_currency_code']);
                    $account_name = nullable_htmlentities($row['account_name']);


                    ?>
                    <tr>
                        <td><?= $payment_date; ?></td>
                        <td><?= $invoice_date; ?></td>
                        <td class="text-bold"><a href="invoice.php?invoice_id=<?= $invoice_id; ?>"><?= "$invoice_prefix$invoice_number"; ?></a></td>
                        <td class="text-right"><?= numfmt_format_currency($currency_format, $invoice_amount, $invoice_currency_code); ?></td>
                        <td class="text-bold text-right"><?= numfmt_format_currency($currency_format, $payment_amount, $payment_currency_code); ?></td>
                        <td><?= $payment_method; ?></td>
                        <td><?= $payment_reference_display; ?></td>
                        <td><?= $account_name; ?></td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>

    </div>
</div>

<?php

require_once '/var/www/portal.twe.tech/includes/footer.php';

