<!-- Header (Topbar) -->
<header class="u-header">
    <div class="u-header-left">
        <a class="u-header-logo" href="/pages/<?php echo $config_start_page; ?>">
            <img class="u-logo-desktop" src="/includes/dist/img/logo.png" width="160" alt="Stream Dashboard">
            <img class="img-fluid u-logo-mobile" src="/includes/dist/img/logo-mobile.png" width="50" alt="Stream Dashboard">
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
                        <input class="form-control u-header-search__field" type="search" placeholder="Type to search…" name='query' value="
                        <?php if (isset($_GET['query'])) {
                            echo nullable_htmlentities($_GET['query']);
                        } ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="u-header-right">
        <!-- Activities -->
        <div class="dropdown mr-4">
            <a class="link-muted" href="#!" role="button" id="dropdownMenuLinkActivities" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                <span class="h3">
                    <i class="far fa-envelope"></i>
                </span>
                <?php if(false) { ?>
                    <span class="u-indicator u-indicator-top-right u-indicator--xxs bg-secondary"></span>
                <?php } ?>
            </a>

            <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-4" aria-labelledby="dropdownMenuLinkActivities" style="width: 360px;">
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
                                    <img class="u-avatar--sm rounded-circle mr-3" src="/includes/dist/img/avatars/img1.jpg" alt="Image description">

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
                                    <img class="u-avatar--sm rounded-circle mr-3" src="/includes/dist/img/avatars/img2.jpg" alt="Image description">

                                    <div class="media-body">
                                        <div class="d-flex align-items-center">
                                            <h4 class="mb-1">Jane Ortega</h4>
                                            <small class="text-muted ml-auto">18 Jan 2018</small>
                                        </div>

                                        <p class="text-truncate mb-0" style="max-width: 250px;">
                                            <span class="text-primary">@Bruce</span> advertising your project is not good idea.
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <!-- End Activity -->

                            <!-- Activity -->
                            <a class="list-group-item list-group-item-action" href="#">
                                <div class="media align-items-center">
                                    <img class="u-avatar--sm rounded-circle mr-3" src="/includes/dist/img/avatars/user-unknown.jpg" alt="Image description">

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
                                    <img class="u-avatar--sm rounded-circle mr-3" src="/includes/dist/img/avatars/img4.jpg" alt="Image description">

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
            <a class="link-muted" href="#!" role="button" id="dropdownMenuLinkNotifications" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                <span class="h3">
                    <i class="far fa-bell"></i>
                </span>
                <?php if($num_notifications > 0) { ?>
                    <span class="u-indicator u-indicator-top-right u-indicator--xxs bg-danger"></span>
                <?php } ?>
            </a>

            <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-4" aria-labelledby="dropdownMenuLinkNotifications" style="width: 360px;">
                <div class="card">
                    <div class="card-header d-flex align-items-center py-3">
                        <h2 class="h4 card-header-title">Notifications</h2>
                        <a class="ml-auto" href="/post.php?dismiss_all_notifications">Clear all</a>
                    </div>

                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <!-- Notification -->
                            <?php
                            $sql_notifications = mysqli_query($mysqli, "SELECT * FROM notifications 
                                LEFT JOIN clients ON notification_client_id = client_id 
                                WHERE notification_dismissed_at IS NULL 
                                AND (notification_user_id = $session_user_id OR notification_user_id = 0) 
                                ORDER BY notification_id DESC LIMIT 5"
                            );

                            while ($row = mysqli_fetch_array($sql_notifications)) {
                                $notification_id = intval($row['notification_id']);
                                $notification_type = nullable_htmlentities($row['notification_type']);
                                $notification = nullable_htmlentities($row['notification']);
                                $notification_action = nullable_htmlentities($row['notification_action']);
                                $notification_timestamp = date('M d g:ia',strtotime($row['notification_timestamp']));
                                $notification_client_id = intval($row['notification_client_id']);
                                if(empty($notification_action)) { $notification_action = "#"; }
                            ?>

                                <a class="list-group-item list-group-item-action" href="<?php echo $notification_action; ?>">
                                    <div class="media align-items-center">
                                        <div class="u-icon u-icon--sm rounded-circle bg-danger text-white mr-3">
                                            <i class="fab fa-dribbble"></i>
                                        </div>

                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-1"><?php echo $notification_type; ?></h4>
                                                <small class="text-muted ml-auto"><?php echo $notification_timestamp; ?></small>
                                            </div>

                                            <p class="text-truncate mb-0" style="max-width: 250px;">
                                                <?php echo $notification; ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            <?php } ?>
                            <!-- End Notification -->
                        </div>
                    </div>

                    <div class="card-footer py-3">
                        <a class="btn btn-block btn-outline-primary" href="/pages/notifications_dismissed.php">View all notifications</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Notifications -->

        <!-- User Profile -->
        <div class="dropdown ml-2">
            <a class="link-muted d-flex align-items-center" href="#!" role="button" id="dropdownMenuLinkUser" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                <img class="u-avatar--xs img-fluid rounded-circle mr-2" src="<?php echo "/uploads/users/$session_user_id/" . nullable_htmlentities($session_avatar); ?>" alt="User Profile">
                <span class="text-dark d-none d-sm-inline-block">
                    <?php echo $session_name ?> <small class="fa fa-angle-down text-muted ml-1"></small>
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3" aria-labelledby="dropdownMenuLinkUser" style="width: 260px;">
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
                                <a class="d-flex align-items-center link-dark" href="/pages/user/user_details.php">
                                    <span class="h3 mb-0"><i class="far fa-user-circle text-muted mr-3"></i></span> View Profile
                                </a>
                            </li>
                            <li class="mb-4">
                                <a class="d-flex align-items-center link-dark" href="/pages/user/user_preferences.php">
                                    <span class="h3 mb-0"><i class="far fa-list-alt text-muted mr-3"></i></span> Settings
                                </a>
                            </li>
                            <li class="mb-4">
                                <a class="d-flex align-items-center link-dark" href="#!">
                                    <span class="h3 mb-0"><i class="far fa-laugh-wink text-muted mr-3"></i></span> Invite your friends
                                </a>
                            </li>
                            <li>
                                <a class="d-flex align-items-center link-dark" href="/post.php?logout">
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
<!-- End Header (Topbar) -->

<main class="u-main" role="main">
