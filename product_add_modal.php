<div class="modal" id="addProductModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-box mr-2"></i>New Product</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form action="post.php" method="post" autocomplete="off">
        <div class="modal-body bg-white">
          
          <div class="form-group">
            <label>Name <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-fw fa-box"></i></span>
              </div>
              <input type="text" class="form-control" name="name" placeholder="Product name" required autofocus>
            </div>
          </div>
          
          <div class="form-group">
            <label>Category <strong class="text-danger">*</strong></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-fw fa-tag"></i></span>
              </div>
              <select class="form-control select2" name="category" required>
                <option value="">- Select Category -</option>
                <?php 
                
                $sql = mysqli_query($mysqli,"SELECT * FROM categories WHERE category_type = 'Income' AND category_archived_at IS NULL AND company_id = $session_company_id"); 
                while($row = mysqli_fetch_array($sql)){
                  $category_id = $row['category_id'];
                  $category_name = htmlentities($row['category_name']);
                ?>
                  <option value="<?php echo $category_id; ?>"><?php echo $category_name; ?></option>
                
                <?php
                }
                ?>
              </select>
              <div class="input-group-append">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addQuickCategoryIncomeModal"><i class="fas fa-fw fa-plus"></i></button>
              </div>
            </div>
          </div>
          
          <div class="form-row">
            <div class="col">
              <div class="form-group">
                <label>Price <strong class="text-danger">*</strong></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-dollar-sign"></i></span>
                  </div>
                  <input type="number" step="0.01" min="0" class="form-control" name="price" placeholder="Price" required>
                </div>
              </div>
            </div>
            
            <div class="col">
              <div class="form-group">
                <label>Tax</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-fw fa-balance-scale"></i></span>
                  </div>
                  <select class="form-control select2" name="tax">
                    <option value="0">None</option>
                    <?php 
                    
                    $taxes_sql = mysqli_query($mysqli,"SELECT * FROM taxes WHERE tax_archived_at IS NULL AND company_id = $session_company_id ORDER BY tax_name ASC"); 
                    while($row = mysqli_fetch_array($taxes_sql)){
                      $tax_id = $row['tax_id'];
                      $tax_name = htmlentities($row['tax_name']);
                      $tax_percent = htmlentities($row['tax_percent']);
                    ?>
                      <option value="<?php echo $tax_id; ?>"><?php echo "$tax_name $tax_percent%"; ?></option>
                    
                    <?php
                    }
                    ?>
                  </select>        
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" rows="8" name="description" placeholder="Product description"></textarea>
          </div>
        
        </div>
        
        <div class="modal-footer bg-white">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="add_product" class="btn btn-primary"><storng><i class="fas fa-check"></i> Create</storng></button>
        </div>
      
      </form>
    </div>
  </div>
</div>