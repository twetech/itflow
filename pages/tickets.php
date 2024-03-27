<?php


// Default Column Sortby Filter
$sort = "ticket_number";
$order = "DESC";

require_once "/var/www/develop.twe.tech/includes/inc_all.php";


// Ticket status from GET
if (isset($_GET['status']) && is_array($_GET['status']) && !empty($_GET['status'])) {
    // Sanitize each element of the status array
    $sanitizedStatuses = array();
    foreach ($_GET['status'] as $status) {
        // Escape each status to prevent SQL injection
        $sanitizedStatuses[] = "'" . sanitizeInput($status) . "'";
    }

    // Convert the sanitized statuses into a comma-separated string
    $sanitizedStatusesString = implode(",", $sanitizedStatuses);
    $ticket_status_snippet = "ticket_status IN ($sanitizedStatusesString)";
} else {

    if (isset($_GET['status']) && ($_GET['status']) == 'Open') {
        $status = 'Open';
        $ticket_status_snippet = "ticket_status != 'Closed'";
    } elseif (isset($_GET['status']) && ($_GET['status']) == 'Closed') {
        $status = 'Closed';
        $ticket_status_snippet = "ticket_status = 'Closed'";
    } else {
        $status = 'Open';
        $ticket_status_snippet = "ticket_status != 'Closed'";
    }
}

// Ticket assignment status filter
if (isset($_GET['assigned']) & !empty($_GET['assigned'])) {
    if ($_GET['assigned'] == 'unassigned') {
        $ticket_assigned_filter = 'AND ticket_assigned_to = 0';
    } else {
        $ticket_assigned_filter = 'AND ticket_assigned_to = ' . intval($_GET['assigned']);
    }
} else {
    // Default - any
    $ticket_assigned_filter = '';
}

//Rebuild URL
$url_query_strings_sort = http_build_query(array_merge($_GET, array('sort' => $sort, 'order' => $order, 'status' => $status, 'assigned' => $ticket_assigned_filter)));

