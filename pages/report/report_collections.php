<?php
    require_once "/var/www/portal.twe.tech/includes/inc_all_reports.php";

    validateAccountantRole();

    // Fetch Accounts and their balances
    $sql_client_balance_report = "SELECT * FROM clients
    LEFT JOIN contacts ON clients.client_id = contacts.contact_client_id
    ORDER BY client_name desc
    ";


    $result_client_balance_report = mysqli_query($mysqli, $sql_client_balance_report);

    //get currency format from settings
    $config_currency_code = getSettingValue("company_currency");

    $datatable_order = "[[4, 'desc']]";

    $past_due_filter = isset($_GET['past_due_filter']) ? intval($_GET['past_due_filter']) : 2;
?>

<div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fas fa-fw fa-balance-scale mr-2"></i>Collections</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-label-primary d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i>Print</button>
            <form class="d-inline d-print-none" action="/pages/report/report_collections.php" method="get">
                <div class="input-group">
                    <label for="past_due_filter" class="d-inline">Past Due Filter:</label>
                    <select name="past_due_filter" id="past_due_filter" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="0" <?= $past_due_filter == 0 ? 'selected' : ''; ?>>0.5 Months</option>
                        <option value="1" <?= $past_due_filter == 1 ? 'selected' : ''; ?>>1 Month</option>
                        <option value="2" <?= $past_due_filter == 2 ? 'selected' : ''; ?>>2 Months</option>
                        <option value="3" <?= $past_due_filter == 3 ? 'selected' : ''; ?>>3 Months</option>
                        <option value="4" <?= $past_due_filter == 4 ? 'selected' : ''; ?>>4 Months</option>
                        <option value="5" <?= $past_due_filter == 5 ? 'selected' : ''; ?>>5 Months</option>
                        <option value="6" <?= $past_due_filter == 6 ? 'selected' : ''; ?>>6 Months</option>
                        <option value="7" <?= $past_due_filter == 7 ? 'selected' : ''; ?>>7 Months</option>
                        <option value="8" <?= $past_due_filter == 8 ? 'selected' : ''; ?>>8 Months</option>
                        <option value="9" <?= $past_due_filter == 9 ? 'selected' : ''; ?>>9 Months</option>
                        <option value="10" <?= $past_due_filter == 10 ? 'selected' : ''; ?>>10 Months</option>
                        <option value="11" <?= $past_due_filter == 11 ? 'selected' : ''; ?>>11 Months</option>
                        <option value="12" <?= $past_due_filter == 12 ? 'selected' : ''; ?>>12 Months</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div>
            <div class="card-datatable table-responsive container-fluid  pt-0">   
                <table class="datatables-basic responsive table table-sm">
                    <thead class="text-dark">
                        <tr>
                            <th>Client Name</th>
                            <th>Billing Contact Phone</th>
                            <th>Balance</th>
                            <th>Monthly Recurring Amount</th>
                            <th>Months Past Due</th>
                            <th>Past Due Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $processed_clients = []; // Array to keep track of processed client IDs

                        while ($row = mysqli_fetch_assoc($result_client_balance_report)) {
                            $client_id = sanitizeInput($row['client_id']);
                            $client_name = sanitizeInput($row['client_name']);
                            $contact_phone = sanitizeInput($row['contact_phone']);
                            $balance = getClientBalance($client_id);
                            $monthly_recurring_amount = getClientRecurringInvoicesTotal($client_id);
                            $past_due_balance = getClientPastDueBalance($client_id);
                            $months_past_due = $monthly_recurring_amount > 0 ? ($past_due_balance / $monthly_recurring_amount) : 0;

                            // if number of months past due ends with .0 precision, add .1 to it
                            if (strpos(number_format($months_past_due, 1), ".0") !== false || $balance > $monthly_recurring_amount) {
                                $months_past_due += .1;
                            }

                            // Skip if client is less than 2 months past due
                                // or less than the past due filter
                                // or if past due balance is 0
                                // or if client has already been processed
                            if ($months_past_due < $past_due_filter || $months_past_due < .5 || $past_due_balance == 0 || in_array($client_id, $processed_clients)) {
                                continue;
                            }

                            // Add client to processed clients
                            array_push($processed_clients, $client_id);
                        ?>
                        <tr>
                            <td>
                                <a href="/pages/client/client_statement.php?client_id=<?= $client_id; ?>">
                                    <?= $client_name; ?>
                                </a>
                            </td>
                            <td>
                                <a href="tel:<?= $contact_phone; ?>">
                                    <?= $contact_phone; ?>
                                </a>
                            </td>
                            <td>
                                <?= numfmt_format_currency($currency_format, $balance, $config_currency_code); ?>
                            </td>
                            <td>
                                <?= numfmt_format_currency($currency_format, $monthly_recurring_amount, $config_currency_code); ?>
                            </td>
                            <td>
                                <?= number_format($months_past_due, 1) ?>
                            </td>
                            <td>
                                <?= numfmt_format_currency($currency_format, $past_due_balance, $config_currency_code); ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '/var/www/portal.twe.tech/includes/footer.php';


?>
