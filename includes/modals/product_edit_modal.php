<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$product_id = intval($_GET['product_id']);

$sql = mysqli_query($mysqli,
"SELECT * FROM products
WHERE product_id = $product_id");
$row = mysqli_fetch_array($sql);
$product_name = nullable_htmlentities($row['product_name']);
$product_description = nullable_htmlentities($row['product_description']);
$product_price = floatval($row['product_price']);
$product_cost = floatval($row['product_cost']);
$product_tax_id = intval($row['product_tax_id']);
$categery_id = intval($row['category_id']);

?>


<div class="modal" id="editProductModal<?php echo $product_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-fw fa-box-open mr-2"></i>Editing product: <strong><?php echo $product_name; ?></strong></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">

                <div class="modal-body bg-white">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <div class="form-group">
                        <label>Name <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-fw fa-box"></i></span>
                            </div>
                            <input type="text" class="form-control" name="name" value="<?php echo $product_name; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Category <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-list"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="category" required>
                                <?php

                                $sql_select = mysqli_query($mysqli, "SELECT * FROM categories WHERE category_type = 'Income' AND (category_archived_at > '$product_created_at' OR category_archived_at IS NULL)");
                                while ($row = mysqli_fetch_array($sql_select)) {
                                    $category_id_select = intval($row['category_id']);
                                    $category_name_select = nullable_htmlentities($row['category_name']);
                                    ?>
                                    <option <?php if ($category_id == $category_id_select) { echo "selected"; } ?> value="<?php echo $category_id_select; ?>"><?php echo $category_name_select; ?></option>
                                    <?php
                                }

                                ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-light" href="admin_categories.php?category=Income" target="_blank"><i class="fas fa-fw fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Price <strong class="text-danger">*</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="text" inputmode="numeric" pattern="[0-9]*\.?[0-9]{0,2}" class="form-control" name="price" value="<?php echo number_format($product_price, 2, '.', ''); ?>" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Cost <strong class="text-danger">*</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="text" inputmode="numeric" pattern="[0-9]*\.?[0-9]{0,2}" class="form-control" name="cost" value="<?php echo number_format($product_cost, 2, '.', ''); ?>" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Tax</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-balance-scale"></i></span>
                                    </div>
                                    <select class="form-control select2" id='select2' name="tax">
                                        <option value="0">None</option>
                                        <?php

                                        $taxes_sql = mysqli_query($mysqli, "SELECT * FROM taxes WHERE (tax_archived_at > '$product_created_at' OR tax_archived_at IS NULL) ORDER BY tax_name ASC");
                                        while ($row = mysqli_fetch_array($taxes_sql)) {
                                            $tax_id_select = intval($row['tax_id']);
                                            $tax_name = nullable_htmlentities($row['tax_name']);
                                            $tax_percent = floatval($row['tax_percent']);
                                            ?>
                                            <option <?php if ($tax_id_select == $product_tax_id) { echo "selected"; } ?> value="<?php echo $tax_id_select; ?>"><?php echo "$tax_name $tax_percent%"; ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="5" name="description"><?php echo $product_description; ?></textarea>
                    </div>

                </div>

                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_product" class="btn btn-label-primary text-bold"><i class="fas fa-check mr-2"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="fas fa-times mr-2"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
