<?php
/**
 * Script para identificar departamentos que necesitan conversión a sistema dinámico
 */

$departamentosDir = __DIR__ . '/module/Departamentos/view/departamentos/';
$departamentos = array_filter(scandir($departamentosDir), function($item) use ($departamentosDir) {
    return is_dir($departamentosDir . $item) && !in_array($item, ['.', '..', 'evaluacion', 'index']);
});

echo "=== ESTADO DE DEPARTAMENTOS ===\n\n";

$listos = [];
$faltaDetalle = [];
$sinVistas = [];

foreach ($departamentos as $slug) {
    $rutaDir = $departamentosDir . $slug . '/';
    $rutaDetalle = $rutaDir . '_tramite_detalle.phtml';
    $rutaIndex = $rutaDir . 'index.phtml';
    
    // Contar archivos .phtml (excluyendo index y _tramite_detalle)
    $archivos = glob($rutaDir . '*.phtml');
    $numArchivos = 0;
    foreach ($archivos as $archivo) {
        $nombre = basename($archivo);
        if (!in_array($nombre, ['index.phtml', '_tramite_detalle.phtml'])) {
            $numArchivos++;
        }
    }
    
    $tieneDetalle = file_exists($rutaDetalle);
    $tieneIndex = file_exists($rutaIndex);
    
    if ($tieneDetalle && $numArchivos > 0) {
        $listos[] = $slug;
        echo "✅ LISTO: {$slug} ({$numArchivos} servicios)\n";
    } elseif ($numArchivos > 0 && !$tieneDetalle) {
        $faltaDetalle[] = $slug;
        echo "⚠️  FALTA _tramite_detalle.phtml: {$slug} ({$numArchivos} servicios)\n";
    } else {
        $sinVistas[] = $slug;
        echo "❌ SIN VISTAS: {$slug}\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "✅ Departamentos listos: " . count($listos) . "\n";
foreach ($listos as $d) echo "   - $d\n";

echo "\n⚠️  Departamentos que necesitan conversión: " . count($faltaDetalle) . "\n";
foreach ($faltaDetalle as $d) echo "   - $d\n";

echo "\n❌ Departamentos sin vistas: " . count($sinVistas) . "\n";
foreach ($sinVistas as $d) echo "   - $d\n";

echo "\n📋 SIGUIENTE PASO:\n";
if (count($faltaDetalle) > 0) {
    echo "Convertir estos " . count($faltaDetalle) . " departamentos al sistema dinámico.\n";
    echo "Estos departamentos tienen vistas hardcodeadas que necesitan:\n";
    echo "1. Extraer los datos a la base de datos\n";
    echo "2. Crear el archivo _tramite_detalle.phtml\n";
    echo "3. Actualizar cada vista para usar render()\n";
}
