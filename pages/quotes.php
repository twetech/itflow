<?php

// Default Column Sortby/Order Filter
$sort = "quote_number";
$order = "DESC";

// Set Datatable Order
$datatable_order = "[[4, 'desc']]";
require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM quotes
    LEFT JOIN clients ON quote_client_id = client_id
    LEFT JOIN categories ON quote_category_id = category_id");

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fa fa-comment-dollar mr-2"></i>Quotes</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="quote_add_modal.php"><i class="fas fa-plus mr-2"></i>New Quote</button>
            </div>
        </div>

        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">
                <table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th>Number</a></th>
                        <th>Scope</a></th>
                        <th>Client</a></th>
                        <th class="text-right">Amount</a></th>
                        <th>Date</a></th>
                        <th>Expire</a></th>
                        <th>Category</a></th>
                        <th>Status</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $quote_id = intval($row['quote_id']);
                        $quote_prefix = nullable_htmlentities($row['quote_prefix']);
                        $quote_number = intval($row['quote_number']);
                        $quote_scope = nullable_htmlentities($row['quote_scope']);
                        if (empty($quote_scope)) {
                            $quote_scope_display = "-";
                        } else {
                            $quote_scope_display = $quote_scope;
                        }
                        $quote_status = nullable_htmlentities($row['quote_status']);
                        $quote_date = nullable_htmlentities($row['quote_date']);
                        $quote_expire = nullable_htmlentities($row['quote_expire']);
                        $quote_amount = floatval($row['quote_amount']);
                        $quote_discount = floatval($row['quote_discount_amount']);
                        $quote_currency_code = nullable_htmlentities($row['quote_currency_code']);
                        $quote_created_at = nullable_htmlentities($row['quote_created_at']);
                        $client_id = intval($row['client_id']);
                        $client_name = nullable_htmlentities($row['client_name']);
                        $client_currency_code = nullable_htmlentities($row['client_currency_code']);
                        $category_id = intval($row['category_id']);
                        $category_name = nullable_htmlentities($row['category_name']);
                        $client_net_terms = intval($row['client_net_terms']);
                        if ($client_net_terms == 0) {
                            $client_net_terms = $config_default_net_terms;
                        }

                        if ($quote_status == "Sent") {
                            $quote_badge_color = "warning text-white";
                        } elseif ($quote_status == "Viewed") {
                            $quote_badge_color = "primary";
                        } elseif ($quote_status == "Accepted") {
                            $quote_badge_color = "success";
                        } elseif ($quote_status == "Declined") {
                            $quote_badge_color = "danger";
                        } elseif ($quote_status == "Invoiced") {
                            $quote_badge_color = "info";
                        } else {
                            $quote_badge_color = "secondary";
                        }
                        ?>

                        <tr>
                            <td class="text-bold"><a href="/pages/quote.php?quote_id=<?= $quote_id; ?>"><?= "$quote_prefix$quote_number"; ?></a></td>
                            <td><?= $quote_scope_display; ?></td>
                            <td class="text-bold"><a href="/pages/client/client_quotes.php?client_id=<?= $client_id; ?>"><?= $client_name; ?></a></td>
                            <td class="text-right text-bold"><?= numfmt_format_currency($currency_format, $quote_amount, $quote_currency_code); ?></td>
                            <td><?= $quote_date; ?></td>
                            <td><?= $quote_expire; ?></td>
                            <td><?= $category_name; ?></td>
                            <td>
                                <span class="p-2 badge bg-label-<?= $quote_badge_color; ?>">
                                    <?= $quote_status; ?>
                                </span>
                            </td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="#" class="dropdown-item loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="quote_edit_modal.php?quote_id=<?= $quote_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <a href="#" class="dropdown-item loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="quote_copy_modal.php?quote_id=<?= $quote_id; ?>">
                                            <i class="fas fa-fw fa-copy mr-2"></i>Copy
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <?php if (!empty($config_smtp_host)) { ?>
                                            <a class="dropdown-item" href="/post.php?email_quote=<?= $quote_id; ?>">
                                                <i class="fas fa-fw fa-paper-plane mr-2"></i>Email
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        <?php } ?>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_quote=<?= $quote_id; ?>">
                                            <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <?php

                        require "/var/www/portal.twe.tech/includes/modals/quote_copy_modal.php";


                    }

                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php


require_once "/var/www/portal.twe.tech/includes/modals/quote_edit_modal.php";

require_once "/var/www/portal.twe.tech/includes/footer.php";

