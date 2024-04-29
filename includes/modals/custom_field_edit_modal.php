<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>
<div class="modal" id="editCustomFieldModal<?php echo $custom_field_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-th-list mr-2"></i>Editing custom field: <strong><?php echo $custom_field_label; ?></strong></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">
                <div class="modal-body bg-white">
                <input type="hidden" name="custom_field_id" value="<?php echo $custom_field_id; ?>">

                    <div class="form-group">
                        <label>Label <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control" name="label" value="<?php echo $custom_field_label; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Type <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-th"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="type" required>
                                <option value="">- Select a field type -</option>
                                <option <?php if ($custom_field_type == 'text') { echo "selected"; } ?> value="text">Text</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_custom_field" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
