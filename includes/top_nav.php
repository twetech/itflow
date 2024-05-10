<?php


if (isset($client_page)) { 
    $clientMenuItems = [
    [
        'title' => 'Home',
        'icon' => 'bx bx-home',
        'link' => '/pages/dashboard.php'
    ],
    [
        'title' => 'Client Overview',
        'icon' => 'bx bx-stats',
        'link' => '/pages/client/client_overview.php?client_id=' . $client_id
    ],
    [
        'title' => 'Support',
        'icon' => 'bx bx-support',
        'children' => [
            ['title' => 'Tickets', 'link' => '/pages/client/client_tickets.php?client_id=' . $client_id, 'icon' => 'bx bx-first-aid'],
            ['title' => 'Contacts', 'link' => '/pages/client/client_contacts.php?client_id=' . $client_id, 'icon' => 'bx bx-user'],
            ['title' => 'Locations', 'link' => '/pages/client/client_locations.php?client_id=' . $client_id, 'icon' => 'bx bx-map'],
            ['title' => 'Trips', 'link' => '/pages/client/client_trips.php?client_id=' . $client_id, 'icon' => 'bx bx-car'],
            ['title' => 'Tasks', 'link' => '/pages/client/client_tasks.php?client_id=' . $client_id, 'icon' => 'bx bx-task'],
        ]
    ],
    [
        'title' => 'Documentation',
        'icon' => 'bx bx-book',
        'children' => [
            ['title' => 'Assets', 'link' => '/pages/client/client_assets.php?client_id=' . $client_id, 'icon' => 'bx bx-barcode'],
            
            ['title' => 'Licenses', 'link' => '/pages/client/client_software.php?client_id=' . $client_id, 'icon' => 'bx bx-key'],
            ['title' => 'Logins', 'link' => '/pages/client/client_logins.php?client_id=' . $client_id, 'icon' => 'bx bx-log-in'],
            ['title' => 'Networks', 'link' => '/pages/client/client_networks.php?client_id=' . $client_id, 'icon' => 'bx bx-network-chart'],
            ['title' => 'Services', 'link' => '/pages/client/client_services.php?client_id=' . $client_id, 'icon' => 'bx bx-server'],
            ['title' => 'Vendors', 'link' => '/pages/client/client_vendors.php?client_id=' . $client_id, 'icon' => 'bx bx-user-voice'],
            ['title' => 'Files', 'link' => '/pages/client/client_files.php?client_id=' . $client_id, 'icon' => 'bx bx-paperclip'],
            ['title' => 'Documents', 'link' => '/pages/client/client_documents.php?client_id=' . $client_id, 'icon' => 'bx bx-file'],
        ]
    ],
    [
        'title' => 'Finance',
        'icon' => 'bx bx-dollar',
        'children' => [
            ['title' => 'Invoices', 'link' => '/pages/client/client_invoices.php?client_id=' . $client_id, 'icon' => 'bx bx-receipt'],
            ['title' => 'Quotes', 'link' => '/pages/client/client_quotes.php?client_id=' . $client_id, 'icon' => 'bx bx-message-square-detail'],
            ['title' => 'Payments', 'link' => '/pages/client/client_payments.php?client_id=' . $client_id, 'icon' => 'bx bx-credit-card'],
            ['title' => 'Statements', 'link' => '/pages/client/client_statement.php?client_id=' . $client_id, 'icon' => 'bx bx-file'],
            ['title' => 'Credits', 'link' => '/pages/client/client_credits.php?client_id=' . $client_id, 'icon' => 'bx bx-money'],
        ]
    ],
    [
        'title' => 'Other',
        'icon' => 'bx bx-plus',
        'children' => [
            ['title' => 'Bulk Email', 'link' => '/pages/client/client_bulk_email.php?client_id=' . $client_id, 'icon' => 'bx bx-mail-send'],
            ['title' => 'Shared Links', 'link' => '/pages/client/client_shared_links.php?client_id=' . $client_id, 'icon' => 'bx bx-link'],
            ['title' => 'Audit Logs', 'link' => '/pages/client/client_audit_logs.php?client_id=' . $client_id, 'icon' => 'bx bx-history']
        ]
    ]
    ];
} else {
    $menuItems = [
        [
            'title' => 'Dashboard',
            'icon' => 'bx bx-home',
            'link' => '/pages/dashboard.php'
        ],
        [
            'title' => 'Search',
            'icon' => 'bx bx-search',
            'link' => '/pages/global_search.php'
        ],
        [
            'title' => 'Support',
            'icon' => 'bx bx-support',
            'children' => [
                ['title' => 'Clients', 'link' => '/pages/clients.php', 'icon' => 'bx bx-briefcase'],
                ['title' => 'Tickets', 'link' => '/pages/tickets.php', 'icon' => 'bx bx-first-aid'],
                ['title' => 'Trips', 'link' => '/pages/trips.php', 'icon' => 'bx bx-car'],
                ['title' => 'Projects', 'link' => '/pages/projects.php', 'icon' => 'bx bx-task'],
                ['title' => 'Calendar', 'link' => '/pages/calendar_events.php', 'icon' => 'bx bx-calendar-star']
            ]
        ],
        [
            'title' => 'Sales',
            'icon' => 'bx bx-shopping-bag',
            'children' => [
                ['title' => 'Quotes', 'link' => '/pages/quotes.php', 'icon' => 'bx bx-message-square-detail'],
                ['title' => 'Invoices', 'link' => '/pages/invoices.php', 'icon' => 'bx bx-receipt'],
                ['title' => 'Products', 'link' => '/pages/products.php', 'icon' => 'bx bx-box'],
                
            ]
        ],
        [
            'title' => 'Accounting',
            'icon' => 'bx bx-money-withdraw',
            'children' => [
                ['title' => 'Recurring Invoices', 'link' => '/pages/recurring_invoices.php', 'icon' => 'bx bx-receipt'],
                ['title' => 'Payments', 'link' => '/pages/payments.php', 'icon' => 'bx bx-credit-card'],
                ['title' => 'Credits', 'link' => '/pages/credits.php', 'icon' => 'bx bx-money'],
                ['title' => 'Expenses', 'link' => '/pages/expenses.php', 'icon' => 'bx bx-money'],
                ['title' => 'Transfers', 'link' => '/pages/transfers.php', 'icon' => 'bx bx-transfer'],
                ['title' => 'Accounts', 'link' => '/pages/accounts.php', 'icon' => 'bx bx-wallet'],
                ['title' => 'Credits', 'link' => '/pages/credits.php', 'icon' => 'bx bx-money']
            ]
        ],
        [
            'title' => 'Reports',
            'icon' => 'bx bx-bar-chart',
            'children' => [
                ['title' => 'Financial', 'icon' => 'bx bx-dollar', 'children' => [
                    ['title' => 'Income', 'link' => '/pages/report/report_income_summary.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Income By Client', 'link' => '/pages/report/report_income_by_client.php', 'icon' => 'bx bx bx-box'],
                    ['title' => 'Recurring Income by Client' , 'link' => '/pages/report/report_recurring_by_client.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Expenses', 'link' => '/pages/report/report_expense_summary.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Expenses By Vendor', 'link' => '/pages/report/report_expenses_by_vendor.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Budgets', 'link' => '/pages/report/report_budget.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Profit & Loss', 'link' => '/pages/report/report_profit_loss.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Balance Sheet', 'link' => '/pages/report/report_balance_sheet.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Cash Flow', 'link' => '/pages/report/report_cash_flow.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Tax Summary', 'link' => '/pages/report/report_tax_summary.php', 'icon' => 'bx bx-box'],
                    ['title' => 'Collections', 'link' => '/pages/report/report_collections.php', 'icon' => 'bx bx-box']
                    ]],
                    ['title' => 'Technical', 'icon' => 'bx bx-cog', 'children' => [
                        ['title' => 'Unbilled Tickets', 'link' => '/pages/report/report_tickets_unbilled.php', 'icon' => 'bx bx-box'],
                        ['title' => 'Tickets', 'link' => '/pages/report/report_tickets.php', 'icon' => 'bx bx-box'],
                        ['title' => 'Tickets by Client', 'link' => '/pages/report/report_tickets_by_client.php', 'icon' => 'bx bx-box'],
                        ['title' => 'Password Rotation', 'link' => '/pages/report/report_password_rotation.php', 'icon' => 'bx bx-box'],
                        ['title' => 'All Assets', 'link' => '/pages/report/report_all_assets.php', 'icon' => 'bx bx-box'],
                    ]]
            ]
        ],
        [
            'title' => 'Administration',
            'icon' => 'bx bx-wrench',
            'children' => [
                ['title' => 'Users', 'link' => '/pages/admin/admin_users.php', 'icon' => 'bx bx-user'],
                ['title' => 'API Keys', 'link' => '/pages/admin/admin_api_keys.php', 'icon' => 'bx bx-key'],
                ['title' => 'Tags and Categories', 'icon' => 'bx bx-tag', 'children' => [
                    ['title' => 'Tags', 'link' => '/pages/admin/admin_tags.php', 'icon' => 'bx bx-purchase-tag'],
                    ['title' => 'Categories', 'link' => '/pages/admin/admin_categories.php', 'icon' => 'bx bx-category']
                ]],
                ['title' => 'Financial', 'icon' => 'bx bx-dollar', 'children' => [
                    ['title' => 'Taxes', 'link' => '/pages/admin/admin_taxes.php', 'icon' => 'bx bx-bank'],
                    ['title' => 'Account Types', 'link' => '/pages/admin/admin_account_types.php', 'icon' => 'bx bx-university']
                ]],
                ['title' => 'Templates', 'icon' => 'bx bx-file', 'children' => [
                    ['title' => 'Vendor Templates', 'link' => '/pages/admin/admin_vendor_templates.php', 'icon' => 'bx bx-file'],
                    ['title' => 'License Templates', 'link' => '/pages/admin/admin_license_templates.php', 'icon' => 'bx bx-file'],
                    ['title' => 'Document Templates', 'link' => '/pages/admin/admin_document_templates.php', 'icon' => 'bx bx-file'],
                ]],
                ['title' => 'Maintenance', 'icon' => 'bx bx-cog', 'children' => [
                    ['title' => 'Mail Queue', 'link' => '/pages/admin/admin_mail_queue.php', 'icon' => 'bx bx-envelope'],
                    ['title' => 'Audit Logs', 'link' => '/pages/admin/admin_audit_logs.php', 'icon' => 'bx bx-history'],
                    ['title' => 'Backup', 'link' => '/pages/admin/admin_backup.php', 'icon' => 'bx bx-cloud-download'],
                    ['title' => 'Debug', 'link' => '/pages/admin/admin_debug.php', 'icon' => 'bx bx-bug']
                ]]
            ]
        ],
        [
            'title' => 'Settings',
            'icon' => 'bx bx-cog',
            'children' => [
                ['title' => 'Modules', 'icon' => 'bx bx-checkbox', 'children' => [
                    ['title' => 'Enabled Modules', 'link' => '/pages/settings/settings_modules.php', 'icon' => 'bx bx-checkbox-square'],
                    ['title' => 'Invoice Module', 'link' => '/pages/settings/settings_invoice.php', 'icon' => 'bx bx-barcode'],
                    ['title' => 'Ticket Module', 'link' => '/pages/settings/settings_ticket.php', 'icon' => 'bx bx-first-aid'],
                    ['title' => 'Task Module', 'link' => '/pages/settings/settings_task.php', 'icon' => 'bx bx-task'],
                    ['title' => 'Calendar Module', 'link' => '/pages/settings/settings_calendar.php', 'icon' => 'bx bx-calendar'],
                    ['title' => 'Quote Module', 'link' => '/pages/settings/settings_quote.php', 'icon' => 'bx bx-message-square-detail'],
                    ['title' => 'Expense Module', 'link' => '/pages/settings/settings_expense.php', 'icon' => 'bx bx-money'],
                    ['title' => 'Transfer Module', 'link' => '/pages/settings/settings_transfer.php', 'icon' => 'bx bx-transfer'],
                    ['title' => 'Online Payments Module', 'link' => '/pages/settings/settings_online_payments.php', 'icon' => 'bx bx-credit-card'],
                    ['title' => 'Integrations', 'link' => '/pages/settings/settings_integrations.php', 'icon' => 'bx bx-plug'],
                ]],
                ['title' => 'General', 'icon' => 'bx bx-cog', 'children' => [
                    ['title' => 'Company', 'link' => '/pages/settings/settings_company.php', 'icon' => 'bx bx-building'],
                    ['title' => 'Localization', 'link' => '/pages/settings/settings_localization.php', 'icon' => 'bx bx-globe'],
                    ['title' => 'Security', 'link' => '/pages/settings/settings_security.php', 'icon' => 'bx bx-lock'],
                    ['title' => 'Email', 'link' => '/pages/settings/settings_email.php', 'icon' => 'bx bx-envelope'],
                    ['title' => 'Notifications', 'link' => '/pages/settings/settings_notifications.php', 'icon' => 'bx bx-bell'],
                    ['title' => 'Custom Fields', 'link' => '/pages/settings/settings_custom_fields.php', 'icon' => 'bx bx-list-ul'],
                    ['title' => 'Defaults', 'link' => '/pages/settings/settings_defaults.php', 'icon' => 'bx bx-cog'],
                    ['title' => 'Integrations', 'link' => '/pages/settings/settings_integrations.php', 'icon' => 'bx bx-plug'],
                    ['title' => 'Webhooks', 'link' => '/pages/settings/settings_webhooks.php', 'icon' => 'bx bx-link'],
                    ['title' => 'AI', 'link' => '/pages/settings/settings_ai.php', 'icon' => 'bx bx-brain'],
                ]]
            ]
        ]
    ];
}

