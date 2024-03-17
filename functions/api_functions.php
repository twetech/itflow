<?php

// API related functions

function createAPIKey($secret, $name, $expire, $client) {
    global $mysqli, $session_name, $session_ip, $session_user_agent, $session_user_id;

    mysqli_query($mysqli,"INSERT INTO api_keys SET api_key_name = '$name', api_key_secret = '$secret', api_key_expire = '$expire', api_key_client_id = $client");
    $api_key_id = mysqli_insert_id($mysqli);

    // Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'API', log_action = 'Create', log_description = '$session_name created API Key $name set to expire on $expire', log_ip = '$session_ip', log_user_agent = '$session_user_agent',  log_client_id = $client, log_user_id = $session_user_id, log_entity_id = $api_key_id");

    return $api_key_id;
}

function deleteAPIKey($api_key_id) {
    global $mysqli, $session_name, $session_ip, $session_user_agent, $session_user_id;

    // Get API Key Name
    $row = mysqli_fetch_array(mysqli_query($mysqli,"SELECT * FROM api_keys WHERE api_key_id = $api_key_id"));
    $name = sanitizeInput($row['api_key_name']);

    mysqli_query($mysqli,"DELETE FROM api_keys WHERE api_key_id = $api_key_id");

    // Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'API Key', log_action = 'Delete', log_description = '$session_name deleted API key $name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_user_id = $session_user_id, log_entity_id = $api_key_id");
}