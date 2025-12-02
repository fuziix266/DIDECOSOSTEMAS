<?php
/**
 * Script para extraer datos de Gestión Habitacional y actualizar BD
 */

require __DIR__ . '/../../../vendor/autoload.php';

$config = require __DIR__ . '/../../../config/autoload/local.php';
$adapter = new Laminas\Db\Adapter\Adapter($config['db_departamentos']);

$viewsPath = __DIR__ . '/../view/departamentos/gestionhabitacional/';
$files = glob($viewsPath . '*.phtml');

$tramitesData = [];

foreach ($files as $file) {
    $filename = basename($file, '.phtml');
    
    if (in_array($filename, ['index', '_tramite_detalle'])) {
        continue;
    }
    
    $content = file_get_contents($file);
    
    $data = [
        'slug' => $filename,
        'documentos_requeridos' => null,
        'requisitos_usuario' => null,
        'instrucciones_paso_paso' => null,
        'tiempo_estimado' => null,
        'responsable_nombre' => null,
        'observaciones' => null,
    ];
    
    // Extraer Documentos Requeridos
    if (preg_match('/Documentos Requeridos.*?<div class="accordion-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['documentos_requeridos'] = $text;
    }
    
    // Extraer Requisitos del Usuario
    if (preg_match('/Requisitos del Usuario.*?<div class="accordion-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['requisitos_usuario'] = $text;
    }
    
    // Extraer Instrucciones Paso a Paso
    if (preg_match('/Instrucciones Paso a Paso.*?<div class="accordion-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['instrucciones_paso_paso'] = $text;
    }
    
    // Extraer Tiempo Estimado
    if (preg_match('/Tiempo Estimado de Respuesta.*?<div class="accordion-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['tiempo_estimado'] = $text;
    }
    
    // Extraer Responsable
    if (preg_match('/Responsable del Trámite.*?<div class="accordion-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['responsable_nombre'] = $text;
    }
    
    // Extraer Observaciones
    if (preg_match('/Observaciones.*?<div class="accordion-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['observaciones'] = $text;
    }
    
    $tramitesData[] = $data;
}

echo "=== GESTIÓN HABITACIONAL - DATOS EXTRAÍDOS ===\n\n";
$totalConDatos = 0;

foreach ($tramitesData as $data) {
    $tieneDatos = !empty($data['documentos_requeridos']) || 
                   !empty($data['requisitos_usuario']) || 
                   !empty($data['instrucciones_paso_paso']) ||
                   !empty($data['tiempo_estimado']) ||
                   !empty($data['responsable_nombre']) ||
                   !empty($data['observaciones']);
    
    if ($tieneDatos) $totalConDatos++;
    
    echo "SLUG: {$data['slug']}\n";
    echo "  Documentos: " . (empty($data['documentos_requeridos']) ? 'NO' : 'SÍ') . "\n";
    echo "  Requisitos: " . (empty($data['requisitos_usuario']) ? 'NO' : 'SÍ') . "\n";
    echo "  Instrucciones: " . (empty($data['instrucciones_paso_paso']) ? 'NO' : 'SÍ') . "\n";
    echo "  Tiempo: " . (empty($data['tiempo_estimado']) ? 'NO' : 'SÍ') . "\n";
    echo "  Responsable: " . (empty($data['responsable_nombre']) ? 'NO' : 'SÍ') . "\n";
    echo "  Observaciones: " . (empty($data['observaciones']) ? 'NO' : 'SÍ') . "\n";
    echo str_repeat('-', 70) . "\n";
}

echo "\n📊 Total con datos: $totalConDatos de " . count($tramitesData) . "\n\n";

// Actualizar BD
$sql = new Laminas\Db\Sql\Sql($adapter);
$updated = 0;

foreach ($tramitesData as $data) {
    $tieneDatos = !empty($data['documentos_requeridos']) || 
                   !empty($data['requisitos_usuario']) || 
                   !empty($data['instrucciones_paso_paso']) ||
                   !empty($data['tiempo_estimado']) ||
                   !empty($data['responsable_nombre']) ||
                   !empty($data['observaciones']);
    
    if (!$tieneDatos) continue;
    
    $update = $sql->update('tramites');
    $update->set([
        'documentos_requeridos' => $data['documentos_requeridos'],
        'requisitos_usuario' => $data['requisitos_usuario'],
        'instrucciones_paso_paso' => $data['instrucciones_paso_paso'],
        'tiempo_estimado' => $data['tiempo_estimado'],
        'responsable_nombre' => $data['responsable_nombre'],
        'observaciones' => $data['observaciones'],
    ]);
    $update->where(['slug' => $data['slug'], 'departamento_id' => 12]);
    
    try {
        $stmt = $sql->prepareStatementForSqlObject($update);
        $result = $stmt->execute();
        $updated += $result->getAffectedRows();
        echo "✅ Actualizado: {$data['slug']}\n";
    } catch (Exception $e) {
        echo "❌ Error en {$data['slug']}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Trámites actualizados: $updated\n";
