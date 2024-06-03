<?php

$page_redir = strval($_GET['page_redir']);
$max_rows = intval($_GET['max_rows']);

$allowed_pages = [
    "client_statement"
];

if (!in_array($page_redir, $allowed_pages)) {
    header("Location: /");
    exit();
}

?>

<div class="modal fade" id="maxRowsModal" tabindex="-1" aria-labelledby="maxRowsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/post.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="maxRowsModalLabel">Max Rows</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>How many rows would you like to display?</p>
                    <input type="hidden" name="page_redir" value="<?= $page_redir; ?>">
                    <input type="number" name="max_rows" class="form-control" value="<?= $max_rows; ?>" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="change_max_rows" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
