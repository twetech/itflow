<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php"; ?>
<div class="modal" id="renameFileModal<?php echo $file_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-<?php echo $file_icon; ?> mr-2"></i>Renaming file: <strong><?php echo $file_name; ?></strong></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">

                <div class="modal-body bg-white">
                <input type="hidden" name="file_id" value="<?php echo $file_id; ?>">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                    <div class="form-group">
                        <label>Name <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-folder"></i></span>
                            </div>
                            <input type="text" class="form-control" name="file_name" placeholder="File Name" value="<?php echo $file_name; ?>" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="rename_file" class="btn btn-label-primary text-bold"></i>Rename</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
