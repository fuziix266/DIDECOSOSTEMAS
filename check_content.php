<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
// Fetch a sample Discapacidad tramite
$stmt = $pdo->query("SELECT id, nombre, documentos_requeridos, requisitos_usuario FROM tramites WHERE departamento_id = 11 LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo "--- Discapacidad Sample ---\n";
print_r($row);

// Fetch a sample from another department (e.g., 1) to compare
$stmt2 = $pdo->query("SELECT id, nombre, documentos_requeridos, requisitos_usuario FROM tramites WHERE departamento_id = 1 AND documentos_requeridos IS NOT NULL LIMIT 1");
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

echo "\n--- Other Dept Sample ---\n";
print_r($row2);
