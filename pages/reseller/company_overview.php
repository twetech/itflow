<?php
if (!isset($_GET['company_id'])) {
    header("Location: /pages/reseller/companies.php");
    exit;
}

require "/var/www/nestogy.io/includes/inc_all.php";

$company_id = intval($_GET['company_id']);

$sql = mysqli_query($mysqli, "SELECT * FROM companies
LEFT JOIN settings ON settings.company_id = companies.company_id
LEFT JOIN reseller_companies ON companies.company_id = reseller_companies.reseller_company_company_id
WHERE reseller_companies.reseller_company_reseller_id = $session_user_company_id AND companies.company_id = $company_id
");

$num_rows = mysqli_num_rows($sql);

if ($num_rows == 0) {
    header("Location: /pages/reseller/companies.php");
    exit;
}

$row = mysqli_fetch_array($sql);

$company_name = sanitizeInput($row['company_name']);
$company_country = sanitizeInput($row['company_country']);
$company_locale = sanitizeInput($row['company_locale']);
$company_currency = sanitizeInput($row['company_currency']);
$company_reseller = $row['company_reseller'] == 1 ? true : false;


?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-header-title">Company Overview</h4>
                <div class="card-header-action">
                    <a href="/pages/reseller/company_edit.php?company_id=<?= $company_id ?>" class="btn btn-primary">Edit Company</a>
                    <a href="/pages/reseller/company_delete.php?company_id=<?= $company_id ?>" class="btn btn-danger">Delete Company</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h5>Company Details</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td>Company Name</td>
                                <td><?= $company_name ?></td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td><?= $company_country ?></td>
                            </tr>
                            <tr>
                                <td>Locale</td>
                                <td><?= $company_locale ?></td>
                            </tr>
                            <tr>
                                <td>Currency</td>
                                <td><?= $company_currency ?></td>
                            </tr>
                            <tr>
                                <td>Reseller</td>
                                <td><?= $company_reseller ? "Yes" : "No" ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

require "/var/www/nestogy.io/includes/footer.php";

