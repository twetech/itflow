<?php 
require_once '/var/www/portal.twe.tech/includes/inc_all_admin.php';

$sql_tenants = "SELECT * FROM tenants
                LEFT JOIN clients ON tenants.tenant_client_id = clients.client_id
                WHERE tenant_status = 1";
$result_tenants = mysqli_query($mysqli, $sql_tenants);
$tenants_row = mysqli_fetch_assoc($result_tenants);

$sql_tenants_count = "SELECT COUNT(*) AS total_tenants FROM tenants WHERE tenant_status = 1";
$result_tenants_count = mysqli_query($mysqli, $sql_tenants_count);
$tenants_count_row = mysqli_fetch_assoc($result_tenants_count);


?>

<div class="card">
    <header class="card-header">
        <h2 class="card-title">Tenants</h2>
    </header>

    <div class="card-body">
        <div class="tab-content">
                    <div class="card-datatable table-responsive container-fluid  pt-0">               
                <table id=responsive class="responsive table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                    <label class="custom-control-label" for="selectAll"></label>
                                </div>
                            </th>
                            <th>Tenant Name</th>
                            <th>Tenant Database</th>
                            <th>Tenant Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($tenants_count_row['total_tenants'] > 0) {
                            do {
                        ?>
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="tenant<?= $tenants_row['tenant_id']; ?>">
                                            <label class="custom-control-label" for="tenant<?= $tenants_row['tenant_id']; ?>"></label>
                                        </div>
                                    </td>
                                    <td><?= $tenants_row['client_name']; ?></td>
                                    <td><?= $tenants_row['tenant_database']; ?></td>
                                    <td><?= $tenants_row['tenant_status']; ?></td>
                                    <td>
                                        <form method="post" action="post.php">
                                            <input type="hidden" name="tenant_id" value="<?= $tenants_row['tenant_id']; ?>">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editTenant<?= $tenants_row['tenant_id']; ?>" class="btn btn-label-primary btn-sm">Edit</a>
                                            <button type="submit" name="delete_tenant" class="btn btn-danger btn-sm">Disable</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php

                                // Edit Tenant Modal
                                require '/var/www/portal.twe.tech/includes/modals/tenant_edit_modal.php';

                            } while ($tenants_row = mysqli_fetch_assoc($result_tenants));
                        } else {
                        ?>
                            <tr>
                                <td colspan="4">No tenants found</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




<?php

require_once '/var/www/portal.twe.tech/includes/footer.php';