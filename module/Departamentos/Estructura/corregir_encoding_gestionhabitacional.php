<?php
/**
 * Corregir codificación de archivos de Gestión Habitacional
 */

$viewsPath = __DIR__ . '/../view/departamentos/gestionhabitacional/';
$files = glob($viewsPath . '*.phtml');

// Contenido correcto en UTF-8
$correctContent = '<?php

/**
 * Control y Gestión Digital - DIDECO
 * @var array $tramite Datos del trámite desde BD
 */

// Incluir vista genérica de detalle de trámite
echo $this->render(\'departamentos/gestionhabitacional/_tramite_detalle\', [\'tramite\' => $tramite]);
';

$fixed = 0;
foreach ($files as $file) {
    $filename = basename($file);
    if (in_array($filename, ['index.phtml', '_tramite_detalle.phtml'])) {
        echo "⏭️  Omitido: $filename\n";
        continue;
    }
    
    // Guardar con UTF-8 sin BOM
    $bytes = file_put_contents($file, $correctContent);
    if ($bytes !== false) {
        echo "✅ Corregido: $filename ($bytes bytes)\n";
        $fixed++;
    } else {
        echo "❌ Error: $filename\n";
    }
}

echo "\n✅ Total corregidos: $fixed archivos\n";
