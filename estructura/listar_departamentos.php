<?php

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');

$stmt = $pdo->query("SELECT id, slug, nombre FROM departamentos ORDER BY id");

while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $r['id'] . " | " . $r['slug'] . " | " . $r['nombre'] . "\n";
}
