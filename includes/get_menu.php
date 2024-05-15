<?

if ($session_company_reseller) {

    $menuItems = [
        [
            'title' => 'Companies',
            'icon' => 'bx bx-building',
            'link' => '/pages/reseller/companies.php'
        ],
        [
            'title' => 'Finance',
            'icon' => 'bx bx-dollar',
            'children' => [
                ['title' => 'Subscriptions', 'link' => '/pages/reseller/subscriptions.php', 'icon' => 'bx bx-receipt'],
                ['title' => 'Invoices', 'link' => '/pages/reseller/invoices.php', 'icon' => 'bx bx-receipt'],
                ['title' => 'Payments', 'link' => '/pages/reseller/payments.php', 'icon' => 'bx bx-credit-card'],
                ['title' => 'Credits', 'link' => '/pages/reseller/credits.php', 'icon' => 'bx bx-money'],
                ['title' => 'Statements', 'link' => '/pages/reseller/statements.php', 'icon' => 'bx bx-file'],
                ['title' => 'Collections', 'link' => '/pages/reseller/collections.php', 'icon' => 'bx bx-box']
            ]
        ]
    ];


} else {
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
}

