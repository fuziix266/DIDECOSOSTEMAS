<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$cols = $pdo->query('SHOW COLUMNS FROM tramites')->fetchAll(PDO::FETCH_ASSOC);
foreach($cols as $col) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}
