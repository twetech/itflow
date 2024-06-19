<?php
require "/var/www/portal.twe.tech/includes/inc_all.php";
// This page will be used to reconcile bank transactions

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$bank_transactions_sql = "SELECT * FROM bank_transactions ORDER BY date DESC";
$bank_transactions = mysqli_query($mysqli, $bank_transactions_sql);

?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Unreconciled Bank Transactions</h5>
                <a href="/post.php?sync_transactions" class="btn btn-primary">Sync Transactions</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Transaction Date</th>
                            <th>Transaction Account</th>
                            <th>Amount</th>
                            <th>Transaction Name</th>
                            <th>Reconcile</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($bank_transactions)) {
                            echo "<tr>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['account_id'] . "</td>";
                            echo "<td>" . $row['amount'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td><a href='reconcile.php?transaction_id=" . $row['transaction_id'] . "'>Reconcile</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php

require "/var/www/portal.twe.tech/includes/footer.php";
