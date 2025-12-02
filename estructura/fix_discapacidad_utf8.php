<?php

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "CORRECCIÓN UTF-8 - DISCAPACIDAD\n";
echo str_repeat("=", 80) . "\n\n";

$correcciones = 0;

try {
    // Correcciones de trámites
    $tramites_fix = [
        81 => [
            'descripcion_corta' => 'Atención y talleres de terapia ocupacional para personas en situación de discapacidad.'
        ],
        82 => [
            'nombre' => 'Terapia Kinésica',
            'descripcion_corta' => 'Terapias kinésicas personalizadas en sala de rehabilitación integral.'
        ],
        83 => [
            'descripcion_corta' => 'Terapias fonoaudiológicas individuales y grupales para mejorar la comunicación.'
        ],
        84 => [
            'descripcion_corta' => 'Actividades físicas adaptadas a distintos tipos de discapacidad.'
        ],
        85 => [
            'nombre' => 'Préstamo de Ayuda Técnica',
            'descripcion_corta' => 'Sillas de ruedas, bastones y otras ayudas técnicas en modalidad de préstamo.'
        ],
        86 => [
            'nombre' => 'Devolución de Ayuda Técnica',
            'descripcion_corta' => 'Proceso formal para devolver ayudas técnicas previamente entregadas.'
        ],
        87 => [
            'descripcion_corta' => 'Solicitud de gift card mediante evaluación social y requisitos específicos.'
        ],
        88 => [
            'descripcion_corta' => 'Trámite para obtener la credencial de discapacidad con respaldo profesional.'
        ],
        89 => [
            'descripcion_corta' => 'Gestión de ayuda social a través de informes dirigidos a entidades específicas.'
        ],
        90 => [
            'descripcion_corta' => 'Informe profesional para obtención de ayudas técnicas según necesidad.'
        ],
        92 => [
            'nombre' => 'Informe para Compras de Ayudas Técnicas'
        ],
        93 => [
            'descripcion_corta' => 'Orientación sobre beneficio monetario a menores con discapacidad mental.'
        ],
        94 => [
            'nombre' => 'Orientación sobre Estipendio'
        ],
        95 => [
            'nombre' => 'Pensión Básica Solidaria de Invalidez',
            'descripcion_corta' => 'Orientación para solicitar PBSI a personas declaradas inválidas parciales.'
        ],
        96 => [
            'descripcion_corta' => 'Llamados especiales para subsidios de arriendo y otros beneficios focalizados.'
        ],
        97 => [
            'nombre' => 'Solicitud IVADEC',
            'descripcion_corta' => 'Aplicación de batería de preguntas para acreditación del grado de discapacidad.'
        ],
        98 => [
            'nombre' => 'Postulación a Ayudas Técnicas SENADIS',
            'descripcion_corta' => 'Gestión de postulaciones a SENADIS durante períodos oficiales.'
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
