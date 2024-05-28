<?php

// Default Column Sortby/Order Filter
$sort = "product_name";
$order = "ASC";

// TODO: Put this in company settings
$margin_goal = 18;

require_once "/var/www/portal.twe.tech/includes/inc_all.php";


//Rebuild URL
$url_query_strings_sort = http_build_query(array_merge($_GET, array('sort' => $sort, 'o' => $order)));

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM products
    LEFT JOIN categories ON product_category_id = category_id
    LEFT JOIN taxes ON product_tax_id = tax_id
    WHERE product_archived_at IS NULL
    ORDER BY $sort $order"
);

$num_rows = mysqli_fetch_row(mysqli_query($mysqli, "SELECT FOUND_ROWS()"));

?>

    <div class="card">
        <div class="card-header py-2">
            <h3 class="card-title mt-2"><i class="fas fa-fw fa-box-open mr-2"></i>Products</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-label-primary loadModalContentBtn" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-modal-file="product_add_modal.php"><i class="fas fa-plus mr-2"></i>New Product</button>
            </div>
        </div>

        <div class="card-body">
            <div class="card-datatable table-responsive container-fluid  pt-0">                   
                <table class="datatables-basic table border-top">
                    <thead class="text-dark <?php if ($num_rows[0] == 0) { echo "d-none"; } ?>">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Tax Name</th>
                            <th>Tax Rate</th>
                            <th>Price</th>
                            <th>Margin</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row = mysqli_fetch_array($sql)) {
                        $product_id = intval($row['product_id']);
                        $product_name = nullable_htmlentities($row['product_name']);
                        $product_description = nullable_htmlentities($row['product_description']);
                        if (empty($product_description)) {
                            $product_description_display = "-";
                        } else {
                            $product_description_display = "<div style='white-space:pre-line'>$product_description</div>";
                        }
                        $product_price = floatval($row['product_price']);
                        $product_currency_code = nullable_htmlentities($row['product_currency_code']);
                        $product_created_at = nullable_htmlentities($row['product_created_at']);
                        $category_id = intval($row['category_id']);
                        $category_name = nullable_htmlentities($row['category_name']);
                        $product_tax_id = intval($row['product_tax_id']);
                        $product_cost = floatval($row['product_cost']);
                        $tax_name = nullable_htmlentities($row['tax_name']);
                        if (empty($tax_name)) {
                            $tax_name_display = "-";
                        } else {
                            $tax_name_display = $tax_name;
                        }
                        $tax_percent = floatval($row['tax_percent']);


                        ?>
                        <tr>
                            <th><a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#editProductModal<?= $product_id; ?>"><?= $product_name; ?></a></th>
                            <td><?= $category_name; ?></td>
                            <td><?= $product_description_display; ?></td>
                            <td><?= $tax_name_display; ?></td>
                            <td><?= $tax_percent; ?>%</td>
                            <td class="text-right"><?= numfmt_format_currency($currency_format, $product_price, $product_currency_code); ?></td>
                            <td class="text-right">
                                <?php
                                if ($product_price != 0) {
                                    $margin = (($product_price - $product_cost) / $product_price) * 100;
                                } else {
                                    $margin = 0;
                                }
                                if ($row['product_cost'] != null) {
                                    if ($margin < $margin_goal) {
                                        echo "<span class='text-danger'>";
                                    } else {
                                        echo "<span class='text-success'>";
                                    }
                                    echo number_format($margin, 0) . "%";
                                } else {
                                    echo '-';
                                }
                                echo "</span>";
                                ?>
                            </td>
                            
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item loadModalContentBtn" href="#" data-bs-toggle="modal" data-bs-target="dynamicModal" data-modal-file="product_edit_modal.php?product_id=<?=$product_id?>" ?>
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_product=<?=$product_id; ?>">
                                            <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                        </a>
                                        <?php if ($config_destructive_deletes_enable) { ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_product=<?=$product_id; ?>">
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
            </div>
        </div>
    </div>

<?php

require_once "/var/www/portal.twe.tech/includes/footer.php";
