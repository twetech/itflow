<?php

/*
 * ITFlow - Main GET/POST request handler
 */

require_once "/var/www/develop.twe.tech/includes/config.php";

require_once "/var/www/develop.twe.tech/includes/functions.php";

require_once "/var/www/develop.twe.tech/includes/check_login.php";

requireOnceAll("/var/www/develop.twe.tech/post");

?>
