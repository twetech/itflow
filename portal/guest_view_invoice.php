<?php

require_once "/var/www/nestogy.io/portal/guest_header.php";

require_once "/var/www/nestogy.io/portal/portal_header.php";

if (!isset($_GET['invoice_id'], $_GET['url_key'])) {
    echo "<br><h2>Oops, something went wrong! Please raise a ticket if you believe this is an error.</h2>";
    require_once "portal/guest_footer.php";

    exit();
}

$url_key = sanitizeInput($_GET['url_key']);
$invoice_id = intval($_GET['invoice_id']);

$sql = mysqli_query(
    $mysqli,
    "SELECT * FROM invoices
    LEFT JOIN clients ON invoice_client_id = client_id
    LEFT JOIN locations ON clients.client_id = locations.location_client_id AND location_primary = 1
    LEFT JOIN contacts ON clients.client_id = contacts.contact_client_id AND contact_primary = 1
    WHERE invoice_id = $invoice_id
    AND invoice_url_key = '$url_key'"
);

if (mysqli_num_rows($sql) !== 1) {
    // Invalid invoice/key
    echo "<br><h2>Oops, something went wrong! Please raise a ticket if you believe this is an error.</h2>";
    require_once "portal/guest_footer.php";

    exit();
}

$row = mysqli_fetch_array($sql);

$invoice_id = intval($row['invoice_id']);
$invoice_prefix = nullable_htmlentities($row['invoice_prefix']);
$invoice_number = intval($row['invoice_number']);
$invoice_status = nullable_htmlentities($row['invoice_status']);
$invoice_date = nullable_htmlentities($row['invoice_date']);
$invoice_due = nullable_htmlentities($row['invoice_due']);
$invoice_discount = floatval($row['invoice_discount_amount']);
$invoice_amount = floatval($row['invoice_amount']);
$invoice_currency_code = nullable_htmlentities($row['invoice_currency_code']);
$invoice_note = nullable_htmlentities($row['invoice_note']);
$invoice_category_id = intval($row['invoice_category_id']);
$client_id = intval($row['client_id']);
$client_name = nullable_htmlentities($row['client_name']);
$client_name_escaped = sanitizeInput($row['client_name']);
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
    $client_net_terms = intval($row['config_default_net_terms']);
}

$sql = mysqli_query($mysqli, "SELECT * FROM companies, settings WHERE companies.company_id = settings.company_id AND companies.company_id = 1");
$row = mysqli_fetch_array($sql);

$company_name = nullable_htmlentities($row['company_name']);
$company_address = nullable_htmlentities($row['company_address']);
$company_city = nullable_htmlentities($row['company_city']);
$company_state = nullable_htmlentities($row['company_state']);
$company_zip = nullable_htmlentities($row['company_zip']);
$company_phone = formatPhoneNumber($row['company_phone']);
$company_email = nullable_htmlentities($row['company_email']);
$company_website = nullable_htmlentities($row['company_website']);
$company_logo = nullable_htmlentities($row['company_logo']);

if (!empty($company_logo)) {
    $company_logo_base64 = base64_encode(file_get_contents("/var/www/nestogy.io/uploads/settings/$company_logo"));
}
$company_locale = nullable_htmlentities($row['company_locale']);
$config_invoice_footer = nullable_htmlentities($row['config_invoice_footer']);
$config_stripe_enable = intval($row['config_stripe_enable']);
$config_stripe_percentage_fee = floatval($row['config_stripe_percentage_fee']);
$config_stripe_flat_fee = floatval($row['config_stripe_flat_fee']);
$config_stripe_client_pays_fees = intval($row['config_stripe_client_pays_fees']);

//Set Currency Format
$currency_format = numfmt_create($company_locale, NumberFormatter::CURRENCY);

$invoice_tally_total = 0; // Default

//Set Badge color based off of invoice status
$invoice_badge_color = getInvoiceBadgeColor($invoice_status);

//Update status to Viewed only if invoice_status = "Sent"
if ($invoice_status == 'Sent') {
    mysqli_query($mysqli, "UPDATE invoices SET invoice_status = 'Viewed' WHERE invoice_id = $invoice_id");
}

//Mark viewed in history
mysqli_query($mysqli, "INSERT INTO history SET history_status = '$invoice_status', history_description = 'Invoice viewed - $ip - $os - $browser', history_invoice_id = $invoice_id");

