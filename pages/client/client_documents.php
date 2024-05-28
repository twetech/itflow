<?php

// Default Column Sortby Filter
$sort = "document_name";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


// Folder
if (!empty($_GET['folder_id'])) {
    $folder = intval($_GET['folder_id']);
} else {
    $folder = 0;
}


//Rebuild URL

// Folder ID
$get_folder_id = 0;
if (!empty($_GET['folder_id'])) {
    $get_folder_id = intval($_GET['folder_id']);
}

// Set Folder Location Var used when creating folders
$folder_location = 0;

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM documents
    LEFT JOIN users ON document_created_by = user_id
    WHERE document_client_id = $client_id
    AND document_template = 0
    AND document_folder_id = $folder
    AND document_archived_at IS NULL
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2">
                <i class="fa fa-fw fa-folder mr-2"></i>Documents
            </h3>
            <div class="card-tools">

                <div class="btn-group">
                    <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="client_document_add_modal.php?client_id=<?= $client_id; ?>">
                        <i class="fas fa-plus mr-2"></i>Create
                    </button>
                    <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                            <i class="fa fa-fw fa-folder-plus mr-2"></i>Folder
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#addDocumentFromTemplateModal">From Template</a>
                    </div>
                </div>

            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 border-right mb-3">
                    <h4>Folders</h4>
                    <hr>
                    <ul class="nav nav-pills flex-column bg-light">
                        <li class="nav-item">
                            <div class="row">
                                <div class="col-10">
                                    
                                    <?php
                                    // Get a count of documents that have no folder
                                    $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('document_id') AS num FROM documents WHERE document_folder_id = 0 AND document_client_id = $client_id AND document_archived_at IS NULL"));
                                    $num_documents = intval($row['num']);
                                    ?>
                                    <a class="nav-link <?php if ($get_folder_id == 0) { echo "active"; } ?>" href="?client_id=<?= $client_id; ?>&folder_id=0">/ <?php if ($num_documents > 0) { echo "<span class='badge rounded-pill bg-label-dark float-right mt-1'>$num_documents</span>"; } ?></a>
                                </div>
                                <div class="col-2">
                                </div>
                            </div>
                        </li>
                        <?php
                        $sql_folders = mysqli_query($mysqli, "SELECT * FROM folders WHERE folder_location = $folder_location AND folder_client_id = $client_id ORDER BY folder_name ASC");
                        while ($row = mysqli_fetch_array($sql_folders)) {
                            $folder_id = intval($row['folder_id']);
                            $folder_name = nullable_htmlentities($row['folder_name']);

                            $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('document_id') AS num FROM documents WHERE document_folder_id = $folder_id AND document_archived_at IS NULL"));
                            $num_documents = intval($row['num']);

                            ?>

                            <li class="nav-item">
                                <div class="row">
                                    <div class="col-10">
                                        <a class="nav-link <?php if ($get_folder_id == $folder_id) { echo "active"; } ?> " href="?client_id=<?= $client_id; ?>&folder_id=<?= $folder_id; ?>">
                                            <?php
                                            if ($get_folder_id == $folder_id) { ?>
                                                <i class="fas fa-fw fa-folder-open"></i>
                                            <?php } else { ?>
                                                <i class="fas fa-fw fa-folder"></i>
                                            <?php } ?>

                                            <?= $folder_name; ?> <?php if ($num_documents > 0) { echo "<span class='badge rounded-pill bg-label-dark float-right mt-1'>$num_documents</span>"; } ?>
                                        </a>
                                    </div>
                                    <div class="col-2">
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#renameFolderModal<?= $folder_id; ?>">
                                                    <i class="fas fa-fw fa-edit mr-2"></i>Rename
                                                </a>
                                                <?php if ($session_user_role == 3 && $num_documents == 0) { ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_folder=<?= $folder_id; ?>">
                                                        <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <?php
                            require "/var/www/portal.twe.tech/includes/modals/folder_rename_modal.php";


                        }
                        ?>
                    </ul>
                    <?php require_once "/var/www/portal.twe.tech/includes/modals/folder_create_modal.php";
 ?>
                </div>

                <div class="col-md-9">
                    <form autocomplete="off">
                        <input type="hidden" name="client_id" value="<?= $client_id; ?>">
                        <input type="hidden" name="folder_id" value="<?= $get_folder_id; ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group mb-3 mb-md-0">
                                    <input type="search" class="form-control" name="q" value="<?php if (isset($q)) { echo stripslashes(nullable_htmlentities($q)); } ?>" placeholder="Search Documents">
                                    <div class="input-group-append">
                                        <button class="btn btn-dark"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group float-right">
                                    <div class="dropdown ml-2" id="bulkActionButton" hidden>
                                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-fw fa-layer-group mr-2"></i>Bulk Action (<span id="selectedCount">0</span>)
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkMoveDocumentModal">
                                                <i class="fas fa-fw fa-exchange-alt mr-2"></i>Move
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <hr>
    
                    <form id="bulkActions" action="/post.php" method="post">

                        <div class="card-datatable table-responsive container-fluid  pt-0">                            <table id=responsive class="responsive table table-striped table-sm table-borderless table-hover">
                                <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                                <tr>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Created
                                    </th>
                                    <th>
                                        Last Update
                                    </th>
                                    <th class="text-center">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                while ($row = mysqli_fetch_array($sql)) {
                                    $document_id = intval($row['document_id']);
                                    $document_name = nullable_htmlentities($row['document_name']);
                                    $document_description = nullable_htmlentities($row['document_description']);
                                    $document_content = nullable_htmlentities($row['document_content']);
                                    $document_created_by_name = nullable_htmlentities($row['user_name']);
                                    $document_created_at = date("m/d/Y",strtotime($row['document_created_at']));
                                    $document_updated_at = date("m/d/Y",strtotime($row['document_updated_at']));
                                    $document_folder_id = intval($row['document_folder_id']);

                                    ?>

                                    <tr>
                                        <td>
                                            <a href="client_document_details.php?client_id=<?= $client_id; ?>&document_id=<?= $document_id; ?>"><i class="fas fa-fw fa-file-alt"></i> <?= $document_name; ?></a>
                                            <div class="text-secondary mt-1"><?= $document_description; ?>
                                        </td>
                                        <td>
                                            <?= $document_created_at; ?>
                                            <div class="text-secondary mt-1"><?= $document_created_by_name; ?>
                                        </td>
                                        <td><?= $document_updated_at; ?></td>
                                        <td>
                                            <div class="dropdown dropleft text-center">
                                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#shareModal" onclick="populateShareModal(<?= "$client_id, 'Document', $document_id"; ?>)">
                                                        <i class="fas fa-fw fa-share mr-2"></i>Share
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#renameDocumentModal<?= $document_id; ?>">
                                                        <i class="fas fa-fw fa-pencil-alt mr-2"></i>Rename
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#moveDocumentModal<?= $document_id; ?>">
                                                        <i class="fas fa-fw fa-exchange-alt mr-2"></i>Move
                                                    </a>
                                                    <?php if ($session_user_role == 3) { ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_document=<?= $document_id; ?>">
                                                            <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_document=<?= $document_id; ?>">
                                                            <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                <?php

                                }

                                ?>

                                </tbody>
                            </table>
                            <br>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

<?php

require_once '/var/www/portal.twe.tech/includes/footer.php';
