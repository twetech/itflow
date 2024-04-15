<?php

// Default Column Sortby Filter
$sort = "contact_name";
$order = "ASC";

require_once "/var/www/develop.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query($mysqli, "SELECT SQL_CALC_FOUND_ROWS * FROM contacts
    LEFT JOIN locations ON location_id = contact_location_id
    WHERE contact_client_id = $client_id 
    ORDER BY contact_primary DESC, contact_important DESC, $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fa fa-fw fa-users mr-2"></i>Contacts</h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="button" class="btn btn-soft-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="client_contact_add_modal.php">
                        <i class="fas fa-plus mr-2"></i>New Contact
                    </button>
                    <button type="button" class="btn btn-soft-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#contactInviteModal"><i class="fas fa-fw fa-paper-plane mr-2"></i>Invite</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#importContactModal">
                            <i class="fa fa-fw fa-upload mr-2"></i>Import
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exportContactModal">
                            <i class="fa fa-fw fa-download mr-2"></i>Export
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="bulkActions" action="/post.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
                <div class="card-datatable table-responsive pt-0">                    <table id=responsive class="responsive table border">
                        <thead class="thead-light <?php if (!$num_rows[0]) { echo "d-none"; } ?>">
                        <tr>
                            <th><a class="text-secondary ml-3" href="?<?php echo $url_query_strings_sort; ?>&sort=contact_name&order=<?php echo $disp; ?>">Name</a></th>
                            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sort; ?>&sort=contact_department&order=<?php echo $disp; ?>">Department</a></th>
                            <th>Contact</th>
                            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sort; ?>&sort=location_name&order=<?php echo $disp; ?>">Location</a></th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($row = mysqli_fetch_array($sql)) {
                            $contact_id = intval($row['contact_id']);
                            $contact_name = nullable_htmlentities($row['contact_name']);
                            $contact_title = nullable_htmlentities($row['contact_title']);
                            if (empty($contact_title)) {
                                $contact_title_display = "";
                            } else {
                                $contact_title_display = "<small class='text-secondary'>$contact_title</small>";
                            }
                            $contact_department = nullable_htmlentities($row['contact_department']);
                            if (empty($contact_department)) {
                                $contact_department_display = "-";
                            } else {
                                $contact_department_display = $contact_department;
                            }
                            $contact_extension = nullable_htmlentities($row['contact_extension']);
                            if (empty($contact_extension)) {
                                $contact_extension_display = "";
                            } else {
                                $contact_extension_display = "<small class='text-secondary ml-1'>x$contact_extension</small>";
                            }
                            $contact_phone = formatPhoneNumber($row['contact_phone']);
                            if (empty($contact_phone)) {
                                $contact_phone_display = "";
                            } else {
                                $contact_phone_display = "<div><i class='fas fa-fw fa-phone mr-2'></i><a href='tel:$contact_phone'>$contact_phone$contact_extension_display</a></div>";
                            }

                            $contact_mobile = formatPhoneNumber($row['contact_mobile']);
                            if (empty($contact_mobile)) {
                                $contact_mobile_display = "";
                            } else {
                                $contact_mobile_display = "<div class='mt-2'><i class='fas fa-fw fa-mobile-alt mr-2'></i><a href='tel:$contact_mobile'>$contact_mobile</a></div>";
                            }
                            $contact_email = nullable_htmlentities($row['contact_email']);
                            if (empty($contact_email)) {
                                $contact_email_display = "";
                            } else {
                                $contact_email_display = "<div class='mt-1'><i class='fas fa-fw fa-envelope mr-2'></i><a href='mailto:$contact_email'>$contact_email</a><button class='btn btn-sm clipboardjs' type='button' data-clipboard-text='$contact_email'><i class='far fa-copy text-secondary'></i></button></div>";
                            }
                            $contact_info_display = "$contact_phone_display $contact_mobile_display $contact_email_display";
                            if (empty($contact_info_display)) {
                                $contact_info_display = "-";
                            }
                            $contact_pin = nullable_htmlentities($row['contact_pin']);
                            $contact_photo = nullable_htmlentities($row['contact_photo']);
                            $contact_initials = initials($contact_name);
                            $contact_notes = nullable_htmlentities($row['contact_notes']);
                            $contact_primary = intval($row['contact_primary']);
                            $contact_important = intval($row['contact_important']);
                            $contact_billing = intval($row['contact_billing']);
                            $contact_technical = intval($row['contact_technical']);
                            $contact_created_at = nullable_htmlentities($row['contact_created_at']);
                            if ($contact_primary == 1) {
                                $contact_primary_display = "<small class='text-success'>Primary Contact</small>";
                            } else {
                                $contact_primary_display = false;
                            }
                            $contact_location_id = intval($row['contact_location_id']);
                            $location_name = nullable_htmlentities($row['location_name']);
                            if (empty($location_name)) {
                                $location_name = "-";
                            }
                            $location_archived_at = nullable_htmlentities($row['location_archived_at']);
                            if ($location_archived_at) {
                                $location_name_display = "<div class='text-danger' title='Archived'><s>$location_name</s></div>";
                            } else {
                                $location_name_display = $location_name;
                            }
                            $auth_method = nullable_htmlentities($row['contact_auth_method']);

                            // Related Assets Query
                            $sql_related_assets = mysqli_query($mysqli, "SELECT * FROM assets WHERE asset_contact_id = $contact_id ORDER BY asset_id DESC");
                            $asset_count = mysqli_num_rows($sql_related_assets);

                            // Related Logins Query
                            $sql_related_logins = mysqli_query($mysqli, "SELECT * FROM logins WHERE login_contact_id = $contact_id ORDER BY login_id DESC");
                            $login_count = mysqli_num_rows($sql_related_logins);

                            // Related Software Query
                            $sql_related_software = mysqli_query($mysqli, "SELECT * FROM software, software_contacts WHERE software.software_id = software_contacts.software_id AND software_contacts.contact_id = $contact_id ORDER BY software.software_id DESC");
                            $software_count = mysqli_num_rows($sql_related_software);

                            // Related Tickets Query
                            $sql_related_tickets = mysqli_query($mysqli, "SELECT * FROM tickets WHERE ticket_contact_id = $contact_id ORDER BY ticket_id DESC");
                            $ticket_count = mysqli_num_rows($sql_related_tickets);

                            ?>
                            <tr>
                                <td>
                                    <a class="text-dark" href="client_contact_details.php?client_id=<?php echo $client_id; ?>&contact_id=<?php echo $contact_id; ?>">
                                        <div class="media">
                                            <?php if (!empty($contact_photo)) { ?>
                                                <span class="fa-stack fa-2x mr-3 text-center">
                                                    <img class="img-fluid rounded-circle" src="<?php echo "/uploads/clients/$client_id/$contact_photo"; ?>">
                                                </span>
                                            <?php } else { ?>

                                                <span class="fa-stack fa-2x mr-3">
                                                    <i class="fa fa-circle fa-stack-2x text-secondary"></i>
                                                    <span class="fa fa-stack-1x text-white"><?php echo $contact_initials; ?></span>
                                                </span>

                                            <?php } ?>

                                            <div class="media-body">
                                                <div class="<?php if(!empty($contact_important)) { echo "text-bold"; } ?>"><?php echo $contact_name; ?></div>
                                                <?php echo $contact_title_display; ?>
                                                <div><?php echo $contact_primary_display; ?></div>
                                                    
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td><?php echo $contact_department_display; ?></td>
                                <td><?php echo $contact_info_display; ?></td>
                                <td><?php echo $location_name_display; ?></td>
                                <td>
                                    <div class="dropdown dropleft text-center">
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="client_contact_details.php?client_id=<?php echo $client_id; ?>&contact_id=<?php echo $contact_id; ?>">
                                                <i class="fas fa-fw fa-eye mr-2"></i>Details
                                            </a>
                                            <a href="#" class="dropdown-item loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="client_contact_edit_modal.php?contact_id=<?php echo $contact_id; ?>">
                                                <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                            </a>
                                            <?php if ($session_user_role == 3 && $contact_primary == 0) { ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger confirm-link" href="/post.php?anonymize_contact=<?php echo $contact_id; ?>">
                                                    <i class="fas fa-fw fa-user-secret mr-2"></i>Anonymize & Archive
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_contact=<?php echo $contact_id; ?>">
                                                    <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                                </a>
                                                <?php if ($config_destructive_deletes_enable) { ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_contact=<?php echo $contact_id; ?>">
                                                    <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                                </a>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <?php

                            require "/var/www/develop.twe.tech/includes/modals/client_contact_edit_modal.php";

                        }

                        ?>

                        </tbody>
                    </table>
                </div>
                <?php require_once "/var/www/develop.twe.tech/includes/modals/client_contact_bulk_assign_location_modal.php"; ?>
                <?php require_once "/var/www/develop.twe.tech/includes/modals/client_contact_bulk_edit_phone_modal.php"; ?>
                <?php require_once "/var/www/develop.twe.tech/includes/modals/client_contact_bulk_edit_department_modal.php"; ?>
                <?php require_once "/var/www/develop.twe.tech/includes/modals/client_contact_bulk_edit_role_modal.php"; ?>
            </form>
            <?php require_once '/var/www/develop.twe.tech/includes/pagination.php';
?>
        </div>
    </div>

<!-- JavaScript to Show/Hide Password Form Group -->
<script>

    function generatePassword(type, id) {
        // Send a GET request to ajax.php as ajax.php?get_readable_pass=true
        jQuery.get(
            "ajax.php", {
                get_readable_pass: 'true'
            },
            function(data) {
                //If we get a response from post.php, parse it as JSON
                const password = JSON.parse(data);

                // Set the password value to the correct modal, based on the type
                if (type == "add") {
                    document.getElementById("password-add").value = password;
                } else if (type == "edit") {
                    document.getElementById("password-edit-"+id.toString()).value = password;
                }
            }
        );
    }

    $(document).ready(function() {
        $('.authMethod').on('change', function() {
            var $form = $(this).closest('.authForm');
            if ($(this).val() === 'local') {
                $form.find('.passwordGroup').show();
            } else {
                $form.find('.passwordGroup').hide();
            }
        });
        $('.authMethod').trigger('change');

    });
</script>

<script src="js/bulk_actions.js"></script>

<?php

require_once "/var/www/develop.twe.tech/includes/modals/client_contact_add_modal.php";

require_once "/var/www/develop.twe.tech/includes/modals/client_contact_invite_modal.php";

require_once "/var/www/develop.twe.tech/includes/modals/client_contact_import_modal.php";

require_once "/var/www/develop.twe.tech/includes/modals/client_contact_export_modal.php";

require_once '/var/www/develop.twe.tech/includes/footer.php';
