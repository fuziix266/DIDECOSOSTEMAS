<?php
$global = include 'config/autoload/global.php';
$local = file_exists('config/autoload/local.php') ? include 'config/autoload/local.php' : [];

echo "--- Global db_radio ---\n";
print_r($global['db_radio'] ?? 'NOT SET');

echo "\n--- Local db_radio ---\n";
print_r($local['db_radio'] ?? 'NOT SET');

echo "\n--- Local Full Check ---\n";
// Check if local has db_radio defined even if null
if (array_key_exists('db_radio', $local)) {
    echo "db_radio IS defined in local.php\n";
} else {
    echo "db_radio is NOT defined in local.php\n";
}
