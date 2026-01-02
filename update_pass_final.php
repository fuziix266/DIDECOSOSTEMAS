<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$email = 'admin@municipalidadarica.cl';
$newPass = 'admin123';
$hash = password_hash($newPass, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE usuarios_sistema SET password = :hash WHERE email = :email");
$stmt->execute(['hash' => $hash, 'email' => $email]);

if ($stmt->rowCount() > 0) {
    echo "Password updated successfully for $email.\n";
} else {
    echo "No rows updated. User might not exist or password matches existing.\n";
}
