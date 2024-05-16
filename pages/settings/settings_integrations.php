<?php
require_once "/var/www/nestogy.io/includes/inc_all_settings.php";
 ?>
<?php if ($session_company_reseller) { ?>

    <div class="col-<?= $session_company_reseller ? '6' : '12' ?>">
        <div class="card" id="microsoft-azure">
            <div class="card-header py-3">
                <h3 class="card-title"><i class="fas fa-fw fa-plug mr-2"></i>Azure Settings</h3>
            </div>
            <div class="card-body">
                <form action="/post.php" method="post" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">

                    <h4>Client Portal SSO via Microsoft Azure AD</h4>
                    <div class="form-group">
                        <label>MS Azure OAuth App (Client) ID</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name="azure_client_id" placeholder="e721e3b6-01d6-50e8-7f22-c84d951a52e7" value="<?php echo nullable_htmlentities($config_azure_client_id); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>MS Azure OAuth Secret</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" name="azure_client_secret" placeholder="Auto-generated from App Registration" value="<?php echo nullable_htmlentities($config_azure_client_secret); ?>" autocomplete="new-password">
                        </div>
                    </div>

                    <hr>

                    <button type="submit" name="edit_integrations_settings" class="btn btn-label-primary text-bold"></i>Save</button>

                </form>
            </div>
        </div>  
    </div>
    <div class="col-6">
        <div class="card" id="plaid">
            <div class="card-header py-3">
                <h3 class="card-title"><i class="fas fa-fw fa-plug mr-2"></i>Plaid Settings</h3>
            </div>
            <div class="card-body">
                
            </div>  
        </div>
    </div>
<?php } else { ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header py-3">
                <h3 class="card-title"><i class="fas fa-fw fa-plug mr-2"></i>Integrations</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fa fa-fw fa-exclamation-triangle"></i> You do not have permission to view this page.
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<?php require_once '/var/www/nestogy.io/includes/footer.php';

