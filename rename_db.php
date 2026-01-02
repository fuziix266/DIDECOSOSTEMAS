<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Assuming empty password based on previous steps
$oldDb = 'qr_vehiculos_municipal';
$newDb = 'vehiculos_app';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if old DB exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$oldDb'");
    if (!$stmt->fetch()) {
        die("Error: Source database '$oldDb' does not exist.\n");
    }

    // Check if new DB exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$newDb'");
    if ($stmt->fetch()) {
        echo "Target database '$newDb' already exists.\n";
        // Check if old DB is empty or if we should just drop it? 
        // For safety, let's just warn and stop, or proceed if user wants merge (not implementing merge here).
        // If the user already created it, maybe we just need to move tables?
    } else {
        echo "Creating database '$newDb'...\n";
        $pdo->exec("CREATE DATABASE `$newDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    // Get tables from old DB
    $stmt = $pdo->query("SHOW TABLES FROM `$oldDb`");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "Source database '$oldDb' is empty.\n";
    } else {
        echo "Moving " . count($tables) . " tables from '$oldDb' to '$newDb'...\n";
        foreach ($tables as $table) {
            echo "Moving $table... ";
            // RENAME TABLE old.tbl TO new.tbl
            $pdo->exec("RENAME TABLE `$oldDb`.`$table` TO `$newDb`.`$table`");
            echo "Done.\n";
        }
    }

    // Drop old DB
    echo "Dropping database '$oldDb'...\n";
    $pdo->exec("DROP DATABASE `$oldDb`");
    echo "Success! Database renamed.\n";
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage() . "\n");
}
