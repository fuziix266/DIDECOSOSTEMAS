<?php
/**
 * Script para corregir problemas de codificación UTF-8 en trámites de Enlace Norte
 */

$host = 'localhost';
$dbname = 'dideco';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado a la base de datos correctamente.\n\n";
    
    // Array de correcciones para Enlace Norte (departamento_id = 1)
    $correcciones = [
        // ID 1 - SUF
        1 => [
            'nombre' => 'Subsidio Único Familiar (SUF)',
            'descripcion' => 'Beneficio económico mensual para familias en situación de vulnerabilidad.'
        ],
        // ID 2 - Subsidio Maternal
        2 => [
            'nombre' => 'Subsidio Maternal',
            'descripcion' => 'Apoyo económico para mujeres embarazadas que requieren atención prenatal y postnatal.'
        ],
        // ID 3 - Subsidio Recién Nacido
        3 => [
            'nombre' => 'Subsidio Recién Nacido',
            'descripcion' => 'Beneficio económico destinado a cubrir gastos asociados al cuidado del recién nacido.'
        ],
        // ID 4 - Subsidio Madre
        4 => [
            'nombre' => 'Subsidio Madre',
            'descripcion' => 'Apoyo económico mensual para madres en situación de vulnerabilidad.'
        ],
        // ID 5 - SUF Menores
        5 => [
            'nombre' => 'SUF Menores de 18 años',
            'descripcion' => 'Subsidio específico para familias con causantes menores de 18 años.'
        ],
        // ID 7 - Pensiones
        7 => [
            'nombre' => 'Pensiones',
            'descripcion' => 'Información general sobre trámites y beneficios relacionados con pensiones.'
        ],
        // ID 8 - PGU
        8 => [
            'nombre' => 'Pensión Garantizada Universal (PGU)',
            'descripcion' => 'Beneficio previsional universal para adultos mayores de 65 años que cumplan requisitos.'
        ],
        // ID 9 - Pensión Invalidez
        9 => [
            'nombre' => 'Pensión Básica Solidaria de Invalidez',
            'descripcion' => 'Pensión para personas entre 18 y 65 años con invalidez, que no tienen otra pensión.'
        ],
        // ID 10 - APSI
        10 => [
            'nombre' => 'Aporte Previsional Solidario de Invalidez (APSI)',
            'descripcion' => 'Complemento a la pensión de invalidez para personas que reciben pensión contributiva baja.'
        ],
        // ID 11 - Bono Por Hijo
        11 => [
            'nombre' => 'Bono Por Hijo',
            'descripcion' => 'Beneficio económico adicional por cada hijo para mujeres pensionadas.'
        ],
        // ID 12 - Subsidio Discapacidad
        12 => [
            'nombre' => 'Subsidio de Discapacidad (S.D.)',
            'descripcion' => 'Apoyo económico mensual para personas con discapacidad mental en situación de vulnerabilidad.'
        ],
        // ID 15 - SAP Urbano
        15 => [
            'nombre' => 'SAP Urbano',
            'descripcion' => 'Subsidio de Agua Potable para sectores urbanos con conexión domiciliaria.'
        ],
        // ID 16 - Comodato
        16 => [
            'nombre' => 'Comodato',
            'descripcion' => 'Asesoramiento para trámites de comodato y cesión de uso de bienes municipales.'
        ],
        // ID 17 - Juntas Vecinales
        17 => [
            'nombre' => 'Territorial Juntas Vecinales',
            'descripcion' => 'Apoyo y orientación para la formación, gestión y fortalecimiento de juntas vecinales.'
        ],
        // ID 19 - Emergencias
        19 => [
            'nombre' => 'Equipo de Logística y Emergencia',
            'descripcion' => 'Coordinación de apoyo logístico y respuesta ante situaciones de emergencia.'
        ],
        // ID 20 - Emergencias (antiguo)
        20 => [
            'nombre' => 'Equipo de Logística y Emergencia',
            'descripcion' => 'Coordinación de apoyo logístico y respuesta ante situaciones de emergencia.'
        ]
    ];
    
    $stmt = $pdo->prepare("
        UPDATE tramites 
        SET nombre = :nombre, 
            descripcion_corta = :descripcion 
        WHERE id = :id AND departamento_id = 1
    ");
    
    $corregidos = 0;
    
    foreach ($correcciones as $id => $datos) {
        $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion']
        ]);
        
        if ($stmt->rowCount() > 0) {
            echo "✓ Corregido trámite ID $id: {$datos['nombre']}\n";
            $corregidos++;
        }
    }
    
    echo "\n================================================================================\n";
    echo "RESUMEN: Se corrigieron $corregidos trámites de Enlace Norte\n";
    echo "================================================================================\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
