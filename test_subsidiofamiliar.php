<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config/application.config.php';
$app = \Laminas\Mvc\Application::init($config);
$sm = $app->getServiceManager();

$tramiteModel = $sm->get(\Departamentos\Model\TramiteModel::class);

echo "Buscando trámite 'subsidiofamiliar' en departamento ID=1...\n\n";

$tramite = $tramiteModel->getTramiteCompleto(1, 'subsidiofamiliar');

if ($tramite) {
    echo "✓ ENCONTRADO:\n";
    echo "ID: " . $tramite['id'] . "\n";
    echo "Nombre: " . $tramite['nombre'] . "\n";
    echo "Slug: " . $tramite['slug'] . "\n";
    echo "Descripción corta: " . ($tramite['descripcion_corta'] ?? 'N/A') . "\n";
    echo "Descripción larga: " . substr($tramite['descripcion_larga'] ?? 'N/A', 0, 100) . "...\n";
    echo "\nDocumentos requeridos: " . ($tramite['documentos_requeridos'] ?? 'N/A') . "\n";
    echo "Requisitos usuario: " . ($tramite['requisitos_usuario'] ?? 'N/A') . "\n";
    echo "Instrucciones: " . substr($tramite['instrucciones_paso_paso'] ?? 'N/A', 0, 100) . "...\n";
    echo "Tiempo estimado: " . ($tramite['tiempo_estimado'] ?? 'N/A') . "\n";
    echo "Responsable nombre: " . ($tramite['responsable_nombre'] ?? 'N/A') . "\n";
    echo "Responsable cargo: " . ($tramite['responsable_cargo'] ?? 'N/A') . "\n";
    echo "Observaciones: " . ($tramite['observaciones'] ?? 'N/A') . "\n";
} else {
    echo "✗ NO ENCONTRADO\n";
}
