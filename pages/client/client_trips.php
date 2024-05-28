<?php

// Default Column Sortby Filter
$sort = "trip_date";
$order = "DESC";

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM trips
    LEFT JOIN users ON trip_user_id = user_id
    WHERE (trip_purpose LIKE '%$q%' OR trip_source LIKE '%$q%' OR trip_destination LIKE '%$q%' OR user_name LIKE '%$q%')
    AND trip_client_id = $client_id
    ORDER BY $sort $order");

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-route mr-2"></i>Trips</h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="trip_add_modal.php?client_id=<?= $client_id; ?>"><i class="fas fa-plus mr-2"></i>New Trip</button>
                    <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"></button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#exportTripModal">
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
                        <div class="float-right">
                        </div>
                    </div>

                </div>
            </form>
            <hr>
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
<table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                    <tr>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=trip_date&order=<?= $disp; ?>">Date</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=user_name&order=<?= $disp; ?>">Driver</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=trip_purpose&order=<?= $disp; ?>">Purpose</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=trip_source&order=<?= $disp; ?>">From</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=trip_destination&order=<?= $disp; ?>">To</a></th>
                        <th><a class="text-dark" href="?<?= $url_query_strings_sort; ?>&sort=trip_miles&order=<?= $disp; ?>">Miles</a></th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $trip_id = intval($row['trip_id']);
                        $trip_date = nullable_htmlentities($row['trip_date']);
                        $trip_purpose = nullable_htmlentities($row['trip_purpose']);
                        $trip_source = nullable_htmlentities($row['trip_source']);
                        $trip_destination = nullable_htmlentities($row['trip_destination']);
                        $trip_miles = number_format(floatval($row['trip_miles']),1);
                        $trip_user_id = intval($row['trip_user_id']);
                        $round_trip = nullable_htmlentities($row['round_trip']);
                        $client_id = intval($row['trip_client_id']);

                        if ($round_trip == 1) {
                            $round_trip_display = "<i class='fa fa-fw fa-sync-alt text-secondary'></i>";
                        } else {
                            $round_trip_display = "";
                        }
                        $user_name = nullable_htmlentities($row['user_name']);
                        if (empty($user_name)) {
                            $user_name_display = "-";
                        } else {
                            $user_name_display = $user_name;
                        }

                        ?>
                        <tr>
                            <td><a class="text-dark" href="#" data-bs-toggle="modal" data-bs-target="#editTripModal<?= $trip_id; ?>"><?= $trip_date; ?></a></td>
                            <td><?= $user_name_display; ?></td>
                            <td><?= $trip_purpose; ?></td>
                            <td><?= $trip_source; ?></td>
                            <td><?= $trip_destination; ?></td>
                            <td><?= "$trip_miles $round_trip_display"; ?></td>
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="//maps.google.com?q=<?= $trip_source; ?> to <?= $trip_destination; ?>" target="_blank">
                                            <i class="fa fa-fw fa-map-marker-alt mr-2"></i>Map it
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editTripModal<?= $trip_id; ?>">
                                            <i class="fa fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addTripCopyModal<?= $trip_id; ?>">
                                            <i class="fa fa-fw fa-copy mr-2"></i>Copy
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_trip=<?= $trip_id; ?>">
                                            <i class="fa fa-fw fa-trash mr-2"></i>Delete
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

