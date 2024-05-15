<!doctype html>

<?php

if (file_exists("/var/www/nestogy.io/includes/config.php")) {
    include "/var/www/nestogy.io/includes/config.php";

}
//start session if not already started

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "/var/www/nestogy.io/includes/functions/functions.php";

include "/var/www/nestogy.io/database/database_version.php";

include_once "/var/www/nestogy.io/includes/settings_localization_array.php";

// Get a list of all available timezones
$timezones = DateTimeZone::listIdentifiers();

if (isset($_GET['starter'])) {
    $tier = 'starter';
} else if (isset($_GET['pro'])) {
    $tier = 'pro';
} else if (isset($_GET['enterprise'])) {
    $tier = 'enterprise';
} else {
    $tier = 'starter';
}

?>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="/includes/assets/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Register Cover - Pages | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

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
    <!-- Vendor -->
    <link rel="stylesheet" href="/includes/assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="/includes/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="/includes/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/includes/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/includes/assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="/includes/assets/img/illustrations/girl-with-laptop-light.png" class="img-fluid" alt="Login image" width="700" data-app-dark-img="illustrations/girl-with-laptop-dark.png" data-app-light-img="illustrations/girl-with-laptop-light.png" />
                </div>
            </div>
            <!-- /Left Text -->

            <!-- Register -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <?php
                    
                    if ($tier == 'starter') {
                        require 'register_starter.php';
                    } else if ($tier == 'pro') {
                        include 'register_pro.php';
                    } else if ($tier == 'enterprise') {
                        include 'register_enterprise.php';
                    } else {
                        include 'register_starter.php';
                    }
                    
                    ?>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="/includes/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/includes/assets/vendor/libs/popper/popper.js"></script>
    <script src="/includes/assets/vendor/js/bootstrap.js"></script>
    <script src="/includes/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/includes/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="/includes/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="/includes/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/includes/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/includes/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="/includes/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="/includes/assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->
    <script src="/includes/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="/includes/assets/js/pages-auth.js"></script>
</body>

</html>