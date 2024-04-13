<?php
require_once '/var/www/develop.twe.tech/includes/inc_all.php';

if (isset($_GET['year'])) {
    $year = intval($_GET['year']);
} else {
    $year = date('Y');
}

if (isset($_GET['month'])) {
    $month = intval($_GET['month']);
} else {
    $month = date('m');
}

if (isset($_GET['enable_financial'])) {
    $enable_financial = intval($_GET['enable_financial']);
    mysqli_query($mysqli, "UPDATE user_settings SET user_config_dashboard_financial_enable = $enable_financial WHERE user_id = $session_user_id");
}

if (isset($_GET['enable_technical'])) {
    $enable_technical = intval($_GET['enable_technical']);
    mysqli_query($mysqli, "UPDATE user_settings SET user_config_dashboard_technical_enable = $enable_technical WHERE user_id = $session_user_id");
}

// Fetch User Dashboard Settings
$sql_user_dashboard_settings = mysqli_query($mysqli, "SELECT * FROM user_settings WHERE user_id = $session_user_id");
$row = mysqli_fetch_array($sql_user_dashboard_settings);
$user_config_dashboard_financial_enable = intval($row['user_config_dashboard_financial_enable']);
$user_config_dashboard_technical_enable = intval($row['user_config_dashboard_technical_enable']);

// GET unique years from expenses, payments invoices and revenues
$sql_years_select = mysqli_query(
    $mysqli,
    'SELECT YEAR(expense_date) AS all_years FROM expenses
    UNION DISTINCT SELECT YEAR(payment_date) FROM payments
    UNION DISTINCT SELECT YEAR(revenue_date) FROM revenues
    UNION DISTINCT SELECT YEAR(invoice_date) FROM invoices
    UNION DISTINCT SELECT YEAR(ticket_created_at) FROM tickets
    UNION DISTINCT SELECT YEAR(client_created_at) FROM clients
    UNION DISTINCT SELECT YEAR(user_created_at) FROM users
    ORDER BY all_years DESC
'
);

$arrow_down = '<i class="fa fa-arrow-down text-danger ml-1"></i>';
$arrow_up = '<i class="fa fa-arrow-up text-success ml-1"></i>';
$expense_arrow_down = '<i class="fa fa-arrow-down text-success ml-1"></i>';
$expense_arrow_up = '<i class="fa fa-arrow-up text-danger ml-1"></i>';

if ($month == 13) {
    $last_year = $year - 1;

    $last_income = getMonthlyIncome($last_year, $month);
    $last_receivables = getMonthlyReceivables($last_year, $month);
    $last_profit = getMonthlyProfit($last_year, $month);
    $last_expenses = getMonthlyExpenses($last_year, $month);
} elseif ($month == 1) {
    $last_month = 12;
    $last_year = $year - 1;

    $last_income = getMonthlyIncome($last_year, $last_month);
    $last_receivables = getMonthlyReceivables($last_year, $last_month);
    $last_profit = getMonthlyProfit($last_year, $last_month);
    $last_expenses = getMonthlyExpenses($last_year, $last_month);
} else {
    $last_month = date('m', strtotime('-1 month'));

    $last_income = getMonthlyIncome($year, $last_month);
    $last_receivables = getMonthlyReceivables($year, $last_month);
    $last_profit = getMonthlyProfit($year, $last_month);
    $last_expenses = getMonthlyExpenses($year, $last_month);
}

// Get total income
$total_income = getMonthlyIncome($year, $month);
$num_payments = getMonthlyPayments($year, $month);

// Get total receivables
$total_receivables = getMonthlyReceivables($year, $month);
$num_outstanding_invoices = getMonthlyOutstandingInvoices($year, $month);

// Get total profit
$total_profit = getMonthlyProfit($year, $month);
$total_unbilled_hours = getUnbilledHours($year, $month);

// Get total expenses
$total_expenses = getMonthlyExpenses($year, $month);
$num_expenses = getMonthlyExpenses($year, $month, true);

//

