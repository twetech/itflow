<?php


if ($client_page) {
    $client_id = $client_header['client_id'];
    $client_name = $client_header['client_name'];
    $client_rate = $client_header['client_rate'] ?? 0;
    $client_currency_code = $client_header['client_currency_code'] ?? 'USD';
    $client_amount_paid = $client_header['client_payments'] ?? 0;
    $client_balance = $client_header['client_balance'] ?? 0;
    $client_recurring_monthly = $client_header['client_recurring_monthly'] ?? 0;
    $client_net_terms = $client_header['client_net_terms'] ?? 0;

    $client_open_tickets = $client_header['client_open_tickets'] ?? 0;
    $client_closed_tickets = $client_header['client_closed_tickets'] ?? 0;

    $location_address = $client_header['client_primary_location']['location_address'] ?? '';
    $location_city = $client_header['client_primary_location']['location_city'] ?? '';
    $location_state = $client_header['client_primary_location']['location_state'] ?? '';
    $location_zip = $client_header['client_primary_location']['location_zip'] ?? '';
    $location_country = $client_header['client_primary_location']['location_country'] ?? '';
    

    $contact_name = $client_header['client_primary_contact']['contact_name'] ?? '';
    $contact_email = $client_header['client_primary_contact']['contact_email'] ?? '';
    $contact_phone = $client_header['client_primary_contact']['contact_phone'] ?? '';
    $contact_mobile = $client_header['client_primary_contact']['contact_mobile'] ?? '';

    

    $clientMenuItems = [
        [
            'title' => 'All Clients',
            'icon' => 'bx bx-briefcase',
            'link' => '/public/?page=client'
        ],
        [
            'title' => 'Client Overview',
            'icon' => 'bx bx-stats',
            'link' => '/public/?page=client&action=show&client_id=' . $client_id
        ],
        [
            'title' => 'Support',
            'icon' => 'bx bx-support',
            'children' => [
                ['title' => 'Tickets', 'link' => '/public/?page=ticket&client_id=' . $client_id, 'icon' => 'bx bx-first-aid'],
                ['title' => 'Contacts', 'link' => '/public/?page=contact&client_id=' . $client_id, 'icon' => 'bx bx-user'],
                ['title' => 'Locations', 'link' => '/public/?page=location&client_id=' . $client_id, 'icon' => 'bx bx-map'],
                ['title' => 'Trips', 'link' => '/public/?page=trips&client_id=' . $client_id, 'icon' => 'bx bx-car'],
                ['title' => 'Projects', 'link' => '/public/?page=projects&client_id=' . $client_id, 'icon' => 'bx bx-task'],
            ]
        ],
        [
            'title' => 'Documentation',
            'icon' => 'bx bx-book',
            'children' => [
                ['title' => 'Assets', 'link' => '/public/?page=documentation&documentation_type=asset&client_id=' . $client_id, 'icon' => 'bx bx-box'],
                ['title' => 'Licenses', 'link' => '/public/?page=documentation&documentation_type=license&client_id=' . $client_id, 'icon' => 'bx bx-key'],
                ['title' => 'Logins', 'link' => '/public/?page=documentation&documentation_type=login&client_id=' . $client_id, 'icon' => 'bx bx-log-in'],
                ['title' => 'Networks', 'link' => '/public/?page=documentation&documentation_type=network&client_id=' . $client_id, 'icon' => 'bx bx-network-chart'],
                ['title' => 'Services', 'link' => '/public/?page=documentation&documentation_type=service&client_id=' . $client_id, 'icon' => 'bx bx-server'],
                ['title' => 'Vendors', 'link' => '/public/?page=documentation&documentation_type=vendor&client_id=' . $client_id, 'icon' => 'bx bx-user-voice'],
                ['title' => 'Files', 'link' => '/public/?page=documentation&documentation_type=file&client_id=' . $client_id, 'icon' => 'bx bx-paperclip'],
                ['title' => 'Documents', 'link' => '/public/?page=documentation&documentation_type=document&client_id=' . $client_id, 'icon' => 'bx bx-file'],
            ]
        ],
        [
            'title' => 'Finance',
            'icon' => 'bx bx-dollar',
            'children' => [
                ['title' => 'Invoices', 'link' => '/public/?page=invoice&client_id=' . $client_id, 'icon' => 'bx bx-receipt'],
                ['title' => 'Quotes', 'link' => '/public/?page=quote&client_id=' . $client_id, 'icon' => 'bx bx-message-square-detail'],
                ['title' => 'Payments', 'link' => '/public/?page=payment&client_id=' . $client_id, 'icon' => 'bx bx-credit-card'],
                ['title' => 'Statements', 'link' => '/public/?page=statement&client_id=' . $client_id, 'icon' => 'bx bx-file'],
                ['title' => 'Credits', 'link' => '/public/?page=credit&client_id=' . $client_id, 'icon' => 'bx bx-money'],
            ]
        ],
        [
            'title' => 'Other',
            'icon' => 'bx bx-plus',
            'children' => [
                ['title' => 'Bulk Email', 'link' => '/public/?page=bulk_email&client_id=' . $client_id, 'icon' => 'bx bx-mail-send'],
                ['title' => 'Shared Links', 'link' => '/public/?page=shared_links&client_id=' . $client_id, 'icon' => 'bx bx-link'],
                ['title' => 'Audit Logs', 'link' => '/public/?page=audit_logs&client_id=' . $client_id, 'icon' => 'bx bx-history']
            ]
        ]
    ];
} else {
    $menuItems = [
        [
            'title' => 'Clients',
            'icon' => 'bx bx-briefcase',
            'link' => '/public/?page=client'
        ],
        [
            'title' => 'Support',
            'icon' => 'bx bx-support',
            'children' => [
                ['title' => 'Tickets', 'link' => '/public/?page=ticket', 'icon' => 'bx bx-first-aid'],
                ['title' => 'Trips', 'link' => '/public/?page=trips', 'icon' => 'bx bx-car'],
                ['title' => 'Projects', 'link' => '/public/?page=projects', 'icon' => 'bx bx-task'],
                ['title' => 'Calendar', 'link' => '/public/?page=calendar', 'icon' => 'bx bx-calendar']
            ]
        ],
        [
            'title' => 'Sales',
            'icon' => 'bx bx-shopping-bag',
            'children' => [
                ['title' => 'Quotes', 'link' => '/public/?page=quote', 'icon' => 'bx bx-message-square-detail'],
                ['title' => 'Invoices', 'link' => '/public/?page=invoice', 'icon' => 'bx bx-receipt'],
                ['title' => 'Products', 'link' => '/public/?page=products', 'icon' => 'bx bx-box'],
            ]
        ],
        [
            'title' => 'Accounting',
            'icon' => 'bx bx-money-withdraw',
            'children' => [
                ['title' => 'Recurring Invoices', 'link' => '/public/?page=recurring_invoices', 'icon' => 'bx bx-receipt'],
                ['title' => 'Payments', 'link' => '/public/?page=payment', 'icon' => 'bx bx-credit-card'],
                ['title' => 'Credits', 'link' => '/public/?page=credit', 'icon' => 'bx bx-money'],
                ['title' => 'Expenses', 'link' => '/public/?page=expense', 'icon' => 'bx bx-money'],
                ['title' => 'Transfers', 'link' => '/public/?page=transfer', 'icon' => 'bx bx-transfer'],
                ['title' => 'Accounts', 'link' => '/public/?page=accounts', 'icon' => 'bx bx-wallet'],
                ['title' => 'Credits', 'link' => '/public/?page=credit', 'icon' => 'bx bx-money']
            ]
        ],
        [
            'title' => 'Reports',
            'icon' => 'bx bx-bar-chart',
            'children' => [
                ['title' => 'Financial', 'icon' => 'bx bx-dollar', 'children' => [
                    ['title' => 'Income', 'link' => '/public/?page=report&report=income_summary', 'icon' => 'bx bx-box'],
                    ['title' => 'Income By Client', 'link' => '/public/?page=report&report=income_by_client', 'icon' => 'bx bx bx-box'],
                    ['title' => 'Recurring Income by Client' , 'link' => '/public/?page=report&report=recurring_by_client', 'icon' => 'bx bx-box'],
                    ['title' => 'Expenses', 'link' => '/public/?page=report&report=expense_summary', 'icon' => 'bx bx-box'],
                    ['title' => 'Expenses By Vendor', 'link' => '/public/?page=report&report=expenses_by_vendor', 'icon' => 'bx bx-box'],
                    ['title' => 'Budgets', 'link' => '/public/?page=report&report=budget', 'icon' => 'bx bx-box'],
                    ['title' => 'Profit & Loss', 'link' => '/public/?page=report&report=profit_loss', 'icon' => 'bx bx-box'],
                    ['title' => 'Balance Sheet', 'link' => '/public/?page=report&report=balance_sheet', 'icon' => 'bx bx-box'],
                    ['title' => 'Cash Flow', 'link' => '/public/?page=report&report=cash_flow', 'icon' => 'bx bx-box'],
                    ['title' => 'Tax Summary', 'link' => '/public/?page=report&report=tax_summary', 'icon' => 'bx bx-box'],
                    ['title' => 'Collections', 'link' => '/public/?page=report&report=collections', 'icon' => 'bx bx-box']
                    ]
                ],
                ['title' => 'Technical', 'icon' => 'bx bx-cog', 'children' => [
                    ['title' => 'Unbilled Tickets', 'link' => '/public/?page=report&report=tickets_unbilled', 'icon' => 'bx bx-box'],
                    ['title' => 'Tickets', 'link' => '/public/?page=report&report=tickets', 'icon' => 'bx bx-box'],
                    ['title' => 'Tickets by Client', 'link' => '/public/?page=report&report=tickets_by_client', 'icon' => 'bx bx-box'],
                    ['title' => 'Password Rotation', 'link' => '/public/?page=report&report=password_rotation', 'icon' => 'bx bx-box'],
                    ['title' => 'All Assets', 'link' => '/public/?page=report&report=all_assets', 'icon' => 'bx bx-box'],
                ]]
            ]
        ],
        [
            'title' => 'Administration',
            'icon' => 'bx bx-wrench',
            'children' => [
                ['title' => 'Users', 'link' => '/public/?page=admin&admin_page=users', 'icon' => 'bx bx-user'],
                ['title' => 'API Keys', 'link' => '/public/?page=admin&admin_page=api_keys', 'icon' => 'bx bx-key'],
                ['title' => 'Tags and Categories', 'icon' => 'bx bx-tag', 'children' => [
                    ['title' => 'Tags', 'link' => '/public/?page=admin&admin_page=tags', 'icon' => 'bx bx-purchase-tag'],
                    ['title' => 'Categories', 'link' => '/public/?page=admin&admin_page=categories', 'icon' => 'bx bx-category']
                ]],
                ['title' => 'Financial', 'icon' => 'bx bx-dollar', 'children' => [
                    ['title' => 'Taxes', 'link' => '/public/?page=admin&admin_page=taxes', 'icon' => 'bx bx-bank'],
                    ['title' => 'Account Types', 'link' => '/public/?page=admin&admin_page=account_types', 'icon' => 'bx bx-university']
                ]],
                ['title' => 'Templates', 'icon' => 'bx bx-file', 'children' => [
                    ['title' => 'Vendor Templates', 'link' => '/public/?page=admin&admin_page=vendor_templates', 'icon' => 'bx bx-file'],
                    ['title' => 'License Templates', 'link' => '/public/?page=admin&admin_page=license_templates', 'icon' => 'bx bx-file'],
                    ['title' => 'Document Templates', 'link' => '/public/?page=admin&admin_page=document_templates', 'icon' => 'bx bx-file'],
                ]],
                ['title' => 'Maintenance', 'icon' => 'bx bx-cog', 'children' => [
                    ['title' => 'Mail Queue', 'link' => '/public/?page=admin&admin_page=mail_queue', 'icon' => 'bx bx-envelope'],
                    ['title' => 'Audit Logs', 'link' => '/public/?page=admin&admin_page=audit_logs', 'icon' => 'bx bx-history'],
                    ['title' => 'Backup', 'link' => '/public/?page=admin&admin_page=backup', 'icon' => 'bx bx-cloud-download'],
                    ['title' => 'Debug', 'link' => '/public/?page=admin&admin_page=debug', 'icon' => 'bx bx-bug']
                ]]
            ]
        ],
        [
            'title' => 'Settings',
            'icon' => 'bx bx-cog',
            'children' => [
                ['title' => 'Modules', 'icon' => 'bx bx-checkbox', 'children' => [
                    ['title' => 'Enabled Modules', 'link' => '/public/?page=settings&settings_page=modules', 'icon' => 'bx bx-checkbox-square'],
                    ['title' => 'Invoice Module', 'link' => '/public/?page=settings&settings_page=invoice', 'icon' => 'bx bx-barcode'],
                    ['title' => 'Ticket Module', 'link' => '/public/?page=settings&settings_page=ticket', 'icon' => 'bx bx-first-aid'],
                    ['title' => 'Task Module', 'link' => '/public/?page=settings&settings_page=task', 'icon' => 'bx bx-task'],
                    ['title' => 'Calendar Module', 'link' => '/public/?page=settings&settings_page=calendar', 'icon' => 'bx bx-calendar'],
                    ['title' => 'Quote Module', 'link' => '/public/?page=settings&settings_page=quote', 'icon' => 'bx bx-message-square-detail'],
                    ['title' => 'Expense Module', 'link' => '/public/?page=settings&settings_page=expense', 'icon' => 'bx bx-money'],
                    ['title' => 'Transfer Module', 'link' => '/public/?page=settings&settings_page=transfer', 'icon' => 'bx bx-transfer'],
                    ['title' => 'Online Payments Module', 'link' => '/public/?page=settings&settings_page=online_payments', 'icon' => 'bx bx-credit-card'],
                    ['title' => 'Integrations', 'link' => '/public/?page=settings&settings_page=integrations', 'icon' => 'bx bx-plug'],
                ]],
                ['title' => 'General', 'icon' => 'bx bx-cog', 'children' => [
                    ['title' => 'Company', 'link' => '/public/?page=settings&settings_page=company', 'icon' => 'bx bx-building'],
                    ['title' => 'Localization', 'link' => '/public/?page=settings&settings_page=localization', 'icon' => 'bx bx-globe'],
                    ['title' => 'Security', 'link' => '/public/?page=settings&settings_page=security', 'icon' => 'bx bx-lock'],
                    ['title' => 'Email', 'link' => '/public/?page=settings&settings_page=email', 'icon' => 'bx bx-envelope'],
                    ['title' => 'Notifications', 'link' => '/public/?page=settings&settings_page=notifications', 'icon' => 'bx bx-bell'],
                    ['title' => 'Custom Fields', 'link' => '/public/?page=settings&settings_page=custom_fields', 'icon' => 'bx bx-list-ul'],
                    ['title' => 'Defaults', 'link' => '/public/?page=settings&settings_page=defaults', 'icon' => 'bx bx-cog'],
                    ['title' => 'Integrations', 'link' => '/public/?page=settings&settings_page=integrations', 'icon' => 'bx bx-plug'],
                    ['title' => 'Webhooks', 'link' => '/public/?page=settings&settings_page=webhooks', 'icon' => 'bx bx-link'],
                    ['title' => 'AI', 'link' => '/public/?page=settings&settings_page=ai', 'icon' => 'bx bx-brain'],
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

//TODO: Implement notifications
$num_notifications = 0;

if ($client_page) {
    $nav_title = 'TWE: '.$client_name;
} else {
    $nav_title = 'TWE Technologies';
}
$nav_title_link = '/public/';

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
                                                //TODO: Implement notifications
                                            ?>
                                        </ul>
                                    </li>
                                    <li class="dropdown-menu-footer border-top p-3">
                                        <a class="btn btn-primary text-uppercase w-100" href="/pages/notifications.php">view all notifications</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- Open Tickets -->
                                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                                    <a class="nav-link loadModalContentBtn" href="#" data-toggle="modal" data-target="#dynamicModal" id="openTicketsModal" data-modal-file="top_nav_tickets_modal.php">
                                        <i class="bx bx-first-aid bx-sm"></i>
                                        <span class="badge rounded-pill badge-notifications" id="runningTicketsCount">0</span>
                                    </a>
                                </li>
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?= "/uploads/users/$session_user_id/$session_avatar"; ?>" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="<?= "/uploads/users/$session_user_id/$session_avatar"; ?>" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block"><?= stripslashes(nullable_htmlentities($session_name)); ?></span>
                                                    <small class="text-muted"><?= nullable_htmlentities($session_user_role_display); ?></small>
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
                            <?php if ($client_page) {
                                renderMenu($clientMenuItems);
                            } else {
                                renderMenu($menuItems);
                            } ?>
                        </div>
                    </aside>
                    <!-- / Menu -->

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">

                    <?php if ($client_page) { ?>
                        <div class="card card-action d-print-none">
                        <div class="card-header">
                            <div class="card-action-title">
                                <h4>
                                    <?php if ($client_page) {
                                        echo ucwords($client_name);
                                    } else {
                                        echo $page_name;
                                    } ?>
                                </h4>
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="dropdown dropleft text-center">
                                            <button class="btn btn-dark btn-sm float-right" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-fw fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_edit_modal.php?client_id=<?= $client_id; ?>">
                                                    <i class="fas fa-fw fa-edit mr-2"></i>Edit Client
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_export_modal.php?client_id=<?= $client_id; ?>">
                                                    <i class="fas fa-fw fa-file-pdf mr-2"></i>Export Data
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_archive_modal.php?client_id=<?= $client_id; ?>">
                                                    <i class="fas fa-fw fa-archive mr-2"></i>Archive Client
                                                </a>
                                                <?php if ($session_user_role == 3) { ?>
                                                <div class="dropdown-divider"></div>
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_delete_modal.php?client_id=<?= $client_id; ?>">
                                                    <i class="fas fa-fw fa-trash mr-2"></i>Delete Client
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body py-2">
                            <div class="collapse <?php if (basename($_SERVER["PHP_SELF"]) == "client_overview.php") { echo "show"; } ?>" id="clientHeader">
                                <div class="row">
                                    <div class="col-md border-top">
                                        <h5 class="text-secondary mt-1">Primary Location</h5>
                                        <?php if (!empty($location_address)) { ?>
                                            <div>
                                                <a href="//maps.<?= $session_map_source; ?>.com/?q=<?= "$location_address $location_zip"; ?>" target="_blank">
                                                    <i class="fa fa-fw fa-map-marker-alt text-secondary ml-1 mr-2"></i><?= $location_address; ?>
                                                    <div><i class="fa fa-fw ml-1 mr-2"></i><?= "$location_city $location_state $location_zip"; ?></div>
                                                </a>
                                            </div>
                                        <?php }
                    
                                        if (!empty($location_phone)) { ?>
                                            <div class="mt-1">
                                                <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i><a href="tel:<?= $location_phone?>"><?= $location_phone; ?></a>
                                            </div>
                                            <hr class="my-2">
                                        <?php }
                    
                                        if (!empty($client_website)) { ?>
                                            <div class="mt-1">
                                                <i class="fa fa-fw fa-globe text-secondary ml-1 mr-2"></i><a target="_blank" href="//<?= $client_website; ?>"><?= $client_website; ?></a>
                                            </div>
                                        <?php } ?>
                    
                                    </div>
                    
                                    <div class="col-md border-left border-top">
                                        <h5 class="text-secondary mt-1">Primary Contact</h5>
                                        <?php
                    
                                        if (!empty($contact_name)) { ?>
                                            <div>
                                                <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i> <?= $contact_name; ?>
                                            </div>
                                        <?php }
                    
                                        if (!empty($contact_email)) { ?>
                                            <div class="mt-1">
                                                <i class="fa fa-fw fa-envelope text-secondary ml-1 mr-2"></i>
                                                <a href="mailto:<?= $contact_email; ?>"> <?= $contact_email; ?></a>
                                            </div>
                                            <?php
                                        }
                    
                                        if (!empty($contact_phone)) { ?>
                                            <div class="mt-1">
                                                <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i>
                                                <a href="tel:<?= $contact_phone; ?>"><?= $contact_phone; ?></a>
                    
                                                <?php
                                                if (!empty($contact_extension)) {
                                                    echo "<small>x$contact_extension</small>";
                                                }
                                                ?>
                                            </div>
                                            <?php
                                        }
                    
                                        if (!empty($contact_mobile)) { ?>
                                            <div class="mt-1">
                                                <i class="fa fa-fw fa-mobile-alt text-secondary ml-1 mr-2"></i>
                                                <a href="tel:<?= $contact_mobile; ?>"><?= $contact_mobile; ?></a>
                                            </div>
                                        <?php } ?>
                    
                                    </div>
                    
                                    <div class="col-md border-left border-top">
                                        <h5 class="text-secondary mt-1">Billing</h5>
                                        <table>
                                            <tr>
                                                <td class="text-secondary">Hourly Rate:</td>
                                                <td class="text-dark"><?= numfmt_format_currency($GLOBALS['currency_format'], $client_rate, $client_currency_code); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-secondary">Paid (YTD):</td>
                                                <td class="text-dark"><?= numfmt_format_currency($GLOBALS['currency_format'], $client_amount_paid, $client_currency_code); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-secondary">Balance:</td>
                                                <td class="<?php if ($balance > 0 || $balance < 0) { echo "text-danger"; }else{ echo "text-dark"; } ?>"><?= numfmt_format_currency($GLOBALS['currency_format'], $client_balance, $client_currency_code); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-secondary">Monthly Recurring:</td>
                                                <td class="text-dark"><?= numfmt_format_currency($GLOBALS['currency_format'], $client_recurring_monthly, $client_currency_code); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-secondary">Net Terms:</td>
                                                <td class="text-dark"><?= $client_net_terms; ?><small class="text-secondary ml-1">Days</small></td>
                                            </tr>
                                            <?php if(!empty($client_tax_id_number)) { ?>
                                                <tr>
                                                    <td class="text-secondary">Tax ID:</td>
                                                    <td class="text-dark"><?= $client_tax_id_number; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                    
                    
                                    <div class="col-md border-left border-top">
                                        <h5 class="text-secondary mt-1">Support</h5>
                                        <div class="ml-1 text-secondary">Open Tickets
                                            <span class="text-dark float-right"><?= $client_open_tickets; ?></span>
                                        </div>
                                        <div class="ml-1 text-secondary mt-1">Closed Tickets
                                            <span class="text-dark float-right"><?= $client_closed_tickets; ?></span>
                                        </div>
                                        <?php
                                        if (!empty($client_tag_name_display_array)) { ?>
                                        <hr>
                                        <?= $client_tags_display; ?>
                                        <?php } ?>
                                    </div>
                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php                    
                    }
                    ?>
                    <h4 class="font-weight-bold py-3 mb-4 d-print-none">
                        <!-- breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/pages/dashboard.php">Home</a></li>
                                
                                <?php if ($client_page) { ?>
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

                                <li class="breadcrumb-item active" aria-current="page"><?=ucwords($template)?></li>
                            </ol>
                        </nav>
                    </h4>
                    <div class="row">
                        

