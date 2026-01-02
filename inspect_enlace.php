<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
// Fetch Enlace Norte (ID 1)
$stmt = $pdo->query("SELECT descripcion FROM departamentos WHERE id = 1");
$desc = $stmt->fetchColumn();

echo "Enlace Norte Hex: " . bin2hex($desc) . "\n";
