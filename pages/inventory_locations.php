<?php

// Default Column Sortby/Order Filter
$sort = "inventory_created_at";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT
    inventory_location_id,
    inventory_location_name,
    inventory_location_description,
    user_name
    FROM inventory_locations
        LEFT JOIN users on inventory_location_user_id = user_id
        WHERE inventory_location_archived_at IS NULL
        ");

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-box mr-2"></i>Inventory locations </h3>
        </div>

        <div class="card-body">
            <form class="mb-4" autocomplete="off">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="search" class="form-control" name="q" value="<?php if (isset($q)) { echo stripslashes(nullable_htmlentities($q)); } ?>" placeholder="Search <?= $product?>">
                            <div class="input-group-append">
                                <button class="btn btn-label-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="btn-group float-right">
                            <a href="inventory.php" class="btn btn-label-primary"><i class="fa fa-fw fa-arrow-left mr-2"></i>Back to full inventory</a>
                            <div class="dropdown ml-2" id="bulkActionButton" hidden>
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-fw fa-layer-group mr-2"></i>Bulk Action (<span id="selectedCount">0</span>)
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkEditCategoryModal">
                                        <i class="fas fa-fw fa-list mr-2"></i>Set Category
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkEditAccountModal">
                                        <i class="fas fa-fw fa-piggy-bank mr-2"></i>Set Account
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkEditClientModal">
                                        <i class="fas fa-fw fa-user mr-2"></i>Set Client
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <form id="bulkActions" action="/post.php" method="post">
                <div class="card-datatable table-responsive container-fluid  pt-0">                       
<table class="datatables-basic table border-top">
                        <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                        <tr>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=vendor_name&order=<?= $disp; ?>">Name</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=category_name&order=<?= $disp; ?>">Description</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=category_name&order=<?= $disp; ?>">User Responsible</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=category_name&order=<?= $disp; ?>">Total Quantity</a></th>
                            <th class="text-center">Manage Inventory</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($row = mysqli_fetch_array($sql)) {
                            $location_id = $row["inventory_location_id"];
                            $location_name = $row["inventory_location_name"];
                            $location_description = $row["inventory_location_description"];
                            $location_user = $row["user_name"];
                            
                            //Calculate number of items in location in DB
                            $location_qty_sql = mysqli_query($mysqli, "SELECT SUM(inventory_quantity) FROM inventory WHERE inventory_location_id = $location_id");
                            $location_qty = mysqli_fetch_row($location_qty_sql)[0];

                            ?>

                            <tr>
                                <td class="bg-light pr-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selected[]" value="<?= $location_id; ?>">
                                    </div>
                                </td>
                                <td><?= $location_name; ?></td>
                                <td><?= $location_description; ?></td>
                                <td><?= $location_user; ?></td>
                                <td><?= $location_qty; ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="inventory_location_manage.php?inventory_location_id=<?= $location_id; ?>" class="btn btn-label-primary btn-sm"><i class="fas fa-fw fa-edit"></i></a>
                                    </div>
                                </td>

                            <?php
                        }

                        ?>

                        </tbody>
                    </table>
                </div>

            </form>
        </div>
    </div>


<?php

require_once '/var/www/portal.twe.tech/includes/footer.php';
