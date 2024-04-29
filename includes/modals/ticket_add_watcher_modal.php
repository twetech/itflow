<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$ticket_id = intval($_GET['ticket_id']);

$sql_ticket_select = mysqli_query($mysqli,
    "SELECT * FROM tickets
    LEFT JOIN clients ON ticket_client_id = client_id
    WHERE ticket_id = $ticket_id");
$row = mysqli_fetch_array($sql_ticket_select);
$ticket_id = intval($row['ticket_id']);
$ticket_number = intval($row['ticket_number']);
$ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
$client_id = intval($row['ticket_client_id']);
$client_name = nullable_htmlentities($row['client_name']);
?>

<div class="modal" id="addTicketWatcherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-eye mr-2"></i>Adding a ticket Watcher: <strong><?php echo "$ticket_prefix$ticket_number"; ?></strong> - <?php echo $client_name; ?></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">

                <div class="modal-body bg-white">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                <input type="hidden" name="ticket_number" value="<?php echo "$ticket_prefix$ticket_number"; ?>">
                    <div class="form-group">
                        <label>Watcher Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' data-tags="true" name="watcher_email">
                                <option value="">-Select a contact-</option>
                                <?php

                                $sql_client_contacts_select = mysqli_query($mysqli, "SELECT * FROM contacts WHERE contact_client_id = $client_id AND contact_email <> '' ORDER BY contact_name ASC");
                                while ($row = mysqli_fetch_array($sql_client_contacts_select)) {
                                    $contact_id_select = intval($row['contact_id']);
                                    $contact_name_select = nullable_htmlentities($row['contact_name']);
                                    $contact_email_select = nullable_htmlentities($row['contact_email']);
                                    ?>
                                    <option value="<?php echo $contact_email_select; ?>"><?php echo "$contact_name_select - $contact_email_select"; ?></option>

                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                
                </div>

                <div class="modal-footer bg-white">
                    <button type="submit" name="add_ticket_watcher" class="btn btn-label-primary text-bold"></i>Add</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>
