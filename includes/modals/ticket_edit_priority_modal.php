<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<?php
isset($_GET['ticket_id']) ? $ticket_id = intval($_GET['ticket_id']) : $ticket_id = '';

$ticket_data = readTicket($ticket_id);
$ticket_prefix = $ticket_data[$ticket_id]['ticket_prefix'];
$ticket_number = $ticket_data[$ticket_id]['ticket_number'];
$ticket_priority = $ticket_data[$ticket_id]['ticket_priority'];

isset($_GET['client_id']) ? $client_id = intval($_GET['client_id']) : $client_id = $ticket_data[$ticket_id]['client_id'];

$client_data = readClient($client_id);
$client_name = $client_data[$client_id]['client_name'];

?>

<div class="modal" id="editTicketPriorityModal<?php echo $ticket_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-thermometer-half mr-2"></i>Editing ticket priority: <strong><?php echo "$ticket_prefix$ticket_number"; ?></strong> <?php echo $client_name; ?></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body bg-white">
            <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                <div class="form-group">
                    <label>Priority</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-thermometer-half"></i></span>
                        </div>
                        <select class="form-control select2" id='select2' name="priority" required>
                            <option <?php if ($ticket_priority == 'Low') { echo "selected"; } ?> >Low</option>
                            <option <?php if ($ticket_priority == 'Medium') { echo "selected"; } ?> >Medium</option>
                            <option <?php if ($ticket_priority == 'High') { echo "selected"; } ?> >High</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="modal-footer bg-white">
                <button type="submit" name="edit_ticket_priority" class="btn btn-label-primary text-bold"></i>Save</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
            </div>


        </div>
    </div>
</div>
