<?php

require_once "/var/www/develop.twe.tech/includes/config.php";

require_once "/var/www/develop.twe.tech/includes/functions/functions.php";

require_once "/var/www/develop.twe.tech/includes/check_login.php";

require_once "/var/www/develop.twe.tech/includes/header.php";

require_once "/var/www/develop.twe.tech/includes/top_nav.php";

require_once "/var/www/develop.twe.tech/includes/inc_side_nav.php";

require_once "/var/www/develop.twe.tech/includes/reports_side_nav.php";

require_once "/var/www/develop.twe.tech/includes/inc_side_nav_close.php";

require_once "/var/www/develop.twe.tech/includes/inc_wrapper.php";

require_once "/var/www/develop.twe.tech/includes/inc_alert_feedback.php";


// Set variable default values
$largest_income_month = 0;
$largest_invoice_month = 0;
$recurring_total = 0;
