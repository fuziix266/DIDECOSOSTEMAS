<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$email = 'admin@municipalidadarica.cl';
$stmt = $pdo->prepare("SELECT password FROM usuarios_sistema WHERE email = :email");
$stmt->execute(['email' => $email]);
$pass = $stmt->fetchColumn();

if ($pass) {
    echo "Current Hash: $pass\n";
    echo "Length: " . strlen($pass) . "\n";
} else {
    echo "User not found.\n";
}
