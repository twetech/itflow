<?php
$date = sanitizeInput($_POST['date']);
$amount = floatval($_POST['amount']);
$account_from = intval($_POST['account_from']);
$account_to = intval($_POST['account_to']);
$notes = sanitizeInput($_POST['notes']);
