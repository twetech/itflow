<?php require_once "/var/www/portal.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$event_id = intval($_GET['event_id']);
$sql_event = mysqli_query($mysqli, "SELECT * FROM events WHERE event_id = $event_id");
$row = mysqli_fetch_array($sql_event);

$event_title = nullable_htmlentities($row['event_title']);
$event_start = nullable_htmlentities($row['event_start']);
$event_end = nullable_htmlentities($row['event_end']);
$event_location = nullable_htmlentities($row['event_location']);
$event_description = nullable_htmlentities($row['event_description']);
$event_repeat = nullable_htmlentities($row['event_repeat']);
$calendar_id = intval($row['calendar_id']);
$client_id = intval($row['client_id']);
?>

<div class="modal" id="editEventModal<?= $event_id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-calendar mr-2"></i><?= $event_title; ?></h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="/post.php" method="post" autocomplete="off">

                <div class="modal-body bg-white">
                <input type="hidden" name="event_id" value="<?= $event_id; ?>">

                    <ul class="nav nav-pills  mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" role="tab" data-bs-toggle="tab" href="#pills-event<?= $event_id; ?>"><i class="fa fa-fw fa-calendar mr-2"></i>Event</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-details<?= $event_id; ?>"><i class="fa fa-fw fa-info-circle mr-2"></i>Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" role="tab" data-bs-toggle="tab" href="#pills-attendees<?= $event_id; ?>"><i class="fa fa-fw fa-users mr-2"></i>Attendees</a>
                        </li>
                    </ul>

                    <hr>

                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="pills-event<?= $event_id; ?>">

                            <div class="form-group">
                                <label>Title <strong class="text-danger">*</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-calendar-day"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="title" value="<?= $event_title; ?>" placeholder="Title of the event" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Calendar <strong class="text-danger">*</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <select class="form-control select2" id='select2' name="calendar" required>
                                        <?php

                                        $sql_calendars_select = mysqli_query($mysqli, "SELECT * FROM calendars ORDER BY calendar_name ASC");
                                        while ($row = mysqli_fetch_array($sql_calendars_select)) {
                                            $calendar_id_select = intval($row['calendar_id']);
                                            $calendar_name_select = nullable_htmlentities($row['calendar_name']);
                                            $calendar_color_select = nullable_htmlentities($row['calendar_color']);
                                            ?>
                                            <option data-content="<i class='fa fa-circle mr-2' style='color:<?= $calendar_color_select; ?>;'></i> <?= $calendar_name_select; ?>"<?php if ($calendar_id == $calendar_id_select) { echo "selected"; } ?> value="<?= $calendar_id_select; ?>"><?= $calendar_name_select; ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Start / End <strong class="text-danger">*</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-calendar-check"></i></span>
                                    </div>
                                    <input type="datetime-local" class="form-control" name="start" value="<?= date('Y-m-d\TH:i:s', strtotime($event_start)); ?>" required>
                                </div>
                            </div>
                                
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-calendar-day"></i></span>
                                    </div>
                                    <input type="datetime-local" class="form-control" name="end" value="<?= date('Y-m-d\TH:i:s', strtotime($event_end)); ?>"required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Repeat</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-recycle"></i></span>
                                    </div>
                                    <select class="form-control select2" id='select2' name="repeat">
                                        <option <?php if (empty($event_repeat)) { echo "selected"; } ?> value="">Never</option>
                                        <option <?php if ($event_repeat == "Day") { echo "selected"; } ?>>Day</option>
                                        <option <?php if ($event_repeat == "Week") { echo "selected"; } ?>>Week</option>
                                        <option <?php if ($event_repeat == "Month") { echo "selected"; } ?>>Month</option>
                                        <option <?php if ($event_repeat == "Year") { echo "selected"; } ?>>Year</option>
                                    </select>
                                </div>
                            </div>  

                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pills-details<?= $event_id; ?>">
                            <div class="form-group">
                                <label>Location</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-fw fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="location" value="<?= $event_location; ?>" placeholder="Location of the event">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="8" name="description" placeholder="Enter a description"><?= $event_description; ?></textarea>
                            </div>
                            

                        </div>

                        <div class="tab-pane fade" role="tabpanel" id="pills-attendees<?= $event_id; ?>">

                            <?php if (isset($_GET['client_id'])) { ?>
                                <input type="hidden" name="client" value="<?= $client_id; ?>">
                            <?php } else { ?>

                                <div class="form-group">
                                    <label>Client</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
                                        </div>
                                        <select class="form-control select2" id='select2' name="client">
                                            <option value="">- Client -</option>
                                            <?php

                                            $sql_clients = mysqli_query($mysqli, "SELECT * FROM clients LEFT JOIN contacts ON clients.client_id = contacts.contact_client_id AND contact_primary = 1 ORDER BY client_name ASC");
                                            while ($row = mysqli_fetch_array($sql_clients)) {
                                                $client_id_select = intval($row['client_id']);
                                                $client_name_select = nullable_htmlentities($row['client_name']);
                                                $contact_email_select = nullable_htmlentities($row['contact_email']);
                                                ?>
                                                <option <?php if ($client_id == $client_id_select) { echo "selected"; } ?> value="<?= $client_id_select; ?>"><?= $client_name_select; ?></option>

                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>

                            <?php } ?>

                            <?php if (!empty($config_smtp_host)) { ?>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customControlAutosizing<?= $event_id; ?>" name="email_event" value="1" >
                                    <label class="custom-control-label" for="customControlAutosizing<?= $event_id; ?>">Email Event</label>
                                </div>
                            <?php } ?>

                        </div>

                    </div>

                </div>
                <div class="modal-footer bg-white">
                    <a class="btn btn-default text-danger mr-auto" href="/post.php?delete_event=<?= $event_id; ?>"><i class="fa fa-calendar-times mr-2"></i>Delete</a>
                    <button type="submit" name="edit_event" class="btn btn-label-primary text-bold"></i>Save</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
