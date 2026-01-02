<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $term = '%discapacidad%';

    // Step 1: List before delete
    $stmt = $pdo->prepare("SELECT id, nombre FROM tramites WHERE nombre LIKE :term OR slug LIKE :term");
    $stmt->execute(['term' => $term]);
    $toDelete = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($toDelete) . " tramites to delete:\n";
    foreach ($toDelete as $t) {
        echo "- ID: {$t['id']}, Name: {$t['nombre']}\n";
    }

    if (count($toDelete) > 0) {
        // Step 2: Delete
        $stmt = $pdo->prepare("DELETE FROM tramites WHERE nombre LIKE :term OR slug LIKE :term");
        $stmt->execute(['term' => $term]);
        $count = $stmt->rowCount();
        echo "Successfully deleted $count records.\n";
    } else {
        echo "No records found to delete.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
