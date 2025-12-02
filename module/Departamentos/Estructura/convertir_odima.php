<?php
/**
 * Convertir vistas de ODIMA al sistema dinámico
 */

$viewsPath = __DIR__ . '/../view/departamentos/odima/';
$files = glob($viewsPath . '*.phtml');

$newContent = '<?php

/**
 * Control y Gestión Digital - DIDECO
 * @var array $tramite Datos del trámite desde BD
 */

// Incluir vista genérica de detalle de trámite
echo $this->render(\'departamentos/odima/_tramite_detalle\', [\'tramite\' => $tramite]);
';

$replaced = 0;
foreach ($files as $file) {
    $filename = basename($file);
    if (in_array($filename, ['index.phtml', '_tramite_detalle.phtml'])) {
        echo "⏭️  Omitido: $filename\n";
        continue;
    }
    
    file_put_contents($file, $newContent);
    echo "✅ Convertido: $filename\n";
    $replaced++;
}

echo "\n✅ Total convertidos: $replaced archivos\n";