if ($invoice_status !== 'Paid') {
    //$client_name_escaped = sanitizeInput($row['client_name']);
    mysqli_query($mysqli, "INSERT INTO notifications SET notification_type = 'Invoice Viewed', notification = 'Invoice $invoice_prefix$invoice_number has been viewed by $client_name_escaped - $ip - $os - $browser', notification_action = 'invoice.php?invoice_id=$invoice_id', notification_client_id = $client_id, notification_entity_id = $invoice_id");
}
$sql_payments = mysqli_query($mysqli, "SELECT * FROM payments, accounts WHERE payment_account_id = account_id AND payment_invoice_id = $invoice_id ORDER BY payments.payment_id DESC");

//Add up all the payments for the invoice and get the total amount paid to the invoice
$sql_amount_paid = mysqli_query($mysqli, "SELECT SUM(payment_amount) AS amount_paid FROM payments WHERE payment_invoice_id = $invoice_id");
$row = mysqli_fetch_array($sql_amount_paid);
$amount_paid = floatval($row['amount_paid']);

// Calculate the balance owed
$balance = $invoice_amount - $amount_paid;

// Calculate Gateway Fee
if ($config_stripe_client_pays_fees == 1) {
    $balance_before_fees = $balance;
    // See here for passing costs on to client https://support.stripe.com/questions/passing-the-stripe-fee-on-to-customers
    // Calculate the amount to charge the client
    $balance_to_pay = ($balance + $config_stripe_flat_fee) / (1 - $config_stripe_percentage_fee);
    // Calculate the fee amount
    $gateway_fee = round($balance_to_pay - $balance_before_fees, 2);
}

//check to see if overdue
$invoice_color = $invoice_badge_color; // Default
if ($invoice_status !== "Paid" && $invoice_status !== "Draft" && $invoice_status !== "Cancelled") {
    $unixtime_invoice_due = strtotime($invoice_due) + 86400;
    if ($unixtime_invoice_due < time()) {
        $invoice_color = "text-danger";
    }
}

// Invoice individual items
$sql_invoice_items = mysqli_query($mysqli, "SELECT * FROM invoice_items WHERE item_invoice_id = $invoice_id ORDER BY item_order ASC");
?>




 <!-- Content wrapper -->
 <div class="content-wrapper">

<!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    
    

<div class="row invoice-preview">
<!-- Invoice -->
<div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
<div class="card invoice-preview-card">
<div class="card-body">
<div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
<div class="row">
  <div class="col-4">
    <div class="d-flex svg-illustration mb-3 gap-2">
      <img src="data:image/png;base64,<?php echo $company_logo_base64; ?>" class="w-75 m-4 center-text" alt="logo" />
    </div>
  </div>
  <div class="col-3">
    <h4><?php echo $company_name; ?></h4>
    <p class="mb-1"><?php echo $company_address; ?></p>
    <p class="mb-1"><?php echo "$company_city $company_state $company_zip"; ?></p>
    <p class="mb-1"><?php echo $company_phone; ?></p>
    <p class="mb-0"><?php echo $company_email; ?></p>
  </div>
  <div class="col-5">
    <div class="d-flex justify-content-end">
      <div class="d-flex flex-column text-end">
        <h4>Invoice <?php echo "$invoice_prefix$invoice_number"; ?></h4>
        <div class="mb-2">
          <span class="me-1">Date Issued:</span>
          <span class="fw-medium"><div class="">
            <?php echo $invoice_date; ?>
          </div></span>
        </div>
        <div>
          <span class="me-1">Date Due:</span>
          <span class="fw-medium"><div class="<?php echo $invoice_color; ?>"><?php echo $invoice_due; ?></div></span>
        </div>
      </div>
    </div>
    </div>    
  </div>
</div>  


</div>
<hr class="my-0" />
<div class="card-body">
<div class="row">
    <div class="col-7">
      <h6 class="pb-2 m-1 text-end">Invoice To:</h6>
    </div>
    <div class="col text-end">
      <strong class="truncate-text"><?php echo $client_name; ?></strong><br>
      <?= $location_address; ?><br>
      <?= $location_city . ", " . $location_state . " " . $location_zip; ?><br>
      <a href="mailto:<?= $contact_email; ?>"><?= $contact_email; ?></a><br>
    </div>
