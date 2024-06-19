<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php";

$transaction_id = $_GET['transaction_id'];
$transaction_sql = "SELECT * FROM bank_transactions WHERE id = $transaction_id";
$transaction = mysqli_query($mysqli, $transaction_sql);
$transaction = mysqli_fetch_assoc($transaction);
extract($transaction);

$accounts_sql = "SELECT * FROM accounts WHERE plaid_id = '$bank_account_id'";
$accounts = mysqli_query($mysqli, $accounts_sql);
$account = mysqli_fetch_assoc($accounts);
if ($account) {
    extract($account);
}
$amount_upper = $amount * 1.5;
$amount_lower = $amount * 0.5;

// Set Date Upper and Lower Bounds to one month before and after the transaction date
$date_upper = date('Y-m-d', strtotime($date . ' + 1 month'));
$date_lower = date('Y-m-d', strtotime($date . ' - 1 month'));

if ($amount < 0) {
    $amount = abs($amount);
    $amount_upper = abs($amount_upper);
    $amount_lower = abs($amount_lower);

    $payments_sql = "SELECT * FROM payments
    LEFT JOIN invoices ON payments.payment_invoice_id = invoices.invoice_id
    LEFT JOIN clients ON invoices.invoice_client_id = clients.client_id
    WHERE plaid_transaction_id IS NULL
    AND payment_date BETWEEN '$date_lower' AND '$date_upper'
    AND payment_amount BETWEEN $amount_lower AND $amount_upper";
    error_log($payments_sql);
    $payments = mysqli_query($mysqli, $payments_sql);
    $expenses = null;
} else {
    $expenses_sql = "SELECT * FROM expenses WHERE plaid_transaction_id IS NULL
    AND expense_date BETWEEN '$date_lower' AND '$date_upper'
    AND expense_amount BETWEEN $amount_lower AND $amount_upper";
    $expenses = mysqli_query($mysqli, $expenses_sql);
    $payments = null;
}
?>
<div class="modal-header">
    <h5 class="modal-title">Reconcile Transaction</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Transaction Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Transaction Date</td>
                                <td><?php echo $date; ?></td>
                            </tr>
                            <tr>
                                <td>Transaction Account</td>
                                <td><?php echo $name; ?></td>
                            </tr>
                            <tr>
                                <td>Amount</td>
                                <td><?php echo $amount; ?></td>
                            </tr>
                            <tr>
                                <td>Transaction Name</td>
                                <td><?php echo $name; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Reconcile Transaction to Existing Payment or Expense</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                        <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
                            <?php if ($payments) { ?>
                                <select name="payment_id" class="form-select">
                                    <option value="">Select Payment</option>
                                    <?php while ($row = mysqli_fetch_assoc($payments)) { 
                                        extract($row);
                                        ?>
                                        <option value="<?= $payment_id ?>">
                                            <?= $client_name . " - " . $payment_reference ." (". $payment_amount .") ". $payment_date; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            <?php } else { ?>
                                <select name="expense_id" class="form-select">
                                    <option value="">Select Expense</option>
                                    <?php while ($row = mysqli_fetch_assoc($expenses)) {
                                        extract($row);
                                        ?>
                                        <option value="<?= $expense_id ?>">
                                            <?= $expense_description . " - " . $expense_reference ." (". $expense_amount .") ". $expense_date; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary" name="link_transaction">Reconcile Transaction</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Create New Payment or Expense</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <a href="" class="btn btn-label-secondary">Create Expense</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>