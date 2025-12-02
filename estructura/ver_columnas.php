<?php

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');

$stmt = $pdo->query("SELECT id, slug, nombre FROM tramites WHERE departamento_id = 15 ORDER BY id");

echo "Trámites del departamento 15 (Derechos Humanos):\n\n";

echo "ID | SLUG | NOMBRE\n";

echo "--------------------------------------------\n";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    echo $row['id'] . " | " . $row['slug'] . " | " . $row['nombre'] . "\n";

}
