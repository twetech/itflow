<?php

require_once "/var/www/portal.twe.tech/includes/inc_all_reports.php";

validateAccountantRole();

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$view = isset($_GET['view']) ? $_GET['view'] : 'quarterly';
$company_currency = getSettingValue('company_currency');


//GET unique years from expenses, payments and revenues
$sql_all_years = mysqli_query($mysqli, "SELECT DISTINCT(YEAR(item_created_at)) AS all_years FROM invoice_items ORDER BY all_years DESC");

$sql_tax = mysqli_query($mysqli, 
    "SELECT `tax_name`
    FROM `taxes`");


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

            <div class="card-datatable table-responsive container-fluid  pt-0">                <table id=responsive class="responsive table table-sm">
                    <thead class="text-dark">
                    <tr>
                        <th>Tax</th>
                        <?php
                        if ($view == 'monthly') {
                            for ($i = 1; $i <= 12; $i++) {
                                echo "<th class='text-right'>" . date('M', mktime(0, 0, 0, $i, 10)) . "</th>";
                            }
                        } else {
                            echo "<th class='text-right'>Jan-Mar</th>";
                            echo "<th class='text-right'>Apr-Jun</th>";
                            echo "<th class='text-right'>Jul-Sep</th>";
                            echo "<th class='text-right'>Oct-Dec</th>";
                        }
                        ?>
                        <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($view == 'monthly') {
                            //get all payments from db
                            $sql = mysqli_query($mysqli, 
                                "SELECT tax_name, payment_date, payment_amount, payment_id, tax_percent
                                FROM payments
                                LEFT JOIN invoices ON payments.payment_invoice_id = invoices.invoice_id
                                LEFT JOIN invoice_items ON invoices.invoice_id = invoice_items.item_invoice_id
                                LEFT JOIN taxes ON invoice_items.item_tax_id = taxes.tax_id
                                WHERE YEAR(payment_date) = $year
                                GROUP BY payment_id;");

                            while ($row = mysqli_fetch_array($sql)) {
                                $tax_name = $row['tax_name'];
                                $payment_date = $row['payment_date'];
                                $payment_amount = $row['payment_amount'];

                                $tax_percent = $row['tax_percent'];

                                if ($tax_name == null) {
                                    $tax_name = 'No Tax';
                                }

                                $payment_month = date('n', strtotime($payment_date));

                                if (isset($tax_collected[$tax_name][$payment_month])) {
                                    $tax_collected[$tax_name][$payment_month]['total_tax_due'] += $payment_amount * ($tax_percent / 100);
                                    $tax_collected[$tax_name][$payment_month]['total_payments'] += $payment_amount;
                                } else {
                                    $tax_collected[$tax_name][$payment_month]['total_tax_due'] = $payment_amount * ($tax_percent / 100);
                                    $tax_collected[$tax_name][$payment_month]['total_payments'] = $payment_amount;
                                }
                            }                         

                        // Get all taxes from db
                        foreach ($tax_collected as $tax_name => $monthly_payments) {
                        
                            echo "<tr>";
                            echo "<td>" . $tax_name . "</td>";

                            if ($view == 'monthly') {
                                for ($i = 1; $i <= 12; $i++) {
                                    if (isset($monthly_payments[$i])) {
                                        ?>
                                        <td class=''>
                                            <div class="">
                                                <?php
                                                echo numfmt_format_currency($currency_format, $monthly_payments[$i]['total_payments'], $company_currency);
                                                ?>
                                            </div>
                                            <div class="small">
                                                <?php
                                                echo numfmt_format_currency($currency_format, $monthly_payments[$i]['total_tax_due'], $company_currency);
                                                ?>
                                            </div>
                                        </td>
                                        <?php
                                    } else {
                                        echo "<td class='text-right'>0</td>";
                                    }
                                }
                            }

                            echo "<td class='text-right'>" . numfmt_format_currency($currency_format, array_sum($monthly_payments), $company_currency) . "</td>";
                        }
                    }


                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php require_once '/var/www/portal.twe.tech/includes/footer.php';

