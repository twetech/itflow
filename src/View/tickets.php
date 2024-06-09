<?php

$total_tickets_open = $support_header_numbers['open_tickets'];
$total_tickets_closed = $support_header_numbers['closed_tickets'];
$total_tickets_unassigned = $support_header_numbers['unassigned_tickets'];
$total_scheduled_tickets = $support_header_numbers['scheduled_tickets'];

$session_mobile = false;

?>


<style>
    .popover {
        max-width: 600px;
    }

</style>
<div class="card">
    <div class="card-header header-elements">
        <h3 class="me-2">
            <i class="bx bx-support"></i>
            Support Tickets
        </h3>
        <div class="card-header-elements">
            <span class="badge rounded-pill bg-label-secondary p-2">Total: <?=$total_tickets_open + $total_tickets_closed?></span> |
            <a href="<?= isset($client_id) ? "/pages/client/client_" : "/pages/" ?>tickets.php?status=Open&assigned=all<?= isset($client_id) ? "&client_id=$client_id" : "" ?>" class="badge rounded-pill bg-label-primary p-2">
                Open: <?=$total_tickets_open?>
            </a> |
            <a href="<?= isset($client_id) ? "/pages/client/client_" : "/pages/" ?>tickets.php?status=5&assigned=all<?= isset($client_id) ? "&client_id=$client_id" : "" ?>" class="badge rounded-pill bg-label-danger p-2">
                Closed: <?=$total_tickets_closed?>
            </a>
        </div>
        <div class="card-header-elements ms-auto">
            <div class="btn-group">
                <div class="btn-group" role="group">
                    <button class="btn btn-label-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                        <?=$session_mobile ? "" : "My Tickets"?>
                        <i class="fa fa-fw fa-envelope m-2"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="?status=Open&assigned=<?= $session_user_id ?>">Active tickets (<?= $user_active_assigned_tickets ?>)</a>
                        <a class="dropdown-item " href="?status=5&assigned=<?= $session_user_id ?>">Closed tickets</a>
                    </div>
                </div>
                <?php if (!isset($_GET['client_id'])) { ?>
                    <a href="?assigned=unassigned" class="btn btn-label-danger">
                        <strong><?=$session_mobile ? "" : "Unassigned:"?> <?= " ".$total_tickets_unassigned; ?></strong>
                        <span class="tf-icons fa fa-fw fa-exclamation-triangle mr-2"></span>
                    </a>
                <?php } ?>
                <a href="<?=isset($_GET['client_id']) ? "/pages/client/client_" : '/pages/'?>recurring_tickets.php" class="btn btn-label-info">
                <strong><?=$session_mobile ? "" : "Recurring:"?> <?= $total_scheduled_tickets; ?> </strong>
                    <span class="tf-icons fa fa-fw fa-redo-alt mr-2"></span>
                </a>
                <a href="#!" class="btn btn-label-secondary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_add_modal.php">
                    <?=$session_mobile ? "Add Ticket" : ""?>
                    <i class="fa fa-fw fa-plus mr-2"></i>
                </a>

            </div>

        </div>
    </div>

    <div class="card-body">
        <form id="bulkActions" action="/post/" method="post">
            <div class="card-datatable table-responsive">
                <table class="datatables-basic table border-top">
                    <thead class="text-dark">
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
                            
                                $rows[] = 'Billable';

                            // Add actions to the end of the table
                            $rows[] = 'Actions';

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
                        // table body
                        foreach ($tickets as $ticket) {
                            $ticket_id = $ticket['ticket_id'];
                            $client_name = $ticket['client_name'];
                            $contact_name = $ticket['contact_name'];
                            $subject = $ticket['ticket_subject'];
                            $priority = $ticket['ticket_priority'];
                            $ticket_status = $ticket['ticket_status_name'];
                            $status_color = $ticket['ticket_status_color'];
                            $assigned = $ticket['user_name'];
                            $created = $ticket['ticket_created_at'];
                            $billable = $ticket['ticket_billable'];
                            $last_response = $ticket['ticket_last_response'];

                            if ($billable == 1) {
                                $billable = "<span class='badge rounded-pill bg-label-success'>$</span>";
                            } else {
                                $billable = "<span class='badge rounded-pill bg-label-secondary'>X</span>";
                            }
                            $ticket_priority = $priority == 1 ? 'Low' : ($priority == 2 ? 'Medium' : 'High');
                            $ticket_priority_color = $priority == 1 ? 'success' : ($priority == 2 ? 'warning' : 'danger');

                            $ticket_assigned = $assigned == 0 ? 'Unassigned' : $assigned;

                            $ticket_last_response = $last_response ? date('Y-m-d H:i', strtotime($last_response)) : 'N/A';
                            $ticket_created = date('Y-m-d H:i', strtotime($created));

                            $ticket_subject = $subject;

                            $ticket_number = $ticket['ticket_number'];

                            $ticket_actions = [
                                'View' => [
                                    'icon' => 'fa-eye',
                                    'url' => isset($_GET['client_id']) ? "/pages/client/client_" : "/pages/" . "ticket.php?ticket_id=$ticket_id"
                                ],
                                'Edit' => [
                                    'icon' => 'fa-edit',
                                    'url' => isset($_GET['client_id']) ? "/pages/client/client_" : "/pages/" . "ticket_edit.php?ticket_id=$ticket_id"
                                ],
                                'Delete' => [
                                    'icon' => 'fa-trash',
                                    'url' => "/post/ticket_delete.php",
                                    'data' => [
                                        'ticket_id' => $ticket_id
                                    ]
                                ]
                            ];
                        ?>

                            <tr class="<?= empty($ticket_updated_at) ? "text-bold" : "" ?>">
                                <td>
                                    <small>
                                        <a href="/public/?page=ticket&action=show&ticket_id=<?=$ticket_id?>">
                                            <span class="badge rounded-pill bg-label-secondary p-3"><?=$ticket_number?></span>
                                        </a>
                                    </small>
                                </td>
                                <td>
                                    <a href="/public/?page=ticket&action=show&ticket_id=<?=$ticket_id?>">
                                        <?=$ticket_subject?>
                                    </a>
                                </td>
                                <td>
                                    <a href="/public/?page=client&action=show&client_id=<?=$ticket['client_id']?>">
                                        <?=$client_name?>
                                    </a>
                                    <br>
                                    <small>
                                        <a href="/public/?page=contact&action=show&contact_id=<?=$ticket['contact_id']?>">
                                            <?=$contact_name?>
                                        </a>
                                    </small>
                                </td>
                                <td>
                                    <a href="#" class="loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_priority_modal.php?ticket_id=<?= $ticket_id; ?>">
                                        <span class='p-2 badge rounded-pill bg-label-<?= $ticket_priority_color; ?>'>
                                            <?= $ticket_priority; ?>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_status_modal.php?ticket_id=<?= $ticket_id; ?>">
                                        <span class='p-2 badge rounded-pill bg-label-<?= $status_color; ?>'>
                                            <?= $ticket_status; ?>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_assigned_modal.php?ticket_id=<?= $ticket_id; ?>">
                                        <?= $ticket_assigned; ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $ticket_last_response; ?>
                                </td>
                                <td>
                                    <?= $ticket_created; ?>
                                </td>
                                <td>
                                    <?= $billable; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-label-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <?php
                                                foreach ($ticket_actions as $action => $data) {
                                                    echo "<a class='dropdown-item' href='" . $data['url'] . "' data-bs-toggle='modal' data-bs-target='#dynamicModal' data-modal-file='ticket_edit_modal.php?ticket_id=$ticket_id'>" . $action . "</a>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