// Render Nav menu
function renderMenu($menuItems, $isSubmenu = false) {
    $ulClass = $isSubmenu ? 'menu-sub' : 'menu-inner';
    $html = "<ul class=\"$ulClass\">";

    foreach ($menuItems as $item) {
        $hasChildren = isset($item['children']);
        $link = $hasChildren ? 'javascript:void(0)' : $item['link'];

        $html .= '<li class="menu-item">';
        $html .= "<a href=\"$link\" class=\"menu-link" . ($hasChildren ? ' menu-toggle' : '') . "\">";
        $html .= '<i class="menu-icon tf-icons ' . $item['icon'] . '"></i>';
        $html .= '<div data-i18n="' . $item['title'] . '">' . $item['title'] . '</div>';
        $html .= '</a>';

        if ($hasChildren) {
            $html .= renderMenu($item['children'], true);
        }
        $html .= '</li>';
    }

    $html .= '</ul>';

    echo $html;
    return $html;
}

// Render user shortcuts
function renderUserShortcuts($shortcutsData, $shortcutsMap) {
    $html = '<div class="row row-bordered overflow-visible g-0">';
    $colCount = 0;

    foreach ($shortcutsData as $row) {
        $key = $row['shortcut_key'];
        if (array_key_exists($key, $shortcutsMap)) {
            $shortcut = $shortcutsMap[$key];
            $html .= '<div class="dropdown-shortcuts-item col">';
            $html .= '<span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">';
            $html .= '<i class="' . $shortcut['icon'] . ' fs-4"></i>';
            $html .= '</span>';
            $html .= '<a href="' . $shortcut['link'] . '" class="stretched-link">' . $shortcut['name'] . '</a>';
            $html .= '<small class="text-muted mb-0">' . $shortcut['description'] . '</small>';
            $html .= '</div>';

            $colCount++;
            if ($colCount % 2 == 0) {
                $html .= '</div><div class="row row-bordered overflow-visible g-0">';
            }
        }
    }
    $html .= '</div>';

    echo $html;
}

