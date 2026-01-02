<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
// Fetch title or description from Dept 11
$stmt = $pdo->query("SELECT descripcion FROM departamentos WHERE id = 11");
$desc = $stmt->fetchColumn();

echo "String: " . $desc . "\n";
echo "Hex: " . bin2hex($desc) . "\n";
