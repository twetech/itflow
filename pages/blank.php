<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="robots" content="noindex">

    <title>TEST | ITFlow</title>

    <!-- 
    Favicon
    If Fav Icon exists else use the default one 
    -->

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/theme.css">

    <!-- Custom Style Sheet -->
    <link href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" type="text/css">
    <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href='plugins/daterangepicker/daterangepicker.css' rel='stylesheet' />
    <link href="plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/toastr/toastr.min.js"></script>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed accent-blue">
    <div class="wrapper text-sm">
        <main class="u-main" role="main">

            <!-- Header (Topbar) -->

            <header class="u-header">
                <div class="u-header-left">
                    <a class="u-header-logo" href="index.html">
                        <img class="u-logo-desktop" src="/includes/dist/img/logo.png" width="160" alt="Stream Dashboard">
                        <img class="img-fluid u-logo-mobile" src="dist/img/logo-mobile.png" width="50" alt="Stream Dashboard">
                    </a>
                </div>

                <div class="u-header-middle">
                    <a class="js-sidebar-invoker u-sidebar-invoker" href="#!" data-is-close-all-except-this="true" data-target="#sidebar">
                        <i class="fa fa-bars u-sidebar-invoker__icon--open"></i>
                        <i class="fa fa-times u-sidebar-invoker__icon--close"></i>
                    </a>

                    <div class="u-header-search" data-search-mobile-invoker="#headerSearchMobileInvoker" data-search-target="#headerSearch">
                        <a id="headerSearchMobileInvoker" class="btn btn-link input-group-prepend u-header-search__mobile-invoker" href="#!">
                            <i class="fa fa-search"></i>
                        </a>

                        <div id="headerSearch" class="u-header-search-form">
                            <form action="global_search.php">
                                <div class="input-group">
                                    <button class="btn-link input-group-prepend u-header-search__btn" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input class="form-control u-header-search__field" type="search" placeholder="Type to search…" name='query' value="">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="u-header-right">
                    <!-- Activities -->
                    <div class="dropdown mr-4">
                        <a class="link-muted" href="#!" role="button" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <span class="h3">
                                <i class="far fa-envelope"></i>
                            </span>
                            <span class="u-indicator u-indicator-top-right u-indicator--xxs bg-secondary"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-4" aria-labelledby="dropdownMenuLink" style="width: 360px;">
                            <div class="card">
                                <div class="card-header d-flex align-items-center py-3">
                                    <h2 class="h4 card-header-title">Activities</h2>
                                    <a class="ml-auto" href="#">Clear all</a>
                                </div>

                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <!-- Activity -->
                                        <a class="list-group-item list-group-item-action" href="#">
                                            <div class="media align-items-center">
                                                <img class="u-avatar--sm rounded-circle mr-3" src="dist/img/avatars/img1.jpg" alt="Image description">

                                                <div class="media-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mb-1">Chad Cannon</h4>
                                                        <small class="text-muted ml-auto">23 Jan 2018</small>
                                                    </div>

                                                    <p class="text-truncate mb-0" style="max-width: 250px;">
                                                        We've just done the project.
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- End Activity -->

                                        <!-- Activity -->
                                        <a class="list-group-item list-group-item-action" href="#">
                                            <div class="media align-items-center">
                                                <img class="u-avatar--sm rounded-circle mr-3" src="dist/img/avatars/img2.jpg" alt="Image description">

                                                <div class="media-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mb-1">Jane Ortega</h4>
                                                        <small class="text-muted ml-auto">18 Jan 2018</small>
                                                    </div>

                                                    <p class="text-truncate mb-0" style="max-width: 250px;">
                                                        <span class="text-primary">@Bruce</span> advertising your
                                                        project is not good idea.
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- End Activity -->

                                        <!-- Activity -->
                                        <a class="list-group-item list-group-item-action" href="#">
                                            <div class="media align-items-center">
                                                <img class="u-avatar--sm rounded-circle mr-3" src="dist/img/avatars/user-unknown.jpg" alt="Image description">

                                                <div class="media-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mb-1">Stella Hoffman</h4>
                                                        <small class="text-muted ml-auto">15 Jan 2018</small>
                                                    </div>

                                                    <p class="text-truncate mb-0" style="max-width: 250px;">
                                                        When the release date is expexted for the advacned settings?
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- End Activity -->

                                        <!-- Activity -->
                                        <a class="list-group-item list-group-item-action" href="#">
                                            <div class="media align-items-center">
                                                <img class="u-avatar--sm rounded-circle mr-3" src="dist/img/avatars/img4.jpg" alt="Image description">

                                                <div class="media-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mb-1">Htmlstream</h4>
                                                        <small class="text-muted ml-auto">05 Jan 2018</small>
                                                    </div>

                                                    <p class="text-truncate mb-0" style="max-width: 250px;">
                                                        Adwords Keyword research for beginners
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- End Activity -->
                                    </div>
                                </div>

                                <div class="card-footer py-3">
                                    <a class="btn btn-block btn-outline-primary" href="#">View all activities</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Activities -->

                    <!-- Notifications -->
                    <div class="dropdown mr-4">
                        <a class="link-muted" href="#!" role="button" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <span class="h3">
                                <i class="far fa-bell"></i>
                            </span>
                            <span class="u-indicator u-indicator-top-right u-indicator--xxs bg-info"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-4" aria-labelledby="dropdownMenuLink" style="width: 360px;">
                            <div class="card">
                                <div class="card-header d-flex align-items-center py-3">
                                    <h2 class="h4 card-header-title">Notifications</h2>
                                    <a class="ml-auto" href="#">Clear all</a>
                                </div>

                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <!-- Notification -->
                                        <a class="list-group-item list-group-item-action" href="#">
                                            <div class="media align-items-center">
                                                <div class="u-icon u-icon--sm rounded-circle bg-danger text-white mr-3">
                                                    <i class="fab fa-dribbble"></i>
                                                </div>

                                                <div class="media-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mb-1">Dribbble</h4>
                                                        <small class="text-muted ml-auto">23 Jan 2018</small>
                                                    </div>

                                                    <p class="text-truncate mb-0" style="max-width: 250px;">
                                                        <span class="text-primary">@htmlstream</span> just liked your
                                                        post!
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- End Notification -->

                                        <!-- Notification -->
                                        <a class="list-group-item list-group-item-action" href="#">
                                            <div class="media align-items-center">
                                                <div class="u-icon u-icon--sm rounded-circle bg-info text-white mr-3">
                                                    <i class="fab fa-twitter"></i>
                                                </div>

                                                <div class="media-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mb-1">Twitter</h4>
                                                        <small class="text-muted ml-auto">18 Jan 2018</small>
                                                    </div>

                                                    <p class="text-truncate mb-0" style="max-width: 250px;">
                                                        Someone mentioned you on the tweet.
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- End Notification -->

                                        <!-- Notification -->
                                        <a class="list-group-item list-group-item-action" href="#">
                                            <div class="media align-items-center">
                                                <div class="u-icon u-icon--sm rounded-circle bg-success text-white mr-3">
                                                    <i class="fab fa-spotify"></i>
                                                </div>

                                                <div class="media-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mb-1">Spotify</h4>
                                                        <small class="text-muted ml-auto">18 Jan 2018</small>
                                                    </div>

                                                    <p class="text-truncate mb-0" style="max-width: 250px;">
                                                        You've just recived $25 free gift card.
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- End Notification -->

                                        <!-- Notification -->
                                        <a class="list-group-item list-group-item-action" href="#">
                                            <div class="media align-items-center">
                                                <div class="u-icon u-icon--sm rounded-circle bg-info text-white mr-3">
                                                    <i class="fab fa-facebook-f"></i>
                                                </div>

                                                <div class="media-body">
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="mb-1">Facebook</h4>
                                                        <small class="text-muted ml-auto">18 Jan 2018</small>
                                                    </div>

                                                    <p class="text-truncate mb-0" style="max-width: 250px;">
                                                        <span class="text-primary">@htmlstream</span> commented in your
                                                        post.
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- End Notification -->
                                    </div>
                                </div>

                                <div class="card-footer py-3">
                                    <a class="btn btn-block btn-outline-primary" href="#">View all notifications</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Notifications -->

                    <!-- Apps -->
                    <div class="dropdown mr-4">
                        <a class="link-muted" href="#!" role="button" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <span class="h3">
                                <i class="far fa-circle"></i>
                            </span>
                            <span class="u-indicator u-indicator-top-right u-indicator--xxs bg-warning"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-4" aria-labelledby="dropdownMenuLink" style="width: 360px;">
                            <div class="card">
                                <div class="card-header d-flex align-items-center py-3">
                                    <h2 class="h4 card-header-title">Apps</h2>
                                    <a class="ml-auto" href="#">Learn more</a>
                                </div>

                                <div class="card-body py-3">
                                    <div class="row">
                                        <!-- App -->
                                        <div class="col-4 px-2 mb-2">
                                            <a class="u-apps d-flex flex-column rounded" href="#!">
                                                <img class="img-fluid u-avatar--xs mx-auto mb-2" src="dist/img/brands-sm/img1.png" alt="">
                                                <span class="text-center">Assana</span>
                                            </a>
                                        </div>
                                        <!-- End App -->

                                        <!-- App -->
                                        <div class="col-4 px-2 mb-2">
                                            <a class="u-apps d-flex flex-column rounded" href="#!">
                                                <img class="img-fluid u-avatar--xs mx-auto mb-2" src="dist/img/brands-sm/img2.png" alt="">
                                                <span class="text-center">Slack</span>
                                            </a>
                                        </div>
                                        <!-- End App -->

                                        <!-- App -->
                                        <div class="col-4 px-2 mb-2">
                                            <a class="u-apps d-flex flex-column rounded" href="#!">
                                                <img class="img-fluid u-avatar--xs mx-auto mb-2" src="dist/img/brands-sm/img3.png" alt="">
                                                <span class="text-center">Cloud</span>
                                            </a>
                                        </div>
                                        <!-- End App -->

                                        <!-- App -->
                                        <div class="col-4 px-2">
                                            <a class="u-apps d-flex flex-column rounded" href="#!">
                                                <img class="img-fluid u-avatar--xs mx-auto mb-2" src="dist/img/brands-sm/img5.png" alt="">
                                                <span class="text-center">Facebook</span>
                                            </a>
                                        </div>
                                        <!-- End App -->

                                        <!-- App -->
                                        <div class="col-4 px-2">
                                            <a class="u-apps d-flex flex-column rounded" href="#!">
                                                <img class="img-fluid u-avatar--xs mx-auto mb-2" src="dist/img/brands-sm/img4.png" alt="">
                                                <span class="text-center">Spotify</span>
                                            </a>
                                        </div>
                                        <!-- End App -->

                                        <!-- App -->
                                        <div class="col-4 px-2">
                                            <a class="u-apps d-flex flex-column rounded" href="#!">
                                                <img class="img-fluid u-avatar--xs mx-auto mb-2" src="dist/img/brands-sm/img6.png" alt="">
                                                <span class="text-center">Twitter</span>
                                            </a>
                                        </div>
                                        <!-- End App -->
                                    </div>
                                </div>

                                <div class="card-footer py-3">
                                    <a class="btn btn-block btn-outline-primary" href="#">View all apps</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Apps -->

                    <!-- User Profile -->
                    <div class="dropdown ml-2">
                        <a class="link-muted d-flex align-items-center" href="#!" role="button" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                            <img class="u-avatar--xs img-fluid rounded-circle mr-2" src="dist/img/avatars/img1.jpg" alt="User Profile">
                            <span class="text-dark d-none d-sm-inline-block">
                                Bruce Goodman <small class="fa fa-angle-down text-muted ml-1"></small>
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3" aria-labelledby="dropdownMenuLink" style="width: 260px;">
                            <div class="card">
                                <div class="card-header py-3">
                                    <!-- Storage -->
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="h6 text-muted text-uppercase mb-0">Storage</span>

                                        <div class="ml-auto text-muted">
                                            <strong class="text-dark">60gb</strong> / 100gb
                                        </div>
                                    </div>

                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <!-- End Storage -->
                                </div>

                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-4">
                                            <a class="d-flex align-items-center link-dark" href="#!">
                                                <span class="h3 mb-0"><i class="far fa-user-circle text-muted mr-3"></i></span> View
                                                Profile
                                            </a>
                                        </li>
                                        <li class="mb-4">
                                            <a class="d-flex align-items-center link-dark" href="#!">
                                                <span class="h3 mb-0"><i class="far fa-list-alt text-muted mr-3"></i></span> Settings
                                            </a>
                                        </li>
                                        <li class="mb-4">
                                            <a class="d-flex align-items-center link-dark" href="#!">
                                                <span class="h3 mb-0"><i class="far fa-laugh-wink text-muted mr-3"></i></span> Invite
                                                your friends
                                            </a>
                                        </li>
                                        <li>
                                            <a class="d-flex align-items-center link-dark" href="#!">
                                                <span class="h3 mb-0"><i class="far fa-share-square text-muted mr-3"></i></span> Sign Out
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End User Profile -->
                </div>
            </header>
            <!-- End Header (Topbar) --><!-- Main Sidebar Container -->

            
            <aside id="sidebar" class="u-sidebar">
                <div class="u-sidebar-inner">
                    <header class="u-sidebar-header">
                        <a class="u-sidebar-logo" href="index.html">
                            <img class="img-fluid" src="/includes/dist/img/logo.png" width="124" alt="Stream Dashboard">
                        </a>
                    </header>

                    <nav class="u-sidebar-nav">
                        <ul class="u-sidebar-nav-menu u-sidebar-nav-menu--top-level">
                            <!-- Dashboard -->
                            <li class="u-sidebar-nav-menu__item">
                                <a class="u-sidebar-nav-menu__link active" href="dashboard.php">
                                    <i class="fa fa-cubes u-sidebar-nav-menu__item-icon"></i>
                                    <span class="u-sidebar-nav-menu__item-title">Dashboard</span>
                                </a>
                            </li>
                            <!-- End Dashboard -->

                            <!-- Support -->
                            <li class="u-sidebar-nav-menu__item">
                                <a class="u-sidebar-nav-menu__link" href="#!" data-target='#supportSideNav'>
                                    <i class="fa fa-question-circle u-sidebar-nav-menu__item-icon"></i>
                                    <span class="u-sidebar-nav-menu__item-title">Support</span>
                                    <span class="u-sidebar-nav-menu__item-arrow">
                                        <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
                                    </span>
                                </a>

                                <ul id="supportSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                                    <!-- Clients -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="clients.php">
                                            <i class="fa fa-users u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Clients</span>
                                        </a>
                                    </li>

                                    <!-- Tickets -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="tickets.php">
                                            <i class="fa fa-life-ring u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Tickets</span>
                                        </a>
                                    </li>

                                    <!-- Calendar -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="calendar_events.php">
                                            <i class="fa fa-calendar-alt u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Calendar</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Sales -->
                            <li class="u-sidebar-nav-menu__item">
                                <a class="u-sidebar-nav-menu__link" href="#!" data-target='#salesSideNav'>
                                    <i class="fa fa-chart-line u-sidebar-nav-menu__item-icon"></i>
                                    <span class="u-sidebar-nav-menu__item-title">Sales</span>
                                    <span class="u-sidebar-nav-menu__item-arrow">
                                        <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
                                    </span>
                                </a>

                                <ul id="salesSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                                    <!-- Quotes -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="quotes.php">
                                            <i class="fa fa-comment-dollar u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Quotes</span>
                                        </a>
                                    </li>

                                    <!-- Invoices -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="invoices.php">
                                            <i class="fa fa-file-invoice u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Invoices</span>
                                        </a>
                                    </li>

                                    <!-- Products -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="products.php">
                                            <i class="fa fa-box-open u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Products</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Accounting -->
                            <li class="u-sidebar-nav-menu__item">
                                <a class="u-sidebar-nav-menu__link" href="#!" data-target='#accountingSideNav'>
                                    <i class="fa fa-calculator u-sidebar-nav-menu__item-icon"></i>
                                    <span class="u-sidebar-nav-menu__item-title">Accounting</span>
                                    <span class="u-sidebar-nav-menu__item-arrow">
                                        <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
                                    </span>
                                </a>

                                <ul id="accountingSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                                    <!-- Payments -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="payments.php">
                                            <i class="fa fa-credit-card u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Payments</span>
                                        </a>
                                    </li>

                                    <!-- Vendors -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="vendors.php">
                                            <i class="fa fa-building u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Vendors</span>
                                        </a>
                                    </li>

                                    <!-- Expenses -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="expenses.php">
                                            <i class="fa fa-shopping-cart u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Expenses</span>
                                        </a>
                                    </li>

                                    <!-- Accounts -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="accounts.php">
                                            <i class="fa fa-piggy-bank u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Accounts</span>
                                        </a>
                                    </li>

                                    <!-- Transfers -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="transfers.php">
                                            <i class="fa fa-exchange-alt u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Transfers</span>
                                        </a>
                                    </li>

                                    <!-- Budget -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="budget.php">
                                            <i class="fa fa-balance-scale u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Budget</span>
                                        </a>
                                    </li>

                                    <!-- Trips -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="trips.php">
                                            <i class="fa fa-route u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Trips</span>
                                        </a>
                                    </li>


                                    <!-- Reports -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link " href="report_income_summary.php">
                                            <i class="fa fa-chart-line u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Reports</span>
                                        </a>
                                    </li>

                                    <!-- Admin -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link" href="admin_users.php">
                                            <i class="fa fa-user-shield u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Admin</span>
                                        </a>
                                    </li>

                                    <!-- Settings -->
                                    <li class="u-sidebar-nav-menu__item">
                                        <a class="u-sidebar-nav-menu__link" href="settings_company.php">
                                            <i class="fa fa-cogs
                        u-sidebar-nav-menu__item-icon"></i>
                                            <span class="u-sidebar-nav-menu__item-title">Settings</span>
                                        </a>
                                    </li>
                                </ul>
                    </nav>
                </div>
            </aside>
            <!-- End Main Sidebar Container -->
            <!-- Content Wrapper. Contains page content -->
            <div class="u-content">

                <!-- Main content -->
                <div class="u-body">
                    <div class="row">

                        <div class="card card-body">
                            <form class="form-inline">
                                <input type="hidden" name="enable_financial" value="0">
                                <input type="hidden" name="enable_technical" value="0">

                                <select onchange="this.form.submit()" class="form-control mr-sm-3 col-sm-2" name="year">
                                    <option> 2025</option>

                                    <option selected> 2024</option>

                                </select>

                                <div class="custom-control custom-switch mr-sm-3">
                                    <input type="checkbox" onchange="this.form.submit()" class="custom-control-input" id="customSwitch1" name="enable_financial" value="1" checked>
                                    <label class="custom-control-label" for="customSwitch1">Toggle Financial</label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" onchange="this.form.submit()" class="custom-control-input" id="customSwitch2" name="enable_technical" value="1" checked>
                                    <label class="custom-control-label" for="customSwitch2">Toggle Technical</label>
                                </div>

                            </form>
                        </div>

                        <div class="card card-body">
                            <!-- Icon Cards-->
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <!-- small box -->
                                    <a class="small-box bg-primary" href="payments.php?dtf=2024-01-01&dtt=2024-12-31">
                                        <div class="inner">
                                            <h3>$106,672.00</h3>
                                            <p>Income</p>
                                            <hr>
                                            <small>Receivables: $5.00</h3></small>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-hand-holding-usd"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <!-- small box -->
                                    <a class="small-box bg-danger" href="expenses.php?dtf=2024-01-01&dtt=2024-12-31">
                                        <div class="inner">
                                            <h3>$449.00</h3>
                                            <p>Expenses</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <!-- small box -->
                                    <a class="small-box bg-success" href="report_profit_loss.php">
                                        <div class="inner">
                                            <h3>$106,223.00</h3>
                                            <p>Profit</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-balance-scale"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <!-- small box -->
                                    <a class="small-box bg-info" href="report_recurring_by_client.php">
                                        <div class="inner">
                                            <h3>$0.00</h3>
                                            <p>Monthly Recurring Income</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-sync-alt"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <!-- small box -->
                                    <a class="small-box bg-pink" href="report_expense_by_vendor.php">
                                        <div class="inner">
                                            <h3>$0.00</h3>
                                            <p>Monthly Recurring Expense</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-clock"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->


                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <!-- small box -->
                                    <a class="small-box bg-secondary" href="report_tickets_unbilled.php">
                                        <div class="inner">
                                            <h3>0</h3>
                                            <p>Unbilled Ticket</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-ticket-alt"></i>
                                        </div>
                                    </a>
                                </div>


                                <div class="col-lg-4 col-6">
                                    <!-- small box -->
                                    <a class="small-box bg-secondary" href="clients.php?dtf=2024-01-01&dtt=2024-12-31">
                                        <div class="inner">
                                            <h3>2</h3>
                                            <p>New Clients</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-6">
                                    <!-- small box -->
                                    <a class="small-box bg-secondary" href="vendors.php?dtf=2024-01-01&dtt=2024-12-31">
                                        <div class="inner">
                                            <h3>1</h3>
                                            <p>New Vendors</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-building"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <!-- small box -->
                                    <a class="small-box bg-secondary" href="trips.php?dtf=2024-01-01&dtt=2024-12-31">
                                        <div class="inner">
                                            <h3>0.00</h3>
                                            <p>Miles Traveled</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-route"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-md-12">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-fw fa-chart-area mr-2"></i>Cash Flow
                                            </h3>
                                            <div class="card-tools">
                                                <a href="report_income_summary.php" class="btn btn-tool">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="cashFlow" width="100%" height="20"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-fw fa-chart-pie mr-2"></i>Income by
                                                Category <small>(Top 5)</small></h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="incomeByCategoryPieChart" width="100%" height="60"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fa fa-fw fa-shopping-cart mr-2"></i>Expenses by Category
                                                <small>(Top 5)</small>
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="expenseByCategoryPieChart" width="100%" height="60"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fa fa-fw fa-building mr-2"></i>Expenses by
                                                Vendor <small>(Top 5)</small></h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="expenseByVendorPieChart" width="100%" height="60"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fa fa-fw fa-piggy-bank mr-2"></i>Account
                                                Balances</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Cash</td>
                                                        <td class="text-right">$106,223.00</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- .col -->
                                <div class="col-md-4">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-fw fa-credit-card mr-2"></i>Latest
                                                Income</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Customer</th>
                                                        <th>Invoice</th>
                                                        <th class="text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>2024-02-27</td>
                                                        <td>test</td>
                                                        <td>INV-13</td>
                                                        <td class="text-right">$20.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2024-02-27</td>
                                                        <td>test</td>
                                                        <td>INV-10</td>
                                                        <td class="text-right">$30.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2024-02-27</td>
                                                        <td>test</td>
                                                        <td>INV-10</td>
                                                        <td class="text-right">$15.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2024-02-27</td>
                                                        <td>test</td>
                                                        <td>INV-12</td>
                                                        <td class="text-right">$45.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2024-02-27</td>
                                                        <td>test</td>
                                                        <td>INV-9</td>
                                                        <td class="text-right">$5.00</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- .col -->
                                <div class="col-md-4">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-fw fa-shopping-cart mr-2"></i>Latest
                                                Expenses</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Vendor</th>
                                                        <th>Category</th>
                                                        <th class="text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>2024-03-15</td>
                                                        <td>test</td>
                                                        <td>Office Supplies</td>
                                                        <td class="text-right">$160.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2024-03-06</td>
                                                        <td>test</td>
                                                        <td>Office Supplies</td>
                                                        <td class="text-right">$15.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2024-03-07</td>
                                                        <td>test</td>
                                                        <td>Advertising</td>
                                                        <td class="text-right">$3.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2024-03-06</td>
                                                        <td>test</td>
                                                        <td>Advertising</td>
                                                        <td class="text-right">$10.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2024-03-06</td>
                                                        <td>test</td>
                                                        <td>Office Supplies</td>
                                                        <td class="text-right">$16.00</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- .col -->
                                <div class="col-md-12">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-fw fa-route mr-2"></i>Trip Flow</h3>
                                            <div class="card-tools">
                                                <a href="trips.php" class="btn btn-tool">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="tripFlow" width="100%" height="20"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div> <!--card -->


                        <!-- Technical Dashboard -->


                        <div class="card card-body">
                            <!-- Icon Cards-->
                            <div class="row">

                                <div class="col-lg-4 col-6">
                                    <!-- small box -->
                                    <a class="small-box bg-secondary" href="clients.php?date_from=2024-01-01&date_to=2024-12-31">
                                        <div class="inner">
                                            <h3>2</h3>
                                            <p>New Clients</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-6">
                                    <a class="small-box bg-success">
                                        <div class="inner">
                                            <h3>2</h3>
                                            <p>New Contacts</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-6">
                                    <a class="small-box bg-info" href="/report_all_assets_by_client.php">
                                        <div class="inner">
                                            <h3>23</h3>
                                            <p>New Assets</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-desktop"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-6">
                                    <a class="small-box bg-danger" href="tickets.php">
                                        <div class="inner">
                                            <h3>8</h3>
                                            <p>Active Tickets</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-ticket-alt"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-6">
                                    <a class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>0</h3>
                                            <p>Expiring Domains</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-globe"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                                <div class="col-lg-4 col-6">
                                    <a class="small-box bg-primary">
                                        <div class="inner">
                                            <h3>0</h3>
                                            <p>Expiring Certificates</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-lock"></i>
                                        </div>
                                    </a>
                                </div>
                                <!-- ./col -->

                            </div> <!-- rows -->

                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-dark mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fa fa-fw fa-life-ring mr-2"></i>Your Open
                                                Tickets</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive-sm">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Number</th>
                                                        <th>Subject</th>
                                                        <th>Client</th>
                                                        <th>Contact</th>
                                                        <th>Priority</th>
                                                        <th>Status</th>
                                                        <th>Last Response</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr class="">
                                                        <td><a class="text-dark" href="ticket.php?ticket_id=6">TCK-6</a>
                                                        </td>
                                                        <td>
                                                            <a href="ticket.php?ticket_id=6">test</a>
                                                        </td>
                                                        <td>
                                                            <a href="client_tickets.php?client_id=1"><strong>test</strong></a>
                                                        </td>
                                                        <td><a href='client_contact_details.php?client_id=1&contact_id=1'>test</a>
                                                        </td>
                                                        <td><span class='p-2 badge badge-pill badge-info'>Low</span>
                                                        </td>
                                                        <td><span class='p-2 badge badge-pill badge-secondary'>Scheduled</span>
                                                        </td>
                                                        <td>3 weeks ago</td>
                                                    </tr>


                                                    <tr class="">
                                                        <td><a class="text-dark" href="ticket.php?ticket_id=3">TCK-3</a>
                                                        </td>
                                                        <td>
                                                            <a href="ticket.php?ticket_id=3">calendar test</a>
                                                        </td>
                                                        <td>
                                                            <a href="client_tickets.php?client_id=1"><strong>test</strong></a>
                                                        </td>
                                                        <td>-</td>
                                                        <td><span class='p-2 badge badge-pill badge-info'>Low</span>
                                                        </td>
                                                        <td><span class='p-2 badge badge-pill badge-secondary'>Scheduled</span>
                                                        </td>
                                                        <td>1 month ago</td>
                                                    </tr>


                                                    <tr class="">
                                                        <td><a class="text-dark" href="ticket.php?ticket_id=1">TCK-1</a>
                                                        </td>
                                                        <td>
                                                            <a href="ticket.php?ticket_id=1">test</a>
                                                        </td>
                                                        <td>
                                                            <a href="client_tickets.php?client_id=1"><strong>test</strong></a>
                                                        </td>
                                                        <td><a href='client_contact_details.php?client_id=1&contact_id=1'>test</a>
                                                        </td>
                                                        <td><span class='p-2 badge badge-pill badge-info'>Low</span>
                                                        </td>
                                                        <td><span class='p-2 badge badge-pill badge-warning'>Open</span>
                                                        </td>
                                                        <td>1 month ago</td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div> <!-- Card -->





                        <!-- End Tech Dashboard -->

                        <div class="modal fade" id="confirmationModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmationModalLabel">Confirm</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary" id="confirmSubmitBtn">Yes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom js-->
    <script src="js/header_timers.js"></script>
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/chart.js/Chart.min.js"></script>
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src='plugins/daterangepicker/daterangepicker.js'></script>
    <script src='plugins/select2/js/select2.min.js'></script>
    <script src='plugins/inputmask/jquery.inputmask.min.js'></script>
    <script src="plugins/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="plugins/Show-Hide-Passwords-Bootstrap-4/bootstrap-show-password.min.js"></script>
    <script src="plugins/clipboardjs/clipboard.min.js"></script>
    <script src="js/keepalive.js"></script>


    <!-- Global Vendor -->
    <script src="dist/vendor/jquery/dist/jquery.min.js"></script>
    <script src="dist/vendor/jquery-migrate/jquery-migrate.min.js"></script>
    <script src="dist/vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="dist/vendor/bootstrap/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="dist/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="dist/vendor/chart.js/dist/Chart.min.js"></script>

    <!-- Initialization  -->
    <script src="dist/js/sidebar-nav.js"></script>
    <script src="dist/js/main.js"></script>
    <script src="dist/js/dashboard-page-scripts.js"></script>
