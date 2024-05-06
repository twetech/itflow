<?php

// Default Column Sortby Filter
$sort = "vendor_name";
$order = "ASC";
$archived = 'NULL';

require_once "/var/www/develop.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM vendors
    WHERE vendor_client_id = $client_id
    AND vendor_template = 0
    AND vendor_archived_at = $archived
    ORDER BY $sort $order"
);
$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2">
                <i class="fas fa-fw fa-building mr-2"></i>Vendors
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="vendor_add_modal.php?client_id=<?php echo $client_id; ?>">
                        <i class="fas fa-plus mr-2"></i>New Vendor
                    </button>
                    <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#addVendorFromTemplateModal">
                            <i class="fa fa-fw fa-puzzle-piece mr-2"></i>Create from Template
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exportVendorModal">
                            <i class="fa fa-fw fa-download mr-2"></i>Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                <input type="hidden" name="archived" value="<?php echo $archived; ?>">
                <div class="row">


                    <div class="col-md-8">
                         <div class="float-right">
                            <?php if($archived == 1){ ?>
                            <a href="?client_id=<?php echo $client_id; ?>&archived=0" class="btn btn-label-primary"><i class="fa fa-fw fa-archive mr-2"></i>Archived</a>
                            <?php } else { ?>
                            <a href="?client_id=<?php echo $client_id; ?>&archived=1" class="btn btn-default"><i class="fa fa-fw fa-archive mr-2"></i>Archived</a>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </form>
            <hr>
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-secondary" href="?<?php echo $url_query_strings_sort; ?>&sort=vendor_name&order=<?php echo $disp; ?>">Vendor</a></th>
                        <th>Contact</th>
                        <th>Website</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $vendor_id = intval($row['vendor_id']);
                        $vendor_name = nullable_htmlentities($row['vendor_name']);
                        $vendor_description = nullable_htmlentities($row['vendor_description']);
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
                        
                        if (empty($vendor_website)) {
                            $vendor_website_display = "-";
                        } else {
                            $vendor_website_display = "<button class='btn btn-sm clipboardjs' data-clipboard-text='$vendor_website'><i class='far fa-copy text-secondary'></i></button><a href='https://$vendor_website' target='_blank'><i class='fa fa-external-link-alt text-secondary'></i></a>";
                        }
                        
                        ?>
                        <tr>
                            <td>
                                <a class="text-dark" href="#" data-bs-toggle="modal" data-bs-target="#editVendorModal<?php echo $vendor_id; ?>">
                                    <div class="media">
                                        <i class="fa fa-fw fa-2x fa-building mr-3"></i>
                                        <div class="media-body">
                                            <div><?php echo $vendor_name; ?></div>
                                            <div><small class="text-secondary"><?php echo $vendor_description; ?></small></div>
                                        </div>
                                    </div>
                                </a>
                        
                            </td>
                            <td>
                                <?php
                                if (!empty($vendor_contact_name)) { ?>
                                    <i class="fa fa-fw fa-user text-secondary mr-2 mb-2"></i><?php echo $vendor_contact_name_display; ?>
                                    <br>
                                <?php } else {
                                    echo $vendor_contact_name_display;
                                }

                                if (!empty($vendor_phone)) { ?>
                                    <i class="fa fa-fw fa-phone text-secondary mr-2 mb-2"></i><?php echo $vendor_phone; ?>
                                    <br>
                                <?php }

                                if (!empty($vendor_email)) { ?>
                                    <i class="fa fa-fw fa-envelope text-secondary mr-2 mb-2"></i><?php echo $vendor_email; ?>
                                    <br>
                                <?php } ?>
                            </td>
                             <td><?php echo $vendor_website_display; ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editVendorModal<?php echo $vendor_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <?php if ($session_user_role == 3) { ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_vendor=<?php echo $vendor_id; ?>">
                                                <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                            </a>
                                            <?php if ($config_destructive_deletes_enable) { ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_vendor=<?php echo $vendor_id; ?>">
                                                <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                            </a>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <?php

                        require "/var/www/develop.twe.tech/includes/modals/vendor_edit_modal.php";

                    } ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

<?php
require_once "/var/www/develop.twe.tech/includes/modals/vendor_add_modal.php";

require_once "/var/www/develop.twe.tech/includes/modals/vendor_add_from_template_modal.php";

require_once "/var/www/develop.twe.tech/includes/modals/client_vendor_export_modal.php";

require_once '/var/www/develop.twe.tech/includes/footer.php';

