<?php

$page_css = '<link rel="stylesheet" href="/includes/assets/vendor/css/pages/app-invoice.css" />';

$invoice_id = intval($_GET['invoice_id']);

// TODO: Put this in company settings
$margin_goal = 18;


require_once "/var/www/portal.twe.tech/includes/inc_all.php";



if (isset($_GET['invoice_id'])) {

    $sql = mysqli_query(
        $mysqli,
        "SELECT * FROM invoices
        LEFT JOIN clients ON invoice_client_id = client_id
        LEFT JOIN contacts ON clients.client_id = contacts.contact_client_id AND contact_primary = 1
        LEFT JOIN locations ON clients.client_id = locations.location_client_id AND location_primary = 1
        WHERE invoice_id = $invoice_id"
    );

    if (mysqli_num_rows($sql) == 0) {
        echo '<h1 class="text-secondary mt-5" style="text-align: center">Nothing to see here</h1>';
        require_once '/var/www/portal.twe.tech/includes/footer.php';

        exit();
    }

    $row = mysqli_fetch_array($sql);
    $invoice_id = intval($row['invoice_id']);
    $invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
    $invoice_number = intval($row['invoice_number']);
    $invoice_scope = nullable_htmlentities($row['invoice_scope']);
    $invoice_status = nullable_htmlentities($row['invoice_status']);
    $invoice_date = nullable_htmlentities($row['invoice_date']);
    $invoice_due = nullable_htmlentities($row['invoice_due']);
    $invoice_amount = floatval($row['invoice_amount']);
    $invoice_discount = floatval($row['invoice_discount_amount']);
    $invoice_currency_code = nullable_htmlentities($row['invoice_currency_code']);
    $invoice_note = nullable_htmlentities($row['invoice_note']);
    $invoice_url_key = nullable_htmlentities($row['invoice_url_key']);
    $invoice_created_at = nullable_htmlentities($row['invoice_created_at']);
    $category_id = intval($row['invoice_category_id']);
    $client_id = intval($row['client_id']);
    $client_name = nullable_htmlentities($row['client_name']);
    $location_address = nullable_htmlentities($row['location_address']);
    $location_city = nullable_htmlentities($row['location_city']);
    $location_state = nullable_htmlentities($row['location_state']);
    $location_zip = nullable_htmlentities($row['location_zip']);
    $contact_email = nullable_htmlentities($row['contact_email']);
    $contact_phone = formatPhoneNumber($row['contact_phone']);
    $contact_extension = nullable_htmlentities($row['contact_extension']);
    $contact_mobile = formatPhoneNumber($row['contact_mobile']);
    $client_website = nullable_htmlentities($row['client_website']);
    $client_currency_code = nullable_htmlentities($row['client_currency_code']);
    $client_net_terms = intval($row['client_net_terms']);
    if ($client_net_terms == 0) {
        $client_net_terms = $config_default_net_terms;
    }

    $sql = mysqli_query($mysqli, "SELECT * FROM companies WHERE company_id = 1");
    $row = mysqli_fetch_array($sql);
    $company_id = intval($row['company_id']);
    $company_name = nullable_htmlentities($row['company_name']);
    $company_country = nullable_htmlentities($row['company_country']);
    $company_address = nullable_htmlentities($row['company_address']);
    $company_city = nullable_htmlentities($row['company_city']);
    $company_state = nullable_htmlentities($row['company_state']);
    $company_zip = nullable_htmlentities($row['company_zip']);
    $company_phone = formatPhoneNumber($row['company_phone']);
    $company_email = nullable_htmlentities($row['company_email']);
    $company_website = nullable_htmlentities($row['company_website']);
    $company_logo = nullable_htmlentities($row['company_logo']);
    if (!empty($company_logo)) {
        $company_logo_base64 = base64_encode(file_get_contents("/uploads/settings/$company_logo"));
    }
    $sql_history = mysqli_query($mysqli, "SELECT * FROM history WHERE history_invoice_id = $invoice_id ORDER BY history_id DESC");

    $sql_payments = mysqli_query($mysqli, "SELECT * FROM payments, accounts WHERE payment_account_id = account_id AND payment_invoice_id = $invoice_id ORDER BY payments.payment_id DESC");

    $sql_tickets = mysqli_query($mysqli, "
        SELECT
            tickets.*,
            SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(ticket_reply_time_worked, '%H:%i:%s')))) AS 'total_time_worked'
        FROM
            tickets
        LEFT JOIN
            ticket_replies ON tickets.ticket_id = ticket_replies.ticket_reply_ticket_id
        WHERE
            ticket_invoice_id = $invoice_id
        GROUP BY
            tickets.ticket_id
        ORDER BY
            ticket_id DESC
    ");

    //Get billable, and unbilled tickets to add to invoice
    $sql_tickets_billable = mysqli_query(
        $mysqli,
        "
        SELECT
            *
        FROM
            tickets
        WHERE
            ticket_client_id = $client_id
        AND
            ticket_billable = 1
        AND
            ticket_invoice_id = 0
        AND
            ticket_status LIKE '%close%';
    "
    );

    //Product autocomplete
    $products_sql = mysqli_query($mysqli, "SELECT product_name AS label, product_description AS description, product_price AS price, product_tax_id AS tax, product_id AS productId FROM products WHERE product_archived_at IS NULL");

    if (mysqli_num_rows($products_sql) > 0) {
        while ($row = mysqli_fetch_assoc($products_sql)) {
            $products[] = $row;
        }
        $json_products = json_encode($products);
    }


    //Add up all the payments for the invoice and get the total amount paid to the invoice
    $sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments WHERE payment_invoice_id = $invoice_id");
    $row = mysqli_fetch_array($sql_amount_paid);
    $amount_paid = floatval($row['amount_paid']);

    $balance = $invoice_amount - $amount_paid;

    //check to see if overdue
    if ($invoice_status !== "Paid" && $invoice_status !== "Draft" && $invoice_status !== "Cancelled") {
        $unixtime_invoice_due = strtotime($invoice_due) + 86400;
        if ($unixtime_invoice_due < time()) {
            $invoice_overdue = "Overdue";
        }
    }
