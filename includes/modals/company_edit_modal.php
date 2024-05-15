<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php"; ?>

<?php

$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : false;

$sql = mysqli_query($mysqli, "SELECT
    * FROM companies
    WHERE company_id = $company_id
");

$row = mysqli_fetch_array($sql);

$company_name = sanitizeInput($row['company_name']);
$company_address = sanitizeInput($row['company_address']);
$company_city = sanitizeInput($row['company_city']);
$company_state = sanitizeInput($row['company_state']);
$company_zip = sanitizeInput($row['company_zip']);
$company_country = sanitizeInput($row['company_country']);
$company_phone = sanitizeInput($row['company_phone']);
$company_email = sanitizeInput($row['company_email']);
$company_locale = sanitizeInput($row['company_locale']);
$company_currency = sanitizeInput($row['company_currency']);
$company_reseller = intval($row['company_reseller']);


if (!$company_id) {
    die("Company ID not set");
}

$num_rows = mysqli_num_rows($sql);

?>

<div class="modal-header">
    <h5 class="modal-title">Edit Company</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="company_id" value="<?php echo $company_id ?>">

    <div class="form-group
    <?php if (isset($_SESSION['errors']['company_name'])) { echo 'has-error'; } ?>">
        <label>Company Name <strong class="text-danger">*</strong></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
            </div>
            <input type="text" class="form-control" name="company_name" value="<?= $company_name ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label>Address</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
            </div>
            <input type="text" class="form-control" name="company_address" value="<?= $company_address ?>">
        </div>
    </div>

    <div class="form-group">
        <label>City</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-city"></i></span>
            </div>
            <input type="text" class="form-control" name="company_city" value="<?= $company_city ?>">
        </div>
    </div>

    <div class="form-group">
        <label>State</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-map-marked-alt"></i></span>
            </div>
            <input type="text" class="form-control" name="company_state" value="<?= $company_state ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Zip</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-map-pin"></i></span>
            </div>
            <input type="text" class="form-control" name="company_zip" value="<?= $company_zip ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Country</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-flag"></i></span>
            </div>
            <input type="text" class="form-control" name="company_country" value="<?= $company_country ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Phone</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-phone"></i></span>
            </div>
            <input type="text" class="form-control" name="company_phone" value="<?= $company_phone ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Email</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
            </div>
            <input type="email" class="form-control" name="company_email" value="<?= $company_email ?>">
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
                    <option <?php if ($company_locale == $locale_code) { echo 'selected'; } ?> value="<?php echo $locale_code; ?>">
                        <?php echo $locale_name; ?>
                    </option>
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
                    <option <?php if ($company_currency == $currency_code) { echo 'selected'; } ?> value="<?php echo $currency_code; ?>">
                        <?php echo "$currency_code - $currency_name"; ?>
                    </option>
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
            <select class="form-control select2" id='select2' name="company_reseller">
                <option value="0" <?php if ($company_reseller == 0) { echo 'selected'; } ?>>No</option>
                <option value="1" <?php if ($company_reseller == 1) { echo 'selected'; } ?>>Yes</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button name="edit_company" type="submit" class="btn btn-primary">Save Changes</button>

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div>
