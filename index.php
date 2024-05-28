<?php 

if (!file_exists("/var/www/portal.twe.tech/includes/config/config.php")) {
	header("Location: setup.php");
}
else {
	header("Location: /portal/login.php");
}



?>