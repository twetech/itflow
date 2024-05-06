<?php

$sort = "transfer_date";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS transfer_created_at, expense_date AS transfer_date, expense_amount AS transfer_amount, expense_account_id AS transfer_account_from, revenue_account_id AS transfer_account_to, transfer_expense_id, transfer_revenue_id , transfer_id, transfer_method, transfer_notes FROM transfers, expenses, revenues
    WHERE transfer_expense_id = expense_id 
    AND transfer_revenue_id = revenue_id
    AND (transfer_notes LIKE '%$q%' OR transfer_method LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-exchange-alt mr-2"></i>Transfers</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="transfer_add_modal.php"><i class="fas fa-plus mr-2"></i>New Transfer</button>
            </div>
        </div>

        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=transfer_date&order=<?php echo $disp; ?>">Date</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=transfer_account_from&order=<?php echo $disp; ?>">From Account</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=transfer_account_to&order=<?php echo $disp; ?>">To Account</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=transfer_method&order=<?php echo $disp; ?>">Method</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=transfer_notes&order=<?php echo $disp; ?>">Notes</a></th>
                        <th class="text-right"><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=transfer_amount&order=<?php echo $disp; ?>">Amount</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $transfer_id = intval($row['transfer_id']);
                        $transfer_date = nullable_htmlentities($row['transfer_date']);
                        $transfer_account_from = intval($row['transfer_account_from']);
                        $transfer_account_to = intval($row['transfer_account_to']);
                        $transfer_amount = floatval($row['transfer_amount']);
                        $transfer_method = nullable_htmlentities($row['transfer_method']);
                        if($transfer_method) {
                            $transfer_method_display = $transfer_method;
                        } else {  
                            $transfer_method_display = "-";
                        }
                        $transfer_notes = nullable_htmlentities($row['transfer_notes']);
                        if(empty($transfer_notes)) {
                            $transfer_notes_display = "-";
                        } else {
                            $transfer_notes_display = nl2br($transfer_notes);
                        }
                        $transfer_created_at = nullable_htmlentities($row['transfer_created_at']);
                        $expense_id = intval($row['transfer_expense_id']);
                        $revenue_id = intval($row['transfer_revenue_id']);

                        $sql_from = mysqli_query($mysqli, "SELECT * FROM accounts WHERE account_id = $transfer_account_from");
                        $row = mysqli_fetch_array($sql_from);
                        $account_name_from = nullable_htmlentities($row['account_name']);
                        $account_from_archived_at = nullable_htmlentities($row['account_archived_at']);
                        if (empty($account_from_archived_at)) {
                            $account_from_archived_display = "";
                        } else {
                            $account_from_archived_display = "Archived - ";
                        }

                        $sql_to = mysqli_query($mysqli, "SELECT * FROM accounts WHERE account_id = $transfer_account_to");
                        $row = mysqli_fetch_array($sql_to);
                        $account_name_to = nullable_htmlentities($row['account_name']);
                        $account_to_archived_at = nullable_htmlentities($row['account_archived_at']);
                        if (empty($account_to_archived_at)) {
                            $account_to_archived_display = "";
                        } else {
                            $account_to_archived_display = "Archived - ";
                        }

                        ?>
                        <tr>
                            <td><a class="text-dark" href="#" data-bs-toggle="modal" data-bs-target="#editTransferModal<?php echo $transfer_id; ?>"><?php echo $transfer_date; ?></a></td>
                            <td><?php echo "$account_from_archived_display$account_name_from"; ?></td>
                            <td><?php echo "$account_to_archived_display$account_name_to"; ?></td>
                            <td><?php echo $transfer_method_display; ?></td>
                            <td><?php echo $transfer_notes_display; ?></td>
                            <td class="text-bold text-right"><?php echo numfmt_format_currency($currency_format, $transfer_amount, $session_company_currency); ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editTransferModal<?php echo $transfer_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_transfer=<?php echo $transfer_id; ?>">
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

