<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php";

$ticket_id = $_GET['ticket_id'];

$sql = "SELECT * FROM tickets LEFT JOIN clients ON tickets.ticket_client_id = clients.client_id WHERE ticket_id = $ticket_id";
$result = mysqli_query($mysqli, $sql);
$row = mysqli_fetch_assoc($result);

$ticket_assigned_to = $row['ticket_assigned_to'];
$ticket_status = $row['ticket_status'];
$ticket_number = $row['ticket_number'];
$ticket_prefix = $row['ticket_prefix'];
$client_name = $row['client_name'];

?>
<div class="modal" id="assignTicketModal<?php echo $ticket_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-user-check mr-2"></i>Assigning Ticket: <strong><?php echo "$ticket_prefix$ticket_number"; ?></strong> - <?php echo $client_name; ?></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">
                <div class="modal-body bg-white">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                    <div class="form-group">
                        <label>Assign to</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user-check"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="assigned_to">
                                <option value="0">Not Assigned</option>
                                <?php
                                $sql_users_select = mysqli_query($mysqli, "SELECT * FROM users 
                                    LEFT JOIN user_settings on users.user_id = user_settings.user_id
                                    WHERE user_role > 1
                                    AND user_archived_at IS NULL 
                                    ORDER BY user_name DESC"
                                );
                                while ($row = mysqli_fetch_array($sql_users_select)) {
                                    $user_id_select = intval($row['user_id']);
                                    $user_name_select = nullable_htmlentities($row['user_name']);

                                    ?>
                                    <option value="<?php echo $user_id_select; ?>" <?php if ($user_id_select  == $ticket_assigned_to) { echo "selected"; } ?>><?php echo $user_name_select; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-white">
                    <button type="submit" name="assign_ticket" class="btn btn-label-primary text-bold"></i>Assign</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>
