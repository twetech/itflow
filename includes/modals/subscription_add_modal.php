<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php"; 

$reseller_id = $_GET['reseller_id'];


?>
<div class="modal-header">
    <h5 class="modal-title">
        Add Subscription
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <input type="hidden" name="reseller_id" value="<?php echo $reseller_id; ?>">

    <div class="form-group">
        <label>Subscription Name</label>
        <input type="text" class="form-control" name="subscription_name" required>
    </div>

    <div class="form-group">
        <label>Subscription Description</label>
        <input type="text" class="form-control" name="subscription_description" required>
    </div>

    <div class="form-group">
        <label>Subscription Price</label>
        <input type="number" class="form-control" name="subscription_price" required>
    </div>

    <div class="form-group">
        <label>Subscription Type</label>
        <input type="number" class="form-control" name="subscription_type" required>
    </div>    
</div>
<div class="modal-footer">
    <button name="add_subscription" type="submit" class="btn btn-primary">
        Add Subscription
    </button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
