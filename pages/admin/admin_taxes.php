<?php

// Default Column Sortby Filter
$sort = "tax_name";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all_admin.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT * FROM taxes
    WHERE tax_archived_at IS NULL
    ORDER BY $sort $order"
);

$num_rows = mysqli_num_rows($sql);

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-balance-scale mr-2"></i>Taxes</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addTaxModal"><i class="fas fa-plus mr-2"></i>New Tax</button>
            </div>
        </div>
        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=tax_name&order=<?= $disp; ?>">Name</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=tax_percent&order=<?= $disp; ?>">Percent</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $tax_id = intval($row['tax_id']);
                        $tax_name = nullable_htmlentities($row['tax_name']);
                        $tax_percent = floatval($row['tax_percent']);

                        ?>
                        <tr>
                            <td><a class="text-dark text-bold" href="#" data-bs-toggle="modal" data-bs-target="#editTaxModal<?= $tax_id; ?>"><?= $tax_name; ?></a></td>
                            <td><?= "$tax_percent%"; ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editTaxModal<?= $tax_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_tax=<?= $tax_id; ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>">
                                            <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <?php

                        require "/var/www/portal.twe.tech/includes/modals/admin_tax_edit_modal.php";

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
require_once "/var/www/portal.twe.tech/includes/modals/admin_tax_add_modal.php";

require_once '/var/www/portal.twe.tech/includes/footer.php';

