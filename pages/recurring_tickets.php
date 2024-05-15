<?php

// Default Column Sortby Filter
$sort = "scheduled_ticket_subject";
$order = "ASC";

require_once "/var/www/nestogy.io/includes/inc_all.php";


//Rebuild URL

// SQL
$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM scheduled_tickets
    LEFT JOIN clients on scheduled_ticket_client_id = client_id
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

<div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fas fa-fw fa-calendar-check mr-2"></i>Recurring Tickets</h3>
        <div class='card-tools'>
            <div class="float-left">
                <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addRecurringTicketModal">
                    <i class="fas fa-plus mr-2"></i>New Recurring Ticket
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">

        <form autocomplete="off">
            <div class="row">

                <div class="col-md-4">
                    <div class="input-group mb-3 mb-md-0">
                        <input type="search" class="form-control" name="q" value="<?php if (isset($q)) {
                                                                                        echo stripslashes(nullable_htmlentities($q));
                                                                                    } ?>" placeholder="Search Recurring Tickets">
                        <div class="input-group-append">
                            <button class="btn btn-dark"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">

                    <div class="dropdown float-right" id="bulkActionButton" hidden>
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-fw fa-layer-group mr-2"></i>Bulk Action (<span id="selectedCount">0</span>)
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item text-danger text-bold" type="submit" form="bulkActions" name="bulk_delete_recurring_tickets">
                                <i class="fas fa-fw fa-trash mr-2"></i>Delete
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
        <hr>

        <div class="card-datatable table-responsive container-fluid  pt-0">
            <form id="bulkActions" action="/post.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">

                   
<table class="datatables-basic table border-top">
                    <thead class="<?php if (!$num_rows[0]) {
                                        echo "d-none";
                                    } ?>">
                        <tr>
                            <th><a class="text-dark">Client</a></th>
                            <th><a class="text-dark">Subject</a></th>
                            <th><a class="text-dark">Priority</a></th>
                            <th><a class="text-dark">Frequency</a></th>
                            <th><a class="text-dark">Next Run Date</a></th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        while ($row = mysqli_fetch_array($sql)) {
                            $scheduled_ticket_id = intval($row['scheduled_ticket_id']);
                            $scheduled_ticket_client_id = intval($row['client_id']);
                            $scheduled_ticket_subject = nullable_htmlentities($row['scheduled_ticket_subject']);
                            $scheduled_ticket_priority = nullable_htmlentities($row['scheduled_ticket_priority']);
                            $scheduled_ticket_frequency = nullable_htmlentities($row['scheduled_ticket_frequency']);
                            $scheduled_ticket_next_run = nullable_htmlentities($row['scheduled_ticket_next_run']);
                            $scheduled_ticket_client_name = nullable_htmlentities($row['client_name']);
                        ?>

                            <tr>

                                <th><a href="client_recurring_tickets.php?client_id=<?php echo $scheduled_ticket_client_id; ?>"><?php echo $scheduled_ticket_client_name ?></a>
                                </th>

                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editRecurringTicketModal" onclick="populateRecurringTicketEditModal(<?php echo $scheduled_ticket_client_id, ",", $scheduled_ticket_id ?>)"> <?php echo $scheduled_ticket_subject ?>
                                    </a>
                                </td>

                                <td><?php echo $scheduled_ticket_priority ?></td>

                                <td><?php echo $scheduled_ticket_frequency ?></td>

                                <td class="text-bold"><?php echo $scheduled_ticket_next_run ?></td>

                                <td>
                                    <div class="dropdown dropleft text-center">
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editRecurringTicketModal" onclick="populateRecurringTicketEditModal(<?php echo $scheduled_ticket_client_id, ",", $scheduled_ticket_id ?>)">
                                                <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                            </a>
                                            <?php
                                            if ($session_user_role == 3) { ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_recurring_ticket=<?php echo $scheduled_ticket_id; ?>">
                                                    <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </form>

        </div>

    </div>
</div>

<script src="/includes/js/recurring_tickets_edit_modal.js"></script>
<script src="/includes/js/bulk_actions.js"></script>

<?php
require_once "/var/www/nestogy.io/includes/modals/recurring_ticket_add_modal.php";

require_once "/var/www/nestogy.io/includes/modals/recurring_ticket_edit_modal.php";

require_once '/var/www/nestogy.io/includes/footer.php';
