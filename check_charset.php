<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$stmt = $pdo->query("SHOW CREATE TABLE departamentos");
print_r($stmt->fetch(PDO::FETCH_ASSOC));
