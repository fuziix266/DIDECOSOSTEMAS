<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $deptId = 11;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tramites WHERE departamento_id = :id");
    $stmt->execute(['id' => $deptId]);
    $count = $stmt->fetchColumn();

    echo "Total tramites for Dept 11: $count\n";

    if ($count > 0) {
        $stmt = $pdo->prepare("SELECT * FROM tramites WHERE departamento_id = :id ORDER BY id DESC LIMIT 1");
        $stmt->execute(['id' => $deptId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Last inserted tramite:\n";
        print_r($row);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
