<?php
/**
 * Script para extraer datos de las vistas .phtml de Enlace Norte
 * y cargarlos en la base de datos
 */

require __DIR__ . '/../../../vendor/autoload.php';

$config = require __DIR__ . '/../../../config/autoload/local.php';
$adapter = new Laminas\Db\Adapter\Adapter($config['db_departamentos']);

$viewsPath = __DIR__ . '/../view/departamentos/enlacenorte/';
$files = glob($viewsPath . '*.phtml');

$tramitesData = [];

foreach ($files as $file) {
    $filename = basename($file, '.phtml');
    
    // Saltar archivos especiales
    if (in_array($filename, ['index', '_tramite_detalle'])) {
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Inicializar datos
    $data = [
        'slug' => $filename,
        'documentos_requeridos' => null,
        'requisitos_usuario' => null,
        'instrucciones_paso_paso' => null,
        'tiempo_estimado' => null,
        'responsable_nombre' => null,
    ];
    
    // Extraer Documentos Requeridos
    if (preg_match('/Documentos Requeridos.*?<div class="card-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['documentos_requeridos'] = $text;
    }
    
    // Extraer Requisitos del Usuario
    if (preg_match('/Requisitos del Usuario.*?<div class="card-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['requisitos_usuario'] = $text;
    }
    
    // Extraer Instrucciones Paso a Paso
    if (preg_match('/Instrucciones Paso a Paso.*?<div class="card-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['instrucciones_paso_paso'] = $text;
    }
    
    // Extraer Tiempo Estimado de Respuesta
    if (preg_match('/Tiempo Estimado de Respuesta.*?<div class="card-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['tiempo_estimado'] = $text;
    }
    
    // Extraer Responsable del Trámite
    if (preg_match('/Responsable del Trámite.*?<div class="card-body">\s*(.*?)\s*<\/div>/s', $content, $matches)) {
        $text = $matches[1];
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text);
        $text = trim($text);
        $data['responsable_nombre'] = $text;
    }
    
    $tramitesData[] = $data;
}

// Mostrar resultados extraídos
echo "=== DATOS EXTRAÍDOS DE ENLACE NORTE ===\n\n";
$totalConDatos = 0;
foreach ($tramitesData as $data) {
    $tieneDatos = !empty($data['documentos_requeridos']) || 
                   !empty($data['requisitos_usuario']) || 
                   !empty($data['instrucciones_paso_paso']) ||
                   !empty($data['tiempo_estimado']) ||
                   !empty($data['responsable_nombre']);
    
    if ($tieneDatos) $totalConDatos++;
    
    echo "SLUG: {$data['slug']}\n";
    echo "  Documentos: " . (empty($data['documentos_requeridos']) ? 'NO' : 'SÍ (' . strlen($data['documentos_requeridos']) . ' caracteres)') . "\n";
    echo "  Requisitos: " . (empty($data['requisitos_usuario']) ? 'NO' : 'SÍ (' . strlen($data['requisitos_usuario']) . ' caracteres)') . "\n";
    echo "  Instrucciones: " . (empty($data['instrucciones_paso_paso']) ? 'NO' : 'SÍ (' . strlen($data['instrucciones_paso_paso']) . ' caracteres)') . "\n";
    echo "  Tiempo: " . (empty($data['tiempo_estimado']) ? 'NO' : 'SÍ (' . strlen($data['tiempo_estimado']) . ' caracteres)') . "\n";
    echo "  Responsable: " . (empty($data['responsable_nombre']) ? 'NO' : 'SÍ (' . strlen($data['responsable_nombre']) . ' caracteres)') . "\n";
    echo str_repeat('-', 70) . "\n";
}

echo "\n📊 RESUMEN:\n";
echo "Total de archivos procesados: " . count($tramitesData) . "\n";
echo "Archivos con datos: $totalConDatos\n";
echo "Archivos sin datos: " . (count($tramitesData) - $totalConDatos) . "\n\n";

// Actualizar base de datos
$sql = new Laminas\Db\Sql\Sql($adapter);
$updated = 0;
$skipped = 0;

foreach ($tramitesData as $data) {
    // Solo actualizar si hay al menos un dato
    $tieneDatos = !empty($data['documentos_requeridos']) || 
                   !empty($data['requisitos_usuario']) || 
                   !empty($data['instrucciones_paso_paso']) ||
                   !empty($data['tiempo_estimado']) ||
                   !empty($data['responsable_nombre']);
    
    if (!$tieneDatos) {
        $skipped++;
        continue;
    }
    
    $update = $sql->update('tramites');
    $update->set([
        'documentos_requeridos' => $data['documentos_requeridos'],
        'requisitos_usuario' => $data['requisitos_usuario'],
        'instrucciones_paso_paso' => $data['instrucciones_paso_paso'],
        'tiempo_estimado' => $data['tiempo_estimado'],
        'responsable_nombre' => $data['responsable_nombre'],
    ]);
    $update->where(['slug' => $data['slug'], 'departamento_id' => 1]);
    
    try {
        $stmt = $sql->prepareStatementForSqlObject($update);
        $result = $stmt->execute();
        $updated += $result->getAffectedRows();
        echo "✅ Actualizado: {$data['slug']}\n";
    } catch (Exception $e) {
        echo "❌ Error en {$data['slug']}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ ACTUALIZACIÓN COMPLETADA!\n";
echo "Trámites actualizados: $updated\n";
echo "Trámites omitidos (sin datos): $skipped\n";
