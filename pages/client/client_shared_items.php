<?php

// Default Column Sortby Filter
$sort = "item_created_at";
$order = "DESC";

require_once "/var/www/develop.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM shared_items
    WHERE item_client_id = $client_id
    AND item_active = '1'
    AND item_views != item_view_limit
    AND item_expire_at > NOW()
    AND (item_note LIKE '%$q%') ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-3">
            <h3 class="card-title"><i class="fa fa-fw fa-share mr-2"></i>Shared Items (Links)</h3>
        </div>
        <div class="card-body">

            <hr>
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th>Item Name</th>
                        <th>Item Type</th>
                        <th>Share Note</th>
                        <th>Views</th>
                        <th>Expires</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $item_id = intval($row['item_id']);
                        $item_active = nullable_htmlentities($row['item_active']);
                        $item_key = nullable_htmlentities($row['item_key']);
                        $item_type = nullable_htmlentities($row['item_type']);
                        $item_related_id = intval($row['item_related_id']);
                        $item_note = nullable_htmlentities($row['item_note']);
                        $item_views = nullable_htmlentities($row['item_views']);
                        $item_view_limit = nullable_htmlentities($row['item_view_limit']);
                        $item_created_at = nullable_htmlentities($row['item_created_at']);
                        $item_expire_at = nullable_htmlentities($row['item_expire_at']);

                        if ($item_type == 'Login') {
                            $share_item_sql = mysqli_query($mysqli, "SELECT login_name FROM logins WHERE login_id = '$item_related_id' AND login_client_id = '$client_id'");
                            $share_item = mysqli_fetch_array($share_item_sql);
                            $item_name = nullable_htmlentities($share_item['login_name']);
                        } elseif ($item_type == 'Document') {
                            $share_item_sql = mysqli_query($mysqli, "SELECT document_name FROM documents WHERE document_id = '$item_related_id' AND document_client_id = '$client_id'");
                            $share_item = mysqli_fetch_array($share_item_sql);
                            $item_name = nullable_htmlentities($share_item['document_name']);
                        } elseif ($item_type == 'File') {
                            $share_item_sql = mysqli_query($mysqli, "SELECT file_name FROM files WHERE file_id = '$item_related_id' AND file_client_id = '$client_id'");
                            $share_item = mysqli_fetch_array($share_item_sql);
                            $item_name = nullable_htmlentities($share_item['file_name']);
                        }


                        ?>
                        <tr>
                            <td><?php echo $item_name; ?></td>
                            <td><?php echo $item_type ?></td>
                            <td><?php echo $item_note ?></td>
                            <td><?php echo "$item_views / $item_view_limit" ?></td>
                            <td><?php echo $item_expire_at ?></td>
                            <td>
                                <?php if ($session_user_role == 3) { ?>
                                    <div class="dropdown dropleft text-center">
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item text-danger confirm-link" href="/post.php?deactivate_shared_item=<?php echo $item_id; ?>">
                                                <i class="fas fa-fw fa-times mr-2"></i>Deactivate
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>
                </table>
            </div>
            <?php require_once '/var/www/develop.twe.tech/includes/pagination.php';
 ?>
        </div>
    </div>

<?php
require_once '/var/www/develop.twe.tech/includes/footer.php';

