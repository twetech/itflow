<?php

require_once "/var/www/portal.twe.tech/includes/config/config.php";
require_once "/var/www/portal.twe.tech/includes/functions/functions.php";

// exchange public token for access token
if (isset($_GET['public_token'])) {
  // recieve public token from front end via body of post request
  $input = file_get_contents('php://input');
  $data = json_decode($input, true);

  $public_token = $data['public_token'];

  error_log("public_token:".$public_token);

  // exchange public token for access token
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://sandbox.plaid.com/item/public_token/exchange',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
        "client_id": "' . $config_plaid_client_id . '",
        "secret": "' . $config_plaid_secret . '",
        "public_token": "' . $public_token . '"
        }',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);

  if ($response === false) {
    // Handle cURL error
    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
  }

  curl_close($curl);

  $response = json_decode($response, true);

  error_log(print_r($response, true));

  $access_token = $response['access_token'];

  // encrypt access token
  $encrypted_access_token = encryptPlaidToken($access_token);

  // store encrypted access token in database
  $sql = "INSERT INTO plaid_access_tokens SET encrypted_access_token = '$encrypted_access_token', client_id = 1";

  if ($mysqli->query($sql) === TRUE) {
    error_log("New record created successfully");
  }
}
