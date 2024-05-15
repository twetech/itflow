<?php    

global $mysqli, $session_name, $session_ip, $session_user_agent, $session_user_id;

function createSubscription($data) {

    global $mysqli;

    $subscription_name = $data['subscription_name'];
    $subscription_description = $data['subscription_description'];
    $subscription_price = $data['subscription_price'];
    $subscription_type = $data['subscription_type'];
    $reseller_subcription_reseller_id = $data['reseller_subscription_reseller_id'];

    $sql = "INSERT INTO reseller_subscriptions SET
            reseller_subscription_name = '$subscription_name',
            reseller_subscription_description = '$subscription_description',
            reseller_subscription_price = $subscription_price,
            reseller_subscription_type = $subscription_type,
            reseller_subscription_reseller_id = $reseller_subcription_reseller_id";
    $result = mysqli_query($mysqli, $sql);
    
    return $result;
}