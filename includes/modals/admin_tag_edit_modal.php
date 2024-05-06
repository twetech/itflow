<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$tag_id = intval($_GET['tag_id']);
$sql_tag = mysqli_query($mysqli, "SELECT * FROM tags WHERE tag_id = $tag_id");
$row = mysqli_fetch_array($sql_tag);

$tag_name = nullable_htmlentities($row['tag_name']);
$tag_type = intval($row['tag_type']);
$tag_color = nullable_htmlentities($row['tag_color']);
$tag_icon = nullable_htmlentities($row['tag_icon']);
?>


<div class="modal" id="editTagModal<?php echo $tag_id; ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-fw fa-tag mr-2"></i>Editing tag: <strong><?php echo $tag_name; ?></strong></h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form action="/post.php" method="post" autocomplete="off">
        <div class="modal-body bg-white">
        <input type="hidden" name="tag_id" value="<?php echo $tag_id; ?>">
          <div class="form-group">
            <label>Name <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
              </div>
              <input type="text" class="form-control" name="name" value="<?php echo $tag_name; ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label>Type <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-th"></i></span>
              </div>
              <select class="form-control select2" id='select2' name="type" required>
                <option value="">- Type -</option>
                <option value="1" <?php if ($tag_type == 1) { echo "selected"; } ?>>Client Tag</option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label>Color <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-paint-brush"></i></span>
              </div>
              <input type="color" class="form-control col-3" name="color" value="<?php echo $tag_color; ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label>Icon</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-image"></i></span>
              </div>
              <input type="text" class="form-control" name="icon" placeholder="Icon ex handshake" value="<?php echo $tag_icon; ?>">
            </div>
          </div>
        
        </div>
        <div class="modal-footer bg-white">
          <button type="submit" name="edit_tag" class="btn btn-label-primary text-bold"><i class="fas fa-check mr-2"></i>Save</button>
          <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="fas fa-times mr-2"></i>Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>