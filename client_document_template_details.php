<?php

require_once("inc_all_client.php");

//Initialize the HTML Purifier to prevent XSS
require("plugins/htmlpurifier/HTMLPurifier.standalone.php");
$purifier_config = HTMLPurifier_Config::createDefault();
$purifier_config->set('URI.AllowedSchemes', ['data' => true, 'src' => true, 'http' => true, 'https' => true]);
$purifier = new HTMLPurifier($purifier_config);

if (isset($_GET['document_id'])) {
	$document_id = intval($_GET['document_id']);
}

$sql_document = mysqli_query($mysqli, "SELECT * FROM documents WHERE document_template = 1 AND document_id = $document_id");

$row = mysqli_fetch_array($sql_document);

$document_name = nullable_htmlentities($row['document_name']);
$document_content = $purifier->purify($row['document_content']);
//$document_content = $row['document_content'];
$document_created_at = nullable_htmlentities($row['document_created_at']);
$document_updated_at = nullable_htmlentities($row['document_updated_at']);

?>

<ol class="breadcrumb d-print-none">
  <li class="breadcrumb-item">
    <a href="invoices.php">Home</a>
  </li>
  <li class="breadcrumb-item">
    <a href="clients.php">Clients</a>
  </li>
  <li class="breadcrumb-item">
    <a href="client_overview.php?client_id=<?php echo $client_id; ?>"><?php echo $client_name; ?></a>
  </li>
  <li class="breadcrumb-item">
    <a href="client_documents.php?client_id=<?php echo $client_id; ?>">Documents</a>
  </li>
  <li class="breadcrumb-item">
    <a href="client_document_templates.php?client_id=<?php echo $client_id; ?>">Templates</a>
  </li>
  <li class="breadcrumb-item active"><i class="fas fa-file"></i> <?php echo $document_name; ?></li>
</ol>


<div class="card card-dark">
  <div class="card-header">

    <h3 class="card-title mt-2"><i class="fa fa-fw fa-file mr-2"></i><?php echo $document_name; ?></h3>

    <div class="card-tools">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editDocumentTemplateModal<?php echo $document_id; ?>">
        <i class="fas fa-edit mr-2"></i>Edit
      </button>
      <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editDocumentModal">
        <i class="fas fa-copy mr-2"></i>Copy
      </button>
    </div>
  </div>
  <div class="card-body">
    <?php echo $document_content; ?>
  </div>
</div>

<?php

require_once("client_document_template_edit_modal.php");

require_once("footer.php");
