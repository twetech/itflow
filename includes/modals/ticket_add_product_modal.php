<?php require_once "/var/www/nestogy.io/includes/inc_all_modal.php"; ?>

<?php
$ticket_id = intval($_GET['ticket_id']);

$sql_ticket_select = mysqli_query($mysqli,
    "SELECT * FROM tickets
    LEFT JOIN clients ON client_id = ticket_client_id
    WHERE ticket_id = $ticket_id");

$row = mysqli_fetch_array($sql_ticket_select);
$ticket_id = intval($row['ticket_id']);
$ticket_number = intval($row['ticket_number']);
$ticket_prefix = nullable_htmlentities($row['ticket_prefix']);
$ticket_onsite = intval($row['ticket_onsite']);
$ticket_client_id = intval($row['ticket_client_id']);
$client_id = intval($row['client_id']);
$client_name = nullable_htmlentities($row['client_name']);
?>


            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-clone mr-2"></i>Add Products</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="/post.php" method="post" autocomplete="off">
                <div class="modal-body bg-white">
                    <div class="form-row">
                    <input type="hidden" id="current_ticket_id" name="ticket_id" value="<?php echo $ticket_id; ?>">
                        <div class="form-group">
                            <label for="product_id">Product</label>
                            <select class="form-control select2" id='select2' id="product_id" name="product_id" required>
                                <option value="" selected disabled>Select a product</option>
                                <?php
                                $products = mysqli_query($mysqli, "SELECT * FROM products
                                    LEFT JOIN inventory ON products.product_id = inventory.inventory_product_id
                                    LEFT JOIN inventory_locations ON inventory.inventory_location_id = inventory_locations.inventory_location_id
                                    WHERE inventory_locations.inventory_location_user_id = $user_id
                                    AND (inventory.inventory_client_id = $client_id OR inventory.inventory_client_id = 0)
                                    GROUP BY products.product_id");
                                while ($product = mysqli_fetch_array($products)) {
                                    echo "<option value=\"$product[product_id]\">$product[product_name]</option>";
                                }
                                ?>
                            </select>
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="submit" id="add_ticket_products_btn" name="add_ticket_products" class="btn btn-label-primary text-bold"><i class="fa fa-plus mr-2"></i>Add</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"></i>Cancel</button>
                    <!-- Merge button starts disabled. Is enabled by the merge_into_number_get_details Javascript function-->
                </div>
            </form>

