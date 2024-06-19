<?php

global $mysqli, $session_name, $session_ip, $session_user_agent, $session_user_id;


/*
 * ITFlow - GET/POST request handler for employees
 */

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

if (isset($_POST['link_employee'])) {
    $user_id = $_POST['user_id'];
    $sql = "INSERT INTO user_employees SET 
        user_id = '$user_id',
        user_pay_type = 'hourly',
        user_pay_rate = 7.25,
        user_max_hours = 50,
        user_payroll_id = 0";

    if (mysqli_query($mysqli, $sql)) {
        referWithAlert("Employee linked successfully.", "success");
    } else {
        referWithAlert("Error linking employee: " . mysqli_error($mysqli), "danger");
    }
}

if (isset($_POST['unlink_employee'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM user_employees WHERE user_id = '$user_id'";

    if (mysqli_query($mysqli, $sql)) {
        referWithAlert("Employee unlinked successfully.", "success");
    } else {
        referWithAlert("Error unlinking employee: " . mysqli_error($mysqli), "danger");
    }
}

if (isset($_POST['update_employee'])) {
    $user_id = $_POST['user_id'];
    $user_pay_type = $_POST['user_pay_type'];
    $user_pay_rate = $_POST['user_pay_rate'];
    $user_max_hours = $_POST['user_max_hours_per_week'] ?? 0;

    $sql = "UPDATE user_employees SET 
        user_pay_type = '$user_pay_type',
        user_pay_rate = '$user_pay_rate',
        user_max_hours = '$user_max_hours'
        WHERE user_id = '$user_id'";

    if (mysqli_query($mysqli, $sql)) {
        referWithAlert("Employee updated successfully.", "success");
    } else {
        referWithAlert("Error updating employee: " . mysqli_error($mysqli), "danger");
    }
}

if (isset($_POST['employee_time_in'])) {
    $user_id = $_POST['user_id'];
    $time_notes = $_POST['time_notes'] ?? "";
    $time_in = date("Y-m-d H:i:s");


    $sql = "INSERT INTO employee_times
        SET employee_id = '$user_id',
        employee_time_start = '$time_in'
    ";
    $time_set = mysqli_query($mysqli, $sql);
    $time_id = mysqli_insert_id($mysqli);

    //Set the session variable for the time_id
    $_SESSION['time_id'] = $time_id;
    referWithAlert("Employee clocked in successfully.", "success");
}

if (isset($_POST['employee_time_out'])) {
    $time_id = $_SESSION['time_id'];
    $time_out = date("Y-m-d H:i:s");

    $sql = "UPDATE employee_times
        SET employee_time_end = '$time_out'
        WHERE employee_time_id = '$time_id'";
    $time_set = mysqli_query($mysqli, $sql);

    //Unset the session variable for the time_id
    unset($_SESSION['time_id']);
    referWithAlert("Employee clocked out successfully.", "success");
}

if (isset($_POST['employee_break_start'])) {
    $time_id = $_SESSION['time_id'];
    $break_notes = $_POST['break_notes'] ?? "";
    $break_start = date("Y-m-d H:i:s");

    error_log("Time ID: $time_id");

    $sql = "INSERT INTO employee_time_breaks
        SET employee_time_id = '$time_id',
        employee_break_time_start = '$break_start',
        employee_break_time_end = '0000-00-00 00:00:00',
        employee_break_time_notes = '$break_notes'";
    error_log($sql);
    $break_set = mysqli_query($mysqli, $sql);
    $break_id = mysqli_insert_id($mysqli);

    //Set the session variable for the break_id
    $_SESSION['break_id'] = $break_id;
    referWithAlert("Employee break started successfully.", "success");
}

if (isset($_POST['employee_break_end'])) {
    $break_id = $_SESSION['break_id'];
    $break_end = date("Y-m-d H:i:s");

    $sql = "UPDATE employee_time_breaks
        SET employee_break_time_end = '$break_end'
        WHERE employee_time_break_id = '$break_id'";
    $break_set = mysqli_query($mysqli, $sql);

    //Unset the session variable for the break_id
    unset($_SESSION['break_id']);
    referWithAlert("Employee break ended successfully.", "success");
}