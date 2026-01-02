<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $deptId = 11;
    $stmt = $pdo->prepare("SELECT slug, nombre FROM tramites WHERE departamento_id = :id");
    $stmt->execute(['id' => $deptId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
