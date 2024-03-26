<?php


// Default Column Sortby Filter
$sort = "ticket_number";
$order = "DESC";

require_once "/var/www/develop.twe.tech/includes/inc_all_client.php";

if (isset($_GET['status']) && ($_GET['status']) == 'Open') {
    $status = 'Open';
    $ticket_status_snippet = "AND ticket_status != 'Closed'";
} elseif (isset($_GET['status']) && ($_GET['status']) == 'Closed') {
    $status = 'Closed';
    $ticket_status_snippet = "AND ticket_status = 'Closed'";
} else {
    $status = 'Open';
    $ticket_status_snippet = "AND ticket_status != 'Closed'";
}

if (($_GET['billable']) == '1') {
    if (isset($_GET['unbilled'])) {
        $billable = 1;
        $ticket_billable_snippet = "AND ticket_billable = 1 AND ticket_invoice_id = 0";
        $ticket_status_snippet = 'AND (ticket_status = "Closed" OR ticket_status = "Auto-Close")';
    }
} else {
    $billable = 0;
}

//Rebuild URL
$url_query_strings_sort = http_build_query($get_copy);

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM tickets
    LEFT JOIN contacts ON ticket_contact_id = contact_id
    LEFT JOIN users ON ticket_assigned_to = user_id
    LEFT JOIN assets ON ticket_asset_id = asset_id
    LEFT JOIN locations ON ticket_location_id = location_id
    LEFT JOIN vendors ON ticket_vendor_id = vendor_id
    WHERE ticket_client_id = $client_id
    $ticket_status_snippet
    $ticket_billable_snippet
    AND (CONCAT(ticket_prefix,ticket_number) LIKE '%$q%' OR ticket_subject LIKE '%$q%' OR ticket_status LIKE '%$q%' OR ticket_priority LIKE '%$q%' OR user_name LIKE '%$q%' OR contact_name LIKE '%$q%' OR asset_name LIKE '%$q%' OR vendor_name LIKE '%$q%' OR ticket_vendor_ticket_number LIKE '%q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

//Get Total tickets open
$sql_total_tickets_open = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_open FROM tickets WHERE ticket_client_id = $client_id AND ticket_status != 'Closed'");
$row = mysqli_fetch_array($sql_total_tickets_open);
$total_tickets_open = intval($row['total_tickets_open']);

//Get Total tickets closed
$sql_total_tickets_closed = mysqli_query($mysqli, "SELECT COUNT(ticket_id) AS total_tickets_closed FROM tickets WHERE ticket_client_id = $client_id AND ticket_status = 'Closed'");
$row = mysqli_fetch_array($sql_total_tickets_closed);
$total_tickets_closed = intval($row['total_tickets_closed']);

//Get Total Scheduled tickets
$sql_total_scheduled_tickets = mysqli_query($mysqli, "SELECT COUNT(scheduled_ticket_id) AS total_scheduled_tickets FROM scheduled_tickets WHERE scheduled_ticket_client_id = $client_id");
$row = mysqli_fetch_array($sql_total_scheduled_tickets);
$total_scheduled_tickets = intval($row['total_scheduled_tickets']);

?>

<div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fa fa-fw fa-life-ring mr-2"></i><?php if (isset($_GET['unbilled'])) { echo "Unbilled "; } ?> Tickets
            <small class="ml-3">
                <a href="?client_id=<?php echo $client_id?>&status=Open" class="text-white"><strong><?php echo $total_tickets_open; ?></strong> Open</a> |
                <a href="?client_id=<?php echo $client_id?>&status=Closed" class="text-white"><strong><?php echo $total_tickets_closed; ?></strong> Closed</a>
            </small>
        </h3>
        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-soft-primary loadModalContentBtn" data-toggle="modal" data-target="#dynamicModal" data-modal-file="ticket_add_modal.php?client_id=<?php echo $client_id; ?>">
                    <i class="fas fa-plus mr-2"></i>New Ticket
                </button>
                <button type="button" class="btn btn-soft-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item text-dark" href="#" data-toggle="modal" data-target="#exportTicketModal">
                        <i class="fa fa-fw fa-download mr-2"></i>Export
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form autocomplete="off">
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
            <div class="row">

                <div class="col-md-4">
                    <div class="input-group mb-3 mb-md-0">
                        <input type="search" class="form-control" name="q" value="<?php if (isset($q)) { echo stripslashes(nullable_htmlentities($q)); } ?>" placeholder="Search Tickets">
                        <div class="input-group-append">
                            <button class="btn btn-dark"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>



                <div class="col-md-8">
                    <div class="float-right">
                        <a href="client_recurring_tickets.php?client_id=<?php echo $client_id; ?>" class="btn btn-outline-info">
                            <i class="fa fa-fw fa-redo-alt mr-2"></i>Recurring Tickets | <strong> <?php echo $total_scheduled_tickets; ?></strong>
                        </a>
                    </div>
                </div>

            </div>
        </form>
        <hr>
        <div class="table-responsive-sm">
             <table id=responsive class="responsive table table-hover">
                <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                <tr>
                    <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=ticket_number&order=<?php echo $disp; ?>">Number</a></th>
                    <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=ticket_subject&order=<?php echo $disp; ?>">Subject</a></th>
                    <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=contact_name&order=<?php echo $disp; ?>">Contact</a></th>
                    <?php if ($config_module_enable_accounting) { ?>
                        <th class="text-center"><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=ticket_billable&order=<?php echo $disp; ?>">Billable</a></th>
                    <?php } ?>
                    <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=ticket_priority&order=<?php echo $disp; ?>">Priority</a></th>
                    <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=ticket_status&order=<?php echo $disp; ?>">Status</a></th>
                    <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=user_name&order=<?php echo $disp; ?>">Assigned</a></th>
                    <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=ticket_updated_at&order=<?php echo $disp; ?>">Last Response</a></th>
                    <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=ticket_created_at&order=<?php echo $disp; ?>">Created</a></th>

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
                    $ticket_status = nullable_htmlentities($row['ticket_status']);
                    $ticket_billable = intval($row['ticket_billable']);
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
                        <td><a href="/pages/ticket.php?ticket_id=<?php echo $ticket_id; ?>"><span class="badge badge-pill badge-secondary p-3"><?php echo "$ticket_prefix$ticket_number"; ?></span></a></td>
                        <td>
                            <a href="/pages/ticket.php?ticket_id=<?php echo $ticket_id; ?>"><?php echo $ticket_subject; ?></a>
                        </td>
                        <td><a href="#" data-toggle="modal" data-target="#editTicketContactModal<?php echo $ticket_id; ?>"><?php echo $contact_display; ?></a></td>

                        <?php if ($config_module_enable_accounting) { ?>
                        <td class="text-center">
                            <a href="#" data-toggle="modal" data-target="#editTicketBillableModal<?php echo $ticket_id; ?>">
                            <?php
                                if ($ticket_billable == 1) {
                                    echo "<span class='badge badge-pill badge-success'>$</span>";
                                } else {
                                    echo "<span class='badge badge-pill badge-secondary'>X</span>";
                                }
                            ?>
                        </td>
                        <?php } ?>

                        <td><a href="#" data-toggle="modal" data-target="#editTicketPriorityModal<?php echo $ticket_id; ?>"><?php echo $ticket_priority_display; ?></a></td>
                        <td><span class='p-2 badge badge-pill badge-<?php echo $ticket_status_color; ?>'><?php echo $ticket_status; ?></span></td>
                        <td><a href="#" data-toggle="modal" data-target="#assignTicketModal<?php echo $ticket_id; ?>"><?php echo $ticket_assigned_to_display; ?></a></td>
                        <td><?php echo $ticket_updated_at_display; ?></td>
                        <td>
                            <?php echo $ticket_created_at_time_ago; ?>
                            <br>
                            <small class="text-secondary"><?php echo $ticket_created_at; ?></small>
                        </td>
                    </tr>

                    <?php

                }

                ?>

                </tbody>
            </table>
        </div>
        <?php require_once '/var/www/develop.twe.tech/includes/pagination.php';
        ?>
    </div>
</div>

<?php
require_once '/var/www/develop.twe.tech/includes/footer.php';

?>