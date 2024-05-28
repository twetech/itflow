<?php

// Default Column Sortby Filter
$sort = "email_id";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all_admin.php";

//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM email_queue
    WHERE (email_id LIKE '%$q%' OR email_from LIKE '%$q%' OR email_from_name LIKE '%$q%' OR email_recipient LIKE '%$q%' OR email_recipient_name LIKE '%$q%' OR email_subject LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-3">
            <h3 class="card-title"><i class="fas fa-fw fa-mail-bulk mr-2"></i>Email Queue</h3>
        </div>
        <div class="card-body">
            <form class="mb-4" autocomplete="off">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="search" class="form-control" name="q" value="<?php if (isset($q)) { echo stripslashes(nullable_htmlentities($q)); } ?>" placeholder="Search mail queue">
                            <div class="input-group-append">
                                <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilter"><i class="fas fa-filter"></i></button>
                                <button class="btn btn-label-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse mt-3 <?php if (!empty($_GET['dtf'])) { echo "show"; } ?>" id="advancedFilter">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Canned Date</label>
                                <select class="form-control select2" id='select2' name="canned_date">
                                    <option <?php if ($_GET['canned_date'] == "custom") { echo "selected"; } ?> value="">Custom</option>
                                    <option <?php if ($_GET['canned_date'] == "today") { echo "selected"; } ?> value="today">Today</option>
                                    <option <?php if ($_GET['canned_date'] == "yesterday") { echo "selected"; } ?> value="yesterday">Yesterday</option>
                                    <option <?php if ($_GET['canned_date'] == "thisweek") { echo "selected"; } ?> value="thisweek">This Week</option>
                                    <option <?php if ($_GET['canned_date'] == "lastweek") { echo "selected"; } ?> value="lastweek">Last Week</option>
                                    <option <?php if ($_GET['canned_date'] == "thismonth") { echo "selected"; } ?> value="thismonth">This Month</option>
                                    <option <?php if ($_GET['canned_date'] == "lastmonth") { echo "selected"; } ?> value="lastmonth">Last Month</option>
                                    <option <?php if ($_GET['canned_date'] == "thisyear") { echo "selected"; } ?> value="thisyear">This Year</option>
                                    <option <?php if ($_GET['canned_date'] == "lastyear") { echo "selected"; } ?> value="lastyear">Last Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date From</label>
                                <input type="date" class="form-control" name="dtf" max="2999-12-31" value="<?= nullable_htmlentities($dtf); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date To</label>
                                <input type="date" class="form-control" name="dtt" max="2999-12-31" value="<?= nullable_htmlentities($dtt); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div class="card-datatable table-responsive container-fluid  pt-0">                <table id=responsive class="responsive table table-sm table-striped table-borderless table-hover">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=email_id&order=<?= $disp; ?>">ID</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=email_queued_at&order=<?= $disp; ?>">Queued</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=email_from&order=<?= $disp; ?>">From</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=email_recipient&order=<?= $disp; ?>">To</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=email_subject&order=<?= $disp; ?>">Subject</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=email_status&order=<?= $disp; ?>">Status</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=email_attempts&order=<?= $disp; ?>">Attempts</a></th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $email_id = intval($row['email_id']);
                        $email_from = nullable_htmlentities($row['email_from']);
                        $email_from_name = nullable_htmlentities($row['email_from_name']);
                        $email_recipient = nullable_htmlentities($row['email_recipient']);
                        $email_recipient_name = nullable_htmlentities($row['email_recipient_name']);
                        $email_subject = nullable_htmlentities($row['email_subject']);
                        $email_attempts = intval($row['email_attempts']);
                        $email_queued_at = nullable_htmlentities($row['email_queued_at']);
                        $email_failed_at = nullable_htmlentities($row['email_failed_at']);
                        $email_sent_at = nullable_htmlentities($row['email_sent_at']);
                        $email_status = intval($row['email_status']);
                        if ($email_status == 0) {
                            $email_status_display = "<div class='text-primary'>Queued</div>";
                        } elseif($email_status == 1) {
                            $email_status_display = "<div class='text-warning'>Sending</div>";
                        } elseif($email_status == 2) {
                            $email_status_display = "<div class='text-danger'>Failed</div><small class='text-secondary'>$email_failed_at</small>";
                        } else {
                            $email_status_display = "<div class='text-success'>Sent</div><small class='text-secondary'>$email_sent_at</small>";
                        }

                        ?>

                        <tr>
                            <td><?= $email_id; ?></td>
                            <td><?= $email_queued_at; ?></td>
                            <td><?= "$email_from<br><small class='text-secondary'>$email_from_name</small>"?></td>
                            <td><?= "$email_recipient<br><small class='text-secondary'>$email_recipient_name</small>"?></td>
                            <td><?= $email_subject; ?></td>
                            <td><?= $email_status_display; ?></td>
                            <td><?= $email_attempts; ?></td>
                            <td>
                                <a class="btn btn-sm btn-light" href="admin_mail_queue_message_view.php?email_id=<?= $email_id; ?>">
                                    <i class="fas fa-fw fa-eye"></i>
                                </a>

                                <!-- Show force resend if all retries have failed -->
                                <?php if ($email_status == 2 && $email_attempts > 3) { ?>
                                    <a class="btn btn-sm btn-success" href="/post.php?send_failed_mail=<?= $email_id; ?>"><i class="fas fa-fw fa-paper-plane"></i></a>
                                <?php } ?>

                                <!-- Allow cancelling a message if it hasn't yet been picked up (e.g. stuck/bugged) -->
                                <?php if ($email_status == 0) { ?>
                                    <a class="btn btn-sm btn-danger confirm-link" href="/post.php?cancel_mail=<?= $email_id; ?>"><i class="fas fa-fw fa-trash"></i></a>
                                <?php } ?>

                            </td>
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
require_once '/var/www/portal.twe.tech/includes/footer.php';

