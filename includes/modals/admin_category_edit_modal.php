<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$category_id = intval($_GET['category_id']);
$sql_category = mysqli_query($mysqli, "SELECT * FROM categories WHERE category_id = $category_id");
$row = mysqli_fetch_array($sql_category);
$category_name = nullable_htmlentities($row['category_name']);
$category_color = nullable_htmlentities($row['category_color']);
?>

<div class="modal" id="editCategoryModal<?php echo $category_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-list-ul mr-2"></i>Editing category: <strong><?php echo $category_name; ?></strong></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">
                <div class="modal-body bg-white">
                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                <input type="hidden" name="type" value="<?php echo nullable_htmlentities($category); ?>">

                    <div class="form-group">
                        <label>Name <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-list-ul"></i></span>
                            </div>
                            <input type="text" class="form-control" name="name" value="<?php echo $category_name; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Color <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-paint-brush"></i></span>
                            </div>
                            <input type="color" class="form-control col-3" name="color" value="<?php echo $category_color; ?>" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_category" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
