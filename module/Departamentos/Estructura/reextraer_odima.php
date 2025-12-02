<?php
/**
 * Re-extraer datos de ODIMA preservando saltos de línea
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

    $viewsPath = __DIR__ . '/../view/departamentos/odima/';
    
    // Restaurar archivos originales desde backup si existe
    $backupPath = __DIR__ . '/../view/departamentos/odima_backup/';
    
    $tramites = [
        'becaindigena' => 'becaindigena.phtml'
    ];

    foreach ($tramites as $slug => $filename) {
        $sourceFile = $backupPath . $filename;
        
        if (!file_exists($sourceFile)) {
            echo "❌ No se encontró backup para $filename\n";
            continue;
        }

        $content = file_get_contents($sourceFile);

        // Extraer documentos requeridos
        preg_match('/Documentos Requeridos.*?<div class="(?:accordion-body|card-body)">(.*?)<\/div>/s', $content, $matchDoc);
        if (isset($matchDoc[1])) {
            $documentos = $matchDoc[1];
            // Convertir <br> y <br/> a saltos de línea
            $documentos = preg_replace('/<br\s*\/?>/i', "\n", $documentos);
            // Limpiar tags HTML pero preservar saltos de línea
            $documentos = strip_tags($documentos);
            // Limpiar espacios en cada línea pero mantener las líneas
            $lineas = explode("\n", $documentos);
            $lineas = array_map('trim', $lineas);
            $lineas = array_filter($lineas); // eliminar líneas vacías
            $documentos = implode("\n", $lineas);
            
            echo "Documentos encontrados:\n";
            echo $documentos . "\n\n";
            
            // Actualizar en BD
            $stmt = $pdo->prepare("UPDATE tramites SET documentos_requeridos = ? WHERE departamento_id = 8 AND slug = ?");
            $stmt->execute([$documentos, $slug]);
            echo "✅ Actualizado: $slug\n\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
