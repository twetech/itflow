<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$users = mysqli_query($mysqli,
    "SELECT * FROM users
    WHERE user_id NOT IN (
        SELECT user_id FROM user_employees
    ) AND user_status = 1
    AND user_archived_at IS NULL
");
?>

<div class="modal-header">
    <h5 class="modal-title">Add Employee</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="form-group">
        <select class="form-control" name="user_id" id="user_id">
            <option value="">Select User</option>
            <?php while ($user = mysqli_fetch_assoc($users)) { ?>
                <option value="<?= $user['user_id']; ?>"><?= nullable_htmlentities($user['user_name']); ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" name="link_employee" class="btn btn-primary">Link Employee</button>
</div>