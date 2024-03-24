

<!-- Company Settings -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_company.php") {
            echo "active";
        } ?>" href="settings_company.php">
        <i class="fa fa-cubes u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Company Details</span>
    </a>
</li>
<!-- End Company Settings -->

<!-- Localization -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_localization.php") {
            echo "active";
        } ?>" href="settings_localization.php">
        <i class="fa fa-globe u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Localization</span>
    </a>
</li>
<!-- End Localization -->

<!-- Theme -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_theme.php") {
            echo "active";
        } ?>" href="settings_theme.php">
        <i class="fa fa-paint-brush u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Theme</span>
    </a>
</li>
<!-- End Theme -->

<!-- Security -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_security.php") {
            echo "active";
        } ?>" href="settings_security.php">
        <i class="fa fa-shield-alt u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Security</span>
    </a>
</li>
<!-- End Security -->

<!-- Mail -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_mail.php") {
            echo "active";
        } ?>" href="settings_mail.php">
        <i class="fa fa-envelope u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Mail</span>
    </a>
</li>
<!-- End Mail -->

<!-- Notifications -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_notifications.php") {
            echo "active";
        } ?>" href="settings_notifications.php">
        <i class="fa fa-bell u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Notifications</span>
    </a>
</li>
<!-- End Notifications -->

<!-- Defaults -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_defaults.php") {
            echo "active";
        } ?>" href="settings_defaults.php">
        <i class="fa fa-cogs
        u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Defaults</span>
    </a>
</li>
<!-- End Defaults -->

<!-- Invoice -->
<?php if ($config_module_enable_accounting) { ?>
    <li class="u-sidebar-nav-menu__item">
        <a class="u-sidebar-nav-menu__link 
            <?php if (basename($_SERVER["PHP_SELF"]) == "settings_invoice.php") {
                echo "active";
            } ?>" href="settings_invoice.php">
            <i class="fa fa-file
            u-sidebar-nav-menu__item-icon"></i>
            <span class="u-sidebar-nav-menu__item-title">Invoice</span>
        </a>
    </li>
<?php } ?>
<!-- End Invoice -->

<!-- Quote -->
<?php if ($config_module_enable_accounting) { ?>
    <li class="u-sidebar-nav-menu__item">
        <a class="u-sidebar-nav-menu__link 
            <?php if (basename($_SERVER["PHP_SELF"]) == "settings_quote.php") {
                echo "active";
            } ?>" href="settings_quote.php">
            <i class="fa fa-comment-dollar
            u-sidebar-nav-menu__item-icon"></i>
            <span class="u-sidebar-nav-menu__item-title">Quote</span>
        </a>
    </li>
<?php } ?>
<!-- End Quote -->

<!-- Online Payment -->
<?php if ($config_module_enable_accounting) { ?>
    <li class="u-sidebar-nav-menu__item">
        <a class="u-sidebar-nav-menu__link 
            <?php if (basename($_SERVER["PHP_SELF"]) == "settings_online_payment.php") {
                echo "active";
            } ?>" href="settings_online_payment.php">
            <i class="fa fa-credit-card
            u-sidebar-nav-menu__item-icon"></i>
            <span class="u-sidebar-nav-menu__item-title">Online Payment</span>
        </a>
    </li>
<?php } ?>
<!-- End Online Payment -->

<!-- Ticket -->
<?php if ($config_module_enable_ticketing) { ?>
    <li class="u-sidebar-nav-menu__item">
        <a class="u-sidebar-nav-menu__link 
            <?php if (basename($_SERVER["PHP_SELF"]) == "settings_ticket.php") {
                echo "active";
            } ?>" href="settings_ticket.php">
            <i class="fa fa-life-ring
            u-sidebar-nav-menu__item-icon"></i>
            <span class="u-sidebar-nav-menu__item-title">Ticket</span>
        </a>
    </li>
<?php } ?>
<!-- End Ticket -->

<!-- AI -->
<li class="u-sidebar-nav-menu__item">
    <li class="u-sidebar-nav-menu__item">
        <a class="u-sidebar-nav-menu__link 
            <?php if (basename($_SERVER["PHP_SELF"]) == "settings_ai.php") {
                echo "active";
            } ?>" href="settings_ai.php">
            <i class="fa fa-robot u-sidebar-nav-menu__item-icon"></i>
            <span class="u-sidebar-nav-menu__item-title">AI</span>
        </a>
    </li>
</li>
<!-- End AI -->

<!-- Integrations -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_integrations.php") {
            echo "active";
        } ?>" href="settings_integrations.php">
        <i class="fa fa-plug
        u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Integrations</span>
    </a>
</li>
<!-- End Integrations -->

<!-- Telemetry -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_telemetry.php") {
            echo "active";
        } ?>" href="settings_telemetry.php">
        <i class="fa fa-satellite-dish
        u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Telemetry</span>
    </a>
</li>
<!-- End Telemetry -->

<!-- Modules -->
<li class="u-sidebar-nav-menu__item">
    <a class="u-sidebar-nav-menu__link 
        <?php if (basename($_SERVER["PHP_SELF"]) == "settings_modules.php") {
            echo "active";
        } ?>" href="settings_modules.php">
        <i class="fa fa-cube
        u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Modules</span>
    </a>
</li>
<!-- End Modules -->

