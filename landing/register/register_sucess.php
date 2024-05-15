<?php

if ($link_result == true) {
    echo '<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Congratulations!</h4>
    <p>Your account has been successfully created. You can now login to your account.</p>
    <hr>
    <p class="mb-0">If you have any questions, please contact our support team.</p>
    </div>';
} else {
    echo '<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error!</h4>
    <p>There was an error creating your account. Please contact our support team.</p>
    </div>';
}

?>

<a href="https://nesto.pro/pages/login.php" class="btn btn-primary">Login</a>