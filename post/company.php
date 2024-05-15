<?php 

global $mysqli, $session_name, $session_ip, $session_user_agent, $session_user_id;


if (isset($_POST['add_company'])) {
    $company_name = sanitizeInput($_POST['company_name']);
    $company_address = sanitizeInput($_POST['company_address']);
    $company_city = sanitizeInput($_POST['company_city']);
    $company_state = sanitizeInput($_POST['company_state']);
    $company_zip = sanitizeInput($_POST['company_zip']);
    $company_country = sanitizeInput($_POST['company_country']);
    $company_phone = sanitizeInput($_POST['company_phone']);
    $company_email = sanitizeInput($_POST['company_email']);
    $company_locale = sanitizeInput($_POST['company_locale']);
    $company_currency = sanitizeInput($_POST['company_currency']);
    $company_reseller = intval($_POST['company_reseller']);
    $company_reseller_id = intval($_POST['reseller_id']);

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
    if ($sql) {
        $company_id = mysqli_insert_id($mysqli);
        $alert = 'Company added successfully';
        referWithAlert($alert, '/reseller/company_overview.php?company_id=' . $company_id);

        // Add the company to the reseller_companies table
        $sql = mysqli_query($mysqli, "INSERT INTO reseller_companies SET
        reseller_company_reseller_id = $company_reseller_id,
        reseller_company_company_id = $company_id
        ");

    } else {
        $alert = 'Error adding company';
        referWithAlert($alert, '/reseller/companies.php');
    }
}

if (isset($_POST['edit_company'])) {
    $data['company_id'] = intval($_POST['company_id']);
    $data['company_name'] = sanitizeInput($_POST['company_name']);
    $data['company_address'] = sanitizeInput($_POST['company_address']);
    $data['company_city'] = sanitizeInput($_POST['company_city']);
    $data['company_state'] = sanitizeInput($_POST['company_state']);
    $data['company_zip'] = sanitizeInput($_POST['company_zip']);
    $data['company_country'] = sanitizeInput($_POST['company_country']);
    $data['company_phone'] = sanitizeInput($_POST['company_phone']);
    $data['company_email'] = sanitizeInput($_POST['company_email']);
    $data['company_locale'] = sanitizeInput($_POST['company_locale']);
    $data['company_currency'] = sanitizeInput($_POST['company_currency']);
    $data['company_reseller'] = intval($_POST['company_reseller']);
    $data['company_reseller_id'] = intval($_POST['reseller_id']);

    $result = editCompany($data);
    if ($result) {
        $alert = 'Company updated successfully';
        referWithAlert($alert, '/reseller/companies.php');
    } else {
        $alert = 'Error updating company';
        referWithAlert($alert, '/reseller/companies.php');
    }
}

if (isset($_POST['archive_company'])) {
    $company_id = intval($_POST['company_id']);
    $sql = mysqli_query($mysqli, "UPDATE companies SET
    company_archived = 1
    WHERE company_id = $company_id
    ");
    if ($sql) {
        $alert = 'Company archived successfully';
        referWithAlert($alert, '/reseller/companies.php');
    } else {
        $alert = 'Error archiving company';
        referWithAlert($alert, '/reseller/companies.php');
    }
}
