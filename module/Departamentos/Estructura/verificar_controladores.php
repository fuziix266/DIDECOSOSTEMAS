<?php
/**
 * Verificar estado de controladores - qué departamentos ya están listos
 */

$controllersPath = __DIR__ . '/../src/Controller/';
$controllers = glob($controllersPath . '*Controller.php');

$listos = [];
$pendientes = [];

foreach ($controllers as $file) {
    $filename = basename($file);
    
    if ($filename === 'IndexController.php' || $filename === 'EvaluacionController.php') {
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Verificar si tiene DepartamentoModel y TramiteModel en el constructor
    $tieneConstructor = strpos($content, 'DepartamentoModel $departamentoModel') !== false;
    $tieneDepartamentoId = preg_match('/private \$departamentoId\s*=\s*\d+/', $content);
    
    // Verificar si usa el método correcto para obtener trámites
    $usaTramiteCompleto = strpos($content, 'getTramiteCompleto') !== false;
    $usaTramiteBySlug = strpos($content, 'getTramiteBySlug') !== false;
    
    $nombreControlador = str_replace('Controller.php', '', $filename);
    
    if ($tieneConstructor && $tieneDepartamentoId && ($usaTramiteCompleto || $usaTramiteBySlug)) {
        $listos[] = $nombreControlador;
    } else {
        $pendientes[] = $nombreControlador;
    }
}

echo "✅ CONTROLADORES LISTOS (" . count($listos) . "):\n";
echo "==================================\n";
sort($listos);
foreach ($listos as $ctrl) {
    echo "  ✓ $ctrl\n";
}

echo "\n❌ CONTROLADORES PENDIENTES (" . count($pendientes) . "):\n";
echo "==================================\n";
sort($pendientes);
foreach ($pendientes as $ctrl) {
    echo "  ✗ $ctrl\n";
}

echo "\n📊 RESUMEN:\n";
echo "==================================\n";
echo "Total: " . (count($listos) + count($pendientes)) . " controladores\n";
echo "Listos: " . count($listos) . "\n";
echo "Pendientes: " . count($pendientes) . "\n";
