<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>
<?php
$quote_id = intval($_GET['quote_id']);

$sql = mysqli_query($mysqli, "SELECT * FROM quotes LEFT JOIN clients ON quote_client_id = client_id WHERE quote_id = $quote_id");
$row = mysqli_fetch_array($sql);

$quote_id = intval($row['quote_id']);
$quote_number = intval($row['quote_number']);
$quote_prefix = nullable_htmlentities($row['quote_prefix']);
$client_id = intval($row['client_id']);
$client_name = nullable_htmlentities($row['client_name']);
$quote_discount = floatval($row['quote_discount']);
$quote_scope = nullable_htmlentities($row['quote_scope']);
$quote_date = nullable_htmlentities($row['quote_date']);
$quote_expire = nullable_htmlentities($row['quote_expire']);
$quote_category_id = intval($row['quote_category_id']);


?>

<div class="modal" id="editQuoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title text-white"><i class="fas fa-fw fa-comment-dollar mr-2"></i>Editing quote: <span class="text-bold">
                    <?php echo "$quote_prefix$quote_number"; ?></span> - <span class="text" id="editQuoteHeaderClient"><?php echo $client_name; ?></span></h5>
                </span> - <span class="text" id="editQuoteHeaderClient"></span></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">
                <input type="hidden" name="quote_id" id="editQuoteID" value="">

                <div class="modal-body bg-white">

                    <div class="form-group">
                        <label>Quote Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" name="date" id="editQuoteDate" max="2999-12-31" value="
                            <?php echo $quote_date; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Expire <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" name="expire" id="editQuoteExpire" max="2999-12-31" value="
                            <?php echo $quote_expire; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Income Category</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="category" id="editQuoteCategory" required>
                                <option value="">- Select Category -</option>
                                <?php

                                $sql = mysqli_query($mysqli, "SELECT * FROM categories WHERE category_type = 'Income' AND category_archived_at IS NULL");
                                while ($row = mysqli_fetch_array($sql)) {
                                    $category_id = intval($row['category_id']);
                                    $category_name = nullable_htmlentities($row['category_name']);
                                    ?>
                                    <option value="<?php echo $category_id; ?>"><?php echo $category_name; ?></option>

                                    <?php
                                }
                                ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-light" href="admin_categories.php?category=Income" target="_blank"><i class="fas fa-fw fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    
                    <div class='form-group'>
                        <label>Discount Amount</label>
                        <div class='input-group'>
                            <div class='input-group-prepend'>
                                <span class='input-group-text'><i class='fa fa-fw fa-dollar-sign'></i></span>
                            </div>
                            <input type='text' class='form-control' inputmode="numeric" pattern="-?[0-9]*\.?[0-9]{0,2}" name='quote_discount' placeholder='0.00' value="<?php echo number_format($quote_discount, 2, '.', ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Scope</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-comment"></i></span>
                            </div>
                            <input type="text" class="form-control" name="scope" id="editQuoteScope" placeholder="Quick description" value="
                            <?php echo $quote_scope; ?>">
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="edit_quote" class="btn btn-soft-primary text-bold"><i class="fas fa-check mr-2"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="fas fa-times mr-2"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
