<?php

require_once "/var/www/develop.twe.tech/includes/tenant_db.php";

require_once "/var/www/develop.twe.tech/includes/config.php";

require_once "/var/www/develop.twe.tech/includes/functions/functions.php";


session_start();

$ip = sanitizeInput(getIP());
$user_agent = sanitizeInput($_SERVER['HTTP_USER_AGENT']);
$os = sanitizeInput(getOS($user_agent));
$browser = sanitizeInput(getWebBrowser($user_agent));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="robots" content="noindex">

    <title><?php echo nullable_htmlentities($config_app_name); ?></title>

    <!-- 
    Favicon
    If Fav Icon exists else use the default one 
    -->
    <?php if(file_exists('/var/www/develop.twe.tech/uploads/favicon.ico')) { ?>
        <link rel="icon" type="image/x-icon" href="//uploads/favicon.ico">
    <?php } ?>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">

    <meta name="description" content="" />

<link rel="manifest" href="/manifest.json">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="/includes/assets/img/favicon/favicon.ico" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

<!-- Icons -->
<link rel="stylesheet" href="/includes/assets/vendor/fonts/boxicons.css" />
<link rel="stylesheet" href="/includes/assets/vendor/fonts/fontawesome.css" />
<link rel="stylesheet" href="/includes/assets/vendor/fonts/flag-icons.css" />

<!-- Core CSS -->
<link rel="stylesheet" href="/includes/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
<link rel="stylesheet" href="/includes/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
<link rel="stylesheet" href="/includes/assets/css/demo.css" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="/includes/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="/includes/assets/vendor/libs/typeahead-js/typeahead.css" />
<link rel="stylesheet" href="/includes/assets/vendor/libs/apex-charts/apex-charts.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.1/css/responsive.bootstrap5.css" />
<link rel="stylesheet" href="/includes/assets/vendor/libs/spinkit/spinkit.css" />
<link rel="stylesheet" href="/includes/assets/vendor/libs/toastr/toastr.css" />
<link rel="stylesheet" href="/includes/assets/vendor/libs/apex-charts/apex-charts.css" />


<!-- Page CSS -->

<!-- Helpers -->
<script src="/includes/assets/vendor/js/helpers.js"></script>
<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
<script src="/includes/assets/vendor/js/template-customizer.js"></script>
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="/includes/assets/js/config.js"></script>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/78o64w2w2bmaf98z8p7idos4tjloc808tr1j9iv8efl63nce/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script></head>


<body class="layout-top-nav">
<div class="wrapper text-sm">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Main content -->
        <div class="content">
            <div class="container">

                <?php
                //Alert Feedback
                if (!empty($_SESSION['alert_message'])) {
                    if (!isset($_SESSION['alert_type'])) {
                        $_SESSION['alert_type'] = "info";
                    }
                    ?>
                    <div class="alert alert-<?php echo $_SESSION['alert_type']; ?>" id="alert">
                        <?php echo nullable_htmlentities($_SESSION['alert_message']); ?>
                        <button class='close' data-bs-dismiss='alert'>&times;</button>
                    </div>
                    <?php

                    unset($_SESSION['alert_type']);
                    unset($_SESSION['alert_message']);

                }
                ?>
