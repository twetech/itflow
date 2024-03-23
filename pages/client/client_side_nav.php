
        <!-- Sidebar Menu -->
        <nav class="u-sidebar-nav">

            <ul class="u-sidebar-nav-menu u-sidebar-nav-menu--top-level">

                <!-- Dashboard -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#detailsSideNav'>
                        <i class="fa fa-user-circle u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">Client Details</span>
                        <span class="u-sidebar-nav-menu__item-arrow">
                            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
                        </span>
                    </a>
                    <ul id="detailsSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_overview.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-tachometer-alt u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Overview</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_contacts.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-users u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Contacts</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_locations.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-map-marker-alt u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Locations</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End Dashboard -->

                <hr>

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
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_tickets.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-life-ring u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Tickets</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_vendors.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-building u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Vendors</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_events.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-calendar-alt u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Calendar</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End Support -->

                <hr>

                <!-- Documentation -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#documentationSideNav'>
                        <i class="fa fa-pencil-alt u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">Documentation</span>
                        <span class="u-sidebar-nav-menu__item-arrow">
                            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
                        </span>
                    </a>
                    <ul id="documentationSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_assets.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-desktop u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Assets</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_software.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-cubes u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Licenses</span>
                            </a>
                        </li> 
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_logins.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-key u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Logins</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_networks.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-network-wired u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Networks</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_certificates.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-lock u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Certificates</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_domains.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-globe u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Domains</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_services.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-stream u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Services</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_documents.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-folder u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Documents</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_files.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-paperclip u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Files</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End Documentation -->

                <hr>

                <!-- Finance -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#financeSideNav'>
                        <i class="fa fa-money-bill-wave u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">Finance</span>
                        <span class="u-sidebar-nav-menu__item-arrow">
                            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
                        </span>
                    </a>
                    <ul id="financeSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_invoices.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-file-invoice u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Invoices</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_quotes.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-comment-dollar u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Quotes</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_payments.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-credit-card u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Payments</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_statement.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-file-invoice-dollar u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Statement</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_trips.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-route u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Trips</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- End Finance -->

                <hr>

                <!-- More -->
                <li class="u-sidebar-nav-menu__item">
                    <a class="u-sidebar-nav-menu__link" href="#!" data-target='#moreSideNav'>
                        <i class="fa fa-plus-circle u-sidebar-nav-menu__item-icon"></i>
                        <span class="u-sidebar-nav-menu__item-title">More</span>
                        <span class="u-sidebar-nav-menu__item-arrow">
                            <i class="fa fa-angle-down u-sidebar-nav-menu__item-arrow-icon"></i>
                        </span>
                    </a>
                    <ul id="moreSideNav" class="u-sidebar-nav-menu u-sidebar-nav-menu--second-level" style="display: none;">
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_bulk_mail.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-envelope-open u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Bulk Mail</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_shared_items.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-share u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Shared Links</span>
                            </a>
                        </li>
                        <li class="u-sidebar-nav-menu__item">
                            <a class="u-sidebar-nav-menu__link" href="client_logs.php?client_id=<?php echo $client_id; ?>">
                                <i class="fa fa-history u-sidebar-nav-menu__item-icon"></i>
                                <span class="u-sidebar-nav-menu__item-title">Audit Logs</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
