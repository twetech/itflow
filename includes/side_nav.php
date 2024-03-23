
                <!-- Dashboard -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "dashboard.php") { echo "active"; } ?>" href="dashboard.php">
                        <i class="fa fa-cubes u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">Dashboard</span>
                    </a>
                </li>
                <!-- End Dashboard -->

                <hr>

                <!-- Support -->
                <li class="u-sidebar-nav-menu__item <?php if (basename($_SERVER["PHP_SELF"]) == "clients.php" || basename($_SERVER["PHP_SELF"]) == "tickets.php" || basename($_SERVER["PHP_SELF"]) == "ticket.php" || basename($_SERVER["PHP_SELF"]) == "recurring_tickets.php" || basename($_SERVER["PHP_SELF"]) == "calendar_events.php") { echo "u-sidebar-nav--opened"; } ?>">
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
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "clients.php") { echo "active"; } ?>" href="clients.php">
                                <i class="fa fa-users u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Clients</span>
                            </a>
                        </li>

                        <!-- Tickets -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "tickets.php" || basename($_SERVER["PHP_SELF"]) == "ticket.php" || basename($_SERVER["PHP_SELF"]) == "recurring_tickets.php") { echo "active"; } ?>" href="tickets.php">
                                <i class="fa fa-life-ring u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Tickets</span>
                            </a>
                        </li>

                        <!-- Calendar -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "calendar_events.php") { echo "active"; } ?>" href="calendar_events.php">
                                <i class="fa fa-calendar-alt u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Calendar</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <hr>

                <!-- Sales -->
                <li class="u-sidebar-nav-menu__item <?php if (basename($_SERVER["PHP_SELF"]) == "quotes.php" || basename($_SERVER["PHP_SELF"]) == "quote.php" || basename($_SERVER["PHP_SELF"]) == "invoices.php" || basename($_SERVER["PHP_SELF"]) == "invoice.php" || basename($_SERVER["PHP_SELF"]) == "recurring_invoices.php" || basename($_SERVER["PHP_SELF"]) == "recurring_invoice.php" || basename($_SERVER["PHP_SELF"]) == "products.php") { echo "u-sidebar-nav--opened"; } ?>">
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
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "quotes.php" || basename($_SERVER["PHP_SELF"]) == "quote.php") { echo "active"; } ?>" href="quotes.php">
                                <i class="fa fa-comment-dollar u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Quotes</span>
                            </a>
                        </li>

                        <!-- Invoices -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "invoices.php" || basename($_SERVER["PHP_SELF"]) == "invoice.php" || basename($_SERVER["PHP_SELF"]) == "recurring_invoices.php" || basename($_SERVER["PHP_SELF"]) == "recurring_invoice.php") { echo "active"; } ?>" href="invoices.php">
                                <i class="fa fa-file-invoice u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Invoices</span>
                            </a>
                        </li>

                        <!-- Products -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "products.php") { echo "active"; } ?>" href="products.php">
                                <i class="fa fa-box-open u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Products</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <hr>

                <!-- Accounting -->
                <li class="u-sidebar-nav-menu__item <?php if (basename($_SERVER["PHP_SELF"]) == "payments.php" || basename($_SERVER["PHP_SELF"]) == "vendors.php" || basename($_SERVER["PHP_SELF"]) == "expenses.php" || basename($_SERVER["PHP_SELF"]) == "recurring_expenses.php" || basename($_SERVER["PHP_SELF"]) == "accounts.php" || basename($_SERVER["PHP_SELF"]) == "transfers.php" || basename($_SERVER["PHP_SELF"]) == "budget.php" || basename($_SERVER["PHP_SELF"]) == "trips.php") { echo "u-sidebar-nav--opened"; } ?>">
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
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "payments.php") { echo "active"; } ?>" href="payments.php">
                                <i class="fa fa-credit-card u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Payments</span>
                            </a>
                        </li>

                        <!-- Vendors -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "vendors.php") { echo "active"; } ?>" href="vendors.php">
                                <i class="fa fa-building u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Vendors</span>
                            </a>
                        </li>

                        <!-- Expenses -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "expenses.php" || basename($_SERVER["PHP_SELF"]) == "recurring_expenses.php") { echo "active"; } ?>" href="expenses.php">
                                <i class="fa fa-shopping-cart u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Expenses</span>
                            </a>
                        </li>

                        <!-- Accounts -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "accounts.php") { echo "active"; } ?>" href="accounts.php">
                                <i class="fa fa-piggy-bank u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Accounts</span>
                            </a>
                        </li>

                        <!-- Transfers -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "transfers.php") { echo "active"; } ?>" href="transfers.php">
                                <i class="fa fa-exchange-alt u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Transfers</span>
                            </a>
                        </li>

                        <!-- Budget -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "budget.php") { echo "active"; } ?>" href="budget.php">
                                <i class="fa fa-balance-scale u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Budget</span>
                            </a>
                        </li>

                        <!-- Trips -->
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "trips.php") { echo "active"; } ?>" href="trips.php">
                                <i class="fa fa-route u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Trips</span>
                            </a>
                        </li>
                    </ul>
                </li>
                    
                <hr>

                <!-- Reports -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link <?php if (basename($_SERVER["PHP_SELF"]) == "report_income_summary.php") { echo "active"; } ?>" href="report_income_summary.php">
                        <i class="fa fa-chart-line u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">Reports</span>
                    </a>
                </li>

                <hr>

                <!-- Admin -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="admin_users.php">
                        <i class="fa fa-user-shield u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">Admin</span>
                    </a>
                </li>

                <hr>

                <!-- Settings -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="settings_company.php">
                        <i class="fa fa-cogs
                        u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">Settings</span>
                    </a>
                </li>
