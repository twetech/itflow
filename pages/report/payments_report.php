<?php

require_once "/var/www/portal.twe.tech/includes/inc_all_reports.php";

validateTechRole();


if (isset($_GET['year'])) {
    $year = intval($_GET['year']);
} else {
    $year = date('Y');
}

$sql_clients = mysqli_query($mysqli, "SELECT client_id, client_name FROM clients ORDER BY client_name ASC");

?>

<div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fas fa-fw fa-life-ring mr-2"></i>Payments Report</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-label-primary d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i>Print</button>
        </div>
    </div>
    <div class="card-body">
        <?php if (!isset($_GET['client_id'])) { ?>
            <div class="row">
                <div class="col">
                    <form action="payments_report.php" method="get">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <select class="form-control" name="client_id" id="client_id">
                                    <option value="">All Clients</option>
                                    <?php
                                    while ($row = mysqli_fetch_array($sql_clients)) {
                                        $client_id = intval($row['client_id']);
                                        $client_name = nullable_htmlentities($row['client_name']);
                                    ?>
                                        <option value="<?= $client_id ?>" <?= ($client_id == $_GET['client_id']) ? 'selected' : '' ?>><?= $client_name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">Year</label>
                                <select class="form-control" name="year" id="year">
                                    <?php
                                    for ($i = date('Y'); $i >= 2019; $i--) {
                                    ?>
                                        <option value="<?= $i ?>" <?= ($i == $_GET['year']) ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="submit">&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col">
                    <a href="payments_report.php" class="btn btn-primary">Back</a>
                </div>
            </div>


            <?php
            $client_id = intval($_GET['client_id']);
            $sql_client = mysqli_query($mysqli, "SELECT client_name FROM clients WHERE client_id = $client_id");
            $client = mysqli_fetch_array($sql_client);
            $client_name = nullable_htmlentities($client['client_name']);

            ?>
            <div class="row">
                <div class="col">
                    <h4>Payments for <?= $client_name ?> in <?= $year ?></h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Payment Date</th>
                                <th>Payment Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Reference</th>
                                <th>View Invoices</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $client_id = intval($_GET['client_id']);
                            $sql_payments = mysqli_query($mysqli, 
                                "SELECT payment_id, payment_date, SUM(payment_amount) AS total_payment, payment_method, payment_reference
                                FROM payments
                                LEFT JOIN invoices ON payments.payment_invoice_id = invoices.invoice_id
                                WHERE invoices.invoice_client_id = $client_id
                                AND YEAR(payment_date) = $year
                                GROUP BY payment_reference
                                ORDER BY payment_date desc
                            ");

                            foreach ($sql_payments as $payment) {
                                $payment_date = nullable_htmlentities($payment['payment_date']);
                                $payment_amount = nullable_htmlentities($payment['total_payment']);
                                $payment_method = nullable_htmlentities($payment['payment_method']);
                                $payment_reference = nullable_htmlentities($payment['payment_reference']);
                            ?>
                                <tr <?php if (nullable_htmlentities($_GET['payment_reference']) == $payment_reference) { echo 'class="table-primary"';} ?>>
                                    <td><?= $payment_date ?></td>
                                    <td><?= $payment_amount ?></td>
                                    <td><?= $payment_method ?></td>
                                    <td><?= $payment_reference ?></td>
                                    <td>
                                        <a class="loadModalContentBtn btn btn-label-secondary" data-bs-toggle="modal" data-bs-target="#dynamicModal" href="#" data-modal-file="payment_invoices.php?payment_reference=<?= $payment_reference ?>">View Invoices</a>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
require_once '/var/www/portal.twe.tech/includes/footer.php';

