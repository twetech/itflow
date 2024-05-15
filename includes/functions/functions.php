<?php

// Role check failed wording
DEFINE("WORDING_ROLECHECK_FAILED", "You are not permitted to do that!");

//Function used to get rest of functions, can also be used for other folders.
function requireOnceAll($functionsPath) {
    foreach (glob($functionsPath . '/*.php') as $file) {
        // skip files in array
        $skip = [
            'index.php',
            'functions.php'
        ];

        if (in_array(basename($file), $skip)) {
            continue;
        }
        require_once $file;
    }
}

// Load all functions -----------------------------------------
// Other functions are categorized in different files

$functionsPath = "/var/www/nestogy.io/includes/functions";

// Require Once All in the functions folder
requireOnceAll($functionsPath);
