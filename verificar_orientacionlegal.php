<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$stmt = $pdo->prepare('SELECT id, nombre, slug FROM tramites WHERE departamento_id = 1 AND slug = ?');
$stmt->execute(['orientacionlegal']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo "ENCONTRADO:\n";
    echo "ID: " . $row['id'] . "\n";
    echo "Nombre: " . $row['nombre'] . "\n";
    echo "Slug: " . $row['slug'] . "\n";
} else {
    echo "NO ENCONTRADO\n";
    echo "\nBuscando variaciones del slug...\n";
    $stmt2 = $pdo->prepare('SELECT id, nombre, slug FROM tramites WHERE departamento_id = 1 AND slug LIKE ?');
    $stmt2->execute(['%orientacion%']);
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row2['id'] . " | Slug: " . $row2['slug'] . " | Nombre: " . $row2['nombre'] . "\n";
    }
}
