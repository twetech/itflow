<?php

// Default Column Sortby Filter
$sort = "account_name";
$order = "ASC";

require_once "/var/www/develop.twe.tech/includes/inc_all.php";

//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM accounts
    LEFT JOIN account_types ON account_types.account_type_id = accounts.account_type 
    WHERE (account_name LIKE '%$q%' OR account_type_name LIKE '%$q%')
    AND account_archived_at IS NULL
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fa fa-fw fa-piggy-bank mr-2"></i>Accounts</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-soft-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="account_add_modal.php"><i class="fas fa-plus mr-2"></i>New Account</button>
            </div>
        </div>
        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=account_name&order=<?php echo $disp; ?>">Name</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=account_type_name&order=<?php echo $disp; ?>">Type</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=account_currency_code&order=<?php echo $disp; ?>">Currency</a></th>
                        <th class="text-right">Balance</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $account_id = intval($row['account_id']);
                        $account_name = nullable_htmlentities($row['account_name']);
                        $opening_balance = floatval($row['opening_balance']);
                        $account_currency_code = nullable_htmlentities($row['account_currency_code']);
                        $account_notes = nullable_htmlentities($row['account_notes']);
                        $account_type = intval($row['account_type']);
                        $account_type_name = nullable_htmlentities($row['account_type_name']);

                        $sql_payments = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS total_payments FROM payments WHERE payment_account_id = $account_id");
                        $row = mysqli_fetch_array($sql_payments);
                        $total_payments = floatval($row['total_payments']);

                        $sql_revenues = mysqli_query($mysqli, "SELECT SUM(revenue_amount) AS total_revenues FROM revenues WHERE revenue_account_id = $account_id");
                        $row = mysqli_fetch_array($sql_revenues);
                        $total_revenues = floatval($row['total_revenues']);

                        $sql_expenses = mysqli_query($mysqli, "SELECT SUM(expense_amount) AS total_expenses FROM expenses WHERE expense_account_id = $account_id");
                        $row = mysqli_fetch_array($sql_expenses);
                        $total_expenses = floatval($row['total_expenses']);

                        $balance = $opening_balance + $total_payments + $total_revenues - $total_expenses;
                        ?>

                        <tr>
                            <td><a class="text-dark" href="#" data-bs-toggle="modal" data-bs-target="#editAccountModal<?php echo $account_id; ?>"><?php echo $account_name; ?></a></td>
                            <td><?php echo $account_type_name; ?></td>
                            <td><?php echo $account_currency_code; ?></td>
                            <td class="text-right"><?php echo numfmt_format_currency($currency_format, $balance, $account_currency_code); ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editAccountModal<?php echo $account_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <?php if ($balance == 0 && $account_id != $config_stripe_account) { //Cannot Archive an Account until it reaches 0 Balance and cant be selected as an online account ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="/post.php?archive_account=<?php echo $account_id; ?>">
                                                <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                            </a>
                                        <?php } ?>
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
            <?php  ?>
        </div>
    </div>

<?php

require_once "/var/www/develop.twe.tech/includes/footer.php";
