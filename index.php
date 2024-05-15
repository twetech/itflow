<?php 

if (!file_exists("/var/www/nestogy.io/includes/config.php")) {
	header("Location: setup.php");
	exit;
}
else {
	// if domain is nestogy.io, direct to pages/login.php
	if ($_SERVER['HTTP_HOST'] == "nestogy.io") {
		header("Location: landing/");
		exit;
	} else if ($_SERVER['HTTP_HOST'] == "nesto.pro") {
		header("Location: portal/login.php");
		exit;
	}
	else {
		header("Location: pages/login.php");
		exit;
	}
}



?>