</div>
</div>
<div class="table-responsive">
<table class="table border-top m-0">
  <thead>
    <tr>
        <th>Product</th>
        <th>Description</th>
        <th class="text-center">Qty</th>
        <th class="text-right">Price</th>
        <th class="text-right">Tax</th>
        <th class="text-right">Total</th>
    </tr>
  </thead>
  <tbody>
    <?php

    $total_tax = 0.00;
    $sub_total = 0.00 - $invoice_discount;

    while ($row = mysqli_fetch_array($sql_invoice_items)) {
        $item_id = intval($row['item_id']);
        $item_name = nullable_htmlentities($row['item_name']);
        $item_description = nullable_htmlentities($row['item_description']);
        $item_quantity = floatval($row['item_quantity']);
        $item_price = floatval($row['item_price']);
        $item_tax = floatval($row['item_tax']);
        $item_total = floatval($row['item_total']);
        $total_tax = $item_tax + $total_tax;
        $sub_total = $item_price * $item_quantity + $sub_total;
        ?>

        <tr>
            <td><?php echo $item_name; ?></td>
            <td><?php echo nl2br($item_description); ?></td>
            <td class="text-center"><?php echo $item_quantity; ?></td>
            <td class="text-right"><?php echo numfmt_format_currency($currency_format, $item_price, $invoice_currency_code); ?></td>
            <td class="text-right"><?php echo numfmt_format_currency($currency_format, $item_tax, $invoice_currency_code); ?></td>
            <td class="text-right"><?php echo numfmt_format_currency($currency_format, $item_total, $invoice_currency_code); ?></td>
        </tr>

    <?php } ?>
    <tr>
      <td colspan="3" class="align-top px-4 py-5">
        <h4 class=" text-center me-2">
           <?php
          $due_date = date('Y-m-d', strtotime($invoice_due));
          $current_date = date('Y-m-d');
          $days_until_due = floor((strtotime($due_date) - strtotime($current_date)) / (60 * 60 * 24));
          if ($balance > 0){
            if ($days_until_due > 0) {
              echo "Due in $days_until_due days";
            } elseif ($days_until_due == 0) {
              echo "Due today";
            } else {
              echo "Past due";
            }
          } else {
            echo "Paid";
          }
          ?>         
        </h4>

        <?php if ($invoice_note !== "") { ?>
          <span>Note:</span>
          <?php echo $invoice_note; ?>
        <?php } ?>

        </div>
        <div class="text-center"><?php echo nl2br($config_invoice_footer); ?></div>
      </td>
      <td colspan="2" class="text-end px-4 py-5">
        <p class="mb-2">Subtotal:</p>
        <p class="mb-2">Discount:</p>
        <p class="mb-2">Tax:</p>
        <p class="mb-<?= $amount_paid > 0 ? 4 : 0 ?>">Total:</p>
        <?php 
          if ($amount_paid > 0) { ?>
            <p class="mb-2">Amount Paid:</p>
            <p class="mb-0">Balance Due:</p>
        <?php } ?>
      </td>
      <td class="px-4 py-5">
        <p class="fw-medium mb-2"><?php echo numfmt_format_currency($currency_format, $sub_total, $invoice_currency_code); ?></p>
        <p class="fw-medium mb-2"><?php echo numfmt_format_currency($currency_format, $invoice_discount, $invoice_currency_code); ?></p>
        <p class="fw-medium mb-2"><?php echo numfmt_format_currency($currency_format, $total_tax, $invoice_currency_code); ?></p>
        <p class="fw-medium mb-<?= $amount_paid > 0 ? 4 : 0 ?>"><?php echo numfmt_format_currency($currency_format, $invoice_amount, $invoice_currency_code); ?></p>
        <?php 
          if ($amount_paid > 0) { ?>
            <p class="fw-medium mb-2"><?php echo numfmt_format_currency($currency_format, $amount_paid, $invoice_currency_code); ?></p>
            <p class="fw-medium mb-2"><?php echo numfmt_format_currency($currency_format, $balance, $invoice_currency_code); ?></p>
        <?php } ?>
      </td>
    </tr>
  </tbody>
</table>
</div>

</div>
</div>
<!-- /Invoice -->

    <!-- Invoice Actions -->
    <div class="col-xl-3 col-md-4 col-12 invoice-actions">
        <div class="card">
            <div class="card-body d-print-none">
                <button class="btn btn-label-secondary d-grid w-100 mb-3">
                Download
                </button>
                <a class="btn btn-label-secondary d-grid w-100 mb-3" target="_blank" onclick="window.print();">Print</a>
                <?php 
                if ($balance > 0) { ?>
                  <a class="btn btn-primary d-grid w-100" href="/portal/guest_pay_invoice_stripe.php?invoice_id=<?php echo $invoice_id; ?>&url_key=<?php echo $url_key; ?>">
                    <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="bx bx-dollar bx-xs me-1"></i>
                        Pay Online <?php if($config_stripe_client_pays_fees == 1) { echo "(Gateway Fee: " .  numfmt_format_currency($currency_format, $gateway_fee, $invoice_currency_code) . ")"; } ?>
                    </span>
                  </a>
                <?php } ?>
            </div>
        </div>
    </div>
<!-- /Invoice Actions -->


</div>



<?php
require_once "portal/guest_footer.php";
?>