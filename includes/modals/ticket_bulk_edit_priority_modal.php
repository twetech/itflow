<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>
<div class="modal" id="bulkEditPriorityTicketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-thermometer-half mr-2"></i>Bulk Editing Priority:</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
                
            <div class="modal-body bg-white">

                <div class="form-group">
                    <label>Priority</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-thermometer-half"></i></span>
                        </div>
                        <select class="form-control select2" id='select2' name="bulk_priority">
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="modal-footer bg-white">
                <button type="submit" name="bulk_edit_ticket_priority" class="btn btn-soft-primary text-bold"></i>Save</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
            </div>

        </div>
    </div>
</div>
