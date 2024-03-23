<?php 

if (file_exists("/var/www/develop.twe.tech/includes/config.php")) {
	header("Location: tennant_login.php");

} else {
	header("Location: setup.php");
}

?>