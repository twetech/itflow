<?php

// Default Column Sortby/Order Filter
$sort = "client_accessed_at";
$order = "DESC";

require_once "/var/www/develop.twe.tech/includes/inc_all.php";

// Leads Query

$leads = 0;

if (isset($_GET['leads'])) {
    $leads = intval($_GET['leads']);
}

if($leads == 1){
    $leads_query = 1;
} else {
    $leads_query = 0;
}

//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "
    SELECT SQL_CALC_FOUND_ROWS clients.*, contacts.*, locations.*, GROUP_CONCAT(tags.tag_name) AS tag_names
    FROM clients
    LEFT JOIN contacts ON clients.client_id = contacts.contact_client_id AND contact_primary = 1
    LEFT JOIN locations ON clients.client_id = locations.location_client_id AND location_primary = 1
    LEFT JOIN client_tags ON client_tags.client_tag_client_id = clients.client_id
    LEFT JOIN tags ON tags.tag_id = client_tags.client_tag_tag_id
    WHERE (clients.client_name LIKE '%$q%' OR clients.client_type LIKE '%$q%' OR clients.client_referral LIKE '%$q%'
           OR contacts.contact_email LIKE '%$q%' OR contacts.contact_name LIKE '%$q%' OR contacts.contact_phone LIKE '%$phone_query%'
           OR contacts.contact_mobile LIKE '%$phone_query%' OR locations.location_address LIKE '%$q%'
           OR locations.location_city LIKE '%$q%' OR locations.location_state LIKE '%$q%' OR locations.location_zip LIKE '%$q%'
           OR tags.tag_name LIKE '%$q%' OR clients.client_tax_id_number LIKE '%$q%')
      AND clients.client_archived_at IS NULL
      AND clients.client_lead = $leads
    GROUP BY clients.client_id
    ORDER BY $sort $order
   
