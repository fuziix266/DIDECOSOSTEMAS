<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
// Fetch a tramite description from Dept 11
$stmt = $pdo->query("SELECT descripcion_larga FROM tramites WHERE departamento_id = 11 LIMIT 1");
$desc = $stmt->fetchColumn();

echo "Tramite Desc Start: " . substr($desc, 0, 100) . "\n";
echo "Tramite Hex: " . bin2hex(substr($desc, 0, 100)) . "\n";
