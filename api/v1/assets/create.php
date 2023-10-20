<?php

require_once '../validate_api_key.php';

require_once '../require_post_method.php';


// Parse POST info
require_once 'asset_model.php';


// Default
$insert_id = false;

if (!empty($name) && !empty($client_id)) {
    // Insert into Database
    $insert_sql = mysqli_query($mysqli, "INSERT INTO assets SET asset_name = '$name', asset_description = '$description', asset_type = '$type', asset_make = '$make', asset_model = '$model', asset_serial = '$serial', asset_os = '$os', asset_ip = '$aip', asset_mac = '$mac', asset_uri = '$uri', asset_status = '$status', asset_location_id = $location, asset_vendor_id = $vendor, asset_contact_id = $contact, asset_purchase_date = $purchase_date, asset_warranty_expire = $warranty_expire, asset_install_date = $install_date, asset_notes = '$notes', asset_network_id = $network, asset_client_id = $client_id");

    if ($insert_sql) {
        $insert_id = mysqli_insert_id($mysqli);

        //Logging
        mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Asset', log_action = 'Created', log_description = '$name via API ($api_key_name)', log_ip = '$ip', log_user_agent = '$user_agent', log_client_id = '$client_id'");
        mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'API', log_action = 'Success', log_description = 'Created asset $name via API ($api_key_name)', log_ip = '$ip', log_user_agent = '$user_agent', log_client_id = '$client_id'");
    }
}

// Output
require_once '../create_output.php';

