<?php

// Default Column Sortby/Order Filter
$sort = "expense_date";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";

//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM expenses
    LEFT JOIN categories ON expense_category_id = category_id
    LEFT JOIN vendors ON expense_vendor_id = vendor_id
    LEFT JOIN accounts ON expense_account_id = account_id
    LEFT JOIN clients ON expense_client_id = client_id
    WHERE expense_vendor_id > 0
    AND (vendor_name LIKE '%$q%' OR client_name LIKE '%$q%' OR category_name LIKE '%$q%' OR account_name LIKE '%$q%' OR expense_description LIKE '%$q%' OR expense_amount LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('recurring_expense_id') AS num FROM recurring_expenses WHERE recurring_expense_archived_at IS NULL"));
$recurring_expense_count = $row['num'];
?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-shopping-cart mr-2"></i>Expenses</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="expense_add_modal.php"><i class="fas fa-plus mr-2"></i>New Expense</button>
            </div>
        </div>
        <div class="card-body">
            <form id="bulkActions" action="/post.php" method="post">
                <div class="card-datatable table-responsive pt-0">                      
                    <table class="datatables-basic table border-top">
                        <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                        <tr>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=expense_date&order=<?= $disp; ?>">Date</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=vendor_name&order=<?= $disp; ?>">Vendor</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=category_name&order=<?= $disp; ?>">Category</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=expense_description&order=<?= $disp; ?>">Description</a></th>
                            <th class="text-right"><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=expense_amount&order=<?= $disp; ?>">Amount</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=account_name&order=<?= $disp; ?>">Account</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=client_name&order=<?= $disp; ?>">Client</a></th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($row = mysqli_fetch_array($sql)) {
                            $expense_id = intval($row['expense_id']);
                            $expense_date = nullable_htmlentities($row['expense_date']);
                            $expense_amount = floatval($row['expense_amount']);
                            $expense_currency_code = nullable_htmlentities($row['expense_currency_code']);
                            $expense_description = nullable_htmlentities($row['expense_description']);
                            $expense_receipt = nullable_htmlentities($row['expense_receipt']);
                            $expense_reference = nullable_htmlentities($row['expense_reference']);
                            $expense_created_at = nullable_htmlentities($row['expense_created_at']);
                            $expense_vendor_id = intval($row['expense_vendor_id']);
                            $vendor_name = nullable_htmlentities($row['vendor_name']);
                            $expense_category_id = intval($row['expense_category_id']);
                            $category_name = nullable_htmlentities($row['category_name']);
                            $account_name = nullable_htmlentities($row['account_name']);
                            $expense_account_id = intval($row['expense_account_id']);
                            $client_name = nullable_htmlentities($row['client_name']);
                            if(empty($client_name)) {
                                $client_name_display = "-";
                            } else {
                                $client_name_display = $client_name;
                            }
                            $expense_client_id = intval($row['expense_client_id']);

                            if (empty($expense_receipt)) {
                                $receipt_attached = "";
                            } else {
                                $receipt_attached = "<a class='text-secondary mr-2' target='_blank' href=/var/www/portal.twe.tech/uploads/expenses/$expense_receipt' download='$expense_date-$vendor_name-$category_name-$expense_id.pdf'><i class='fa fa-file-pdf'></i></a>";
                            }

                            ?>

                            <tr>
                                <td><?= $receipt_attached; ?> <a class="text-dark" href="#" title="Created: <?= $expense_created_at; ?>" data-bs-toggle="modal" data-bs-target="#editExpenseModal<?= $expense_id; ?>"><?= $expense_date; ?></a></td>
                                <td><?= $vendor_name; ?></td>
                                <td><?= $category_name; ?></td>
                                <td><?= truncate($expense_description, 50); ?></td>
                                <td class="text-bold text-right"><?= numfmt_format_currency($currency_format, $expense_amount, $expense_currency_code); ?></td>
                                <td><?= $account_name; ?></td>
                                <td><?= $client_name_display; ?></td>
                                <td>
                                    <div class="dropdown dropleft text-center">
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <?php
                                            if (!empty($expense_receipt)) { ?>
                                                <a class="dropdown-item" href="<?= "/uploads/expenses/$expense_receipt"; ?>" download="<?= "$expense_date-$vendor_name-$category_name-$expense_id.pdf"; ?>">
                                                    <i class="fas fa-fw fa-download mr-2"></i>Download
                                                </a>
                                                <div class="dropdown-divider"></div>
                                            <?php } ?>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editExpenseModal<?= $expense_id; ?>">
                                                <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addExpenseCopyModal<?= $expense_id; ?>">
                                                <i class="fas fa-fw fa-copy mr-2"></i>Copy
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addExpenseRefundModal<?= $expense_id; ?>">
                                                <i class="fas fa-fw fa-undo-alt mr-2"></i>Refund
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_expense=<?= $expense_id; ?>">
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
            </form>
            <?php  ?>
        </div>
    </div>

<script src="/includes/js/bulk_actions.js"></script>

<?php
require_once "/var/www/portal.twe.tech/includes/footer.php";
