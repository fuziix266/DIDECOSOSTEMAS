<?php

$config = require '../../../config/autoload/local.php';

$pdo = new PDO(
    $config['db_departamentos']['dsn'],
    $config['db_departamentos']['username'],
    $config['db_departamentos']['password']
);
$pdo->exec('SET NAMES utf8mb4');

$stmt = $pdo->query("SELECT id, nombre FROM departamentos ORDER BY id");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . " - " . $row['nombre'] . "\n";
}
