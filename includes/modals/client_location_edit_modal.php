<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$location_id = intval($_GET['location_id']);
$sql = mysqli_query($mysqli, "SELECT * FROM locations WHERE location_id = $location_id");
$row = mysqli_fetch_array($sql);
$client_id = intval($row['location_client_id']);
$location_name = nullable_htmlentities($row['location_name']);
$location_description = nullable_htmlentities($row['location_description']);
$location_photo = nullable_htmlentities($row['location_photo']);
$location_address = nullable_htmlentities($row['location_address']);
$location_city = nullable_htmlentities($row['location_city']);
$location_state = nullable_htmlentities($row['location_state']);
$location_zip = nullable_htmlentities($row['location_zip']);
$location_country = nullable_htmlentities($row['location_country']);
$location_phone = nullable_htmlentities($row['location_phone']);
$location_contact_id = intval($row['location_contact_id']);
$location_hours = nullable_htmlentities($row['location_hours']);
$location_notes = nullable_htmlentities($row['location_notes']);

?>


<div class="modal" id="editLocationModal<?php echo $location_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-map-marker-alt mr-2"></i>Editing location: <strong><?php echo $location_name; ?></strong></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" enctype="multipart/form-data" autocomplete="off">

                
                <div class="modal-body bg-white">
                <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                    <ul class="nav nav-pills  mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" data-bs-toggle="tab" href="#pills-details<?php echo $location_id; ?>">Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-address<?php echo $location_id; ?>">Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-contact<?php echo $location_id; ?>">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-notes<?php echo $location_id; ?>">Notes</a>
                        </li>
                    </ul>

                    <hr>

                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="pills-details<?php echo $location_id; ?>">

                            <div class="form-group">
                                <label>Location Name <strong class="text-danger">*</strong> / <span class="text-secondary">Primary</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-map-marker"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="name" placeholder="Name of location" value="<?php echo $location_name; ?>" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="location_primary" value="1" <?php if ($location_primary == 1) { echo "checked"; } ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-angle-right"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="description" placeholder="Short Description" value="<?php echo $location_description; ?>">
                                </div>
                            </div>

                            <div class="mb-3" style="text-align: center;">
                                <?php if (!empty($location_photo)) { ?>
                                    <img class="img-fluid" src="<?php echo "/uploads/clients/$client_id/$location_photo"; ?>">
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label>Photo</label>
                                <input type="file" class="form-control-file" name="file">
                            </div>

                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pills-address<?php echo $location_id; ?>">

                            <div class="form-group">
                                <label>Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="address" placeholder="Street Address" value="<?php echo $location_address; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>City</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="city" placeholder="City" value="<?php echo $location_city; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>State / Province</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-flag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="state" placeholder="State or Province" value="<?php echo $location_state; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Postal Code</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-fw fa-usps"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="zip" placeholder="Zip or Postal Code" value="<?php echo $location_zip; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Country</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-globe-americas"></i></span>
                                    </div>
                                    <select class="form-control select2" id='select2' name="country">
                                        <option value="">- Country -</option>
                                        <?php foreach($countries_array as $country_name) { ?>
                                            <option <?php if ($location_country == $country_name) { echo "selected"; } ?>><?php echo $country_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pills-contact<?php echo $location_id; ?>">

                            <div class="form-group">
                                <label>Contact</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                                    </div>
                                    <select class="form-control select2" id='select2' name="contact">
                                        <option value="">- Contact -</option>
                                        <?php

                                        $sql_contacts = mysqli_query($mysqli, "SELECT * FROM contacts WHERE (contact_archived_at > '$location_created_at' OR contact_archived_at IS NULL) AND contact_client_id = $client_id ORDER BY contact_archived_at ASC, contact_name ASC");
                                        while ($row = mysqli_fetch_array($sql_contacts)) {
                                            $contact_id_select = intval($row['contact_id']);
                                            $contact_name_select = nullable_htmlentities($row['contact_name']);
                                            $contact_archived_at = nullable_htmlentities($row['contact_archived_at']);
                                            if (empty($contact_archived_at)) {
                                                $contact_archived_display = "";
                                            } else {
                                                $contact_archived_display = "Archived - ";
                                            }

                                            ?>
                                            <option <?php if ($location_contact_id == $contact_id_select) { echo "selected"; } ?> value="<?php echo $contact_id_select; ?>"><?php echo "$contact_archived_display$contact_name_select"; ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="phone" placeholder="Phone Number" value="<?php echo $location_phone; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Hours</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-clock"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="hours" placeholder="Hours of operation" value="<?php echo $location_hours; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea class="form-control" rows="5" name="notes" placeholder="Notes, eg Parking Info, Building Access etc"><?php echo $location_notes; ?></textarea>
                            </div>

                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pills-notes<?php echo $location_id; ?>">

                            <div class="form-group">
                                <textarea class="form-control" rows="12" name="notes" placeholder="Notes, eg Parking Info, Building Access etc"><?php echo $location_notes; ?></textarea>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_location" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
