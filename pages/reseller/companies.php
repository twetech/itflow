<?php


require "/var/www/nestogy.io/includes/inc_all.php";

$sql = mysqli_query($mysqli, "SELECT
    companies.company_id AS company_id, 
    companies.company_name AS company_name,
    companies.company_country AS company_country,
    companies.company_locale AS company_locale,
    companies.company_currency AS company_currency,
    companies.company_reseller AS company_reseller,
    reseller_companies.reseller_company_reseller_id AS reseller_company_reseller_id
    FROM companies
    LEFT JOIN settings ON settings.company_id = companies.company_id
    LEFT JOIN reseller_companies ON companies.company_id = reseller_companies.reseller_company_company_id
    WHERE reseller_companies.reseller_company_reseller_id = $session_user_company_id
");
$num_rows = mysqli_num_rows($sql);



?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title"><?=ucwords($localization['companies'])?></h4>
        <div class="card-header-action">
            <ul class="list-inline ml-auto mb0">
                <li class="list-inline-item mr3">
                    <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="text-dark loadModalContentBtn" data-modal-file="company_add_modal.php?reseller_id=<?php echo $session_user_company_id; ?>">
                        <i class="fa fa-fw fa-plus mr-2"></i><!-- Add Client -->
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
                        <th><?= ucwords($localization['company name']) ?></th>
                        <th><?= ucwords($localization['balance']) ?></th>
                        <th><?= ucwords($localization['monthly']) ?></th>
                        <th><?= ucwords($localization['tier']) ?></th>
                        <th><?= ucwords($localization['country']) ?></th>
                        <th><?= ucwords($localization['actions']) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($num_rows > 0) {
                        while ($row = mysqli_fetch_array($sql)) {
                            $company_id = $row['company_id'];
                            $company_name = sanitizeInput($row['company_name']);
                            $company_country = sanitizeInput($row['company_country']);
                            $company_locale = sanitizeInput($row['company_locale']);
                            $company_currency = sanitizeInput($row['company_currency']);
                            $company_balance = getCompanyBalance($company_id);
                            $company_monthly = getCompanyMonthly($company_id);

                            if ($company_currency != $session_company_currency) {
                                $company_balance_reseller_currency = convertCurrency($company_balance, $company_currency, $session_company_currency);
                                $company_monthly_reseller_currency = convertCurrency($company_monthly, $company_currency, $session_company_currency);
                            }

                            // Calculate the company tier based on number of clients
                            $company_tier_id = getCompanyTier($company_id);
                            $company_tier_count = getCompanyTier($company_id, true);
                            $company_tier_db = getCompanyTier($company_id, false, true);

                            error_log("Company ID: " . $company_id . " Tier ID: " . $company_tier_id . " Tier Count: " . $company_tier_count);

                            $company_tier_name_sql = mysqli_query($mysqli, "SELECT * FROM reseller_tiers 
                            LEFT JOIN reseller_subscriptions ON reseller_tiers.reseller_tier_subscription_id = reseller_subscriptions.reseller_subscription_id
                            WHERE reseller_tiers.reseller_tier_id = $company_tier_id");
                            $company_tier_name_row = mysqli_fetch_array($company_tier_name_sql);
                            $company_tier = $company_tier_name_row['reseller_subscription_name'];

                            $company_db_tier_sql = mysqli_query($mysqli, "SELECT * FROM reseller_tiers
                            LEFT JOIN reseller_subscriptions ON reseller_tiers.reseller_tier_subscription_id = reseller_subscriptions.reseller_subscription_id
                            WHERE reseller_tiers.reseller_tier_id = $company_tier_db");
                            $company_db_tier_row = mysqli_fetch_array($company_db_tier_sql);
                            $company_db_tier = $company_db_tier_row['reseller_subscription_name'];

                            ?>
                            <tr>
                                <td><span><a href="/pages/reseller/company_overview.php?company_id=<?= $company_id ?>"><?= $company_name ?></a></span></td>
                                <td>
                                    <?php if ($company_currency != $session_company_currency) { ?>
                                        <span class="badge bg-primary"><?= numfmt_format_currency($currency_format, $company_balance_reseller_currency, $session_company_currency) ?></span>
                                        <span class="badge bg-secondary"><?= numfmt_format_currency($currency_format, $company_balance, $company_currency) ?></span>
                                    <?php } else { ?>
                                        <span class="badge bg-primary"><?= numfmt_format_currency($currency_format, $company_balance, $company_currency) ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($company_currency != $session_company_currency) { ?>
                                        <span class="badge bg-primary"><?= numfmt_format_currency($currency_format, $company_monthly, $session_company_currency) ?></span>
                                        <span class="badge bg-secondary"><?= numfmt_format_currency($currency_format, $company_monthly_reseller_currency, $company_currency) ?></span>
                                    <?php } else { ?>
                                        <span class="badge bg-primary"><?= numfmt_format_currency($currency_format, $company_monthly, $company_currency) ?></span>
                                    <?php } ?>
                                </td>
                                <td><?php
                                    if ($company_tier_db != $company_tier_id) {
                                        if ($company_tier_db > $company_tier_id) {
                                            $badge_class = "success";
                                        } else {
                                            $badge_class = "danger";
                                        }
                                        echo "<span class='badge bg-$badge_class'>$company_tier -> DB: $company_db_tier</span>";
                                    } else {
                                        echo "<span class='badge bg-primary'>$company_tier</span>";
                                    }
                                    ?></td>
                                <td><?= $company_country ?></td>
                                <td>
                                    <ul>
                                        <div class="dropdown dropleft text-center">
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="company_edit_modal.php?company_id=<?php echo $company_id; ?>">
                                                    <i class="fas fa-fw fa-edit mr-2"></i><?= ucwords($localization['edit']) ?>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_company=<?php echo $company_id; ?>">
                                                    <i class="fas fa-fw fa-archive mr-2"></i><?= ucwords($localization['archive']) ?>
                                                </a>
                                            </div>
                                        </div>
                                    </ul>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5">No companies found</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
require "/var/www/nestogy.io/includes/footer.php";
