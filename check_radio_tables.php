<?php
try {
    // Try to connect to radio_app directly. 
    // Assuming 'root' and empty password for localhost as typical in this setup
    $pdo = new PDO('mysql:host=localhost;dbname=radio_app;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to radio_app\n";
    echo "--- Tables ---\n";
    $stmt = $pdo->query("SHOW TABLES");
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $table) {
        echo $table . "\n";
    }

    // Check users table columns if it exists
    echo "\n--- users Columns ---\n";
    try {
        $stmt = $pdo->query("DESCRIBE users");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
            echo $col['Field'] . "\n";
        }
    } catch (PDOException $e) {
        echo "DESCRIBE users failed: " . $e->getMessage() . "\n";
    }
} catch (PDOException $e) {
    echo "Connection to radio_app failed: " . $e->getMessage();
}
