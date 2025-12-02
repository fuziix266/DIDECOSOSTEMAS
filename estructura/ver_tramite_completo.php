<?php

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');

// Ver datos completos de vigenciacedula
$stmt = $pdo->query("
    SELECT nombre, descripcion_corta, descripcion_larga, requisitos_usuario, 
           documentos_requeridos, instrucciones_paso_paso, tiempo_estimado,
           responsable_nombre, responsable_cargo, observaciones
    FROM tramites 
    WHERE slug = 'vigenciacedula' AND departamento_id = 15
");

$tramite = $stmt->fetch(PDO::FETCH_ASSOC);

if ($tramite) {
    echo "TRÁMITE: " . $tramite['nombre'] . "\n";
    echo str_repeat("=", 80) . "\n\n";
    
    foreach ($tramite as $campo => $valor) {
        if (!empty($valor) && !in_array($campo, ['id', 'departamento_id', 'slug', 'created_at', 'updated_at'])) {
            echo strtoupper($campo) . ":\n";
            echo $valor . "\n\n";
        }
    }
} else {
    echo "No se encontró el trámite\n";
}
