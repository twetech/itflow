<?php
require "/var/www/portal.twe.tech/includes/inc_all.php";

error_reporting(E_ALL);
// Get all users
$users = mysqli_query($mysqli,
    "SELECT * FROM user_employees
    LEFT JOIN users ON user_employees.user_id = users.user_id
");
?>

<div class="row">
    <a href="#" class="loadModalContentBtn btn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="employee_add.php">
        <i class="fas fa-fw fa-plus mr-2"></i>Add Employee
    </a>
    <?php if (mysqli_num_rows($users) == 0) { ?>
        <div class="col-12">
            <div class="alert alert-info mt-4" role="alert">
                No employees found. Click the button above to link an employee.
            </div>
        </div>
    <?php } ?>
    <?php while ($user = mysqli_fetch_assoc($users)) { ?>
        <?php if ($user['user_archived_at'] != null || $user['user_status'] == 0) { continue; } ?>
        <div class="col-12 col-md-6">
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h3 class="card-title"><i class="fas fa-fw fa-user mr-2"></i><?= nullable_htmlentities($user['user_name']); ?></h3>
                    <form action="/post.php" method="post" class="float-right">
                        <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>">
                        <button type="submit" name="unlink_employee" class="btn btn-label-danger">Unlink Employee</button>
                    </form>
                </div>
                <div class="card-body">
                <?php
                // Form to update employee details, salary or hourly rate, benefits, bank account, etc.
                ?>
                <form action="/post.php" method="post">
                    <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <label for="user_pay_type">Pay Type</label>
                                <select class="form-control" name="user_pay_type" id="user_pay_type">
                                    <option value="salary" <?= $user['user_pay_type'] == 'salary' ? 'selected' : ''; ?>>Salary (Per Month, Paid Monthly)</option>
                                    <option value="hourly" <?= $user['user_pay_type'] == 'hourly' ? 'selected' : ''; ?>>Hourly (Per Hour, Paid Monthly)</option>
                                    <option value="contractor" <?= $user['user_pay_type'] == 'contractor' ? 'selected' : ''; ?>>Contractor (Per Billable Hour, Paid Monthly)</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="user_pay_rate">Pay Rate <?php switch ($user['user_pay_type']) {
                                    case 'salary': echo '(Per Month)'; break;
                                    case 'hourly': echo '(Per Hour)'; break;
                                    case 'contractor': echo '(Per Billable Hour)'; break;
                                } ?></label>
                                <input class="form-control" name="user_pay_rate" id="user_pay_rate" value="<?= $user['user_pay_rate']; ?>">
                            </div>
                            <?php // if user_pay_type is hourly, show max hours per week
                            if ($user['user_pay_type'] != 'salary') { ?>
                                <div class="col-12 col-lg-6">
                                    <label for="user_max_hours_per_week">Max Hours per Cycle</label>
                                    <input type="number" class="form-control" name="user_max_hours_per_week" id="user_max_hours_per_week" value="<?= $user['user_max_hours']; ?>">
                                </div>
                            <?php } ?>
                            <div class="col-12 col-lg-6">
                                <button type="submit" name="update_employee" class="btn btn-primary">Update Employee</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php
require "/var/www/portal.twe.tech/includes/footer.php";
