<!-- Right -->
<div class="col<?= $session_mobile ? '' : '-3'; ?>">
    <div class="card card-action mb-3">
        <div class="card-header">
            <div class="card-action-title row">
                <div class="col">
                    <h5 class="card-title">Ticket <?= $ticket_prefix . $ticket_number ?></h5>
                </div>
            </div>
            <div class="card-action-element">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <div class="dropdown dropleft text-center d-print-none">
                            <button class="btn btn-light btn-sm float-right" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                <i class="fas fa-fw fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="#"  class="dropdown-item loadModalContentBtn"data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_modal.php?ticket_id=<?= $ticket_id; ?>">
                                    <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                </a>
                                <a href="#" class="dropdown-item loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_merge_modal.php?ticket_id=<?= $ticket_id; ?>">
                        
                                    <i class="fas fa-fw fa-clone mr-2"></i>Merge
                                </a>
                                <a href="#" class="dropdown-item loadModalContentBtn"  data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_change_client_modal.php?ticket_id=<?= $ticket_id; ?>">
                                    <i class="fas fa-fw fa-people-carry mr-2"></i>Change Client
                                </a>
                                <?php if ($session_user_role == 3) { ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_ticket=<?= $ticket_id; ?>">
                                    <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>
        </div>
        <div class="collapse <?= !$session_mobile ? 'show' : ''; ?>">
            <div class="card-body">
                <div class="row">
                    <h5><strong><?= $client_name; ?></strong></h5>
                    <?php
                            if (!empty($location_phone)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2 mb-2"></i><?= $location_phone; ?>
                    </div>
                    <?php } ?>

                    <?php
                            if (!empty($client_tags_display)) { ?>
                    <div class="mt-1"><?= $client_tags_display; ?></div>
                    <?php } ?>
                </div>
                                <!-- Ticket Actions -->
                                <?php
                    if ($ticket_status_id != 5) {
                        $close_ticket_button = true;
                    }
                    if ($ticket_billable) {
                        $invoice_ticket_button = true;
                    }

                    if ($close_ticket_button || $invoice_ticket_button) {
                ?>
                    <div class="card card-body card-outline card-dark mb-2 d-print-none">
                        <?php if (isset($invoice_ticket_button)) { ?>
                        <a href="#" class="btn btn-primary btn-block mb-3 loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_invoice_add_modal.php?ticket_id=<?= $ticket_id; ?>&ticket_total_reply_time=<?= $ticket_total_reply_time; ?>">
                            <i class="fas fa-fw fa-file-invoice mr-2"></i>Invoice Ticket
                        </a>
                        <?php } ?>
                        <?php if (isset($close_ticket_button)) { ?>
                        <a href="/post.php?close_ticket=<?= $ticket_id; ?>" class="btn btn-secondary btn-block confirm-link" id="ticket_close">
                            <i class="fas fa-fw fa-gavel mr-2"></i>Close Ticket
                        </a>
                        <?php } ?>
                    </div>
                    <?php } ?>

                <!-- End Ticket Actions -->
                <!-- End Client row -->
                    <div class="row small">
                        <table class="me-2 table table-sm table-borderless table-striped table-hover table-responsive-md">
                            <thead>
                                <tr>
                                    <th>Details</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Priority:</td>
                                    <td><?= $ticket_priority_display; ?></td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td><?= $ticket_status_display; ?></td>
                                </tr>
                                <tr>
                                    <td>Billable:</td>
                                    <td>
                                        <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_billable_modal.php?ticket_id=<?= $ticket_id; ?>">
                                            <?php if ($ticket_billable == 1) { ?>
                                                <span class="badge rounded-pill bg-label-success p-2">$</span>
                                            <?php } else { ?>
                                                <span class="badge rounded-pill bg-label-secondary p-2">X</span>
                                            <?php } ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Time Tracked:</td>
                                    <td><?= $ticket_total_reply_time; ?></td>
                                    
                                </tr>
                                <tr>
                                    <td>Tasks Completed:</td>
                                    <td>
                                        <div class="progress">
                                            <?php if ($tasks_completed_percent < 15) {
                                                $tasks_completed_percent_display = 15;
                                            } else {
                                                $tasks_completed_percent_display = $tasks_completed_percent;
                                            } 
                                            if ($task_count == 0) {
                                                $tasks_completed_percent_display = 100;
                                                $tasks_completed_percent = 100;
                                            } ?>
                                            <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: <?= $tasks_completed_percent_display; ?>%;" aria-valuenow="<?= $tasks_completed_percent_display; ?>" aria-valuemin="0" aria-valuemax="100"><?= $tasks_completed_percent; ?>%</div>
                                        </div>
                                        <div>
                                            <small>
                                                <?= $completed_task_count; ?> of
                                                <?= $task_count; ?> tasks completed
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Collaborators:
                                    </td>
                                    <td>
                                        <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_collaborators_modal.php?ticket_id=<?= $ticket_id; ?>">
                                            <?= $ticket_collaborators; ?>
                                        </a>
                                    </td>
                                <tr>
                                    <td>Created:</td>
                                    <td><?= $ticket_created_at; ?></td>
                                </tr>
                                <tr>
                                    <td>Updated:</td>
                                    <td><strong><?= $ticket_updated_at; ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Scheduled:</td>
                                    <td>
                                        <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_schedule_modal.php?ticket_id=<?= $ticket_id; ?>">
                                            <?= $ticket_scheduled_wording ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Assigned to:</td>
                                    <td><a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_assign_modal.php?ticket_id=<?= $ticket_id; ?>"><?= $ticket_assigned_to_display; ?></a></td>
                                </tr>
                                <tr>
                                    <td>Category:</td>
                                    <td><a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_category_modal.php?ticket_id=<?= $ticket_id; ?>"><?= $ticket_category_display; ?></a></td>
                                </tr>


                            </tbody>

                        </table>
                    </div>


                    <!-- Ticket closure info -->
                    <?php if ($ticket_status == "Closed") {
                                $sql_closed_by = mysqli_query($mysqli, "SELECT * FROM tickets, users WHERE ticket_closed_by = user_id");
                                $row = mysqli_fetch_array($sql_closed_by);
                                $ticket_closed_by_display = nullable_htmlentities($row['user_name']);
                                ?>
                        <div class="mt-1">
                            <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i>Closed by:
                            <?= ucwords($ticket_closed_by_display); ?>
                        </div>
                        <div class="mt-1">
                            <i class="fa fa-fw fa-comment-dots text-secondary ml-1 mr-2"></i>Feedback:
                            <?= $ticket_feedback; ?>
                        </div>
                    <?php } ?>
                    <!-- END Ticket closure info -->

                <!-- Contact card -->
                <div class="card card-body card-outline mb-3">
                    <h5 class="text-secondary">Contact</h5>
                    <?php if (!empty($contact_id)) { ?>
                    <div>
                        <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i><a class="loadModalContentBtn" href="#" data-bs-toggle="modal"
                            data-bs-target="#dynamicModal" data-modal-file="ticket_edit_contact_modal.php?ticket_id=<?= $ticket_id; ?>"><strong><?= $contact_name; ?></strong>
                        </a>
                    </div>
                    <?php
                                if (!empty($location_name)) { ?>
                    <div class="mt-2">
                        <i class="fa fa-fw fa-map-marker-alt text-secondary ml-1 mr-2"></i><?= $location_name; ?>
                    </div>
                    <?php }
                                if (!empty($contact_email)) { ?>
                    <div class="mt-2">
                        <i class="fa fa-fw fa-envelope text-secondary ml-1 mr-2"></i><a
                            href="mailto:<?= $contact_email; ?>"><?= $contact_email; ?></a>
                    </div>
                    <?php }
                                if (!empty($contact_phone)) { ?>
                    <div class="mt-2">
                        <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i><a
                            href="tel:<?= $contact_phone; ?>"><?= $contact_phone; ?></a>
                    </div>
                    <?php }
                                if (!empty($contact_mobile)) { ?>
                    <div class="mt-2">
                        <i class="fa fa-fw fa-mobile-alt text-secondary ml-1 mr-2"></i><a
                            href="tel:<?= $contact_mobile; ?>"><?= $contact_mobile; ?></a>
                    </div>
                    <?php } ?>
                    <?php
                            // Previous tickets
                            $prev_ticket_id = $prev_ticket_subject = $prev_ticket_status = ''; // Default blank
                            $sql_prev_ticket = "SELECT ticket_id, ticket_created_at, ticket_subject, ticket_status, ticket_assigned_to FROM tickets
                                LEFT JOIN ticket_statuses ON ticket_status_id = ticket_status
                                WHERE ticket_contact_id = $contact_id AND ticket_id  <> $ticket_id ORDER BY ticket_id DESC LIMIT 1";
                            $prev_ticket_row = mysqli_fetch_assoc(mysqli_query($mysqli, $sql_prev_ticket));
                            if ($prev_ticket_row) {
                                $prev_ticket_id = intval($prev_ticket_row['ticket_id']);
                                $prev_ticket_subject = nullable_htmlentities($prev_ticket_row['ticket_subject']);
                                $prev_ticket_status = nullable_htmlentities($prev_ticket_row['ticket_status_name']);
                            ?>

                    <hr>
                    <div>
                        <i class="fa fa-fw fa-history text-secondary ml-1 mr-2"></i><b>Previous ticket:</b>
                        <a
                            href="ticket.php?ticket_id=<?= $prev_ticket_id; ?>"><?= $prev_ticket_subject; ?></a>
                    </div>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-hourglass-start text-secondary ml-1 mr-2"></i><strong>Status:</strong>
                        <span class="text-success"><?= $prev_ticket_status; ?></span>
                    </div>
                    <?php } ?>
                    <?php } else { ?>
                    <div class="d-print-none">
                        <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_contact_modal.php?ticket_id=<?= $ticket_id; ?>"><i
                                class="fa fa-fw fa-plus mr-2"></i>Add a Contact</a>
                    </div>
                    <?php } ?>
                </div>
                <!-- End contact card -->

                <!-- Ticket watchers card -->
                <?php
                    $sql_ticket_watchers = mysqli_query($mysqli, "SELECT * FROM ticket_watchers WHERE watcher_ticket_id = $ticket_id ORDER BY watcher_email DESC");
                    if ($ticket_status_id !== 5 || mysqli_num_rows($sql_ticket_watchers) > 0) { ?>

                    <div class="card card-body card-outline mb-3">
                        <h5 class="text-secondary">Watchers</h5>

                        <?php if ($ticket_status !== 5) { ?>
                        <div class="d-print-none">
                            <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_add_watcher_modal.php?ticket_id=<?= $ticket_id; ?>"
                            ><i
                                    class="fa fa-fw fa-plus mr-2"></i>Add a Watcher</a>
                        </div>
                        <?php } ?>

                        <?php
                                // Get Watchers
                                while ($ticket_watcher_row = mysqli_fetch_array($sql_ticket_watchers)) {
                                    $watcher_id = intval($ticket_watcher_row['watcher_id']);
                                    $ticket_watcher_email = nullable_htmlentities($ticket_watcher_row['watcher_email']);
                                    ?>
                        <div class='mt-1'>
                            <i class="fa fa-fw fa-eye text-secondary ml-1 mr-2"></i><?= $ticket_watcher_email; ?>
                            <?php if ($ticket_status !== "Closed") { ?>
                            <a class="confirm-link" href="/post.php?delete_ticket_watcher=<?= $watcher_id; ?>">
                                <i class="fas fa-fw fa-times text-secondary ml-1"></i>
                            </a>
                            <?php }
                                    ?>
                        </div>
                        <?php
                            } ?>
                        </div>
                <?php } ?>
                <!-- End Ticket watchers card -->
                <!-- Ticket Details card -->
                <!-- End Ticket details card -->
                <!-- Asset card -->
                <div class="card card-body card-outline mb-3">
                    <h5 class="text-secondary">Asset</h5>

                    <?php if ($asset_id == 0) { ?>

                    <div class="d-print-none">
                        <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_asset_modal.php?ticket_id=<?= $ticket_id; ?>"><i
                                class="fa fa-fw fa-plus mr-2"></i>Add an Asset</a>
                    </div>

                    <?php } else { ?>

                    <div>
                        <a href='client_asset_details.php?client_id=<?= $client_id ?>&asset_id=<?= $asset_id ?>'><i
                                class="fa fa-fw fa-desktop text-secondary ml-1 mr-2"></i><strong><?= $asset_name; ?></strong></a>
                    </div>

                    <?php if (!empty($asset_os)) { ?>
                    <div class="mt-1">
                        <i class="fab fa-fw fa-microsoft text-secondary ml-1 mr-2"></i><?= $asset_os; ?>
                    </div>
                    <?php }

                                if (!empty($asset_ip)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-network-wired text-secondary ml-1 mr-2"></i><?= $asset_ip; ?>
                    </div>
                    <?php }

                                if (!empty($asset_make)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-tag text-secondary ml-1 mr-2"></i>Model:
                        <?= "$asset_make $asset_model"; ?>
                    </div>
                    <?php }

                                if (!empty($asset_serial)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-barcode text-secondary ml-1 mr-2"></i>Service Tag:
                        <?= $asset_serial; ?>
                    </div>
                    <?php }

                                if (!empty($asset_warranty_expire)) { ?>
                    <div class="mt-1">
                        <i class="far fa-fw fa-calendar-alt text-secondary ml-1 mr-2"></i>Warranty expires:
                        <strong><?= $asset_warranty_expire ?></strong>
                    </div>
                    <?php }

                                if (!empty($asset_uri)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-globe text-secondary ml-1 mr-2"></i><a href="<?= $asset_uri; ?>"
                            target="_blank" rel="noopener"><?= truncate($asset_uri, 25); ?></a>
                    </div>
                    <?php }

                            if ($ticket_asset_count > 0) { ?>

                    <button class="btn btn-block btn-light mt-2 d-print-none" data-bs-toggle="modal"
                        data-bs-target="#assetTicketsModal">Service History (<?= $ticket_asset_count; ?>)</button>

                    <div class="modal" id="assetTicketsModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content bg-dark">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fa fa-fw fa-desktop"></i> <?= $asset_name; ?>
                                    </h5>
                                    <button type="button" class="close text-white" data-bs-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body bg-white">
                                    <?php
                                                    // Query is run from client_assets.php
                                                    while ($row = mysqli_fetch_array($sql_asset_tickets)) {
                                                        $service_ticket_id = intval($row['ticket_id']);
                                                        $service_ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
                                                        $service_ticket_number = intval($row['ticket_number']);
                                                        $service_ticket_subject = nullable_htmlentities($row['ticket_subject']);
                                                        $service_ticket_status = nullable_htmlentities($row['ticket_status']);
                                                        $service_ticket_created_at = nullable_htmlentities($row['ticket_created_at']);
                                                        $service_ticket_updated_at = nullable_htmlentities($row['ticket_updated_at']);
                                                    ?>
                                    <p>
                                        <i class="fas fa-fw fa-ticket-alt"></i>
                                        Ticket: <a
                                            href="ticket.php?ticket_id=<?= $service_ticket_id; ?>"><?= "$service_ticket_prefix$service_ticket_number" ?></a>
                                        <?= "on $service_ticket_created_at - <b>$service_ticket_subject</b> ($service_ticket_status)"; ?>
                                    </p>
                                    <?php
                                                    }
                                                    ?>
                                </div>
                                <div class="modal-footer bg-white">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php } // End Ticket asset Count
                                ?>

                    <?php } // End if asset_id == 0 else
                            ?>

                </div>
                <!-- End Asset card -->
                <!-- Vendor card -->
                <div class="card card-body card-outline mb-3">
                    <h5 class="text-secondary">Vendor</h5>
                    <?php if (empty($vendor_id)) { ?>
                    <div class="d-print-none">
                        <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_edit_vendor_modal.php?ticket_id=<?= $ticket_id; ?>"><i
                                class="fa fa-fw fa-plus mr-2"></i>Add a Vendor</a>
                    </div>
                    <?php } else { ?>
                    <div>
                        <i
                            class="fa fa-fw fa-building text-secondary ml-1 mr-2"></i><strong><?= $vendor_name; ?></strong>
                    </div>
                    <?php

                                if (!empty($vendor_contact_name)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i><?= $vendor_contact_name; ?>
                    </div>
                    <?php }

                                if (!empty($ticket_vendor_ticket_number)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-tag text-secondary ml-1 mr-2"></i><?= $ticket_vendor_ticket_number; ?>
                    </div>
                    <?php }

                                if (!empty($vendor_email)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-envelope text-secondary ml-1 mr-2"></i><a
                            href="mailto:<?= $vendor_email; ?>"><?= $vendor_email; ?></a>
                    </div>
                    <?php }

                                if (!empty($vendor_phone)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i><?= $vendor_phone; ?>
                    </div>
                    <?php }

                                if (!empty($vendor_website)) { ?>
                    <div class="mt-1">
                        <i class="fa fa-fw fa-globe text-secondary ml-1 mr-2"></i><?= $vendor_website; ?>
                    </div>
                    <?php } ?>

                    <?php } //End Else
                            ?>
                </div>
                <!-- End Vendor card -->
                <!-- Products card -->
                <?php if ($config_module_enable_accounting == 1) { ?>
                    <div class="card card-body card-outline mb-3">
                        <h5 class="text-secondary">Products</h5>
                        <div class="d-print-none">
                            <a class="loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="ticket_add_product_modal.php?ticket_id=<?= $ticket_id; ?>"><i
                                    class="fa fa-fw fa-plus mr-2"></i>Manage Products</a>
                        </div>
                        <?= $ticket_products_display; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
