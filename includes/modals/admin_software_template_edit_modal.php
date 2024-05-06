<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$software_id = intval($_GET['software_id']);
$sql_software = mysqli_query($mysqli, "SELECT * FROM software_templates WHERE software_id = $software_id");
$row = mysqli_fetch_array($sql_software);
$software_name = nullable_htmlentities($row['software_name']);
$software_version = nullable_htmlentities($row['software_version']);
$software_description = nullable_htmlentities($row['software_description']);
$software_type = nullable_htmlentities($row['software_type']);
$software_license_type = nullable_htmlentities($row['software_license_type']);
$software_notes = nullable_htmlentities($row['software_notes']);
?>

<div class="modal" id="editSoftwareTemplateModal<?php echo $software_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-cube mr-2"></i>Editing template: <strong><?php echo $software_name; ?></strong></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">
                <div class="modal-body bg-white">
                <input type="hidden" name="software_id" value="<?php echo $software_id; ?>">

                    <div class="form-group">
                        <label>Template Name <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-cube"></i></span>
                            </div>
                            <input type="text" class="form-control" name="name" placeholder="Software name" value="<?php echo $software_name; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Version</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-cube"></i></span>
                            </div>
                            <input type="text" class="form-control" name="version" placeholder="Software version" value="<?php echo $software_version; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-angle-right"></i></span>
                            </div>
                            <input type="text" class="form-control" name="description" placeholder="Short description" value="<?php echo $software_description; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Type <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="type" required>
                                <?php foreach($software_types_array as $software_type_select) { ?>
                                    <option <?php if($software_type == $software_type_select) { echo "selected"; } ?>><?php echo $software_type_select; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>License Type</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-cube"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="license_type">
                                <option value="">- Select a License Type -</option>
                                <?php foreach($license_types_array as $license_type_select) { ?>
                                    <option <?php if($license_type_select == $software_license_type){ echo "selected"; } ?>><?php echo $license_type_select; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <textarea class="form-control" rows="8" placeholder="Enter some notes" name="notes"><?php echo $software_notes; ?></textarea>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_software_template" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
