<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->exec("UPDATE tramites SET nombre='Inscripción Emprendedora' WHERE slug='emprendimiento' AND departamento_id=9");
$pdo->exec("UPDATE tramites SET nombre='Inscripción a Talleres' WHERE slug='talleres' AND departamento_id=9");
echo "Nombres actualizados correctamente\n";
