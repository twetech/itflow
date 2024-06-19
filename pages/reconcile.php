<?php
require "/var/www/portal.twe.tech/includes/inc_all.php";
// This page will be used to reconcile bank transactions

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$bank_transactions_sql =
    "SELECT * FROM bank_transactions
    LEFT JOIN accounts ON bank_transactions.bank_account_id = accounts.plaid_id
    WHERE reconciled = 0 ORDER BY date DESC";

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
                            //Check if there is a matching payment or expense
                            $plaid_transaction_id = $row['transaction_id'];
                            $payment_check = "SELECT * FROM payments WHERE plaid_transaction_id = '$plaid_transaction_id'";
                            $expense_check = "SELECT * FROM expenses WHERE plaid_transaction_id = '$plaid_transaction_id'";

                            error_log($expense_check);

                            $payment_result = mysqli_query($mysqli, $payment_check);
                            $expense_result = mysqli_query($mysqli, $expense_check);

                            if (mysqli_num_rows($payment_result) > 0 || mysqli_num_rows($expense_result) > 0) {
                                continue;
                            }

                            if (isset($row['account_name'])) {
                                $account_name = $row['account_name'];
                            } else {
                                $account_name = "Unknown <br> <button type='button' class='btn btn-primary loadModalContentBtn' data-bs-toggle='modal' data-bs-target='#dynamicModal' data-modal-file='link_plaid_account.php?plaid_id=" . $row['bank_account_id'] . "'>Link Account</button>";
                            }

                            echo "<tr>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $account_name . "</td>";
                            echo "<td>" . $row['amount'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            ?>
                            <td>
                                <button type="button" class="btn btn-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="reconcile_transaction.php?transaction_id=<?php echo $row['id']; ?>">Reconcile</button>
                            </td>
                            <?php
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
