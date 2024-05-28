<?php
/*
 * Client Portal
 * Ticket detail page
 */

require_once "/var/www/portal.twe.tech/includes/inc_portal.php";

//Initialize the HTML Purifier to prevent XSS
require "/var/www/portal.twe.tech/includes/plugins/htmlpurifier/HTMLPurifier.standalone.php";

$purifier_config = HTMLPurifier_Config::createDefault();
$purifier_config->set('URI.AllowedSchemes', ['data' => true, 'src' => true, 'http' => true, 'https' => true]);
$purifier = new HTMLPurifier($purifier_config);

$allowed_extensions = array('jpg', 'jpeg', 'gif', 'png', 'webp', 'pdf', 'txt', 'md', 'doc', 'docx', 'csv', 'xls', 'xlsx', 'xlsm', 'zip', 'tar', 'gz');

if (isset($_GET['id']) && intval($_GET['id'])) {
    $ticket_id = intval($_GET['id']);

    if ($session_contact_primary == 1 || $session_contact_is_technical_contact) {
        // For a primary / technical contact viewing all tickets
        $ticket_sql = mysqli_query($mysqli, "SELECT * FROM tickets LEFT JOIN users on ticket_assigned_to = user_id WHERE ticket_id = $ticket_id AND ticket_client_id = $session_client_id");
    } else {
        // For a user viewing their own ticket
        $ticket_sql = mysqli_query($mysqli, "SELECT * FROM tickets LEFT JOIN users on ticket_assigned_to = user_id WHERE ticket_id = $ticket_id AND ticket_client_id = $session_client_id AND ticket_contact_id = $session_contact_id");
    }

    $ticket_row = mysqli_fetch_array($ticket_sql);

    if ($ticket_row) {

        $ticket_prefix = nullable_htmlentities($ticket_row['ticket_prefix']);
        $ticket_number = intval($ticket_row['ticket_number']);
        $ticket_status = intval($ticket_row['ticket_status']);
        $ticket_priority = nullable_htmlentities($ticket_row['ticket_priority']);
        $ticket_subject = nullable_htmlentities($ticket_row['ticket_subject']);
        $ticket_details = $purifier->purify($ticket_row['ticket_details']);
        $ticket_assigned_to = nullable_htmlentities($ticket_row['user_name']);
        $ticket_feedback = nullable_htmlentities($ticket_row['ticket_feedback']);

        ?>

        <ol class="breadcrumb d-print-none">
            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="tickets.php">Tickets</a>
            </li>
            <li class="breadcrumb-item active">Ticket <?= $ticket_number; ?></li>
        </ol>

        <div class="card me-2">
            <div class="card-header header-elements">
                <span class="me-2">
                    Ticket <?= $ticket_prefix, $ticket_number ?>
                </span>
                <div class="header-elements ms-auto">
                    <?php if ($ticket_status !== 5) { 
                        error_log("Ticket status: $ticket_status");
                        ?>
                        
                        <a href="portal_post.php?close_ticket=<?= $ticket_id; ?>" class="btn btn-xs btn-outline-success confirm-link"><i class="fas fa-fw fa-check text-success"></i> Close ticket</a>
                    <?php } ?>
                </div>
            </div>

            <div class="card-body">
                <h5><strong>Subject:</strong> <?= $ticket_subject ?></h5>
                <hr>
                <p>
                    <strong>State:</strong> <?= $ticket_status ?>
                    <br>
                    <strong>Priority:</strong> <?= $ticket_priority ?>
                    <br>
                    <?php if (!empty($ticket_assigned_to) && $ticket_status !== 5) { ?>
                        <strong>Assigned to: </strong> <?= $ticket_assigned_to ?>
                    <?php } ?>
                </p>
                <?= $ticket_details ?>
            </div>
        </div>

        <hr>

        <div class="card me-2">
            <div class="card-body">
                <?php if ($ticket_status !== 5) { ?>
                    <form action="portal_post.php" enctype="multipart/form-data" method="post">
                        <div class="row">
                            <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
                            <div class="col-12 form-group">
                                <textarea  class="form-control mb-2" name="comment" placeholder="Add comments.."></textarea>
                            </div>
                            <div class="col-8 form-group">
                                <input type="file" class="form-control-file mb-2" name="file[]" multiple id="fileInput" accept=".jpg, .jpeg, .gif, .png, .webp, .pdf, .txt, .md, .doc, .docx, .odt, .csv, .xls, .xlsx, .ods, .pptx, .odp, .zip, .tar, .gz, .xml, .msg, .json, .wav, .mp3, .ogg, .mov, .mp4, .av1, .ovpn">
                            </div>                            
                            <div class="col-4">
                                <button type="submit" class="btn btn-label-primary mb-2" name="add_ticket_comment">Reply</button>
                            </div>

                        </div>

                    </form>


                <?php } elseif (empty($ticket_feedback)) { ?>

                    <h4>Rate your ticket</h4>

                    <form action="portal_post.php" method="post">
                        <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">

                        <button type="submit" class="btn btn-label-success btn-lg" name="add_ticket_feedback" value="Good" onclick="this.form.submit()">
                            <span class="fa fa-smile" aria-hidden="true"></span> Good
                        </button>

                        <button type="submit" class="btn btn-label-danger btn-lg" name="add_ticket_feedback" value="Bad" onclick="this.form.submit()">
                            <span class="fa fa-frown" aria-hidden="true"></span> Bad
                        </button>
                    </form>

                <?php } else { ?>

                    <h4>Rated <?= $ticket_feedback ?> -- Thanks for your feedback!</h4>

                    <?php if ($ticket_feedback == "Bad") { ?>
                        <p>Thats not what we wanted to hear. We have alerted management, and someone will be in touch to discuss further and listen to feedback.</p>

                    <?php } elseif ($ticket_feedback == "Good") { ?>
                        <p>Great to hear! We're glad we could help.</p>
                        <hr>
                        <p>
                            <a href="https://g.page/r/CT21V3QZ9Jp8EAI/review" target="_blank" class="btn btn-label-google-plus">Leave a review?</a>
                        </p>
                    <?php } ?>

                <?php } ?>                
            </div>
        </div>



        <!-- Either show the reply comments box, ticket smiley feedback, or thanks for feedback -->



        <!-- End comments/feedback -->

        <hr>
        <br>

        <?php
        $sql = mysqli_query($mysqli, "SELECT * FROM ticket_replies LEFT JOIN users ON ticket_reply_by = user_id LEFT JOIN contacts ON ticket_reply_by = contact_id WHERE ticket_reply_ticket_id = $ticket_id AND ticket_reply_archived_at IS NULL AND ticket_reply_type != 'Internal' ORDER BY ticket_reply_id DESC");

        while ($row = mysqli_fetch_array($sql)) {
            $ticket_reply_id = intval($row['ticket_reply_id']);
            $ticket_reply = $purifier->purify($row['ticket_reply']);
            $ticket_reply_created_at = nullable_htmlentities($row['ticket_reply_created_at']);
            $ticket_reply_updated_at = nullable_htmlentities($row['ticket_reply_updated_at']);
            $ticket_reply_by = intval($row['ticket_reply_by']);
            $ticket_reply_type = $row['ticket_reply_type'];

            if ($ticket_reply_type == "Client") {
                $ticket_reply_by_display = nullable_htmlentities($row['contact_name']);
                $user_initials = initials($row['contact_name']);
                $user_avatar = $row['contact_photo'];
                $avatar_link = "/includes/uploads/clients/$session_client_id/$user_avatar";
            } else {
                $ticket_reply_by_display = nullable_htmlentities($row['user_name']);
                $user_id = intval($row['user_id']);
                $user_avatar = $row['user_avatar'];
                $user_initials = initials($row['user_name']);
                $avatar_link = "/includes/uploads/users/$user_id/$user_avatar";
            }
            ?>

            <div class="card card-outline <?php if ($ticket_reply_type == 'Client') { echo "card-warning"; } else { echo "card-info"; } ?> mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <div class="media">
                            <?php
                            if (!empty($user_avatar)) {
                                ?>
                                <img src="<?= $avatar_link ?>" alt="User Avatar" class="img-fluid mr-3 rounded-circle">
                                <?php
                            } else {
                                ?>
                                <span class="fa-stack fa-2x">
                                    <i class="fa fa-circle fa-stack-2x text-secondary"></i>
                                    <span class="fa fa-stack-1x text-white"><?= $user_initials; ?></span>
                                </span>
                                <?php
                            }
                            ?>

                            <div class="media-body">
                                <?= $ticket_reply_by_display; ?>
                                <br>
                                <small class="text-muted"><?= $ticket_reply_created_at; ?> <?php if (!empty($ticket_reply_updated_at)) { echo "(edited: $ticket_reply_updated_at)"; } ?></small>
                            </div>
                        </div>
                    </h3>
                </div>

                <div class="card-body prettyContent">
                    <?= $ticket_reply; ?>
                </div>
            </div>

            <?php

        }

        ?>

        <script src="/includes/js/pretty_content.js"></script>

    <?php
    } else {
        echo "Ticket ID not found!";
    }

} else {
    header("Location: index.php");
}

require_once "portal_footer.php";


