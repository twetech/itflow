<?php

$ticket_id = intval($_GET['ticket_id']);

$ticket_sql = mysqli_query($mysqli, "SELECT * FROM tickets WHERE ticket_id = $ticket_id");
$ticket = mysqli_fetch_assoc($ticket_sql);

$ticket_prefix = $ticket['ticket_prefix'];
$ticket_number = $ticket['ticket_number'];
$ticket_subject = $ticket['ticket_subject'];
$ticket_details = $ticket['ticket_details'];
$ticket_priority = $ticket['ticket_priority'];
$ticket_status = $ticket['ticket_status'];

?>

<div class="modal fade" id="calendarTicketModal" tabindex="-1" role="dialog" aria-labelledby="calendarTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarTicketModalLabel">Ticket #<?php echo $ticket_prefix . $ticket_number; ?> - <?php echo $ticket_subject; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Details</h5>
                        <p><?= $ticket_details; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Priority</h5>
                        <p><?= $ticket_priority; ?></p>
                        <h5>Status</h5>
                        <p><?= $ticket_status; ?></p>
                        <a href="ticket.php?ticket_id=<?= $ticket_id; ?>" class="btn btn-primary">View Ticket</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>