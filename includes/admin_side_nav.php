<!-- Access -->
<li class="u-sidebar-nav-menu__item <?php if (basename($_SERVER["PHP_SELF"]) == "admin_users.php" || basename($_SERVER["PHP_SELF"]) == "api_keys.php") { echo "u-sidebar-nav--opened"; } ?>">
    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#accessSideNav'>
        <i class="fa fa-question-circle u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Access</span>
        <span class="u-sidebar-nav-menu__item-arrow">
            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
        </span>
    </a>

    <ul id="accessSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
        <!-- Clients -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_users.php") { echo "active"; } ?>" href="/pages/admin/admin_users.php">
                <i class="fa fa-users u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Users</span>
            </a>
        </li>


        <!-- API Keys -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_api_keys.php") { echo "active"; } ?>" href="/pages/admin/admin_api_keys.php">
                <i class="fa fa-key u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">API Keys</span>
            </a>

        </li>
    </ul>
</li>

<hr>

<!-- Tags & Categories --> 
<li class="u-sidebar-nav-menu__item <?php if (basename($_SERVER["PHP_SELF"]) == "admin_tags.php" || basename($_SERVER["PHP_SELF"]) == "admin_categories.php" || basename($_SERVER["PHP_SELF"]) == "admin_taxes.php" || basename($_SERVER["PHP_SELF"]) == "admin_account_types.php" || basename($_SERVER["PHP_SELF"]) == "admin_inventory_locations.php") { echo "u-sidebar-nav--opened"; } ?>">
    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#tagsSideNav'>
        <i class="fa fa-question-circle u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Tags & Categories</span>
        <span class="u-sidebar-nav-menu__item-arrow">
            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
        </span>
    </a>

    <ul id="tagsSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
        <!-- Tags -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_tags.php") { echo "active"; } ?>" href="/pages/admin/admin_tags.php">
                <i class="fa fa-tags u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Tags</span>
            </a>
        </li>

        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_categories.php") { echo "active"; } ?>" href="/pages/admin/admin_categories.php">
                <i class="fa fa-list u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Categories</span>
            </a>
        </li>

        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_taxes.php") { echo "active"; } ?>" href="/pages/admin/admin_taxes.php">
                <i class="fa fa-balance-scale u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Taxes</span>
            </a>
        </li>

        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_account_types.php") { echo "active"; } ?>" href="/pages/admin/admin_account_types.php">
                <i class="fa fa-money-bill-wave u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Account Types</span>
            </a>
        </li>

        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_inventory_locations.php") { echo "active"; } ?>" href="/pages/admin/admin_inventory_locations.php">
                <i class="fa fa-map-marker-alt u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Inventory Locations</span>
            </a>
        </li>
    </ul>
</li>

<hr>

<!-- Templates -->
<li class="u-sidebar-nav-menu__item <?php if (basename($_SERVER["PHP_SELF"]) == "admin_vendor_templates.php" || basename($_SERVER["PHP_SELF"]) == "admin_software_templates.php" || basename($_SERVER["PHP_SELF"]) == "admin_document_templates.php") { echo "u-sidebar-nav--opened"; } ?>">
    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#templatesSideNav'>
        <i class="fa fa-question-circle u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Templates</span>
        <span class="u-sidebar-nav-menu__item-arrow">
            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
        </span>
    </a>

    <ul id="templatesSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
        <!-- Vendor Templates -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_vendor_templates.php") { echo "active"; } ?>" href="/pages/admin/admin_vendor_templates.php">
                <i class="fa fa-building u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Vendor Templates</span>
            </a>
        </li>

        <!-- License Templates -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_software_templates.php") { echo "active"; } ?>" href="/pages/admin/admin_software_templates.php">
                <i class="fa fa-rocket u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">License Templates</span>
            </a>
        </li>

        <!-- Document Templates -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_document_templates.php") { echo "active"; } ?>" href="/pages/admin/admin_document_templates.php">
                <i class="fa fa-file u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Document Templates</span>
            </a>
        </li>
    </ul>
</li>

<hr>

<!-- Maintenance -->
<li class="u-sidebar-nav-menu__item <?php if (basename($_SERVER["PHP_SELF"]) == "admin_mail_queue.php" || basename($_SERVER["PHP_SELF"]) == "admin_logs.php" || basename($_SERVER["PHP_SELF"]) == "admin_backup.php" || basename($_SERVER["PHP_SELF"]) == "admin_debug.php" || basename($_SERVER["PHP_SELF"]) == "admin_update.php" || basename($_SERVER["PHP_SELF"]) == "admin_bulk_mail.php") { echo "u-sidebar-nav--opened"; } ?>">
    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#maintenanceSideNav'>
        <i class="fa fa-question-circle u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Maintenance</span>
        <span class="u-sidebar-nav-menu__item-arrow">
            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
        </span>
    </a>

    <ul id="maintenanceSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
        <!-- Mail Queue -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_mail_queue.php") { echo "active"; } ?>" href="/pages/admin/admin_mail_queue.php">
                <i class="fa fa-envelope u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Mail Queue</span>
            </a>
        </li>

        <!-- Audit Logs -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_logs.php") { echo "active"; } ?>" href="/pages/admin/admin_logs.php">
                <i class="fa fa-history u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Audit Logs</span>
            </a>
        </li>

        <!-- Backup -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_backup.php") { echo "active"; } ?>" href="/pages/admin/admin_backup.php">
                <i class="fa fa-cloud-upload-alt u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Backup</span>
            </a>
        </li>

        <!-- Debug -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_debug.php") { echo "active"; } ?>" href="/pages/admin/admin_debug.php">
                <i class="fa fa-bug u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Debug</span>
            </a>
        </li>

        <!-- Update -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_update.php") { echo "active"; } ?>" href="/pages/admin/admin_update.php">
                <i class="fa fa-download u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Update</span>
            </a>
        </li>

        <!-- Bulk Mail -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_bulk_mail.php") { echo "active"; } ?>" href="/pages/admin/admin_bulk_mail.php">
                <i class="fa fa-envelope u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Bulk Mail</span>
            </a>
        </li>
    </ul>
</li>
<!-- End Access -->


<?php 
if ($database == "itflow"){
?>

<hr>

<!-- Tenants -->
<li class="u-sidebar-nav-menu__item <?php if (basename($_SERVER["PHP_SELF"]) == "admin_tenants.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_users.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_api_keys.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_tags.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_categories.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_taxes.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_account_types.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_inventory_locations.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_vendor_templates.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_software_templates.php" || basename($_SERVER["PHP_SELF"]) == "admin_tenant_document_templates.php") { echo "u-sidebar-nav--opened"; } ?>">
    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#tenantsSideNav'>
        <i class="fa fa-question-circle u-sidebar-nav-menu__item-icon"></i>
        <span class="u-sidebar-nav-menu__item-title">Tenants</span>
        <span class="u-sidebar-nav-menu__item-arrow">
            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
        </span>
    </a>

    <ul id="tenantsSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
        <!-- Tenants -->
        <li class="u-sidebar-nav-menu__item">
            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "admin_tenants.php") { echo "active"; } ?>" href="/pages/admin/admin_tenants.php">
                <i class="fa fa-building u-sidebar-nav-menu__item-icon"></i>
                <span class="u-sidebar-nav-menu__item-title">Tenants</span>
            </a>
        </li>
    </ul>
</li>

<?php
}