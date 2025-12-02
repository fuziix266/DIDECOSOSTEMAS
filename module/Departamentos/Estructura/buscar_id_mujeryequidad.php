<?php

$config = require '../../../config/autoload/local.php';

$pdo = new PDO(
    $config['db_departamentos']['dsn'],
    $config['db_departamentos']['username'],
    $config['db_departamentos']['password']
);
$pdo->exec('SET NAMES utf8mb4');

$stmt = $pdo->query("SELECT id, nombre FROM departamentos WHERE nombre LIKE '%mujer%' OR nombre LIKE '%equidad%'");
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo "ID: " . $result['id'] . " - " . $result['nombre'] . "\n";
} else {
    echo "No encontrado\n";
}
