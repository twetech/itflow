<?php
require_once "/var/www/portal.twe.tech/includes/inc_all.php";

?>

<div class="card">
    <div class="card-header py-3">
        <h3 class="card-title"><i class="fas fa-fw fa-shield-alt mr-2"></i>Your Password</h3>
    </div>
    <div class="card-body">
        <form action="/post.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">

            <div class="form-group">
                <label>Your New Password <strong class="text-danger">*</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" data-bs-toggle="password" name="new_password" placeholder="Leave blank for no change" autocomplete="new-password" minlength="8" required>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-fw fa-eye"></i></span>
                    </div>
                </div>
            </div>

            <button type="submit" name="edit_your_user_password" class="btn btn-label-primary btn-block mt-3"><i class="fas fa-check mr-2"></i>Save</button>

        </form>
    </div>
</div>

<div class="card">
    <div class="card-header py-3">
        <h3 class="card-title"><i class="fas fa-fw fa-lock mr-2"></i>Mult-Factor Authentication</h3>
    </div>
    <div class="card-body">
        <form action="/post.php" method="post" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">

            <?php if (empty($session_token)) { ?>
                <button type="submit" name="enable_2fa" class="btn btn-success btn-block mt-3"><i class="fa fa-fw fa-lock"></i><br> Enable 2FA</button>
            <?php } else { ?>
                <p>You have set up 2FA. Your QR code is below.</p>
                <button type="submit" name="disable_2fa" class="btn btn-danger btn-block mt-3"><i class="fa fa-fw fa-unlock"></i><br>Disable 2FA</button>
            <?php } ?>

            <center>
                <?php

                require_once '/var/www/portal.twe.tech/includes/rfc6238.php';

                //Generate a base32 Key
                $secretkey = key32gen();

                if (!empty($session_token)) {

                    //Generate QR Code based off the generated key
                    print sprintf('<img src="%s"/>', TokenAuth6238::getBarCodeUrl($session_name, ' ', $session_token, $_SERVER['SERVER_NAME']));

                    echo "<p class='text-secondary'>$session_token</p>";
                }

                ?>
            </center>

            <input type="hidden" name="token" value="<?php echo $secretkey; ?>">

        </form>

        <?php if (!empty($session_token)) { ?>
            <form action="/post.php" method="post" autocomplete="off">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-key"></i></span>
                        </div>
                        <input type="text" class="form-control" inputmode="numeric" pattern="[0-9]*" name="code" placeholder="Verify 2FA Code" required>
                        <div class="input-group-append">
                            <button type="submit" name="verify" class="btn btn-success">Verify</button>
                        </div>
                    </div>
                </div>

            </form>
        <?php } ?>
    </div>

<?php
require_once '/var/www/portal.twe.tech/includes/footer.php';
