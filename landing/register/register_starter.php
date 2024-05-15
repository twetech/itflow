<?php 


if (isset($_POST['tier'])) {
// 2nd step: Create the first user

    // Escape Vars
    $company_name = sanitizeInput($_POST['company_name']);
    $email = sanitizeInput($_POST['email']);

    // Save vars to session
    $_SESSION['company_name'] = $company_name;
    $_SESSION['email'] = $email;
        
    // Show the user creation form
    include 'register_user_creation_form.php';

} else if (isset($_POST['user_creation'])) {
// 3rd step: Create the company

    // Escape Vars
    $name = sanitizeInput($_POST['name']);
    $email = $_SESSION['email'];
    $password = sanitizeInput($_POST['password']);
    $company_name = $_SESSION['company_name'];

    // Create the first user
    $user_id = createFirstCompanyUser($name, $email, $password);
    $_SESSION['user_id'] = $user_id;
    
    // Show the company creation form
    include 'register_company_creation_form.php';

} else if (isset($_POST['company_creation'])) {

    // Escape Vars and assemble array
    $data['company_name'] = $_SESSION['company_name'];
    $data['company_address'] = sanitizeInput($_POST['company_address']);
    $data['company_city'] = sanitizeInput($_POST['company_city']);
    $data['company_state'] = sanitizeInput($_POST['company_state']);
    $data['company_zip'] = sanitizeInput($_POST['company_zip']);
    $data['company_country'] = sanitizeInput($_POST['company_country']);
    $data['company_phone'] = sanitizeInput($_POST['company_phone']);
    $data['company_email'] = $_SESSION['email'];
    $data['company_locale'] = sanitizeInput($_POST['company_locale']);
    $data['company_currency'] = sanitizeInput($_POST['company_currency']);
    $data['company_reseller'] = 0;

    // Create the company
    $company_id = createCompany($data);
    $_SESSION['company_id'] = $company_id;
    
    // Link the user to the company
    $link_result = linkUserCompany($_SESSION['user_id'], $company_id);

    include 'register_sucess.php';

}else if (!isset($_POST['tier'])) {
    // 1st step: Basic Information, and set tier
    include 'register_basic_info_form.php';
}

