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
                <input type="checkbox" onchange="this.form.submit()" class="custom-control-input" id="customSwitch1" name="enable_financial" value="1" <?php if ($user_config_dashboard_financial_enable == 1) {
                                                                                                                                                            echo "checked";
                                                                                                                                                        } ?>>
                <label class="custom-control-label" for="customSwitch1">Toggle Financial</label>
            </div>
        <?php } ?>

        <?php if ($session_user_role >= 2 && $config_module_enable_ticketing == 1) { ?>
            <div class="custom-control custom-switch">
                <input type="checkbox" onchange="this.form.submit()" class="custom-control-input" id="customSwitch2" name="enable_technical" value="1" <?php if ($user_config_dashboard_technical_enable == 1) {
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
                            <canvas class="js-doughnut-chart chartjs-render-monitor" width="140" height="140" data-set="[65, 35]" data-colors="['#2972fa','#f6f9fc']" style="display: block; height: 70px; width: 70px;">
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
                        <div class="u-doughnut u-doughnut--70 mr-3 mr-xl-2">
                            <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <canvas class="js-doughnut-chart chartjs-render-monitor" width="140" height="140" data-set="[35, 65]" data-colors="['#fab633','#f6f9fc']" style="display: block; height: 70px; width: 70px;"></canvas>

                            <div class="u-doughnut__label text-warning"><?php echo $num_expenses; ?></div>
                        </div>

                        <div class="media-body">
                            <h5 class="h6 text-muted text-uppercase mb-2">
                                Expenses <?php if ($total_expenses < $last_expenses) {
                                                echo $expense_arrow_down;
                                            } else {
                                                echo $expense_arrow_up;
                                            } ?>
                            </h5>
                            <span class="h2 mb-0"><?php echo numfmt_format_currency($currency_format, $total_expenses, "$session_company_currency"); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3 mb-4">
                <div class="card">
                    <div class="card-body media align-items-center px-xl-3">
                        <div class="u-doughnut u-doughnut--70 mr-3 mr-xl-2">
                            <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
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
                        <div class="u-doughnut u-doughnut--70 mr-3 mr-xl-2">
                            <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
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

    <div class="card mb-4">
        <!-- Card Header -->
        <header class="card-header d-md-flex align-items-center">
            <h2 class="h3 card-header-title">Cash Flow Summary</h2>

            <!-- Nav Tabs -->
            <ul id="overallIncomeTabsControl" class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                <li class="nav-item mr-4">
                    <a class="nav-link active show" href="#cashFlowSummary1" role="tab" aria-selected="true" data-toggle="tab">
                        <span class="d-none d-md-inline">Last</span>
                        7 days
                    </a>
                </li>
                <li class="nav-item mr-4">
                    <a class="nav-link" href="#cashFlowSummary2" role="tab" aria-selected="false" data-toggle="tab">
                        <span class="d-none d-md-inline">Last</span>
                        30 days
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#cashFlowSummary3" role="tab" aria-selected="false" data-toggle="tab">
                        <span class="d-none d-md-inline">Last</span>
                        90 days
                    </a>
                </li>
            </ul>
            <!-- End Nav Tabs -->
        </header>
        <!-- End Card Header -->

        <!-- Card Body -->
        <div class="card-body">
            <div class="tab-content" id="overallIncomeTabs">
                <!-- Tab Content -->
                <div class="tab-pane fade active show" id="cashFlowSummary1" role="tabpanel">
                    <div class="row">
                        <!-- Chart -->
                        <div class="col-md-9 mb-4 mb-md-0" style="min-height: 300px;">
                            <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <canvas class="js-overall-income-chart chartjs-render-monitor" width="1112" height="600" style="display: block; height: 300px; width: 556px;"></canvas>
                        </div>
                        <!-- End Chart -->

                        <div class="col-md-3">
                            <!-- Total Income -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-primary mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Total Income</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-success">
                                        <span>+9.5%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-up ml-2"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="h3 mb-0">$6,400</span>
                            </div>
                            <!-- End Total Income -->

                            <hr>

                            <!-- Total Installs -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-secondary mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Total Installs</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-success">
                                        <span>+7.5%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-up ml-2"></i>
                                        </span>
                                    </div>
                                </div>

                                <span class="h3 mb-0">1,346,600</span>
                            </div>
                            <!-- End Total Installs -->

                            <hr>

                            <!-- Active Users -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-info mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Active Users</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-danger">
                                        <span>-3.5%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-down ml-2"></i>
                                        </span>
                                    </div>
                                </div>

                                <span class="h3 mb-0">896,200</span>
                            </div>
                            <!-- End Active Users -->

                            <hr>

                            <a class="btn btn-block btn-soft-primary" href="#">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- End Tab Content -->

                <!-- Tab Content -->
                <div class="tab-pane fade" id="cashFlowSummary2" role="tabpanel">
                    <div class="row">
                        <!-- Chart -->
                        <div class="col-md-9 mb-4 mb-md-0" style="min-height: 300px;">
                            <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <canvas class="js-overall-income-chart chartjs-render-monitor" width="1980" height="600" style="display: block; height: 300px; width: 990px;"></canvas>
                        </div>
                        <!-- End Chart -->

                        <div class="col-md-3">
                            <!-- Total Income -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-primary mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Total Income</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-success">
                                        <span>+10.4%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-up ml-2"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="h3 mb-0">$48,650</span>
                            </div>
                            <!-- End Total Income -->

                            <hr>

                            <!-- Total Installs -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-secondary mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Total Installs</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-success">
                                        <span>+7.9%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-up ml-2"></i>
                                        </span>
                                    </div>
                                </div>

                                <span class="h3 mb-0">5,169,854</span>
                            </div>
                            <!-- End Total Installs -->

                            <hr>

                            <!-- Active Users -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-info mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Active Users</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-danger">
                                        <span>-2.5%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-down ml-2"></i>
                                        </span>
                                    </div>
                                </div>

                                <span class="h3 mb-0">389,545</span>
                            </div>
                            <!-- End Active Users -->

                            <hr>

                            <a class="btn btn-block btn-soft-primary" href="#">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- End Tab Content -->

                <!-- Tab Content -->
                <div class="tab-pane fade" id="cashFlowSummary3" role="tabpanel">
                    <div class="row">
                        <!-- Chart -->
                        <div class="col-md-9 mb-4 mb-md-0" style="min-height: 300px;">
                            <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <canvas class="js-overall-income-chart chartjs-render-monitor" width="1980" height="600" style="display: block; height: 300px; width: 990px;"></canvas>
                        </div>
                        <!-- End Chart -->

                        <div class="col-md-3">
                            <!-- Total Income -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-primary mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Total Income</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-success">
                                        <span>+12.8%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-up ml-2"></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="h3 mb-0">$112,800</span>
                            </div>
                            <!-- End Total Income -->

                            <hr>

                            <!-- Total Installs -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-secondary mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Total Installs</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-success">
                                        <span>+8.1%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-up ml-2"></i>
                                        </span>
                                    </div>
                                </div>

                                <span class="h3 mb-0">9,151,304</span>
                            </div>
                            <!-- End Total Installs -->

                            <hr>

                            <!-- Active Users -->
                            <div>
                                <div class="media align-items-center">
                                    <div class="media-body d-flex align-items-baseline">
                                        <span class="u-indicator u-indicator--xxs bg-info mr-2"></span>
                                        <h5 class="h6 text-muted text-uppercase mb-1">Active Users</h5>
                                    </div>

                                    <div class="d-flex align-items-center h4 text-danger">
                                        <span>-1.5%</span>
                                        <span class="small">
                                            <i class="fa fa-arrow-down ml-2"></i>
                                        </span>
                                    </div>
                                </div>

                                <span class="h3 mb-0">3252,191</span>
                            </div>
                            <!-- End Active Users -->

                            <hr>

                            <a class="btn btn-block btn-soft-primary" href="#">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- End Tab Content -->
            </div>
        </div>
        <!-- End Card Body -->
    </div>

<?php } ?>

<?php require_once "/var/www/develop.twe.tech/includes/footer.php";
?>
<script>
    (function($) {
        $(document).on('ready', function() {
            $('.js-overall-income-chart').each(function(i, el) {
                var chart = new Chart(el, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Total Income',
                            borderColor: 'rgba(107,21,182,0.6)',
                            backgroundColor: 'rgba(107,21,182,0.6)',
                            data: [
                                <?php
                                for ($month = 1; $month <= 12; $month++) {
                                    $sql_payments = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS payment_amount_for_month FROM payments, invoices WHERE payment_invoice_id = invoice_id AND YEAR(payment_date) = $year AND MONTH(payment_date) = $month");
                                    $row = mysqli_fetch_array($sql_payments);
                                    $payments_for_month = floatval($row['payment_amount_for_month']);

                                    $sql_revenues = mysqli_query($mysqli, "SELECT SUM(revenue_amount) AS revenue_amount_for_month FROM revenues WHERE revenue_category_id > 0 AND YEAR(revenue_date) = $year AND MONTH(revenue_date) = $month");
                                    $row = mysqli_fetch_array($sql_revenues);
                                    $revenues_for_month = floatval($row['revenue_amount_for_month']);

                                    $income_for_month = $payments_for_month + $revenues_for_month;

                                    if ($income_for_month > 0 && $income_for_month > $largest_income_month) {
                                        $largest_income_month = $income_for_month;
                                    }


                                ?>
                                    <?php echo "$income_for_month,"; ?>

                                <?php

                                }

                                ?>
                            ]
                        }, {
                            label: 'LY Total Income',
                            borderColor: 'rgba(41,114,250,0.6)',
                            backgroundColor: 'rgba(41,114,250,0.6)',
                            data: [
                                <?php
                                for ($month = 1; $month <= 12; $month++) {
                                    $sql_payments = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS payment_amount_for_month FROM payments, invoices WHERE payment_invoice_id = invoice_id AND YEAR(payment_date) = $year-1 AND MONTH(payment_date) = $month");
                                    $row = mysqli_fetch_array($sql_payments);
                                    $payments_for_month = floatval($row['payment_amount_for_month']);

                                    $sql_revenues = mysqli_query($mysqli, "SELECT SUM(revenue_amount) AS revenue_amount_for_month FROM revenues WHERE revenue_category_id > 0 AND YEAR(revenue_date) = $year-1 AND MONTH(revenue_date) = $month");
                                    $row = mysqli_fetch_array($sql_revenues);
                                    $revenues_for_month = floatval($row['revenue_amount_for_month']);

                                    $income_for_month = $payments_for_month + $revenues_for_month;

                                    if ($income_for_month > 0 && $income_for_month > $largest_income_month) {
                                        $largest_income_month = $income_for_month;
                                    }


                                ?>
                                    <?php echo "$income_for_month,"; ?>

                                <?php

                                }

                                ?>
                            ]
                        }, {
                            label: 'Projected',
                            borderColor: 'rgba(97,200,167,0.6)',
                            backgroundColor: 'rgba(97,200,167,0.6)',
                            data: [
                                <?php

                                $largest_invoice_month = 0;

                                for ($month = 1; $month <= 12; $month++) {
                                    $sql_projected = mysqli_query($mysqli, "SELECT SUM(invoice_amount) AS invoice_amount_for_month FROM invoices WHERE YEAR(invoice_due) = $year AND MONTH(invoice_due) = $month AND invoice_status NOT LIKE 'Cancelled' AND invoice_status NOT LIKE 'Draft'");
                                    $row = mysqli_fetch_array($sql_projected);
                                    $invoice_for_month = floatval($row['invoice_amount_for_month']);

                                    if ($invoice_for_month > 0 && $invoice_for_month > $largest_invoice_month) {
                                        $largest_invoice_month = $invoice_for_month;
                                    }

                                ?>
                                    <?php echo "$invoice_for_month,"; ?>

                                <?php

                                }

                                ?>
                            ]
                        }, {
                            label: 'Expense',
                            borderColor: 'rgba(250,182,51,0.6)',
                            backgroundColor: 'rgba(250,182,51,0.6)',
                            data: [
                                <?php

                                $largest_expense_month = 0;

                                for ($month = 1; $month <= 12; $month++) {
                                    $sql_expenses = mysqli_query($mysqli, "SELECT SUM(expense_amount) AS expense_amount_for_month FROM expenses WHERE YEAR(expense_date) = $year AND MONTH(expense_date) = $month AND expense_vendor_id > 0");
                                    $row = mysqli_fetch_array($sql_expenses);
                                    $expenses_for_month = floatval($row['expense_amount_for_month']);

                                    if ($expenses_for_month > 0 && $expenses_for_month > $largest_expense_month) {
                                        $largest_expense_month = $expenses_for_month;
                                    }


                                ?>
                                    <?php echo "$expenses_for_month,"; ?>

                                <?php

                                }

                                ?>
                            ]
                        }, {
                            label: 'Profit',
                            borderColor: 'rgba(41,114,250,0.6)',
                            backgroundColor: 'rgba(41,114,250,0.6)',
                            data: [
                                <?php

                                $largest_profit_month = 0;

                                for ($month = 1; $month <= 12; $month++) {
                                    $sql_payments = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS payment_amount_for_month FROM payments, invoices WHERE payment_invoice_id = invoice_id AND YEAR(payment_date) = $year AND MONTH(payment_date) = $month");
                                    $row = mysqli_fetch_array($sql_payments);
                                    $payments_for_month = floatval($row['payment_amount_for_month']);

                                    $sql_revenues = mysqli_query($mysqli, "SELECT SUM(revenue_amount) AS revenue_amount_for_month FROM revenues WHERE revenue_category_id > 0 AND YEAR(revenue_date) = $year AND MONTH(revenue_date) = $month");
                                    $row = mysqli_fetch_array($sql_revenues);
                                    $revenues_for_month = floatval($row['revenue_amount_for_month']);

                                    $income_for_month = $payments_for_month + $revenues_for_month;

                                    $sql_expenses = mysqli_query($mysqli, "SELECT SUM(expense_amount) AS expense_amount_for_month FROM expenses WHERE YEAR(expense_date) = $year AND MONTH(expense_date) = $month AND expense_vendor_id > 0");
                                    $row = mysqli_fetch_array($sql_expenses);
                                    $expenses_for_month = floatval($row['expense_amount_for_month']);

                                    $profit_for_month = $income_for_month - $expenses_for_month;

                                    if ($profit_for_month > 0 && $profit_for_month > $largest_profit_month) {
                                        $largest_profit_month = $profit_for_month;
                                    }

                                ?>
                                    <?php echo "$profit_for_month,"; ?>

                                <?php

                                }

                                ?>
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        elements: {
                            point: {
                                radius: 4
                            },
                            line: {
                                borderWidth: 1
                            }
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    borderDash: [8, 8],
                                    color: '#eaf2f9'
                                },
                                ticks: {
                                    fontFamily: 'Open Sans',
                                    fontColor: '#6e7f94'
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    borderDash: [8, 8],
                                    color: '#eaf2f9'
                                },
                                ticks: {
                                    fontFamily: 'Open Sans',
                                    fontColor: '#6e7f94'
                                }
                            }]
                        },
                        tooltips: {
                            enabled: false,
                            intersect: 0,
                            custom: function(tooltipModel) {
                                // Tooltip Element
                                var tooltipEl = document.getElementById('overallIncomeChartTooltip' + i);

                                // Create element on first render
                                if (!tooltipEl) {
                                    tooltipEl = document.createElement('div');
                                    tooltipEl.id = 'overallIncomeChartTooltip' + i;
                                    tooltipEl.className = 'u-chart-tooltip';
                                    tooltipEl.innerHTML = '<div class="u-tooltip-body"></div>';
                                    document.body.appendChild(tooltipEl);
                                }

                                // Hide if no tooltip
                                if (tooltipModel.opacity === 0) {
                                    tooltipEl.style.opacity = 0;
                                    return;
                                }

                                // Set caret Position
                                tooltipEl.classList.remove('above', 'below', 'no-transform');
                                if (tooltipModel.yAlign) {
                                    tooltipEl.classList.add(tooltipModel.yAlign);
                                } else {
                                    tooltipEl.classList.add('no-transform');
                                }

                                function getBody(bodyItem) {
                                    return bodyItem.lines;
                                }

                                // Set Text
                                if (tooltipModel.body) {
                                    var titleLines = tooltipModel.title || [],
                                        bodyLines = tooltipModel.body.map(getBody),
                                        innerHtml = '<h4 class="u-chart-tooltip__title">';

                                    titleLines.forEach(function(title) {
                                        innerHtml += title;
                                    });

                                    innerHtml += '</h4>';

                                    bodyLines.forEach(function(body, i) {
                                        var colors = tooltipModel.labelColors[i];
                                        innerHtml += '<div class="u-chart-tooltip__value">' + body + '</div>';
                                    });

                                    var tableRoot = tooltipEl.querySelector('.u-tooltip-body');
                                    tableRoot.innerHTML = innerHtml;
                                }

                                // `this` will be the overall tooltip
                                var $self = this,
                                    position = $self._chart.canvas.getBoundingClientRect(),
                                    tooltipWidth = $(tooltipEl).outerWidth(),
                                    tooltipHeight = $(tooltipEl).outerHeight();

                                // Display, position, and set styles for font
                                tooltipEl.style.opacity = 1;
                                tooltipEl.style.left = (position.left + tooltipModel.caretX - tooltipWidth / 2) + 'px';
                                tooltipEl.style.top = (position.top + tooltipModel.caretY - tooltipHeight - 15) + 'px';

                                $(window).on('scroll', function() {
                                    var position = $self._chart.canvas.getBoundingClientRect(),
                                        tooltipWidth = $(tooltipEl).outerWidth(),
                                        tooltipHeight = $(tooltipEl).outerHeight();

                                    // Display, position, and set styles for font
                                    tooltipEl.style.left = (position.left + tooltipModel.caretX - tooltipWidth / 2) + 'px';
                                    tooltipEl.style.top = (position.top + tooltipModel.caretY - tooltipHeight - 15) + 'px';
                                });
                            }
                        }
                    }
                });
            });

            $('.js-doughnut-chart').each(function(i, el) {
                var data = JSON.parse(el.getAttribute('data-set')),
                    colors = JSON.parse(el.getAttribute('data-colors'));

                var chart = new Chart(el, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            backgroundColor: colors,
                            data: data
                        }]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        tooltips: {
                            enabled: false
                        },
                        cutoutPercentage: 87
                    }
                });
            });
        });
    })(jQuery);
</script>