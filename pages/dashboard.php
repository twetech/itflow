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

$data_keys = ['Income', 'Receivables', 'Profit', 'Expenses', 'Payments', 'Invoices', 'UnbilledHours', 'Markup'];
$percentage_keys = ['Income', 'Receivables', 'Profit', 'Expenses', 'Payments', 'Invoices'];



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

    global $data_keys, $percentage_keys;

    // Determine last month and last year based on current month and year
    if ($month == 1) {
        $last_month = 12;
        $last_year = $year - 1;
    } else {
        $last_month = $month - 1;
        $last_year = $year;
    }

    $data = [
        'last' => [],
        'current' => []
    ];

    foreach ($data_keys as $key) {
        $function_name = 'getMonthly' . $key;
        // Fetch data for last period
        if (in_array($key, $percentage_keys)) {
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

function generateCard($title, $size = "small", $type = "financialNumber", $number = 0, $icon = "dollar-circle") {
    $subtext = "";
    $card_key = str_replace(' ', '', $title);

    switch ($size) {
        case "medium":
            echo '<div class="col-sm-12 col-md-4 col-lg-4 mb-4">';
            break;
        case "large":
            echo '<div class="col-sm-12 col-md-6 mb-4">';
            break;
        default:
            echo '<div class="col-sm-6 col-md-4 col-lg-3 mb-4">';
            break;
    }


    if ($type == "financialNumber") {
        global $currency_format, $financial_data, $session_company_currency, $financial_percentage_changes;

        $valueKey = strtolower($card_key);
    
        if (isset($financial_percentage_changes[$valueKey . '_percentage_display'])){
            $subtext = '<small class="fw-medium">' . $financial_percentage_changes[$valueKey . '_percentage_display'] . '</small>';
        }
        
        $number = numfmt_format_currency($currency_format, $financial_data['current'][$valueKey], $session_company_currency);
    }

echo '  <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <i class="bx bx-'.$icon.'"></i>
                </div>
                <span class="d-block">' . $title . '</span>
                <h4 class="card-title mb-1">' . $number . '</h4>
                '. $subtext .'
            </div>
        </div>
    </div>';
}


$financial_data = getMonthlyData($year, $month);

// Calculate percentages
$financial_percentage_changes = [];
foreach ($percentage_keys as $item) {
    $item = strtolower($item);
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
}


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
<div class="row">
    <div class="col-12">
        <div class="row">
            <?php
                generateCard('Receivables');
                generateCard('Markup', "small", "manual", $financial_data['current']['markup']*100, "credit-card");
                generateCard('Profit');
                generateCard('Expenses');
                generateCard('Income');
                generateCard('Payments', "medium");
                generateCard('Invoices', "medium");
                generateCard('Unbilled Hours', "medium");
            ?>
        </div>
    </div>
</div>

<?php } 

if ($user_config_dashboard_technical_enable == 1) {
    // Enforce technician / admin role for the technical dashboard
    if ($_SESSION['user_role'] < 2) {
        exit('<script type="text/javascript">window.location.href = \'dashboard_financial.php\';</script>');
    }

    $unassigned_tickets = getUnassignedTickets();
    $stale_tickets = getStaleTickets();
    $new_clients = 2;
?>
<div class="row">
    <div class="col-12">
        <div class="row">
            <?php
                //generateCard('Calendar Events');
                generateCard('Unassigned Tickets', "small", "manual", $unassigned_tickets, "support");
                generateCard('Stale Tickets', "small", "manual", $stale_tickets, "circle");
                generateCard('New Clients', "small", "manual", $new_clients, "user");

            ?>
        </div>
    </div>
</div>
<?php } 

require_once '/var/www/develop.twe.tech/includes/footer.php';
?>
