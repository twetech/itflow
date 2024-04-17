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



function getMonthlyData($year, $month) {
    // Determine last month and last year based on current month and year
    if ($month == 1) {
        $last_month = 12;
        $last_year = $year - 1;
    } else {
        $last_month = $month - 1;
        $last_year = $year;
    }

    $data_keys = ['Income', 'Receivables', 'Profit', 'Expenses', 'Payments', 'OutstandingInvoices', 'UnbilledHours'];
    $data = [
        'last' => [],
        'current' => []
    ];

    foreach ($data_keys as $key) {
        $function_name = 'getMonthly' . $key;
        // Fetch data for last period
        if (in_array($key, ['Income', 'Receivables', 'Profit', 'Expenses'])) {
            $data['last'][strtolower($key)] = $function_name($last_year, $last_month);
        }
        // Fetch data for current period
        $data['current'][strtolower($key)] = $function_name($year, $month);
    }

    // Special case for counting expenses in the current period
    $data['current']['num_expenses'] = getMonthlyExpenses($year, $month, true);

    return $data;
}

function calculatePercentageChange($last_value, $current_value) {
    return ($last_value == 0) ? 0 : (($current_value - $last_value) / $last_value) * 100;
}

// Usage
$financial_data = getMonthlyData($year, $month);

// Calculate percentages
$financial_percentage_changes = [];
foreach (['income', 'receivables', 'profit', 'expenses'] as $item) {
    $financial_percentage_changes[$item . '_percentage'] = calculatePercentageChange($financial_data['last'][$item], $financial_data['current'][$item]);
}

$arrow_down = '<i class="bx bx-down-arrow-alt text-danger ml-1"></i>';
$arrow_up = '<i class="bx bx-up-arrow-alt text-success ml-1"></i>';
$expense_arrow_down = '<i class="bx bx-down-arrow-alt text-success ml-1"></i>';
$expense_arrow_up = '<i class="bx bx-up-arrow-alt text-danger ml-1"></i>';

// Format percentages for display and include arrows
foreach ($financial_percentage_changes as $key => $value) {
    $is_positive = $value >= 0;
    $formatted_value = number_format($value, 2) . '%';
    $color_class = $is_positive ? 'text-success' : 'text-danger';

    // Determine the appropriate arrow and color
    if (strpos($key, 'expense') === false) {
        // Regular financial data (not expenses)
        $arrow = $is_positive ? $arrow_up : $arrow_down;
    } else {
        // Expense data has reversed color logic
        $arrow = $is_positive ? $expense_arrow_down : $expense_arrow_up;
    }

    // Combine the number and arrow with matching color
    $financial_percentage_changes[$key . '_display'] = '<span class="' . $color_class . '">' . ($is_positive ? '+' : '') . $formatted_value . '</span> ' . $arrow;
    echo json_encode($financial_data);
}