// Main ticket query:
$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM tickets
    LEFT JOIN clients ON ticket_client_id = client_id
    LEFT JOIN contacts ON ticket_contact_id = contact_id
    LEFT JOIN users ON ticket_assigned_to = user_id
    LEFT JOIN assets ON ticket_asset_id = asset_id
    LEFT JOIN locations ON ticket_location_id = location_id
    LEFT JOIN vendors ON ticket_vendor_id = vendor_id
    WHERE $ticket_status_snippet " . $ticket_assigned_filter . "
    AND (CONCAT(ticket_prefix,ticket_number) LIKE '%$q%' OR client_name LIKE '%$q%' OR ticket_subject LIKE '%$q%' OR ticket_status LIKE '%$q%' OR ticket_priority LIKE '%$q%' OR user_name LIKE '%$q%' OR contact_name LIKE '%$q%' OR asset_name LIKE '%$q%' OR vendor_name LIKE '%$q%' OR ticket_vendor_ticket_number LIKE '%q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

//Get Total tickets open
$sql_total_tickets_open = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_open FROM tickets WHERE ticket_status != 'Closed'");
$row = mysqli_fetch_array($sql_total_tickets_open);
$total_tickets_open = intval($row['total_tickets_open']);

//Get Total tickets closed
$sql_total_tickets_closed = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_closed FROM tickets WHERE ticket_status = 'Closed'");
$row = mysqli_fetch_array($sql_total_tickets_closed);
$total_tickets_closed = intval($row['total_tickets_closed']);

//Get Total Recurring (scheduled) tickets
$sql_total_scheduled_tickets = mysqli_query($mysqli, "SELECT COUNT(scheduled_ticket_id) AS total_scheduled_tickets FROM scheduled_tickets");
$row = mysqli_fetch_array($sql_total_scheduled_tickets);
$total_scheduled_tickets = intval($row['total_scheduled_tickets']);

//Get Unassigned tickets
$sql_total_tickets_unassigned = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_unassigned FROM tickets WHERE ticket_assigned_to = '0' AND ticket_status != 'Closed'");
$row = mysqli_fetch_array($sql_total_tickets_unassigned);
$total_tickets_unassigned = intval($row['total_tickets_unassigned']);

//Get Total tickets assigned to me
$sql_total_tickets_assigned = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_assigned FROM tickets WHERE ticket_assigned_to = $session_user_id AND ticket_status != 'Closed'");
$row = mysqli_fetch_array($sql_total_tickets_assigned);
$user_active_assigned_tickets = intval($row['total_tickets_assigned']);

?>
<style>
    .popover {
        max-width: 600px;
    }
</style>
<div class="card">
    <header class="card-header d-flex align-items-center">
        <div class="col">
            <h3 class="card-title mt-2"><i class="fa fa-fw fa-life-ring mr-2"></i>Support Tickets</h3>
            <small class="ml-3">
                    <a href="?status=Open"><strong><?php echo $total_tickets_open; ?></strong> Open</a> |
                    <a href="?status=Closed"><strong><?php echo $total_tickets_closed; ?></strong> Closed</a>
            </small>
        </div>
        <div class="col">
            <div class="btn-group">
                <button class="btn btn-outline-dark dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown">
                    <i class="fa fa-fw fa-envelope mr-2"></i><?php if(!$session_mobile) { echo "My Tickets"; } ?>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="?status=Open&assigned=<?php echo $session_user_id ?>">Active tickets (<?php echo $user_active_assigned_tickets ?>)</a>
                    <a class="dropdown-item " href="?status=Closed&assigned=<?php echo $session_user_id ?>">Closed tickets</a>
                </div>
            </div>
            <a href="?assigned=unassigned" class="btn btn-outline-danger">
                <i class="fa fa-fw fa-exclamation-triangle mr-2"></i><?php if(!$session_mobile) { echo "Unassigned"; } ?> | <strong><?php echo $total_tickets_unassigned; ?></strong>
            </a>
            <a href="recurring_tickets.php" class="btn btn-outline-info">
                <i class="fa fa-fw fa-redo-alt mr-2"></i><?php if(!$session_mobile) { echo "Recurring"; } ?> | <strong> <?php echo $total_scheduled_tickets; ?></strong>
            </a>
        </div>
            <?php if ($session_user_role == 3) { ?>
                <ul class="list-inline ml-auto mb0">
                    <li class="list-inline-item mr3">
                        <a href="#!" class="dropdown-item loadModalContentBtn" data-toggle="modal" data-target="#dynamicModal" data-modal-file="ticket_add_modal.php">
                            <i class="fa fa-fw fa-plus mr-2"></i>
                        </a>
                    </li>
                </ul>
            <?php } ?>
    </header>
    <div class="card-body">
        <form id="bulkActions" action="/post/" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
            <div class="table-responsive-sm">
                <table id='responsive' class=" table table-hover responsive">
                    <thead class="text-dark <?php if (!$num_rows[0]) { echo "d-none"; } ?>">

                            <tr>
                                <?php
                                // table head

                                if (!$session_mobile) {
                                    $rows = [ 'Number', 'Subject', 'Client / Contact', 'Priority', 'Status', 'Assigned', 'Last Response', 'Created' ];
                                    $datatable_order = "[[7,'desc']]";
                                } else {
                                    $rows = [ 'Subject', 'Client', 'Number', 'Status', 'Assigned', 'Last Response', 'Created' ];
                                    $datatable_order = "[[6,'desc']]";
                                }
                                
                                if ($config_module_enable_accounting) {
                                    $rows[] = 'Billable';
                                }
                                foreach ($rows as $row) {
                                    echo "<th>$row</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            while ($row = mysqli_fetch_array($sql)) {
                                $ticket_id = intval($row['ticket_id']);
                                $ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
                                $ticket_number = intval($row['ticket_number']);
                                $ticket_subject = nullable_htmlentities($row['ticket_subject']);
                                $ticket_priority = nullable_htmlentities($row['ticket_priority']);
                                $ticket_status = nullable_htmlentities($row['ticket_status']);
                                $ticket_billable = intval($row['ticket_billable']);
                                $ticket_scheduled_for = nullable_htmlentities($row['ticket_schedule']);
                                $ticket_vendor_ticket_number = nullable_htmlentities($row['ticket_vendor_ticket_number']);
                                $ticket_created_at = nullable_htmlentities($row['ticket_created_at']);
                                $ticket_created_at_time_ago = timeAgo($row['ticket_created_at']);
                                $ticket_updated_at = nullable_htmlentities($row['ticket_updated_at']);
                                $ticket_updated_at_time_ago = timeAgo($row['ticket_updated_at']);
                                if (empty($ticket_updated_at)) {
                                    if ($ticket_status == "Closed") {
                                        $ticket_updated_at_display = "<p>Never</p>";
                                    } else {
                                        $ticket_updated_at_display = "<p class='text-danger'>Never</p>";
                                    }
                                } else {
                                    $ticket_updated_at_display = "$ticket_updated_at_time_ago<br><small class='text-secondary'>$ticket_updated_at</small>";
                                }
                                $ticket_closed_at = nullable_htmlentities($row['ticket_closed_at']);
                                $client_id = intval($row['ticket_client_id']);
                                $client_name = nullable_htmlentities($row['client_name']);
                                $contact_id = intval($row['ticket_contact_id']);
                                $contact_name = nullable_htmlentities($row['contact_name']);
                                $contact_title = nullable_htmlentities($row['contact_title']);
                                $contact_email = nullable_htmlentities($row['contact_email']);
                                $contact_phone = formatPhoneNumber($row['contact_phone']);
                                $contact_extension = nullable_htmlentities($row['contact_extension']);
                                $contact_mobile = formatPhoneNumber($row['contact_mobile']);

                                $ticket_status_color = getTicketStatusColor($ticket_status);

                                if ($ticket_priority == "High") {
                                    $ticket_priority_color = "danger";
                                } elseif ($ticket_priority == "Medium") {
                                    $ticket_priority_color = "warning";
                                } else {
                                    $ticket_priority_color = "info";
                                }

                                $ticket_assigned_to = intval($row['ticket_assigned_to']);
                                if (empty($ticket_assigned_to)) {
                                    if ($ticket_status == "Closed") {
                                        $ticket_assigned_to_display = "<p>Not Assigned</p>";
                                    } else {
                                        $ticket_assigned_to_display = "<p class='text-danger'>Not Assigned</p>";
                                    }
                                } else {
                                    $ticket_assigned_to_display = nullable_htmlentities($row['user_name']);
                                }

                                if (empty($contact_name)) {
                                    $contact_display = "-";
                                } else {
                                    $contact_display = "$contact_name<br><small class='text-secondary'>$contact_email</small>";
                                }

                                $asset_id = intval($row['ticket_asset_id']);
                                $vendor_id = intval($row['ticket_vendor_id']);

                            ?>

                                <tr class="<?= empty($ticket_updated_at) ? "text-bold" : "" ?>">

                                    <td>
                                        <a href="ticket.php?ticket_id=<?php echo $ticket_id; ?>">
                                            <span class="badge badge-pill badge-secondary p-3"><?php echo "$ticket_prefix$ticket_number"; ?></span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="ticket.php?ticket_id=<?php echo $ticket_id; ?>"><?php echo $ticket_subject; ?></a>
                                    </td>
                                    <td>
                                        <a href="client_tickets.php?client_id=<?php echo $client_id; ?>"><strong><?php echo $client_name; ?></strong></a>

                                        <div class="mt-1"><?php echo $contact_display; ?></div>
                                    </td>
                                    <td><a href="#" class="loadModalContentBtn" data-toggle="modal" data-target="#dynamicModal" data-modal-file="ticket_edit_priority_modal.php?ticket_id=<?php echo $ticket_id; ?>"><span class='p-2 badge badge-pill badge-<?php echo $ticket_priority_color; ?>'><?php echo $ticket_priority; ?></span></a></td>
                                    <td><span class='p-2 badge badge-pill badge-<?php echo $ticket_status_color; ?>'><?php echo $ticket_status; ?></span> <?php if ($ticket_status == 'On Hold' && isset ($ticket_scheduled_for)) { echo "<div class=\"mt-1\"> <small class='text-secondary'> $ticket_scheduled_for </small></div>"; } ?></td>
                                    <td><a href="#" class="loadModalContentBtn" data-toggle="modal" data-target="#dynamicModal" data-modal-file="ticket_assign_modal.php?ticket_id=<?php echo $ticket_id; ?>"><?php echo $ticket_assigned_to_display; ?></a></td>
                                    <td><?php echo $ticket_updated_at_display; ?></td>
                                    <td>
                                        <?php echo $ticket_created_at_time_ago; ?>
                                        <br>
                                        <small class="text-secondary"><?php echo $ticket_created_at; ?></small>
                                    </td>

                                    <?php if ($config_module_enable_accounting) { ?>
                                        <td class="text-center">
                                            <a href="#" class="loadModalContentBtn" data-toggle="modal" data-target="#dynamicModal" data-modal-file="ticket_edit_billable_modal.php?ticket_id=<?php echo $ticket_id; ?>">
                                                <?php
                                                    if ($ticket_billable == 1) {
                                                        echo "<span class='badge badge-pill badge-success'>$</span>";
                                                    } else {
                                                        echo "<span class='badge badge-pill badge-secondary'>X</span>";
                                                    }
                                                ?>
                                        </td>
                                    <?php } ?>
                                </tr>

                            <?php
                        }

                        ?>

                    </tbody>
                </table>
            </div>

        </form>
        <?php 
        ?>
    </div>
</div>

<script src="js/bulk_actions.js"></script>

<?php

require_once "/var/www/develop.twe.tech/includes/footer.php";
