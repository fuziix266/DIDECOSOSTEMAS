<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$stmt = $pdo->query("DESCRIBE usuarios_sistema");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
