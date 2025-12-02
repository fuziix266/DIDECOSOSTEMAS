<?php
require 'vendor/autoload.php';

$config = require 'config/autoload/local.php';
$adapter = new Laminas\Db\Adapter\Adapter($config['db_departamentos']);
$sql = new Laminas\Db\Sql\Sql($adapter);

// Verificar trámite de afrodescendientes
$select = $sql->select('tramites');
$select->where(['departamento_id' => 9]); // Afrodescendientes
$select->limit(1);

$stmt = $sql->prepareStatementForSqlObject($select);
$result = $stmt->execute()->current();

echo "=== DATOS DE UN TRÁMITE DE AFRODESCENDIENTES ===\n\n";
if ($result) {
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo "No se encontró ningún trámite\n";
}
