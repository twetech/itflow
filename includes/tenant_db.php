<?php 
$database = 'itflow';

if (isset($_GET['tenant_id'])) {
    $database = $_GET['tenant_id'];
    if ($database == 'twe') {
        $database = 'itflow';
    }
}
