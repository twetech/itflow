<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$ticket_id = intval($_GET['ticket_id']);

$sql_ticket_select = mysqli_query($mysqli,
    "SELECT * FROM tickets
    LEFT JOIN clients ON client_id = ticket_client_id
    LEFT JOIN assets ON asset_id = ticket_asset_id
    WHERE ticket_id = $ticket_id");
$row = mysqli_fetch_array($sql_ticket_select);
$ticket_id = intval($row['ticket_id']);
$ticket_number = intval($row['ticket_number']);
$ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
$ticket_onsite = intval($row['ticket_onsite']);
$ticket_asset_id = intval($row['ticket_asset_id']);
$ticket_client_id = intval($row['ticket_client_id']);
$asset_id = intval($row['asset_id']);
$client_id = intval($row['client_id']);
$client_name = nullable_htmlentities($row['client_name']);
$asset_name = nullable_htmlentities($row['asset_name']);

?>

<div class="modal" id="editTicketAssetModal<?php echo $ticket_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-life-desktop mr-2"></i>Editing ticket Asset: <strong><?php echo "$ticket_prefix$ticket_number"; ?></strong> - <?php echo $client_name; ?></h5>
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
                        <label>Asset</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-desktop"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="ticket_asset_id">
                                <option value="0">- None -</option>
                                <?php

                                $sql_assets = mysqli_query($mysqli, "SELECT * FROM assets LEFT JOIN contacts ON contact_id = asset_contact_id WHERE asset_client_id = $client_id AND asset_archived_at IS NULL ORDER BY asset_name ASC");
                                while ($row = mysqli_fetch_array($sql_assets)) {
                                    $asset_id_select = intval($row['asset_id']);
                                    $asset_name_select = nullable_htmlentities($row['asset_name']);
                                    $asset_contact_name_select = nullable_htmlentities($row['contact_name']);
                                    ?>
                                    <option <?php if ($asset_id == $asset_id_select) { echo "selected"; } ?> value="<?php echo $asset_id_select; ?>"><?php echo "$asset_name_select - $asset_contact_name_select"; ?></option>

                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_ticket_asset" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>
