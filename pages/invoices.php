<?php

// Default Column Sortby/Order Filter
$sort = "invoice_number";
$order = "DESC";
require_once "/var/www/nestogy.io/includes/inc_all.php";

$datatable_order = '[[5, "desc"]]';
$datatable_settings = ",
    columnDefs: [
        {
            targets: [5, 6],
            render: DataTable.render.datetime('Do MMM YYYY')
        },
    ],
    createdRow: function(row, data, dataIndex) {
        // Assuming that the due date is in the 6th column (index 5)
        var dueDateStr = data[5]; // Get the date string from the data array
        var dueDate = new Date(dueDateStr); // Convert string to date
        var now = new Date(); // Get today's date
        now.setHours(0, 0, 0, 0); // Normalize the time to 00:00:00

        // If the due date is earlier than today, color the fifth column red
        if (dueDate < now) {
            $(row).find('td').eq(5).css('color', 'red');
        }
    }

    ";

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('recurring_id') AS num FROM recurring WHERE recurring_archived_at IS NULL"));
$recurring_invoice_count = $row['num'];

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('invoice_id') AS num FROM invoices WHERE invoice_status = 'Sent'"));
$sent_count = $row['num'];

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('invoice_id') AS num FROM invoices WHERE invoice_status = 'Viewed'"));
$viewed_count = $row['num'];

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('invoice_id') AS num FROM invoices WHERE invoice_status = 'Partial'"));
$partial_count = $row['num'];

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('invoice_id') AS num FROM invoices WHERE invoice_status = 'Draft'"));
$draft_count = $row['num'];

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('invoice_id') AS num FROM invoices WHERE invoice_status = 'Cancelled'"));
$cancelled_count = $row['num'];

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('invoice_id') AS num FROM invoices WHERE invoice_status NOT LIKE 'Draft' AND invoice_status NOT LIKE 'Paid' AND invoice_status NOT LIKE 'Cancelled' AND invoice_due < CURDATE()"));
$overdue_count = $row['num'];

$sql_total_draft_amount = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS total_draft_amount FROM invoices WHERE invoice_status = 'Draft'");
$row = mysqli_fetch_array($sql_total_draft_amount);
$total_draft_amount = floatval($row['total_draft_amount']);

$sql_total_sent_amount = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS total_sent_amount FROM invoices WHERE invoice_status = 'Sent'");
$row = mysqli_fetch_array($sql_total_sent_amount);
$total_sent_amount = floatval($row['total_sent_amount']);

$sql_total_viewed_amount = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS total_viewed_amount FROM invoices WHERE invoice_status = 'Viewed'");
$row = mysqli_fetch_array($sql_total_viewed_amount);
$total_viewed_amount = floatval($row['total_viewed_amount']);

$sql_total_cancelled_amount = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS total_cancelled_amount FROM invoices WHERE invoice_status = 'Cancelled'");
$row = mysqli_fetch_array($sql_total_cancelled_amount);
$total_cancelled_amount = floatval($row['total_cancelled_amount']);

$sql_total_partial_amount = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS total_partial_amount FROM payments, invoices WHERE payment_invoice_id = invoice_id AND invoice_status = 'Partial'");
$row = mysqli_fetch_array($sql_total_partial_amount);
$total_partial_amount = floatval($row['total_partial_amount']);
$total_partial_count = mysqli_num_rows($sql_total_partial_amount);

$sql_total_overdue_partial_amount = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS total_overdue_partial_amount FROM payments, invoices WHERE payment_invoice_id = invoice_id AND invoice_status = 'Partial' AND invoice_due < CURDATE()");
$row = mysqli_fetch_array($sql_total_overdue_partial_amount);
$total_overdue_partial_amount = floatval($row['total_overdue_partial_amount']);

$sql_total_overdue_amount = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS total_overdue_amount FROM invoices WHERE invoice_status NOT LIKE 'Draft' AND invoice_status NOT LIKE 'Paid' AND invoice_status NOT LIKE 'Cancelled' AND invoice_due < CURDATE()");
$row = mysqli_fetch_array($sql_total_overdue_amount);
$total_overdue_amount = floatval($row['total_overdue_amount']);

$real_overdue_amount = $total_overdue_amount - $total_overdue_partial_amount;
$total_unpaid_amount = $total_sent_amount + $total_viewed_amount + $total_partial_amount;
$unpaid_count = $sent_count + $viewed_count + $partial_count;

$overdue_query = "";
$status_query = "";

//Invoice status from GET
if (isset($_GET['status']) && ($_GET['status']) == 'Draft') {
    $status_query = "invoice_status = 'Draft'";
} elseif (isset($_GET['status']) && ($_GET['status']) == 'Unpaid') {
    $status_query = "invoice_status = 'Sent' OR invoice_status = 'Viewed' OR invoice_status = 'Partial'";
} elseif (isset($_GET['status']) && ($_GET['status']) == 'Overdue') {
    $status_query = "invoice_status = 'Sent' OR invoice_status = 'Viewed' OR invoice_status = 'Partial'";
    $overdue_query = "AND (invoice_due < CURDATE())";
} else {
    $status_query = "invoice_status LIKE '%'";
}

