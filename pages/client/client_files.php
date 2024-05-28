<?php

// Default Column Sortby Filter
$sort = "file_name";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


// Folder
if (!empty($_GET['folder_id'])) {
    $folder_id = intval($_GET['folder_id']);
} else {
    $folder_id = 0;
}

//Rebuild URL

// Folder ID
$get_folder_id = 0;
if (!empty($_GET['folder_id'])) {
    $get_folder_id = intval($_GET['folder_id']);
}

// View Mode -- 0 List, 1 Thumbnail
if (!empty($_GET['view'])) {
    $view = intval($_GET['view']);
} else {
    $view = 0;
}

if ($view == 1) {
    $query_images = "AND (file_ext LIKE 'JPG' OR file_ext LIKE 'jpg' OR file_ext LIKE 'JPEG' OR file_ext LIKE 'jpeg' OR file_ext LIKE 'png' OR file_ext LIKE 'PNG' OR file_ext LIKE 'webp' OR file_ext LIKE 'WEBP')";
} else {
    $query_images = '';
}

// Set Folder Location Var used when creating folders
$folder_location = 1;

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM files
    WHERE file_client_id = $client_id
    AND file_folder_id = $folder_id
    AND file_archived_at IS NULL
    AND (file_name LIKE '%$q%' OR file_ext LIKE '%$q%' OR file_description LIKE '%$q%')
    $query_images
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

$num_of_files = mysqli_num_rows($sql);

?>

