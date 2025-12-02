<?php
/**
 * Test para verificar si el controlador OMIL se puede instanciar
 */

chdir(__DIR__ . '/../../../');
require 'vendor/autoload.php';

use Laminas\Mvc\Application;

try {
    $appConfig = require 'config/application.config.php';
    $app = Application::init($appConfig);
    $serviceManager = $app->getServiceManager();
    
    echo "✓ Aplicación inicializada\n";
    
    // Intentar obtener el controlador
    $controllerManager = $serviceManager->get('ControllerManager');
    echo "✓ ControllerManager obtenido\n";
    
    $controller = $controllerManager->get(\Departamentos\Controller\OmilController::class);
    echo "✓ OmilController instanciado correctamente\n";
    echo "  Tipo: " . get_class($controller) . "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "  Archivo: " . $e->getFile() . "\n";
    echo "  Línea: " . $e->getLine() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}
