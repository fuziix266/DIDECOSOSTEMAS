<?php
/**
 * Script para convertir archivos .phtml de Presupuesto Participativo
 */

$baseDir = __DIR__ . '/../view/departamentos/presupuestoparticipativo/';

$archivos = [
    'fondeco.phtml' => 'fondeco',
    'fondeve.phtml' => 'fondeve',
    'presupuestoparticipativo.phtml' => 'presupuestoparticipativo'
];

foreach ($archivos as $archivo => $slug) {
    $rutaArchivo = $baseDir . $archivo;
    
    if (!file_exists($rutaArchivo)) {
        echo "❌ No existe: $archivo\n";
        continue;
    }
    
    $contenidoNuevo = "<?php\n";
    $contenidoNuevo .= "// Este archivo ha sido convertido para usar el template dinámico\n";
    $contenidoNuevo .= "echo \$this->render('departamentos/presupuestoparticipativo/_tramite_detalle', ['tramite' => \$tramite]);\n";
    
    if (file_put_contents($rutaArchivo, $contenidoNuevo)) {
        echo "✅ Convertido: $archivo (slug: $slug)\n";
    } else {
        echo "❌ Error al escribir: $archivo\n";
    }
}

echo "\n✅ Conversión completada\n";
