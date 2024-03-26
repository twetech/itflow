<?php

    // Calculate Execution time start
    // uncomment for test
    // $time_start = microtime(true);

header("X-Frame-Options: DENY");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="robots" content="noindex">

    <title><?php echo nullable_htmlentities($session_company_name); ?> | <?php echo nullable_htmlentities($config_app_name); ?></title>

        <!-- jQuery -->
        <script src="/includes/plugins/jquery/jquery.min.js"></script>
    <script src="/includes/plugins/toastr/toastr.min.js"></script>
    
        <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.css"> 

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.css"> 

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>

    <!-- DataTables Responsive -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap4.css">
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/3.0.0/js/responsive.bootstrap4.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap4.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <!-- 
    Favicon
    If Fav Icon exists else use the default one 
    -->
    <?php if(file_exists('/var/www/develop.twe.tech/uploads/favicon.ico')) { ?>
        <link rel="icon" type="image/x-icon" href="/uploads/favicon.ico">
    <?php } else { ?>
        <link rel="icon" type="image/x-icon" href="/includes/dist/img/favicon.ico">
    <?php } ?>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/includes/plugins/fontawesome-free/css/all.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="/includes/dist/css/theme.css">

    <!-- Custom Style Sheet -->
    <link href="/includes/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" type="text/css">
    <link href="/includes/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="/includes/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href='/includes/plugins/daterangepicker/daterangepicker.css' rel='stylesheet' />
    <link href="/includes/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/includes/dist/vendor/datatables/datatables.css" rel="stylesheet">



</head>
<body>

