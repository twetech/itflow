<?php
/*
 * Client Portal
 * Landing / Home page for the client portal
 */

header("Content-Security-Policy: default-src 'self' fonts.googleapis.com fonts.gstatic.com");

require_once "/var/www/nestogy.io/includes/inc_portal.php";


?>
<div class="col-md-2 offset-1">
    <a href="ticket_add.php" class="btn btn-label-primary btn-block">New ticket</a>
</div>

<?php require_once "portal_footer.php"; ?>
