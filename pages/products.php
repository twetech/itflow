<?php

// Default Column Sortby/Order Filter
$sort = "product_name";
$order = "ASC";

require_once "/var/www/develop.twe.tech/includes/inc_all.php";


//Rebuild URL
$url_query_strings_sort = http_build_query(array_merge($_GET, array('sort' => $sort, 'o' => $order)));

$sql = mysqli_query(
    $mysqli,
    "SELECT SQL_CALC_FOUND_ROWS * FROM products
    LEFT JOIN categories ON product_category_id = category_id
    LEFT JOIN taxes ON product_tax_id = tax_id
    WHERE product_archived_at IS NULL
    AND (product_name LIKE '%$q%' OR product_description LIKE '%$q%' OR category_name LIKE '%$q%' OR product_price LIKE '%$q%' OR tax_name LIKE '%$q%' OR tax_percent LIKE '%$q%')
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
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=product_name&order=<?php echo $disp; ?>">Name</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=category_name&order=<?php echo $disp; ?>">Category</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=product_description&order=<?php echo $disp; ?>">Description</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=tax_name&order=<?php echo $disp; ?>">Tax Name</a></th>
                        <th><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=tax_percent&order=<?php echo $disp; ?>">Tax Rate</a></th>
                        <th class="text-right"><a class="text-dark" href="?<?php echo $url_query_strings_sort; ?>&sort=product_price&order=<?php echo $disp; ?>">Price</a></th>
                        
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
                        $tax_name = nullable_htmlentities($row['tax_name']);
                        if (empty($tax_name)) {
                            $tax_name_display = "-";
                        } else {
                            $tax_name_display = $tax_name;
                        }
                        $tax_percent = floatval($row['tax_percent']);


                        ?>
                        <tr>
                            <th><a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product_id; ?>"><?php echo $product_name; ?></a></th>
                            <td><?php echo $category_name; ?></td>
                            <td><?php echo $product_description_display; ?></td>
                            <td><?php echo $tax_name_display; ?></td>
                            <td><?php echo $tax_percent; ?>%</td>
                            <td class="text-right"><?php echo numfmt_format_currency($currency_format, $product_price, $product_currency_code); ?></td>
                            
                            <td>
                                <div class="dropdown dropleft text-center">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product_id; ?>">
                                            <i class="fas fa-fw fa-edit mr-2"></i>Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger confirm-link" href="/post.php?archive_product=<?php echo $product_id; ?>">
                                            <i class="fas fa-fw fa-archive mr-2"></i>Archive
                                        </a>
                                        <?php if ($config_destructive_deletes_enable) { ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger text-bold confirm-link" href="/post.php?delete_product=<?php echo $product_id; ?>">
                                             <i class="fas fa-fw fa-trash mr-2"></i>Delete
                                         </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <?php

                        require "/var/www/develop.twe.tech/includes/modals/product_edit_modal.php";


                    }

                    ?>

                    </tbody>
                </table>
            </div>
            <?php 
 ?>
        </div>
    </div>

<?php

require_once "/var/www/develop.twe.tech/includes/modals/product_add_modal.php";

require_once "/var/www/develop.twe.tech/includes/footer.php";
