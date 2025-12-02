<?php
/**
 * Script para reemplazar todas las vistas hardcodeadas de Enlace Norte
 * por llamadas dinámicas a _tramite_detalle.phtml
 */

$viewsPath = __DIR__ . '/../view/departamentos/enlacenorte/';
$files = glob($viewsPath . '*.phtml');

$replaced = 0;
$skipped = 0;

$newContent = '<?php

/**
 * Control y Gestión Digital - DIDECO
 * @var array $tramite Datos del trámite desde BD
 */

// Incluir vista genérica de detalle de trámite
echo $this->render(\'departamentos/enlacenorte/_tramite_detalle\', [\'tramite\' => $tramite]);
';

foreach ($files as $file) {
    $filename = basename($file);
    
    // Saltar archivos especiales
    if (in_array($filename, ['index.phtml', '_tramite_detalle.phtml'])) {
        echo "⏭️  Omitido: $filename (archivo especial)\n";
        $skipped++;
        continue;
    }
    
    // Reemplazar contenido
    file_put_contents($file, $newContent);
    echo "✅ Reemplazado: $filename\n";
    $replaced++;
}

echo "\n📊 RESUMEN:\n";
echo "Archivos reemplazados: $replaced\n";
echo "Archivos omitidos: $skipped\n";
echo "\n✅ Todos los archivos ahora cargan dinámicamente desde la BD!\n";