?>


    <div class="row invoice-edit">
        <!-- Invoice Edit-->
        <div class="col-lg-9 col-12 mb-lg-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row p-sm-3 p-0">
                        <div class="col-md-6 mb-md-0 mb-4">
                            <div class="d-flex svg-illustration mb-4 gap-2">
                                <span class="app-brand-text demo text-body fw-bold"><?php echo $company_name; ?></span>
                            </div>
                            <p class="mb-1"><?php echo $company_address; ?></p>
                            <p class="mb-1"><?php echo "$company_city $company_state $company_zip"; ?></p>
                            <p class="mb-1"><?php echo "$company_phone $company_email"; ?></p>
                            <p class="mb-0"><?php echo $company_website; ?></p>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-2">
                                <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end">
                                    <span class="h4 text-capitalize mb-0 text-nowrap">Invoice <?= $invoice_prefix ?></span>
                                </dt>
                                <dd class="col-sm-6 d-flex justify-content-md-end">
                                    <div class="w-px-150">
                                        <input type="text" class="form-control" disabled placeholder="<?php echo "$invoice_number"; ?>" value="<?php echo "$invoice_number"; ?>" id="invoiceId" />
                                    </div>
                                </dd>
                                <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end">
                                    <span class="fw-normal">Status:</span>
                                </dt>
                                <dd class="col-sm-6 d-flex justify-content-md-end">
                                    <div class="w-px-150">
                                        <select class="form-select invoice-status" id="invoiceStatus">
                                            <option value="Draft" <?=$invoice_status == 'Draft' ? 'selected' : 'disabled'?>>Draft</option>
                                            <option value="Sent" <?=$invoice_status == 'Sent' ? 'selected' : 'disabled'?>>Sent</option>
                                            <option value="Viewed" <?=$invoice_status == 'Viewed' ? 'selected' : 'disabled'?>>Viewed</option>
                                            <option value="Paid" <?=$invoice_status == 'Paid' ? 'selected' : 'disabled'?>>Paid</option>
                                            <option value="Partial" <?=$invoice_status == 'Partial' ? 'selected' : 'disabled'?>>Partial</option>
                                            <option value="Overdue" <?=$invoice_status == 'Overdue' ? 'selected' : 'disabled'?>>Overdue</option>
                                            <option value="Cancelled" <?=$invoice_status == 'Cancelled' ? 'selected' : 'disabled'?>>Cancelled</option>
                                        </select>
                                    </div>
                                </dd>
                                <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end">
                                    <span class="fw-normal">Date:</span>
                                </dt>
                                <dd class="col-sm-6 d-flex justify-content-md-end">
                                    <div class="w-px-150">
                                        <input type="text" class="form-control invoice-date" placeholder="YYYY-MM-DD" value="<?= $invoice_date ?>" />
                                    </div>
                                </dd>
                                <dt class="col-sm-6 mb-2 mb-sm-0 text-md-end">
                                    <span class="fw-normal">Due Date:</span>
                                </dt>
                                <dd class="col-sm-6 d-flex justify-content-md-end">
                                    <div class="w-px-150">
                                        <input type="text" class="form-control due-date" placeholder="YYYY-MM-DD" value="<?= $invoice_due ?>" />
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <hr class="my-4 mx-n4" />

                    <div class="row p-sm-3 p-0">
                        <div class="col-md-6 col-sm-5 col-12 mb-sm-0 mb-4">
                            <h6 class="pb-2">Invoice To:</h6>
                            <p class="mb-1"><strong><?php echo $contact_name; ?></strong></p>
                            <p class="mb-1"><strong><?php echo $client_name; ?></strong></p>
                            <p class="mb-1"><?php echo $location_address; ?></p>
                            <p class="mb-1"><?php echo "$location_city $location_state $location_zip"; ?></p>
                            <p class="mb-1"><?php echo "$contact_phone $contact_extension"; ?></p>
                            <p class="mb-1"><?php echo $contact_mobile; ?></p>
                            <p class="mb-0"><?php echo $contact_email; ?></p>


                        </div>
                    </div>


                    <div class="mb-3">

                    <?php $sql_invoice_items = mysqli_query($mysqli, 
                        "SELECT * FROM invoice_items
                        LEFT JOIN taxes ON item_tax_id = tax_id
                        WHERE item_invoice_id = $invoice_id
                        ORDER BY item_order ASC"); 

                    $subtotal = 0;
                    $discount_total = 0;
                    $tax_total = 0;
                    $total_cost = 0;
                    ?>

        
                    <?php while ($row = mysqli_fetch_array($sql_invoice_items)) {
                        $item_id = intval($row['item_id']);
                        $item_name = nullable_htmlentities($row['item_name']);
                        $item_description = nullable_htmlentities($row['item_description']);
                        $item_price = floatval($row['item_price']);
                        $item_qty = floatval($row['item_quantity']);
                        $item_discount = floatval($row['item_discount']);
                        $item_tax_id = floatval($row['item_tax_id']);
                        $item_tax = floatval($row['item_tax']);
                        $item_subtotal = $item_price * $item_qty; // Calculate the total of the item
                        $tax_percent = floatval($row['tax_percent']);
                        $tax_name = nullable_htmlentities($row['tax_name']);
                        $item_product_id = intval($row['item_product_id']);

                        $tax_total += $item_tax; // Calculate the total tax
                        $item_total = $item_subtotal + $item_tax - $item_discount; // Calculate the total of the item after tax and discount

                        $subtotal += $item_subtotal; // Calculate the subtotal of all items
                        $discount_total += $item_discount; // Calculate the total discount

                        // Calculate the discount percentage
                        if ($item_discount > 0) {
                            $item_discount_percent = ($item_discount / $item_subtotal) * 100;
                        } else {
                            $item_discount_percent = 0;
                        }

                        $profit = 0;

                        if ($item_product_id > 0) {
                            $product_sql = mysqli_query($mysqli, "SELECT * FROM products WHERE product_id = $item_product_id");
                            $product_row = mysqli_fetch_array($product_sql);
                            $product_cost = floatval($product_row['product_cost']);
                            $item_cost = $item_qty * $product_cost;

                            $total_cost += $item_cost;

                            $item_profit = $item_subtotal - ($item_qty * $product_cost);
                            $profit += $item_profit;

                            if ($item_subtotal != 0) {
                                $item_margin = $item_profit / $item_subtotal;
                            } else {
                                $item_margin = 0;  // Default or error value if subtotal is zero
                            }
                            
                            if ($item_cost != 0) {
                                $item_markup = $item_profit / $item_cost;
                            } else {
                                $item_markup = 0;  // Default or error value if cost is zero
                            }
                        } else {
                            $item_cost = 0;
                            $item_profit = 0;
                            $item_margin = 0;
                            $item_markup = 0;
                        }

                    ?>

                        <hr class="mx-n4" />

                        <div class="pt-0 pt-md-4 mb-4">
                            <form action="/post.php" method="post" autocomplete="off" enctype="multipart/form-data" >
                                <input type="hidden" name="invoice_id" value="<?=$invoice_id?>" />
                                <input type="hidden" name="item_id" value="<?=$item_id?>" />
                                <div id="item<?=$item_id?>" class="d-flex border rounded position-relative pe-0 item-container">
                                    <div class="row w-100 m-0 p-3">
                                        <div class="col-md-6 col-12 mb-md-0 mb-3 ps-md-0">
                                            <p class="mb-2 repeater-title">Item</p>
                                            <input type="text" class="form-control invoice-item-name mb-2" value="<?= $item_name ?>" name="name"/>
                                            <textarea class="form-control" rows="2" id="item_<?=$item_id?>_description" name="description"><?= $item_description ?></textarea>
                                        </div>
                                        <div class="col-md-3 col-12 mb-md-0 mb-3">
                                            <p class="mb-2 repeater-title">Unit Price</p>
                                            <input type="text" name="price" class="form-control invoice-item-price mb-2" value="<?= $item_price ?>" placeholder="<?= $item_price ?>"/>
                                            <div class="d-flex me-1">
                                                <span class="discount me-1"  data-bs-toggle="tooltip" data-bs-placement="top" title="Discount: <?=numfmt_format_currency($currency_format, $item_discount, $client_currency_code)?>"><?=number_format($item_discount_percent, 0)?>%</span>
                                                <span class="tax me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Tax: <?= numfmt_format_currency($currency_format, $item_tax, $client_currency_code)?>"><?=number_format($tax_percent, 3)?>%</span>

                                            </div>
                                            <div class="d-flex me-1">
                                                <span class="markup me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Markup: <?=numfmt_format_currency($currency_format, $item_profit, $client_currency_code)?>"><?=number_format($item_markup*100, 0)?>%</span>
                                                <span class="margin me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Margin"><?=number_format($item_margin*100, 0)?>%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12 mb-md-0 mb-3">
                                            <p class="mb-2 repeater-title">Qty</p>
                                            <input type="number" name="qty"  class="form-control invoice-item-qty" value="<?=$item_qty?>" placeholder="<?=$item_qty?>" min="" max="" />
                                        </div>
                                        <div class="col-md-1 col-12 pe-0">
                                            <p class="mb-2 repeater-title">Line Total</p>
                                            <p class="mb-0"><?=numfmt_format_currency($currency_format, $item_total, $client_currency_code)?></p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                        <a href="/post.php?delete_invoice_item=<?=$item_id?>&invoice_id=<?=$invoice_id?>">
                                            <i class="bx bx-x fs-4 text-muted cursor-pointer"></i>
                                        </a>
                                        <button id="SaveItem<?=$item_id?>" type="submit" name="edit_item" class="btn btn-link text-primary p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Save Changes" hidden>
                                            <i class="bx bx-check fs-4"></i>
                                        </button>
                                        <div class="dropdown">
                                            <i class="bx bx-cog bx-xs text-muted cursor-pointer more-options-dropdown" role="button" id="dropdownMenuButton" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                            </i>
                                            <div class="dropdown-menu dropdown-menu-end w-px-300 p-3" aria-labelledby="dropdownMenuButton">
                                                <div class="row g-3">
                                                    <div class="col-6" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $invoice_currency_code; ?> or end with % ">
                                                        <label for="discountInput" class="form-label">Discount (<?php echo $invoice_currency_code; ?>) </label>
                                                        <input class="form-control" name="discount" id="discount" <?=$item_discount ? 'value="'.$item_discount.'"' : 'placeholder="0%"'?> />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="taxInput1" class="form-label">Tax</label>
                                                        <select class="form-select select2 invoice-item-tax mb-2" name="tax_id" id="tax" style="width: 100%;">
                                                            <option value="0">No Tax</option>
                                                            <?php
                                                            $tax_sql = "SELECT * FROM taxes";
                                                            $tax_result = mysqli_query($mysqli, $tax_sql);

                                                            while ($tax_row = mysqli_fetch_assoc($tax_result)) {
                                                                $tax_id = $tax_row['tax_id'];
                                                                $tax_name = $tax_row['tax_name'];
                                                                $tax_rate = $tax_row['tax_percent'];
                                                                ?>

                                                                <option value="<?=$tax_id?>" data-rate="<?=$tax_rate?>" <?php if ($tax_id == $item_tax_id) { echo 'selected'; } ?>>
                                                                    <?=$tax_name?> (<?=$tax_rate?>%)
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="dropdown-divider"></div>
                                                <div class="row g-3">
                                                    <div class="col">
                                                    <label for="product_id" class="form-label">Product</label>
                                                        <div class="input-group">
                                                            <select class="form-select select2" name="product_id" id="product_id">
                                                                <?php
                                                                $product_sql = "SELECT * FROM products ORDER BY product_name";
                                                                $product_result = mysqli_query($mysqli, $product_sql);

                                                                while ($product_row = mysqli_fetch_assoc($product_result)) {
                                                                    $product_id = $product_row['product_id'];
                                                                    $product_name = $product_row['product_name'];
                                                                    ?>

                                                                    <option value="<?=$product_id?>" <?php if ($product_id == $item_product_id) { echo 'selected'; } ?>>
                                                                        <?=$product_name?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                            <button type="submit" name="add_item_product" class="btn btn-primary mt-2">
                                                                <i class="bx bx-plus"></i>
                                                            </button>                                                            
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="button" class="btn btn-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="invoice_add_item_modal.php?invoice_id=<?=$invoice_id?>">
                            <i class="bx bx-plus me-1"></i>Add Item
                        </button>
                    </div>


                    <hr class="my-4 mx-n4" />

                    <div class="row py-sm-3">
                        <div class="col-md-8 mb-md-0 mb-3">
                            <div class="mb-3">
                                <label for="note" class="form-label fw-medium">Note:</label>
                                <textarea class="form-control" rows="2" id="note">
                                    <?=$invoice_note?>
                                </textarea>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            <div class="invoice-calculations">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="w-px-100">Subtotal:</span>
                                    <span class="fw-medium"><?=numfmt_format_currency($currency_format, $subtotal, $client_currency_code)?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="w-px-100">Discount:</span>
                                    <span class="fw-medium"><?=numfmt_format_currency($currency_format, $discount_total, $client_currency_code)?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="w-px-100">Tax:</span>
                                    <span class="fw-medium"><?=numfmt_format_currency($currency_format, $tax_total, $client_currency_code)?></span>
                                </div>
                                <hr />
                                <div class="d-flex justify-content-between">
                                    <span class="w-px-100">Total:</span>
                                    <span class="fw-medium"><?=numfmt_format_currency($currency_format, $subtotal-$discount_total+$tax_total, $client_currency_code)?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /Invoice Edit-->

        <!-- Invoice Actions -->
        <div class="col-lg-3 col-12 invoice-actions">
            <div class="card mb-4">
                <div class="card-body">
                    <?php if ($invoice_status == 'Draft') { ?>
                        <div class="d-grid d-flex my-3 w-100">
                            <button class="btn btn-primary dropdown-toggle d-grid w-100 d-flex align-items-center justify-content-center text-nowrap" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-fw fa-paper-plane me-1"></i>Send
                            </button>
                            <div class="dropdown-menu">
                                <?php if (!empty($config_smtp_host) && !empty($contact_email)) { ?>
                                    <a class="dropdown-item" href="/post.php?email_invoice=<?php echo $invoice_id; ?>">
                                        <i class="fas fa-fw fa-paper-plane mr-2"></i>Send Email
                                    </a>
                                    <div class="dropdown-divider"></div>
                                <?php } ?>
                                <a class="dropdown-item" href="/post.php?mark_invoice_sent=<?php echo $invoice_id; ?>">
                                    <i class="fas fa-fw fa-check mr-2"></i>Mark Sent
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="d-grid d-flex  my-3 w-100">
                        <a target="_blank" href="/portal/guest_view_invoice.php?invoice_id=<?php echo "$invoice_id&url_key=$invoice_url_key"; ?>" class="btn btn-label-primary me-3 w-100">
                            <i class="bx bx-show me-1"></i>
                            View
                        </a>
                        <button class="btn btn-primary d-grid w-100 loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="invoice_payment_add_modal.php?invoice_id=<?=$invoice_id?>&balance=<?=$balance?>">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="bx bx-dollar bx-xs me-1"></i>Add Payment</span>
                        </button>
                    </div>
                    <div class="d-grid d-flex my-3">
                        <a href="/post.php?cancel_invoice=<?=$invoice_id?>" class="btn btn-label-danger me-3 w-100"><i class="bx bx-x-circle me-1"></i>Cancel</a>
                        <a href="/post.php?delete_invoice=<?=$invoice_id?>" class="btn btn-danger me-3 w-100"><i class="bx bx-trash me-1"></i></a>
                    </div>
                    <hr class="my-0" />

                    <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-dollar me-2"></i>
                            <span class="fw-medium">Amount Due:</span>
                        </div>
                        <span class="fw-medium"><?=numfmt_format_currency($currency_format, $balance, $client_currency_code)?></span>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-credit-card me-2"></i>
                            <span class="fw-medium">Amount Paid:</span>
                        </div>
                        <span class="fw-medium"><?=numfmt_format_currency($currency_format, $amount_paid, $client_currency_code)?></span>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-dollar me-2"></i>
                            <span class="fw-medium">Margin:</span>
                        </div>
                        <?php
                            if ($total_cost != 0) {
                                $margin = $profit / $subtotal;
                            } else {
                                $margin = 0;  // Default or error value if cost is zero
                            }
                            if ($margin < $margin_goal/100) {
                                echo "<span class='fw-medium text-danger'>";
                            } else {
                                echo "<span class='fw-medium text-success'>";
                            }
                            echo number_format($margin*100, 1) . "%";
                            echo "</span>";
                            ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Invoice Actions -->
    </div>

<!-- Include jQuery UI -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
    $(document).on('modalContentLoaded', function() {
        // Bind event handlers to the inputs after the modal content has been loaded
        // Get the description of the selected product
        $(function() {
            var availableProducts = <?php echo $json_products?>;
            var zIndex = $('#name').css('z-index');

            $("#name").autocomplete({
                source: availableProducts,
                select: function (event, ui) {
                    $("#name").val(ui.item.label); // Product name field - this seemingly has to referenced as label
                    $("#desc").val(ui.item.description); // Product description field
                    $("#qty").val(1); // Product quantity field automatically make it a 1
                    $("#price").val(ui.item.price); // Product price field
                    $("#product_id").val(ui.item.productId); // Product ID field
                    $(".invoice-item-tax-modal").val(ui.item.tax).trigger('change'); // Product tax field
                    if (tinymce.get("desc")) { // Check if the TinyMCE instance for 'desc' exists
                        tinymce.get("desc").setContent(ui.item.description);
                    }
                    updateLineTotal();
                    return false;
                }
            });

            // Event listeners for when the inputs are changed
            $('#price, #qty, .invoice-item-discount').on('input', function() {
                updateLineTotal(); // Call the update function when price, qty, or discount changes
            });

            $('.invoice-item-tax-modal').on('change', function() {
                updateLineTotal();
            });

            console.log("Length: ", $('.invoice-item-tax').length); // Check how many selects with this class are present
            $('.invoice-item-tax').each(function() {
                console.log("Num Options: ", $(this).find('option:selected').length); // Check how many options are selected in each
                console.log("Data Rata: ", $(this).find('option:selected').data('rate')); // Log the data rate of selected options
            });

            function updateLineTotal() {
                var price = parseFloat($('#price').val()) || 0; // Get the price or 0 if empty
                var qty = parseFloat($('#qty').val()) || 0; // Get the quantity or 0 if empty
                var discountInput = $('.invoice-item-discount').val().trim(); // Get the discount value
                var taxRate = $('.invoice-item-tax-modal').find(':selected').data('rate') || 0;

                var subtotal = price * qty; // Calculate the subtotal
                var taxAmount = subtotal * (taxRate / 100); // Calculate the tax amount
                var discount = 0; // Initialize discount

                if (discountInput.endsWith('%')) {
                    var discountPercentage = parseFloat(discountInput) || 0; // Parse the percentage number
                    discount = (subtotal * discountPercentage / 100); // Calculate percentage-based discount
                } else {
                    discount = parseFloat(discountInput) || 0; // Otherwise, treat it as a fixed amount
                }

                var total = subtotal + taxAmount - discount; // Calculate the total after tax and discount
                $('.invoice-item-total').val(total.toFixed(2)); // Set the calculated total, formatted to 2 decimal places
            }
        });
    });





    // Find all input, textarea, and select elements within any 'item-container' div
    document.querySelectorAll('.item-container input, .item-container textarea, .item-container select').forEach(function(element) {
        element.addEventListener('change', function() {
            // Find the closest parent element with the class 'item-container'
            var itemContainer = this.closest('.item-container');

            // Find the save button within this container and show it
            var saveButton = itemContainer.querySelector('.btn[data-bs-original-title="Save Changes"]');
            if (saveButton) {
                saveButton.hidden = false;
            }
        });
    });

});
</script>

<style>
    .ui-autocomplete {
        z-index: 9999999;
    }
</style>

<?php
}
require_once '/var/www/portal.twe.tech/includes/footer.php';
?>