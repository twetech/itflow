
<!-- Details -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "user_details.php") {
            echo "active";
        } ?>" href="/pages/user/user_details.php">
        <i class="fa fa-cubes u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">User Details</span>
    </a>
</li>
<!-- End Details-->

<hr>

<!-- Security -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "user_security.php") {
            echo "active";
        } ?>" href="/pages/user/user_security.php">
        <i class="fa fa-shield-alt u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Security</span>
    </a>
</li>
<!-- End Security -->

<hr>

<!-- Preferences -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "user_preferences.php") {
            echo "active";
        } ?>" href="/pages/user/user_preferences.php">
        <i class="fa fa-cogs u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Preferences</span>
    </a>
</li>
<!-- End Preferences -->

<hr>

<!-- Activity -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "user_activity.php") {
            echo "active";
        } ?>" href="/pages/user/user_activity.php">
        <i class="fa fa-clock u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Activity</span>
    </a>
</li>
<!-- End Activity -->

