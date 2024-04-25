
<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$ticket_id = intval($_GET['ticket_id']);

$sql_ticket_select = mysqli_query($mysqli,
    "SELECT * FROM tickets
    LEFT JOIN clients ON client_id = ticket_client_id
    LEFT JOIN vendors ON vendor_id = ticket_vendor_id
    WHERE ticket_id = $ticket_id");

$row = mysqli_fetch_array($sql_ticket_select);
$ticket_id = intval($row['ticket_id']);
$ticket_number = intval($row['ticket_number']);
$ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
$ticket_onsite = intval($row['ticket_onsite']);
$ticket_vendor_id = intval($row['ticket_vendor_id']);
$ticket_client_id = intval($row['ticket_client_id']);
$client_id = intval($row['client_id']);
$client_name = nullable_htmlentities($row['client_name']);
$vendor_id = intval($row['vendor_id']);
$vendor_name = nullable_htmlentities($row['vendor_name']);



?>
<div class="modal" id="editTicketVendorModal<?php echo $ticket_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-building mr-2"></i>Editing ticket Vendor: <strong><?php echo "$ticket_prefix$ticket_number"; ?></strong> - <?php echo $client_name; ?></h5>
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
                        <label>Vendor</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="vendor">
                                <option value="0">- None -</option>
                                <?php

                                $sql_vendors = mysqli_query($mysqli, "SELECT * FROM vendors WHERE vendor_client_id = $client_id AND vendor_template = 0 AND vendor_archived_at IS NULL ORDER BY vendor_name ASC");
                                while ($row = mysqli_fetch_array($sql_vendors)) {
                                    $vendor_id_select = intval($row['vendor_id']);
                                    $vendor_name_select = nullable_htmlentities($row['vendor_name']);
                                    ?>
                                    <option <?php if ($vendor_id == $vendor_id_select) { echo "selected"; } ?> value="<?php echo $vendor_id_select; ?>"><?php echo $vendor_name_select; ?></option>

                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_ticket_vendor" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>
