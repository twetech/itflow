<?php

// Default Column Sortby/Order Filter
$sort = "vendor_name";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM vendors
    WHERE vendor_client_id = 0
    AND vendor_template = 0
    AND (vendor_name LIKE '%$q%' OR vendor_description LIKE '%$q%' OR vendor_account_number LIKE '%$q%' OR vendor_website LIKE '%$q%' OR vendor_contact_name LIKE '%$q%' OR vendor_email LIKE '%$q%' OR vendor_phone LIKE '%$phone_query%')
    AND vendor_archived_at IS NULL
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-building mr-2"></i>Vendors</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primaryloadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_priority_modal.php?ticket_id=<i class="fas fa-plus mr-2"></i>New Vendor</button>
            </div>
        </div>

        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">                <table id=responsive class="responsive table table-striped table-hover table-borderless">
                    <thead class="<?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=vendor_name&order=<?= $disp; ?>">Vendor</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=vendor_description&order=<?= $disp; ?>">Description</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=vendor_contact_name&order=<?= $disp; ?>">Contact</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $vendor_id = intval($row['vendor_id']);
                        $vendor_name = nullable_htmlentities($row['vendor_name']);
                        $vendor_description = nullable_htmlentities($row['vendor_description']);
                        if (empty($vendor_description)) {
                            $vendor_description_display = "-";
                        } else {
                            $vendor_description_display = $vendor_description;
                        }
                        $vendor_account_number = nullable_htmlentities($row['vendor_account_number']);
                        $vendor_contact_name = nullable_htmlentities($row['vendor_contact_name']);
                        if (empty($vendor_contact_name)) {
                            $vendor_contact_name_display = "-";
                        } else {
                            $vendor_contact_name_display = $vendor_contact_name;
                        }
                        $vendor_phone = formatPhoneNumber($row['vendor_phone']);
                        $vendor_extension = nullable_htmlentities($row['vendor_extension']);
                        $vendor_email = nullable_htmlentities($row['vendor_email']);
                        $vendor_website = nullable_htmlentities($row['vendor_website']);
                        $vendor_hours = nullable_htmlentities($row['vendor_hours']);
                        $vendor_sla = nullable_htmlentities($row['vendor_sla']);
                        $vendor_code = nullable_htmlentities($row['vendor_code']);
                        $vendor_notes = nullable_htmlentities($row['vendor_notes']);
                        $vendor_template_id = intval($row['vendor_template_id']);

                        ?>

                        <tr>
                            <th>
                                <a class="text-dark" href="#" data-bs-toggle="modal" data-bs-target="#editVendorModal<?= $vendor_id; ?>"><?= $vendor_name; ?></a>
                                <?php if (!empty($vendor_account_number)) { ?>
                                    <br>
                                    <small class="text-secondary"><?= $vendor_account_number; ?></small>
                                <?php } ?>

                            </th>
                            <td><?= $vendor_description_display; ?></td>
                            <td>
                                <?php
                                if (!empty($vendor_contact_name)) { ?>
                                    <i class="fa fa-fw fa-user text-secondary mr-2 mb-2"></i><?= $vendor_contact_name; ?>
                                    <br>
                                <?php } else {
                                    echo $vendor_contact_name_display;
                                }

                                if (!empty($vendor_phone)) { ?>
                                    <i class="fa fa-fw fa-phone text-secondary mr-2 mb-2"></i><?= $vendor_phone; ?> <?php if (!empty($vendor_extension)) { echo "x$vendor_extension"; } ?>
                                    <br>
                                <?php }

                                if (!empty($vendor_email)) { ?>
                                    <i class="fa fa-fw fa-envelope text-secondary mr-2 mb-2"></i><?= $vendor_email; ?>
                                    <br>
                                <?php } ?>

                            </td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editVendorModal<?= $vendor_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_vendor=<?= $vendor_id; ?>">
                                            <i class="fas fa-fw fa-archive mr-2"></i>Archive
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

