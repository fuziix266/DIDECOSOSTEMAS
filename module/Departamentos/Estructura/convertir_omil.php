<?php
/**
 * Script para convertir archivos .phtml de OMIL a usar render()
 */

$baseDir = __DIR__ . '/../view/departamentos/omil/';

$archivos = [
    'certificadocesantia.phtml' => 'certificadocesantia',
    'certificadoinscripcion.phtml' => 'certificadoinscripcion', 
    'cesantiasolidaria.phtml' => 'cesantiasolidaria',
    'inscripcion.phtml' => 'inscripcion',
    'postulacion.phtml' => 'postulacion',
    'requerimiento.phtml' => 'requerimiento',
    'solicituddiscapacidad.phtml' => 'solicituddiscapacidad'
];

foreach ($archivos as $archivo => $slug) {
    $rutaArchivo = $baseDir . $archivo;
    
    if (!file_exists($rutaArchivo)) {
        echo "❌ No existe: $archivo\n";
        continue;
    }
    
    $contenidoNuevo = "<?php\n";
    $contenidoNuevo .= "// Este archivo ha sido convertido para usar el template dinámico\n";
    $contenidoNuevo .= "echo \$this->render('departamentos/omil/_tramite_detalle', ['tramite' => \$tramite]);\n";
    
    if (file_put_contents($rutaArchivo, $contenidoNuevo)) {
        echo "✅ Convertido: $archivo (slug: $slug)\n";
    } else {
        echo "❌ Error al escribir: $archivo\n";
    }
}

echo "\n✅ Conversión completada\n";
