<?php

require_once "/var/www/portal.twe.tech/includes/inc_all_reports.php";
validateAccountantRole();

//generate array 

?>

<!-- Apex Charts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<script>

</script>

<div class="card">
    <div class="card-header py-2">
        <h3 class="card-title mt-2"><i class="fas fa-fw fa-life-ring mr-2"></i>Cash Flow Report</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-label-primary d-print-none" onclick="window.print();"><i class="fas fa-fw fa-print mr-2"></i>Print</button>
        </div>
    </div>
    <div class="card-body">

        <canvas id="cashFlow"></canvas>

        <div class="card-datatable table-responsive container-fluid  pt-0">
            <table id=responsive class="responsive table table-striped">
                <thead>
                <tr>
                    <th>Category</th>
                    