<?php
// Test script para debug de búsqueda
require_once __DIR__ . '/../../vendor/autoload.php';

// Configuración de la aplicación
$appConfig = require __DIR__ . '/../../config/application.config.php';
$app = \Laminas\Mvc\Application::init($appConfig);
$serviceManager = $app->getServiceManager();

try {
    // Obtener el adaptador de BD
    $dbAdapter = $serviceManager->get('db_departamentos');
    echo "✓ Conexión a BD exitosa\n";

    // Crear instancia del modelo
    $tramiteModel = new \Application\Model\TramiteModel($dbAdapter);
    echo "✓ TramiteModel creado\n";

    // Probar búsqueda
    $results = $tramiteModel->searchTramites('ayuda');
    echo "✓ Búsqueda ejecutada\n";
    echo "Resultados encontrados: " . count($results) . "\n";

    if (count($results) > 0) {
        echo "\nPrimer resultado:\n";
        print_r($results[0]);
    }
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
