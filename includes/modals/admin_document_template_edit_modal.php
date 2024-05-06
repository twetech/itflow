<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$document_id = intval($_GET['document_id']);
$sql_document = mysqli_query($mysqli, "SELECT * FROM document_templates WHERE document_id = $document_id");
$row = mysqli_fetch_array($sql_document);
$document_name = nullable_htmlentities($row['document_name']);
$document_content = nullable_htmlentities($row['document_content']);
$document_description = nullable_htmlentities($row['document_description']);
?>

<div class="modal" id="editDocumentTemplateModal<?php echo $document_id; ?>" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-file-alt mr-2"></i>Editing template: <strong><?php echo $document_name; ?></strong></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">
                <div class="modal-body bg-white">
                <input type="hidden" name="document_id" value="<?php echo $document_id; ?>">

                    <div class="form-group">
                        <input type="text" class="form-control" name="name" value="<?php echo $document_name; ?>" placeholder="Name" required>
                    </div>

                    <div class="form-group">
                        <textarea  class="form-control" name="content"><?php echo $document_content; ?></textarea>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" name="description" value="<?php echo $document_description; ?>" placeholder="Short summary">
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_document_template" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
