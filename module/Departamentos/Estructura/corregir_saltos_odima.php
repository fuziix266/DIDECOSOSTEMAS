<?php
/**
 * Corregir datos de ODIMA con saltos de línea correctos
 */

$config = require '../../../config/autoload/local.php';

try {
    $pdo = new PDO(
        $config['db_departamentos']['dsn'],
        $config['db_departamentos']['username'],
        $config['db_departamentos']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");

    $departamentoId = 8;
    
    // Datos corregidos con saltos de línea
    $tramites = [
        'becaindigena' => [
            'documentos' => "• Acreditación Indígena de la persona solicitante\n• Certificado de notas",
            'requisitos' => '',
            'instrucciones' => ''
        ],
        'acreditacionindigena' => [
            'documentos' => "• Certificado de Nacimiento\n• Documentación que acredite ascendencia indígena",
            'requisitos' => '',
            'instrucciones' => ''
        ]
    ];

    $stmt = $pdo->prepare("
        UPDATE tramites 
        SET documentos_requeridos = ?
        WHERE departamento_id = ? AND slug = ?
    ");

    foreach ($tramites as $slug => $data) {
        $stmt->execute([$data['documentos'], $departamentoId, $slug]);
        echo "✅ Actualizado: $slug\n";
        echo "   Documentos: " . str_replace("\n", " | ", $data['documentos']) . "\n\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
