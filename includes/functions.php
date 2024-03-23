<?php

// Role check failed wording
DEFINE("WORDING_ROLECHECK_FAILED", "You are not permitted to do that!");

//Function used to get rest of functions, can also be used for other folders.
function requireOnceAll($functionsPath) {
    foreach (glob($functionsPath . '/*.php') as $file) {
        // skip index.php
        if (strpos($file, 'index.php') !== false) {
            continue;
        }
        require_once $file;
    }
}

// Load all functions -----------------------------------------
// Other functions are categorized in different files

$functionsPath = "/var/www/develop.twe.tech/includes/functions";

// Require Once All in the functions folder
requireOnceAll($functionsPath);
