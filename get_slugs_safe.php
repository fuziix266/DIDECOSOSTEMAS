<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$stmt = $pdo->query("SELECT slug, nombre FROM tramites WHERE departamento_id = 11");
$fp = fopen('slugs.txt', 'w');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fwrite($fp, "SLUG:{$row['slug']}|NOMBRE:{$row['nombre']}\n");
}
fclose($fp);
echo "Written to slugs.txt\n";
