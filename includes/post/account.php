<?php

global $mysqli, $session_name, $session_ip, $session_user_agent, $session_user_id;


global $mysqli, $session_name, $session_ip, $session_user_agent, $session_user_id;


/*
 * ITFlow - GET/POST request handler for account(s) (accounting related)
 */

if (isset($_POST['add_account'])) {
    // Check if the user is an accountant
    validateAccountantRole();

    // Sanitize the input
    $name = sanitizeInput($_POST['name']);
    $opening_balance = floatval($_POST['opening_balance']);
    $currency_code = sanitizeInput($_POST['currency_code']);
    $notes = sanitizeInput($_POST['notes']);
    $type = intval($_POST['type']);

    // Create the account
    createAccount($name, $opening_balance, $currency_code, $notes, $type);

    // Redirect to the accounts page with a success message
    referWithAlert("Account created", "success", "accounts.php");
}

if (isset($_POST['edit_account'])) {
    // Check if the user is an accountant
    validateAccountantRole();

    // Sanitize the input
    $account_id = intval($_POST['account_id']);
    $name = sanitizeInput($_POST['name']);
    $type = intval($_POST['type']);
    $notes = sanitizeInput($_POST['notes']);

    // Edit the account
    editAccount($account_id, $name, $type, $notes);

    // Redirect to the referring page with a success message
    referWithAlert("Account edited", "success");
}

if (isset($_GET['archive_account'])) {
    // Check if the user is an accountant
    validateAccountantRole();

    // Sanitize the input
    $account_id = intval($_GET['archive_account']);

    // Archive the account
    archiveAccount($account_id);

    // Redirect to the accounts page with a success message
    referWithAlert("Account archived", "success", "accounts.php");
}

if (isset($_GET['delete_account'])) {
    // Check if the user is an accountant
    validateAccountantRole();

    // Sanitize the input
    $account_id = intval($_GET['delete_account']);

    // Delete the account
    deleteAccount($account_id);

    // Redirect to the accounts page with a success message
    referWithAlert("Account deleted", "success", "accounts.php");
}

if (isset($_POST['link_plaid_account'])) {
    // Check if the user is an accountant
    validateAccountantRole();

    // Sanitize the input
    $account_id = intval($_POST['account_id']);
    $plaid_id = sanitizeInput($_POST['plaid_id']);

    if (empty($account_id)) {
        referWithAlert("Please select an account", "danger");
    }

    if (empty($plaid_id)) {
        referWithAlert("Invalid Plaid account", "danger");
    }

    // Link the account
    linkPlaidAccount($account_id, $plaid_id);

    // Redirect to the referring page with a success message
    referWithAlert("Account linked", "success");
}

if (isset($_POST['link_transaction'])) {
    // Check if the user is an accountant
    validateAccountantRole();

    // Sanitize the input
    $transaction_id = sanitizeInput($_POST['transaction_id']);

    isset($_POST['payment_id']) ? $payment_id = intval($_POST['payment_id']) : $payment_id = null;
    isset($_POST['expense_id']) ? $expense_id = intval($_POST['expense_id']) : $expense_id = null;

    if (empty($payment_id) && empty($expense_id)) {
        referWithAlert("Please select a payment or expense", "danger");
    }

    if (!empty($payment_id)) {
        // Link the payment
        error_log("POST: Linking transaction to payment");
        error_log("Transaction ID: $transaction_id");
        error_log("Payment ID: $payment_id");
        linkTransactionToPayment($transaction_id, $payment_id);
    } else {
        // Link the expense
        error_log("Linking transaction to expense");
        error_log("Transaction ID: $transaction_id");
        error_log("Expense ID: $expense_id");
        linkTransactionToExpense($transaction_id, $expense_id);
    }
    referWithAlert("Transaction reconciled", "success");
}

