<?php

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');

// Verificar UTF-8 en departamento
$stmt = $pdo->query("SELECT id, nombre, descripcion FROM departamentos WHERE id = 15");
$dept = $stmt->fetch(PDO::FETCH_ASSOC);

echo "DEPARTAMENTO (ID 15):\n";
echo str_repeat("=", 80) . "\n";
echo "Nombre: " . $dept['nombre'] . "\n";
echo "Descripción: " . ($dept['descripcion'] ?: '(vacío)') . "\n\n";

// Verificar UTF-8 en trámites
$stmt = $pdo->query("
    SELECT id, slug, nombre, descripcion_corta 
    FROM tramites 
    WHERE departamento_id = 15 
    ORDER BY id
");

echo "TRÁMITES:\n";
echo str_repeat("=", 80) . "\n";
$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $count++;
    echo $count . ". " . $row['nombre'] . "\n";
    echo "   Slug: " . $row['slug'] . "\n";
    if (!empty($row['descripcion_corta'])) {
        echo "   Desc: " . substr($row['descripcion_corta'], 0, 70) . "...\n";
    }
    echo "\n";
}

echo "\nTotal: $count trámites\n";
