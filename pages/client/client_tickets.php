<?php


// Default Column Sortby Filter
$sort = "ticket_number";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";

if (isset($_GET['status']) && ($_GET['status']) == 2) {
    $status = 'Open';
    $ticket_status_snippet = "AND ticket_status != 5";
} elseif (isset($_GET['status']) && ($_GET['status']) == 5) {
    $status = 'Closed';
    $ticket_status_snippet = "AND ticket_status = 5";
} else {
    $status = 'Open';
    $ticket_status_snippet = "AND ticket_status != 5";
}

if (($_GET['billable']) == '1') {
    if (isset($_GET['unbilled'])) {
        $billable = 1;
        $ticket_billable_snippet = "AND ticket_billable = 1 AND ticket_invoice_id = 0";
        $ticket_status_snippet = 'AND (ticket_status = 5 OR ticket_status = 4)';
    }
} else {
    $billable = 0;
}

//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM tickets
    LEFT JOIN contacts ON ticket_contact_id = contact_id
    LEFT JOIN users ON ticket_assigned_to = user_id
    LEFT JOIN assets ON ticket_asset_id = asset_id
    LEFT JOIN locations ON ticket_location_id = location_id
    LEFT JOIN vendors ON ticket_vendor_id = vendor_id
    LEFT JOIN ticket_statuses ON ticket_status = ticket_status_id
    WHERE ticket_client_id = $client_id
    $ticket_status_snippet
    $ticket_billable_snippet
    AND (CONCAT(ticket_prefix,ticket_number) LIKE '%$q%' OR ticket_subject LIKE '%$q%' OR ticket_status LIKE '%$q%' OR ticket_priority LIKE '%$q%' OR user_name LIKE '%$q%' OR contact_name LIKE '%$q%' OR asset_name LIKE '%$q%' OR vendor_name LIKE '%$q%' OR ticket_vendor_ticket_number LIKE '%q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

//Get Total tickets open
$sql_total_tickets_open = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_open FROM tickets WHERE ticket_client_id = $client_id AND ticket_status != 5");
$row = mysqli_fetch_array($sql_total_tickets_open);
$total_tickets_open = intval($row['total_tickets_open']);

//Get Total tickets closed
$sql_total_tickets_closed = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_closed FROM tickets WHERE ticket_client_id = $client_id AND ticket_status = 5");
$row = mysqli_fetch_array($sql_total_tickets_closed);
$total_tickets_closed = intval($row['total_tickets_closed']);

//Get Total Scheduled tickets
$sql_total_scheduled_tickets = mysqli_query($mysqli, "SELECT COUNT(scheduled_ticket_id) AS total_scheduled_tickets FROM scheduled_tickets WHERE scheduled_ticket_client_id = $client_id");
$row = mysqli_fetch_array($sql_total_scheduled_tickets);
$total_scheduled_tickets = intval($row['total_scheduled_tickets']);

?>

<div class="card">

    <?php require_once '/var/www/portal.twe.tech/includes/support_card_header.php'; ?>

    <div class="card-body">

        <hr>
        <div class="card-datatable table-responsive container-fluid  pt-0">               
            <table class="datatables-basic table border-top">
                <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                <tr>
                    <th>Number</a></th>
                    <th>Subject</th>
                    <th>Contact</th>
                    <?php if ($config_module_enable_accounting) { ?>
                        <th class="text-center">Billable</a></th>
                    <?php } ?>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned</th>
                    <th>Last Response</th>
                    <th>Created</th>

                </tr>
            </thead>
            <tbody>
                <?php

                while ($row = mysqli_fetch_array($sql)) {
                    $ticket_id = intval($row['ticket_id']);
                    $ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
                    $ticket_number = nullable_htmlentities($row['ticket_number']);
                    $ticket_subject = nullable_htmlentities($row['ticket_subject']);
                    $ticket_priority = nullable_htmlentities($row['ticket_priority']);
                    $ticket_status = nullable_htmlentities($row['ticket_status_name']);
                    $ticket_billable = intval($row['ticket_billable']);
                    $ticket_vendor_ticket_number = nullable_htmlentities($row['ticket_vendor_ticket_number']);
                    $ticket_created_at = nullable_htmlentities($row['ticket_created_at']);
                    $ticket_created_at_time_ago = timeAgo($row['ticket_created_at']);
                    $ticket_updated_at = nullable_htmlentities($row['ticket_updated_at']);
                    $ticket_updated_at_time_ago = timeAgo($row['ticket_updated_at']);
                    if (empty($ticket_updated_at)) {
                        if ($ticket_status == 5) {
                            $ticket_updated_at_display = "<p>Never</p>";
                        } else {
                            $ticket_updated_at_display = "<p class='text-danger'>Never</p>";
                        }
                    } else {
                        $ticket_updated_at_display = "$ticket_updated_at_time_ago<br><small class='text-secondary'>$ticket_updated_at</small>";
                    }
                    $ticket_closed_at = nullable_htmlentities($row['ticket_closed_at']);

                    $ticket_status_color = getTicketStatusColor($ticket_status);

                    if ($ticket_priority == "High") {
                        $ticket_priority_display = "<span class='p-2 badge badge-danger'>$ticket_priority</span>";
                    } elseif ($ticket_priority == "Medium") {
                        $ticket_priority_display = "<span class='p-2 badge badge-warning'>$ticket_priority</span>";
                    } elseif ($ticket_priority == "Low") {
                        $ticket_priority_display = "<span class='p-2 badge badge-info'>$ticket_priority</span>";
                    } else{
                        $ticket_priority_display = "-";
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
                    $contact_id = intval($row['ticket_contact_id']);
                    $contact_name = nullable_htmlentities($row['contact_name']);
                    $contact_title = nullable_htmlentities($row['contact_title']);
                    $contact_email = nullable_htmlentities($row['contact_email']);
                    $contact_phone = formatPhoneNumber($row['contact_phone']);
                    $contact_extension = nullable_htmlentities($row['contact_extension']);
                    $contact_mobile = formatPhoneNumber($row['contact_mobile']);
                    $contact_archived_at = nullable_htmlentities($row['contact_archived_at']);
                    if (empty($contact_archived_at)) {
                        $contact_archived_display = "";
                    } else {
                        $contact_archived_display = "Archived - ";
                    }
                    if (empty($contact_name)) {
                        $contact_display = "-";
                    } else {
                        $contact_display = "$contact_archived_display$contact_name<br><small class='text-secondary'>$contact_email</small>";
                    }


                    $asset_id = intval($row['ticket_asset_id']);
                    $vendor_id = intval($row['ticket_vendor_id']);

                    ?>

                    <tr class="<?php if(empty($ticket_updated_at)) { echo "text-bold"; }?>">
                        <td><a href="/pages/ticket.php?ticket_id=<?= $ticket_id; ?>"><span class="badge rounded-pill bg-label-secondary p-3"><?= "$ticket_prefix$ticket_number"; ?></span></a></td>
                        <td>
                            <a href="/pages/ticket.php?ticket_id=<?= $ticket_id; ?>"><?= $ticket_subject; ?></a>
                        </td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#editTicketContactModal<?= $ticket_id; ?>"><?= $contact_display; ?></a></td>

                        <?php if ($config_module_enable_accounting) { ?>
                        <td class="text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editTicketBillableModal<?= $ticket_id; ?>">
                            <?php
                                if ($ticket_billable == 1) {
                                    echo "<span class='badge rounded-pill bg-label-success'>$</span>";
                                } else {
                                    echo "<span class='badge rounded-pill bg-label-secondary'>X</span>";
                                }
                            ?>
                        </td>
                        <?php } ?>

                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#editTicketPriorityModal<?= $ticket_id; ?>"><?= $ticket_priority_display; ?></a></td>
                        <td><span class='p-2 badge rounded-pill bg-label-<?= $ticket_status_color; ?>'><?= $ticket_status; ?></span></td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#assignTicketModal<?= $ticket_id; ?>"><?= $ticket_assigned_to_display; ?></a></td>
                        <td><?= $ticket_updated_at_display; ?></td>
                        <td>
                            <?= $ticket_created_at_time_ago; ?>
                            <br>
                            <small class="text-secondary"><?= $ticket_created_at; ?></small>
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

?>
