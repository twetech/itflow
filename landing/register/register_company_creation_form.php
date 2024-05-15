<h5 class="text-center">Company Information</h5>
<form method="post" enctype="multipart/form-data" autocomplete="off" class="formAuthentication">
    <input type="hidden" name="company_creation" value="true">

    <div class="form-group">
        <label>Company Name <strong class="text-danger">*</strong></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-building"></i></span>
            </div>
            <input type="text" class="form-control" name="company_name" id="company_name" value="<?= $company_name?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label>Logo</label>
        <input type="file" class="form-control-file" name="file">
    </div>

    <div class="form-group">
        <label>Address</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
            </div>
            <input type="text" class="form-control" name="company_address" placeholder="Street Address">
        </div>
    </div>

    <div class="form-group">
        <label>City</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-city"></i></span>
            </div>
            <input type="text" class="form-control" name="company_city" placeholder="City">
        </div>
    </div>

    <div class="form-group">
        <label>State / Province</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-flag"></i></span>
            </div>
            <input type="text" class="form-control" name="company_state" placeholder="State or Province">
        </div>
    </div>

    <div class="form-group">
        <label>Postal Code</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fab fa-fw fa-usps"></i></span>
            </div>
            <input type="text" class="form-control" name="company_zip" placeholder="Zip or Postal Code">
        </div>
    </div>

    <div class="form-group">
        <label>Country <strong class="text-danger">*</strong></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-globe-americas"></i></span>
            </div>
            <select class="form-control select2" id='select2' name="company_country" required>
                <option value="">- Country -</option>
                <?php foreach($countries_array as $country_name) { ?>
                    <option><?php echo $country_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Phone</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-phone"></i></span>
            </div>
            <input type="text" class="form-control" name="company_phone" placeholder="Phone Number">
        </div>
    </div>

    <div class="form-group">
        <label>Email</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
            </div>
            <input type="email" class="form-control" name="company_email" value="<?= $email?>" disabled>
        </div>
    </div>

    <div class="form-group">
        <label>Language <strong class="text-danger">*</strong></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-language"></i></span>
            </div>
            <select class="form-control select2" id='select2' name="locale" required>
                <option value="">- Select a Language -</option>
                <?php foreach($locales_array as $locale_code => $locale_name) { ?>
                    <option value="<?php echo $locale_code; ?>"><?php echo $locale_name; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Currency <strong class="text-danger">*</strong></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-money-bill"></i></span>
            </div>
            <select class="form-control select2" id='select2' name="company_currency" required>
                <option value="">- Select a Currency -</option>
                <?php foreach($currencies_array as $currency_code => $currency_name) { ?>
                    <option value="<?php echo $currency_code; ?>"><?php echo "$currency_code - $currency_name"; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <hr>
    <button class="btn btn-primary d-grid w-100" name='register' type="submit">
        Next
    </button>
</form>