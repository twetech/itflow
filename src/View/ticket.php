<?php

$session_mobile = false; //TODO: Implement mobile session

$ticket_prefix = $ticket['ticket_prefix'];
$ticket_number = $ticket['ticket_number'];
$ticket_subject = $ticket['ticket_subject'];
$ticket_details = $ticket['ticket_details'];
$ticket_status = $ticket['ticket_status_name'];
$ticket_status_id = $ticket['ticket_status_id'];
$ticket_id = $ticket['ticket_id'];
$ticket_priority = $ticket['ticket_priority'];
$ticket_billable = $ticket['ticket_billable'];
$ticket_total_reply_time = 0; // TODO: Implement total reply time
$ticket_replies = $data['ticket_replies'];
$ticket_reply_num = count($ticket_replies);
$ticket_created_at = $ticket['ticket_created_at'];
isset($ticket['ticket_updated_at']) ? $ticket_updated_at = $ticket['ticket_updated_at'] : $ticket_updated_at = "Not Updated";
$ticket_schedule = $ticket['ticket_schedule'];
isset($ticket["user_name"]) ? $ticket_assigned_to = $ticket["user_name"] : $ticket_assigned_to = "Unassigned";
if(!$client_page) {
    $client_name = "";
}

empty($ticket['ticket_collaborators']) ? $ticket_collaborators = array() : $ticket_collaborators = $ticket['ticket_collaborators'];

isset($ticket['ticker_schedule']) ? $ticket_schedule = $ticket['ticket_schedule'] : $ticket_schedule = "No Schedule Set";

$completed_task_count = 0; // TODO: Implement completed task count
$tasks_completed_percent = 0; // TODO: Implement tasks completed percent
$task_count = 0; // TODO: Implement task count


