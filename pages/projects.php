<?php

// Default Column Sortby/Order Filter
$sort = "project_name";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";

// Status Query

$status = 0;

if (isset($_GET['status'])) {
    $status = intval($_GET['status']);
}

if($status == 1) {
    $status_query = "IS NOT NULL";
} else {
    $status_query = "IS NULL";
}


$sql_projects = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM projects
    LEFT JOIN clients ON client_id = project_client_id
    LEFT JOIN users ON user_id = project_manager
    AND project_archived_at IS NULL
    AND project_completed_at $status_query
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card card-dark">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-project-diagram mr-2"></i>Projects</h3>
            <div class="card-tools">
                <button href="#!" class="btn btn-label-secondary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="project_add_modal.php">
                    <i class="fas fa-plus mr-2"></i>New Project
                </button>
            </div>
        </div>

        <div class="card-body">
            <form class="mb-4" autocomplete="off">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="btn-toolbar float-right">
                            <div class="btn-group mr-2">
                                <a href="?status=0" class="btn btn-<?php if($status == 0){ echo "primary"; } else { echo "default"; } ?>"><i class="fa fa-fw fa-door-open mr-2"></i>Open</a>
                                <a href="?status=1" class="btn btn-<?php if($status == 1){ echo "primary"; } else { echo "default"; } ?>"><i class="fa fa-fw fa-door-closed mr-2"></i>Closed</a>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
                <table class="datatables-basic table border-top">
                    <thead class="<?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th>Number</th>
                        <th>Project</th>
                        <th>Tickets / Tasks</th>
                        <th>Due</th>
                        
                        <?php if ($status == 1) { ?>
                        <th>Completed</th>
                        <?php } ?>
                        <th>Manager</th>
                        <th>Client</th>
                        <th>Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql_projects)) {
                        $project_id = intval($row['project_id']);
                        $project_prefix = nullable_htmlentities($row['project_prefix']);
                        $project_number = intval($row['project_number']);
                        $project_name = nullable_htmlentities($row['project_name']);
                        $project_description = nullable_htmlentities($row['project_description']);
                        $project_due = nullable_htmlentities($row['project_due']);
                        $project_completed_at = nullable_htmlentities($row['project_completed_at']);
                        $project_completed_at_display = date("Y-m-d", strtotime($project_completed_at));
                        $project_created_at = nullable_htmlentities($row['project_created_at']);
                        $project_created_at_display = date("Y-m-d", strtotime($project_created_at));
                        $project_updated_at = nullable_htmlentities($row['project_updated_at']);

                        $client_id = intval($row['client_id']);
                        $client_name = nullable_htmlentities($row['client_name']);

                        $project_manager = intval($row['user_id']);
                        if ($project_manager) {
                            $project_manager_display = nullable_htmlentities($row['user_name']);
                        } else {
                            $project_manager_display = "-";
                        }


                        // Get Tasks and Tickets Stats
                        // Get Tickets
                        $sql_tickets = mysqli_query($mysqli, "SELECT * FROM tickets WHERE ticket_project_id = $project_id");
                        $ticket_count = mysqli_num_rows($sql_tickets);

                        // Get Closed Ticket Count
                        $sql_closed_tickets = mysqli_query($mysqli, "SELECT * FROM tickets WHERE ticket_project_id = $project_id AND ticket_closed_at IS NOT NULL");

                        $closed_ticket_count = mysqli_num_rows($sql_closed_tickets);
                        
                        // Ticket Closed Percent
                        if($ticket_count) {
                            $tickets_closed_percent = round(($closed_ticket_count / $ticket_count) * 100);
                        }
                        // Get All Tasks
                        $sql_tasks = mysqli_query($mysqli,
                            "SELECT * FROM tickets, tasks
                            WHERE ticket_id = task_ticket_id
                            AND ticket_project_id = $project_id"
                        );
                        $task_count = mysqli_num_rows($sql_tasks);

                        // Get Completed Task Count
                        $sql_tasks_completed = mysqli_query($mysqli,
                            "SELECT * FROM tickets, tasks
                            WHERE ticket_id = task_ticket_id
                            AND ticket_project_id = $project_id
                            AND task_completed_at IS NOT NULL"
                        );
                        $completed_task_count = mysqli_num_rows($sql_tasks_completed);

                        // Tasks Completed Percent
                        if($task_count) {
                            $tasks_completed_percent = round(($completed_task_count / $task_count) * 100);
                        }

                        ?>

                        <tr>
                            <td>
                                <a class="text-dark" href="project_details.php?project_id=<?= $project_id; ?>">
                                    <?= "$project_prefix$project_number"; ?>
                                </a>
                            </td>
                            <td>
                                <a class="text-dark" href="project_details.php?project_id=<?= $project_id; ?>">
                                    <div class="media">
                                        <i class="fa fa-fw fa-2x fa-project-diagram mr-3"></i>
                                        <div class="media-body">
                                            <div><?= $project_name; ?></div>
                                            <div><small class="text-secondary"><?= $project_description; ?></small></div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <?php if($ticket_count) { ?>
                                <div class="progress" style="height: 20px;">
                                    <i class="fa fas fa-fw fa-life-ring mr-2"></i>
                                    <div class="progress-bar bg-primary" style="width: <?= $tickets_closed_percent; ?>%;"><?= $closed_ticket_count; ?> / <?= $ticket_count; ?></div>
                                </div>
                                <?php } else { echo "<div>-</div>"; } ?>
                                <?php if($task_count) { ?>
                                <div class="progress mt-2" style="height: 20px;">
                                    <i class="fa fas fa-fw fa-tasks mr-2"></i>
                                    <div class="progress-bar bg-secondary" style="width: <?= $tasks_completed_percent; ?>%;"><?= $completed_task_count; ?> / <?= $task_count; ?></div>
                                </div>
                                <?php } ?>
                            </td>
                            <td><?= $project_due; ?></td>
                            <?php if ($status == 1) { ?>
                            <td><?= $project_completed_at_display; ?></td>
                            <?php } ?>
                            <td><?= $project_manager_display; ?></td>
                            <td>
                                <a href="client_tickets.php?client_id=<?= $client_id; ?>">
                                    <?= $client_name; ?>
                                </a>
                            </td>
                            <td><?= $project_created_at_display; ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php if(empty($project_completed_at)) { ?>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editProjectModal<?= $project_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <?php } ?>
                                        <a class="dropdown-item text-danger confirm-link" href="post.php?delete_project=<?= $project_id; ?>">
                                            <i class="fas fa-fw fa-archive mr-2"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php }  ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
require_once "/var/www/portal.twe.tech/includes/footer.php";