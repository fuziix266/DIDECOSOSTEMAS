<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=radio_app', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('INSERT INTO users (nombre, correo, password_hash, rol) VALUES (?, ?, ?, ?)');
    // Hash for 'admin123'
    $hash = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt->execute(['Admin Radio', 'admin.radio@municipalidadarica.cl', $hash, 'ADMIN']);
    echo "User inserted successfully with password 'admin123'\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
