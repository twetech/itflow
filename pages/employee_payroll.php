<?php
require "/var/www/portal.twe.tech/includes/inc_all.php";

// Get all users
$users = mysqli_query($mysqli,
    "SELECT * FROM user_employees
    LEFT JOIN users ON user_employees.user_id = users.user_id
");

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$total_hours_worked = 0;
$total_billable_hours = 0;
$total_pay = 0;
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-fw fa-user mr-2"></i>
                    Employee Payroll
                </h3>
                <form method="GET">
                    <div class="form-group">
                        <label for="month">Month</label>
                        <select class="form-control" name="month" id="month" onchange="this.form.submit()">
                            <?php for ($i = 1; $i <= 12; $i++) { ?>
                                <option value="<?= $i; ?>" <?= $month == $i ? 'selected' : ''; ?>><?= date('F', strtotime('2021-' . $i . '-01')); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Pay Type</th>
                            <th>Hours Worked</th>
                            <th>Billable Hours</th>
                            <th>Pay Rate</th>
                            <th>Total Pay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users)) { ?>
                            <?php if ($user['user_archived_at'] != null || $user['user_status'] == 0) { continue; } ?>
                            <?php
                            // Calculate the hours worked for the month provided
                            $user_hours_worked = 0;
                            $user_id = $user['user_id'];
                            $employee_time = mysqli_query($mysqli,
                                "SELECT * FROM employee_times
                                WHERE employee_id = $user_id
                                AND employee_time_start >= '" . date('Y-' . $month . '-01') . "'
                                AND employee_time_start <= '" . date('Y-' . $month . '-t') . " 23:59:59'

                            "
                            );
                            $time_running_icon = false;
                            $break_icon = false;
                            while ($time = mysqli_fetch_assoc($employee_time)) {
                                // Look for breaks in the time
                                $breaks = mysqli_query($mysqli,
                                    "SELECT * FROM employee_time_breaks
                                    WHERE employee_time_id = " . $time['employee_time_id']
                                );
                                $break_time = 0;
                                while ($break = mysqli_fetch_assoc($breaks)) {
                                    $employee_break_time_start = strtotime($break['employee_break_time_start']);
                                    // If employee break does not have an end time, use the current time
                                    if ($break['employee_break_time_end'] == '0000-00-00 00:00:00') {
                                        $employee_break_time_end = time();
                                        $break_icon = true;
                                    } else {
                                        $employee_break_time_end = strtotime($break['employee_break_time_end']);
                                    }
                                    $break_time += $employee_break_time_end - $employee_break_time_start;
                                }
                                $employee_time_start = strtotime($time['employee_time_start']);

                                //If employee time does not have an end time, show the running icon
                                if ($time['employee_time_end'] == '0000-00-00 00:00:00') {
                                    $time_running_icon = true;
                                    $employee_time_end = time();
                                } else {
                                    $employee_time_end = strtotime($time['employee_time_end']);
                                }

                                $user_hours_worked += $employee_time_end - $employee_time_start - $break_time;

                            }
                            // Round user_hours_worked to the nearest minute
                            $user_hours_worked = round($user_hours_worked / 60) * 60;


                            // Calculate the total reply time across ticket replies
                            $reply_time_sql = mysqli_query($mysqli,
                                "SELECT * FROM ticket_replies
                                WHERE ticket_reply_by = $user_id
                                AND ticket_reply_time_worked IS NOT NULL
                                AND ticket_reply_created_at >= '" . date('Y-' . $month . '-01') . "'
                                AND ticket_reply_created_at <= '" . date('Y-' . $month . '-t') . " 23:59:59'
                            "
                            );
                            $user_reply_time = 0;
                            while ($reply = mysqli_fetch_assoc($reply_time_sql)) {
                                //get time worked from hh:mm:ss to seconds
                                $reply_time_worked = strtotime($reply['ticket_reply_time_worked']) - strtotime('00:00:00');
                                $user_reply_time += $reply_time_worked;
                            }

                            $user_hours_worked = round($user_hours_worked / 3600, 2);
                            $user_reply_time = round($user_reply_time / 3600, 2);
                            //if user_max_hours is 0, show an infinity symbol
                            if ($user['user_max_hours'] == 0) {
                                $user['user_max_hours'] = '∞';
                            }
                            ?>
                            <tr>
                                <td><?= nullable_htmlentities($user['user_name']); ?></td>
                                <td><?php switch ($user['user_pay_type']) {
                                    case 'salary': echo 'Salary'; break;
                                    case 'hourly': echo 'Hourly'; break;
                                    case 'contractor': echo 'Contractor'; break;
                                } ?></td>
                                <td>
                                    <?= $user_hours_worked . ' / ' . $user['user_max_hours']; ?>
                                    <?php if ($time_running_icon) { ?><i class="fas fa-fw fa-stopwatch ml-2"></i><?php } ?>
                                    <?php if ($break_icon) { ?><i class="fas fa-fw fa-coffee ml-2"></i><?php } ?>
                                </td>
                                <td><?= $user_reply_time . ' hours, $' . 125*$user_reply_time; ?></td>
                                <td><?= numfmt_format_currency($currency_format, $user['user_pay_rate'], $session_company_currency); ?>
                                <?php if ($user['user_pay_type'] == 'hourly') { ?>/hr<?php }
                                elseif ($user['user_pay_type'] == 'contractor') { ?>/billable hr<?php }
                                else { ?>/mo<?php } ?></td>
                                <td><?php
                                if ($user['user_pay_type'] == 'hourly') {
                                    $user_pay = $user['user_pay_rate'] * $user_hours_worked;
                                    echo numfmt_format_currency($currency_format, $user_pay, $session_company_currency);
                                } elseif ($user['user_pay_type'] == 'contractor') {
                                    $user_pay = $user['user_pay_rate'] * $user_reply_time;
                                    echo numfmt_format_currency($currency_format, $user_pay, $session_company_currency);
                                } else {
                                    $user_pay = $user['user_pay_rate'];
                                    echo numfmt_format_currency($currency_format, $user['user_pay_rate'], $session_company_currency);
                                }
                                ?></td>
                            </tr>
                        <?php 
                        $total_hours_worked += $user_hours_worked;
                        $total_billable_hours += $user_reply_time;
                        $total_pay += $user_pay;
                        } ?>
                        <tr>
                            <td colspan="2" class="text-right"><strong>Total</strong></td>
                            <td><strong><?= $total_hours_worked; ?></strong></td>
                            <td><strong><?= $total_billable_hours . ' hours, $' . 125*$total_billable_hours; ?></strong></td>
                            <td></td>
                            <td><strong><?= numfmt_format_currency($currency_format, $total_pay, $session_company_currency); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require "/var/www/portal.twe.tech/includes/footer.php";
