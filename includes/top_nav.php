<?php

$menuItems = [
    [
        'title' => 'Dashboard',
        'icon' => 'bx bx-home',
        'link' => '/pages/dashboard.php'
    ],
    [
        'title' => 'Support',
        'icon' => 'bx bx-support',
        'children' => [
            ['title' => 'Clients', 'link' => '/pages/clients.php', 'icon' => 'bx bx-briefcase'],
            ['title' => 'Tickets', 'link' => '/pages/tickets.php', 'icon' => 'bx bx-first-aid'],
            ['title' => 'Trips', 'link' => '/pages/trips.php', 'icon' => 'bx bx-car'],
            ['title' => 'Tasks', 'link' => '/pages/tasks.php', 'icon' => 'bx bx-task'],
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
        'title' => 'Finance',
        'icon' => 'bx bx-money-withdraw',
        'children' => [
            ['title' => 'Payments', 'link' => '/pages/payments.php', 'icon' => 'bx bx-credit-card'],
            ['title' => 'Expenses', 'link' => '/pages/expenses.php', 'icon' => 'bx bx-money'],
            ['title' => 'Transfers', 'link' => '/pages/transfers.php', 'icon' => 'bx bx-transfer'],
            ['title' => 'Accounts', 'link' => '/pages/accounts.php', 'icon' => 'bx bx-wallet'],
            
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
            ['title' => 'Estimates', 'link' => '/pages/client/client_quotes.php?client_id=' . $client_id, 'icon' => 'bx bx-message-square-detail'],
            ['title' => 'Payments', 'link' => '/pages/client/client_payments.php?client_id=' . $client_id, 'icon' => 'bx bx-credit-card'],
            ['title' => 'Statements', 'link' => '/pages/client/client_statements.php?client_id=' . $client_id, 'icon' => 'bx bx-file'],
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

require_once "/var/www/develop.twe.tech/includes/shortcuts.php";


//get number of notifications
$sql_notifications = mysqli_query(
    $mysqli,
    "SELECT * FROM notifications
    WHERE notification_dismissed_at IS NULL
    ORDER BY notification_timestamp DESC
    "
);
$num_notifications = mysqli_num_rows($sql_notifications);

?>

            
            <!-- Navbar -->
            <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                <div class="container-xxl">
                    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
                        <a href="index.html" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                        <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                                        <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                                        <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                                        <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                                    </defs>
                                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                            <g id="Icon" transform="translate(27.000000, 15.000000)">
                                                <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                    <mask id="mask-2" fill="white">
                                                        <use xlink:href="#path-1"></use>
                                                    </mask>
                                                    <use fill="#696cff" xlink:href="#path-1"></use>
                                                    <g id="Path-3" mask="url(#mask-2)">
                                                        <use fill="#696cff" xlink:href="#path-3"></use>
                                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                                    </g>
                                                    <g id="Path-4" mask="url(#mask-2)">
                                                        <use fill="#696cff" xlink:href="#path-4"></use>
                                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                                    </g>
                                                </g>
                                                <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                                    <use fill="#696cff" xlink:href="#path-5"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <span class="app-brand-text demo menu-text fw-bold"><?= $tenant_brand ? $tenant_brand : 'ITFlow-NG' ?></span>
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
                                            if (in_array($page_name, array_keys($shortcutsMap))) {
                                            ?>
                                            <a href="/post.php?add_shortcut=<?= $page_name ?>" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Add shortcuts"><i class="bx bx-sm bx-plus-circle"></i></a>
                                            <?php } ?>
                                    </div>
                                    <div class="dropdown-shortcuts-list scrollable-container">
                                        <?php
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
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1"><?= $notification_type ?></h6>
                                                        <p class="mb-0"><?= $notification ?></p>
                                                        <small class="text-muted"><?= $notification_timestamp ?></small>
                                                    </div>
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
                                        <button class="btn btn-primary text-uppercase w-100">view all notifications</button>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Notification -->
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
                    <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
                        <div class="container-xxl d-flex h-100">
                            <?php if ($page_is_client || $page_is_ticket) {
                                renderMenu($clientMenuItems);
                            } else {
                                renderMenu($menuItems);
                            } ?>
                        </div>
                    </aside>
                    <!-- / Menu -->

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">

                    <?php if ($page_is_client) { //if page is client page (client.php, client-*.php
                        require_once "/var/www/develop.twe.tech/includes/inc_client_top_head.php";
                    }
                    ?>
                    <h4 class="font-weight-bold py-3 mb-4">
                        <!-- breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/pages/dashboard.php">Home</a></li>
                                
                                <?php if ($page_is_client || $page_is_ticket) { ?>
                                    <li class="breadcrumb-item">
                                        <a href="/pages/client/client_overview.php?client_id=<?= $client_id ?>">
                                            <?= ucfirst($client_name) ?>
                                        </a>
                                    </li>
                                <?php } ?>

                                <li class="breadcrumb-item active" aria-current="page"><?= $page_name ?></li>
                            </ol>
                        </nav>
                    </h4>
                    <div class="row">
                        

