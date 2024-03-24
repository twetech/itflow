<?php
require_once "/var/www/develop.twe.tech/includes/inc_all.php";

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

//GET unique years from expenses, payments invoices and revenues
$sql_years_select = mysqli_query(
    $mysqli,
    "SELECT YEAR(expense_date) AS all_years FROM expenses
    UNION DISTINCT SELECT YEAR(payment_date) FROM payments
    UNION DISTINCT SELECT YEAR(revenue_date) FROM revenues
    UNION DISTINCT SELECT YEAR(invoice_date) FROM invoices
    UNION DISTINCT SELECT YEAR(ticket_created_at) FROM tickets
    UNION DISTINCT SELECT YEAR(client_created_at) FROM clients
    UNION DISTINCT SELECT YEAR(user_created_at) FROM users
    ORDER BY all_years DESC
"
);

$arrow_down = '<i class="fa fa-arrow-down text-danger ml-1"></i>';
$arrow_up = '<i class="fa fa-arrow-up text-success ml-1"></i>';


if ($month == 13) {
    $last_year = $year - 1;

    $last_income = getMonthlyIncome($last_year, $month);
    $last_receivables = getMonthlyReceivables($last_year, $month);
    $last_profit = getMonthlyProfit($last_year, $month);
    

} elseif ($month == 1) {
    $last_month = 12;
    $last_year = $year - 1;

    $last_income = getMonthlyIncome($last_year, $last_month);
    $last_receivables = getMonthlyReceivables($last_year, $last_month);
    $last_profit = getMonthlyProfit($last_year, $last_month);

} else {
    $last_month = date('m', strtotime('-1 month'));

    $last_income = getMonthlyIncome($year, $last_month);
    $last_receivables = getMonthlyReceivables($year, $last_month);
    $last_profit = getMonthlyProfit($year, $last_month);
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

// 

?>
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
                            echo "selected";
                        } ?> value="<?php echo $i; ?>"><?php echo $month_name; ?></option>
            <?php } ?>
                <option value="13" <?php if ($month == 13) {
                            echo "selected";
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
                            echo "selected";
                        } ?>> <?php echo $year_select; ?></option>

            <?php } ?>
        </select>

        <?php if ($session_user_role == 1 || $session_user_role == 3 && $config_module_enable_accounting == 1) { ?>
            <div class="custom-control custom-switch mr-sm-3">
                <input type="checkbox" onchange="this.form.submit()" class="custom-control-input" id="customSwitch1" name="enable_financial" value="1"
                <?php if ($user_config_dashboard_financial_enable == 1) {
                    echo "checked";
                } ?>>
                <label class="custom-control-label" for="customSwitch1">Toggle Financial</label>
            </div>
        <?php } ?>

        <?php if ($session_user_role >= 2 && $config_module_enable_ticketing == 1) { ?>
            <div class="custom-control custom-switch">
                <input type="checkbox" onchange="this.form.submit()" class="custom-control-input" id="customSwitch2" name="enable_technical" value="1" 
                <?php if ($user_config_dashboard_technical_enable == 1) {
                    echo "checked";
                } ?>>
                <label class="custom-control-label" for="customSwitch2">Toggle Technical</label>
            </div>
        <?php } ?>

    </form>
</div>

<?php

if ($user_config_dashboard_financial_enable == 1) {

    // Enforce accountant / admin role for the financial dashboard
    if ($_SESSION['user_role'] != 3 && $_SESSION['user_role'] != 1) {
        exit('<script type="text/javascript">window.location.href = \'dashboard_technical.php\';</script>');
    }


?>
<div class="card card-body">
    <!-- Icon Cards-->

    <div class="row">
        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card">
                <div class="card-body media align-items-center px-xl-3">
                    <div class="u-doughnut u-doughnut--70 mr-3 mr-xl-2">
                        <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0">
                                </div>
                            </div>
                            <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                </div>
                            </div>
                        </div>
                        <canvas class="js-doughnut-chart chartjs-render-monitor" width="140" height="140" data-set="[65, 35]" 
                            data-colors="['#2972fa','#f6f9fc']" style="display: block; height: 70px; width: 70px;">
                        </canvas>
                        <div class="u-doughnut__label text-info"><?php echo $num_payments; ?></div>
                    </div>

                    <div class="media-body">
                        <h5 class="h6 text-muted text-uppercase mb-2">
                            Income <?php if ($total_income < $last_income) {
                                        echo $arrow_down;
                                    } else {
                                        echo $arrow_up;
                                    } ?>
                        </h5>
                        <span class="h2 mb-0"><?php echo numfmt_format_currency($currency_format, $total_income, "$session_company_currency"); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card">
                <div class="card-body media align-items-center px-xl-3">
                    <div class="u-doughnut u-doughnut--70 mr-3 mr-xl-2"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas class="js-doughnut-chart chartjs-render-monitor" width="140" height="140" data-set="[35, 65]" data-colors="['#fab633','#f6f9fc']" style="display: block; height: 70px; width: 70px;"></canvas>

                        <div class="u-doughnut__label text-warning"><?php echo $num_outstanding_invoices; ?></div>
                    </div>

                    <div class="media-body">
                        <h5 class="h6 text-muted text-uppercase mb-2">
                            Receivables <?php if ($total_receivables < $last_receivables) {
                                            echo $arrow_down;
                                        } else {
                                            echo $arrow_up;
                                        } ?>
                        </h5>
                        <span class="h2 mb-0"><?php echo numfmt_format_currency($currency_format, $total_receivables, "$session_company_currency"); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3 mb-4">
            <div class="card">
                <div class="card-body media align-items-center px-xl-3">
                    <div class="u-doughnut u-doughnut--70 mr-3 mr-xl-2"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                        <canvas class="js-doughnut-chart chartjs-render-monitor" width="140" height="140" data-set="[65, 35]" data-colors="['#2972fa','#f6f9fc']" style="display: block; height: 70px; width: 70px;"></canvas>

                        <div class="u-doughnut__label text-info"><?php echo $total_unbilled_hours; ?></div>
                    </div>

                    <div class="media-body">
                        <h5 class="h6 text-muted text-uppercase mb-2">
                            Profit <?php if ($total_profit < $last_profit) {
                                        echo $arrow_down;
                                    } else {
                                        echo $arrow_up;
                                    } ?>
                        </h5>
                        <span class="h2 mb-0"><?php echo numfmt_format_currency($currency_format, $total_profit, "$session_company_currency"); ?></span>
                    </div>
                </div>
            </div>
        </div>




    </div>
                    

</div> <!-- Card -->

<?php } ?>

<?php require_once "/var/www/develop.twe.tech/includes/footer.php";
?>
<script src="/includes/dist/js/dashboard-page-scripts.js"></script>