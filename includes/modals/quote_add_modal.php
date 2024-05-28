<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<?php

if (isset($_GET['client_id'])) {
    $client_id = intval($_GET['client_id']);
}

?>


<div class="modal" id="addQuoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-fw fa-comment-dollar mr-2"></i>New Quote</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">

                <div class="modal-body bg-white">

                    <div class="form-group">
                        <label>Scope</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-comment"></i></span>
                            </div>
                            <input type="text" class="form-control" name="scope" placeholder="Quick description">
                        </div>
                    </div>

                    <?php if (isset($_GET['client_id'])) { ?>
                        <input type="hidden" name="client" value="<?= $client_id; ?>">
                    <?php } else { ?>

                        <div class="form-group">
                            <label>Client <strong class="text-danger">*</strong></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                                </div>
                                <select class="form-control select2" id='select2' name="client" required>
                                    <option value="">- Client -</option>
                                    <?php

                                    //select unarchived clients
                                    $sql = mysqli_query($mysqli, "SELECT * FROM clients WHERE client_archived_at IS NULL ORDER BY client_name ASC");
                                    while ($row = mysqli_fetch_array($sql)) {
                                        $client_id = intval($row['client_id']);
                                        $client_name = nullable_htmlentities($row['client_name']);
                                        ?>
                                        <option value="<?= $client_id; ?>"><?= $client_name; ?></option>
                                    <?php } ?>

                                </select>
                            </div>
                        </div>

                    <?php } ?>

                    <div class="form-group">
                        <label>Category <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="category" required>
                                <option value="">- Category -</option>
                                <?php

                                $sql = mysqli_query($mysqli, "SELECT * FROM categories WHERE category_type = 'Income' AND category_archived_at IS NULL ORDER BY category_name ASC");
                                while ($row = mysqli_fetch_array($sql)) {
                                    $category_id = intval($row['category_id']);
                                    $category_name = nullable_htmlentities($row['category_name']);
                                    ?>
                                    <option value="<?= $category_id; ?>"><?= $category_name; ?></option>

                                <?php } ?>

                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-light" href="admin_categories.php?category=Income" target="_blank"><i class="fas fa-fw fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" name="date" max="2999-12-31" value="<?= date("Y-m-d"); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Expire <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" name="expire" min="<?= date("Y-m-d"); ?>" max="2999-12-31" value="<?= date("Y-m-d", strtotime("+30 days")); ?>" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="add_quote" class="btn btn-label-primary text-bold"><i class="fas fa-check mr-2"></i>Create</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
