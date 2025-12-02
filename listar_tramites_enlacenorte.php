<?php
require 'vendor/autoload.php';

$config = require 'config/autoload/local.php';
$adapter = new Laminas\Db\Adapter\Adapter($config['db_departamentos']);
$sql = new Laminas\Db\Sql\Sql($adapter);

// Listar todos los trámites de Enlace Norte
$select = $sql->select('tramites');
$select->where(['departamento_id' => 1]);
$select->order('orden ASC');

$stmt = $sql->prepareStatementForSqlObject($select);
$results = $stmt->execute();

echo "=== TRÁMITES DE ENLACE NORTE (ID: 1) ===\n\n";

$count = 0;
foreach ($results as $tramite) {
    $count++;
    echo "[$count] {$tramite['nombre']} (slug: {$tramite['slug']})\n";
    echo "    Descripción corta: " . (empty($tramite['descripcion_corta']) ? 'VACÍO' : 'OK') . "\n";
    echo "    Descripción larga: " . (empty($tramite['descripcion_larga']) ? 'VACÍO' : 'OK') . "\n";
    echo "    Documentos requeridos: " . (empty($tramite['documentos_requeridos']) ? 'VACÍO' : 'OK') . "\n";
    echo "    Requisitos usuario: " . (empty($tramite['requisitos_usuario']) ? 'VACÍO' : 'OK') . "\n";
    echo "    Instrucciones: " . (empty($tramite['instrucciones_paso_paso']) ? 'VACÍO' : 'OK') . "\n";
    echo "    Activo: " . ($tramite['activo'] ? 'SÍ' : 'NO') . "\n";
    echo "\n";
}

echo "Total de trámites: $count\n";
