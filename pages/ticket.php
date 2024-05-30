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
        LEFT JOIN ticket_statuses ON ticket_status = ticket_status_id
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
        $ticket_status_name = nullable_htmlentities($row['ticket_status_name']);
        $ticket_status_id = intval($row['ticket_status_id']);
        $ticket_status_color = getTicketStatusColor($ticket_status);
        $ticket_status_display = "<span class='p-2 badge rounded-pill bg-label-$ticket_status_color'>$ticket_status_name</span>";

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
            $ticket_related_open = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS ticket_related_open FROM tickets WHERE ticket_status != 5 AND ticket_contact_id = $contact_id ");
            $row = mysqli_fetch_array($ticket_related_open);
            $ticket_related_open = intval($row['ticket_related_open']);

            $ticket_related_closed = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS ticket_related_closed  FROM tickets WHERE ticket_status = 5 AND ticket_contact_id = $contact_id ");
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

        // Get Tasks
        $sql_tasks = mysqli_query( $mysqli, "SELECT * FROM tasks WHERE task_ticket_id = $ticket_id ORDER BY task_created_at ASC");
        $task_count = mysqli_num_rows($sql_tasks);

        // Get Completed Task Count
        $sql_tasks_completed = mysqli_query($mysqli,
            "SELECT * FROM tasks
            WHERE task_ticket_id = $ticket_id
            AND task_completed_at IS NOT NULL"
        );
        $completed_task_count = mysqli_num_rows($sql_tasks_completed);

        // Tasks Completed Percent
        if($task_count) {
            $tasks_completed_percent = round(($completed_task_count / $task_count) * 100);
        }

        // Get all Assigned ticket Users as a comma-separated string
        $sql_ticket_collaborators = mysqli_query($mysqli, "
            SELECT GROUP_CONCAT(DISTINCT user_name SEPARATOR ', ') AS user_names
            FROM users
            LEFT JOIN ticket_replies ON user_id = ticket_reply_by 
            WHERE ticket_reply_archived_at IS NULL AND ticket_reply_ticket_id = $ticket_id
        ");

        // Fetch the result
        $row = mysqli_fetch_assoc($sql_ticket_collaborators);

        // The user names in a comma-separated string
        $ticket_collaborators = nullable_htmlentities($row['user_names']);

        
?>
<div class="row">

    <?php if ($session_mobile) {
        require_once "/var/www/portal.twe.tech/includes/ticket_sidebar.php";
        }
    ?>
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
                            echo "<hr><i class='fas fa-fw fa-paperclip text-secondary mr-1'></i>$name | <a href='/uploads/tickets/$ticket_id/$ref_name' download='$name'><i class='fas fa-fw fa-download mr-1'></i>Download</a> | <a target='_blank' href='/uploads/tickets/$ticket_id/$ref_name'><i class='fas fa-fw fa-external-link-alt mr-1'></i>View</a>";
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
                                    <?php if ($ticket_status_id != 5) {
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
                                            <?php if ($ticket_status_id != 5) { ?>
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
                        <?php if ($ticket_status_id != 5) { ?>
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
                                            <?php 
                                            
                                            $sql_ticket_statuses = mysqli_query($mysqli, "SELECT * FROM ticket_statuses WHERE ticket_status_active = 1 AND ticket_status_visible = 1
                                            ORDER BY ticket_status_order ASC");
                                            while ($row = mysqli_fetch_array($sql_ticket_statuses)) {
                                                $select_ticket_status_id = intval($row['ticket_status_id']);
                                                $select_ticket_status_name = nullable_htmlentities($row['ticket_status_name']);
                                                $select_ticket_status_color = nullable_htmlentities($row['ticket_status_color']);
                                                $select_ticket_status_reply_default = intval($row['ticket_status_reply_default']);

                                                $ticket_status_selected = $ticket_status_reply_default == 1 ? "selected" : "";



                                                echo "<option value='$select_ticket_status_id' style='background-color: $select_ticket_status_color;' $ticket_status_selected>$select_ticket_status_name</option>";
                                            }

                                            
                                            ?>

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

        <!-- Ticket Tasks -->
        <!-- Tasks Card -->
        <div class="card card-body card-outline card-dark">
            <h5 class="text-secondary">Tasks</h5>
            <form action="/post.php" method="post" autocomplete="off">
                <input type="hidden" name="ticket_id" value="<?= $ticket_id; ?>">
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-tasks"></i></span>
                        </div>
                        <input type="text" class="form-control" name="name" placeholder="Create Task">
                        <div class="input-group-append">
                            <button type="submit" name="add_task" class="btn btn-dark">
                                <i class="fas fa-fw fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-sm">
                <?php
                while($row = mysqli_fetch_array($sql_tasks)){
                    $task_id = intval($row['task_id']);
                    $task_name = nullable_htmlentities($row['task_name']);
                    $task_description = nullable_htmlentities($row['task_description']);
                    $task_completed_at = nullable_htmlentities($row['task_completed_at']);
                ?>
                    <tr>
                        <td>
                            <?php if($task_completed_at) { ?>
                            <i class="far fa-fw fa-check-square text-primary"></i>
                            <?php } else { ?>
                            <a href="/post.php?complete_task=<?= $task_id; ?>">
                                <i class="far fa-fw fa-square text-secondary"></i>
                            </a>
                            <?php } ?>
                        </td>
                        <td><?= $task_name; ?></td>
                        <td>
                            <div class="float-right">
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-link text-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-fw fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editTaskModal<?= $task_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger confirm-link" href="/post.php?delete_task=<?= $task_id; ?>">
                                            <i class="fas fa-fw fa-trash-alt mr-2"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                <?php
            } ?>
            </table>
        </div>
    </div>
    <?php if (!$session_mobile) {
        require_once "/var/www/portal.twe.tech/includes/ticket_sidebar.php";
        }
    ?>
</div>

<?php


    }
}

require_once '/var/www/portal.twe.tech/includes/footer.php';

?>




<script src="/includes/js/show_modals.js"></script> <?php

if ($ticket_status !== 5) { ?>
    <!-- Ticket Time Tracking JS -->
    <script src="/includes/js/ticket_time_tracking.js"></script>

    <!-- Ticket collision detect JS (jQuery is called in footer, so collision detection script MUST be below it) -->
    <script src="/includes/js/ticket_collision_detection.js"></script>
    <script src="/includes/js/ticket_button_respond_note.js"></script>
<?php } ?>

<script src="/includes/js/pretty_content.js"></script>