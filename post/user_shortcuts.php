<?php

if (isset($_GET['add_shortcut'])) {

    global $mysqli, $session_user_id;

    $shortcut_key = $_GET['add_shortcut'];

    //check if the shortcut exists in the shortcuts map

    require_once "/var/www/develop.twe.tech/includes/shortcuts.php";

    if (!array_key_exists($shortcut_key, $shortcutsMap)) {
        referWithAlert("Shortcut not found", "danger");
        exit;
    }

    //check if the shortcut already exists in the user_shortcuts table
    $shortcut_sql = "SELECT * FROM user_shortcuts WHERE user_shortcut_user_id = $session_user_id AND user_shortcut_key = '$shortcut_key'";
    $shortcut_result = mysqli_query($mysqli, $shortcut_sql);
    
    if (mysqli_num_rows($shortcut_result) > 0) {
        referWithAlert("Shortcut already exists", "danger");
        exit;
    }

    $shortcut_sql = "SELECT * FROM user_shortcuts WHERE user_shortcut_user_id = $session_user_id";
    $shortcut_result = mysqli_query($mysqli, $shortcut_sql);

    $shortcut_sql = "INSERT INTO user_shortcuts SET user_shortcut_key = '$shortcut_key', user_shortcut_user_id = $session_user_id, user_shortcut_order = ".mysqli_num_rows($shortcut_result);
    mysqli_query($mysqli, $shortcut_sql);

    referWithAlert("Shortcut added", "success");

}