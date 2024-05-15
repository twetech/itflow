<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php"; ?>

<?php

$reseller_id = isset($_GET['reseller_id']) ? intval($_GET['reseller_id']) : false;

if (!$reseller_id) {
    die("Reseller ID not set");
}

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
    WHERE reseller_companies.reseller_company_reseller_id = $reseller_id
");

$num_rows = mysqli_num_rows($sql);

?>

<div class="modal-header">
    <h5 class="modal-title">Add Company</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="reseller_id" value="<?php echo $reseller_id ?>">

    <div class="form-group
    <?php if (isset($_SESSION['errors']['company_name'])) { echo 'has-error'; } ?>">
        <label>Company Name <strong class="text-danger">*</strong></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
            </div>
            <input type="text" class="form-control" name="company_name" value="<?php echo isset($_SESSION['post']['company_name']) ? $_SESSION['post']['company_name'] : ''; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label>Address</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
            </div>
            <input type="text" class="form-control" name="company_address" value="<?php echo isset($_SESSION['post']['company_address']) ? $_SESSION['post']['company_address'] : ''; ?>">
        </div>
    </div>

    <div class="form-group">
        <label>City</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-city"></i></span>
            </div>
            <input type="text" class="form-control" name="company_city" value="<?php echo isset($_SESSION['post']['company_city']) ? $_SESSION['post']['company_city'] : ''; ?>">
        </div>
    </div>

    <div class="form-group">
        <label>State</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-map-marked-alt"></i></span>
            </div>
            <input type="text" class="form-control" name="company_state" value="<?php echo isset($_SESSION['post']['company_state']) ? $_SESSION['post']['company_state'] : ''; ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Zip</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-map-pin"></i></span>
            </div>
            <input type="text" class="form-control" name="company_zip" value="<?php echo isset($_SESSION['post']['company_zip']) ? $_SESSION['post']['company_zip'] : ''; ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Country</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-flag"></i></span>
            </div>
            <input type="text" class="form-control" name="company_country" value="<?php echo isset($_SESSION['post']['company_country']) ? $_SESSION['post']['company_country'] : ''; ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Phone</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-phone"></i></span>
            </div>
            <input type="text" class="form-control" name="company_phone" value="<?php echo isset($_SESSION['post']['company_phone']) ? $_SESSION['post']['company_phone'] : ''; ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Email</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
            </div>
            <input type="email" class="form-control" name="company_email" value="<?php echo isset($_SESSION['post']['company_email']) ? $_SESSION['post']['company_email'] : ''; ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Locale</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-language"></i></span>
            </div>
            <select class="form-control select2" id='select2' name="company_locale">
                <option value="">- Select a Locale -</option>
                <?php foreach($locales_array as $locale_code => $locale_name) { ?>
                    <option value="<?php echo $locale_code; ?>"><?php echo $locale_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Currency</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-money-bill"></i></span>
            </div>
            <select class="form-control select2" id='select2' name="company_currency">
                <option value="">- Currency -</option>
                <?php foreach($currencies_array as $currency_code => $currency_name) { ?>
                    <option value="<?php echo $currency_code; ?>"><?php echo "$currency_code - $currency_name"; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label>Reseller</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-user-shield"></i></span>
            </div>
            <input type="checkbox" class="form-control" name="company_reseller" value="1">           
        </div>
    </div>
</div>
<div class="modal-footer">
    <button name="add_company" type="submit" class="btn btn-primary">Add Company</button>

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div>
