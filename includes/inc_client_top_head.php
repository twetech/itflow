<div class="card card-action d-print-none">
    <div class="card-header">
        <div class="card-action-title">
            <h4>
                <?php if ($client_page) {
                    echo ucwords($client_name);
                } else {
                    echo $page_name;
                } ?>
            </h4>
        </div>
        <div class="card-action-element">
            <ul class="list-inline mb-0">
                <li class="list-inline-item">
                    <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons bx bx-chevron-up"></i></a>
                </li>
                <li class="list-inline-item">
                    <div class="dropdown dropleft text-center">
                        <button class="btn btn-dark btn-sm float-right" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-fw fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_edit_modal.php?client_id=<?php echo $client_id; ?>">
                                <i class="fas fa-fw fa-edit mr-2"></i>Edit Client
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_export_modal.php?client_id=<?php echo $client_id; ?>">
                                <i class="fas fa-fw fa-file-pdf mr-2"></i>Export Data
                            </a>
                            <div class="dropdown-divider"></div>
                            
                            <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_archive_modal.php?client_id=<?php echo $client_id; ?>">
                                <i class="fas fa-fw fa-archive mr-2"></i>Archive Client
                            </a>
                            <?php if ($session_user_role == 3) { ?>
                            <div class="dropdown-divider"></div>
                            <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_delete_modal.php?client_id=<?php echo $client_id; ?>">
                                <i class="fas fa-fw fa-trash mr-2"></i>Delete Client
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body py-2">
        <div class="row">
            <div class="col">
                
            </div>
            <div class="col">
                <?php if ($session_user_role == 3) { ?>

                <?php } ?>
            </div>
        </div>

        <div class="collapse <?php if (basename($_SERVER["PHP_SELF"]) == "client_overview.php") { echo "show"; } ?>" id="clientHeader">

            <div class="row">

                <div class="col-md border-top">
                    <h5 class="text-secondary mt-1">Primary Location</h5>
                    <?php if (!empty($location_address)) { ?>
                        <div>
                            <a href="//maps.<?php echo $session_map_source; ?>.com/?q=<?php echo "$location_address $location_zip"; ?>" target="_blank">
                                <i class="fa fa-fw fa-map-marker-alt text-secondary ml-1 mr-2"></i><?php echo $location_address; ?>
                                <div><i class="fa fa-fw ml-1 mr-2"></i><?php echo "$location_city $location_state $location_zip"; ?></div>
                            </a>
                        </div>
                    <?php }

                    if (!empty($location_phone)) { ?>
                        <div class="mt-1">
                            <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i><a href="tel:<?php echo $location_phone?>"><?php echo $location_phone; ?></a>
                        </div>
                        <hr class="my-2">
                    <?php }

                    if (!empty($client_website)) { ?>
                        <div class="mt-1">
                            <i class="fa fa-fw fa-globe text-secondary ml-1 mr-2"></i><a target="_blank" href="//<?php echo $client_website; ?>"><?php echo $client_website; ?></a>
                        </div>
                    <?php } ?>

                </div>

                <div class="col-md border-left border-top">
                    <h5 class="text-secondary mt-1">Primary Contact</h5>
                    <?php

                    if (!empty($contact_name)) { ?>
                        <div>
                            <i class="fa fa-fw fa-user text-secondary ml-1 mr-2"></i> <?php echo $contact_name; ?>
                        </div>
                    <?php }

                    if (!empty($contact_email)) { ?>
                        <div class="mt-1">
                            <i class="fa fa-fw fa-envelope text-secondary ml-1 mr-2"></i>
                            <a href="mailto:<?php echo $contact_email; ?>"> <?php echo $contact_email; ?></a>
                        </div>
                        <?php
                    }

                    if (!empty($contact_phone)) { ?>
                        <div class="mt-1">
                            <i class="fa fa-fw fa-phone text-secondary ml-1 mr-2"></i>
                            <a href="tel:<?php echo $contact_phone; ?>"><?php echo $contact_phone; ?></a>

                            <?php
                            if (!empty($contact_extension)) {
                                echo "<small>x$contact_extension</small>";
                            }
                            ?>
                        </div>
                        <?php
                    }

                    if (!empty($contact_mobile)) { ?>
                        <div class="mt-1">
                            <i class="fa fa-fw fa-mobile-alt text-secondary ml-1 mr-2"></i>
                            <a href="tel:<?php echo $contact_mobile; ?>"><?php echo $contact_mobile; ?></a>
                        </div>
                    <?php } ?>

                </div>

                <?php if ($session_user_role == 1 || $session_user_role == 3 && $config_module_enable_accounting == 1) { ?>
                <div class="col-md border-left border-top">
                    <h5 class="text-secondary mt-1">Billing</h5>
                    <div class="ml-1 text-secondary">Hourly Rate
                        <span class="text-dark float-right"> <?php echo numfmt_format_currency($currency_format, $client_rate, $client_currency_code); ?></span>
                    </div>
                    <div class="ml-1 mt-1 text-secondary">Paid
                        <span class="text-dark float-right"> <?php echo numfmt_format_currency($currency_format, $amount_paid, $client_currency_code); ?></span>
                    </div>
                    <div class="ml-1 mt-1 text-secondary">Balance
                        <span class="<?php if ($balance > 0 || $balance < 0) { echo "text-danger"; }else{ echo "text-dark"; } ?> float-right"> <?php echo numfmt_format_currency($currency_format, $balance, $client_currency_code); ?></span>
                    </div>
                    <div class="ml-1 mt-1 text-secondary">Monthly Recurring
                        <span class="text-dark float-right"> <?php echo numfmt_format_currency($currency_format, $recurring_monthly, $client_currency_code); ?></span>
                    </div>
                    <div class="ml-1 mt-1 text-secondary">Net Terms
                        <span class="text-dark float-right"><?php echo $client_net_terms; ?><small class="text-secondary ml-1">Days</small></span>
                    </div>
                    <?php if(!empty($client_tax_id_number)) { ?>
                    <div class="ml-1 mt-1 text-secondary">Tax ID
                        <span class="text-dark float-right"><?php echo $client_tax_id_number; ?></span>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>


                <div class="col-md border-left border-top">
                    <h5 class="text-secondary mt-1">Support</h5>
                    <div class="ml-1 text-secondary">Open Tickets
                        <span class="text-dark float-right"><?php echo $num_active_tickets; ?></span>
                    </div>
                    <div class="ml-1 text-secondary mt-1">Closed Tickets
                        <span class="text-dark float-right"><?php echo $num_closed_tickets; ?></span>
                    </div>
                    <?php
                    if (!empty($client_tag_name_display_array)) { ?>
                    <hr>
                    <?php echo $client_tags_display; ?>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
