<?php

// Default Column Sortby/Order Filter
$sort = "recurring_expense_next_date";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM recurring_expenses
    LEFT JOIN categories ON recurring_expense_category_id = category_id
    LEFT JOIN vendors ON recurring_expense_vendor_id = vendor_id
    LEFT JOIN accounts ON recurring_expense_account_id = account_id
    LEFT JOIN clients ON recurring_expense_client_id = client_id
    AND (vendor_name LIKE '%$q%' OR client_name LIKE '%$q%' OR category_name LIKE '%$q%' OR account_name LIKE '%$q%' OR recurring_expense_description LIKE '%$q%' OR recurring_expense_amount LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><a href="expenses.php"><i class="fas fa-fw fa-shopping-cart mr-2"></i>Expenses</a> / <i class="fas fa-fw fa-clock mr-2"></i>Recurring</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#createRecurringExpenseModal"><i class="fas fa-plus mr-2"></i>Create</button>
            </div>
        </div>

        <div class="card-body">
            <form class="mb-4" autocomplete="off">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="search" class="form-control" name="q" value="<?php if (isset($q)) { echo stripslashes(nullable_htmlentities($q)); } ?>" placeholder="Search Recurring Expenses">
                            <div class="input-group-append">
                                <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilter"><i class="fas fa-filter"></i></button>
                                <button class="btn btn-label-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                    </div>
                </div>
                <div class="collapse mt-3 <?php if (!empty($_GET['dtf']) || $_GET['canned_date'] !== "custom" ) { echo "show"; } ?>" id="advancedFilter">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Canned Date</label>
                                <select class="form-control select2" id='select2' name="canned_date">
                                    <option <?php if ($_GET['canned_date'] == "custom") { echo "selected"; } ?> value="">Custom</option>
                                    <option <?php if ($_GET['canned_date'] == "today") { echo "selected"; } ?> value="today">Today</option>
                                    <option <?php if ($_GET['canned_date'] == "yesterday") { echo "selected"; } ?> value="yesterday">Yesterday</option>
                                    <option <?php if ($_GET['canned_date'] == "thisweek") { echo "selected"; } ?> value="thisweek">This Week</option>
                                    <option <?php if ($_GET['canned_date'] == "lastweek") { echo "selected"; } ?> value="lastweek">Last Week</option>
                                    <option <?php if ($_GET['canned_date'] == "thismonth") { echo "selected"; } ?> value="thismonth">This Month</option>
                                    <option <?php if ($_GET['canned_date'] == "lastmonth") { echo "selected"; } ?> value="lastmonth">Last Month</option>
                                    <option <?php if ($_GET['canned_date'] == "thisyear") { echo "selected"; } ?> value="thisyear">This Year</option>
                                    <option <?php if ($_GET['canned_date'] == "lastyear") { echo "selected"; } ?> value="lastyear">Last Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date From</label>
                                <input type="date" class="form-control" name="dtf" max="2999-12-31" value="<?= nullable_htmlentities($dtf); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date To</label>
                                <input type="date" class="form-control" name="dtt" max="2999-12-31" value="<?= nullable_htmlentities($dtt); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=recurring_expense_next_date&order=<?= $disp; ?>">Next Date</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=vendor_name&order=<?= $disp; ?>">Vendor</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=category_name&order=<?= $disp; ?>">Category</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=recurring_expense_description&order=<?= $disp; ?>">Description</a></th>
                        <th class="text-right"><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=recurring_expense_amount&order=<?= $disp; ?>">Amount</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=recurring_expense_frequency&order=<?= $disp; ?>">Frequency</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=recurring_expense_last_sent&order=<?= $disp; ?>">Last Billed</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=account_name&order=<?= $disp; ?>">Account</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=client_name&order=<?= $disp; ?>">Client</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $recurring_expense_id = intval($row['recurring_expense_id']);
                        $recurring_expense_frequency = intval($row['recurring_expense_frequency']);
                        if($recurring_expense_frequency == 1) {
                            $recurring_expense_frequency_display = "Monthly";
                        } else {
                            $recurring_expense_frequency_display = "Annually";
                        }
                        $recurring_expense_day = intval($row['recurring_expense_day']);
                        $recurring_expense_month = intval($row['recurring_expense_month']);
                        $recurring_expense_last_sent = nullable_htmlentities($row['recurring_expense_last_sent']);
                        if(empty($recurring_expense_last_sent)) {
                            $recurring_expense_last_sent_display = "-";
                        } else {
                            $recurring_expense_last_sent_display = $recurring_expense_last_sent;
                        }
                        $recurring_expense_next_date = nullable_htmlentities($row['recurring_expense_next_date']);
                        $recurring_expense_status = intval($row['recurring_expense_status']);
                        $recurring_expense_description = nullable_htmlentities($row['recurring_expense_description']);
                        $recurring_expense_amount = floatval($row['recurring_expense_amount']);
                        $recurring_expense_payment_method = nullable_htmlentities($row['recurring_expense_payment_method']);
                        $recurring_expense_reference = nullable_htmlentities($row['recurring_expense_reference']);
                        $recurring_expense_currency_code = nullable_htmlentities($row['recurring_expense_currency_code']);
                        $recurring_expense_created_at = nullable_htmlentities($row['recurring_expense_created_at']);
                        $recurring_expense_vendor_id = intval($row['recurring_expense_vendor_id']);
                        $vendor_name = nullable_htmlentities($row['vendor_name']);
                        $recurring_expense_category_id = intval($row['recurring_expense_category_id']);
                        $category_name = nullable_htmlentities($row['category_name']);
                        $account_name = nullable_htmlentities($row['account_name']);
                        $recurring_expense_account_id = intval($row['recurring_expense_account_id']);
                        $client_name = nullable_htmlentities($row['client_name']);
                        if(empty($client_name)) {
                            $client_name_display = "-";
                        } else {
                            $client_name_display = $client_name;
                        }
                        $recurring_expense_client_id = intval($row['recurring_expense_client_id']);

                        ?>

                        <tr>
                            <td><a class="text-dark" href="#" data-bs-toggle="modal" data-bs-target="#editRecurringExpenseModal<?= $recurring_expense_id; ?>"><?= $recurring_expense_next_date; ?></a></td>
                            <td><?= $vendor_name; ?></td>
                            <td><?= $category_name; ?></td>
                            <td><?= truncate($recurring_expense_description, 50); ?></td>
                            <td class="text-bold text-right"><?= numfmt_format_currency($currency_format, $recurring_expense_amount, $recurring_expense_currency_code); ?></td>
                            <td><?= $recurring_expense_frequency_display; ?></td>
                            <td><?= $recurring_expense_last_sent_display; ?></td>
                            <td><?= $account_name; ?></td>
                            <td><?= $client_name_display; ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editRecurringExpenseModal<?= $recurring_expense_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_recurring_expense=<?= $recurring_expense_id; ?>">
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

