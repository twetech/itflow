<?php

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Initialize the HTML Purifier to prevent XSS
require "/var/www/portal.twe.tech/includes/plugins/htmlpurifier/HTMLPurifier.standalone.php";

$purifier_config = HTMLPurifier_Config::createDefault();
$purifier_config->set('URI.AllowedSchemes', ['data' => true, 'src' => true, 'http' => true, 'https' => true]);
$purifier = new HTMLPurifier($purifier_config);

if (isset($_GET['document_id'])) {
	$document_id = intval($_GET['document_id']);
}

$folder_location = 0;

$sql_document = mysqli_query($mysqli, "SELECT * FROM documents 
  LEFT JOIN folders ON document_folder_id = folder_id
  LEFT JOIN users ON document_created_by = user_id
  WHERE document_client_id = $client_id AND document_id = $document_id"
);

$row = mysqli_fetch_array($sql_document);

$folder_name = nullable_htmlentities($row['folder_name']);
$document_name = nullable_htmlentities($row['document_name']);
$document_description = nullable_htmlentities($row['document_description']);
$document_content = $purifier->purify($row['document_content']);
$document_created_by_id = intval($row['document_created_by']);
$document_created_by_name = nullable_htmlentities($row['user_name']);
$document_created_at = nullable_htmlentities($row['document_created_at']);
$document_updated_at = nullable_htmlentities($row['document_updated_at']);
$document_archived_at = nullable_htmlentities($row['document_archived_at']);
$document_folder_id = intval($row['document_folder_id']);
$document_parent = intval($row['document_parent']);

?>

<ol class="breadcrumb d-print-none">
  <li class="breadcrumb-item">
    <a href="client_overview.php?client_id=<?= $client_id; ?>"><?= $client_name; ?></a>
  </li>
  <li class="breadcrumb-item">
    <a href="client_documents.php?client_id=<?= $client_id; ?>">Documents</a>
  </li>
  <?php if ($document_folder_id > 0) { ?>
  <li class="breadcrumb-item">
    <a href="client_documents.php?client_id=<?= $client_id; ?>&folder_id=<?= $document_folder_id; ?>"><i class="fas fa-fw fa-folder-open mr-2"></i><?= $folder_name; ?></a>
  </li>
  <?php } ?>
  <li class="breadcrumb-item active"><i class="fas fa-file"></i> <?= $document_name; ?> <?php if(!empty($document_archived_at)){ echo "<span class='text-danger ml-2'>(ARCHIVED on $document_archived_at)</span>"; } ?></li>
</ol>

