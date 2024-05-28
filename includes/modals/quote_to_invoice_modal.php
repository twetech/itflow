<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>
<div class="modal" id="addQuoteToInvoiceModal<?= $quote_id; ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-fw fa-file mr-2"></i>Quote <?= "$quote_prefix$quote_number"; ?> <i class="fas fa-arrow-right mr-2"></i>Invoice</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form action="/post.php" method="post" autocomplete="off">
        <input type="hidden" name="quote_id" value="<?= $quote_id; ?>">
        <input type="hidden" name="client_net_terms" value="<?= $client_net_terms; ?>">
        
        <div class="modal-body bg-white">
         
          <div class="form-group">
            <label>Invoice Date <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
              </div>
              <input type="date" class="form-control" name="date" max="2999-12-31" value="<?= date("Y-m-d"); ?>" required>
            </div>
          </div>
      
        </div>
        <div class="modal-footer bg-white">
          <button type="submit" name="add_quote_to_invoice" class="btn btn-label-primary text-bold"><strong><i class="fas fa-check mr-2"></i>Create Invoice</button>
            <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="fas fa-times mr-2"></i>Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>