require_once "/var/www/portal.twe.tech/includes/shortcuts.php";


//get number of notifications
$sql_notifications = mysqli_query(
    $mysqli,
    "SELECT * FROM notifications
    WHERE notification_dismissed_at IS NULL
    ORDER BY notification_timestamp DESC
    "
);
$num_notifications = mysqli_num_rows($sql_notifications);

if (isset($client_page)) {
    $nav_title = 'TWE: '.$client_name;
    $nav_title_link = '/pages/client/client_overview.php?client_id=' . $client_id;
} else {
    $nav_title = 'TWE Technologies';
    $nav_title_link = '/pages/dashboard.php';
}

?>

            
            <!-- Navbar -->
            <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme d-print-none" id="layout-navbar">
                <div class="container-xxl">
                    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
                        <a href="<?= $nav_title_link ?>" class="app-brand-link gap-2">
                            <span class="app-brand-text demo menu-text fw-bold"><?= $nav_title ?></span>
                            </span>
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                            <i class="bx bx-chevron-left bx-sm align-middle"></i>
                        </a>
                    </div>

                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Search -->
                            <li class="nav-item navbar-search-wrapper me-2 me-xl-0">
                                <a class="nav-link search-toggler" href="javascript:void(0);">
                                    <i class="bx bx-search bx-sm"></i>
                                </a>
                            </li>
                            <!-- /Search -->

                            <!-- Quick links  -->
                            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="bx bx-grid-alt bx-sm"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end py-0">
                                    <div class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                                            <?php //if pagename not in array shortcutsMap, dont show add button
                                            if (in_array(ucwords($page_name), array_keys($shortcutsMap))) {
                                            ?>
                                            <a href="/post.php?add_shortcut=<?= ucwords($page_name) ?>" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Add shortcuts"><i class="bx bx-sm bx-plus-circle"></i></a>
                                            <?php } ?>
                                    </div>
                                    <div class="dropdown-shortcuts-list scrollable-container">
                                        <?php
                                            !isset($shortcutsData) ? $shortcutsData = [] : $shortcutsData;
                                            renderUserShortcuts($shortcutsData, $shortcutsMap);
                                        ?>
                                    </div>
                                </div>
                            </li>
                            <!-- Quick links -->

                            <!-- Style Switcher -->
                            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <i class="bx bx-sm"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                            <span class="align-middle"><i class="bx bx-sun me-2"></i>Light</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                            <span class="align-middle"><i class="bx bx-moon me-2"></i>Dark</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                            <span class="align-middle"><i class="bx bx-desktop me-2"></i>System</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- / Style Switcher-->

                            <!-- Notification -->
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="bx bx-bell bx-sm"></i>
                                    <?= $num_notifications > 0 ? '<span class="badge bg-danger rounded-pill badge-notifications">'. $num_notifications . '</span>' : $num_notifications ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">Notification</h5>
                                            <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i class="bx fs-4 bx-envelope-open"></i></a>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            <?php
                                                while ($row = mysqli_fetch_array($sql_notifications)) {
                                                    $notification_id = intval($row['notification_id']);
                                                    $notification_type = nullable_htmlentities($row['notification_type']);
                                                    $notification = nullable_htmlentities($row['notification']);
                                                    $notification_action = nullable_htmlentities($row['notification_action']);
                                                    $notification_timestamp = date('M d g:ia',strtotime($row['notification_timestamp']));
                                                    $notification_client_id = intval($row['notification_client_id']);
                                                    if(empty($notification_action)) { $notification_action = "#"; }
                                            ?>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <a class="flex-grow-1" href="/pages/<?= $notification_action ?>">
                                                        <h6 class="mb-1"><?= $notification_type ?></h6>
                                                        <p class="mb-0"><?= $notification ?></p>
                                                        <small class="text-muted"><?= $notification_timestamp ?></small>
                                                    </a>
                                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="bx bx-x"></span></a>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        </ul>
                                    </li>
                                    <li class="dropdown-menu-footer border-top p-3">
                                        <a class="btn btn-primary text-uppercase w-100" href="/pages/notifications.php">view all notifications</a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Notification -->
                            <?php if ($config_module_enable_ticketing == 1) { ?>
                            <!-- Open Tickets -->
                                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                                    <a class="nav-link loadModalContentBtn" href="#" data-toggle="modal" data-target="#dynamicModal" id="openTicketsModal" data-modal-file="top_nav_tickets_modal.php">
                                        <i class="bx bx-first-aid bx-sm"></i>
                                        <span class="badge rounded-pill badge-notifications" id="runningTicketsCount">0</span>
                                    </a>
                                </li>

                            <?php } ?>
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?php echo "/uploads/users/$session_user_id/$session_avatar"; ?>" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="<?php echo "/uploads/users/$session_user_id/$session_avatar"; ?>" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block"><?php echo stripslashes(nullable_htmlentities($session_name)); ?></span>
                                                    <small class="text-muted"><?php echo nullable_htmlentities($session_user_role_display); ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/pages/user/user_details.php">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/pages/user/user_preferences.php">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/post.php?logout" target="_blank">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>

                    <!-- Search Small Screens -->
                    <div class="navbar-search-wrapper search-input-wrapper container-xxl d-none">
                        <input type="text" class="form-control search-input border-0" placeholder="Search..." aria-label="Search..." />
                        <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
                    </div>
                </div>
            </nav> <!-- / Navbar -->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Menu -->
                    <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0 d-print-none">
                        <div class="container-xxl d-flex h-100">
                            <?php if (isset($client_page)) {
                                renderMenu($clientMenuItems);
                            } else {
                                renderMenu($menuItems);
                            } ?>
                        </div>
                    </aside>
                    <!-- / Menu -->

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">

                    <?php if (isset($client_page)) { //if page is client page (client.php, client-*.php
                        require_once "/var/www/portal.twe.tech/includes/inc_client_top_head.php";
                    }
                    ?>
                    <h4 class="font-weight-bold py-3 mb-4 d-print-none">
                        <!-- breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/pages/dashboard.php">Home</a></li>
                                
                                <?php if (isset($client_page)) { ?>
                                    <li class="breadcrumb-item">
                                        <a href="/pages/client/client_overview.php?client_id=<?= $client_id ?>">
                                            <?= ucfirst($client_name) ?>
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if (isset($report_page)) { ?>
                                    <li class="breadcrumb-item">
                                        Reports
                                    </li>
                                <?php } ?>

                                <li class="breadcrumb-item active" aria-current="page"><?= ucwords($page_name) ?></li>
                            </ol>
                        </nav>
                    </h4>
                    <div class="row">
                        

