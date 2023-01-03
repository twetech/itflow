<div class="modal" id="recurringNoteModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title text-white"><i class="fa fa-edit mr-2"></i>Quote Notes</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form action="post.php" method="post" autocomplete="off">
        <input type="hidden" name="recurring_id" value="<?php echo $recurring_id; ?>">
        <div class="modal-body bg-white">  
          <div class="form-group">
            <textarea class="form-control" rows="8" name="note" placeholder="Enter some notes"><?php echo $recurring_note; ?></textarea>
          </div>
        </div>
        <div class="modal-footer bg-white">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="recurring_note" class="btn btn-primary"><strong><i class="fas fa-check"></i> Save</strong></button>
        </div>
      </form>
    </div>
  </div>
</div>