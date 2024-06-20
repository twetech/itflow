<?php
require_once "/var/www/portal.twe.tech/includes/inc_all_reports.php";

validateAccountantRole();


$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$view = isset($_GET['view']) ? $_GET['view'] : 'monthly';
$company_currency = getSettingValue('company_currency');
$currency_format = numfmt_create('en_US', NumberFormatter::CURRENCY);

// Get unique years from expenses, payments, and revenues
$sql_all_years = mysqli_query($mysqli, "SELECT DISTINCT(YEAR(item_created_at)) AS all_years FROM invoice_items ORDER BY all_years DESC");

// Get all taxes
$sql_tax = mysqli_query($mysqli, "SELECT `tax_name` FROM `taxes`");

$sql = "
    SELECT
        *
    FROM 
        payments
    LEFT JOIN 
        invoices ON payments.payment_invoice_id = invoices.invoice_id
    LEFT JOIN 
        invoice_items ON invoices.invoice_id = invoice_items.item_invoice_id
    LEFT JOIN 
        taxes ON invoice_items.item_tax_id = taxes.tax_id
    LEFT JOIN 
        clients ON invoices.invoice_client_id = clients.client_id
    WHERE
        YEAR(payments.payment_date) = $year
    ORDER BY taxes.tax_name, MONTH(payments.payment_date), taxes.tax_id, clients.client_name, invoices.invoice_id, payments.payment_id, invoice_items.item_id
";

$result = mysqli_query($mysqli, $sql);

$tax_collected = [];
$monthly_fractional_payment = array_fill(1, 12, []);
$monthly_tax_owed = array_fill(1, 12, []);

$total_payments = array_fill(1, 12, 0);
$total_tax_due = array_fill(1, 12, 0);



