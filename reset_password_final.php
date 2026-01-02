<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$email = 'admin@municipalidadarica.cl';
$newPass = 'admin123';
$hash = password_hash($newPass, PASSWORD_DEFAULT);

$table = 'qr_usuarios';

try {
    $stmt = $pdo->prepare("SELECT id FROM `$table` WHERE correo = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        $update = $pdo->prepare("UPDATE `$table` SET password_hash = :hash WHERE correo = :email");
        $update->execute(['hash' => $hash, 'email' => $email]);
        echo "Updated password for $email in table '$table'.\n";
    } else {
        echo "User $email not found in '$table'.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
