<?php

// Default Column Sortby Filter
$sort = "invoice_number";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM invoices
    LEFT JOIN categories ON invoice_category_id = category_id
    WHERE invoice_client_id = $client_id
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('recurring_id') AS num FROM recurring WHERE recurring_archived_at IS NULL AND recurring_client_id = $client_id"));
$recurring_invoice_count = $row['num'];

?>

<div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fa fa-fw fa-file-invoice mr-2"></i>Invoices</h3>
        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="invoice_add_modal.php?client_id=<?php echo $client_id; ?>"><i class="fas fa-plus mr-2"></i>New Invoice</button>
                <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exportInvoiceModal">
                        <i class="fa fa-fw fa-download mr-2"></i>Export
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form autocomplete="off">
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
            <div class="row">

                <div class="col-md-4">
                    <div class="input-group mb-3 mb-md-0">
                        <input type="search" class="form-control" name="q" value="<?php if (isset($q)) {
                                                                                        echo stripslashes(nullable_htmlentities($q));
                                                                                    } ?>" placeholder="Search Invoices">
                        <div class="input-group-append">
                            <button class="btn btn-dark"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="float-right">
                        <div class="btn-group float-right">
                            <a href="client_recurring_invoices.php?client_id=<?php echo $client_id; ?>" class="btn btn-label-primary"><i class="fa fa-fw fa-redo-alt mr-2"></i>Recurring | <b><?php echo $recurring_invoice_count; ?></b></a>
                            <?php if ($balance > 0) { ?>
                                <button type="button" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#addBulkPaymentModal"><i class="fa fa-credit-card mr-2"></i>Batch Payment</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>
        </form>
        <hr>
        <div class="card-datatable table-responsive container-fluid  pt-0">               
<table class="datatables-basic table border-top">
                <thead class="text-dark <?php if ($num_rows[0] == 0) {

                                            echo "d-none";
                                        } ?>">
                    <tr>
                        <th>Number</th>
                        <th>Scope</th>
                        <th class="text-right">Amount</th>
                        <th>Date</th>
                        <th>Due</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
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
                        $category_id = intval($row['category_id']);
                        $category_name = nullable_htmlentities($row['category_name']);

                        if (($invoice_status == "Sent" || $invoice_status == "Partial" || $invoice_status == "Viewed") && strtotime($invoice_due) < time()) {
                            $overdue_color = "text-danger font-weight-bold";
                        } else {
                            $overdue_color = "";
                        }

                        $invoice_badge_color = getInvoiceBadgeColor($invoice_status);

                    ?>
                        <tr>
                            <td class="text-bold"><a href="/pages/invoice.php?invoice_id=<?php echo $invoice_id; ?>"><?php echo "$invoice_prefix$invoice_number"; ?></a></td>
                            <td><?php echo $invoice_scope_display; ?></td>
                            <td class="text-bold text-right"><?php echo numfmt_format_currency($currency_format, $invoice_amount, $invoice_currency_code); ?></td>
                            <td><?php echo $invoice_date; ?></td>
                            <td>
                                <div class="<?php echo $overdue_color; ?>"><?php echo $invoice_due; ?></div>
                            </td>
                            <td><?php echo $category_name; ?></td>
                            <td>
                              <span class="p-2 badge bg-label-<?php echo $invoice_badge_color; ?>">
                                  <?php echo $invoice_status; ?>
                              </span>
                            </td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-label-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-plus"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php if (!empty($config_smtp_host)) { ?>
                                            <a class="dropdown-item" href="/post.php?email_invoice=<?php echo $invoice_id; ?>">
                                                <i class="fas fa-fw fa-paper-plane mr-2"></i>Send
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        <?php } ?>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editInvoiceModal<?php echo $invoice_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addInvoiceCopyModal<?php echo $invoice_id; ?>">
                                            <i class="fas fa-fw fa-copy mr-2"></i>Copy
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_invoice=<?php echo $invoice_id; ?>">
                                            <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    <?php

                    }

                    ?>

                </tbody>
            </table>
        </div>

    </div>
</div>

<?php


require_once '/var/www/portal.twe.tech/includes/footer.php';