<div class="card">
    
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fa fa-fw fa-paperclip mr-2"></i>Files</h3>
        
        <div class="card-tools">
            <div class="btn-group">
                <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#uploadFilesModal">
                    <i class="fas fa-fw fa-cloud-upload-alt mr-2"></i>Upload
                </button>
                <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item text-dark loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="folder_create_modal.php?client_id=<?= $client_id; ?>">
                        <i class="fa fa-fw fa-folder-plus mr-2"></i>Create Folder
                    </a>
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
                        <a class="nav-link <?php if ($get_folder_id == 0) { echo "active"; } ?>" href="?client_id=<?= $client_id; ?>&folder_id=0">/</a>
                    </li>
                    <?php
                    $sql_folders = mysqli_query($mysqli, "SELECT * FROM folders WHERE folder_location = $folder_location AND folder_client_id = $client_id ORDER BY folder_name ASC");
                    while ($row = mysqli_fetch_array($sql_folders)) {
                        $folder_id = intval($row['folder_id']);
                        $folder_name = nullable_htmlentities($row['folder_name']);

                        $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT('file_id') AS num FROM files WHERE file_archived_at IS NULL AND file_folder_id = $folder_id"));
                        $num_files = intval($row['num']);

                        ?>

                        <li class="nav-item">
                            <div class="row">
                                <div class="col-10">
                                    <a class="nav-link <?php if ($get_folder_id == $folder_id) { echo "active"; } ?> " href="?client_id=<?= $client_id; ?>&folder_id=<?= $folder_id; ?>&view=<?= $view; ?>">
                                        <?php
                                        if ($get_folder_id == $folder_id) { ?>
                                            <i class="fas fa-fw fa-folder-open"></i>
                                        <?php } else { ?>
                                            <i class="fas fa-fw fa-folder"></i>
                                        <?php } ?>

                                        <?= $folder_name; ?> <?php if ($num_files > 0) { echo "<span class='badge rounded-pill bg-label-dark float-right mt-1'>$num_files</span>"; } ?>
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
                                            <?php if ($session_user_role == 3 && $num_files == 0) { ?>
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
                    <input type="hidden" name="view" value="<?= $view; ?>">
                    <input type="hidden" name="folder_id" value="<?= $get_folder_id; ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group mb-3 mb-md-0">
                                <input type="search" class="form-control" name="q" value="<?php if (isset($q)) { echo stripslashes(nullable_htmlentities($q)); } ?>" placeholder="Search Files">
                                <div class="input-group-append">
                                    <button class="btn btn-dark"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="btn-group float-right">
                                <a href="?<?= $url_query_strings_sort; ?>&view=0" class="btn <?php if($view == 0){ echo "btn-soft-primary"; } else { echo "btn-outline-secondary"; } ?>"><i class="fas fa-list-ul"></i></a>
                                <a href="?<?= $url_query_strings_sort; ?>&view=1" class="btn <?php if($view == 1){ echo "btn-soft-primary"; } else { echo "btn-outline-secondary"; } ?>"><i class="fas fa-th-large"></i></a>
                                
                                <div class="dropdown ml-2" id="bulkActionButton" hidden>
                                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-fw fa-layer-group mr-2"></i>Bulk Action (<span id="selectedCount">0</span>)
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkMoveFilesModal">
                                            <i class="fas fa-fw fa-exchange-alt mr-2"></i>Move
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
                
                <hr>

                <?php
                if ($num_of_files == 0) {
                    echo "<div style='text-align: center;'><h3 class='text-secondary'>No Records Here</h3></div>";
                }

                if($view == 1){

                ?>

                <div class="row">

                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $file_id = intval($row['file_id']);
                        $file_name = nullable_htmlentities($row['file_name']);
                        $file_reference_name = nullable_htmlentities($row['file_reference_name']);
                        $file_ext = nullable_htmlentities($row['file_ext']);

                        ?>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-3">
                            <div class="card">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#viewFileModal<?= $file_id; ?>">
                                    <img class="img-fluid" src="<?= "/uploads/clients/$client_id/$file_reference_name"; ?>" alt="<?= $file_reference_name ?>">
                                </a>
                                <div class="card-footer bg-dark text-white p-1" style="text-align: center;">
                                    <a href="<?= "/uploads/clients/$client_id/$file_reference_name"; ?>" download="<?= $file_name; ?>" class="text-white float-left ml-1"><i class="fa fa-cloud-download-alt"></i></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#shareModal" onclick="populateShareModal(<?= "$client_id, 'File', $file_id"; ?>)" class="text-white float-left ml-1"><i class="fa fa-share"></i></a>

                                    <small><?= $file_name; ?></small>

                                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteFileModal" onclick="populateFileDeleteModal(<?= "$file_id , '$file_name'" ?>)" class="text-white float-right mr-1"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                        </div>

                        <?php
                        require "/var/www/portal.twe.tech/includes/modals/client_file_view_modal.php";

                    }
                    ?>
                </div>

                <?php } else { ?>

                <form id="bulkActions" action="/post.php" method="post">

                    <div class="card-datatable table-responsive container-fluid  pt-0">                        <table class="datatables-basic table border-top">
                            
                            <thead class="thead-light <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                            <tr>
                                <th><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=file_name&order=<?= $disp; ?>">Name</a></th>
                                <th><a class="text-secondary" href="?<?= $url_query_strings_sort; ?>&sort=file_created_at&order=<?= $disp; ?>">Uploaded</a></th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            
                            <tbody>

                            <?php
                            while ($row = mysqli_fetch_array($sql)) {
                                $file_id = intval($row['file_id']);
                                $file_name = nullable_htmlentities($row['file_name']);
                                $file_description = nullable_htmlentities($row['file_description']);
                                $file_reference_name = nullable_htmlentities($row['file_reference_name']);
                                $file_ext = nullable_htmlentities($row['file_ext']);
                                if ($file_ext == 'pdf') {
                                    $file_icon = "file-pdf";
                                } elseif ($file_ext == 'gz' || $file_ext == 'tar' || $file_ext == 'zip' || $file_ext == '7z' || $file_ext == 'rar') {
                                    $file_icon = "file-archive";
                                } elseif ($file_ext == 'txt' || $file_ext == 'md') {
                                    $file_icon = "file-alt";
                                } elseif ($file_ext == 'msg') {
                                    $file_icon = "envelope";
                                } elseif ($file_ext == 'doc' || $file_ext == 'docx' || $file_ext == 'odt') {
                                    $file_icon = "file-word";
                                } elseif ($file_ext == 'xls' || $file_ext == 'xlsx' || $file_ext == 'ods') {
                                    $file_icon = "file-excel";
                                } elseif ($file_ext == 'pptx' || $file_ext == 'odp') {
                                    $file_icon = "file-powerpoint";
                                } elseif ($file_ext == 'mp3' || $file_ext == 'wav' || $file_ext == 'ogg') {
                                    $file_icon = "file-audio";
                                } elseif ($file_ext == 'mov' || $file_ext == 'mp4' || $file_ext == 'av1') {
                                    $file_icon = "file-video";
                                } elseif ($file_ext == 'jpg' || $file_ext == 'jpeg' || $file_ext == 'png' || $file_ext == 'gif' || $file_ext == 'webp' || $file_ext == 'bmp' || $file_ext == 'tif') {
                                    $file_icon = "file-image";
                                } else {
                                    $file_icon = "file";
                                }
                                $file_created_at = nullable_htmlentities($row['file_created_at']);
                                ?>

                                <tr>
                                    <td>
                                        <a href="<?= "/uploads/clients/$client_id/$file_reference_name"; ?>" target="_blank" class="text-secondary">
                                            <div class="media">
                                                <i class="fa fa-fw fa-2x fa-<?= $file_icon; ?> mr-3"></i>
                                                <div class="media-body">
                                                    <p>
                                                        <?= basename($file_name); ?>
                                                        <br>
                                                        <small class="text-secondary"><?= $file_description; ?></small>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td><?= $file_created_at; ?></td>
                                    <td>
                                        <div class="dropdown dropleft text-center">
                                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="<?= "/uploads/clients/$client_id/$file_reference_name"; ?>" download="<?= $file_name; ?>">
                                                    <i class="fas fa-fw fa-cloud-download-alt mr-2"></i>Download
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#shareModal" onclick="populateShareModal(<?= "$client_id, 'File', $file_id"; ?>)">
                                                    <i class="fas fa-fw fa-share mr-2"></i>Share
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#renameFileModal<?= $file_id; ?>">
                                                    <i class="fas fa-fw fa-edit mr-2"></i>Rename
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#moveFileModal<?= $file_id; ?>">
                                                    <i class="fas fa-fw fa-exchange-alt mr-2"></i>Move
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_file=<?= $file_id; ?>">
                                                    <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger text-bold" href="#" data-bs-toggle="modal" data-bs-target="#deleteFileModal" onclick="populateFileDeleteModal(<?= "$file_id , '$file_name'" ?>)">
                                                    <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php

                            }
                            ?>
                            </tbody>

                        </table>
                    </div>
                </form>

                <?php } ?>

            </div>
        </div>
    </div>
</div>


<?php


require_once '/var/www/portal.twe.tech/includes/footer.php';
