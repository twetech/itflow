<?php
require_once '/var/www/develop.twe.tech/includes/inc_all_modal.php';

$ticket_id = intval($_GET['ticket_id']);

// Initialize the HTML Purifier to prevent XSS
require '/var/www/develop.twe.tech/includes/plugins/htmlpurifier/HTMLPurifier.standalone.php';


$purifier_config = HTMLPurifier_Config::createDefault();
$purifier_config->set('URI.AllowedSchemes', ['data' => true, 'src' => true, 'http' => true, 'https' => true]);
$purifier = new HTMLPurifier($purifier_config);

// Get ticket details
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

    include_once 'footer.php';
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
        $ticket_scheduled_wording = 'Add';
    } else {
        $ticket_scheduled_wording = "$ticket_scheduled_for";
    }

    // Set Ticket Badge Color based of priority
    if ($ticket_priority == 'High') {
        $ticket_priority_display = "<span class='p-2 badge badge-danger'>$ticket_priority</span>";
    } elseif ($ticket_priority == 'Medium') {
        $ticket_priority_display = "<span class='p-2 badge badge-warning'>$ticket_priority</span>";
    } elseif ($ticket_priority == 'Low') {
        $ticket_priority_display = "<span class='p-2 badge badge-info'>$ticket_priority</span>";
    } else {
        $ticket_priority_display = '-';
    }
    $ticket_feedback = nullable_htmlentities($row['ticket_feedback']);

    // Ticket Status Display
    $ticket_status = nullable_htmlentities($row['ticket_status']);
    $ticket_status_color = getTicketStatusColor($ticket_status);
    $ticket_status_display = "<span class='p-2 badge badge-$ticket_status_color'>$ticket_status</span>";

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
        // Get Contact Ticket Stats
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

    // Get Total Ticket Time
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
            $client_tag_color = 'dark';
        }
        $client_tag_icon = nullable_htmlentities($row['tag_icon']);
        if (empty($client_tag_icon)) {
            $client_tag_icon = 'tag';
        }

        $client_tag_id_array[] = $client_tag_id;
        $client_tag_name_display_array[] = "<span class='badge text-light p-1 mr-1' style='background-color: $client_tag_color;'><i class='fa fa-fw fa-$client_tag_icon mr-2'></i>$client_tag_name</span>";
    }
    $client_tags_display = implode(' ', $client_tag_name_display_array);

    // Get the number of responses
    $ticket_responses_sql = mysqli_query($mysqli, "SELECT COUNT(ticket_reply_id) AS ticket_responses FROM ticket_replies WHERE ticket_reply_archived_at IS NULL AND ticket_reply_ticket_id = $ticket_id");
    $row = mysqli_fetch_array($ticket_responses_sql);
    $ticket_responses = intval($row['ticket_responses']);

    // Get & format asset warranty expiry
    $date = date('Y-m-d H:i:s');
    $dt_value = $asset_warranty_expire;  // sample date
    $warranty_check = date('m/d/Y', strtotime('-8 hours'));

    if ($dt_value <= $date) {
        $dt_value = "Expired on $asset_warranty_expire";
        $warranty_status_color = 'red';
    } else {
        $warranty_status_color = 'green';
    }

    if ($asset_warranty_expire == 'NULL') {
        $dt_value = 'None';
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
        'SELECT users.user_id, user_name FROM users
        LEFT JOIN user_settings on users.user_id = user_settings.user_id
        WHERE user_role > 1
        AND user_status = 1
        AND user_archived_at IS NULL
        ORDER BY user_name ASC'
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

<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-fw fa-ticket-alt"></i> Ticket: <?php echo "$ticket_prefix$ticket_number"; ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<div class="modal-body">
            <!-- Client card -->
            <div class="card card-body card-outline card-primary mb-3">
                <h5><strong><?php echo $client_name; ?></strong></h5>
                <?php
                        if (!empty($location_phone)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2 mb-2"></i><?php echo $location_phone; ?>
                </div>
                <?php } ?>

                <?php
                        if (!empty($client_tags_display)) { ?>
                <div class="mt-1"><?php echo $client_tags_display; ?></div>
                <?php } ?>
            </div>
            <!-- End Client card -->

            <!-- Contact card -->
            <div class="card card-body card-outline mb-3">
                <h5 class="text-secondary">Contact</h5>

                <?php if (!empty($contact_id)) { ?>

                <div>
                    <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i><a href="#" data-toggle="modal"
                        data-target="#editTicketContactModal<?php echo $ticket_id; ?>"><strong><?php echo $contact_name; ?></strong>
                    </a>
                </div>

                <?php

                            if (!empty($location_name)) { ?>
                <div class="mt-2">
                    <i class="fa fa-fw fa-map-marker-alt text-secondary ml-1 mr-2"></i><?php echo $location_name; ?>
                </div>
                <?php }

                            if (!empty($contact_email)) { ?>
                <div class="mt-2">
                    <i class="fa fa-fw fa-envelope text-secondary ml-1 mr-2"></i><a
                        href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a>
                </div>
                <?php }

                            if (!empty($contact_phone)) { ?>
                <div class="mt-2">
                    <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i><a
                        href="tel:<?php echo $contact_phone; ?>"><?php echo $contact_phone; ?></a>
                </div>
                <?php }

                            if (!empty($contact_mobile)) { ?>
                <div class="mt-2">
                    <i class="fa fa-fw fa-mobile-alt text-secondary ml-1 mr-2"></i><a
                        href="tel:<?php echo $contact_mobile; ?>"><?php echo $contact_mobile; ?></a>
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
                        href="ticket.php?ticket_id=<?php echo $prev_ticket_id; ?>"><?php echo $prev_ticket_subject; ?></a>
                </div>
                <div class="mt-1">
                    <i class="fa fa-fw fa-hourglass-start text-secondary ml-1 mr-2"></i><strong>Status:</strong>
                    <span class="text-success"><?php echo $prev_ticket_status; ?></span>
                </div>
                <?php } ?>

                <?php } else { ?>
                <div class="d-print-none">
                    <a href="#" data-toggle="modal" data-target="#editTicketContactModal<?php echo $ticket_id; ?>"><i
                            class="fa fa-fw fa-plus mr-2"></i>Add a Contact</a>
                </div>
                <?php } ?>
            </div>
            <!-- End contact card -->


            <!-- Ticket watchers card -->
            <?php
                $sql_ticket_watchers = mysqli_query($mysqli, "SELECT * FROM ticket_watchers WHERE watcher_ticket_id = $ticket_id ORDER BY watcher_email DESC");

                if ($ticket_status !== "Closed" || mysqli_num_rows($sql_ticket_watchers) > 0) { ?>

                <div class="card card-body card-outline mb-3">
                    <h5 class="text-secondary">Watchers</h5>

                    <?php if ($ticket_status !== "Closed") { ?>
                    <div class="d-print-none">
                        <a href="#" data-toggle="modal" data-target="#addTicketWatcherModal"><i
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
                        <i class="fa fa-fw fa-eye text-secondary ml-1 mr-2"></i><?php echo $ticket_watcher_email; ?>
                        <?php if ($ticket_status !== "Closed") { ?>
                        <a class="confirm-link" href="/post.php?delete_ticket_watcher=<?php echo $watcher_id; ?>">
                            <i class="fas fa-fw fa-times text-secondary ml-1"></i>
                        </a>
                        <?php }
                        } ?>
                    </div>
            <?php } ?>
            <!-- End Ticket watchers card -->

            <!-- Ticket Details card -->
            <div class="card card-body card-outline mb-3">
                <h5 class="text-secondary">Details</h5>
                <div>
                    <i class="fa fa-fw fa-thermometer-half text-secondary ml-1 mr-2"></i><a href="#" data-toggle="modal"
                        data-target="#editTicketPriorityModal<?php echo $ticket_id; ?>"><?php echo $ticket_priority_display; ?></a>
                </div>
                <div class="mt-1">
                    <i class="fa fa-fw fa-calendar text-secondary ml-1 mr-2"></i>Created:
                    <?php echo $ticket_created_at; ?>
                </div>
                <div class="mt-2">
                    <i class="fa fa-fw fa-history text-secondary ml-1 mr-2"></i>Updated:
                    <strong><?php echo $ticket_updated_at; ?></strong>
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
                    <?php echo ucwords($ticket_closed_by_display); ?>
                </div>
                <div class="mt-1">
                    <i class="fa fa-fw fa-comment-dots text-secondary ml-1 mr-2"></i>Feedback:
                    <?php echo $ticket_feedback; ?>
                </div>
                <?php } ?>
                <!-- END Ticket closure info -->

                <?php
                        // Ticket scheduling
                        if ($ticket_status !== "Closed") { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-calendar-check text-secondary ml-1 mr-2"></i>Scheduled: <a href="#"
                        data-toggle="modal" data-target="#editTicketScheduleModal">
                        <?php echo $ticket_scheduled_wording ?> </a>
                </div>
                <?php }

                        // Time tracking
                        if (!empty($ticket_total_reply_time)) { ?>
                <div class="mt-1">
                    <i class="far fa-fw fa-clock text-secondary ml-1 mr-2"></i>Total time worked:
                    <?php echo $ticket_total_reply_time; ?>
                </div>
                <?php }

                        // Billable
                        if ($config_module_enable_accounting) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-dollar-sign text-secondary ml-1 mr-2"></i>Billable:
                    <a href="#" data-toggle="modal" data-target="#editTicketBillableModal<?php echo $ticket_id; ?>">
                        <?php
                                    if ($ticket_billable == 1) {
                                        echo "<span class='badge badge-pill badge-success p-2'>$</span>";
                                    } else {
                                        echo "<span class='badge badge-pill badge-secondary p-2'>X</span>";
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
                    <a href="#" data-toggle="modal" data-target="#editTicketAssetModal<?php echo $ticket_id; ?>"><i
                            class="fa fa-fw fa-plus mr-2"></i>Add an Asset</a>
                </div>

                <?php } else { ?>

                <div>
                    <a
                        href='client_asset_details.php?client_id=<?php echo $client_id ?>&asset_id=<?php echo $asset_id ?>'><i
                            class="fa fa-fw fa-desktop text-secondary ml-1 mr-2"></i><strong><?php echo $asset_name; ?></strong></a>
                </div>

                <?php if (!empty($asset_os)) { ?>
                <div class="mt-1">
                    <i class="fab fa-fw fa-microsoft text-secondary ml-1 mr-2"></i><?php echo $asset_os; ?>
                </div>
                <?php }

                            if (!empty($asset_ip)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-network-wired text-secondary ml-1 mr-2"></i><?php echo $asset_ip; ?>
                </div>
                <?php }

                            if (!empty($asset_make)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-tag text-secondary ml-1 mr-2"></i>Model:
                    <?php echo "$asset_make $asset_model"; ?>
                </div>
                <?php }

                            if (!empty($asset_serial)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-barcode text-secondary ml-1 mr-2"></i>Service Tag:
                    <?php echo $asset_serial; ?>
                </div>
                <?php }

                            if (!empty($asset_warranty_expire)) { ?>
                <div class="mt-1">
                    <i class="far fa-fw fa-calendar-alt text-secondary ml-1 mr-2"></i>Warranty expires:
                    <strong><?php echo $asset_warranty_expire ?></strong>
                </div>
                <?php }

                            if (!empty($asset_uri)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-globe text-secondary ml-1 mr-2"></i><a href="<?php echo $asset_uri; ?>"
                        target="_blank"><?php echo truncate($asset_uri, 25); ?></a>
                </div>
                <?php }

                        if ($ticket_asset_count > 0) { ?>

                <button class="btn btn-block btn-light mt-2 d-print-none" data-toggle="modal"
                    data-target="#assetTicketsModal">Service History (<?php echo $ticket_asset_count; ?>)</button>

                <div class="modal" id="assetTicketsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content bg-dark">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fa fa-fw fa-desktop"></i> <?php echo $asset_name; ?>
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
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
                                        href="ticket.php?ticket_id=<?php echo $service_ticket_id; ?>"><?php echo "$service_ticket_prefix$service_ticket_number" ?></a>
                                    <?php echo "on $service_ticket_created_at - <b>$service_ticket_subject</b> ($service_ticket_status)"; ?>
                                </p>
                                <?php
                                                }
                                                ?>
                            </div>
                            <div class="modal-footer bg-white">
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
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
                    <a href="#" data-toggle="modal" data-target="#editTicketVendorModal<?php echo $ticket_id; ?>"><i
                            class="fa fa-fw fa-plus mr-2"></i>Add a Vendor</a>
                </div>
                <?php } else { ?>
                <div>
                    <i
                        class="fa fa-fw fa-building text-secondary ml-1 mr-2"></i><strong><?php echo $vendor_name; ?></strong>
                </div>
                <?php

                            if (!empty($vendor_contact_name)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i><?php echo $vendor_contact_name; ?>
                </div>
                <?php }

                            if (!empty($ticket_vendor_ticket_number)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-tag text-secondary ml-1 mr-2"></i><?php echo $ticket_vendor_ticket_number; ?>
                </div>
                <?php }

                            if (!empty($vendor_email)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-envelope text-secondary ml-1 mr-2"></i><a
                        href="mailto:<?php echo $vendor_email; ?>"><?php echo $vendor_email; ?></a>
                </div>
                <?php }

                            if (!empty($vendor_phone)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i><?php echo $vendor_phone; ?>
                </div>
                <?php }

                            if (!empty($vendor_website)) { ?>
                <div class="mt-1">
                    <i class="fa fa-fw fa-globe text-secondary ml-1 mr-2"></i><?php echo $vendor_website; ?>
                </div>
                <?php } ?>

                <?php } //End Else
                        ?>
            </div>
            <!-- End Vendor card -->

            <!-- Products card -->
            <?php 
                    if ($config_module_enable_accounting == 1) {
                        ?>
            <div class="card card-body card-outline mb-3">
                <h5 class="text-secondary">Products</h5>
                <div class="d-print-none">
                    <a href="#" data-toggle="modal" data-target="#addTicketProductModal<?php echo $ticket_id; ?>"><i
                            class="fa fa-fw fa-plus mr-2"></i>Manage Products</a>
                </div>
                <?php echo $ticket_products_display; ?>
            </div>
            <?php
                    }
                    ?>
                <!-- Assigned to -->
                <form action="/post.php" method="post">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                    <input type="hidden" name="ticket_status" value="<?php echo $ticket_status; ?>">
                    <div class="form-group">
                        <label>Assigned to</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <select class="form-control select2" name="assigned_to" <?php if ($ticket_status == "Closed") {
                                                                                        echo "disabled";
                                                                                    } ?>>
                                <option value="0">Not Assigned</option>
                                <?php

                                while ($row = mysqli_fetch_array($sql_assign_to_select)) {
                                    $user_id = intval($row['user_id']);
                                    $user_name = nullable_htmlentities($row['user_name']); ?>
                                    <option <?php if ($ticket_assigned_to == $user_id) {
                                                echo "selected";
                                            } ?> value="<?php echo $user_id; ?>"><?php echo $user_name; ?></option>
                                <?php } ?>
                            </select>
                            <div class="input-group-append d-print-none">
                                <button type="submit" class="btn btn-soft-primary" name="assign_ticket" <?php if ($ticket_status == "Closed") {
                                                                                                        echo "disabled";
                                                                                                    } ?>><i class="fas fa-check"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End Assigned to -->

            <div class="card card-body card-outline mb-2 d-print-none">
                <?php if ($config_module_enable_accounting && $ticket_billable == 1) { ?>
                <a href="#" class="btn btn-info btn-block" href="#" data-toggle="modal"
                    data-target="#addInvoiceFromTicketModal">
                    <i class="fas fa-fw fa-file-invoice mr-2"></i>Invoice Ticket
                </a>
                <?php }

                        if ($ticket_status !== "Closed") { ?>
                <a href="/post.php?close_ticket=<?php echo $ticket_id; ?>"
                    class="btn btn-light btn-block confirm-link" id="ticket_close">
                    <i class="fas fa-fw fa-gavel mr-2"></i>Close Ticket
                </a>
                <?php } ?>
            </div>


</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-fw fa-times"></i> Close</button>
</div>

<?php } ?>