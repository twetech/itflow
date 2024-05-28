<?php

// Default Column Sortby Filter
$sort = "network_name";
$order = "ASC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL
$url_query_strings_sb = http_build_query(array_merge($_GET, array('sort' => $sort, 'order' => $order)));

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM networks
    LEFT JOIN locations ON location_id = network_location_id
    WHERE network_client_id = $client_id
    AND network_archived_at IS NULL
    AND (network_name LIKE '%$q%' OR network_description LIKE '%$q%' OR network_vlan LIKE '%$q%' OR network LIKE '%$q%' OR network_gateway LIKE '%$q%' OR network_dhcp_range LIKE '%$q%' OR location_name LIKE '%$q%')
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-network-wired mr-2"></i>Networks</h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="client_network_add_modal.php?client_id=<?= $client_id; ?>"><i class="fas fa-plus mr-2"></i>New Network</button>
                    <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exportNetworkModal">
                            <i class="fa fa-fw fa-download mr-2"></i>Export
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <input type="hidden" name="client_id" value="<?= $client_id; ?>">
                <div class="row">

                    <div class="col-md-8">
                        <div class="btn-group float-right">
                            <div class="dropdown ml-2" id="bulkActionButton" hidden>
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-fw fa-layer-group mr-2"></i>Bulk Action (<span id="selectedCount">0</span>)
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-danger text-bold confirm-link"
                                            type="submit" form="bulkActions" name="bulk_delete_networks">
                                        <i class="fas fa-fw fa-trash mr-2"></i>Delete
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
                                    <input class="form-check-input" id="selectAllCheckbox"  type="checkbox" onclick="checkAll(this)">
                                </div>
                            </td>
                            <th><a class="text-secondary" href="?<?= $url_query_strings_sb; ?>&sort=network_name&order=<?= $disp; ?>">Name</a></th>
                            <th><a class="text-secondary" href="?<?= $url_query_strings_sb; ?>&sort=network_vlan&order=<?= $disp; ?>">vLAN</a></th>
                            <th><a class="text-secondary" href="?<?= $url_query_strings_sb; ?>&sort=network&order=<?= $disp; ?>">Network</a></th>
                            <th><a class="text-secondary" href="?<?= $url_query_strings_sb; ?>&sort=network_gateway&order=<?= $disp; ?>">Gateway</a></th>
                            <th><a class="text-secondary" href="?<?= $url_query_strings_sb; ?>&sort=network_dhcp_range&order=<?= $disp; ?>">DHCP Range</a></th>
                            <th><a class="text-secondary" href="?<?= $url_query_strings_sb; ?>&sort=location_name&order=<?= $disp; ?>">Location</a></th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($row = mysqli_fetch_array($sql)) {
                            $network_id = intval($row['network_id']);
                            $network_name = nullable_htmlentities($row['network_name']);
                            $network_description = nullable_htmlentities($row['network_description']);
                            $network_vlan = intval($row['network_vlan']);
                            if (empty($network_vlan)) {
                                $network_vlan_display = "-";
                            } else {
                                $network_vlan_display = $network_vlan;
                            }
                            $network = nullable_htmlentities($row['network']);
                            $network_gateway = nullable_htmlentities($row['network_gateway']);
                            $network_dhcp_range = nullable_htmlentities($row['network_dhcp_range']);
                            if (empty($network_dhcp_range)) {
                                $network_dhcp_range_display = "-";
                            } else {
                                $network_dhcp_range_display = $network_dhcp_range;
                            }
                            $network_location_id = intval($row['network_location_id']);
                            $location_name = nullable_htmlentities($row['location_name']);
                            if (empty($location_name)) {
                                $location_name_display = "-";
                            } else {
                                $location_name_display = $location_name;
                            }

                            ?>
                            <tr>
                                <td>
                                    <a class="text-dark" href="#" data-bs-toggle="modal" onclick="populateNetworkEditModal(<?= $client_id, ",", $network_id ?>)" data-bs-target="#editNetworkModal">
                                        <div class="media">
                                            <i class="fa fa-fw fa-2x fa-network-wired mr-3"></i>
                                            <div class="media-body">
                                                <div><?= $network_name; ?></div>
                                                <div><small class="text-secondary"><?= $network_description; ?></small></div>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td><?= $network_vlan_display; ?></td>
                                <td><?= $network; ?></td>
                                <td><?= $network_gateway; ?></td>
                                <td><?= $network_dhcp_range_display; ?></td>
                                <td><?= $location_name_display; ?></td>
                                <td>
                                    <div class="dropdown dropleft text-center">
                                        <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" onclick="populateNetworkEditModal(<?= $client_id, ",", $network_id ?>)" data-bs-target="#editNetworkModal">
                                                <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                            </a>
                                            <?php if ($session_user_role == 3) { ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_network=<?= $network_id; ?>">
                                                    <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_network=<?= $network_id; ?>">
                                                    <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                                </a>
                                            <?php } ?>
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
