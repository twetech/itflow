<?php
require_once "/var/www/portal.twe.tech/includes/inc_all.php";

// Initialize the HTML Purifier to prevent XSS
require "/var/www/portal.twe.tech/includes/plugins/htmlpurifier/HTMLPurifier.standalone.php";

$datatable_order = '[[1, "desc"]]';

$purifier_config = HTMLPurifier_Config::createDefault();
$purifier_config->set('URI.AllowedSchemes', ['data' => true, 'src' => true, 'http' => true, 'https' => true]);
$purifier = new HTMLPurifier($purifier_config);

if (isset($_GET['ticket_id'])) {
    $ticket_id = intval($_GET['ticket_id']);

    $sql = mysqli_query(
        $mysqli,
        "SELECT * FROM tickets
        LEFT JOIN clients ON ticket_client_id = client_id
        LEFT JOIN contacts ON ticket_contact_id = contact_id
        LEFT JOIN users ON ticket_assigned_to = user_id
        LEFT JOIN locations ON ticket_location_id = location_id
        LEFT JOIN assets ON ticket_asset_id = asset_id
        LEFT JOIN vendors ON ticket_vendor_id = vendor_id
        WHERE ticket_id = $ticket_id LIMIT 1"
    );

    if (mysqli_num_rows($sql) == 0) {
        echo "<center><h1 class='text-secondary mt-5'>Nothing to see here</h1><a class='btn btn-lg btn-light mt-3' href='tickets.php'><i class='fa fa-fw fa-arrow-left'></i> Go Back</a></center>";

        include_once "footer.php";
    } else {

        $row = mysqli_fetch_array($sql);
        $client_id = intval($row['client_id']);
        $client_name = nullable_htmlentities($row['client_name']);
        $client_type = nullable_htmlentities($row['client_type']);
        $client_website = nullable_htmlentities($row['client_website']);

        $client_net_terms = intval($row['client_net_terms']);
        if ($client_net_terms == 0) {
            $client_net_terms = $config_default_net_terms;
        }

        $client_rate = floatval($row['client_rate']);

        $ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
        $ticket_number = intval($row['ticket_number']);
        $ticket_category = nullable_htmlentities($row['ticket_category']);
        $ticket_subject = nullable_htmlentities($row['ticket_subject']);
        $ticket_details = $purifier->purify($row['ticket_details']);
        $ticket_priority = nullable_htmlentities($row['ticket_priority']);
        $ticket_billable = intval($row['ticket_billable']);
        $ticket_scheduled_for = nullable_htmlentities($row['ticket_schedule']);
        $ticket_onsite = nullable_htmlentities($row['ticket_onsite']);
        if (empty($ticket_scheduled_for)) {
            $ticket_scheduled_wording = "Add";
        } else {
            $ticket_scheduled_wording = "$ticket_scheduled_for";
        }

        //Set Ticket Badge Color based of priority
        if ($ticket_priority == "High") {
            $ticket_priority_display = "<span class='p-2 badge rounded-pill bg-label-danger'>$ticket_priority</span>";
        } elseif ($ticket_priority == "Medium") {
            $ticket_priority_display = "<span class='p-2 badge rounded-pill bg-label-warning'>$ticket_priority</span>";
        } elseif ($ticket_priority == "Low") {
            $ticket_priority_display = "<span class='p-2 badge rounded-pill bg-label-info'>$ticket_priority</span>";
        } else {
            $ticket_priority_display = "-";
        }
        $ticket_feedback = nullable_htmlentities($row['ticket_feedback']);
        
        // Ticket Status Display
        $ticket_status = nullable_htmlentities($row['ticket_status']);
        $ticket_status_color = getTicketStatusColor($ticket_status);
        $ticket_status_display = "<span class='p-2 badge rounded-pill bg-label-$ticket_status_color'>$ticket_status</span>";

        $ticket_vendor_ticket_number = nullable_htmlentities($row['ticket_vendor_ticket_number']);
        $ticket_created_at = nullable_htmlentities($row['ticket_created_at']);
        $ticket_date = date('Y-m-d', strtotime($ticket_created_at));
        $ticket_updated_at = nullable_htmlentities($row['ticket_updated_at']);
        $ticket_closed_at = nullable_htmlentities($row['ticket_closed_at']);

        $ticket_assigned_to = intval($row['ticket_assigned_to']);
        if (empty($ticket_assigned_to)) {
            $ticket_assigned_to_display = "<span class='text-danger'>Not Assigned</span>";
        } else {
            $ticket_assigned_to_display = nullable_htmlentities($row['user_name']);
        }

        $contact_id = intval($row['contact_id']);
        $contact_name = nullable_htmlentities($row['contact_name']);
        $contact_title = nullable_htmlentities($row['contact_title']);
        $contact_email = nullable_htmlentities($row['contact_email']);
        $contact_phone = formatPhoneNumber($row['contact_phone']);
        $contact_extension = nullable_htmlentities($row['contact_extension']);
        $contact_mobile = formatPhoneNumber($row['contact_mobile']);

        $asset_id = intval($row['asset_id']);
        $asset_ip = nullable_htmlentities($row['asset_ip']);
        $asset_name = nullable_htmlentities($row['asset_name']);
        $asset_type = nullable_htmlentities($row['asset_type']);
        $asset_uri = nullable_htmlentities($row['asset_uri']);
        $asset_make = nullable_htmlentities($row['asset_make']);
        $asset_model = nullable_htmlentities($row['asset_model']);
        $asset_serial = nullable_htmlentities($row['asset_serial']);
        $asset_os = nullable_htmlentities($row['asset_os']);
        $asset_warranty_expire = nullable_htmlentities($row['asset_warranty_expire']);

        $vendor_id = intval($row['ticket_vendor_id']);
        $vendor_name = nullable_htmlentities($row['vendor_name']);
        $vendor_description = nullable_htmlentities($row['vendor_description']);
        $vendor_account_number = nullable_htmlentities($row['vendor_account_number']);
        $vendor_contact_name = nullable_htmlentities($row['vendor_contact_name']);
        $vendor_phone = formatPhoneNumber($row['vendor_phone']);
        $vendor_extension = nullable_htmlentities($row['vendor_extension']);
        $vendor_email = nullable_htmlentities($row['vendor_email']);
        $vendor_website = nullable_htmlentities($row['vendor_website']);
        $vendor_hours = nullable_htmlentities($row['vendor_hours']);
        $vendor_sla = nullable_htmlentities($row['vendor_sla']);
        $vendor_code = nullable_htmlentities($row['vendor_code']);
        $vendor_notes = nullable_htmlentities($row['vendor_notes']);

        $location_name = nullable_htmlentities($row['location_name']);
        $location_address = nullable_htmlentities($row['location_address']);
        $location_city = nullable_htmlentities($row['location_city']);
        $location_state = nullable_htmlentities($row['location_state']);
        $location_zip = nullable_htmlentities($row['location_zip']);
        $location_phone = formatPhoneNumber($row['location_phone']);

        if ($contact_id) {
            //Get Contact Ticket Stats
            $ticket_related_open = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS ticket_related_open FROM tickets WHERE ticket_status != 'Closed' AND ticket_contact_id = $contact_id ");
            $row = mysqli_fetch_array($ticket_related_open);
            $ticket_related_open = intval($row['ticket_related_open']);

            $ticket_related_closed = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS ticket_related_closed  FROM tickets WHERE ticket_status = 'Closed' AND ticket_contact_id = $contact_id ");
            $row = mysqli_fetch_array($ticket_related_closed);
            $ticket_related_closed = intval($row['ticket_related_closed']);

            $ticket_related_total = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS ticket_related_total FROM tickets WHERE ticket_contact_id = $contact_id ");
            $row = mysqli_fetch_array($ticket_related_total);
            $ticket_related_total = intval($row['ticket_related_total']);
        }

        //Get Total Ticket Time
        $ticket_total_reply_time = mysqli_query($mysqli, "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(ticket_reply_time_worked))) AS ticket_total_reply_time FROM ticket_replies WHERE ticket_reply_archived_at IS NULL AND ticket_reply_ticket_id = $ticket_id");
        $row = mysqli_fetch_array($ticket_total_reply_time);
        $ticket_total_reply_time = nullable_htmlentities($row['ticket_total_reply_time']);

        // Client Tags

        $client_tag_name_display_array = array();
        $client_tag_id_array = array();
        $sql_client_tags = mysqli_query($mysqli, "SELECT * FROM client_tags LEFT JOIN tags ON client_tags.client_tag_tag_id = tags.tag_id WHERE client_tags.client_tag_client_id = $client_id ORDER BY tag_name ASC");
        while ($row = mysqli_fetch_array($sql_client_tags)) {

            $client_tag_id = intval($row['tag_id']);
            $client_tag_name = nullable_htmlentities($row['tag_name']);
            $client_tag_color = nullable_htmlentities($row['tag_color']);
            if (empty($client_tag_color)) {
                $client_tag_color = "dark";
            }
            $client_tag_icon = nullable_htmlentities($row['tag_icon']);
            if (empty($client_tag_icon)) {
                $client_tag_icon = "tag";
            }

            $client_tag_id_array[] = $client_tag_id;
            $client_tag_name_display_array[] = "<span class='w-100 badge text-truncate p-1 mr-1' style='background-color: $client_tag_color;'><i class='fa fa-fw fa-$client_tag_icon mr-2'></i>$client_tag_name</span>";
        }
        $client_tags_display = implode(' ', $client_tag_name_display_array);

        // Get the number of responses
        $ticket_responses_sql = mysqli_query($mysqli, "SELECT COUNT(ticket_reply_id) AS ticket_responses FROM ticket_replies WHERE ticket_reply_archived_at IS NULL AND ticket_reply_ticket_id = $ticket_id");
        $row = mysqli_fetch_array($ticket_responses_sql);
        $ticket_responses = intval($row['ticket_responses']);

        // Get & format asset warranty expiry
        $date = date('Y-m-d H:i:s');
        $dt_value = $asset_warranty_expire; //sample date
        $warranty_check = date('m/d/Y', strtotime('-8 hours'));

        if ($dt_value <= $date) {
            $dt_value = "Expired on $asset_warranty_expire";
            $warranty_status_color = 'red';
        } else {
            $warranty_status_color = 'green';
        }

        if ($asset_warranty_expire == "NULL") {
            $dt_value = "None";
            $warranty_status_color = 'red';
        }

        // Get all ticket replies
        $sql_ticket_replies = mysqli_query($mysqli, "SELECT * FROM ticket_replies LEFT JOIN users ON ticket_reply_by = user_id LEFT JOIN contacts ON ticket_reply_by = contact_id WHERE ticket_reply_ticket_id = $ticket_id AND ticket_reply_archived_at IS NULL ORDER BY ticket_reply_id DESC");

        // Get other tickets for this asset
        if (!empty($asset_id)) {
            $sql_asset_tickets = mysqli_query($mysqli, "SELECT * FROM tickets WHERE ticket_asset_id = $asset_id ORDER BY ticket_number DESC");
            $ticket_asset_count = mysqli_num_rows($sql_asset_tickets);
        }

        // Get technicians to assign the ticket to
        $sql_assign_to_select = mysqli_query(
            $mysqli,
            "SELECT users.user_id, user_name FROM users
            LEFT JOIN user_settings on users.user_id = user_settings.user_id
            WHERE user_role > 1
            AND user_status = 1
            AND user_archived_at IS NULL
            ORDER BY user_name ASC"
        );

        $sql_ticket_attachments = mysqli_query(
            $mysqli,
            "SELECT * FROM ticket_attachments
            WHERE ticket_attachment_reply_id IS NULL
            AND ticket_attachment_ticket_id = $ticket_id"
        );

        // Get Products in inventory attached to this ticket
        $sql_ticket_products = mysqli_query(
            $mysqli,
            "SELECT * FROM ticket_products
            LEFT JOIN products ON ticket_products.ticket_product_product_id = products.product_id
            WHERE ticket_product_ticket_id = $ticket_id"
        );

        $ticket_products_display = '';
        while ($row = mysqli_fetch_array($sql_ticket_products)) {
            $ticket_product_id = intval($row['ticket_product_id']);
            $product_id = intval($row['product_id']);
            $product_name = nullable_htmlentities($row['product_name']);
            $product_quantity = intval($row['ticket_product_quantity']);


            $ticket_products_display .= "<div><span class='badge badge-secondary'>$product_name x$product_quantity</span><a href='post.php?delete_ticket_product=$ticket_product_id&ticket_id=$ticket_id' class='ml-2 text-danger'>✘</a></div>";
        }
        
?>
<div class="row">
        <!-- Left -->
        <div class="col<?= $session_mobile ? '' : '-9'; ?>">
            <!-- Ticket Details -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-fw fa-info-circle mr-2"></i><?= $ticket_subject; ?>
                    </h5>
                </div>
                <div class="card-body prettyContent" id="ticketDetails">
                    <div class="row">
                        <div class="text-truncate">
                            <?= $ticket_details; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                            while ($ticket_attachment = mysqli_fetch_array($sql_ticket_attachments)) {
                                $name = nullable_htmlentities($ticket_attachment['ticket_attachment_name']);
                                $ref_name = nullable_htmlentities($ticket_attachment['ticket_attachment_reference_name']);
                                echo "<hr><i class='fas fa-fw fa-paperclip text-secondary mr-1'></i>$name | <a href=/var/www/portal.twe.tech/uploads/tickets/$ticket_id/$ref_name' download='$name'><i class='fas fa-fw fa-download mr-1'></i>Download</a> | <a target='_blank' href=/var/www/portal.twe.tech/uploads/tickets/$ticket_id/$ref_name'><i class='fas fa-fw fa-external-link-alt mr-1'></i>View</a>";
                            }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Ticket Responses -->
            <?php if ($ticket_responses > 0) { ?>
                <div class="card mb-3 card-action">
                    <div class="card-header">
                        <div class="card-action-title">
                            <h5 class="mb-4">Responses (<?= $ticket_responses; ?>):</h5>
                        </div>
                        <div class="card-action-element">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-expand"><i class="tf-icons bx bx-fullscreen"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-reload"><i class="tf-icons bx bx-rotate-left scaleX-n1-rtl"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="collapse <?= !$session_mobile ? 'show' : ''; ?>">
                        <div class="card-body">
                            <!-- Ticket replies -->
                            <table class="datatables-basic table border-top">
                                <thead>
                                    <tr>
                                        <th data-priority="1">Reply</th>
                                        <th>Time</th>
                                        <th>Time Worked</th>
                                        <th data-priority="2">By</th>
                                        <?php if ($ticket_status != "Closed") {
                                            echo "<th data-priority='3'>Actions</th>";
                                        } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        while ($row = mysqli_fetch_array($sql_ticket_replies)) {
                                            $ticket_reply_id = intval($row['ticket_reply_id']);
                                            $ticket_reply = $purifier->purify($row['ticket_reply']);
                                            $ticket_reply_type = nullable_htmlentities($row['ticket_reply_type']);
                                            $ticket_reply_created_at = nullable_htmlentities($row['ticket_reply_created_at']);
                                            $ticket_reply_updated_at = nullable_htmlentities($row['ticket_reply_updated_at']);
                                            $ticket_reply_by = intval($row['ticket_reply_by']);

                                            if ($ticket_reply_type == "Client") {
                                                $ticket_reply_by_display = nullable_htmlentities($row['contact_name']);
                                                $user_initials = initials($row['contact_name']);
                                                $user_avatar = nullable_htmlentities($row['contact_photo']);
                                                $avatar_link = "/uploads/clients/$client_id/$user_avatar";
                                            } else {
                                                $ticket_reply_by_display = nullable_htmlentities($row['user_name']);
                                                $user_id = intval($row['user_id']);
                                                $user_avatar = nullable_htmlentities($row['user_avatar']);
                                                $user_initials = initials($row['user_name']);
                                                $avatar_link = "/uploads/users/$user_id/$user_avatar";
                                                $ticket_reply_time_worked = date_create($row['ticket_reply_time_worked']);
                                            }
                                            $sql_ticket_reply_attachments = mysqli_query(
                                                $mysqli,
                                                "SELECT * FROM ticket_attachments
                                                WHERE ticket_attachment_reply_id = $ticket_reply_id
                                                AND ticket_attachment_ticket_id = $ticket_id"
                                            );
                                        ?>
                                        <div class="card">
                                            <tr>
                                                <td>
                                                    <div class="prettyContent">
                                                        <?= $ticket_reply; ?>
                                                        <?php
                                                            while ($ticket_attachment = mysqli_fetch_array($sql_ticket_reply_attachments)) {
                                                                $name = nullable_htmlentities($ticket_attachment['ticket_attachment_name']);
                                                                $ref_name = nullable_htmlentities($ticket_attachment['ticket_attachment_reference_name']);
                                                                echo "<hr><i class='fas fa-fw fa-paperclip text-secondary mr-1'></i>$name | <a href=/var/www/portal.twe.tech/uploads/tickets/$ticket_id/$ref_name' download='$name'><i class='fas fa-fw fa-download mr-1'></i>Download</a> | <a target='_blank' href=/var/www/portal.twe.tech/uploads/tickets/$ticket_id/$ref_name'><i class='fas fa-fw fa-external-link-alt mr-1'></i>View</a>";
                                                            }
                                                        ?>                                                        
                                                    </div>
                                                </td>
                                                <td class="date-time-ago">
                                                    <?= empty($ticket_reply_updated_at) ? $ticket_reply_created_at : $ticket_reply_updated_at; ?>
                                                </td>
                                                <td class="date-time-worked">
                                                    <?php if ($ticket_reply_type !== "Client") { ?>
                                                        <?= date_format($ticket_reply_time_worked, 'H:i:s'); ?>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($user_avatar)) { ?>
                                                    <img src="<?= $avatar_link; ?>" alt="User Avatar"
                                                        class="img-fluid mr-3 rounded-circle" width='40px'>
                                                    <?php } else { ?>
                                                    <span class="fa-stack fa-2x">
                                                        <i class="fa fa-circle fa-stack-2x text-secondary"></i>
                                                        <span class="fa fa-stack-1x text-white"><?= $user_initials; ?></span>
                                                    </span>
                                                    <?php } ?>
                                                    <?= $ticket_reply_by_display; ?>
                                                </td>
                                                <?php if ($ticket_status != "Closed") { ?>
                                                <td>
                                                    <!-- Dropdown for edit and archive -->
                                                    <div class="dropdown dropleft text-center d-print-none">
                                                        <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton"
                                                            aria-atomic="" data-bs-toggle="dropdown">
                                                            <i class="fas fa-fw fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editTicketReplyModal<?= $ticket_reply_id; ?>">
                                                                <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                                            </a>
                                                            <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#archiveTicketReplyModal<?= $ticket_reply_id; ?>">
                                                                <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php } ?>

                                            </tr>
                                        </div>
                                        <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <!-- Ticket Respond Field -->
            <div class="card card-action mb-3">
                <form class="mb-3 d-print-none" action="/post.php" method="post" autocomplete="off">
                    <div class="card-header">
                        <div class="card-action-title">
                            <h5 class="mb-4">Update Ticket:</h5>
                        </div>
                        <div class="card-action-element">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-expand"><i class="tf-icons bx bx-fullscreen"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-reload"><i class="tf-icons bx bx-rotate-left scaleX-n1-rtl"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="collapse">
                        <div class="card-body">
                            <?php if ($ticket_status != "Closed") { ?>
                                <input type="hidden" name="ticket_id" id="ticket_id" value="<?= $ticket_id; ?>">
                                <input type="hidden" name="client_id" id="client_id" value="<?= $client_id; ?>">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <?php if($config_ai_enable) { ?>
                                            <div class="form-group">
                                                <textarea class="form-control " id="response" name="ticket_reply" placeholder="Type a response"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <button id="rewordButton" class="btn btn-label-primary" type="button">
                                                    <i class="fas fa-fw fa-robot mr-2"></i>Reword
                                                </button>
                                                <button id="undoButton" class="btn btn-light" type="button" style="display:none;">
                                                    <i class="fas fa-fw fa-redo-alt mr-2"></i>Undo
                                                </button>
                                            </div>
                                            <?php } else { ?>
                                            <div class="form-group">
                                                <textarea id="" class="form-control" name="ticket_reply"
                                                    placeholder="Type a response"></textarea>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-fw fa-thermometer-half"></i></span>
                                            </div>
                                            <select class="form-control select2" id='select2' name="status" required>
                                                <option <?php if ($ticket_status == "Open") {
                                                                        echo "selected";
                                                                    } ?>>Open</option>
                                                <option <?php if ($ticket_status == "On Hold") {
                                                                        echo "selected";
                                                                    } ?>>On Hold</option>
                                                <?php if ($config_ticket_autoclose) { ?>
                                                <option <?php if ($ticket_status == 'Auto Close') {
                                                                            echo "selected";
                                                                        } ?>>Auto Close</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <!-- Time Tracking -->
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" inputmode="numeric" id="hours" name="hours"
                                                placeholder="Hrs" min="0" max="23" pattern="0?[0-9]|1[0-9]|2[0-3]">
                                            <input type="text" class="form-control" inputmode="numeric" id="minutes"
                                                name="minutes" placeholder="Mins" min="0" max="59" pattern="[0-5]?[0-9]">
                                            <input type="text" class="form-control" inputmode="numeric" id="seconds"
                                                name="seconds" placeholder="Secs" min="0" max="59" pattern="[0-5]?[0-9]">
                                        </div>
                                    </div>
                                    <!-- Timer Controls -->
                                    <div class="col">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success" id="startStopTimer"><i
                                                    class="fas fa-fw fa-pause"></i></button>
                                            <button type="button" class="btn btn-danger" id="resetTimer"><i
                                                    class="fas fa-fw fa-redo-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <p class="font-weight-light" id="ticket_collision_viewing"></p>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                            <?php
                                // Public responses by default (maybe configurable in future?)
                                $ticket_reply_button_wording = "Respond";
                                $ticket_reply_button_check = "checked";
                                $ticket_reply_button_icon = "paper-plane";

                                // Internal responses by default if 1) the contact email is empty or 2) the contact email matches the agent responding
                                if (empty($contact_email) || $contact_email == $session_email) {
                                    // Internal
                                    $ticket_reply_button_wording = "Add note";
                                    $ticket_reply_button_check = "";
                                    $ticket_reply_button_icon = "sticky-note";
                                } ?>

                                <div class="col col-lg-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="ticket_reply_type_checkbox"
                                                name="public_reply_type" value="1" <?= $ticket_reply_button_check ?>>
                                            <label class="custom-control-label" for="ticket_reply_type_checkbox">Public Update<br>
                                                <small class="text-secondary">(Emails contact)</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col col-lg-2">
                                    <button type="submit" id="ticket_add_reply" name="add_ticket_reply"
                                        class="btn btn-label-primary text-bold"><i
                                        class="fas fa-<?= $ticket_reply_button_icon ?> mr-2"></i><?= $ticket_reply_button_wording ?></button>
                                </div>
                            <!-- End IF for reply modal -->
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

            <!-- Right -->
        <div class="col<?= $session_mobile ? '' : '-3'; ?>">
            <div class="card card-action mb-3">
                <div class="card-header">
                    <div class="card-action-title row">
                        <div class="col">
                            <h5 class="card-title">Ticket <?= $ticket_prefix . $ticket_number ?></h5>
                        </div>
                    </div>
                    <div class="card-action-element">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <div class="dropdown dropleft text-center d-print-none">
                                    <button class="btn btn-light btn-sm float-right" type="button" id="dropdownMenuButton" aria-atomic=""data-bs-toggle="dropdown">
                                        <i class="fas fa-fw fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a href="#"  class="dropdown-item loadModalContentBtn"data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_modal.php?ticket_id=<?= $ticket_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <a href="#" class="dropdown-item loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_merge_modal.php?ticket_id=<?= $ticket_id; ?>">
                                
                                            <i class="fas fa-fw fa-clone mr-2"></i>Merge
                                        </a>
                                        <a href="#" class="dropdown-item loadModalContentBtn"  data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_change_client_modal.php?ticket_id=<?= $ticket_id; ?>">
                                            <i class="fas fa-fw fa-people-carry mr-2"></i>Change Client
                                        </a>
                                        <?php if ($session_user_role == 3) { ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_ticket=<?= $ticket_id; ?>">
                                            <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="collapse <?= !$session_mobile ? 'show' : ''; ?>">
                    <div class="card-body">
                        <div class="row">
                            <hr>
                            <div class="col">
                                <div class="card card-body card-outline mb-3">
                                    <h5><strong><?= $client_name; ?></strong></h5>
                                    <?php
                                            if (!empty($location_phone)) { ?>
                                    <div class="mt-1">
                                        <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2 mb-2"></i><?= $location_phone; ?>
                                    </div>
                                    <?php } ?>

                                    <?php
                                            if (!empty($client_tags_display)) { ?>
                                    <div class="mt-1"><?= $client_tags_display; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!-- End Client card -->
                        <!-- Ticket Status -->
                        <div class="card card-body card-outline mb-3">
                            <h5 class="text-secondary">Status</h5>
                            <div>
                                <?= $ticket_status_display; ?>
                            </div>
                        </div>
                        <!-- Ticket Actions -->
                        <?php
                            if ($ticket_status != "Closed") {
                                $close_ticket_button = true;
                            }
                            if ($ticket_billable) {
                                $invoice_ticket_button = true;
                            }

                            if ($close_ticket_button || $invoice_ticket_button) {
                            ?>
                            <div class="card card-body card-outline card-dark mb-2 d-print-none">
                                <?php if ($invoice_ticket_button) { ?>
                                <a href="#" class="btn btn-primary btn-block mb-3 loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_invoice_add_modal.php?ticket_id=<?= $ticket_id; ?>&ticket_total_reply_time=<?= $ticket_total_reply_time; ?>">
                                    <i class="fas fa-fw fa-file-invoice mr-2"></i>Invoice Ticket
                                </a>
                                <?php } ?>
                                <?php if ($close_ticket_button) { ?>
                                <a href="/post.php?close_ticket=<?= $ticket_id; ?>" class="btn btn-secondary btn-block confirm-link" id="ticket_close">
                                    <i class="fas fa-fw fa-gavel mr-2"></i>Close Ticket
                                </a>
                                <?php } ?>
                            </div>
                            <?php } ?>

                        <!-- End Ticket Actions -->
                        <!-- Contact card -->
                        <div class="card card-body card-outline mb-3">
                            <h5 class="text-secondary">Contact</h5>
                            <?php if (!empty($contact_id)) { ?>
                            <div>
                                <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i><a class="loadModalContentBtn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#dynamicModal" data-modal-file="ticket_edit_contact_modal.php?ticket_id=<?= $ticket_id; ?>"><strong><?= $contact_name; ?></strong>
                                </a>
                            </div>
                            <?php
                                        if (!empty($location_name)) { ?>
                            <div class="mt-2">
                                <i class="fa fa-fw fa-map-marker-alt text-secondary ml-1 mr-2"></i><?= $location_name; ?>
                            </div>
                            <?php }
                                        if (!empty($contact_email)) { ?>
                            <div class="mt-2">
                                <i class="fa fa-fw fa-envelope text-secondary ml-1 mr-2"></i><a
                                    href="mailto:<?= $contact_email; ?>"><?= $contact_email; ?></a>
                            </div>
                            <?php }
                                        if (!empty($contact_phone)) { ?>
                            <div class="mt-2">
                                <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i><a
                                    href="tel:<?= $contact_phone; ?>"><?= $contact_phone; ?></a>
                            </div>
                            <?php }
                                        if (!empty($contact_mobile)) { ?>
                            <div class="mt-2">
                                <i class="fa fa-fw fa-mobile-alt text-secondary ml-1 mr-2"></i><a
                                    href="tel:<?= $contact_mobile; ?>"><?= $contact_mobile; ?></a>
                            </div>
                            <?php } ?>
                            <?php
                                    // Previous tickets
                                    $prev_ticket_id = $prev_ticket_subject = $prev_ticket_status = ''; // Default blank
                                    $sql_prev_ticket = "SELECT ticket_id, ticket_created_at, ticket_subject, ticket_status, ticket_assigned_to FROM tickets WHERE ticket_contact_id = $contact_id AND ticket_id  <> $ticket_id ORDER BY ticket_id DESC LIMIT 1";
                                    $prev_ticket_row = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_prev_ticket));
                                    if ($prev_ticket_row) {
                                        $prev_ticket_id = intval($prev_ticket_row['ticket_id']);
                                        $prev_ticket_subject = nullable_htmlentities($prev_ticket_row['ticket_subject']);
                                        $prev_ticket_status = nullable_htmlentities($prev_ticket_row['ticket_status']);
                                    ?>

                            <hr>
                            <div>
                                <i class="fa fa-fw fa-history text-secondary ml-1 mr-2"></i><b>Previous ticket:</b>
                                <a
                                    href="ticket.php?ticket_id=<?= $prev_ticket_id; ?>"><?= $prev_ticket_subject; ?></a>
                            </div>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-hourglass-start text-secondary ml-1 mr-2"></i><strong>Status:</strong>
                                <span class="text-success"><?= $prev_ticket_status; ?></span>
                            </div>
                            <?php } ?>
                            <?php } else { ?>
                            <div class="d-print-none">
                                <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_contact_modal.php?ticket_id=<?php echo $ticket_id; ?>"><i
                                        class="fa fa-fw fa-plus mr-2"></i>Add a Contact</a>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- End contact card -->
                        <!-- Assigned to -->
                        <div class="card card-body card-outline mb-3">
                            <h5 class="text-secondary">Assigned to</h5>
                            <div>
                                <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i><?= $ticket_assigned_to_display; ?>
                            </div>
                            <form action="/post.php" method="post">
                                <input type="hidden" name="ticket_id" value="<?= $ticket_id; ?>">
                                <input type="hidden" name="ticket_status" value="<?= $ticket_status; ?>">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                                        </div>
                                        <select class="form-control select2" id='select2' name="assigned_to" <?php if ($ticket_status == "Closed") {
                                                                                                    echo "disabled";
                                                                                                } ?>>
                                            <option value="0">Not Assigned</option>
                                            <?php

                                            while ($row = mysqli_fetch_array($sql_assign_to_select)) {
                                                $user_id = intval($row['user_id']);
                                                $user_name = nullable_htmlentities($row['user_name']); ?>
                                                <option <?php if ($ticket_assigned_to == $user_id) {
                                                            echo "selected";
                                                        } ?> value="<?= $user_id; ?>"><?= $user_name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-append d-print-none">
                                            <button type="submit" class="btn btn-label-primary" name="assign_ticket" <?php if ($ticket_status == "Closed") {
                                                                                                                    echo "disabled";
                                                                                                                } ?>><i class="fas fa-check"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Assigned to -->
                        <!-- Ticket watchers card -->
                        <?php
                            $sql_ticket_watchers = mysqli_query($mysqli, "SELECT * FROM ticket_watchers WHERE watcher_ticket_id = $ticket_id ORDER BY watcher_email DESC");
                            if ($ticket_status !== "Closed" || mysqli_num_rows($sql_ticket_watchers) > 0) { ?>

                            <div class="card card-body card-outline mb-3">
                                <h5 class="text-secondary">Watchers</h5>

                                <?php if ($ticket_status !== "Closed") { ?>
                                <div class="d-print-none">
                                    <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_add_watcher_modal.php?ticket_id=<?php echo $ticket_id; ?>"
                                    ><i
                                            class="fa fa-fw fa-plus mr-2"></i>Add a Watcher</a>
                                </div>
                                <?php } ?>

                                <?php
                                        // Get Watchers
                                        while ($ticket_watcher_row = mysqli_fetch_array($sql_ticket_watchers)) {
                                            $watcher_id = intval($ticket_watcher_row['watcher_id']);
                                            $ticket_watcher_email = nullable_htmlentities($ticket_watcher_row['watcher_email']);
                                            ?>
                                <div class='mt-1'>
                                    <i class="fa fa-fw fa-eye text-secondary ml-1 mr-2"></i><?= $ticket_watcher_email; ?>
                                    <?php if ($ticket_status !== "Closed") { ?>
                                    <a class="confirm-link" href="/post.php?delete_ticket_watcher=<?= $watcher_id; ?>">
                                        <i class="fas fa-fw fa-times text-secondary ml-1"></i>
                                    </a>
                                    <?php }
                                            ?>
                                </div>
                                <?php
                                    } ?>
                                </div>
                        <?php } ?>
                        <!-- End Ticket watchers card -->
                        <!-- Ticket Details card -->
                        <div class="card card-body card-outline mb-3">
                            <h5 class="text-secondary">Details</h5>
                            <div>
                                <i class="fa fa-fw fa-thermometer-half text-secondary ml-1 mr-2"></i><a href="#" data-bs-toggle="modal"
                                    data-bs-target="#editTicketPriorityModal<?= $ticket_id; ?>"><?= $ticket_priority_display; ?></a>
                            </div>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-calendar text-secondary ml-1 mr-2"></i>Created:
                                <?= $ticket_created_at; ?>
                            </div>
                            <div class="mt-2">
                                <i class="fa fa-fw fa-history text-secondary ml-1 mr-2"></i>Updated:
                                <strong><?= $ticket_updated_at; ?></strong>
                            </div>

                            <!-- Ticket closure info -->
                            <?php
                                    if ($ticket_status == "Closed") {
                                        $sql_closed_by = mysqli_query($mysqli, "SELECT * FROM tickets, users WHERE ticket_closed_by = user_id");
                                        $row = mysqli_fetch_array($sql_closed_by);
                                        $ticket_closed_by_display = nullable_htmlentities($row['user_name']);
                                    ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i>Closed by:
                                <?= ucwords($ticket_closed_by_display); ?>
                            </div>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-comment-dots text-secondary ml-1 mr-2"></i>Feedback:
                                <?= $ticket_feedback; ?>
                            </div>
                            <?php } ?>
                            <!-- END Ticket closure info -->

                            <?php
                                    // Ticket scheduling
                                    if ($ticket_status !== "Closed") { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-calendar-check text-secondary ml-1 mr-2"></i>Scheduled: <a class="loadModalContentBtn" href="#"
                                    data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_schedule_modal.php?ticket_id=<?php echo $ticket_id; ?>">
                                    <?= $ticket_scheduled_wording ?> </a>
                            </div>
                            <?php }

                                    // Time tracking
                                    if (!empty($ticket_total_reply_time)) { ?>
                            <div class="mt-1">
                                <i class="far fa-fw fa-clock text-secondary ml-1 mr-2"></i>Total time worked:
                                <?= $ticket_total_reply_time; ?>
                            </div>
                            <?php }

                                    // Billable
                                    if ($config_module_enable_accounting) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-dollar-sign text-secondary ml-1 mr-2"></i>Billable:
                                <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_billable_modal.php?ticket_id=<?php echo $ticket_id; ?>">
                                    <?php
                                                if ($ticket_billable == 1) {
                                                    echo "<span class='badge rounded-pill bg-label-success p-2'>$</span>";
                                                } else {
                                                    echo "<span class='badge rounded-pill bg-label-secondary p-2'>X</span>";
                                                }
                                                ?>
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- End Ticket details card -->
                        <!-- Asset card -->
                        <div class="card card-body card-outline mb-3">
                            <h5 class="text-secondary">Asset</h5>

                            <?php if ($asset_id == 0) { ?>

                            <div class="d-print-none">
                                <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_asset_modal.php?ticket_id=<?php echo $ticket_id; ?>"><i
                                        class="fa fa-fw fa-plus mr-2"></i>Add an Asset</a>
                            </div>

                            <?php } else { ?>

                            <div>
                                <a href='client_asset_details.php?client_id=<?= $client_id ?>&asset_id=<?= $asset_id ?>'><i
                                        class="fa fa-fw fa-desktop text-secondary ml-1 mr-2"></i><strong><?= $asset_name; ?></strong></a>
                            </div>

                            <?php if (!empty($asset_os)) { ?>
                            <div class="mt-1">
                                <i class="fab fa-fw fa-microsoft text-secondary ml-1 mr-2"></i><?= $asset_os; ?>
                            </div>
                            <?php }

                                        if (!empty($asset_ip)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-network-wired text-secondary ml-1 mr-2"></i><?= $asset_ip; ?>
                            </div>
                            <?php }

                                        if (!empty($asset_make)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-tag text-secondary ml-1 mr-2"></i>Model:
                                <?= "$asset_make $asset_model"; ?>
                            </div>
                            <?php }

                                        if (!empty($asset_serial)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-barcode text-secondary ml-1 mr-2"></i>Service Tag:
                                <?= $asset_serial; ?>
                            </div>
                            <?php }

                                        if (!empty($asset_warranty_expire)) { ?>
                            <div class="mt-1">
                                <i class="far fa-fw fa-calendar-alt text-secondary ml-1 mr-2"></i>Warranty expires:
                                <strong><?= $asset_warranty_expire ?></strong>
                            </div>
                            <?php }

                                        if (!empty($asset_uri)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-globe text-secondary ml-1 mr-2"></i><a href="<?= $asset_uri; ?>"
                                    target="_blank"><?= truncate($asset_uri, 25); ?></a>
                            </div>
                            <?php }

                                    if ($ticket_asset_count > 0) { ?>

                            <button class="btn btn-block btn-light mt-2 d-print-none" data-bs-toggle="modal"
                                data-bs-target="#assetTicketsModal">Service History (<?= $ticket_asset_count; ?>)</button>

                            <div class="modal" id="assetTicketsModal" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content bg-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="fa fa-fw fa-desktop"></i> <?= $asset_name; ?>
                                            </h5>
                                            <button type="button" class="close text-white" data-bs-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body bg-white">
                                            <?php
                                                            // Query is run from client_assets.php
                                                            while ($row = mysqli_fetch_array($sql_asset_tickets)) {
                                                                $service_ticket_id = intval($row['ticket_id']);
                                                                $service_ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
                                                                $service_ticket_number = intval($row['ticket_number']);
                                                                $service_ticket_subject = nullable_htmlentities($row['ticket_subject']);
                                                                $service_ticket_status = nullable_htmlentities($row['ticket_status']);
                                                                $service_ticket_created_at = nullable_htmlentities($row['ticket_created_at']);
                                                                $service_ticket_updated_at = nullable_htmlentities($row['ticket_updated_at']);
                                                            ?>
                                            <p>
                                                <i class="fas fa-fw fa-ticket-alt"></i>
                                                Ticket: <a
                                                    href="ticket.php?ticket_id=<?= $service_ticket_id; ?>"><?= "$service_ticket_prefix$service_ticket_number" ?></a>
                                                <?= "on $service_ticket_created_at - <b>$service_ticket_subject</b> ($service_ticket_status)"; ?>
                                            </p>
                                            <?php
                                                            }
                                                            ?>
                                        </div>
                                        <div class="modal-footer bg-white">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <?php } // End Ticket asset Count
                                        ?>

                            <?php } // End if asset_id == 0 else
                                    ?>

                        </div>
                        <!-- End Asset card -->
                        <!-- Vendor card -->
                        <div class="card card-body card-outline mb-3">
                            <h5 class="text-secondary">Vendor</h5>
                            <?php if (empty($vendor_id)) { ?>
                            <div class="d-print-none">
                                <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_vendor_modal.php?ticket_id=<?php echo $ticket_id; ?>"><i
                                        class="fa fa-fw fa-plus mr-2"></i>Add a Vendor</a>
                            </div>
                            <?php } else { ?>
                            <div>
                                <i
                                    class="fa fa-fw fa-building text-secondary ml-1 mr-2"></i><strong><?= $vendor_name; ?></strong>
                            </div>
                            <?php

                                        if (!empty($vendor_contact_name)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i><?= $vendor_contact_name; ?>
                            </div>
                            <?php }

                                        if (!empty($ticket_vendor_ticket_number)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-tag text-secondary ml-1 mr-2"></i><?= $ticket_vendor_ticket_number; ?>
                            </div>
                            <?php }

                                        if (!empty($vendor_email)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-envelope text-secondary ml-1 mr-2"></i><a
                                    href="mailto:<?= $vendor_email; ?>"><?= $vendor_email; ?></a>
                            </div>
                            <?php }

                                        if (!empty($vendor_phone)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i><?= $vendor_phone; ?>
                            </div>
                            <?php }

                                        if (!empty($vendor_website)) { ?>
                            <div class="mt-1">
                                <i class="fa fa-fw fa-globe text-secondary ml-1 mr-2"></i><?= $vendor_website; ?>
                            </div>
                            <?php } ?>

                            <?php } //End Else
                                    ?>
                        </div>
                        <!-- End Vendor card -->
                        <!-- Products card -->
                        <?php if ($config_module_enable_accounting == 1) { ?>
                            <div class="card card-body card-outline mb-3">
                                <h5 class="text-secondary">Products</h5>
                                <div class="d-print-none">
                                    <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_add_product_modal.php?ticket_id=<?php echo $ticket_id; ?>"><i
                                            class="fa fa-fw fa-plus mr-2"></i>Manage Products</a>
                                </div>
                                <?= $ticket_products_display; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
</div>
<?php


    }
}

require_once '/var/www/portal.twe.tech/includes/footer.php';

?>




<script src="/includes/js/show_modals.js"></script> <?php

if ($ticket_status !== "Closed") { ?>
    <!-- Ticket Time Tracking JS -->
    <script src="/includes/js/ticket_time_tracking.js"></script>

    <!-- Ticket collision detect JS (jQuery is called in footer, so collision detection script MUST be below it) -->
    <script src="/includes/js/ticket_collision_detection.js"></script>
    <script src="/includes/js/ticket_button_respond_note.js"></script>
<?php } ?>

<script src="/includes/js/pretty_content.js"></script>