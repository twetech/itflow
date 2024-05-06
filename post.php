<?php

/*
 * ITFlow - Main GET/POST request handler
 */

require_once "/var/www/develop.twe.tech/includes/tenant_db.php";

require_once "/var/www/develop.twe.tech/includes/config.php";

require_once "/var/www/develop.twe.tech/includes/functions/functions.php";

require_once "/var/www/develop.twe.tech/includes/check_login.php";

requireOnceAll("/var/www/develop.twe.tech/post");

?>