//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM invoices
    LEFT JOIN clients ON invoice_client_id = client_id
    LEFT JOIN categories ON invoice_category_id = category_id
    WHERE ($status_query)
    $overdue_query"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

<div class="col-12">
        <div class="card mb-4">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-12 col-lg-4">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3><?php echo numfmt_format_currency($currency_format, $total_unpaid_amount, $session_company_currency); ?></h3>
                                    <a><?php echo $unpaid_count; ?> Unpaid</a>
                                </div>
                                <a href="/pages/invoices.php?status=Unpaid" class="badge bg-label-secondary rounded p-2 me-sm-4">
                                    <i class="bx bx-dollar
                                    bx-sm"></i>
                                </a>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3><?php echo numfmt_format_currency($currency_format, $total_overdue_amount, $session_company_currency); ?></h3>
                                    <p><?php echo $overdue_count; ?> Overdue</p>
                                </div>
                                <a href="/pages/invoices.php?status=Overdue" class="badge bg-label-secondary rounded p-2 me-sm-4">
                                    <i class="bx bx-time
                                     bx-sm"></i>
                                </a>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 pb-3 pb-sm-0">
                                <div>
                                    <h3><?php echo numfmt_format_currency($currency_format, $total_draft_amount, $session_company_currency); ?></h3>
                                    <p><?php echo $draft_count; ?> Draft</p>
                                </div>
                                <a href="/pages/invoices.php?status=Draft" class="badge bg-label-secondary rounded p-2 me-sm-4">
                                    <i class="bx bx-pencil
                                    bx-sm"></i>
                                </a>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fa fa-fw fa-file-invoice mr-2"></i>Invoices</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="invoice_add_modal.php"><i class="fas fa-plus mr-2"></i>New Invoice</button>
            </div>
        </div>

        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
                <table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                        <tr>
                            <th>Number</th>
                            <th>Scope</th>
                            <th>Client</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Balance</th>
                            <th>Date</th>
                            <th>Due</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $invoice_id = intval($row['invoice_id']);
                        $invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
                        $invoice_number = nullable_htmlentities($row['invoice_number']);
                        $invoice_scope = nullable_htmlentities($row['invoice_scope']);
                        if (empty($invoice_scope)) {
                            $invoice_scope_display = "-";
                        } else {
                            $invoice_scope_display = $invoice_scope;
                        }
                        $invoice_status = nullable_htmlentities($row['invoice_status']);
                        $invoice_date = nullable_htmlentities($row['invoice_date']);
                        $invoice_due = nullable_htmlentities($row['invoice_due']);
                        $invoice_discount = floatval($row['invoice_discount_amount']);
                        $invoice_amount = floatval($row['invoice_amount']);
                        $invoice_currency_code = nullable_htmlentities($row['invoice_currency_code']);
                        $invoice_created_at = nullable_htmlentities($row['invoice_created_at']);
                        $client_id = intval($row['client_id']);
                        $client_name = nullable_htmlentities($row['client_name']);
                        $category_id = intval($row['category_id']);
                        $category_name = nullable_htmlentities($row['category_name']);
                        $client_currency_code = nullable_htmlentities($row['client_currency_code']);
                        $client_net_terms = intval($row['client_net_terms']);
                        if ($client_net_terms == 0) {
                            $client_net_terms = $config_default_net_terms;
                        }

                        $now = time();

                        if (($invoice_status == "Sent" || $invoice_status == "Partial" || $invoice_status == "Viewed") && strtotime($invoice_due) + 86400 < $now) {
                            $overdue_color = "text-danger font-weight-bold";
                        } else {
                            $overdue_color = "";
                        }

                        $invoice_badge_color = getInvoiceBadgeColor($invoice_status);

                        $invoice_balance = getInvoiceBalance($invoice_id);
                        ?>

                        <tr>
                            <td class="text-bold"><a href="invoice.php?invoice_id=<?php echo $invoice_id; ?>"><?php echo "$invoice_number"; ?></a></td>
                            <td><?php echo $invoice_scope_display; ?></td>
                            <td class="text-bold"><a href="/pages/client/client_invoices.php?client_id=<?php echo $client_id; ?>"><?php echo $client_name; ?></a></td>
                            <td class="text-bold text-right"><?php echo numfmt_format_currency($currency_format, $invoice_amount, $invoice_currency_code); ?></td>
                            <td class="text-bold text-right"><?php echo numfmt_format_currency($currency_format, $invoice_balance, $invoice_currency_code); ?></td>
                            <td><?php echo $invoice_date; ?></td>
                            <td class="<?php echo $overdue_color; ?>"><?php echo $invoice_due; ?></td>
                            <td>
                              <span class="p-2 badge bg-label-<?php echo $invoice_badge_color; ?>">
                                  <?php echo $invoice_status; ?>
                              </span>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php 
 ?>
        </div>
    </div>

<?php

require_once "/var/www/nestogy.io/includes/footer.php";

