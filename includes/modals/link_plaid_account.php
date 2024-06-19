<?php
require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php";

$plaid_account_id = $_GET['plaid_id'];
$accounts_sql = "SELECT * FROM accounts WHERE plaid_id IS NULL";
$plaid_accounts_sql = "SELECT * FROM plaid_accounts WHERE plaid_account_id = '$plaid_account_id'";

$accounts = mysqli_query($mysqli, $accounts_sql);
$plaid_accounts = mysqli_query($mysqli, $plaid_accounts_sql);

$row = mysqli_fetch_assoc($plaid_accounts);


$name = $row['plaid_official_name'];
?>
<div class="modal-dialog">
    <div class="modal-content bg-dark">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="fa fa-university"></i>
                Link Account to bank
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <input type="hidden" name="plaid_id" id="plaid_id" value="<?= $plaid_account_id; ?>">

                <div class="col-md-12">
                    <div class="form-group">
                        <p>
                            Link account <strong><?= $name; ?></strong> to a portal account
                        </p>
                        <label for="account_id">Account</label>
                        <select class="form-control" id="account_id" name="account_id">
                            <option value="">Select Account</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($accounts)) {
                                echo "<option value='" . $row['account_id'] . "'>" . $row['account_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <?php // submit button ?>
            <button type="submit" class="btn btn-primary" name="link_plaid_account">Link Account</button>
        </div>
    </div>
</div>