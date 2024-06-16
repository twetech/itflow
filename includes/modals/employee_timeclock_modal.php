<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<?php
// employee_timeclock_modal.php
$user_id = $_POST['user_id'] ?? $session_user_id;

// Find if the user is already clocked in
$employee_time = mysqli_query($mysqli,
    "SELECT * FROM employee_times
    WHERE employee_id = '$user_id'
    AND employee_time_end = '0000-00-00 00:00:00'
");
if (mysqli_num_rows($employee_time) > 0) {
    $time_id = $_SESSION['time_id'] ?? 0;
    $time = mysqli_fetch_assoc($employee_time);
    $time_in = $time['employee_time_start'];
    $employee_breaks = mysqli_query($mysqli,
        "SELECT * FROM employee_time_breaks
        WHERE employee_time_id = '$time_id'
        AND employee_break_time_end = '0000-00-00 00:00:00'
    ");
    if (mysqli_num_rows($employee_breaks) > 0) {
        $break = mysqli_fetch_assoc($employee_breaks);
        $break_time_in = $break['employee_break_time_start'];
        $clock_status = "break";
    } else {
        $clock_status = "in";
        // Check if the user has taken a break today
        $employee_breaks = mysqli_query($mysqli,
            "SELECT * FROM employee_time_breaks
            WHERE employee_time_id = '$time_id'
            AND employee_break_time_end != '0000-00-00 00:00:00'
            AND employee_time_id = '$time_id'
        ");
        if (mysqli_num_rows($employee_breaks) > 0) {
            $break = mysqli_fetch_assoc($employee_breaks);
            $break_time_in = $break['employee_break_time_start'];
            $break_time_out = $break['employee_break_time_end'];
            $employee_break_taken = true;
        }
    }
} else {
    $clock_status = "out";
}

?>

<div class="modal-header">
    <h5 class="modal-title">Employee Time Clock</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <?php
        echo "<strong>Clock Status:</strong> " . ucfirst($clock_status);
    ?>
    <br>
    <?php if ($clock_status == "in") { ?>
        <p>
            <strong>Time In:</strong> 
            <?php
                echo date("F j, Y, g:i a", strtotime($time_in));

                if ($employee_break_taken) {
                    echo "<br><strong>Break Time In:</strong> " . date("F j, Y, g:i a", strtotime($break_time_in));
                    echo "<br><strong>Break Time Out:</strong> " . date("F j, Y, g:i a", strtotime($break_time_out));
                }
            ?>
        </p>
        <form method="post" action="/post.php">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
            <?php
                if (!$employee_break_taken) {
                    echo "<button type='submit' name='employee_break_start' class='btn btn-primary'>Start Break</button>";
                }
            ?>
            <button type="submit" name="employee_time_out" class="btn btn-primary">Clock Out</button>
        </form>
    <?php } elseif ($clock_status == "out") { ?>
        <form method="post" action="/post.php">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
            <button type="submit" name="employee_time_in" class="btn btn-primary">Clock In</button>
        </form>
    <?php } elseif ($clock_status == "break") { ?>
        <p>
            <strong>Time In:</strong>
            <?php
                echo date("F j, Y, g:i a", strtotime($time_in));
            ?>
            <br>
            <strong>Break Time Start:</strong> 
            <?php
                echo date("F j, Y, g:i a", strtotime($break_time_in));
            ?>
        </p>
        <form method="post" action="/post.php">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
            <button type="submit" name="employee_break_end" class="btn btn-primary">End Break</button>
        </form>
    <?php } ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>