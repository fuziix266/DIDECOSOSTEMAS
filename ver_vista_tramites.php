<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
echo "Columnas de v_tramites_completos:\n";
echo str_repeat("=", 60) . "\n";
$cols = $pdo->query('SHOW COLUMNS FROM v_tramites_completos')->fetchAll(PDO::FETCH_ASSOC);
foreach($cols as $col) {
    echo $col['Field'] . "\n";
}

echo "\n\nPrimera fila de ejemplo:\n";
echo str_repeat("=", 60) . "\n";
$row = $pdo->query('SELECT * FROM v_tramites_completos LIMIT 1')->fetch(PDO::FETCH_ASSOC);
if ($row) {
    foreach($row as $key => $value) {
        echo "$key: " . substr($value, 0, 50) . "\n";
    }
}
