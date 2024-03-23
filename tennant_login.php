<?php
// Purpose: Login page for tennants
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ITFlow-NG | Login</title>
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
					<a class="u-login-form py-3 mb-auto login-logo" href="index.html">
                        <img alt="">
					</a>

                    <?php if(!empty($config_login_message)){ ?>
                        <p class="login-box-msg px-0"><?php echo nl2br($config_login_message); ?></p>
                    <?php } ?>

                    <?php if (isset($response)) { ?>
                        <p><?php echo $response; ?></p>
                    <?php } ?>

					<div class="u-login-form">
						<form method="get" action="/pages/login.php">
							<div class="mb-3">
								<h1 class="h2">Welcome Back!</h1>
								<p class="small">Enter your Company ID to be redirected to your login page</p>
							</div>

							<div class="form-group mb-4" <?php if (isset($token_field)) { echo "style='display:none;'"; } ?> >
								<label for="email">Company ID</label>
                                <input type="text" class="form-control" placeholder="Company ID" name="tennant_id" autofocus>
							</div>

							<button class="btn btn-primary btn-block" type="submit" name="next">Next</button>
						</form>

                        <?php if($config_client_portal_enable == 1){ ?>
                        <hr>
                        <h5 class="text-center">Looking for the <a href="portal">Client Portal?</a></h5>
                        <?php } ?>
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
        <script src="/includes/js/login_prevent_resubmit.js"></script>

    </body>
</html>