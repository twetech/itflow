<!-- Main Sidebar Container -->
<aside id="sidebar" class="u-sidebar">
    <div class="u-sidebar-inner">
        <header class="u-sidebar-header">
            <a class="u-sidebar-logo" href="/index.html">
                <img class="img-fluid" src="/includes/dist/img/logo.png" width="124" alt="<?= $config_app_name; ?>">
            </a>
        </header>
        <nav class="u-sidebar-nav">
            <ul class="u-sidebar-nav-menu u-sidebar-nav-menu--top-level">
                <!-- Dashboard -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "dashboard.php") { echo "active"; } ?>" href="/pages/dashboard.php">
                        <i class="fa fa-home u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">Dashboard</span>
                    </a>
                </li>
                <!-- End Dashboard -->

                <hr>
