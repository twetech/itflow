<?php 

if (!file_exists("/var/www/portal.twe.tech/includes/config.php")) {
	header("Location: setup.php");
}
else {
	header("Location: /landing/landing-page.php");
}



?>