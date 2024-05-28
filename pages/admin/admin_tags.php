<?php

// Default Column Sortby Filter
$sort = "tag_name";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all_admin.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM tags
    WHERE tag_name LIKE '%$q%'
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-tags mr-2"></i>Tags</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addTagModal"><i class="fas fa-plus mr-2"></i>New Tag</button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-4 mb-2">
                    <form autocomplete="off">
                        <div class="input-group">
                            <input type="search" class="form-control" name="q" value="<?php if (isset($q)) { echo stripslashes(nullable_htmlentities($q)); } ?>" placeholder="Search Tags">
                            <div class="input-group-append">
                                <button class="btn btn-label-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-8">
                </div>
            </div>

            <hr>
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=tag_name&order=<?= $disp; ?>">Name</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=tag_type&order=<?= $disp; ?>">Type</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $tag_id = intval($row['tag_id']);
                        $tag_name = nullable_htmlentities($row['tag_name']);
                        $tag_type = intval($row['tag_type']);
                        $tag_color = nullable_htmlentities($row['tag_color']);
                        $tag_icon = nullable_htmlentities($row['tag_icon']);

                        ?>
                        <tr>
                            <td>
                                <a href="#" data-bs-toggle="modal" class="loadModalContentBtn" data-bs-target="#dynamicModal" data-modal-file="admin_tag_edit_modal.php?tag_id=<?= $tag_id; ?>">
                                    <span class='badge p-2 mr-1' style="background-color: <?= $tag_color; ?>"><i class="fa fa-fw fa-<?= $tag_icon; ?> mr-2"></i><?= $tag_name; ?></span>
                                </a>
                            </td>
                            <td><?= $tag_type; ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="admin_tag_edit_modal.php?tag_id=<?= $tag_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_tag=<?= $tag_id; ?>">
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

        </div>
    </div>

<?php

require_once '/var/www/portal.twe.tech/includes/footer.php';

