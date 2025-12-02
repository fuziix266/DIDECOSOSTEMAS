<?php
/**
 * Test de diagnóstico para OMIL desde Apache
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico OMIL</h1>";

// Verificar autoload
chdir(__DIR__ . '/../../../');
require 'vendor/autoload.php';

echo "<h2>1. Verificar Base de Datos</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tramites WHERE departamento_id = 14");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Trámites OMIL en BD: " . $result['total'] . "<br>";
} catch (Exception $e) {
    echo "❌ Error BD: " . $e->getMessage() . "<br>";
}

echo "<h2>2. Verificar Clases</h2>";
$clases = [
    'Departamentos\Controller\OmilController',
    'Departamentos\Factory\OmilControllerFactory',
    'Departamentos\Model\DepartamentoModel',
    'Departamentos\Model\TramiteModel'
];

foreach ($clases as $clase) {
    if (class_exists($clase)) {
        echo "✅ Clase existe: $clase<br>";
    } else {
        echo "❌ Clase NO existe: $clase<br>";
    }
}

echo "<h2>3. Verificar Aplicación</h2>";
try {
    $appConfig = require 'config/application.config.php';
    $app = Laminas\Mvc\Application::init($appConfig);
    echo "✅ Aplicación inicializada<br>";
    
    $serviceManager = $app->getServiceManager();
    echo "✅ ServiceManager obtenido<br>";
    
    $controllerManager = $serviceManager->get('ControllerManager');
    echo "✅ ControllerManager obtenido<br>";
    
    $controller = $controllerManager->get(\Departamentos\Controller\OmilController::class);
    echo "✅ OmilController instanciado: " . get_class($controller) . "<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>4. Verificar Archivos</h2>";
$archivos = [
    'module/Departamentos/src/Controller/OmilController.php',
    'module/Departamentos/src/Factory/OmilControllerFactory.php',
    'module/Departamentos/view/departamentos/omil/_tramite_detalle.phtml',
    'module/Departamentos/view/departamentos/omil/postulacion.phtml'
];

foreach ($archivos as $archivo) {
    if (file_exists($archivo)) {
        echo "✅ Archivo existe: $archivo<br>";
    } else {
        echo "❌ Archivo NO existe: $archivo<br>";
    }
}