?>
<div class="col-12 mb-4">
    <div class="card card-body">
        <form class="form-inline">
            <input type="hidden" name="enable_financial" value="0">
            <input type="hidden" name="enable_technical" value="0">

            <select onchange="this.form.submit()" class="form-control mr-sm-3 col-sm-2" name="month">
                <?php

                for ($i = 1; $i <= 12; $i++) {
                    $month_name = date('F', mktime(0, 0, 0, $i, 10));
                ?>
                    <option <?php if ($month == $i) {
                                echo 'selected';
                            } ?> value="<?php echo $i; ?>"><?php echo $month_name; ?></option>
                <?php } ?>
                <option value="13" <?php if ($month == 13) {
                                        echo 'selected';
                                    } ?>>All</option>

            </select>

            <select onchange="this.form.submit()" class="form-control mr-sm-3 col-sm-2" name="year">
                <?php

                while ($row = mysqli_fetch_array($sql_years_select)) {
                    $year_select = $row['all_years'];
                    if (empty($year_select)) {
                        $year_select = date('Y');
                    }
                ?>
                    <option <?php if ($year == $year_select) {
                                echo 'selected';
                            } ?>> <?php echo $year_select; ?></option>

                <?php } ?>
            </select>

            <?php if ($session_user_role == 1 || $session_user_role == 3 && $config_module_enable_accounting == 1) { ?>
                <div class="custom-control custom-switch mr-sm-3">
                    <input type="checkbox" onchange="this.form.submit()" class="custom-control-input" id="customSwitch1" name="enable_financial" value="1" <?php if ($user_config_dashboard_financial_enable == 1) {
                                                                                                                                                                echo 'checked';
                                                                                                                                                            } ?>>
                    <label class="custom-control-label" for="customSwitch1">Toggle Financial</label>
                </div>
            <?php } ?>

            <?php if ($session_user_role >= 2 && $config_module_enable_ticketing == 1) { ?>
                <div class="custom-control custom-switch">
                    <input type="checkbox" onchange="this.form.submit()" class="custom-control-input" id="customSwitch2" name="enable_technical" value="1" <?php if ($user_config_dashboard_technical_enable == 1) {
                                                                                                                                                                echo 'checked';
                                                                                                                                                            } ?>>
                    <label class="custom-control-label" for="customSwitch2">Toggle Technical</label>
                </div>
            <?php } ?>

        </form>
    </div>
</div>


<?php

if ($user_config_dashboard_financial_enable == 1) {
    // Enforce accountant / admin role for the financial dashboard
    if ($_SESSION['user_role'] != 3 && $_SESSION['user_role'] != 1) {
        exit('<script type="text/javascript">window.location.href = \'dashboard_technical.php\';</script>');
    }

?>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-1">
                                        <?php echo numfmt_format_currency($currency_format, $total_income, "$session_company_currency"); ?> 
                                        <?php if ($total_income < $last_income) {
                                            echo $arrow_down;
                                        } else {
                                            echo $arrow_up;
                                        } ?></h3>
                                    <p class="mb-0">Income</p>
                                </div>
                                <span class="badge bg-label-secondary rounded p-2 me-sm-4">
                                    <i class="bx bx-user bx-sm"></i>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                <div>
                                    <h3 class="mb-1">
                                        <?php echo numfmt_format_currency($currency_format, $total_expenses, "$session_company_currency"); ?>
                                        <?php if ($total_expenses < $last_expenses) {
                                                echo $expense_arrow_down;
                                            } else {
                                                echo $expense_arrow_up;
                                            } ?></h3>
                                    <p class="mb-0">Expenses</p>
                                </div>
                                <span class="badge bg-label-secondary rounded p-2 me-lg-4">
                                    <i class="bx bx-file bx-sm"></i>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <h3 class="mb-1">
                                        <?php echo numfmt_format_currency($currency_format, $total_receivables, "$session_company_currency"); ?>
                                        <?php if ($total_receivables < $last_receivables) {
                                                echo $arrow_down;
                                            } else {
                                                echo $arrow_up;
                                            } ?></h3>
                                    <p class="mb-0">Receivables</p>
                                </div>
                                <span class="badge bg-label-secondary rounded p-2 me-sm-4">
                                    <i class="bx bx-check-double bx-sm"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="mb-1">
                                        <?php echo numfmt_format_currency($currency_format, $total_profit, "$session_company_currency"); ?>
                                        <?php if ($total_profit < $last_profit) {
                                                echo $arrow_down;
                                            } else {
                                                echo $arrow_up;
                                            } ?></h3>
                                    <p class="mb-0">Profit</p>
                                </div>
                                <span class="badge bg-label-secondary rounded p-2">
                                    <i class="bx bx-error-circle bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php
require_once '/var/www/develop.twe.tech/includes/footer.php';
?>
