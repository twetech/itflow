<?php


// Default Column Sortby Filter
$sort = "ticket_number";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


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
        $ticket_status_snippet = "ticket_status != 5";
    } elseif (isset($_GET['status']) && ($_GET['status']) == 5) {
        $status = '5';
        $ticket_status_snippet = "ticket_status = 5";
    } else {
        $status = 'Open';
        $ticket_status_snippet = "ticket_status != 5";
    }
}

// Ticket assignment status filter
if (isset($_GET['assigned']) & !empty($_GET['assigned'])) {
    $ticket_assigned_filter = 'AND ticket_assigned_to = '.$session_user_id;
    // Unassigned
    if ($_GET['assigned'] == 'unassigned') {
        $ticket_assigned_filter = 'AND ticket_assigned_to = 0';
    }
    // Assigned to any
    elseif ($_GET['assigned'] == 'all') {
        $ticket_assigned_filter = '';
    }
} else {
    // Default - Assigned to me or unassigned
    $ticket_assigned_filter = 'AND (ticket_assigned_to = 0 OR ticket_assigned_to = ' . $session_user_id . ')';
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
    LEFT JOIN ticket_statuses ON ticket_status = ticket_status_id
    WHERE $ticket_status_snippet " . $ticket_assigned_filter);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

//Get Total tickets open
$sql_total_tickets_open = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_open FROM tickets WHERE ticket_status != '5'");
$row = mysqli_fetch_array($sql_total_tickets_open);
$total_tickets_open = intval($row['total_tickets_open']);

//Get Total tickets closed
$sql_total_tickets_closed = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_closed FROM tickets WHERE ticket_status = '5'");
$row = mysqli_fetch_array($sql_total_tickets_closed);
$total_tickets_closed = intval($row['total_tickets_closed']);

//Get Total Recurring (scheduled) tickets
$sql_total_scheduled_tickets = mysqli_query($mysqli, "SELECT COUNT(scheduled_ticket_id) AS total_scheduled_tickets FROM scheduled_tickets");
$row = mysqli_fetch_array($sql_total_scheduled_tickets);
$total_scheduled_tickets = intval($row['total_scheduled_tickets']);

//Get Unassigned tickets
$sql_total_tickets_unassigned = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_unassigned FROM tickets WHERE ticket_assigned_to = '0' AND ticket_status != '5'");
$row = mysqli_fetch_array($sql_total_tickets_unassigned);
$total_tickets_unassigned = intval($row['total_tickets_unassigned']);

//Get Total tickets assigned to me
$sql_total_tickets_assigned = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_assigned FROM tickets WHERE ticket_assigned_to = $session_user_id AND ticket_status != '5'");
$row = mysqli_fetch_array($sql_total_tickets_assigned);
$user_active_assigned_tickets = intval($row['total_tickets_assigned']);

?>
<style>
    .popover {
        max-width: 600px;
    }
</style>
<div class="card">
    <?php require_once '/var/www/portal.twe.tech/includes/support_card_header.php'; // Support Card Header ?>
    <div class="card-body">
        <form id="bulkActions" action="/post/" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
            <div class="card-datatable table-responsive container-fluid  container-fluid pt-0">
                <table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if (!$num_rows[0]) { echo "d-none"; } ?>">
                            <tr>
                                <?php
                                // table head
                                if (!$session_mobile) {
                                    $rows = [ 'Number', 'Subject', 'Client / Contact', 'Priority', 'Status', 'Assigned', 'Last Response', 'Created' ];
                                    $datatable_order = "[[7,'desc']]";
                                    $datatable_priority = [
                                        'Number' => 1,
                                        'Subject' => 2,
                                        'Assigned' => 3
                                    ];

                                } else {
                                    $rows = [ 'Subject', 'Client', 'Number', 'Status', 'Assigned', 'Last Response', 'Created' ];
                                    $datatable_order = "[[6,'desc']]";
                                    $datatable_priority = [
                                        'Status' => 1,
                                        'Number' => 3,
                                        'Subject' => 2,
                                        'Assigned' => 4,
                                        'Client' => 5,
                                        'Last Response' => 6
                                    ];
                                }
                                
                                if ($config_module_enable_accounting) {
                                    $rows[] = 'Billable';
                                }
                                foreach ($rows as $row) {
                                    if (isset($datatable_priority[$row])) {
                                        echo "<th data-priority='" . $datatable_priority[$row] . "'>$row</th>";
                                    } else {
                                        echo "<th>$row</th>";
                                    }
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
                                $ticket_status = nullable_htmlentities($row['ticket_status_name']);
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

                                //$datetime_format="date-time-format";
                                $datetime_format="date-time-ago";

                                if (!$session_mobile) {
                                    
                            ?>
                                <tr class="<?= empty($ticket_updated_at) ? "text-bold" : "" ?>">

                                    <td>
                                        <small>
                                            <a href="ticket.php?ticket_id=<?= $ticket_id ?>">
                                                <span class="badge rounded-pill bg-label-secondary p-3"><?=$ticket_number?></span>
                                            </a>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <a href="ticket.php?ticket_id=<?= $ticket_id ?>"><?= $ticket_subject ?></a>
                                        </small>
                                    </td>
                                    <td>
                                        <a href="client_tickets.php?client_id=<?= $client_id ?>"><strong><?= $client_name ?></strong></a>

                                        <div class="mt-1"><?= $contact_display ?></div>
                                    </td>
                                    <td><a href="#" class="loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_priority_modal.php?ticket_id=<?php echo $ticket_id; ?>"><span class='p-2 badge rounded-pill bg-label-<?php echo $ticket_priority_color; ?>'><?php echo $ticket_priority; ?></span></a></td>
                                    <td><span class='p-2 badge rounded-pill bg-label-<?= $ticket_status_color ?>'><?= $ticket_status; ?></span> <?php if ($ticket_status == 'On Hold' && isset ($ticket_scheduled_for)) { echo "<div class=\"mt-1\"> <small class='text-secondary'> $ticket_scheduled_for </small></div>"; } ?></td>
                                    <td><a href="#" class="loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_assign_modal.php?ticket_id=<?php echo $ticket_id; ?>"><?php echo $ticket_assigned_to_display; ?></a></td>
                                    <td <?= $ticket_updated_at ? "class='" . $datetime_format . "'" : '' ?>><?= $ticket_updated_at ? $ticket_updated_at : 'never'; ?></td>
                                    <td class="<?= $datetime_format?>">
                                        <?= $ticket_created_at ?>
                                    </td>

                                    <?php if ($config_module_enable_accounting) { ?>
                                        <td class="text-center">
                                            <a href="#" class="loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_billable_modal.php?ticket_id=<?php echo $ticket_id; ?>">
                                                <?php
                                                    if ($ticket_billable == 1) {
                                                        echo "<span class='badge rounded-pill bg-label-success'>$</span>";
                                                    } else {
                                                        echo "<span class='badge rounded-pill bg-label-secondary'>X</span>";
                                                    }
                                                ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php
                                } else {
                            ?>
                                <tr class="<?= empty($ticket_updated_at) ? "text-bold" : "" ?>">
                                    <td data-priority="1">
                                        <!-- subject -->
                                        <a href="ticket.php?ticket_id=<?= $ticket_id ?>">
                                            <strong><?= $ticket_subject ?></strong>
                                        </a>
                                    </td>
                                    <td>
                                        <!-- client -->
                                        <a href="client_tickets.php?client_id=<?= $client_id ?>">
                                            <strong><?= $client_name ?></strong>
                                        </a>
                                    </td>
                                    <td data-priority="2">
                                        <!-- ticket number -->
                                        <small>
                                            <a href="ticket.php?ticket_id=<?= $ticket_id ?>">
                                                <span class="badge rounded-pill bg-label-secondary p-3"><?=$ticket_prefix . $ticket_number?></span>
                                            </a>
                                        </small>
                                    </td>
                                    <td>
                                        <!-- status -->
                                        <span class='p-2 badge rounded-pill bg-label-<?= $ticket_status_color ?>'><?= $ticket_status; ?></span>
                                    </td>
                                    <td>
                                        <!-- assigned -->
                                        <a href="#" class="loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_assign_modal.php?ticket_id=<?php echo $ticket_id; ?>"><?php echo $ticket_assigned_to_display; ?></a>
                                    </td>
                                    <td <?= $ticket_updated_at ? "class='" . $datetime_format . "'" : '' ?>>
                                        <!-- last response -->
                                        <?= $ticket_updated_at ? $ticket_updated_at : 'never'; ?>
                                    </td>
                                    <td class="<?= $datetime_format?>">
                                        <!-- created -->
                                        <?= $ticket_created_at ?>
                                    </td>
                                    <td>
                                        <!-- billable -->
                                        <?php if ($config_module_enable_accounting) { ?>
                                            <a href="#" class="loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_billable_modal.php?ticket_id=<?php echo $ticket_id; ?>">
                                                <?php
                                                    if ($ticket_billable == 1) {
                                                        echo "<span class='badge rounded-pill bg-label-success'>$</span>";
                                                    } else {
                                                        echo "<span class='badge rounded-pill bg-label-secondary'>X</span>";
                                                    }
                                                ?>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                                }
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

<?php

require_once "/var/www/portal.twe.tech/includes/footer.php";
