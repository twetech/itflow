<?php 

if (!file_exists("/var/www/portal.twe.tech/includes/config.php")) {
	header("Location: setup.php");
}
else {
	header("Location: /pages/login.php?tenant_id=");
}



?>