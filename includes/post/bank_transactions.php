<?php

if (isset($_GET['sync_transactions'])) {
    // Fetch transactions from plaid

    $result = syncPlaidTransactions();
    error_log("Result: " . $result);

    if($result === "no_access_token") {
        error_log("Post: No access token found");
        referWithAlert("Please link your bank account to sync transactions", "warning", "/pages/admin/plaid.php");
    } elseif($result === "failed") {
        referWithAlert("Failed to sync transactions", "warning", "/pages/reconcile.php");
    } else {
        referWithAlert("Transactions synced successfully", "success", "/pages/reconcile.php");
    }

}