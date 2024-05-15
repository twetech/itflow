<?php
require "/var/www/nestogy.io/includes/inc_all.php";

// Count number of companies in each tier
$sql_companies = mysqli_query($mysqli, "SELECT * FROM companies
    LEFT JOIN reseller_companies ON company_id = reseller_company_company_id
    WHERE reseller_company_reseller_id = $session_user_company_id
");
while ($row_companies = mysqli_fetch_array($sql_companies)) {
    $company_tier = getCompanyTier($row_companies['company_id']);
    if (isset($company_tier_count[$company_tier])) {
        $company_tier_count[$company_tier]++;
    } else {
        $company_tier_count[$company_tier] = 1;
    }

}

$sql = mysqli_query($mysqli, "SELECT * FROM reseller_subscriptions
    LEFT JOIN reseller_tiers ON reseller_subscription_id = reseller_tier_subscription_id
    WHERE reseller_subscription_reseller_id = $session_user_company_id
    AND reseller_subscription_status = 'Active'
");
$num_rows = mysqli_num_rows($sql);

?>
<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Subscriptions</h4>
        <div class="card-header-action">
            <ul class="list-inline ml-auto mb0">
                <li class="list-inline-item mr3">
                    <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="text-dark loadModalContentBtn" data-modal-file="subscription_add_modal.php?reseller_id=<?php echo $session_user_company_id; ?>">
                        <i class="fa fa-fw fa-plus mr-2"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-nowrap table-align-middle table-vcenter">
                <thead>
                    <tr>
                        <th>
                            Subscription Name
                        </th>
                        <th>
                            Subscription Price
                        </th>
                        <th>
                            Subscription Upper Limit
                        </th>
                        <th>
                            Number of Companies
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($num_rows > 0) {
                        while ($row = mysqli_fetch_array($sql)) {
                            $subscription_id = $row['reseller_subscription_id'];
                            $subscription_name = sanitizeInput($row['reseller_subscription_name']);
                            $subscription_price = sanitizeInput($row['reseller_subscription_price']);
                            $subscription_upper_limit = sanitizeInput($row['reseller_tier_upper_limit']);
                            ?>
                            <tr>
                                <td>
                                    <?php echo $subscription_name; ?>
                                </td>
                                <td>
                                    <?php echo numfmt_format_currency($currency_format, $subscription_price, $session_company_currency); ?>
                                </td>
                                <td>
                                    <?php echo $subscription_upper_limit; ?>
                                </td>
                                <td>
                                    <?php echo $company_tier_count[$subscription_id]?? 0; ?>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm dropdown dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-fw fa-cog"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="text-dark loadModalContentBtn" data-modal-file="subscription_edit_modal.php?subscription_id=<?php echo $subscription_id; ?>">
                                            Edit
                                        </a>
                                        <a class="dropdown-item" href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="text-dark loadModalContentBtn" data-modal-file="subscription_delete_modal.php?subscription_id=<?php echo $subscription_id; ?>">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="3">
                                    No subscriptions found.
                                </td>
                            </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<?php

require "/var/www/nestogy.io/includes/footer.php";
