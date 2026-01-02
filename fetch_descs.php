<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$stmt = $pdo->query("SELECT id, nombre, descripcion_larga FROM tramites WHERE departamento_id = 11");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    echo "ID: {$row['id']}\nNOMBRE: {$row['nombre']}\nDESC: " . substr(strip_tags($row['descripcion_larga']), 0, 300) . "...\n\n";
}
