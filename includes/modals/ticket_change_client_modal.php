
<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>


<?php
$ticket_id = $_GET['ticket_id'];

$ticket = readTicket($ticket_id)[$ticket_id];

$ticket_number = $ticket['ticket_number'];
$ticket_prefix = $ticket['ticket_prefix'];

$clients = readClients();
?>

<div class="modal" id="clientChangeTicketModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-people-carry mr-2"></i>Change <?= "$ticket_prefix$ticket_number"; ?> to another client</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">
                <div class="modal-body bg-white">
                <input type="hidden" name="ticket_id" value="<?= $ticket_id; ?>">

                    <div class="form-group">
                        <label>New Client <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-users"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="new_client_id" id="changeClientSelect" required>
                                <option value="">Select Client</option>
                                <?php foreach ($clients as $client) : ?>
                                    <option value="<?= $client['client_id']; ?>" <?= $client['client_id'] == $ticket_client_id ? 'selected' : '' ?>>
                                        <?= $client['client_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="change_client_ticket" class="btn btn-label-primary text-bold"></i>Change</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Ticket Change Client JS -->
<link rel="stylesheet" href="plugins/jquery-ui/jquery-ui.min.css">
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/includes/js/ticket_change_client.js"></script>
