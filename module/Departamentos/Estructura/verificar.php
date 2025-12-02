<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '', [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"
]);

$stmt = $pdo->query('SELECT nombre, descripcion_corta FROM tramites WHERE departamento_id = 9 ORDER BY orden');

echo "Verificación de datos:\n";
echo str_repeat('-', 80) . "\n";

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['nombre'] . "\n";
    echo "  " . $row['descripcion_corta'] . "\n\n";
}
