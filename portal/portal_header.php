<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/includes/assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>ITFlow-NG</title>

    <meta name="description" content="" />

    <link rel="manifest" href="/manifest.json">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <!-- Helpers -->
    <script src="/includes/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/includes/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/includes/assets/js/config.js"></script>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/78o64w2w2bmaf98z8p7idos4tjloc808tr1j9iv8efl63nce/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script></head>

</head>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><?php echo nullable_htmlentities($session_company_name); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == "index.php") {echo "active";} ?>">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == "tickets.php" || basename($_SERVER['PHP_SELF']) == "ticket_add.php" || basename($_SERVER['PHP_SELF']) == "ticket.php") {echo "active";} ?>" href="tickets.php">Tickets</a>
                    </li>
                
                <?php if (($session_contact_primary == 1 || $session_contact_is_billing_contact) && $config_module_enable_accounting == 1) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == "invoices.php") {echo "active";} ?>" href="invoices.php">Invoices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == "quotes.php") {echo "active";} ?>" href="quotes.php">Quotes</a>
                    </li>
                <?php } ?>
                <?php if ($session_contact_primary == 1 || $session_contact_is_technical_contact) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == "documents.php") {echo "active";} ?>" href="documents.php">Documents</a>
                    </li>
                <?php } ?>
            </ul>

            <ul class="nav navbar-nav pull-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <?php echo stripslashes(nullable_htmlentities($session_contact_name)); ?>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.php"><i class="fas fa-fw fa-user mr-2"></i>Account</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="portal_post.php?logout"><i class="fas fa-fw fa-sign-out-alt mr-2"></i>Sign out</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<br>

<!-- Page content container -->
<div class="container">

    <div class="row mb-3">
        <div class="col-md-1 text-center">
            <?php if (!empty($session_contact_photo)) { ?>
                <img src="<?php echo "..//uploads/clients/$session_client_id/$session_contact_photo"; ?>" alt="..." height="50" width="50" class="rounded-circle mb-3img-responsive">

            <?php } else { ?>

                <span class="fa-stack fa-2x rounded-left">
                    <i class="fa fa-circle fa-stack-2x text-secondary"></i>
                    <span class="fa fa-stack-1x text-white"><?php echo $session_contact_initials; ?></span>
                </span>
            <?php } ?>
        </div>

        <div class="col-md-11 p-0">
            <h4>Welcome, <strong><?php echo stripslashes(nullable_htmlentities($session_contact_name)); ?></strong>!</h4>
            <hr>
        </div>
    </div>
