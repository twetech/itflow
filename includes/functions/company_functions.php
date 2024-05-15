<?php

function createCompany($data, $bool = true)
{
    global $mysqli;

    $company_name = sanitizeInput($data['company_name']);
    $company_address = sanitizeInput($data['company_address']);
    $company_city = sanitizeInput($data['company_city']);
    $company_state = sanitizeInput($data['company_state']);
    $company_zip = sanitizeInput($data['company_zip']);
    $company_country = sanitizeInput($data['company_country']);
    $company_phone = sanitizeInput($data['company_phone']);
    $company_email = sanitizeInput($data['company_email']);
    $company_locale = sanitizeInput($data['company_locale']);
    $company_currency = sanitizeInput($data['company_currency']);
    $company_reseller = intval($data['company_reseller']);
    $company_reseller_id = intval($data['company_reseller_id'] ?? 1);

    $sql = mysqli_query($mysqli, "INSERT INTO companies SET
    company_name = '$company_name',
    company_address = '$company_address',
    company_city = '$company_city',
    company_state = '$company_state',
    company_zip = '$company_zip',
    company_country = '$company_country',
    company_phone = '$company_phone',
    company_email = '$company_email',
    company_locale = '$company_locale',
    company_currency = '$company_currency',
    company_reseller = $company_reseller
    ");

    $company_id = mysqli_insert_id($mysqli);

    $reseller_sql = mysqli_query($mysqli, "INSERT INTO reseller_companies SET
    reseller_company_reseller_id = $company_reseller_id,
    reseller_company_company_id = " . $company_id);

    $company_1_settings_sql = mysqli_query($mysqli, "SELECT * FROM settings WHERE company_id = 1");
    $company_1_settings = mysqli_fetch_array($company_1_settings_sql);

    $db_version = $company_1_settings['config_current_database_version'];

          
    if ($sql && $reseller_sql && $company_settings_sql) {
        if ($bool) {
            return true;
        } else {
            return $company_id;
        }
    } else {
        return false;
    }


}

function editCompany($data)
{
    global $mysqli;

    $company_id = intval($data['company_id']);
    $company_name = isset($data['company_name']) ? sanitizeInput($data['company_name']) : false;
    $company_address = isset($data['company_address']) ? sanitizeInput($data['company_address']) : false;
    $company_city = isset($data['company_city']) ? sanitizeInput($data['company_city']) : false;
    $company_state = isset($data['company_state']) ? sanitizeInput($data['company_state']) : false;
    $company_zip = isset($data['company_zip']) ? sanitizeInput($data['company_zip']) : false;
    $company_country = isset($data['company_country']) ? sanitizeInput($data['company_country']) : false;
    $company_phone = isset($data['company_phone']) ? sanitizeInput($data['company_phone']) : false;
    $company_email = isset($data['company_email']) ? sanitizeInput($data['company_email']) : false;
    $company_locale = isset($data['company_locale']) ? sanitizeInput($data['company_locale']) : false;
    $company_currency = isset($data['company_currency']) ? sanitizeInput($data['company_currency']) : false;
    $company_reseller = isset($data['company_reseller']) ? intval($data['company_reseller']) : false;
    $company_discount = isset($data['company_discount']) ? intval($data['company_discount']) : false;

    $sql = "UPDATE companies SET ";
    if ($company_name) {
        $sql .= "company_name = '$company_name', ";
    }
    if ($company_address) {
        $sql .= "company_address = '$company_address', ";
    }
    if ($company_city) {
        $sql .= "company_city = '$company_city', ";
    }
    if ($company_state) {
        $sql .= "company_state = '$company_state', ";
    }
    if ($company_zip) {
        $sql .= "company_zip = '$company_zip', ";
    }
    if ($company_country) {
        $sql .= "company_country = '$company_country', ";
    }
    if ($company_phone) {
        $sql .= "company_phone = '$company_phone', ";
    }
    if ($company_email) {
        $sql .= "company_email = '$company_email', ";
    }
    if ($company_locale) {
        $sql .= "company_locale = '$company_locale', ";
    }
    if ($company_currency) {
        $sql .= "company_currency = '$company_currency', ";
    }
    if ($company_reseller) {
        $sql .= "company_reseller = $company_reseller, ";
    }
    if ($company_discount) {
        $sql .= "company_discount = $company_discount, ";
    }
    $sql = rtrim($sql, ", ");
    $sql .= " WHERE company_id = $company_id";

    $query = mysqli_query($mysqli, $sql);

    if ($query) {
        return true;
    } else {
        return false;
    }
}

function getCompanyTier($company_id, $count = false, $db = false)
{
    // Check if client id is greater than 0
    if ($company_id == 0) {
        return "BROKE";
    }
    global $mysqli;

    if ($db) {
        $sql = mysqli_query($mysqli, "SELECT company_tier_id FROM companies WHERE company_id = $company_id");
        $row = mysqli_fetch_array($sql);
        $tier_id = $row['company_tier_id'];
        return $tier_id;
    } else {
        $sql = mysqli_query($mysqli, "SELECT COUNT(*) as total_clients FROM clients WHERE client_company_id = $company_id");
        $row = mysqli_fetch_array($sql);
        $total_clients = intval($row['total_clients']);
        if ($count) {
            return $total_clients;
        } else {
            // Lookup tiers in database and return the correct tier based on the upper limit, and count
            $sql = mysqli_query($mysqli, "SELECT * FROM reseller_tiers ORDER BY reseller_tier_upper_limit ASC");
            while ($row = mysqli_fetch_array($sql)) {
                $tier_id = $row['reseller_tier_id'];
                $tier_upper_limit = $row['reseller_tier_upper_limit'];
                if ($total_clients <= $tier_upper_limit) {
                    return $tier_id;
                }
            }
            // If no tier is found, return the highest tier
            return $tier_id;
        }        
    }

}

function createFirstCompanyUser($name, $email, $password_raw) {

    global $mysqli;

    $password = password_hash(trim($password_raw), PASSWORD_DEFAULT);


    //Generate master encryption key
    $site_encryption_master_key = randomString();

    //Generate user specific key
    $user_specific_encryption_ciphertext = setupFirstUserSpecificKey(trim($password_raw), $site_encryption_master_key);

    mysqli_query($mysqli,"INSERT INTO users SET user_name = '$name', user_email = '$email', user_password = '$password', user_specific_encryption_ciphertext = '$user_specific_encryption_ciphertext'");
    $user_id = mysqli_insert_id($mysqli);


    mkdirMissing("/var/www/nestogy.io/uploads/users/" . $user_id . "/");

    //Check to see if a file is attached
    if ($_FILES['file']['tmp_name'] != '') {

        // get details of the uploaded file
        $file_error = 0;
        $file_tmp_path = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        $file_extension = strtolower(end(explode('.',$_FILES['file']['name'])));

        // sanitize file-name
        $new_file_name = md5(time() . $file_name) . '.' . $file_extension;

        // check if file has one of the following extensions
        $allowed_file_extensions = array('jpg', 'gif', 'png');

        if (in_array($file_extension,$allowed_file_extensions) === false) {
            $file_error = 1;
        }

        //Check File Size
        if ($file_size > 2097152) {
            $file_error = 1;
        }

        if ($file_error == 0) {
            // directory in which the uploaded file will be moved
            $upload_file_dir = "uploads/users/" . $user_id . "/";
            $dest_path = $upload_file_dir . $new_file_name;

            move_uploaded_file($file_tmp_path, $dest_path);

            //Set Avatar
            mysqli_query($mysqli,"UPDATE users SET user_avatar = '$new_file_name' WHERE user_id = $user_id");

            $_SESSION['alert_message'] = 'File successfully uploaded.';
        } else {

            $_SESSION['alert_message'] = 'There was an error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
        }
    }

    //Create Settings
    mysqli_query($mysqli,"INSERT INTO user_settings SET user_id = $user_id, user_role = 3");
    return $user_id;
}

function linkUserCompany($user_id, $company_id, $unlink = false)
{
    global $mysqli;

    if ($unlink) {
        // Delete any matching records
        $sql = mysqli_query($mysqli, "DELETE FROM user_companies WHERE user_company_user_id = $user_id AND user_company_company_id = $company_id");
    } else {
        // Insert the record
        $sql = mysqli_query($mysqli, "INSERT INTO user_companies SET user_company_user_id = $user_id, user_company_company_id = $company_id");
    }

    if ($sql) {
        return true;
    } else {
        return false;
    }
}

function getCompanyMonthly($company_id)
{

    $count = getCompanyTier($company_id, true);
    $tier_id = getCompanyTier($company_id);

    global $mysqli;

    $sql = mysqli_query($mysqli, "SELECT * FROM reseller_tiers 
    LEFT JOIN reseller_subscriptions ON reseller_tier_subscription_id = reseller_subscription_id
    WHERE reseller_tier_id = $tier_id");

    $row = mysqli_fetch_array($sql);
    $reseller_subscription_price = $row['reseller_subscription_price'];
    $company_discount_sql = mysqli_query($mysqli, "SELECT company_discount FROM companies WHERE company_id = $company_id");
    $company_discount_row = mysqli_fetch_array($company_discount_sql);
    $company_discount = $company_discount_row['company_discount'];
    //Assume monthly discount is a percentage, stored as a whole number
    $monthly_discount = $reseller_subscription_price * ($company_discount / 100);
    $monthly_rate = $reseller_subscription_price - $monthly_discount;
    $monthly = $monthly_rate * $count;

    error_log("Company ID: " . $company_id . " Tier ID: " . $tier_id . " Tier Count: " . $count . " Monthly Rate: " . $monthly_rate . " Monthly: " . $monthly);
    return $monthly;
}

function getCompanyBalance($company_id)
{
    global $mysqli;

    $sql = mysqli_query($mysqli, "SELECT * FROM invoices
    LEFT JOIN payments ON invoice_id = payment_invoice_id
    WHERE invoice_company_id = $company_id AND (invoice_status != 'Paid' OR invoice_status != 'Draft')"); 
    $row = mysqli_fetch_array($sql);

    $balance = 0;
    while ($row = mysqli_fetch_array($sql)) {
        $invoice_amount = $row['invoice_amount'];
        $payment_amount = $row['payment_amount'];
        $invoice_discount_amount = $row['invoice_discount_amount'];

        $balance += $invoice_amount - $payment_amount - $invoice_discount_amount;
    }

    return $balance;
}

function companyCanCreateClient($company_id)
{
    $tier_id = getCompanyTier($company_id, false, true);
    global $mysqli;

    $sql = mysqli_query($mysqli, "SELECT * FROM reseller_tiers WHERE reseller_tier_id = $tier_id");
    $row = mysqli_fetch_array($sql);
    $reseller_tier_max_clients = $row['reseller_tier_upper_limit'];
    $client_count = getCompanyTier($company_id, true);

    if ($client_count < $reseller_tier_max_clients) {
        return true;
    } else {
        return false;
    }

}