<?php

/*
 * ITFlow - Main GET/POST request handler
 */

require_once "/var/www/nestogy.io/includes/tenant_db.php";

require_once "/var/www/nestogy.io/includes/config.php";

require_once "/var/www/nestogy.io/includes/functions/functions.php";

require_once "/var/www/nestogy.io/includes/check_login.php";

requireOnceAll("/var/www/nestogy.io/post");

?>
