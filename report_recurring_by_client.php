<?php

require_once("inc_all_reports.php");
validateAccountantRole();

$sql = mysqli_query($mysqli, "
    SELECT clients.client_name,
        SUM(CASE WHEN recurring.recurring_frequency = 'month' THEN recurring.recurring_amount
            WHEN recurring.recurring_frequency = 'year' THEN recurring.recurring_amount / 12 END) AS recurring_monthly_total
    FROM clients
    LEFT JOIN recurring ON clients.client_id = recurring.recurring_client_id
    WHERE recurring.recurring_status = 1
    GROUP BY clients.client_id
    HAVING recurring_monthly_total > 0
    ORDER BY recurring_monthly_total DESC
");

?>

<div class="card card-dark">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fas fa-fw fa-sync mr-2"></i>Recurring Income By Client</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i>Print</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th>Client</th>
                    <th class="text-right">Monthly Recurring</th>
                </tr>
                </thead>
                <tbody>
                <?php

                while ($row = mysqli_fetch_array($sql)) {
                    $client_id = intval($_row['client_id']);
                    $client_name = htmlentities($row['client_name']);
                    $recurring_monthly_total = floatval($row['recurring_monthly_total']);
                    $recurring_total = $recurring_total + $recurring_monthly_total;
                ?>


                    <tr>
                        <td><?php echo $client_name; ?></td>
                        <td class="text-right"><?php echo numfmt_format_currency($currency_format, $recurring_monthly_total, $session_company_currency); ?></td>
                    </tr>
                    <?php
                }
        
                ?>
                    <tr>
                        <th>Total Monthly Income</th>
                        <th class="text-right"><?php echo numfmt_format_currency($currency_format, $recurring_total, $session_company_currency); ?></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once("footer.php"); ?>
