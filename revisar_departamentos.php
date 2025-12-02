<?php
require 'vendor/autoload.php';

$config = require 'config/autoload/local.php';
$adapter = new Laminas\Db\Adapter\Adapter($config['db_departamentos']);
$sql = new Laminas\Db\Sql\Sql($adapter);

// Listar todos los departamentos
$select = $sql->select('departamentos');
$select->order('id ASC');
$stmt = $sql->prepareStatementForSqlObject($select);
$deptos = $stmt->execute();

echo "=== DEPARTAMENTOS EN LA BASE DE DATOS ===\n\n";

$departamentosListos = ['enlacenorte', 'afrodescendientes'];

foreach ($deptos as $depto) {
    $slug = $depto['slug'];
    
    // Verificar si tiene _tramite_detalle.phtml
    $rutaDetalle = __DIR__ . "/module/Departamentos/view/departamentos/{$slug}/_tramite_detalle.phtml";
    $tieneDetalle = file_exists($rutaDetalle);
    
    // Verificar si tiene archivos de vista
    $rutaVistas = __DIR__ . "/module/Departamentos/view/departamentos/{$slug}/";
    $tieneVistas = is_dir($rutaVistas);
    
    // Contar trámites
    $selectTramites = $sql->select('tramites');
    $selectTramites->where(['departamento_id' => $depto['id']]);
    $stmtTramites = $sql->prepareStatementForSqlObject($selectTramites);
    $tramites = $stmtTramites->execute();
    $numTramites = count($tramites);
    
    // Verificar si tiene controlador
    $rutaController = __DIR__ . "/module/Departamentos/src/Controller/" . ucfirst($slug) . "Controller.php";
    $tieneController = file_exists($rutaController);
    
    $estado = '❌ NO CONFIGURADO';
    if ($tieneDetalle && $tieneController && $numTramites > 0) {
        $estado = '✅ LISTO';
    } elseif ($tieneController || $tieneVistas) {
        $estado = '⚠️  PARCIAL';
    }
    
    echo sprintf(
        "%s %2d. %-35s (%-25s) %2d trámites | Controller: %s | Detalle: %s\n",
        $estado,
        $depto['id'],
        $depto['nombre'],
        $slug,
        $numTramites,
        $tieneController ? 'SÍ' : 'NO',
        $tieneDetalle ? 'SÍ' : 'NO'
    );
}

echo "\n=== RESUMEN ===\n";
echo "Verifica la carpeta module/Departamentos/view/departamentos/ para ver qué departamentos tienen vistas.\n";
