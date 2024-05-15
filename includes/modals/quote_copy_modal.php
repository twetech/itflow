<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php"; ?>

<? $quote_id = intval($_GET['quote_id']);

$sql = mysqli_query($mysqli, "SELECT * FROM quotes LEFT JOIN clients ON quote_client_id = client_id WHERE quote_id = $quote_id");
$row = mysqli_fetch_array($sql);

$quote_id = intval($row['quote_id']);
$quote_number = intval($row['quote_number']);
$quote_prefix = nullable_htmlentities($row['quote_prefix']);
$client_id = intval($row['client_id']);
$client_name = nullable_htmlentities($row['client_name']);

?>

<div class="modal" id="addQuoteCopyModal<?php echo $quote_id; ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-fw fa-copy mr-2"></i>Copying quote: <strong><?php echo "$quote_prefix$quote_number"; ?></strong> - <?php echo $client_name; ?></h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form action="/post.php" method="post" autocomplete="off">
        <input type="hidden" name="quote_id" value="<?php echo $quote_id; ?>">
        <div class="modal-body bg-white">

          <div class="form-group">
            <label>Set Date for New Quote <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
              </div>
              <input type="date" class="form-control" name="date" max="2999-12-31" value="<?php echo date("Y-m-d"); ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label>Expire <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
              </div>
              <input type="date" class="form-control" name="expire" min="<?php echo date("Y-m-d"); ?>" max="2999-12-31" value="<?php echo date("Y-m-d", strtotime("+30 days")); ?>" required>
            </div>
          </div>

        </div>
        <div class="modal-footer bg-white">
          <button type="submit" name="add_quote_copy" class="btn btn-label-primary text-bold"></i>Copy</button>
          <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>