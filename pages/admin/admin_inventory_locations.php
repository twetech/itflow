<?php

// Default Column Sortby Filter
$sort = "inventory_location_zip";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all_admin.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT * FROM inventory_locations
    WHERE inventory_location_archived_at IS NULL
    ORDER BY $sort $order"
);

$num_rows = mysqli_num_rows($sql);

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-map-marker-alt mr-2"></i>Inventory Locations</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addLocationModal"><i class="fas fa-plus mr-2"></i>New Location</button>
            </div>
        </div>
        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=inventory_location_name&order=<?= $disp; ?>">Name</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=inventory_location_description&order=<?= $disp; ?>">Description</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=inventory_location_user_id&order=<?= $disp; ?>">User Assigned</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=inventory_location_city&order=<?= $disp; ?>">City</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $inventory_location_id = intval($row['inventory_location_id']);
                        $inventory_location_name = nullable_htmlentities($row['inventory_location_name']);
                        $inventory_location_description = nullable_htmlentities($row['inventory_location_description']);
                        $inventory_location_user_id = intval($row['inventory_location_user_id']);
                        $inventory_location_city = nullable_htmlentities($row['inventory_location_city']);


                        //get username for display
                        $inventory_location_sql_user = mysqli_query($mysqli, "SELECT * FROM users WHERE user_id = $inventory_location_user_id");
                        $inventory_location_user = mysqli_fetch_array($inventory_location_sql_user);
                        if ($inventory_location_user) {
                            $inventory_location_user_name = nullable_htmlentities($inventory_location_user['user_name']);
                        } else {
                            $inventory_location_user_name = "Unassigned";
                        }
                        ?>

                        <tr>
                            <td><a class="text-dark text-bold" href="#" data-bs-toggle="modal" data-bs-target="#editTaxModal<?= $inventory_location_id; ?>"><?= $inventory_location_name; ?></a></td>
                            <td><?= $inventory_location_description; ?></td>
                            <td><?= $inventory_location_user_name; ?></td>
                            <td><?= $inventory_location_city; ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editLocationModal<?= $inventory_location_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <a class="dropdown-item" href="/post.php?archive_inventory_location=<?= $inventory_location_id; ?>">
                                            <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                        </a>
                                    </div>
                                </div>
                            </td>

                        <?php
                        
                        require "/var/www/portal.twe.tech/includes/modals/admin_inventory_location_edit_modal.php";

                    }

                    if ($num_rows == 0) {
                        echo "<h3 class='text-secondary mt-3' style='text-align: center'>No Records Here</h3>";
                    }

                    ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

<?php
require_once "/var/www/portal.twe.tech/includes/modals/admin_inventory_location_add_modal.php";

require_once '/var/www/portal.twe.tech/includes/footer.php';

