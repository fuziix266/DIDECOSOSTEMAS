<?php

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "CORRECCIÓN UTF-8 - DERECHOS HUMANOS\n";
echo str_repeat("=", 80) . "\n\n";

$correcciones = 0;

try {
    // Corregir departamento (ID 15)
    $stmt = $pdo->prepare("
        UPDATE departamentos 
        SET 
            descripcion = 'Trámites y servicios vinculados a la orientación, regularización migratoria, diversidad sexual y promoción de derechos humanos.'
        WHERE id = 15
    ");
    $stmt->execute();
    echo "✓ Departamento actualizado: Oficina Municipal de Derechos Humanos\n";
    $correcciones++;

    // Corregir trámites
    $tramites_fix = [
        113 => [
            'descripcion_corta' => 'Asesoría sobre vigencia de documentos y acceso a información en plataforma ChileAtiende.'
        ],
        114 => [
            'descripcion_corta' => 'Consulta y seguimiento a trámites de migrantes con residencia transitoria.'
        ],
        115 => [
            'descripcion_corta' => 'Orientación para obtención de residencia temporal.'
        ],
        117 => [
            'descripcion_corta' => 'Orientación para proceso de obtención de nacionalidad chilena.'
        ],
        119 => [
            'descripcion_corta' => 'Orientación a personas LGBTQIANB+ en situación de vulnerabilidad, violencia o discriminación.'
        ],
        120 => [
            'descripcion_corta' => 'Actividades de formación, entrega de kits y jornadas de conmemoración en diversidad sexual.'
        ]
    ];

    foreach ($tramites_fix as $id => $datos) {
        $set_clauses = [];
        $params = [];
        
        foreach ($datos as $campo => $valor) {
            $set_clauses[] = "$campo = :$campo";
            $params[":$campo"] = $valor;
        }
        
        $params[':id'] = $id;
        
        $sql = "UPDATE tramites SET " . implode(', ', $set_clauses) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Obtener nombre del trámite
        $stmt_nombre = $pdo->prepare("SELECT nombre FROM tramites WHERE id = :id");
        $stmt_nombre->execute([':id' => $id]);
        $nombre = $stmt_nombre->fetchColumn();
        
        echo "✓ Trámite $id actualizado: $nombre\n";
        $correcciones++;
    }
    
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "TOTAL: $correcciones registros corregidos\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