/**
 * Financial Data Retrieval and Analysis
 *
 * Above is designed to fetch and analyze financial data for a given month and year.
 * It provides functionalities to retrieve the last and current financial data,
 * calculate percentage changes, and format these percentages for display.
 *
 * Functions:
 * - getMonthlyData($year, $month): Fetches financial data for both the last and current periods
 *   based on the provided year and month. Adjusts automatically for year-end transitions.
 *   Returns an associative array with 'last' and 'current' keys, each containing financial data.
 *
 * - calculatePercentageChange($last_value, $current_value): Calculates the percentage change
 *   between the last period's value and the current period's value. Handles division by zero.
 *   Returns the percentage change as a float.
 *
 * Usage:
 * - Call getMonthlyData() with the year and month you are interested in analyzing.
 *   This function returns an array of data including income, receivables, profit, expenses, and more.
 *
 * - To calculate percentage changes between periods for key financial metrics,
 *   use calculatePercentageChange() for each metric. This function helps in understanding the growth or reduction in figures.
 *
 * - The percentage changes can be accessed through the $percentage_changes array, which includes formatted strings
 *   suitable for direct display in user interfaces.
 *
 * Adding New Metrics:
 * - To add new metrics, extend the $data_keys array in getMonthlyData() with the new metric names.
 * - Ensure that a corresponding getter function exists for each new metric (e.g., getMonthlyNewMetric()).
 * - If needed, add new handling logic in calculatePercentageChange() to accommodate specifics of the new metrics (if any).
 * - Update the documentation block to include the new metrics for clarity and maintainability.
 *
 * Note:
 * - All financial data retrieval functions like getMonthlyIncome() must be predefined and accessible within this script.
 * - This script assumes all financial data functions follow a naming convention and have consistent return types.
 */


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

    <div class="col-md-12 col-lg-4">
        <div class="row">
            <div class="col-lg-6 col-md-3 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <i class="bx bx-dollar-circle"></i>
                        </div>
                        <span class="d-block">Income</span>
                        <h4 class="card-title mb-1"><?= numfmt_format_currency($currency_format, $financial_data['current']['income'], $session_company_currency) ?></h4>
                        <small class="fw-medium"><?= $financial_percentage_changes['income_percentage_display'] ?></small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-3 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <i class="bx bx-dollar-circle"></i>
                        </div>
                        <span class="d-block">Receivables</span>
                        <h4 class="card-title mb-1"><?= numfmt_format_currency($currency_format, $financial_data['current']['receivables'], $session_company_currency) ?></h4>
                        <small class="fw-medium"><?= $financial_percentage_changes['receivables_percentage_display'] ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="col-lg-8 mb-4">
    <div class="card">
      <div class="card-body row g-4">
        <div class="col-md-6 pe-md-4 card-separator">
          <div class="card-title d-flex align-items-start justify-content-between">
            <h5 class="mb-0">New Visitors</h5>
            <small>Last Week</small>
          </div>
          <div class="d-flex justify-content-between" style="position: relative;">
            <div class="mt-auto">
              <h2 class="mb-2">23%</h2>
              <small class="text-danger text-nowrap fw-medium"><i class="bx bx-down-arrow-alt"></i> -13.24%</small>
            </div>
            <div id="visitorsChart" style="min-height: 120px;"><div id="apexchartsctcb339z" class="apexcharts-canvas apexchartsctcb339z apexcharts-theme-light" style="width: 200px; height: 120px;"><svg id="SvgjsSvg1279" width="200" height="120" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1281" class="apexcharts-inner apexcharts-graphical" transform="translate(22, 5)"><defs id="SvgjsDefs1280"><linearGradient id="SvgjsLinearGradient1284" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop1285" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop1286" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop1287" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMaskctcb339z"><rect id="SvgjsRect1289" width="172" height="90.157" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskctcb339z"></clipPath><clipPath id="nonForecastMaskctcb339z"></clipPath><clipPath id="gridRectMarkerMaskctcb339z"><rect id="SvgjsRect1290" width="172" height="94.157" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><rect id="SvgjsRect1288" width="12" height="90.157" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient1284)" class="apexcharts-xcrosshairs" y2="90.157" filter="none" fill-opacity="0.9"></rect><g id="SvgjsG1309" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1310" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText1312" font-family="Helvetica, Arial, sans-serif" x="12" y="119.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1313">M</tspan><title>M</title></text><text id="SvgjsText1315" font-family="Helvetica, Arial, sans-serif" x="36" y="119.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1316">T</tspan><title>T</title></text><text id="SvgjsText1318" font-family="Helvetica, Arial, sans-serif" x="60" y="119.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1319">W</tspan><title>W</title></text><text id="SvgjsText1321" font-family="Helvetica, Arial, sans-serif" x="84" y="119.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1322">T</tspan><title>T</title></text><text id="SvgjsText1324" font-family="Helvetica, Arial, sans-serif" x="108" y="119.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1325">F</tspan><title>F</title></text><text id="SvgjsText1327" font-family="Helvetica, Arial, sans-serif" x="132" y="119.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1328">S</tspan><title>S</title></text><text id="SvgjsText1330" font-family="Helvetica, Arial, sans-serif" x="156" y="119.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1331">S</tspan><title>S</title></text></g></g><g id="SvgjsG1334" class="apexcharts-grid"><g id="SvgjsG1335" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1337" x1="0" y1="0" x2="168" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1338" x1="0" y1="18.031399999999998" x2="168" y2="18.031399999999998" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1339" x1="0" y1="36.062799999999996" x2="168" y2="36.062799999999996" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1340" x1="0" y1="54.094199999999994" x2="168" y2="54.094199999999994" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1341" x1="0" y1="72.12559999999999" x2="168" y2="72.12559999999999" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1342" x1="0" y1="90.15699999999998" x2="168" y2="90.15699999999998" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1336" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1344" x1="0" y1="90.157" x2="168" y2="90.157" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1343" x1="0" y1="1" x2="0" y2="90.157" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1291" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG1292" class="apexcharts-series" rel="1" seriesName="seriesx1" data:realIndex="0"><path id="SvgjsPath1296" d="M6 82.157L6 62.094199999999994C6 56.76086666666666 8.666666666666668 54.094199999999994 14 54.094199999999994L10 54.094199999999994C15.333333333333334 54.094199999999994 18 56.76086666666666 18 62.094199999999994L18 62.094199999999994L18 82.157C18 87.49033333333333 15.333333333333334 90.157 10 90.157C10 90.157 14 90.157 14 90.157C8.666666666666668 90.157 6 87.49033333333333 6 82.157C6 82.157 6 82.157 6 82.157 " fill="#666ee81a" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskctcb339z)" pathTo="M 6 82.157L 6 62.094199999999994Q 6 54.094199999999994 14 54.094199999999994L 10 54.094199999999994Q 18 54.094199999999994 18 62.094199999999994L 18 62.094199999999994L 18 82.157Q 18 90.157 10 90.157L 14 90.157Q 6 90.157 6 82.157z" pathFrom="M 6 82.157L 6 82.157L 18 82.157L 18 82.157L 18 82.157L 18 82.157L 18 82.157L 6 82.157" cy="54.094199999999994" cx="30" j="0" val="40" barHeight="36.0628" barWidth="12"></path><path id="SvgjsPath1298" d="M30 82.157L30 12.507850000000005C30 7.174516666666676 32.666666666666664 4.507850000000005 38 4.507850000000005L34 4.507850000000005C39.333333333333336 4.507850000000005 42 7.174516666666676 42 12.507850000000005L42 12.507850000000005L42 82.157C42 87.49033333333333 39.333333333333336 90.157 34 90.157C34 90.157 38 90.157 38 90.157C32.666666666666664 90.157 30 87.49033333333333 30 82.157C30 82.157 30 82.157 30 82.157 " fill="#666ee81a" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskctcb339z)" pathTo="M 30 82.157L 30 12.507850000000005Q 30 4.507850000000005 38 4.507850000000005L 34 4.507850000000005Q 42 4.507850000000005 42 12.507850000000005L 42 12.507850000000005L 42 82.157Q 42 90.157 34 90.157L 38 90.157Q 30 90.157 30 82.157z" pathFrom="M 30 82.157L 30 82.157L 42 82.157L 42 82.157L 42 82.157L 42 82.157L 42 82.157L 30 82.157" cy="4.507850000000005" cx="54" j="1" val="95" barHeight="85.64914999999999" barWidth="12"></path><path id="SvgjsPath1300" d="M54 82.157L54 44.062799999999996C54 38.72946666666666 56.66666666666667 36.062799999999996 62 36.062799999999996L58 36.062799999999996C63.33333333333333 36.062799999999996 66 38.72946666666666 66 44.062799999999996L66 44.062799999999996L66 82.157C66 87.49033333333333 63.33333333333333 90.157 58 90.157C58 90.157 62 90.157 62 90.157C56.66666666666667 90.157 54 87.49033333333333 54 82.157C54 82.157 54 82.157 54 82.157 " fill="#666ee81a" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskctcb339z)" pathTo="M 54 82.157L 54 44.062799999999996Q 54 36.062799999999996 62 36.062799999999996L 58 36.062799999999996Q 66 36.062799999999996 66 44.062799999999996L 66 44.062799999999996L 66 82.157Q 66 90.157 58 90.157L 62 90.157Q 54 90.157 54 82.157z" pathFrom="M 54 82.157L 54 82.157L 66 82.157L 66 82.157L 66 82.157L 66 82.157L 66 82.157L 54 82.157" cy="36.062799999999996" cx="78" j="2" val="60" barHeight="54.0942" barWidth="12"></path><path id="SvgjsPath1302" d="M78 82.157L78 57.586349999999996C78 52.25301666666667 80.66666666666667 49.586349999999996 86 49.586349999999996L82 49.586349999999996C87.33333333333333 49.586349999999996 90 52.25301666666667 90 57.586349999999996L90 57.586349999999996L90 82.157C90 87.49033333333333 87.33333333333333 90.157 82 90.157C82 90.157 86 90.157 86 90.157C80.66666666666667 90.157 78 87.49033333333333 78 82.157C78 82.157 78 82.157 78 82.157 " fill="#666ee81a" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskctcb339z)" pathTo="M 78 82.157L 78 57.586349999999996Q 78 49.586349999999996 86 49.586349999999996L 82 49.586349999999996Q 90 49.586349999999996 90 57.586349999999996L 90 57.586349999999996L 90 82.157Q 90 90.157 82 90.157L 86 90.157Q 78 90.157 78 82.157z" pathFrom="M 78 82.157L 78 82.157L 90 82.157L 90 82.157L 90 82.157L 90 82.157L 90 82.157L 78 82.157" cy="49.586349999999996" cx="102" j="3" val="45" barHeight="40.57065" barWidth="12"></path><path id="SvgjsPath1304" d="M102 82.157L102 17.015699999999995C102 11.682366666666667 104.66666666666666 9.015699999999995 110 9.015699999999995L106 9.015699999999995C111.33333333333334 9.015699999999995 114 11.682366666666667 114 17.015699999999995L114 17.015699999999995L114 82.157C114 87.49033333333333 111.33333333333334 90.157 106 90.157C106 90.157 110 90.157 110 90.157C104.66666666666666 90.157 102 87.49033333333333 102 82.157C102 82.157 102 82.157 102 82.157 " fill="#666ee81a" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskctcb339z)" pathTo="M 102 82.157L 102 17.015699999999995Q 102 9.015699999999995 110 9.015699999999995L 106 9.015699999999995Q 114 9.015699999999995 114 17.015699999999995L 114 17.015699999999995L 114 82.157Q 114 90.157 106 90.157L 110 90.157Q 102 90.157 102 82.157z" pathFrom="M 102 82.157L 102 82.157L 114 82.157L 114 82.157L 114 82.157L 114 82.157L 114 82.157L 102 82.157" cy="9.015699999999995" cx="126" j="4" val="90" barHeight="81.1413" barWidth="12"></path><path id="SvgjsPath1306" d="M126 82.157L126 53.0785C126 47.74516666666666 128.66666666666666 45.0785 134 45.0785L130 45.0785C135.33333333333334 45.0785 138 47.74516666666666 138 53.0785L138 53.0785L138 82.157C138 87.49033333333333 135.33333333333334 90.157 130 90.157C130 90.157 134 90.157 134 90.157C128.66666666666666 90.157 126 87.49033333333333 126 82.157C126 82.157 126 82.157 126 82.157 " fill="rgba(105,108,255,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskctcb339z)" pathTo="M 126 82.157L 126 53.0785Q 126 45.0785 134 45.0785L 130 45.0785Q 138 45.0785 138 53.0785L 138 53.0785L 138 82.157Q 138 90.157 130 90.157L 134 90.157Q 126 90.157 126 82.157z" pathFrom="M 126 82.157L 126 82.157L 138 82.157L 138 82.157L 138 82.157L 138 82.157L 138 82.157L 126 82.157" cy="45.0785" cx="150" j="5" val="50" barHeight="45.0785" barWidth="12"></path><path id="SvgjsPath1308" d="M150 82.157L150 30.539249999999996C150 25.20591666666666 152.66666666666666 22.539249999999996 158 22.539249999999996L154 22.539249999999996C159.33333333333334 22.539249999999996 162 25.20591666666666 162 30.539249999999996L162 30.539249999999996L162 82.157C162 87.49033333333333 159.33333333333334 90.157 154 90.157C154 90.157 158 90.157 158 90.157C152.66666666666666 90.157 150 87.49033333333333 150 82.157C150 82.157 150 82.157 150 82.157 " fill="#666ee81a" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskctcb339z)" pathTo="M 150 82.157L 150 30.539249999999996Q 150 22.539249999999996 158 22.539249999999996L 154 22.539249999999996Q 162 22.539249999999996 162 30.539249999999996L 162 30.539249999999996L 162 82.157Q 162 90.157 154 90.157L 158 90.157Q 150 90.157 150 82.157z" pathFrom="M 150 82.157L 150 82.157L 162 82.157L 162 82.157L 162 82.157L 162 82.157L 162 82.157L 150 82.157" cy="22.539249999999996" cx="174" j="6" val="75" barHeight="67.61775" barWidth="12"></path><g id="SvgjsG1294" class="apexcharts-bar-goals-markers" style="pointer-events: none"><g id="SvgjsG1295" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1297" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1299" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1301" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1303" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1305" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1307" className="apexcharts-bar-goals-groups"></g></g></g><g id="SvgjsG1293" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1345" x1="0" y1="0" x2="168" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1346" x1="0" y1="0" x2="168" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1347" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1348" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1349" class="apexcharts-point-annotations"></g></g><g id="SvgjsG1332" class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)"><g id="SvgjsG1333" class="apexcharts-yaxis-texts-g"></g></g><g id="SvgjsG1282" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 60px;"></div><div class="apexcharts-tooltip apexcharts-theme-light"><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgba(102, 110, 232, 0.1);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
          <div class="resize-triggers"><div class="expand-trigger"><div style="width: 305px; height: 121px;"></div></div><div class="contract-trigger"></div></div></div>
        </div>
        <div class="col-md-6 ps-md-4">
          <div class="card-title d-flex align-items-start justify-content-between">
            <h5 class="mb-0">Activity</h5>
            <small>Last Week</small>
          </div>
          <div class="d-flex justify-content-between" style="position: relative;">
            <div class="mt-auto">
              <h2 class="mb-2">82%</h2>
              <small class="text-success text-nowrap fw-medium"><i class="bx bx-up-arrow-alt"></i> 24.8%</small>
            </div>
            <div id="activityChart" style="min-height: 120px;"><div id="apexchartscyw4qsp" class="apexcharts-canvas apexchartscyw4qsp apexcharts-theme-light" style="width: 220px; height: 120px;"><svg id="SvgjsSvg1350" width="220" height="120" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1352" class="apexcharts-inner apexcharts-graphical" transform="translate(22, 10)"><defs id="SvgjsDefs1351"><clipPath id="gridRectMaskcyw4qsp"><rect id="SvgjsRect1357" width="194" height="83.157" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskcyw4qsp"></clipPath><clipPath id="nonForecastMaskcyw4qsp"></clipPath><clipPath id="gridRectMarkerMaskcyw4qsp"><rect id="SvgjsRect1358" width="192" height="85.157" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient1363" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop1364" stop-opacity="0.8" stop-color="rgba(113,221,55,0.8)" offset="0"></stop><stop id="SvgjsStop1365" stop-opacity="0.25" stop-color="rgba(227,248,215,0.25)" offset="0.85"></stop><stop id="SvgjsStop1366" stop-opacity="0.25" stop-color="rgba(227,248,215,0.25)" offset="1"></stop></linearGradient></defs><line id="SvgjsLine1356" x1="0" y1="0" x2="0" y2="81.157" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="81.157" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG1369" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1370" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText1372" font-family="Helvetica, Arial, sans-serif" x="0" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1373">A1</tspan><title>A1</title></text><text id="SvgjsText1375" font-family="Helvetica, Arial, sans-serif" x="23.5" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1376">A2</tspan><title>A2</title></text><text id="SvgjsText1378" font-family="Helvetica, Arial, sans-serif" x="47" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1379">A3</tspan><title>A3</title></text><text id="SvgjsText1381" font-family="Helvetica, Arial, sans-serif" x="70.5" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1382">A4</tspan><title>A4</title></text><text id="SvgjsText1384" font-family="Helvetica, Arial, sans-serif" x="94" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1385">A5</tspan><title>A5</title></text><text id="SvgjsText1387" font-family="Helvetica, Arial, sans-serif" x="117.5" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1388">A6</tspan><title>A6</title></text><text id="SvgjsText1390" font-family="Helvetica, Arial, sans-serif" x="141" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1391">A7</tspan><title>A7</title></text><text id="SvgjsText1393" font-family="Helvetica, Arial, sans-serif" x="164.5" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1394">A8</tspan><title>A8</title></text><text id="SvgjsText1396" font-family="Helvetica, Arial, sans-serif" x="188" y="110.157" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a1acb8" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1397">A9</tspan><title>A9</title></text></g></g><g id="SvgjsG1400" class="apexcharts-grid"><g id="SvgjsG1401" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1403" x1="0" y1="0" x2="188" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1404" x1="0" y1="16.2314" x2="188" y2="16.2314" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1405" x1="0" y1="32.4628" x2="188" y2="32.4628" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1406" x1="0" y1="48.6942" x2="188" y2="48.6942" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1407" x1="0" y1="64.9256" x2="188" y2="64.9256" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1408" x1="0" y1="81.15700000000001" x2="188" y2="81.15700000000001" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1402" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1410" x1="0" y1="81.157" x2="188" y2="81.157" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1409" x1="0" y1="1" x2="0" y2="81.157" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1359" class="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG1360" class="apexcharts-series" seriesName="seriesx1" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1367" d="M0 81.157L0 66.95452499999999C8.225 66.95452499999999 15.275 56.80989999999999 23.5 56.80989999999999C31.725 56.80989999999999 38.775 68.98344999999999 47 68.98344999999999C55.225 68.98344999999999 62.275 52.75204999999999 70.5 52.75204999999999C78.725 52.75204999999999 85.775 62.896674999999995 94 62.896674999999995C102.225 62.896674999999995 109.275 16.231399999999994 117.5 16.231399999999994C125.725 16.231399999999994 132.775 73.04129999999999 141 73.04129999999999C149.225 73.04129999999999 156.275 26.376025 164.5 26.376025C172.725 26.376025 179.775 46.665274999999994 188 46.665274999999994C188 46.665274999999994 188 46.665274999999994 188 81.157M188 46.665274999999994C188 46.665274999999994 188 46.665274999999994 188 46.665274999999994 " fill="url(#SvgjsLinearGradient1363)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskcyw4qsp)" pathTo="M 0 81.157L 0 66.95452499999999C 8.225 66.95452499999999 15.275 56.80989999999999 23.5 56.80989999999999C 31.725 56.80989999999999 38.775 68.98344999999999 47 68.98344999999999C 55.225 68.98344999999999 62.275 52.75204999999999 70.5 52.75204999999999C 78.725 52.75204999999999 85.775 62.896674999999995 94 62.896674999999995C 102.225 62.896674999999995 109.275 16.231399999999994 117.5 16.231399999999994C 125.725 16.231399999999994 132.775 73.04129999999999 141 73.04129999999999C 149.225 73.04129999999999 156.275 26.376025 164.5 26.376025C 172.725 26.376025 179.775 46.665274999999994 188 46.665274999999994C 188 46.665274999999994 188 46.665274999999994 188 81.157M 188 46.665274999999994z" pathFrom="M -1 97.38839999999999L -1 97.38839999999999L 23.5 97.38839999999999L 47 97.38839999999999L 70.5 97.38839999999999L 94 97.38839999999999L 117.5 97.38839999999999L 141 97.38839999999999L 164.5 97.38839999999999L 188 97.38839999999999"></path><path id="SvgjsPath1368" d="M0 66.95452499999999C8.225 66.95452499999999 15.274999999999999 56.80989999999999 23.5 56.80989999999999C31.725 56.80989999999999 38.775 68.98344999999999 47 68.98344999999999C55.225 68.98344999999999 62.275 52.75204999999999 70.5 52.75204999999999C78.725 52.75204999999999 85.775 62.896674999999995 94 62.896674999999995C102.225 62.896674999999995 109.275 16.231399999999994 117.5 16.231399999999994C125.725 16.231399999999994 132.775 73.04129999999999 141 73.04129999999999C149.225 73.04129999999999 156.275 26.376025 164.5 26.376025C172.725 26.376025 179.775 46.665274999999994 188 46.665274999999994C188 46.665274999999994 188 46.665274999999994 188 46.665274999999994 " fill="none" fill-opacity="1" stroke="#71dd37" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskcyw4qsp)" pathTo="M 0 66.95452499999999C 8.225 66.95452499999999 15.275 56.80989999999999 23.5 56.80989999999999C 31.725 56.80989999999999 38.775 68.98344999999999 47 68.98344999999999C 55.225 68.98344999999999 62.275 52.75204999999999 70.5 52.75204999999999C 78.725 52.75204999999999 85.775 62.896674999999995 94 62.896674999999995C 102.225 62.896674999999995 109.275 16.231399999999994 117.5 16.231399999999994C 125.725 16.231399999999994 132.775 73.04129999999999 141 73.04129999999999C 149.225 73.04129999999999 156.275 26.376025 164.5 26.376025C 172.725 26.376025 179.775 46.665274999999994 188 46.665274999999994" pathFrom="M -1 97.38839999999999L -1 97.38839999999999L 23.5 97.38839999999999L 47 97.38839999999999L 70.5 97.38839999999999L 94 97.38839999999999L 117.5 97.38839999999999L 141 97.38839999999999L 164.5 97.38839999999999L 188 97.38839999999999"></path><g id="SvgjsG1361" class="apexcharts-series-markers-wrap" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle1416" r="0" cx="0" cy="0" class="apexcharts-marker wxrxgsg7f no-pointer-events" stroke="#ffffff" fill="#71dd37" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG1362" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1411" x1="0" y1="0" x2="188" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1412" x1="0" y1="0" x2="188" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1413" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1414" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1415" class="apexcharts-point-annotations"></g><rect id="SvgjsRect1417" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect><rect id="SvgjsRect1418" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect></g><rect id="SvgjsRect1355" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG1398" class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)"><g id="SvgjsG1399" class="apexcharts-yaxis-texts-g"></g></g><g id="SvgjsG1353" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 60px;"></div><div class="apexcharts-tooltip apexcharts-theme-light"><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(113, 221, 55);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light"><div class="apexcharts-xaxistooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
          <div class="resize-triggers"><div class="expand-trigger"><div style="width: 306px; height: 121px;"></div></div><div class="contract-trigger"></div></div></div>
        </div>
      </div>
    </div>
  </div>
  
<?php } ?>

<?php
require_once '/var/www/develop.twe.tech/includes/footer.php';
?>
