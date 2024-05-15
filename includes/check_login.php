<?php

if (!isset($_SESSION)) {
    // HTTP Only cookies
    ini_set("session.cookie_httponly", true);
    if ($config_https_only) {
        // Tell client to only send cookie(s) over HTTPS
        ini_set("session.cookie_secure", true);
    }
    session_start();
}


//Check to see if setup is enabled
if (!isset($config_enable_setup) || $config_enable_setup == 1) {
    echo "Setup is enabled, please disable setup in the config.php file to continue.";
    exit;
}

// Check user is logged in with a valid session
if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
    if ($_SERVER["REQUEST_URI"] == "/") {
        header("Location: /pages/login.php");
        exit;
    } else {
        header("Location: /pages/login.php?last_visited=" . base64_encode($_SERVER["REQUEST_URI"]) );
        exit;
    }
    exit;
}

// User IP & UA
$session_ip = sanitizeInput(getIP());
$session_user_agent = sanitizeInput($_SERVER['HTTP_USER_AGENT']);

$session_user_id = intval($_SESSION['user_id']);


// Get companies associated with the user
$sql = mysqli_query($mysqli, "SELECT * FROM users
    LEFT JOIN user_companies ON user_id = user_company_user_id
    WHERE user_id = $session_user_id");


// if multiple companies, get the send to the company selection page which will set the session company_id
if (mysqli_num_rows($sql) > 1) {
    error_log("Multiple companies found for the user");
    $session_multiple_companies = true;
    if (!isset($_SESSION['company_id'])) {
        error_log("No company_id set in session");
        header("Location: /pages/company_select.php");
        exit;
    } else {
        $session_company_id = intval($_SESSION['company_id']);
        error_log("Company ID set in session: $session_company_id");
    }

} else if (mysqli_num_rows($sql) == 0) {
    // No companies found for the user
    echo "No companies found for the user";
    exit;
} else if (mysqli_num_rows($sql) == 1) {
    // Set the session company_id
    $row = mysqli_fetch_assoc($sql);
    $session_company_id = intval($row['user_company_company_id']);
    error_log("Company ID set in session: $session_company_id");
    error_log("User ID set in session: $session_user_id");
}



// Get the user's details
$sql = mysqli_query($mysqli, "SELECT * FROM users
    LEFT JOIN user_settings ON users.user_id = user_settings.user_id
    LEFT JOIN user_companies ON users.user_id = user_company_user_id
    LEFT JOIN companies ON user_company_company_id = company_id
    WHERE users.user_id = $session_user_id AND user_company_company_id = $session_company_id");

$row = mysqli_fetch_assoc($sql);

$session_user_id = intval($row['user_id']);
$session_name = sanitizeInput($row['user_name']);
$session_email = $row['user_email'];
$session_avatar = $row['user_avatar'];
$session_token = $row['user_token'];
$session_user_role = intval($row['user_role']);
$session_user_company_id = $session_company_id;
if ($session_user_role == 3) {

    $session_user_role_display = "Administrator";
} elseif ($session_user_role == 2) {
    $session_user_role_display = "Technician";
} else {
    $session_user_role_display = "Accountant";
}
$session_user_config_force_mfa = intval($row['user_config_force_mfa']);
$user_config_records_per_page = intval($row['user_config_records_per_page']);

$session_company_name = $row['company_name'];
$session_company_country = $row['company_country'];
$session_company_locale = $row['company_locale'];
$session_company_currency = $row['company_currency'];
$session_company_reseller = $row['company_reseller'] == 1 ? true : false;
$session_timezone = $row['config_timezone'];

// Set Timezone to the companies timezone
// 2024-02-08 JQ - The option to set the timezone in PHP was disabled to prevent inconsistencies with MariaDB/MySQL, which utilize the system's timezone, It is now consdered best practice to set the timezone on system itself
//date_default_timezone_set($session_timezone);

// 2024-03-21 JQ - Re-Enabled Timezone setting as new PHP update does not respect System Time but defaulted to UTC
date_default_timezone_set($session_timezone);

//Set Currency Format
$currency_format = numfmt_create($session_company_locale, NumberFormatter::CURRENCY);

require_once "/var/www/nestogy.io/includes/get_settings.php";


//Detects if using an Apple device and uses Apple Maps instead of google
$iPod = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
$iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
$iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");

if ($iPod || $iPhone || $iPad) {
    $session_map_source = "apple";
} else {
    $session_map_source = "google";
}

//Check if mobile device
$session_mobile = isMobile();

//Get Notification Count for the badge on the top nav
$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('notification_id') AS num FROM notifications WHERE (notification_user_id = $session_user_id OR notification_user_id = 0) AND notification_dismissed_at IS NULL"));
$num_notifications = $row['num'];

// Get localization array from localizations/ for the company's locale
$localization = getLocalization($session_company_locale);


