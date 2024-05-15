<?php

// Default Column Sortby/Order Filter
$sort = "budget_year";
$order = "DESC";

require_once "/var/www/nestogy.io/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM budget
    LEFT JOIN categories ON budget_category_id = category_id
    AND (budget_description LIKE '%$q%' OR budget_amount LIKE '%$q%' OR budget_month LIKE '%$q%' OR budget_year LIKE '%$q%' OR category_name LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-balance-scale mr-2"></i>Budget</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="budget_add_modal.php"><i class="fas fa-plus mr-2"></i>Create</button>
            </div>
        </div>

        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=budget_year&order=<?php echo $disp; ?>">Year</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=budget_month&order=<?php echo $disp; ?>">Month</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=category_name&order=<?php echo $disp; ?>">Category</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=budget_description&order=<?php echo $disp; ?>">Description</a></th>
                        <th class="text-right"><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=budget_amount&order=<?php echo $disp; ?>">Amount</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $budget_id = intval($row['budget_id']);
                        $budget_description = nullable_htmlentities($row['budget_description']);
                        $budget_year = intval($row['budget_year']);
                        $budget_month = intval($row['budget_month']);
                        $budget_amount = floatval($row['budget_amount']);
                        $budget_category_id = intval($row['budget_category_id']);
                        $category_name = nullable_htmlentities($row['category_name']);

                        ?>

                        <tr>
                            <td><a class="text-dark" href="#" data-bs-toggle="modal" data-bs-target="#editBudgetModal<?php echo $budget_id; ?>"><?php echo $budget_year; ?></a></td>
                            <td><?php echo $budget_month; ?></td>
                            <td><?php echo $category_name; ?></td>
                            <td><?php echo truncate($budget_description, 50); ?></td>
                            <td class="text-bold text-right"><?php echo numfmt_format_currency($currency_format, $budget_amount, $session_company_currency); ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editBudgetModal<?php echo $budget_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_budget=<?php echo $budget_id; ?>">
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
            <?php 
 ?>
        </div>
    </div>

<?php

require_once "/var/www/nestogy.io/includes/footer.php";

