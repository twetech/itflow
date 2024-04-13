
                <!-- Support -->
                <li class="menu-item <?php if (basename($_SERVER["PHP_SELF"]) == "clients.php" || basename($_SERVER["PHP_SELF"]) == "tickets.php" || basename($_SERVER["PHP_SELF"]) == "ticket.php" || basename($_SERVER["PHP_SELF"]) == "recurring_tickets.php" || basename($_SERVER["PHP_SELF"]) == "calendar_events.php") { echo "u-sidebar-nav--opened"; } ?>">
                    <a class="menu-link" href="#!" data-target='#supportSideNav'>
                        <i class="fa fa-question-circle menu-icon"></i>
                        <span class="menu-item-title">Support</span>
                        <span class="menu-item-arrow">
                            <i class="fa fa-angle-down menu-item-arrow-icon"></i>
                        </span>
                    </a>

                    <ul id="supportSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                        <!-- Clients -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "clients.php") { echo "active"; } ?>" href="clients.php">
                                <i class="fa fa-users menu-icon"></i>
                                <span class="menu-item-title">Clients</span>
                            </a>
                        </li>

                        <!-- Tickets -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "tickets.php" || basename($_SERVER["PHP_SELF"]) == "ticket.php" || basename($_SERVER["PHP_SELF"]) == "recurring_tickets.php") { echo "active"; } ?>" href="tickets.php">
                                <i class="fa fa-life-ring menu-icon"></i>
                                <span class="menu-item-title">Tickets</span>
                            </a>
                        </li>

                        <!-- Calendar -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "calendar_events.php") { echo "active"; } ?>" href="calendar_events.php">
                                <i class="fa fa-calendar-alt menu-icon"></i>
                                <span class="menu-item-title">Calendar</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <hr>

                <!-- Sales -->
                <li class="menu-item <?php if (basename($_SERVER["PHP_SELF"]) == "quotes.php" || basename($_SERVER["PHP_SELF"]) == "quote.php" || basename($_SERVER["PHP_SELF"]) == "invoices.php" || basename($_SERVER["PHP_SELF"]) == "invoice.php" || basename($_SERVER["PHP_SELF"]) == "recurring_invoices.php" || basename($_SERVER["PHP_SELF"]) == "recurring_invoice.php" || basename($_SERVER["PHP_SELF"]) == "products.php") { echo "u-sidebar-nav--opened"; } ?>">
                    <a class="menu-link" href="#!" data-target='#salesSideNav'>
                        <i class="fa fa-chart-line menu-icon"></i>
                        <span class="menu-item-title">Sales</span>
                        <span class="menu-item-arrow">
                            <i class="fa fa-angle-down menu-item-arrow-icon"></i>
                        </span>
                    </a>

                    <ul id="salesSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                        <!-- Quotes -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "quotes.php" || basename($_SERVER["PHP_SELF"]) == "quote.php") { echo "active"; } ?>" href="quotes.php">
                                <i class="fa fa-comment-dollar menu-icon"></i>
                                <span class="menu-item-title">Quotes</span>
                            </a>
                        </li>

                        <!-- Invoices -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "invoices.php" || basename($_SERVER["PHP_SELF"]) == "invoice.php" || basename($_SERVER["PHP_SELF"]) == "recurring_invoices.php" || basename($_SERVER["PHP_SELF"]) == "recurring_invoice.php") { echo "active"; } ?>" href="invoices.php">
                                <i class="fa fa-file-invoice menu-icon"></i>
                                <span class="menu-item-title">Invoices</span>
                            </a>
                        </li>

                        <!-- Products -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "products.php") { echo "active"; } ?>" href="products.php">
                                <i class="fa fa-box-open menu-icon"></i>
                                <span class="menu-item-title">Products</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <hr>

                <!-- Accounting -->
                <li class="menu-item <?php if (basename($_SERVER["PHP_SELF"]) == "payments.php" || basename($_SERVER["PHP_SELF"]) == "vendors.php" || basename($_SERVER["PHP_SELF"]) == "expenses.php" || basename($_SERVER["PHP_SELF"]) == "recurring_expenses.php" || basename($_SERVER["PHP_SELF"]) == "accounts.php" || basename($_SERVER["PHP_SELF"]) == "transfers.php" || basename($_SERVER["PHP_SELF"]) == "budget.php" || basename($_SERVER["PHP_SELF"]) == "trips.php") { echo "u-sidebar-nav--opened"; } ?>">
                    <a class="menu-link" href="#!" data-target='#accountingSideNav'>
                        <i class="fa fa-calculator menu-icon"></i>
                        <span class="menu-item-title">Accounting</span>
                        <span class="menu-item-arrow">
                            <i class="fa fa-angle-down menu-item-arrow-icon"></i>
                        </span>
                    </a>

                    <ul id="accountingSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                        <!-- Payments -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "payments.php") { echo "active"; } ?>" href="payments.php">
                                <i class="fa fa-credit-card menu-icon"></i>
                                <span class="menu-item-title">Payments</span>
                            </a>
                        </li>

                        <!-- Vendors -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "vendors.php") { echo "active"; } ?>" href="vendors.php">
                                <i class="fa fa-building menu-icon"></i>
                                <span class="menu-item-title">Vendors</span>
                            </a>
                        </li>

                        <!-- Expenses -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "expenses.php" || basename($_SERVER["PHP_SELF"]) == "recurring_expenses.php") { echo "active"; } ?>" href="expenses.php">
                                <i class="fa fa-shopping-cart menu-icon"></i>
                                <span class="menu-item-title">Expenses</span>
                            </a>
                        </li>

                        <!-- Accounts -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "accounts.php") { echo "active"; } ?>" href="accounts.php">
                                <i class="fa fa-piggy-bank menu-icon"></i>
                                <span class="menu-item-title">Accounts</span>
                            </a>
                        </li>

                        <!-- Transfers -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "transfers.php") { echo "active"; } ?>" href="transfers.php">
                                <i class="fa fa-exchange-alt menu-icon"></i>
                                <span class="menu-item-title">Transfers</span>
                            </a>
                        </li>

                        <!-- Budget -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "budget.php") { echo "active"; } ?>" href="budget.php">
                                <i class="fa fa-balance-scale menu-icon"></i>
                                <span class="menu-item-title">Budget</span>
                            </a>
                        </li>

                        <!-- Trips -->
                        <li class="menu-item">
                            <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "trips.php") { echo "active"; } ?>" href="trips.php">
                                <i class="fa fa-route menu-icon"></i>
                                <span class="menu-item-title">Trips</span>
                            </a>
                        </li>
                    </ul>
                </li>
                    
                <hr>

                <!-- Reports -->
                <li class="menu-item">
                    <a class="menu-link <?php if (basename($_SERVER["PHP_SELF"]) == "report_income_summary.php") { echo "active"; } ?>" href="/pages/report/">
                        <i class="fa fa-chart-line menu-icon"></i>
                        <span class="menu-item-title">Reports</span>
                    </a>
                </li>

                <hr>

                <!-- Admin -->
                <li class="menu-item">
                    <a class="menu-link" href="/pages/admin/">
                        <i class="fa fa-user-shield menu-icon"></i>
                        <span class="menu-item-title">Admin</span>
                    </a>
                </li>

                <hr>

                <!-- Settings -->
                <li class="menu-item">
                    <a class="menu-link" href="/pages/settings">
                        <i class="fa fa-cogs
                        menu-icon"></i>
                        <span class="menu-item-title">Settings</span>
                    </a>
                </li>
