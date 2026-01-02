<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
// Fetch columns to check what valid icon format looks like
$stmt = $pdo->query("SELECT nombre, icono_bootstrap, icono_fontawesome FROM tramites WHERE icono_bootstrap IS NOT NULL OR icono_fontawesome IS NOT NULL LIMIT 5");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Existing Icons Sample:\n";
print_r($rows);
