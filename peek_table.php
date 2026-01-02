<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$stmt = $pdo->query("SELECT * FROM usuarios_sistema LIMIT 1");
print_r($stmt->fetch(PDO::FETCH_ASSOC));
