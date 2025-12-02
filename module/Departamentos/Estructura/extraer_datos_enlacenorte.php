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
    
    // Extraer datos usando expresiones regulares
    $data = [
        'slug' => $filename,
        'documentos_requeridos' => null,
        'requisitos_usuario' => null,
        'instrucciones_paso_paso' => null,
        'tiempo_estimado' => null,
        'responsable_nombre' => null,
    ];
    
    // Extraer Documentos Requeridos
    if (preg_match('/<div class="card-body">\s*<ul>(.*?)<\/ul>/s', $content, $matches, PREG_OFFSET_CAPTURE, strpos($content, 'Documentos Requeridos'))) {
        $html = $matches[1][0];
        $html = strip_tags($html, '<li>');
        $html = str_replace(['<li>', '</li>'], ['• ', "\n"], $html);
        $data['documentos_requeridos'] = trim($html);
    }
    
    // Extraer Requisitos del Usuario
    if (preg_match('/<div class="card-body">\s*<ul>(.*?)<\/ul>/s', $content, $matches, PREG_OFFSET_CAPTURE, strpos($content, 'Requisitos del Usuario'))) {
        $html = $matches[1][0];
        $html = strip_tags($html, '<li>');
        $html = str_replace(['<li>', '</li>'], ['• ', "\n"], $html);
        $data['requisitos_usuario'] = trim($html);
    }
    
    // Extraer Instrucciones Paso a Paso
    if (preg_match('/<div class="card-body">\s*<ol>(.*?)<\/ol>/s', $content, $matches, PREG_OFFSET_CAPTURE, strpos($content, 'Instrucciones Paso a Paso'))) {
        $html = $matches[1][0];
        $html = strip_tags($html, '<li>');
        $lines = explode('</li>', $html);
        $instructions = [];
        $step = 1;
        foreach ($lines as $line) {
            $line = strip_tags($line);
            $line = trim($line);
            if (!empty($line)) {
                $instructions[] = "$step. $line";
                $step++;
            }
        }
        $data['instrucciones_paso_paso'] = implode("\n", $instructions);
    }
    
    // Extraer Tiempo Estimado
    if (preg_match('/Tiempo Estimado de Respuesta.*?<div class="card-body">\s*<p>(.*?)<\/p>/s', $content, $matches)) {
        $data['tiempo_estimado'] = trim(strip_tags($matches[1]));
    }
    
    // Extraer Responsable
    if (preg_match('/Responsable del Trámite.*?<div class="card-body">\s*<p>(.*?)<\/p>/s', $content, $matches)) {
        $data['responsable_nombre'] = trim(strip_tags($matches[1]));
    }
    
    $tramitesData[] = $data;
}

// Mostrar resultados extraídos
echo "=== DATOS EXTRAÍDOS DE ENLACE NORTE ===\n\n";
foreach ($tramitesData as $data) {
    echo "SLUG: {$data['slug']}\n";
    echo "Documentos: " . (empty($data['documentos_requeridos']) ? 'NO' : 'SÍ') . "\n";
    echo "Requisitos: " . (empty($data['requisitos_usuario']) ? 'NO' : 'SÍ') . "\n";
    echo "Instrucciones: " . (empty($data['instrucciones_paso_paso']) ? 'NO' : 'SÍ') . "\n";
    echo "Tiempo: " . (empty($data['tiempo_estimado']) ? 'NO' : 'SÍ') . "\n";
    echo "Responsable: " . (empty($data['responsable_nombre']) ? 'NO' : 'SÍ') . "\n";
    echo str_repeat('-', 50) . "\n";
}

echo "\n=== ¿DESEAS ACTUALIZAR LA BASE DE DATOS? (s/n): ===\n";
echo "Total de trámites encontrados: " . count($tramitesData) . "\n\n";

// Para ejecutar la actualización, descomenta las siguientes líneas:
/*
$sql = new Laminas\Db\Sql\Sql($adapter);
$updated = 0;

foreach ($tramitesData as $data) {
    $update = $sql->update('tramites');
    $update->set([
        'documentos_requeridos' => $data['documentos_requeridos'],
        'requisitos_usuario' => $data['requisitos_usuario'],
        'instrucciones_paso_paso' => $data['instrucciones_paso_paso'],
        'tiempo_estimado' => $data['tiempo_estimado'],
        'responsable_nombre' => $data['responsable_nombre'],
    ]);
    $update->where(['slug' => $data['slug'], 'departamento_id' => 1]);
    
    $stmt = $sql->prepareStatementForSqlObject($update);
    $result = $stmt->execute();
    $updated += $result->getAffectedRows();
}

echo "\n✅ Actualización completada!\n";
echo "Trámites actualizados: $updated\n";
*/