?>
<div class="row">
    

    <?php if ($session_mobile) {
        require_once "/var/www/portal.twe.tech/includes/ticket_sidebar.php";
        }
    ?>
    <!-- Left -->
    <div class="col<?= $session_mobile ? '' : '-9'; ?>">
        <!-- Ticket Details -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-fw fa-info-circle mr-2"></i><?= $ticket_subject; ?>
                </h5>
            </div>
            <div class="card-body prettyContent" id="ticketDetails">
                <div class="row">
                    <div class="text-truncate">
                        <?= $ticket_details; ?>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>
        </div>

        <!-- Ticket Responses -->
        <?php if ($ticket_reply_num > 0) { ?>
            <div class="card mb-3 card-action">
                <div class="card-header">
                    <div class="card-action-title">
                        <h5 class="mb-4">Responses (<?= $ticket_reply_num; ?>):</h5>
                    </div>
                    <div class="card-action-element">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="card-expand"><i class="tf-icons bx bx-fullscreen"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="card-reload"><i class="tf-icons bx bx-rotate-left scaleX-n1-rtl"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="collapse <?= !$session_mobile ? 'show' : ''; ?>">
                    <div class="card-body">
                        <!-- Ticket replies -->
                        <table class="datatables-basic table border-top">
                            <thead>
                                <tr>
                                    <th data-priority="1">Reply</th>
                                    <th>Time</th>
                                    <th>Time Worked</th>
                                    <th data-priority="2">By</th>
                                    <?php if ($ticket_status_id != 5) {
                                        echo "<th data-priority='3'>Actions</th>";
                                    } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ticket_replies as $reply) {
                                    $reply_id = $reply['ticket_reply_id'];
                                    $reply_content = $reply['ticket_reply'];
                                    $reply_created_at = $reply['ticket_reply_created_at'];
                                    $reply_time_worked = $reply['ticket_reply_time_worked'];
                                    $reply_user = $reply['user_name'];
                                    ?>
                                    <tr>
                                        <td><?= $reply_content; ?></td>
                                        <td><?= $reply_created_at; ?></td>
                                        <td><?= $reply_time_worked; ?></td>
                                        <td><?= $reply_user; ?></td>
                                        <?php if ($ticket_status_id != 5) {
                                            echo "<td><a href='/post.php?delete_ticket_reply=$reply_id' class='btn btn-danger btn-sm'><i class='fas fa-fw fa-trash'></i></a></td>";
                                        } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Ticket Respond Field -->
        <div class="card card-action mb-3">
            <form class="mb-3 d-print-none" action="/post.php" method="post" autocomplete="off">
                <div class="card-header">
                    <div class="card-action-title">
                        <h5 class="mb-4">Update Ticket:</h5>
                    </div>
                    <div class="card-action-element">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="card-expand"><i class="tf-icons bx bx-fullscreen"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="card-reload"><i class="tf-icons bx bx-rotate-left scaleX-n1-rtl"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="collapse">
                    <div class="card-body">
                        <?php if ($ticket_status_id != 5) { ?>
                            <input type="hidden" name="ticket_id" id="ticket_id" value="<?= $ticket_id; ?>">
                            <input type="hidden" name="client_id" id="client_id" value="<?= $client_id; ?>">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <?php if($config_ai_enable) { ?>
                                        <div class="form-group">
                                            <textarea class="form-control " id="response" name="ticket_reply" placeholder="Type a response"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <button id="rewordButton" class="btn btn-label-primary" type="button">
                                                <i class="fas fa-fw fa-robot mr-2"></i>Reword
                                            </button>
                                            <button id="undoButton" class="btn btn-light" type="button" style="display:none;">
                                                <i class="fas fa-fw fa-redo-alt mr-2"></i>Undo
                                            </button>
                                        </div>
                                        <?php } else { ?>
                                        <div class="form-group">
                                            <textarea id="" class="form-control" name="ticket_reply"
                                                placeholder="Type a response"></textarea>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-fw fa-thermometer-half"></i></span>
                                        </div>
                                        <select class="form-control select2" id='select2' name="status" required>
                                            <?php 

                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <!-- Time Tracking -->
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" inputmode="numeric" id="hours" name="hours"
                                            placeholder="Hrs" min="0" max="23" pattern="0?[0-9]|1[0-9]|2[0-3]">
                                        <input type="text" class="form-control" inputmode="numeric" id="minutes"
                                            name="minutes" placeholder="Mins" min="0" max="59" pattern="[0-5]?[0-9]">
                                        <input type="text" class="form-control" inputmode="numeric" id="seconds"
                                            name="seconds" placeholder="Secs" min="0" max="59" pattern="[0-5]?[0-9]">
                                    </div>
                                </div>
                                <!-- Timer Controls -->
                                <div class="col">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success" id="startStopTimer"><i
                                                class="fas fa-fw fa-pause"></i></button>
                                        <button type="button" class="btn btn-danger" id="resetTimer"><i
                                                class="fas fa-fw fa-redo-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                            <p class="font-weight-light" id="ticket_collision_viewing"></p>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                        <?php
                            // Public responses by default (maybe configurable in future?)
                            $ticket_reply_button_wording = "Respond";
                            $ticket_reply_button_check = "checked";
                            $ticket_reply_button_icon = "paper-plane";

                            // Internal responses by default if 1) the contact email is empty or 2) the contact email matches the agent responding
                            if (empty($contact_email) || $contact_email == $session_email) {
                                // Internal
                                $ticket_reply_button_wording = "Add note";
                                $ticket_reply_button_check = "";
                                $ticket_reply_button_icon = "sticky-note";
                            } ?>

                            <div class="col col-lg-3">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="ticket_reply_type_checkbox"
                                            name="public_reply_type" value="1" <?= $ticket_reply_button_check ?>>
                                        <label class="custom-control-label" for="ticket_reply_type_checkbox">Public Update<br>
                                            <small class="text-secondary">(Emails contact)</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col col-lg-2">
                                <button type="submit" id="ticket_add_reply" name="add_ticket_reply"
                                    class="btn btn-label-primary text-bold"><i
                                    class="fas fa-<?= $ticket_reply_button_icon ?> mr-2"></i><?= $ticket_reply_button_wording ?></button>
                            </div>
                        <!-- End IF for reply modal -->
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>

        <!-- Ticket Tasks -->
        <!-- Tasks Card -->
        <div class="card card-body card-outline card-dark">
            <h5 class="text-secondary">Tasks</h5>
            <form action="/post.php" method="post" autocomplete="off">
                <input type="hidden" name="ticket_id" value="<?= $ticket_id; ?>">
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-fw fa-tasks"></i></span>
                        </div>
                        <input type="text" class="form-control" name="name" placeholder="Create Task">
                        <div class="input-group-append">
                            <button type="submit" name="add_task" class="btn btn-dark">
                                <i class="fas fa-fw fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-sm">
                <?php

                ?>
            </table>
        </div>
    </div>
    <?php if (!$session_mobile) {
        require_once "/var/www/portal.twe.tech/includes/ticket_sidebar.php";
        }
    ?>
</div>