<?php

// Default Column Sortby/Order Filter
$sort = "payment_date";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM payments
    LEFT JOIN invoices ON payment_invoice_id = invoice_id
    LEFT JOIN clients ON invoice_client_id = client_id
    LEFT JOIN accounts ON payment_account_id = account_id
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
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table border-top">
                <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th>Payment Date</th>
                        <th>Invoice Date</th>
                        <th>Invoice</th>
                        <th>Client </th>
                        <th class="text-right">Amount </th>
                        <th>Payment Method</th>
                        <th>Reference </th>
                        <th>Account </th>
                        <th>Actions</th>
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
                        $payment_id = intval($row['payment_id']);
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
                        <td><?= $payment_date; ?></td>
                        <td><?= $invoice_date; ?></td>
                        <td>
                            <a href="/pages/invoice.php?invoice_id=<?= $invoice_id; ?>">
                                <?= "$invoice_prefix$invoice_number"; ?>
                            </a>
                        </td>
                        <td>
                            <a href="/pages/client/client_payments.php?client_id=<?= $client_id; ?>">
                                <?= $client_name; ?>
                            </a>
                        </td>
                        <td class="text-right">
                            <?= numfmt_format_currency($currency_format, $payment_amount, $payment_currency_code); ?>
                        </td>
                        <td><?= $payment_method; ?></td>
                        <td><?= $payment_reference_display; ?></td>
                        <td><?= "$account_archived_display$account_name"; ?></td>
                        <td>
                            <?php if ($payment_method == "Stripe") { ?>
                                <a href="/post.php?stripe_payment_refund=<?= $payment_id; ?>" class="btn btn-sm btn-danger" title="Refund Payment">
                                    <i class="fas fa-fw fa-undo"></i>
                                </a>
                            <?php } else if ($payment_reference == "Credit Applied") { ?>
                                <a href="/post.php?credit_payment_refund=<?= $payment_id; ?>" class="btn btn-sm btn-danger" title="Refund Payment">
                                    <i class="fas fa-fw fa-undo"></i>
                                </a>
                            <?php } else { ?>
                                <a href="/post.php?payment_refund=<?= $payment_id; ?>" class="btn btn-sm btn-danger" title="Refund Payment">
                                    <i class="fas fa-fw fa-undo"></i>
                                </a>
                            <?php } ?>

                    </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '/var/www/portal.twe.tech/includes/footer.php';
 ?>
 