<?php

$config = require '../../../config/autoload/local.php';

$pdo = new PDO(
    $config['db_departamentos']['dsn'],
    $config['db_departamentos']['username'],
    $config['db_departamentos']['password']
);
$pdo->exec('SET NAMES utf8mb4');

$stmt = $pdo->query("SELECT slug, documentos_requeridos FROM tramites WHERE departamento_id = 8 AND slug = 'becaindigena'");
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo "Slug: " . $result['slug'] . "\n\n";
    echo "Documentos actuales en BD:\n";
    echo "==================\n";
    echo $result['documentos_requeridos'] . "\n";
    echo "==================\n\n";
    echo "Bytes: " . strlen($result['documentos_requeridos']) . "\n";
}
