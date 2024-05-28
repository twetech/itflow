<?php


require_once "/var/www/portal.twe.tech/includes/inc_all.php";


if (isset($_GET['client_id'])) {

    $client_id = intval($_GET['client_id']);

    $sql_client_details = "
    SELECT
        client_name,
        client_type,
        client_website,
        client_net_terms
    FROM
        clients
    WHERE
        client_id = $client_id";

    $result_client_details = mysqli_query($mysqli, $sql_client_details);
    $row_client_details = mysqli_fetch_assoc($result_client_details);

    $client_name = nullable_htmlentities($row_client_details['client_name']);
    $client_type = nullable_htmlentities($row_client_details['client_type']);
    $client_website = nullable_htmlentities($row_client_details['client_website']);
    $client_net_terms = intval($row_client_details['client_net_terms']);
    $client_balance = getClientBalance($client_id);

    $sql_client_unpaid_invoices = "
    SELECT
        invoice_id,
        invoice_number,
        invoice_prefix,
        invoice_date,
        invoice_due,
        invoice_amount
    FROM
        invoices
    WHERE
        invoice_client_id = $client_id
        AND invoice_status NOT LIKE 'Draft'
        AND invoice_status NOT LIKE 'Cancelled'
        AND invoice_status NOT LIKE 'Paid'";

    $result_client_unpaid_invoices = mysqli_query($mysqli, $sql_client_unpaid_invoices);

    $currency_code = getSettingValue("company_currency");

    $transaction_invoices = [];

    ?>

    <ol class="breadcrumb d-print-none">
        <li class="breadcrumb-item">
            <a href="clients.php">Clients</a>
        </li>
        <li class="breadcrumb-item">
            <a href="client_invoices.php?client_id=<?= $client_id; ?>"><?= $client_name; ?></a>
        </li>
    </ol>

    <div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fas fa-fw fa-balance-scale mr-2"></i>Statement for <?= $client_name ?></h3>
        <div class="card-tools">
            <button type="button" class="btn btn-label-primary d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i>Print</button>
        </div>
    </div>
    <div class="card-body">
        <div>
            <div class="row">
                <div class="col-md-6">
                    <div class="me-3">
                        <h4>Client Details</h4>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td>Client Name:</td>
                                <td><?= $client_name; ?></td>
                            </tr>
                            <tr>
                                <td>Net Terms:</td>
                                <td><?= $client_net_terms; ?> days</td>
                            </tr>
                            <tr>
                                <td>Current Balance:</td>
                                <td><?= numfmt_format_currency($currency_format, $client_balance, $currency_code); ?></td>
                            </tr>
                            <tr>
                                <td>Past Due:</td>
                                <td><?= numfmt_format_currency($currency_format, getClientPastDueBalance($client_id), $currency_code); ?></td>
                            </tr>
                        </table>                        
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="me-3">
                        <h4>Unpaid Invoices</h4>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                            </tr>
                            <?php
                            while ($row = mysqli_fetch_assoc($result_client_unpaid_invoices)) {
                                $invoice_id = intval($row['invoice_id']);
                                
                                $invoice_number = nullable_htmlentities($row['invoice_number']);
                                $invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
                                $invoice_date = nullable_htmlentities($row['invoice_date']);
                                $invoice_due = nullable_htmlentities($row['invoice_due']);
                                $invoice_amount = nullable_htmlentities($row['invoice_amount']);
                                ?>
                                <tr>
                                    <td><a href="/pages/invoice.php?invoice_id=<?= $invoice_id; ?>"><?= $invoice_prefix . $invoice_number; ?></a></td>
                                    <td><?= $invoice_date; ?></td>
                                    <td><?= $invoice_due; ?></td>
                                    <td><?= numfmt_format_currency($currency_format, $invoice_amount, $currency_code); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>                        
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="m-3">
                        <h4>All Invoices and Associated Payments</h4>
                        <div class="card-datatable table-responsive  pt-0">                
                            <table class="datatables-basic table border-top">
                                <tr>
                                    <th hidden>Order</th>
                                    <th>Date</th>
                                    <th>Transaction</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                </tr>
                                <?php
                                $sql_client_transactions = "SELECT * FROM invoices 
                                    LEFT JOIN payments ON invoices.invoice_id = payments.payment_invoice_id
                                    WHERE invoices.invoice_client_id = $client_id
                                    AND invoices.invoice_status NOT LIKE 'Draft'
                                    AND invoices.invoice_status NOT LIKE 'Cancelled'
                                    ORDER BY invoices.invoice_date DESC";
                                $result_client_transactions = mysqli_query($mysqli, $sql_client_transactions);
                                $default_order = 0;
                                while ($row = mysqli_fetch_assoc($result_client_transactions)) {

                                    if (in_array($row['invoice_id'], $transaction_invoices)) {
                                        continue;
                                    } else {
                                        array_push($transaction_invoices, $row['invoice_id']);
                                    }

                                    $transaction_date = nullable_htmlentities($row['invoice_date']);
                                    $transaction_type = "Invoice" . " " . $row['invoice_prefix'] . $row['invoice_number'];
                                    $transaction_amount = floatval($row['invoice_amount']);
                                    $transaction_balance = getInvoiceBalance($row['invoice_id']);
                                    $transaction_due_date = $row['invoice_due'];

                                    if ($transaction_balance <= 0) {
                                        $transaction_balance = 0;
                                    }

                                    // IF due date has passed, add a warning class
                                    if ((strtotime($transaction_due_date) < strtotime(date("Y-m-d"))) && ($transaction_balance > 0)) {
                                        $transaction_balance = "<span class='text-danger'>" . numfmt_format_currency($currency_format, $transaction_balance, $currency_code) . "</span>";
                                    } else {
                                        $transaction_balance = numfmt_format_currency($currency_format, $transaction_balance, $currency_code);
                                    }
                                    $default_order ++;
                                    ?>
                                    <tr>
                                        <td hidden><?= $default_order; ?></td>
                                        <td><?= $transaction_date; ?></td>
                                        <td><?= $transaction_type; ?></td>
                                        <td><?= numfmt_format_currency($currency_format, $transaction_amount, $currency_code); ?></td>
                                        <td><?= $transaction_balance ?></td>
                                    </tr>
                                    <?php
                                    $payments = getPaymentsForInvoice($row['invoice_id']) ?? [];
                                    foreach ($payments as $payment) {
                                        $transaction_date = nullable_htmlentities($payment['payment_date']);
                                        $transaction_type = $payment['payment_method'];
                                        $transaction_amount = floatval($payment['payment_amount']) *-1;
                                        if ($payment['payment_menthod'] != "Stripe") {
                                            $transaction_type = $transaction_type. " " . $payment['payment_reference'];
                                        } else {
                                            $stripe_ref_last_4 = "...".substr($payment['payment_reference'], -4);
                                            $transaction_type = "Online Payment";
                                        }
                                        $default_order ++;
                                        ?>
                                        <tr class="small">
                                            <td hidden><?= $default_order; ?></td>
                                            <td><?= $transaction_date; ?></td>
                                            <td ><i class="bx bx-credit-card"></i>
                                                <?= $transaction_type; ?></td>
                                            <td><?= numfmt_format_currency($currency_format, $transaction_amount, $currency_code); ?></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                    }
                                }                                
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '/var/www/portal.twe.tech/includes/footer.php';

                        }
?>
