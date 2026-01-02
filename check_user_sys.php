<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
echo "--- Columns ---\n";
$stmt = $pdo->query("DESCRIBE usuarios_sistema");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}

echo "\n--- Sample Data (admin) ---\n";
$stmt = $pdo->query("SELECT * FROM usuarios_sistema WHERE usuario LIKE '%admin%' OR correo LIKE '%admin%' LIMIT 1");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    print_r($row);
} else {
    echo "No admin user found via 'usuario' or 'correo' columns (if they exist).\n";
}