while ($row = mysqli_fetch_assoc($result)) {
    $month = date('n', strtotime($row['payment_date']));
    $tax_name = $row['tax_name'];


    $item_id = $row['item_id'];
    $invoice_id = $row['invoice_id'];
    $payment_id = $row['payment_id'];
    $invoice_amount = $row['invoice_amount'];
    $payment_amount = $row['payment_amount'];
    $percent_paid = $invoice_amount > 0 ? $payment_amount / $invoice_amount : 0;
    $item_price = $row['item_price'];
    $item_quantity = $row['item_quantity'];
    $item_discount = $row['item_discount'];
    $item_total = ($item_price * $item_quantity) - $item_discount;
    $fractional_payment_amount = $item_total * $percent_paid;
    $tax_rate = $row['tax_percent'];
    $tax_owed = $fractional_payment_amount * $tax_rate / 100;

    $total_fractional_payment = isset($monthly_fractional_payment[$month][$tax_name]) ? $monthly_fractional_payment[$month][$tax_name] : 0;
    $total_tax_owed = isset($monthly_tax_owed[$month][$tax_name]) ? $monthly_tax_owed[$month][$tax_name] : 0;


    $monthly_fractional_payment[$month][$tax_name] = $total_fractional_payment + $fractional_payment_amount;
    $monthly_tax_owed[$month][$tax_name] = $total_tax_owed + $tax_owed;
    

}
?>
<div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fas fa-fw fa-balance-scale mr-2"></i>Collected Tax Summary</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-label-primary d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i>Print</button>
        </div>
    </div>
    <div class="card-body p-0">
        <form class="p-3">
            <select onchange="this.form.submit()" class="form-control" name="year">
                <?php
                while ($row = mysqli_fetch_array($sql_all_years)) {
                    $all_years = intval($row['all_years']);
                    ?>
                    <option <?php if ($year == $all_years) { echo "selected"; } ?> > <?= $all_years; ?></option>
                    <?php
                }
                ?>
            </select>
            <!-- View Selection Dropdown -->
            <select onchange="this.form.submit()" class="form-control" name="view">
                <option value="monthly" <?php if ($view == 'monthly') echo "selected"; ?>>Monthly</option>
                <option value="quarterly" <?php if ($view == 'quarterly') echo "selected"; ?>>Quarterly</option>
            </select>
        </form>
        <div class="card-datatable table-responsive container-fluid pt-0">
            <table id=responsive class="responsive table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Tax</th>
                        <?php
                        if ($view == 'monthly') {
                            for ($i = 1; $i <= 12; $i++) {
                                echo "<th class='text-right'>" . date('M', mktime(0, 0, 0, $i, 10)) . "</th>";
                                //Stop table if month is greater than current month and year is current year
                                if ($i == date('n') && $year == date('Y')) {
                                    break;
                                }
                            }
                        } else {
                            echo "<th class='text-right'>Jan-Mar</th>";
                            echo "<th class='text-right'>Apr-Jun</th>";
                            echo "<th class='text-right'>Jul-Sep</th>";
                            echo "<th class='text-right'>Oct-Dec</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (array_keys($monthly_fractional_payment[1]) as $tax_name) {
                        echo "<tr>";
                        echo "<td><div class='font-weight-bold'>Net " . $tax_name . "</div><div class='small'> Collected Tax</div></td>";

                        if ($view == 'monthly') {
                            for ($i = 1; $i <= 12; $i++) {
                                $row_payments = isset($monthly_fractional_payment[$i][$tax_name]) ? $monthly_fractional_payment[$i][$tax_name] : 0;
                                $row_tax_due = isset($monthly_tax_owed[$i][$tax_name]) ? $monthly_tax_owed[$i][$tax_name] : 0;
                                ?>
                                <td class=''>
                                    <div class="">
                                        <a href="breakdown.php?tax_name=<?php echo urlencode($tax_name); ?>&month=<?php echo $i; ?>&year=<?php echo $year; ?>&type=payments">
                                            <?php echo numfmt_format_currency($currency_format, $row_payments, $company_currency); ?>
                                        </a>
                                    </div>
                                    <div class="small">
                                        <a href="breakdown.php?tax_name=<?php echo urlencode($tax_name); ?>&month=<?php echo $i; ?>&year=<?php echo $year; ?>&type=taxes">
                                            <?php echo numfmt_format_currency($currency_format, $row_tax_due, $company_currency); ?>
                                        </a>
                                    </div>
                                </td>
                                <?php
                                // Add to total payments and tax due for the year
                                $total_payments[$i] += $row_payments;
                                $total_tax_due[$i] += $row_tax_due;
                                // Stop table if month is greater than current month and year is current year
                                if ($i == date('n') && $year == date('Y')) {
                                    break;
                                }
                            }
                        } else {
                            for ($quarter = 1; $quarter <= 4; $quarter++) {
                                $start_month = ($quarter - 1) * 3 + 1;
                                $end_month = $quarter * 3;
                                $quarter_payments = array_slice($monthly_payments, $start_month, 3);
                                $total_payments = array_sum(array_column($quarter_payments, 'total_payments'));
                                $total_tax_due = array_sum(array_column($quarter_payments, 'total_tax_due'));
                                ?>
                                <td class='text-right'>
                                    <div class="">
                                        <a href="breakdown.php?tax_name=<?php echo urlencode($tax_name); ?>&quarter=<?php echo $quarter; ?>&year=<?php echo $year; ?>&type=payments">
                                            <?php echo numfmt_format_currency($currency_format, $total_payments - $total_tax_due, $company_currency); ?>
                                        </a>
                                    </div>
                                    <div class="small">
                                        <a href="breakdown.php?tax_name=<?php echo urlencode($tax_name); ?>&quarter=<?php echo $quarter; ?>&year=<?php echo $year; ?>&type=taxes">
                                            <?php echo numfmt_format_currency($currency_format, $total_tax_due, $company_currency); ?>
                                        </a>
                                    </div>
                                </td>
                                <?php
                            }
                        }
                        echo "</tr>";
                    }

                    // Display total monthly payments and tax row
                    echo "<tr><td><strong>Gross Total Payments Recieved</strong></td>";
                    if ($view == 'monthly') {
                        for ($i = 1; $i <= 12; $i++) {
                            echo "<td class='text-right'>" . numfmt_format_currency($currency_format, $total_payments[$i] + $total_tax_due[$i], $company_currency) . "</td>";
                            // Stop table if month is greater than current month and year is current year
                            if ($i == date('n') && $year == date('Y')) {
                                break;
                            }
                        }
                    } else {
                        for ($quarter = 1; $quarter <= 4; $quarter++) {
                            $start_month = ($quarter - 1) * 3 + 1;
                            $end_month = $quarter * 3;
                            $total_payments = array_sum(array_column(array_slice($monthly_payments, $start_month, 3), 'total_payments'));
                            echo "<td class='text-right'>" . numfmt_format_currency($currency_format, $total_payments, $company_currency) . "</td>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once '/var/www/portal.twe.tech/includes/footer.php'; ?>
