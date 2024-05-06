<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$ticket_id = intval($_GET['ticket_id']);

$sql_ticket_select = mysqli_query($mysqli,
    "SELECT * FROM tickets
    LEFT JOIN clients ON client_id = ticket_client_id
    WHERE ticket_id = $ticket_id");
$row = mysqli_fetch_array($sql_ticket_select);
$ticket_id = intval($row['ticket_id']);
$ticket_number = intval($row['ticket_number']);
$ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
$client_name = nullable_htmlentities($row['client_name']);
$client_id = intval($row['client_id']);
$ticket_subject = nullable_htmlentities($row['ticket_subject']);
$ticket_details = nullable_htmlentities($row['ticket_details']);
$ticket_priority = nullable_htmlentities($row['ticket_priority']);
$ticket_billable = intval($row['ticket_billable']);
$contact_id = intval($row['ticket_contact_id']);
$asset_id = intval($row['ticket_asset_id']);
$location_id = intval($row['ticket_location_id']);
$vendor_id = intval($row['ticket_vendor_id']);
$ticket_vendor_ticket_number = nullable_htmlentities($row['ticket_vendor_ticket_number']);
?>

<div class="modal" id="editTicketModal<?php echo $ticket_id; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-life-ring mr-2"></i>Editing ticket: <strong><?php echo "$ticket_prefix$ticket_number"; ?></strong> - <?php echo $client_name; ?></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">

                <div class="modal-body bg-white">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                <input type="hidden" name="ticket_number" value="<?php echo "$ticket_prefix$ticket_number"; ?>">
                    <ul class="nav nav-pills  mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" data-bs-toggle="tab" href="#pills-details<?php echo $ticket_id; ?>"><i class="fa fa-fw fa-life-ring mr-2"></i>Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-contacts<?php echo $ticket_id; ?>"><i class="fa fa-fw fa-users mr-2"></i>Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-assets<?php echo $ticket_id; ?>"><i class="fa fa-fw fa-desktop mr-2"></i>Asset</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-locations<?php echo $ticket_id; ?>"><i class="fa fa-fw fa-map-marker-alt mr-2"></i>Location</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-vendors<?php echo $ticket_id; ?>"><i class="fa fa-fw fa-building mr-2"></i>Vendor</a>
                        </li>
                    </ul>

                    <hr>

                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="pills-details<?php echo $ticket_id; ?>">

                            <div class="form-group">
                                <label>Subject <strong class="text-danger">*</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="subject" value="<?php echo $ticket_subject; ?>" placeholder="Subject" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea  class="form-control" rows="8" name="details"><?php echo $ticket_details; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Priority <strong class="text-danger">*</strong></label>
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

                            <?php if ($config_module_enable_accounting) {
                                ?>
                            <div class="form-group">
                                <label>Billable</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-money-bill"></i></span>
                                    </div>
                                    <select class="form-control" name="billable">
                                        <option <?php if ($ticket_billable == 1) { echo "selected"; } ?> value="1">Yes</option>
                                        <option <?php if ($ticket_billable == 0) { echo "selected"; } ?> value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>


                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pills-contacts<?php echo $ticket_id; ?>">

                            <div class="form-group">
                                <label>Contact</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                                    </div>
                                    <select class="form-control select2" id='select2' name="contact_id">
                                        <option value="0">No One</option>
                                        <?php
                                        $sql_client_contacts_select = mysqli_query($mysqli, "SELECT * FROM contacts WHERE contact_client_id = $client_id AND contact_archived_at IS NULL ORDER BY contact_primary DESC, contact_technical DESC, contact_name ASC");
                                        while ($row = mysqli_fetch_array($sql_client_contacts_select)) {
                                            $contact_id_select = intval($row['contact_id']);
                                            $contact_name_select = nullable_htmlentities($row['contact_name']);
                                            $contact_primary_select = intval($row['contact_primary']);
                                            if($contact_primary_select == 1) {
                                                $contact_primary_display_select = " (Primary)";
                                            } else {
                                                $contact_primary_display_select = "";
                                            }
                                            $contact_technical_select = intval($row['contact_technical']);
                                            if($contact_technical_select == 1) {
                                                $contact_technical_display_select = " (Technical)";
                                            } else {
                                                $contact_technical_display_select = "";
                                            }
                                            $contact_title_select = nullable_htmlentities($row['contact_title']);
                                            if(!empty($contact_title_select)) {
                                                $contact_title_display_select = " - $contact_title_select";
                                            } else {
                                                $contact_title_display_select = "";
                                            }
                                            
                                            ?>
                                            <option value="<?php echo $contact_id_select; ?>" <?php if ($contact_id_select  == $contact_id) { echo "selected"; } ?>><?php echo "$contact_name_select$contact_title_display_select$contact_primary_display_select$contact_technical_display_select"; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pills-assets<?php echo $ticket_id; ?>">

                            <div class="form-group">
                                <label>Asset</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-desktop"></i></span>
                                    </div>
                                    <select class="form-control select2" id='select2' name="asset">
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

                        <div class="tab-pane fade" role="tabpanel" id="pills-locations<?php echo $ticket_id; ?>">

                            <div class="form-group">
                                <label>Location</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                                    </div>
                                    <select class="form-control select2" id='select2' name="location">
                                        <option value="0">- None -</option>
                                        <?php

                                        $sql_locations = mysqli_query($mysqli, "SELECT * FROM locations WHERE location_client_id = $client_id AND location_archived_at IS NULL ORDER BY location_name ASC");
                                        while ($row = mysqli_fetch_array($sql_locations)) {
                                            $location_id_select = intval($row['location_id']);
                                            $location_name_select = nullable_htmlentities($row['location_name']);
                                            ?>
                                            <option <?php if ($location_id == $location_id_select) { echo "selected"; } ?> value="<?php echo $location_id_select; ?>"><?php echo $location_name_select; ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pills-vendors<?php echo $ticket_id; ?>">

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

                            <div class="form-group">
                                <label>Vendor Ticket Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="vendor_ticket_number" placeholder="Vendor ticket number" value="<?php echo $ticket_vendor_ticket_number; ?>">
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_ticket" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>

            </form>

        </div>
    </div>
</div>
