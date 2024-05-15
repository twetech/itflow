<?php

// Enforce a Content Security Policy for security against cross-site scripting
header("Content-Security-Policy: default-src 'self' fonts.googleapis.com fonts.gstatic.com");

if (!file_exists('/var/www/nestogy.io/includes/config.php')) {
    header("Location: setup.php");
    exit;
}

if (!isset($_SESSION)) {
    // Tell client to only send cookie(s) over HTTPS
    ini_set("session.cookie_secure", true);
    session_start();
}

require_once "/var/www/nestogy.io/includes/config.php";

// Check if $mysqli is a valid connection
if (!$mysqli) {
    header("Location: /");
    exit;
}

// Check if the application is configured for HTTPS-only access
if ($config_https_only && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') && (!isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https')) {
    echo "Login is restricted as ITFlow defaults to HTTPS-only for enhanced security. To login using HTTP, modify the config.php file by setting config_https_only to false. However, this is strongly discouraged, especially when accessing from potentially unsafe networks like the internet.";
    exit;
}

require_once "/var/www/nestogy.io/includes/functions/functions.php";

require_once "/var/www/nestogy.io/includes/rfc6238.php";



// IP & User Agent for logging
$ip = sanitizeInput(getIP());
$user_agent = sanitizeInput($_SERVER['HTTP_USER_AGENT']);
$session_user_id = intval($_SESSION['user_id']);

// Block brute force password attacks - check recent failed login attempts for this IP
//  Block access if more than 15 failed login attempts have happened in the last 10 minutes
$row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(log_id) AS failed_login_count FROM logs WHERE log_ip = '$ip' AND log_type = 'Login' AND log_action = 'Failed' AND log_created_at > (NOW() - INTERVAL 10 MINUTE)"));
$failed_login_count = intval($row['failed_login_count']);

if ($failed_login_count >= 15) {

    // Logging
    mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Login', log_action = 'Blocked', log_description = '$ip was blocked access to login due to IP lockout', log_ip = '$ip', log_user_agent = '$user_agent'");

    // Inform user & quit processing page
    exit("<h2>$config_app_name</h2>Your IP address has been blocked due to repeated failed login attempts. Please try again later. <br><br>This action has been logged.");
}

if (isset($_POST['continue'])) {
    $company_id = intval($_POST['company_id']);
    $_SESSION['company_id'] = $company_id;

    // Logging
    mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Company', log_action = 'Selected', log_description = 'User selected company $company_id', log_user_id = $session_user_id, log_ip = '$ip', log_user_agent = '$user_agent'");
    header("Location: /pages/dashboard.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Company Selection</title>
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="/includes/plugins/fontawesome-free/css/all.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="keywords" content="Bootstrap Theme, Freebies, Dashboard, MIT license">
    <meta name="description" content="Stream - Dashboard UI Kit">
    <meta name="author" content="htmlstream.com">

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Web Fonts -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Components Vendor Styles -->
    <link rel="stylesheet" href="/includes/dist/vendor/font-awesome/css/all.min.css">

    <!-- Theme Styles -->
    <link rel="stylesheet" href="/includes/dist/css/theme.css">
</head>

<body class="hold-transition login-page">
		<main class="container-fluid w-100" role="main">
			<div class="row">
				<div class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-white mnh-100vh login-box">
					<div class="w-75">
                        <div class="text-center">
                            <a href="/" aria-label="Nestogy">
                                <img class="img-fluid" src="/includes/dist/svg/logos/logo.svg" alt="Nestogy">
                            </a>
                        </div>

                        <div class="mb-4">
                            <h1 class="h2">Select Company</h1>
                            <p class="text-muted
                            ">Select the company you would like to access.</p>
                        </div>

                        <form action="" method="post">
                            <div class="form-group
                            ">
                                <label for="company_id">Company</label>
                                <select class="form-control" id="company_id" name="company_id" required>
                                    <option name="company_id" value="">Select Company</option>
                                    <?php
                                    $sql = mysqli_query($mysqli, "SELECT * FROM user_companies
                                    LEFT JOIN companies ON user_companies.user_company_company_id = companies.company_id
                                    WHERE user_company_user_id = $session_user_id
                                    ORDER BY company_name ASC");
                                    while ($row = mysqli_fetch_array($sql)) {
                                        $company_id = intval($row['company_id']);
                                        $company_name = nullable_htmlentities($row['company_name']);
                                        echo "<option value='$company_id'>$company_name</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" name="continue" class="btn btn-primary btn-block">Continue</button>
                        </form>
                    </div>


					<div class="u-login-form text-muted py-3 mt-auto">
						<small><i class="far fa-question-circle mr-1"></i> If you are not able to sign in, please <a href="mailto:help@twe.tech">contact us</a>.</small>
					</div>
				</div>

				<div class="col-lg-6 d-none d-lg-flex flex-column align-items-center justify-content-center bg-light">
					<img class="img-fluid position-relative u-z-index-3 mx-5" src="/includes/dist/svg/mockups/mockup.svg" alt="Image description">

					<figure class="u-shape u-shape--top-right u-shape--position-5">
						<img src="/includes/dist/svg/shapes/shape-1.svg" alt="Image description">
					</figure>
					<figure class="u-shape u-shape--center-left u-shape--position-6">
						<img src="/includes/dist/svg/shapes/shape-2.svg" alt="Image description">
					</figure>
					<figure class="u-shape u-shape--center-right u-shape--position-7">
						<img src="/includes/dist/svg/shapes/shape-3.svg" alt="Image description">
					</figure>
					<figure class="u-shape u-shape--bottom-left u-shape--position-8">
						<img src="/includes/dist/svg/shapes/shape-4.svg" alt="Image description">
					</figure>
				</div>
			</div>
		</main>

        <!-- jQuery -->
        <script src="/includes/plugins/jquery/jquery.min.js"></script>

        <!-- Bootstrap 4 -->
        <script src="/includes/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Prevents resubmit on refresh or back -->
        <script src="/js/login_prevent_resubmit.js"></script>

    </body>
</html>