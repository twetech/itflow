<?php 

include("inc_all.php"); 

if(isset($_GET['invoice_id'])){

  $invoice_id = intval($_GET['invoice_id']);

  $sql = mysqli_query($mysqli,"SELECT * FROM invoices 
    LEFT JOIN clients ON invoice_client_id = client_id
    LEFT JOIN locations ON primary_location = location_id
    LEFT JOIN contacts ON primary_contact = contact_id
    LEFT JOIN companies ON invoices.company_id = companies.company_id
    WHERE invoice_id = $invoice_id"
  );

  if(mysqli_num_rows($sql) == 0){
    echo "<center><h1 class='text-secondary mt-5'>Nothing to see here</h1></center>";
  }else{

  $row = mysqli_fetch_array($sql);
  $invoice_id = $row['invoice_id'];
  $invoice_prefix = htmlentities($row['invoice_prefix']);
  $invoice_number = htmlentities($row['invoice_number']);
  $invoice_scope = htmlentities($row['invoice_scope']);
  $invoice_status = htmlentities($row['invoice_status']);
  $invoice_date = $row['invoice_date'];
  $invoice_due = $row['invoice_due'];
  $invoice_amount = floatval($row['invoice_amount']);
  $invoice_currency_code = htmlentities($row['invoice_currency_code']);
  $invoice_note = htmlentities($row['invoice_note']);
  $invoice_url_key = htmlentities($row['invoice_url_key']);
  $invoice_created_at = $row['invoice_created_at'];
  $category_id = $row['invoice_category_id'];
  $client_id = $row['client_id'];
  $client_name = htmlentities($row['client_name']);
  $location_address = htmlentities($row['location_address']);
  $location_city = htmlentities($row['location_city']);
  $location_state = htmlentities($row['location_state']);
  $location_zip = htmlentities($row['location_zip']);
  $contact_email = htmlentities($row['contact_email']);
  $contact_phone = formatPhoneNumber($row['contact_phone']);
  $contact_extension = htmlentities($row['contact_extension']);
  $contact_mobile = formatPhoneNumber($row['contact_mobile']);
  $client_website = htmlentities($row['client_website']);
  $client_currency_code = htmlentities($row['client_currency_code']);
  $client_net_terms = htmlentities($row['client_net_terms']);
  if($client_net_terms == 0){
    $client_net_terms = $config_default_net_terms;
  }
  $company_id = $row['company_id'];
  $company_name = htmlentities($row['company_name']);
  $company_country = htmlentities($row['company_country']);
  $company_address = htmlentities($row['company_address']);
  $company_city = htmlentities($row['company_city']);
  $company_state = htmlentities($row['company_state']);
  $company_zip = htmlentities($row['company_zip']);
  $company_phone = formatPhoneNumber($row['company_phone']);
  $company_email = htmlentities($row['company_email']);
  $company_website = htmlentities($row['company_website']);
  $company_logo = htmlentities($row['company_logo']);
  if(!empty($company_logo)){
  	$company_logo_base64 = base64_encode(file_get_contents("uploads/settings/$company_id/$company_logo"));
	}
  $sql_history = mysqli_query($mysqli,"SELECT * FROM history WHERE history_invoice_id = $invoice_id ORDER BY history_id DESC");
  
  $sql_payments = mysqli_query($mysqli,"SELECT * FROM payments, accounts WHERE payment_account_id = account_id AND payment_invoice_id = $invoice_id ORDER BY payments.payment_id DESC");

  //Add up all the payments for the invoice and get the total amount paid to the invoice
  $sql_amount_paid = mysqli_query($mysqli,"SELECT SUM(payment_amount) AS amount_paid FROM payments WHERE payment_invoice_id = $invoice_id");
  $row = mysqli_fetch_array($sql_amount_paid);
  $amount_paid = $row['amount_paid'];

  $balance = $invoice_amount - $amount_paid;

  //check to see if overdue
  if($invoice_status !== "Paid" && $invoice_status !== "Draft" && $invoice_status !== "Cancelled"){
    $unixtime_invoice_due = strtotime($invoice_due) + 86400;
    if($unixtime_invoice_due < time()){
      $invoice_overdue = "Overdue";
    }
  }
  
  //Set Badge color based off of invoice status
  if($invoice_status == "Sent"){
    $invoice_badge_color = "warning text-white";
  }elseif($invoice_status == "Viewed"){
    $invoice_badge_color = "info";
  }elseif($invoice_status == "Partial"){
    $invoice_badge_color = "primary";
  }elseif($invoice_status == "Paid"){
    $invoice_badge_color = "success";
  }elseif($invoice_status == "Cancelled"){
    $invoice_badge_color = "danger";
  }else{
    $invoice_badge_color = "secondary";
  }

  //Product autocomplete
  $products_sql = mysqli_query($mysqli,"SELECT product_name AS label, product_description AS description, product_price AS price FROM products WHERE company_id = $session_company_id");

  if(mysqli_num_rows($products_sql) > 0){
    while($row = mysqli_fetch_array($products_sql)){
      $products[] = $row;
    }
    $json_products = json_encode($products);
  }

?>

<ol class="breadcrumb d-print-none">
  <li class="breadcrumb-item">
    <a href="invoices.php">Invoices</a>
  </li>
  <li class="breadcrumb-item">
    <a href="client_invoices.php?client_id=<?php echo $client_id; ?>"><?php echo $client_name; ?></a>
  </li>
  <li class="breadcrumb-item active"><?php echo "$invoice_prefix$invoice_number"; ?></li>
  <?php if(isset($invoice_overdue)){ ?>
  <span class="p-2 ml-2 badge badge-danger"><?php echo $invoice_overdue; ?></span>
  <?php } ?>
</ol>
  
<div class="card">
  
  <div class="card-header d-print-none">
    
    <div class="row">
      
      <div class="col-md-4">
        <?php if($invoice_status == 'Draft'){ ?>
        <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
          <i class="fas fa-fw fa-paper-plane"></i> Send
        </button>
        <div class="dropdown-menu">
          <?php if(!empty($config_smtp_host) && !empty($contact_email)){ ?>
          <a class="dropdown-item" href="post.php?email_invoice=<?php echo $invoice_id; ?>">Send Email</a>
          <div class="dropdown-divider"></div>
          <?php } ?>
          <a class="dropdown-item" href="post.php?mark_invoice_sent=<?php echo $invoice_id; ?>">Mark Sent</a>
        </div>
        <?php } ?>

        <?php if($invoice_status !== 'Paid' && $invoice_status !== 'Cancelled' && $invoice_status !== 'Draft'){ ?>
        <a class="btn btn-success btn-sm" href="#" data-toggle="modal" data-target="#addPaymentModal"><i class="fa fa-fw fa-credit-card"></i> Add Payment</a>
        <?php } ?>
      </div>
      
      <div class="col-md-8">
        
        <div class="dropdown dropleft text-center">
          <button class="btn btn-primary btn-sm float-right" type="button" data-toggle="dropdown">
            <i class="fas fa-fw fa-ellipsis-v"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editInvoiceModal<?php echo $invoice_id; ?>">Edit</a>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addInvoiceCopyModal<?php echo $invoice_id; ?>">Copy</a>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addInvoiceRecurringModal<?php echo $invoice_id; ?>">Recurring</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onclick="window.print();">Print</a>
            <a class="dropdown-item" href="#" onclick="pdfMake.createPdf(docDefinition).download('<?php echo "$invoice_date-$company_name-$client_name-Invoice-$invoice_prefix$invoice_number.pdf"; ?>');">Download PDF</a>
            <?php if(!empty($config_smtp_host) && !empty($contact_email)){ ?>
            <a class="dropdown-item" href="post.php?email_invoice=<?php echo $invoice_id; ?>">Send Email</a>
            <?php } ?>
            <a class="dropdown-item" target="_blank" href="guest_view_invoice.php?invoice_id=<?php echo "$invoice_id&url_key=$invoice_url_key"; ?>">Guest URL</a>
            <?php if($invoice_status !== 'Cancelled' && $invoice_status !== 'Paid'){ ?>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="post.php?cancel_invoice=<?php echo $invoice_id; ?>">Cancel</a>
            <?php } ?>
          </div>
        </div>
      
      </div>
    
    </div>
  
  </div>
  
  <div class="card-body">
      
    <div class="row mb-4">
      <div class="col-sm-2">
        <img class="img-fluid" src="<?php echo "uploads/settings/$company_id/$company_logo"; ?>">
      </div>
      <div class="col-sm-10">
        <div class="ribbon-wrapper">
          <div class="ribbon bg-<?php echo $invoice_badge_color; ?>">
            <?php echo $invoice_status; ?>
          </div>
        </div>
        <h3 class="text-right mt-5"><strong>Invoice</strong><br><small class="text-secondary"><?php echo "$invoice_prefix$invoice_number"; ?></small></h3>
      </div>
      
    </div>
    <div class="row mb-4">
      <div class="col-sm">
        <ul class="list-unstyled">
          <li><h4><strong><?php echo $company_name; ?></strong></h4></li>
          <li><?php echo $company_address; ?></li>
          <li><?php echo "$company_city $company_state $company_zip"; ?></li>
          <li><?php echo $company_phone; ?></li>
          <li><?php echo $company_email; ?></li>
          <li><?php echo $company_website; ?></li>
        </ul>
      </div>
      <div class="col-sm">
        <ul class="list-unstyled text-right">
          <li><h4><strong><?php echo $client_name; ?></strong></h4></li>
          <li><?php echo $location_address; ?></li>
          <li><?php echo "$location_city $location_state $location_zip"; ?></li>
          <li><?php echo "$contact_phone $contact_extension"; ?></li>
          <li><?php echo $contact_mobile; ?></li>
          <li><?php echo $contact_email; ?></li>
        </ul>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-sm-8">
      </div>
      <div class="col-sm-4">
        <table class="table">
          <tr>
            <td>Invoice Date</td>
            <td class="text-right"><?php echo $invoice_date; ?></td>
          </tr>
          <tr>
            <td>Due Date</td>
            <td class="text-right"><?php echo $invoice_due; ?></td>
          </tr>
        </table>
      </div>
    </div>

    <?php $sql_invoice_items = mysqli_query($mysqli,"SELECT * FROM invoice_items WHERE item_invoice_id = $invoice_id ORDER BY item_id ASC"); ?>

    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="d-print-none"></th>
                  <th>Item</th>
                  <th>Description</th>
                  <th class="text-center">Qty</th>
                  <th class="text-right">Price</th>
                  <th class="text-right">Tax</th>
                  <th class="text-right">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                
                $total_tax = 0;
                $sub_total = 0;
          
                while($row = mysqli_fetch_array($sql_invoice_items)){
                  $item_id = $row['item_id'];
                  $item_name = htmlentities($row['item_name']);
                  $item_description = htmlentities($row['item_description']);
                  $item_quantity = floatval($row['item_quantity']);
                  $item_price = floatval($row['item_price']);
                  $item_tax = floatval($row['item_tax']);
                  $item_total = floatval($row['item_total']);
                  $item_created_at = $row['item_created_at'];
                  $tax_id = $row['item_tax_id'];
                  $total_tax = $item_tax + $total_tax;
                  $sub_total = $item_price * $item_quantity + $sub_total;

                ?>

                <tr>
                  <td class="text-center d-print-none">
                    <a class="text-secondary" href="#" data-toggle="modal" data-target="#editItemModal<?php echo $item_id; ?>"><i class="fa fa-fw fa-edit"></i></a>
                    <a class="text-danger" href="post.php?delete_invoice_item=<?php echo $item_id; ?>"><i class="fa fa-fw fa-trash-alt"></i></a>
                  </td>
                  <td><?php echo $item_name; ?></td>
                  <td><div style="white-space:pre-line"><?php echo $item_description; ?></div></td>
                  <td class="text-center"><?php echo $item_quantity; ?></td>
                  <td class="text-right"><?php echo numfmt_format_currency($currency_format, $item_price, $invoice_currency_code); ?></td>
                  <td class="text-right"><?php echo numfmt_format_currency($currency_format, $item_tax, $invoice_currency_code); ?></td>
                  <td class="text-right"><?php echo numfmt_format_currency($currency_format, $item_total, $invoice_currency_code); ?></td>  
                </tr>

                <?php 

                include("item_edit_modal.php");

                }

                ?>

                <tr class="d-print-none">
                  <form action="post.php" method="post" autocomplete="off">
                    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
                    <td></td>            
                    <td><input type="text" class="form-control" id="name" name="name" placeholder="Item" required></td>
                    <td><textarea class="form-control" rows="2" id="desc" name="description" placeholder="Description"></textarea></td>
                    <td><input type="number" step="0.01" min="0" class="form-control" style="text-align: center;" id="qty" name="qty" placeholder="QTY"></td>
                    <td><input type="number" step="0.01" class="form-control" style="text-align: right;" id="price" name="price" placeholder="Price (<?php echo $invoice_currency_code; ?>)"></td>
                    <td>             
                      <select class="form-control select2" name="tax_id" required>
                        <option value="0">None</option>
                        <?php 
                        
                        $taxes_sql = mysqli_query($mysqli,"SELECT * FROM taxes WHERE company_id = $session_company_id ORDER BY tax_name ASC"); 
                        while($row = mysqli_fetch_array($taxes_sql)){
                          $tax_id = $row['tax_id'];
                          $tax_name = htmlentities($row['tax_name']);
                          $tax_percent = htmlentities($row['tax_percent']);
                        ?>
                          <option value="<?php echo $tax_id; ?>"><?php echo "$tax_name $tax_percent%"; ?></option>
                        
                        <?php
                        }
                        ?>
                      </select>
                    </td>
                    <td>
                      <button class="btn btn-link text-success" type="submit" name="add_invoice_item">
                        <i class="fa fa-fw fa-check"></i>
                      </button>
                    </td>
                  </form>  
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-sm-7">
        <div class="card">
          <div class="card-header">
            Notes
            <div class="card-tools d-print-none">
              <a href="#" class="btn btn-tool" data-toggle="modal" data-target="#invoiceNoteModal">
                <i class="fas fa-edit"></i>
              </a>
            </div>
          </div>
          <div class="card-body">
            <div style="white-space:pre-line"><?php echo $invoice_note; ?></div>
          </div>
        </div>
      </div>
      <div class="col-sm-3 offset-sm-2">
        <table class="table table-borderless">
          <tbody>    
            <tr class="border-bottom">
              <td>Subtotal</td>
              <td class="text-right"><?php echo numfmt_format_currency($currency_format, $sub_total, $invoice_currency_code); ?></td>
            </tr>
            <?php if($total_tax > 0){ ?>
            <tr class="border-bottom">
              <td>Tax</td>
              <td class="text-right"><?php echo numfmt_format_currency($currency_format, $total_tax, $invoice_currency_code); ?></td>        
            </tr>
            <?php } ?>
            <?php if($amount_paid > 0){ ?>
            <tr class="border-bottom">
              <td><div class="text-success">Paid to Date</div></td>
              <td class="text-right text-success"><?php echo numfmt_format_currency($currency_format, $amount_paid, $invoice_currency_code); ?></td>
            </tr>
            <?php } ?>
            <tr class="border-bottom">
              <td><strong>Balance Due</strong></td>
              <td class="text-right"><strong><?php echo numfmt_format_currency($currency_format, $balance, $invoice_currency_code); ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <hr class="d-none d-print-block mt-5">

    <center class="d-none d-print-block" style="white-space:pre-line"><?php echo htmlentities($config_invoice_footer); ?></center>
  </div>
</div>

<div class="row d-print-none mb-3">
  <div class="col-sm">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-fw fa-history"></i> History
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Status</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <?php
      
            while($row = mysqli_fetch_array($sql_history)){
              $history_created_at = $row['history_created_at'];
              $history_status = htmlentities($row['history_status']);
              $history_description = htmlentities($row['history_description']);
             
            ?>
            <tr>
              <td><?php echo $history_created_at; ?></td>
              <td><?php echo $history_status; ?></td>
              <td><?php echo $history_description; ?></td>
            </tr>
            <?php
            }
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-sm d-print-none">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-fw fa-credit-card"></i> Payments
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">  
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th class="text-right">Amount</th>
                <th>Reference</th>
                <th>Account</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
        
              while($row = mysqli_fetch_array($sql_payments)){
                $payment_id = $row['payment_id'];
                $payment_date = $row['payment_date'];
                $payment_amount = floatval($row['payment_amount']);
                $payment_currency_code = htmlentities($row['payment_currency_code']);
                $payment_reference = htmlentities($row['payment_reference']);
                $account_name = htmlentities($row['account_name']);

              ?>
              <tr>
                <td><?php echo $payment_date; ?></td>
                <td class="text-right"><?php echo numfmt_format_currency($currency_format, $payment_amount, $payment_currency_code); ?></td>
                <td><?php echo $payment_reference; ?></td>
                <td><?php echo $account_name; ?></td>
                <td class="text-center"><a class="btn btn-danger btn-sm" href="post.php?delete_payment=<?php echo $payment_id; ?>"><i class="fa fa-trash"></i></a></td>
              </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
  include("invoice_payment_add_modal.php");
  include("invoice_copy_modal.php");
  include("invoice_recurring_add_modal.php");
  include("invoice_edit_modal.php");
  include("invoice_note_modal.php");
  include("category_quick_add_modal.php");
  
  } 
}

include("footer.php"); 

?>

<!-- JSON Autocomplete / type ahead -->
<link rel="stylesheet" href="plugins/jquery-ui/jquery-ui.min.css">
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $(function(){
    var availableProducts = <?php echo $json_products?>;

    $("#name").autocomplete({
      source: availableProducts,
      select: function (event, ui){
        $("#name").val(ui.item.label); // Product name field - this seemingly has to referenced as label
        $("#desc").val(ui.item.description); // Product description field
        $("#qty").val(1); // Product quantity field automatically make it a 1
        $("#price").val(ui.item.price); // Product price field
        return false;
      }
    });
  });
</script>

<script src='plugins/pdfmake/pdfmake.min.js'></script>
<script src='plugins/pdfmake/vfs_fonts.js'></script>
<script>

var docDefinition = {
	info: {
		title: <?php echo json_encode(html_entity_decode($company_name) . "- Invoice") ?>,
		author: <?php echo json_encode(html_entity_decode($company_name)) ?>
	},

	//watermark: {text: '<?php echo $invoice_status; ?>', color: 'lightgrey', opacity: 0.3, bold: true, italics: false},

	content: [
		// Header
		{
			columns: [
				<?php if(!empty($company_logo_base64)){ ?>
				{
					image: <?php echo json_encode("data:image;base64,$company_logo_base64") ?>,
					width: 120
				},
				<?php } ?>
		            
				[
					{
						text: 'Invoice', 
						style: 'invoiceTitle',
						width: '*'
					},
					{
						text: <?php echo json_encode(html_entity_decode("$invoice_prefix$invoice_number")) ?>, 
						style: 'invoiceNumber',
						width: '*'
					},
		    ],
	 		],
		},
		// Billing Headers
		{
			columns: [
				{
		    	text: <?php echo json_encode(html_entity_decode($company_name)) ?>,
		      style: 'invoiceBillingTitle',
		    },
		    {
		      text: <?php echo json_encode(html_entity_decode($client_name)) ?>,
		      style: 'invoiceBillingTitleClient',        
		    },
			]
		},
		// Billing Address
		{
			columns: [
		  	{
		      text: <?php echo json_encode(html_entity_decode("$company_address \n $company_city $company_state $company_zip \n $company_phone \n $company_website")) ?>,
		      style: 'invoiceBillingAddress'
		    },
		    {
		      text: <?php echo json_encode(html_entity_decode("$location_address \n $location_city $location_state $location_zip \n $contact_email \n $contact_phone")) ?>,
		      style: 'invoiceBillingAddressClient'
		    },
		  ]
		},
		//Invoice Dates Table
		{
			table: {
		  	// headers are automatically repeated if the table spans over multiple pages
		    // you can declare how many rows should be treated as headers
		    headerRows: 0,
		    widths: [ '*',80, 80 ],

		    body: [
		    	// Total
		      [ 
		      	{
		      		text: '',
		      		rowSpan: 3
		      	},
		      	{}, 
						{},
		      ],
		      [ 
		      	{},
		      	{
							text: 'Invoice Date',
							style: 'invoiceDateTitle'
						}, 
						{
							text: <?php echo json_encode($invoice_date) ?>,
							style: 'invoiceDateValue'
						},
		      ],
		      [ 
		      	{},
		      	{
							text: 'Due Date',
							style: 'invoiceDateTitle'
						}, 
						{
							text: <?php echo json_encode($invoice_due) ?>,
							style: 'invoiceDateValue'
						},
		      ],
		    ]
		  }, // table
		  layout: 'lightHorizontalLines'
		},
		// Line breaks
		'\n\n',
		// Items
		{
			table: {
				// headers are automatically repeated if the table spans over multiple pages
				// you can declare how many rows should be treated as headers
				headerRows: 1,
				widths: [ '*', 40, 'auto', 'auto', 80 ],

				body: [
					// Table Header
					[ 
						{
							text: 'Product',
							style: [ 'itemsHeader', 'left']
						}, 
						{
						  text: 'Qty',
						  style: [ 'itemsHeader', 'center']
						}, 
						{
						  text: 'Price',
						  style: [ 'itemsHeader', 'right']
						}, 
						{
						  text: 'Tax',
						  style: [ 'itemsHeader', 'right']
						}, 
						{
						  text: 'Total',
						  style: [ 'itemsHeader', 'right']
						} 
					],
		      // Items
		      <?php 
		      $total_tax = 0;
		      $sub_total = 0;

		      $sql_invoice_items = mysqli_query($mysqli,"SELECT * FROM invoice_items WHERE item_invoice_id = $invoice_id ORDER BY item_id ASC");
		      
		      while($row = mysqli_fetch_array($sql_invoice_items)){
		        $item_name = $row['item_name'];
		        $item_description = $row['item_description'];
		        $item_quantity = $row['item_quantity'];
		        $item_price = $row['item_price'];
		        $item_subtotal = $row['item_price'];
		        $item_tax = $row['item_tax'];
		        $item_total = $row['item_total'];
		        $tax_id = $row['item_tax_id'];
		        $total_tax = $item_tax + $total_tax;	        
		        $sub_total = $item_price * $item_quantity + $sub_total;
		      ?>

		      // Item
		      [	 
		        [
		        	{
		          	text: <?php echo json_encode($item_name) ?>,
		            style: 'itemTitle'
		          },
		          {
		            text: <?php echo json_encode($item_description) ?>,
		            style: 'itemDescription'       
		          }
		        ], 
		        {
		        	text: <?php echo json_encode($item_quantity) ?>,
		          style: 'itemQty'
		        }, 
		        {
		        	text: <?php echo json_encode(numfmt_format_currency($currency_format, $item_price, $invoice_currency_code)) ?>,
		         	style: 'itemNumber'
		        }, 
		        {
		          text: <?php echo json_encode(numfmt_format_currency($currency_format, $item_tax, $invoice_currency_code)) ?>,
		          style: 'itemNumber'
		        }, 
		        {
		          text: <?php echo json_encode(numfmt_format_currency($currency_format, $item_total, $invoice_currency_code)) ?>,
		          style: 'itemNumber'
		        } 
		    	],
		
					<?php
					}
					?>
		      // END Items
		    ]
		  }, // table
		  layout: 'lightHorizontalLines'
	 	},
	 	// TOTAL
		{
			table: {
		  	// headers are automatically repeated if the table spans over multiple pages
		    // you can declare how many rows should be treated as headers
		    headerRows: 0,
		    widths: [ '*','auto', 80 ],

		    body: [
		    	// Total
		      [ 
		      	{
		      		text: 'Notes',
		  				style: 'notesTitle'
		  			},
		      	{},
		      	{}
		      ],
		      [ 
		      	{
		      		rowSpan: 5,
							text: <?php echo json_encode(html_entity_decode($invoice_note)) ?>,
		  				style: 'notesText'
		  			},
		      	{
		        	text: 'Subtotal',
		          style: 'itemsFooterSubTitle'
		        }, 
		        { 
		         	text: <?php echo json_encode(numfmt_format_currency($currency_format, $sub_total, $invoice_currency_code)) ?>,
		          style: 'itemsFooterSubValue'
		        }
		      ],
		      [ 
		      	{},    	
		      	{
		        	text: 'Tax',
		          style: 'itemsFooterSubTitle'
		        },
		        {
		         	text: <?php echo json_encode(numfmt_format_currency($currency_format, $total_tax, $invoice_currency_code)) ?>,
		          style: 'itemsFooterSubValue'
		        }
		      ],
		      [ 
		      	{},
		      	{
		        	text: 'Total',
		          style: 'itemsFooterSubTitle'
		        }, 
		        {
		         	text: <?php echo json_encode(numfmt_format_currency($currency_format, $invoice_amount, $invoice_currency_code)) ?>,
		          style: 'itemsFooterSubValue'
		        }
		      ],
		      [ 
		      	{},
		      	{
		        	text: 'Paid',
		          style: 'itemsFooterSubTitle'
		        },
		        {
		          text: <?php echo json_encode(numfmt_format_currency($currency_format, $amount_paid, $invoice_currency_code)) ?>,
		          style: 'itemsFooterSubValue'
		        }
		      ],
		      [ 
		      	{},
		      	{
		        	text: 'Balance',
		          style: 'itemsFooterTotalTitle'
		        },
		        {
		        	text: <?php echo json_encode(numfmt_format_currency($currency_format, $balance, $invoice_currency_code)) ?>,
		          
		          style: 'itemsFooterTotalTitle'
		        }
		      ],
		    ]
		  }, // table
		  layout: 'lightHorizontalLines'
		},
    // TERMS / FOOTER
    {
      text: <?php echo json_encode($config_invoice_footer) ?>,
      style: 'documentFooterCenter'  
    }
	], //End Content,
	styles: {
		// Document Footer
		documentFooterCenter: {
			fontSize: 9,
		  margin: [10,50,10,10],
		  alignment: 'center',
		},
		// Invoice Title
		invoiceTitle: {
			fontSize: 18,
			bold: true,
			alignment: 'right',
			margin: [0,0,0,3]
		},
		// Invoice Number
		invoiceNumber: {
			fontSize: 14,
			alignment: 'right'
		},
		// Billing Headers
		invoiceBillingTitle: {
			fontSize: 14,
			bold: true,
			alignment: 'left',
			margin: [0,20,0,5]
		},
		invoiceBillingTitleClient: {
			fontSize: 14,
			bold: true,
			alignment: 'right',
			margin: [0,20,0,5]
		},
		// Billing Details
		invoiceBillingAddress: {
		  fontSize: 10,
		  lineHeight: 1.2
		},
		invoiceBillingAddressClient: {
			fontSize: 10,
			lineHeight: 1.2,
			alignment: 'right',
			margin: [0,0,0,30]
		},
		// Invoice Dates
		invoiceDateTitle: {
			fontSize: 10,
			alignment: 'left',
			margin: [0,5,0,5]
		},
		invoiceDateValue: {
			fontSize: 10,
			alignment: 'right',
			margin: [0,5,0,5]
		},
		// Items Header
		itemsHeader: {
			fontSize: 10,
			margin: [0,5,0,5],
			bold: true,
			alignment: 'right'
		},
		// Item Title
		itemTitle: {
			fontSize: 10,
			bold: true,
			margin: [0,5,0,3]
		},
		itemDescription: {
			italics: true,
			fontSize: 9,
			lineHeight: 1.1,
			margin: [0,3,0,5]
		},
		itemQty: {
			fontSize: 10,
			margin: [0,5,0,5],
			alignment: 'center'
		},
		itemNumber: {
		  fontSize: 10,
		  margin: [0,5,0,5],
		  alignment: 'right'
		},
		itemTotal: {
		  fontSize: 10,
		  margin: [0,5,0,5],
		  bold: true,
		  alignment: 'right'
		},
		// Items Footer (Subtotal, Total, Tax, etc)
		itemsFooterSubTitle: {
		  fontSize: 10,
		  margin: [0,5,0,5],
		  alignment:'right'
		},
		itemsFooterSubValue: {
		  fontSize: 10,
		  margin: [0,5,0,5],
		  bold: false,
		  alignment: 'right'
		},
		itemsFooterTotalTitle: {
		  fontSize: 10,
		  margin: [0,5,0,5],
		  bold: true,
		  alignment: 'right'
		},
		itemsFooterTotalValue: {
		  fontSize: 10,
		  margin: [0,5,0,5],
		  bold: true,
		  alignment: 'right'
		},
		notesTitle: {
			fontSize: 10,
			bold: true,  
			margin: [0,5,0,5]
		},
		notesText: {
			fontSize: 9,
			margin: [0,5,50,5]
		},
		left: {
			alignment: 'left'
		},
		center: {
			alignment: 'center'
		},
	},
	defaultStyle: {
		columnGap: 20
	}
}
</script>
