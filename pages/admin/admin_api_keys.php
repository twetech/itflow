<?php

// Default Column Sortby Filter
$sort = "api_key_name";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all_admin.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM api_keys
    LEFT JOIN clients on api_keys.api_key_client_id = clients.client_id
    WHERE (api_key_name LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-key mr-2"></i>API Keys</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addApiKeyModal"><i class="fas fa-plus mr-2"></i>Create</button>
            </div>
        </div>

        <div class="card-body">

            <form autocomplete="off">
                <div class="row">



                    <div class="col-md-8">
                        <div class="btn-group float-right">
                            <div class="dropdown ml-2" id="bulkActionButton" hidden>
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-fw fa-layer-group mr-2"></i>Bulk Action (<span id="selectedCount">0</span>)
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-danger text-bold"
                                            type="submit" form="bulkActions" name="bulk_delete_api_keys">
                                        <i class="fas fa-fw fa-trash mr-2"></i>Revoke
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </form>
            <hr>

            <div class="card-datatable table-responsive container-fluid  pt-0">
                <form id="bulkActions" action="/post.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                       
<table class="datatables-basic table border-top">
                        <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                        <tr>
                            <td class="pr-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" onclick="checkAll(this)">
                                </div>
                            </td>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=api_key_name&order=<?= $disp; ?>">Name</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=api_key_client_id&order=<?= $disp; ?>">Client</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=api_key_secret&order=<?= $disp; ?>">Secret</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=api_key_created_at&order=<?= $disp; ?>">Created</a></th>
                            <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=api_key_expire&order=<?= $disp; ?>">Expires</a></th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($row = mysqli_fetch_array($sql)) {
                            $api_key_id = intval($row['api_key_id']);
                            $api_key_name = nullable_htmlentities($row['api_key_name']);
                            $api_key_secret = nullable_htmlentities("************" . substr($row['api_key_secret'], -4));
                            $api_key_created_at = nullable_htmlentities($row['api_key_created_at']);
                            $api_key_expire = nullable_htmlentities($row['api_key_expire']);
                            if ($api_key_expire < date("Y-m-d H:i:s")) {
                                $api_key_expire = $api_key_expire . " (Expired)";
                            }

                            if ($row['api_key_client_id'] == 0) {
                                $api_key_client = "<i>All Clients</i>";
                            } else {
                                $api_key_client = nullable_htmlentities($row['client_name']);
                            }

                            ?>
                            <tr>

                                <td class="text-bold"><?= $api_key_name; ?></td>

                                <td><?= $api_key_client; ?></td>

                                <td><?= $api_key_secret; ?></td>

                                <td><?= $api_key_created_at; ?></td>

                                <td><?= $api_key_expire; ?></td>

                                <td>
                                    <div class="dropdown dropleft text-center">
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_api_key=<?= $api_key_id; ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>">
                                                <i class="fas fa-fw fa-times mr-2"></i>Revoke
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        <?php } ?>


                        </tbody>
                    </table>

                </form>

            </div>
        </div>
    </div>


<?php
require_once '/var/www/portal.twe.tech/includes/footer.php';