</body>

</html>


<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Area Chart Example
    var ctx = document.getElementById("cashFlow");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                    label: "Income",
                    fill: false,
                    borderColor: "#007bff",
                    pointBackgroundColor: "#007bff",
                    pointBorderColor: "#007bff",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "#007bff",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [
                        0,
                        106672,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,

                    ],
                },
                {
                    label: "LY Income",
                    fill: false,
                    borderColor: "#9932CC",
                    pointBackgroundColor: "#9932CC",
                    pointBorderColor: "#9932CC",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "#9932CC",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,

                    ],
                },
                {
                    label: "Projected",
                    fill: false,
                    borderColor: "black",
                    pointBackgroundColor: "black",
                    pointBorderColor: "black",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "black",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [
                        0,
                        0,
                        106677,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,

                    ],
                },
                {
                    label: "Expense",
                    lineTension: 0.3,
                    fill: false,
                    borderColor: "#dc3545",
                    pointBackgroundColor: "#dc3545",
                    pointBorderColor: "#dc3545",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "#dc3545",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [
                        0,
                        0,
                        449,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,

                    ],
                }
            ],
        },
        options: {
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 12
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 107000,
                        maxTicksLimit: 5
                    },
                    gridLines: {
                        color: "rgba(0, 0, 0, .125)",
                    }
                }],
            },
            legend: {
                display: true
            }
        }
    });

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Area Chart Example
    var ctx = document.getElementById("tripFlow");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Trip",
                lineTension: 0.3,
                backgroundColor: "red",
                borderColor: "darkred",
                pointRadius: 5,
                pointBackgroundColor: "red",
                pointBorderColor: "red",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "darkred",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: [
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,

                ],
            }],
        },
        options: {
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 12
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 1000,
                        maxTicksLimit: 5
                    },
                    gridLines: {
                        color: "rgba(0, 0, 0, .125)",
                    }
                }],
            },
            legend: {
                display: false
            }
        }
    });

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Pie Chart Example
    var ctx = document.getElementById("incomeByCategoryPieChart");
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                "Service",
            ],
            datasets: [{
                data: [
                    106517,
                ],
                backgroundColor: [
                    "blue",
                ],
            }],
        },
        options: {
            legend: {
                display: true,
                position: 'right'
            }
        }
    });

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Pie Chart Example
    var ctx = document.getElementById("expenseByCategoryPieChart");
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                "Advertising", "Office Supplies",
            ],
            datasets: [{
                data: [
                    258, 191,
                ],
                backgroundColor: [
                    "green", "blue",
                ],
            }],
        },
        options: {
            legend: {
                display: true,
                position: 'right'
            }
        }
    });

    // Pie Chart Example
    var ctx = document.getElementById("expenseByVendorPieChart");
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                "test",
            ],
            datasets: [{
                data: [
                    449,
                ],
                backgroundColor: [
                    '#09126d',
                ],
            }],
        },
        options: {
            legend: {
                display: true,
                position: 'right'
            }
        }
    });
</script>