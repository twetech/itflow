<?php

require_once "/var/www/portal.twe.tech/includes/inc_all_admin.php";


//Initialize the HTML Purifier to prevent XSS
require "/var/www/portal.twe.tech/includes/modals/plugins/htmlpurifier/HTMLPurifier.standalone.php";

$purifier_config = HTMLPurifier_Config::createDefault();
$purifier_config->set('URI.AllowedSchemes', ['data' => true, 'src' => true, 'http' => true, 'https' => true]);
$purifier = new HTMLPurifier($purifier_config);

if (isset($_GET['document_id'])) {
	$document_id = intval($_GET['document_id']);
}

$sql_document = mysqli_query($mysqli, "SELECT * FROM documents WHERE document_template = 1 AND document_id = $document_id");

$row = mysqli_fetch_array($sql_document);

$document_name = nullable_htmlentities($row['document_name']);
$document_description = nullable_htmlentities($row['document_description']);
$document_content = $purifier->purify($row['document_content']);
$document_created_at = nullable_htmlentities($row['document_created_at']);
$document_updated_at = nullable_htmlentities($row['document_updated_at']);

?>

<ol class="breadcrumb d-print-none">
  <li class="breadcrumb-item">
    <a href="clients.php">Home</a>
  </li>
  <li class="breadcrumb-item">
    <a href="admin_users.php">Admin</a>
  </li>
  <li class="breadcrumb-item">
    <a href="admin_document_templates.php">Document Templates</a>
  </li>
  <li class="breadcrumb-item active"><i class="fas fa-file mr-2"></i><?= $document_name; ?></li>
</ol>

<div class="card">
  <div class="card-header">

    <h3 class="card-title mt-2"><i class="fa fa-fw fa-file mr-2"></i><?= $document_name; ?></h3>

    <div class="card-tools">
      <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#editDocumentTemplateModal<?= $document_id; ?>">
        <i class="fas fa-edit mr-2"></i>Edit
      </button>
    </div>
  </div>
  <div class="card-body prettyContent">
    <?= $document_content; ?>
  </div>
</div>

<script src="/includes/js/pretty_content.js"></script>

<?php

require_once "/var/www/portal.twe.tech/includes/modals/admin_document_template_edit_modal.php";

require_once '/var/www/portal.twe.tech/includes/footer.php';

