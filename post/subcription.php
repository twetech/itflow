<?php

global $mysqli, $session_name, $session_ip, $session_user_agent, $session_user_id;

if (isset($_POST['add_subscription'])) {
    $data['subscription_name'] = sanitizeInput($_POST['subscription_name']);
    $data['subscription_description'] = sanitizeInput($_POST['subscription_description']);
    $data['subscription_price'] = floatval($_POST['subscription_price']);
    $data['subscription_type'] = intval($_POST['subscription_type']);
    $data['reseller_subscription_reseller_id'] = intval($_POST['reseller_id']);

    $return = createSubscription($data);

    referWithAlert("Subscription added successfully", "success", "/pages/reseller/companies.php");
}