<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php"; ?>

<div class="modal" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <form action="/post.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-fw fa-user-plus mr-2"></i>New User</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
                <div class="modal-body bg-white">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
                    <div class="form-group">
                        <label>Name <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name="name" placeholder="Full Name" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" data-bs-toggle="password" name="password" id="password" placeholder="Enter a Password" autocomplete="new-password" required minlength="8">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fa fa-fw fa-eye"></i></span>
                            </div>
                            <div class="input-group-append">
                                <span class="btn btn-default"><i class="fa fa-fw fa-question" onclick="generatePassword()"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Role <strong class="text-danger">*</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-fw fa-user-shield"></i></span>
                            </div>
                            <select class="form-control select2" id='select2' name="role" required>
                                <option value="">- Role -</option>
                                <option value="3">Administrator</option>
                                <option value="2">Technician</option>
                                <option value="1">Accountant</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Avatar</label>
                        <input type="file" class="form-control-file" accept="image/*;capture=camera" name="file">
                    </div>

                    <div class="form-group" <?php if(empty($config_smtp_host)) { echo "hidden"; } ?>>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="sendEmailCheckBox" name="send_email" value="" checked>
                            <label for="sendEmailCheckBox" class="custom-control-label">
                                Send user e-mail with login details?
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="forceMFACheckBox" name="force_mfa" value=1>
                            <label for="forceMFACheckBox" class="custom-control-label">
                                Force MFA
                            </label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" name="add_user" class="btn btn-label-primary text-bold"><i class="fas fa-check mr-2"></i>Create</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="fas fa-times mr-2"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
