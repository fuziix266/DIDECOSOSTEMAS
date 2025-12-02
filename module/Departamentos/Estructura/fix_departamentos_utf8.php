<?php
/**
 * Script para corregir la codificación UTF-8 de todos los departamentos
 */

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'"
]);

$departamentos = [
    [
        'id' => 9,
        'nombre' => 'Oficina Afrodescendiente',
        'descripcion' => 'Apoyo, visibilización e impulso de los derechos, actividades y reconocimiento del pueblo tribal afrodescendiente chileno.'
    ]
];

$sql = "UPDATE departamentos SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
$stmt = $pdo->prepare($sql);

foreach ($departamentos as $depto) {
    $stmt->execute($depto);
    echo "✓ Actualizado: {$depto['nombre']}\n";
}

echo "\n¡Actualización completada con éxito!\n";
