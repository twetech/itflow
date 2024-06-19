<?php

if (isset($_GET['sync_transactions'])) {
    // Fetch transactions from plaid
    syncPlaidTransactions();
    referWithAlert("Bank transactions synced successfully", "success");
}