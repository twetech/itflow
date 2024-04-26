
<?php require_once "/var/www/develop.twe.tech/includes/inc_all_modal.php"; ?>

<?php
$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;

if (isset($_GET['invoice_id'])) {
    $invoice_sql = "SELECT * FROM invoices WHERE invoice_id = $invoice_id";
    $invoice_result = mysqli_query($mysqli, $invoice_sql);
    $invoice_row = mysqli_fetch_assoc($invoice_result);

    $invoice_currency_code = $invoice_row['invoice_currency_code'];
} else {
    $invoice_currency_code = $company_currency_code;
}



//Product autocomplete
$products_sql = mysqli_query($mysqli, "SELECT product_name AS label, product_description AS description, product_price AS price, product_tax_id AS tax FROM products WHERE product_archived_at IS NULL");

if (mysqli_num_rows($products_sql) > 0) {
    while ($row = mysqli_fetch_array($products_sql)) {
        $products[] = $row;
    }
    $json_products = json_encode($products);
}
?>

<div class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-fw fa-piggy-bank mr-2"></i>Add Invoice Item</h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body bg-white">

            <input hidden name="invoice_id" value="<?=$invoice_id?>">
            <input hidden name="product_id" id="product_id" value="0">

            <div class="d-flex border rounded position-relative pe-0">
                <div class="row w-100 m-0 p-3">
                    <div class="row">
                        <div class="col-12 mb-md-2 mb-3 ps-md-0">
                            <p class="mb-2 repeater-title">Item</p>
                            <input type="text" class="form-control mb-2" id="name" name="name" placeholder="Item" required>
                            <textarea class="form-control mb-1" rows="2" id="desc" name="description" placeholder="Enter a Description"></textarea>
                        </div>
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-7 col-12 mb-md-0 mb-3 ps-md-0">
                            <p class="mb-2 repeater-title">Tax</p>
                            <select class="form-select invoice-item-tax-modal mb-2" name="tax_id" id="tax" style="width: 100%;" autocomplete="off">
                                <option name="noTax" value="0" data-rate="0">No Tax</option>
                                <?php
                                $tax_sql = "SELECT * FROM taxes";
                                $tax_result = mysqli_query($mysqli, $tax_sql);

                                while ($tax_row = mysqli_fetch_assoc($tax_result)) {
                                    $tax_id = $tax_row['tax_id'];
                                    $tax_name = $tax_row['tax_name'];
                                    $tax_rate = $tax_row['tax_percent'];
                                    ?>
                                    <option value="<?=$tax_id?>" data-rate="<?=$tax_rate?>"><?=$tax_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-5 col-12 pe-0 ps-md-0">
                            <p class="mb-2 repeater-title">Discount</p>
                            <input type="text" class="form-control invoice-item-discount mb-2" placeholder="0.00" name="discount" pattern="-?[0-9]*\.?[0-9]{0,2}[%]?" style="text-align: right;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-12 mb-md-0 mb-3 ps-md-0">
                            <p class="mb-2 repeater-title">Unit Price</p>
                            <input type="text" class="form-control" pattern="-?[0-9]*\.?[0-9]{0,2}" style="text-align: right;" id="price" name="price" placeholder="Price (<?php echo $invoice_currency_code; ?>)">
                        </div>
                        <div class="col-md-3 col-12 mb-md-0 mb-3 ps-md-0">
                            <p class="mb-2 repeater-title">Qty</p>
                            <input type="number" pattern="[0-9]*\.?[0-9]{0,2}" class="form-control" style="text-align: center;" id="qty" name="qty" placeholder="0">
                        </div>
                        <div class="col-md-5 col-12 pe-0 ps-md-0">
                            <p class="mb-2 repeater-title">Line Total</p>
                            <input type="text" class="form-control invoice-item-total" placeholder="0.00" disabled/>
                        </div>
                    </div>
                </div>
            </div>


            </div>
            <div class="modal-footer bg-white">
                <button type="submit" name="add_invoice_item" class="btn btn-label-primary text-bold">Add</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
