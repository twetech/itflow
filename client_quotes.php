<?php

//Paging
if(isset($_GET['p'])){
  $p = intval($_GET['p']);
  $record_from = (($p)-1)*$_SESSION['records_per_page'];
  $record_to = $_SESSION['records_per_page'];
}else{
  $record_from = 0;
  $record_to = $_SESSION['records_per_page'];
  $p = 1;
}
  
if(isset($_GET['q'])){
  $q = mysqli_real_escape_string($mysqli,$_GET['q']);
}else{
  $q = "";
}

if(!empty($_GET['sb'])){
  $sb = mysqli_real_escape_string($mysqli,$_GET['sb']);
}else{
  $sb = "quote_number";
}

if(isset($_GET['o'])){
  if($_GET['o'] == 'ASC'){
    $o = "ASC";
    $disp = "DESC";
  }else{
    $o = "DESC";
    $disp = "ASC";
  }
}else{
  $o = "DESC";
  $disp = "ASC";
}

//Rebuild URL
$url_query_strings_sb = http_build_query(array_merge($_GET,array('sb' => $sb, 'o' => $o)));

$sql = mysqli_query($mysqli,"SELECT SQL_CALC_FOUND_ROWS * FROM quotes 
  LEFT JOIN categories ON category_id = quote_category_id
  WHERE quote_client_id = $client_id 
  AND (CONCAT(quote_prefix,quote_number) LIKE '%$q%' OR quote_scope LIKE '%$q%' OR category_name LIKE '%$q%' OR quote_status LIKE '%$q%') 
  ORDER BY $sb $o LIMIT $record_from, $record_to");

$num_rows = mysqli_fetch_row(mysqli_query($mysqli,"SELECT FOUND_ROWS()"));

?>

<div class="card card-dark">
  <div class="card-header py-2">
    <h3 class="card-title mt-2"><i class="fa fa-fw fa-file"></i> Quotes</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQuoteModal"><i class="fas fa-fw fa-plus"></i> New Quote</button>
    </div>
  </div>
  <div class="card-body">
    <form autocomplete="off">
      <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
      <input type="hidden" name="tab" value="<?php echo $_GET['tab']; ?>">
      <div class="row">
        
        <div class="col-md-4">
          <div class="input-group mb-3 mb-md-0">
            <input type="search" class="form-control" name="q" value="<?php if(isset($q)){echo stripslashes($q);} ?>" placeholder="Search <?php echo ucwords($_GET['tab']); ?>">
            <div class="input-group-append">
              <button class="btn btn-dark"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="float-right">
            <a href="post.php?export_client_<?php echo $_GET['tab']; ?>_csv=<?php echo $client_id; ?>" class="btn btn-default"><i class="fa fa-fw fa-download"></i> Export</a>
          </div>
        </div>

      </div>
    </form>
    <hr>
    <div class="table-responsive">
      <table class="table table-striped table-borderless table-hover">
        <thead class="text-dark <?php if($num_rows[0] == 0){ echo "d-none"; } ?>">
          <tr>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=quote_number&o=<?php echo $disp; ?>">Number</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=quote_scope&o=<?php echo $disp; ?>">Scope</a></th>
            <th class="text-right"><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=quote_amount&o=<?php echo $disp; ?>">Amount</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=quote_date&o=<?php echo $disp; ?>">Date</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=category_name&o=<?php echo $disp; ?>">Category</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=quote_status&o=<?php echo $disp; ?>">Status</a></th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
      
          while($row = mysqli_fetch_array($sql)){
            $quote_id = $row['quote_id'];
            $quote_prefix = $row['quote_prefix'];
            $quote_number = $row['quote_number'];
            $quote_scope = $row['quote_scope'];
            if(empty($quote_scope)){
              $quote_scope_display = "-";
            }else{
              $quote_scope_display = $quote_scope;
            }
            $quote_status = $row['quote_status'];
            $quote_date = $row['quote_date'];
            $quote_amount = $row['quote_amount'];
            $quote_currency_code = $row['quote_currency_code'];
            $quote_created_at = $row['quote_created_at'];
            $category_id = $row['category_id'];
            $category_name = $row['category_name'];

            //Set Badge color based off of quote status
            if($quote_status == "Sent"){
              $quote_badge_color = "warning text-white";
            }elseif($quote_status == "Viewed"){
              $quote_badge_color = "primary";
            }elseif($quote_status == "Accepted"){
              $quote_badge_color = "success";
            }elseif($quote_status == "Declined"){
              $quote_badge_color = "danger";
            }elseif($quote_status == "Invoiced"){
              $quote_badge_color = "info";
            }else{
              $quote_badge_color = "secondary";
            }

          ?>

          <tr>
            <td><a href="quote.php?quote_id=<?php echo $quote_id; ?>"><?php echo "$quote_prefix$quote_number"; ?></a></td>
            <td><?php echo $quote_scope_display; ?></td>
            <td class="text-right"><?php echo get_currency_symbol($quote_currency_code); ?> <?php echo number_format($quote_amount,2); ?></td>
            <td><?php echo $quote_date; ?></td>
            <td><?php echo $category_name; ?></td>
            <td>
              <span class="p-2 badge badge-<?php echo $quote_badge_color; ?>">
                <?php echo $quote_status; ?>
              </span>
            </td>
            <td>
              <div class="dropdown dropleft text-center">
                <button class="btn btn-secondary btn-sm" type="button" data-toggle="dropdown">
                  <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editQuoteModal<?php echo $quote_id; ?>">Edit</a>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addQuoteCopyModal<?php echo $quote_id; ?>">Copy</a>
                  <div class="dropdown-divider"></div>
                  <?php if(!empty($config_smtp_host) AND !empty($client_email)){ ?>
                  <a class="dropdown-item" href="post.php?email_quote=<?php echo $quote_id; ?>">Send</a>
                  <div class="dropdown-divider"></div>
                  <?php } ?>
                  <a class="dropdown-item text-danger" href="post.php?delete_quote=<?php echo $quote_id; ?>">Delete</a>
                </div>
              </div>    
            </td>
          </tr>

          <?php

          include("quote_edit_modal.php");
          include("quote_copy_modal.php");
          }

          ?>

        </tbody>
      </table>
    </div>
    <?php include("pagination.php"); ?>
  </div>
</div>

<?php include("quote_add_modal.php"); ?>