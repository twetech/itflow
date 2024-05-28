
<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>
<div class="modal" id="viewDocumentModal<?= $document_id; ?>" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title text-white"><i class="fa fa-fw fa-file-alt mr-2"></i><?= $document_name; ?></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-white">
                <?= $document_content; ?>
            </div>
        </div>
    </div>
</div>