<div class="row">

  <div class="col-md-9">
    <div class="card">
      <div class="card-header">

        <h3><?= $document_name; ?> <?php if (!empty($document_description)) { ?><span class="h6 text-muted">(<?= $document_description; ?>)</span><?php } ?></h3>

        <div class="row">
          <div class="col"><strong>Date:</strong> <?= date('Y-m-d', strtotime($document_created_at)); ?></div>
          <?php if(!empty($document_created_by_name)){ ?>
          <div class="col"><strong>Prepared By:</strong> <?= $document_created_by_name; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body prettyContent">
        <?= $document_content; ?>
      </div>
    </div>
  </div>

	<div class="col-md-3 d-print-none">
    <div class="row">
      <div class="col-12 mb-3">
        <button type="button" class="btn btn-label-primary mr-2" data-bs-toggle="modal" data-bs-target="#editDocumentModal<?= $document_id; ?>">
          <i class="fas fa-fw fa-edit mr-2"></i>Edit
        </button>
        <button type="button" class="btn btn-light mr-2" data-bs-toggle="modal" data-bs-target="#shareModal"
          onclick="populateShareModal(<?= "$client_id, 'Document', $document_id"; ?>)">
          <i class="fas fa-fw fa-share mr-2"></i>Share
        </button>
        <button type="button" class="btn btn-light" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i>Print</button>
      </div>
    </div>
    <div class="card card-body bg-light">
      <h5 class="mb-3"><i class="fas fa-tags mr-2"></i>Related Items</h5>
      <h6>
        <i class="fas fa-fw fa-paperclip text-secondary mr-2"></i>Files
        <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#linkFileToDocumentModal">
          <i class="fas fa-fw fa-plus"></i>
        </button>
      </h6>
      <?php
      $sql_files = mysqli_query($mysqli, "SELECT * FROM files, document_files
        WHERE document_files.file_id = files.file_id 
        AND document_files.document_id = $document_id
        ORDER BY file_name ASC"
      );

      $linked_files = array();

      while ($row = mysqli_fetch_array($sql_files)) {
        $file_id = intval($row['file_id']);
        $folder_id = intval($row['file_folder_id']);
        $file_name = nullable_htmlentities($row['file_name']);

        $linked_files[] = $file_id;

        ?>
        <div class="ml-2">
          <a href="client_files.php?client_id=<?= $client_id; ?>&folder_id=<?= $folder_id; ?>&q=<?= $file_name; ?>" target="_blank"><?= $file_name; ?></a>
          <a class="confirm-link" href="/post.php?unlink_file_from_document&file_id=<?= $file_id; ?>&document_id=<?= $document_id; ?>">
            <i class="fas fa-fw fa-trash-alt text-secondary float-right"></i>
          </a>
        </div>
        <?php
        }
        ?>
      <h6>
        <i class="fas fa-fw fa-users text-secondary mt-3 mr-2"></i>Contacts
        <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#linkContactToDocumentModal">
          <i class="fas fa-fw fa-plus"></i>
        </button>
      </h6>
      <?php
      $sql_contacts = mysqli_query($mysqli, "SELECT * FROM contacts, contact_documents
        WHERE contacts.contact_id = contact_documents.contact_id 
        AND contact_documents.document_id = $document_id
        ORDER BY contact_name ASC"
      );

      $linked_contacts = array();

      while ($row = mysqli_fetch_array($sql_contacts)) {
        $contact_id = intval($row['contact_id']);
        $contact_name = nullable_htmlentities($row['contact_name']);

        $linked_contacts[] = $contact_id;

        ?>
        <div class="ml-2">
          <a href="client_contact_details.php?client_id=<?= $client_id; ?>&contact_id=<?= $contact_id; ?>" target="_blank"><?= $contact_name; ?></a>
          <a class="confirm-link float-right" href="/post.php?unlink_contact_from_document&contact_id=<?= $contact_id; ?>&document_id=<?= $document_id; ?>">
            <i class="fas fa-fw fa-trash-alt text-secondary"></i>
          </a>
        </div>
        <?php
        }
        ?>
      <h6>
        <i class="fas fa-fw fa-laptop text-secondary mr-2 mt-3"></i>Assets
        <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#linkAssetToDocumentModal">
          <i class="fas fa-fw fa-plus"></i>
        </button>
      </h6>
      <?php
      $sql_assets = mysqli_query($mysqli, "SELECT * FROM assets, asset_documents
        WHERE assets.asset_id = asset_documents.asset_id
        AND asset_documents.document_id = $document_id
        ORDER BY asset_name ASC"
      );

      $linked_assets = array();

      while ($row = mysqli_fetch_array($sql_assets)) {
        $asset_id = intval($row['asset_id']);
        $asset_name = nullable_htmlentities($row['asset_name']);

        $linked_assets[] = $asset_id;

        ?>
        <div class="ml-2">
          <a href="client_asset_details.php?client_id=<?= $client_id; ?>&asset_id=<?= $asset_id; ?>" target="_blank"><?= $asset_name; ?></a>
          <a class="confirm-link float-right" href="/post.php?unlink_asset_from_document&asset_id=<?= $asset_id; ?>&document_id=<?= $document_id; ?>">
            <i class="fas fa-fw fa-trash-alt text-secondary"></i>
          </a>
        </div>
      <?php
      }
      ?>
      <h6>
        <i class="fas fa-fw fa-cube text-secondary mr-2 mt-3"></i>Licenses
        <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#linkSoftwareToDocumentModal">
          <i class="fas fa-fw fa-plus"></i>
        </button>
      </h6>
      <?php
      $sql_software = mysqli_query($mysqli, "SELECT * FROM software, software_documents
        WHERE software.software_id = software_documents.software_id 
        AND software_documents.document_id = $document_id
        ORDER BY software_name ASC"
      );

      $linked_software = array();

      while ($row = mysqli_fetch_array($sql_software)) {
        $software_id = intval($row['software_id']);
        $software_name = nullable_htmlentities($row['software_name']);

        $linked_software[] = $software_id;

        ?>
        <div class="ml-2">
          <a href="client_software.php?client_id=<?= $client_id; ?>&q=<?= $software_name; ?>" target="_blank"><?= $software_name; ?></a>
          <a class="confirm-link float-right" href="/post.php?unlink_software_from_document&software_id=<?= $software_id; ?>&document_id=<?= $document_id; ?>">
            <i class="fas fa-fw fa-trash-alt text-secondary"></i>
          </a>
        </div>
        <?php
        }
        ?>
      <h6>
        <i class="fas fa-fw fa-building text-secondary mr-2 mt-3"></i>Vendors
        <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#linkVendorToDocumentModal">
          <i class="fas fa-fw fa-plus"></i>
        </button>
      </h6>
      <?php
      $sql_vendors = mysqli_query($mysqli, "SELECT * FROM vendors, vendor_documents
        WHERE vendors.vendor_id = vendor_documents.vendor_id 
        AND vendor_documents.document_id = $document_id
        ORDER BY vendor_name ASC"
      );

      $associated_vendors = array();

      while ($row = mysqli_fetch_array($sql_vendors)) {
        $vendor_id = intval($row['vendor_id']);
        $vendor_name = nullable_htmlentities($row['vendor_name']);

        $associated_vendors[] = $vendor_id;

        ?>
        <div class="ml-2">
          <a href="client_vendors.php?client_id=<?= $client_id; ?>&q=<?= $vendor_name; ?>" target="_blank"><?= $vendor_name; ?></a>
          <a class="confirm-link float-right" href="/post.php?unlink_vendor_from_document&vendor_id=<?= $vendor_id; ?>&document_id=<?= $document_id; ?>">
            <i class="fas fa-fw fa-trash-alt text-secondary"></i>
          </a>
        </div>
      <?php
      }
      ?>
    </div>

    <div class="card card-body bg-light">
      <h6><i class="fas fa-history mr-2"></i>Revisions</h6>
      <?php

      $sql_document_revisions = mysqli_query($mysqli, "SELECT * FROM documents
        LEFT JOIN users ON document_created_by = user_id
        WHERE document_parent = $document_parent
        ORDER BY document_created_at DESC"
      );

      while ($row = mysqli_fetch_array($sql_document_revisions)) {
        $revision_document_id = intval($row['document_id']);
        $revision_document_name = nullable_htmlentities($row['document_name']);
        $revision_document_description = nullable_htmlentities($row['document_description']);
        $revision_document_created_by_name = nullable_htmlentities($row['user_name']);
        $revision_document_created_date = nullable_htmlentities($row['document_created_at']);
        $revision_document_created_date = nullable_htmlentities($row['document_created_at']);

        ?>
        <div class="mt-1 <?php if($document_id == $revision_document_id){ echo "text-bold"; } ?>">
          <i class="fas fa-fw fa-history text-secondary mr-2"></i><a href="?client_id=<?= $client_id; ?>&document_id=<?= $revision_document_id; ?>"><?= "  $revision_document_created_date"; ?></a><?php if($document_parent == $revision_document_id){ echo "<span class='float-right'>(Parent)</span>"; 
            } else { ?>
              <a class="confirm-link float-right" href="/post.php?delete_document_version=<?= $revision_document_id; ?>">
                <i class="fas fa-fw fa-trash-alt text-secondary"></i>
              </a>
            <?php 
            } 
            ?>
        </div>
        <?php
        }
        ?>
    </div>

	</div>

</div>

<script src="/includes/js/pretty_content.js"></script>

<?php

require_once "/var/www/portal.twe.tech/includes/modals/client_document_edit_modal.php";

require_once "/var/www/portal.twe.tech/includes/modals/client_document_link_file_modal.php";

require_once "/var/www/portal.twe.tech/includes/modals/client_document_link_contact_modal.php";

require_once "/var/www/portal.twe.tech/includes/modals/client_document_link_asset_modal.php";

require_once "/var/www/portal.twe.tech/includes/modals/client_document_link_software_modal.php";

require_once "/var/www/portal.twe.tech/includes/modals/client_document_link_vendor_modal.php";

require_once "/var/www/portal.twe.tech/includes/modals/share_modal.php";

require_once '/var/www/portal.twe.tech/includes/footer.php';

