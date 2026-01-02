<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT id, nombre, slug FROM tramites WHERE nombre LIKE :term OR slug LIKE :term");
$term = '%discapacidad%';
$stmt->execute(['term' => $term]);
$tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($tramites) . " tramites related to 'discapacidad':\n";
foreach ($tramites as $t) {
    echo "ID: {$t['id']} | Nombre: {$t['nombre']} | Slug: {$t['slug']}\n";
}
