<?php

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');

// Verificar departamento
$stmt = $pdo->query("SELECT id, slug, nombre FROM departamentos WHERE id = 1");
$dept = $stmt->fetch(PDO::FETCH_ASSOC);

echo "DEPARTAMENTO: " . $dept['nombre'] . " (ID: " . $dept['id'] . ")\n";
echo str_repeat("=", 80) . "\n\n";

// Verificar trámites
$stmt = $pdo->query("
    SELECT id, slug, nombre, descripcion_corta 
    FROM tramites 
    WHERE departamento_id = " . $dept['id'] . "
    ORDER BY orden, id
");

echo "TRÁMITES:\n";
$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $count++;
    echo "$count. ID:{$row['id']} | {$row['slug']}\n";
    echo "   Nombre: {$row['nombre']}\n";
    if (!empty($row['descripcion_corta'])) {
        echo "   Desc: " . substr($row['descripcion_corta'], 0, 70) . "...\n";
    }
    echo "\n";
}

echo "Total: $count trámites\n";