");

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <header class="card-header d-flex align-items-center">
            <h3 class="card-title mt-2"><i class="fa fa-fw fa-user-friends mr-2"></i><?php if($leads == 0){ echo "Client"; } else { echo "Lead"; } ?> Management</h3>
                <?php if ($session_user_role == 3) { ?>
                    <ul class="list-inline ml-auto mb0">
                        <li class="list-inline-item mr3">
                            <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="text-dark loadModalContentBtn" data-modal-file="client_add_modal.php?leads=<?php echo $leads; ?>">
                                <i class="fa fa-fw fa-plus mr-2"></i><!-- Add Client -->
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#exportClientModal" class="text-dark">
                                <i class="fa fa-fw fa-download mr-2"></i><!-- Export Clients -->
                            </a>
                        </li>
                    </ul>
                <?php } ?>
        </header>

        <div class="card-body p-2 p-md-3">

            <div class="card-datatable table-responsive pt-0">                <table id='responsive' class="responsive table table-hover">
                    <thead class="<?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                                <th>Name</th>
                                <th>Primary Location</th>
                                <th>Primary Contact</th>
                                <?php if (($session_user_role == 3 || $session_user_role == 1) && $config_module_enable_accounting == 1) { ?> <th class="text-right">Billing</th> <?php } ?>
                                <?php if ($session_user_role == 3) { ?> <th class="text-center">Action</th> <?php } ?>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $client_id = intval($row['client_id']);
                        $client_name = nullable_htmlentities($row['client_name']);
                        $client_type = nullable_htmlentities($row['client_type']);
                        $location_id = intval($row['location_id']);
                        $location_country = nullable_htmlentities($row['location_country']);
                        $location_address = nullable_htmlentities($row['location_address']);
                        $location_city = nullable_htmlentities($row['location_city']);
                        $location_state = nullable_htmlentities($row['location_state']);
                        $location_zip = nullable_htmlentities($row['location_zip']);
                        if (empty($location_address) && empty($location_city) && empty($location_state) && empty($location_zip)) {
                            $location_address_display = "-";
                        } else {
                            $location_address_display = "$location_address<br>$location_city $location_state $location_zip";
                        }
                        $contact_id = intval($row['contact_id']);
                        $contact_name = nullable_htmlentities($row['contact_name']);
                        $contact_title = nullable_htmlentities($row['contact_title']);
                        $contact_phone = formatPhoneNumber($row['contact_phone']);
                        $contact_extension = nullable_htmlentities($row['contact_extension']);
                        $contact_mobile = formatPhoneNumber($row['contact_mobile']);
                        $contact_email = nullable_htmlentities($row['contact_email']);
                        $client_website = nullable_htmlentities($row['client_website']);
                        $client_rate = floatval($row['client_rate']);
                        $client_currency_code = nullable_htmlentities($row['client_currency_code']);
                        $client_net_terms = intval($row['client_net_terms']);
                        $client_tax_id_number = nullable_htmlentities($row['client_tax_id_number']);
                        $client_referral = nullable_htmlentities($row['client_referral']);
                        $client_notes = nullable_htmlentities($row['client_notes']);
                        $client_created_at = date('Y-m-d', strtotime($row['client_created_at']));
                        $client_updated_at = nullable_htmlentities($row['client_updated_at']);
                        $client_archive_at = nullable_htmlentities($row['client_archived_at']);
                        $client_is_lead = intval($row['client_lead']);

                        // Client Tags

                        $client_tag_name_display_array = array();
                        $client_tag_id_array = array();
                        $sql_client_tags = mysqli_query($mysqli, "SELECT * FROM client_tags LEFT JOIN tags ON client_tags.client_tag_tag_id = tags.tag_id WHERE client_tags.client_tag_client_id = $client_id ORDER BY tag_name ASC");
                        while ($row = mysqli_fetch_array($sql_client_tags)) {

                            $client_tag_id = intval($row['tag_id']);
                            $client_tag_name = nullable_htmlentities($row['tag_name']);
                            $client_tag_color = nullable_htmlentities($row['tag_color']);
                            if (empty($client_tag_color)) {
                                $client_tag_color = "dark";
                            }
                            $client_tag_icon = nullable_htmlentities($row['tag_icon']);
                            if (empty($client_tag_icon)) {
                                $client_tag_icon = "tag";
                            }

                            $client_tag_id_array[] = $client_tag_id;
                            $client_tag_name_display_array[] = "<a href='clients.php?q=$client_tag_name'><span class='badge text-light p-1 mr-1' style='background-color: $client_tag_color;'><i class='fa fa-fw fa-$client_tag_icon mr-2'></i>$client_tag_name</span></a>";
                        }
                        $client_tags_display = implode('', $client_tag_name_display_array);

                        //Get Client Balance and Amount Paid
                        $sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments, invoices WHERE payment_invoice_id = invoice_id AND invoice_client_id = $client_id");
                        $row = mysqli_fetch_array($sql_amount_paid);

                        $amount_paid = floatval($row['amount_paid']);

                        $balance = getClientBalance( $client_id);
                        //set Text color on balance
                        if ($balance > 0) {
                            $balance_text_color = "text-danger font-weight-bold";
                        } else {
                            $balance_text_color = "";
                        }

                        //Get Monthly Recurring Total
                        $sql_recurring_monthly_total = mysqli_query($mysqli, "SELECT SUM(recurring_amount) AS recurring_monthly_total FROM recurring WHERE recurring_status = 1 AND recurring_frequency = 'month' AND recurring_client_id = $client_id");
                        $row = mysqli_fetch_array($sql_recurring_monthly_total);

                        $recurring_monthly_total = floatval($row['recurring_monthly_total']);

                        //Get Yearly Recurring Total
                        $sql_recurring_yearly_total = mysqli_query($mysqli, "SELECT SUM(recurring_amount) AS recurring_yearly_total FROM recurring WHERE recurring_status = 1 AND recurring_frequency = 'year' AND recurring_client_id = $client_id");
                        $row = mysqli_fetch_array($sql_recurring_yearly_total);

                        $recurring_yearly_total = floatval($row['recurring_yearly_total']) / 12;

                        $recurring_monthly = $recurring_monthly_total + $recurring_yearly_total;

                        ?>
                        <tr>
                            <td>
                                <a class="font-weight-bold" href="/pages/client/client_overview.php?client_id=<?php echo $client_id; ?>"><?php echo $client_name; ?> <i class="fas fa-fw fa-arrow-circle-right"></i></a>

                                <?php
                                if (!empty($client_type)) {
                                ?>
                                    <div class="text-secondary mt-1">
                                        <?php echo $client_type; ?>
                                    </div>
                                <?php } ?>
                                <?php
                                if (!empty($client_tags_display)) { ?>
                                    <div class="mt-1">
                                        <?php echo $client_tags_display; ?>
                                    </div>
                                <?php } ?>
                                <div class="mt-1 text-secondary">
                                    <small><strong>Created:</strong> <?php echo $client_created_at; ?></small>
                                </div>

                            </td>
                            <td><?php echo $location_address_display; ?></td>
                            <td>
                                <?php
                                if (empty($contact_name) && empty($contact_phone) && empty($contact_mobile) && empty($client_email)) {
                                    echo "-";
                                }

                                if (!empty($contact_name)) { ?>
                                    <div class="text-bold">
                                        <i class="fa fa-fw fa-user text-secondary mr-2 mb-2"></i><?php echo $contact_name; ?>
                                    </div>
                                <?php } else {
                                    echo "-";
                                }

                                if (!empty($contact_phone)) { ?>
                                    <div class="mt-1">
                                        <i class="fa fa-fw fa-phone text-secondary mr-2 mb-2"></i><?php echo $contact_phone; ?> <?php if (!empty($contact_extension)) { echo "x$contact_extension"; } ?>
                                    </div>
                                <?php }

                                if (!empty($contact_mobile)) { ?>
                                    <div class="mt-1">
                                        <i class="fa fa-fw fa-mobile-alt text-secondary mr-2"></i><?php echo $contact_mobile; ?>
                                    </div>
                                <?php }

                                if (!empty($contact_email)) { ?>
                                    <div class="mt-1">
                                        <i class="fa fa-fw fa-envelope text-secondary mr-2"></i><a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a><button class='btn btn-sm clipboardjs' data-clipboard-text='<?php echo $contact_email; ?>'><i class='far fa-copy text-secondary'></i></button>
                                    </div>
                                <?php } ?>
                            </td>

                            <!-- Show Billing for Admin/Accountant roles only and if accounting module is enabled -->
                            <?php if (($session_user_role == 3 || $session_user_role == 1) && $config_module_enable_accounting == 1) { ?>
                                <td class="text-right">
                                    <div class="mt-1">
                                        <span class="text-secondary">Balance</span> <span class="<?php echo $balance_text_color; ?>"><?php echo numfmt_format_currency($currency_format, $balance, $session_company_currency); ?></span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-secondary">Paid</span> <?php echo numfmt_format_currency($currency_format, $amount_paid, $session_company_currency); ?>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-secondary">Monthly</span> <?php echo numfmt_format_currency($currency_format, $recurring_monthly, $session_company_currency); ?>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-secondary">Hourly Rate</span> <?php echo numfmt_format_currency($currency_format, $client_rate, $session_company_currency); ?>
                                    </div>
                                </td>
                            <?php } ?>

                            <!-- Show actions for Admin role only -->
                            <?php if ($session_user_role == 3) { ?>
                                <td>
                                    <ul>
                                        <div class="dropdown dropleft text-center">
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="#!" data-bs-toggle="modal" data-bs-target="#dynamicModal" class="dropdown-item loadModalContentBtn" data-modal-file="client_edit_modal.php?client_id=<?php echo $client_id; ?>">
                                                    <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_client=<?php echo $client_id; ?>">
                                                    <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                                </a>
                                            </div>
                                        </div>
                                    </ul>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php


require_once "/var/www/develop.twe.tech/includes/footer.php";

