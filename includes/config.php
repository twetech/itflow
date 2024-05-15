<?php

$dbhost = 'db-mysql-nyc3-48108-do-user-14390994-0.c.db.ondigitalocean.com';
$dbusername = 'nestogy';
$dbpassword = 'AVNS_32N_d_ifVH8ay0NaIit';
$database = 'nestogy';
$port = 25060;
$mysqli = mysqli_connect($dbhost, $dbusername, $dbpassword, $database, $port) or die('Database Connection Failed');
$config_app_name = 'Nestogy';
$config_base_url = 'nestogy.io/';
$config_https_only = TRUE;
$repo_branch = 'master';
$installation_id = 'dHX200dU2LhLAykV1PhZKAIkNlPZaauE';
$config_enable_setup = 0;
$exchange_rate_api_key = '37d8cc6e789f543335922e26';
$exchange_rate_api_url = 'https://v6.exchangerate-api.com/v6/';