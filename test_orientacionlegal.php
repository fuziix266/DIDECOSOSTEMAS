<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config/application.config.php';
$app = \Laminas\Mvc\Application::init($config);
$sm = $app->getServiceManager();

$departamentoModel = $sm->get(\Departamentos\Model\DepartamentoModel::class);
$tramiteModel = $sm->get(\Departamentos\Model\TramiteModel::class);

echo "Buscando trámite 'orientacionlegal' en departamento ID=1...\n\n";

$tramite = $tramiteModel->getTramiteCompleto(1, 'orientacionlegal');

if ($tramite) {
    echo "✓ ENCONTRADO:\n";
    echo "ID: " . $tramite['id'] . "\n";
    echo "Nombre: " . $tramite['nombre'] . "\n";
    echo "Slug: " . $tramite['slug'] . "\n";
    echo "Descripción corta: " . substr($tramite['descripcion_corta'], 0, 80) . "...\n";
} else {
    echo "✗ NO ENCONTRADO por getTramiteCompleto()\n";
    echo "\nVerificando si existe en la BD...\n";
    
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $stmt = $pdo->prepare('SELECT id, nombre, slug, departamento_id FROM tramites WHERE slug = ?');
    $stmt->execute(['orientacionlegal']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo "Existe en BD:\n";
        print_r($row);
    }
}
