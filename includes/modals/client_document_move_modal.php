<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php"; ?>
<div class="modal" id="moveDocumentModal<?php echo $document_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-file-alt mr-2"></i>Moving document: <strong><?php echo $document_name; ?></strong></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">

                <div class="modal-body bg-white">
                <input type="hidden" name="document_id" value="<?php echo $document_id; ?>">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                    <div class="form-group">
                        <label>Move Document to</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-folder"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="folder">
                                <option value="0">/</option>
                                <?php
                                $sql_folders_select = mysqli_query($mysqli, "SELECT * FROM folders WHERE folder_location = $folder_location AND folder_client_id = $client_id ORDER BY folder_name ASC");
                                while ($row = mysqli_fetch_array($sql_folders_select)) {
                                    $folder_id_select = intval($row['folder_id']);
                                    $folder_name_select = nullable_htmlentities($row['folder_name']);
                                    ?>
                                    <option <?php if ($folder_id_select == $document_folder_id) echo "selected"; ?> value="<?php echo $folder_id_select ?>"><?php echo $folder_name_select; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="move_document" class="btn btn-label-primary text-bold"></i>Move</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
