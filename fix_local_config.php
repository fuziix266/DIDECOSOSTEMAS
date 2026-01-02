<?php
$configFile = 'config/autoload/local.php';

if (!file_exists($configFile)) {
    die("local.php not found.\n");
}

$config = include $configFile;

// Update Radio DB
if (isset($config['db_radio'])) {
    echo "Updating db_radio...\n";
    $config['db_radio']['dsn'] = 'mysql:dbname=radio_app;host=localhost;charset=utf8';
    $config['db_radio']['username'] = 'root';
    $config['db_radio']['password'] = '';
} else {
    echo "db_radio not found in local.php (will use global).\n";
}

// Update Vehiculos DB (just in case)
if (isset($config['db_vehiculos'])) {
    echo "Updating db_vehiculos...\n";
    $config['db_vehiculos']['dsn'] = 'mysql:dbname=vehiculos_app;host=localhost;charset=utf8';
    $config['db_vehiculos']['username'] = 'root';
    $config['db_vehiculos']['password'] = '';
} else {
    echo "db_vehiculos not found in local.php (will use global).\n";
}

// Write back
$content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
file_put_contents($configFile, $content);
echo "local.php updated.\n";
