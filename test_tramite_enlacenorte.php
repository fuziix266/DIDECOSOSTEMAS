<?php
require 'vendor/autoload.php';

$config = require 'config/autoload/local.php';
// Usar la base de datos de departamentos
$adapter = new Laminas\Db\Adapter\Adapter($config['db_departamentos']);
$sql = new Laminas\Db\Sql\Sql($adapter);

// Verificar trámite subsidiofamiliar
$select = $sql->select('tramites');
$select->where(['slug' => 'subsidiofamiliar', 'departamento_id' => 1]);

$stmt = $sql->prepareStatementForSqlObject($select);
$result = $stmt->execute()->current();

echo "=== DATOS DEL TRÁMITE SUBSIDIOFAMILIAR ===\n\n";
if ($result) {
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "\n\n";

    // Listar campos vacíos
    echo "=== CAMPOS VACÍOS ===\n";
    foreach ($result as $key => $value) {
        if (empty($value) && $value !== '0' && $value !== 0) {
            echo "- $key\n";
        }
    }
} else {
    echo "No se encontró el trámite\n";
}

