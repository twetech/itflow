<?php

require_once "/var/www/develop.twe.tech/includes/tenant_db.php";

require_once "/var/www/develop.twe.tech/includes/config.php";

require_once "/var/www/develop.twe.tech/includes/functions/functions.php";

require_once "/var/www/develop.twe.tech/includes/check_login.php";

require_once "/var/www/develop.twe.tech/includes/header.php";

require_once "/var/www/develop.twe.tech/includes/top_nav.php";


// Set variable default values
$largest_income_month = 0;
$largest_invoice_month = 0;
$recurring_total = 0;
