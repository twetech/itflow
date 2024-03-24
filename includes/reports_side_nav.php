<?php  if ($session_user_role == 1 || $session_user_role == 3 && $config_module_enable_accounting == 1) { ?>
    <!-- Financial -->
    <li class="u-sidebar-nav-menu__item <?php if (
        basename($_SERVER["PHP_SELF"]) == "report_income_summary.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_income_by_client.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_recurring_by_client.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_clients_with_balance.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_expense_summary.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_expense_by_vendor.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_budget.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_tax_summary.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_profit_loss.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_balance_sheet.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_collections.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_tickets_unbilled.php"
    ) { echo "u-sidebar-nav--opened"; } ?>">

        <a class="u-sidebar-nav-menu__link" href="#!" data-target='#financialSideNav'>
            <i class="fa fa-dollar-sign u-sidebar-nav-menu__item-icon"></i>
            <span class="u-sidebar-nav-menu__item-title">Financial</span>
            <span class="u-sidebar-nav-menu__item-arrow">
                <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
            </span>
        </a>

        <ul id="financialSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
            <!-- Income -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_income_summary.php") { echo "active"; } ?>" href="report_income_summary.php">
                    <i class="fa fa-circle u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Income</span>
                </a>
            </li>
            <!-- End Income -->

            <!-- Income By Client -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_income_by_client.php") { echo "active"; } ?>" href="report_income_by_client.php">
                    <i class="fa fa-user u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Income By Client</span>
                </a>
            </li>
            <!-- End Income By Client -->

            <!-- Recurring Income By Client -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_recurring_by_client.php") { echo "active"; } ?>" href="report_recurring_by_client.php">
                    <i class="fa fa-sync u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Recurring Income By Client</span>
                </a>
            </li>
            <!-- End Recurring Income By Client -->

            <!-- Clients with a Balance -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_clients_with_balance.php") { echo "active"; } ?>" href="report_clients_with_balance.php">
                    <i class="fa fa-exclamation-triangle u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Clients with a Balance</span>
                </a>
            </li>
            <!-- End Clients with a Balance -->

            <!-- Expense -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_expense_summary.php") { echo "active"; } ?>" href="report_expense_summary.php">
                    <i class="fa fa-credit-card u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Expense</span>
                </a>
            </li>
            <!-- End Expense -->

            <!-- Expense By Vendor -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_expense_by_vendor.php") { echo "active"; } ?>" href="report_expense_by_vendor.php">
                    <i class="fa fa-building u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Expense By Vendor</span>
                </a>
            </li>
            <!-- End Expense By Vendor -->

            <!-- Budget -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_budget.php") { echo "active"; } ?>" href="report_budget.php">
                    <i class="fa fa-list
                    u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Budget</span>
                </a>
            </li>
            <!-- End Budget -->

            <!-- Tax Summary -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_tax_summary.php") { echo "active"; } ?>" href="report_tax_summary.php">
                    <i class="fa fa-percent u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Tax Summary</span>
                </a>
            </li>
            <!-- End Tax Summary -->

            <!-- Profit & Loss -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_profit_loss.php") { echo "active"; } ?>" href="report_profit_loss.php">
                    <i class="fa fa-file
                    u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Profit & Loss</span>
                </a>
            </li>
            <!-- End Profit & Loss -->

            <!-- Balance Sheet -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_balance_sheet.php") { echo "active"; } ?>" href="report_balance_sheet.php">
                    <i class="fa fa-balance-scale u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Balance Sheet</span>
                </a>
            </li>
            <!-- End Balance Sheet -->

            <!-- Collections -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_collections.php") { echo "active"; } ?>" href="report_collections.php">
                    <i class="fa fa-search-dollar u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Collections</span>
                </a>
            </li>
            <!-- End Collections -->

            <!-- Unbilled Tickets -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_tickets_unbilled.php") { echo "active"; } ?>" href="report_tickets_unbilled.php">
                    <i class="fa fa-life
                    u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Unbilled Tickets</span>
                </a>
            </li>
            <!-- End Unbilled Tickets -->
        </ul>
    </li>

    <hr>
<?php } // End support IF statement ?>

<?php  if ($session_user_role == 2 || $session_user_role == 3) { ?>
    <!-- Technical -->
    <li class="u-sidebar-nav-menu__item <?php if (
        basename($_SERVER["PHP_SELF"]) == "report_ticket_summary.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_ticket_by_client.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_password_rotation.php" ||
        basename($_SERVER["PHP_SELF"]) == "report_all_assets_by_client.php"
    ) { echo "u-sidebar-nav--opened"; } ?>">
        <a class="u-sidebar-nav-menu__link" href="#!" data-target='#supportSideNav'>
            <i class="fa fa-question-circle u-sidebar-nav-menu__item-icon"></i>
            <span class="u-sidebar-nav-menu__item-title">Technical</span>
            <span class="u-sidebar-nav-menu__item-arrow">
                <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
            </span>
        </a>

        <ul id="supportSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
            <!-- Tickets -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_ticket_summary.php") { echo "active"; } ?>" href="report_ticket_summary.php">
                    <i class="fa fa-life u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Tickets</span>
                </a>
            </li>
            <!-- End Tickets -->

            <!-- Tickets by Client -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_ticket_by_client.php") { echo "active"; } ?>" href="report_ticket_by_client.php">
                    <i class="fa fa-life
                    u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Tickets by Client</span>
                </a>
            </li>
            <!-- End Tickets by Client -->

            <!-- Password rotation -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_password_rotation.php") { echo "active"; } ?>" href="report_password_rotation.php">
                    <i class="fa fa-life
                    u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">Password rotation</span>
                </a>
            </li>
            <!-- End Password rotation -->

            <!-- All Assets -->
            <li class="u-sidebar-nav-menu__item">
                <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_all_assets_by_client.php") { echo "active"; } ?>" href="report_all_assets_by_client.php">
                    <i class="fa fa-life
                    u-sidebar-nav-menu__item-icon"></i>
                    <span class="u-sidebar-nav-menu__item-title">All Assets</span>
                </a>
            </li>
            <!-- End All Assets -->
        </ul>
    </li>
<?php } // End technical IF statement ?>
