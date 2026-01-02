<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');

echo "--- Table: usuarios_sistema ---\n";
try {
    $stmt = $pdo->query("SELECT id, correo FROM usuarios_sistema");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- Table: usuarios ---\n";
try {
    $stmt = $pdo->query("SELECT id, correo FROM usuarios");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
