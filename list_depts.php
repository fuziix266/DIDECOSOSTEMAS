<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$stmt = $pdo->query("SELECT id, nombre FROM departamentos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
