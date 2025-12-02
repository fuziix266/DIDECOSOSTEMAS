<?php
/**
 * Script para convertir archivos .phtml de Subsidios y Pensiones
 */

$baseDir = __DIR__ . '/../view/departamentos/subsidioypensiones/';

$archivos = [
    'aguapotable.phtml' => 'aguapotable',
    'apsi.phtml' => 'apsi',
    'bonoporhijo.phtml' => 'bonoporhijo',
    'discapacidad.phtml' => 'discapacidad',
    'familiarduplo.phtml' => 'familiarduplo',
    'madre.phtml' => 'madre',
    'maternal.phtml' => 'maternal',
    'menores.phtml' => 'menores',
    'pbsi.phtml' => 'pbsi',
    'pensiones.phtml' => 'pensiones',
    'pgu.phtml' => 'pgu',
    'reciennacido.phtml' => 'reciennacido',
    'saprural.phtml' => 'saprural',
    'sapurbano.phtml' => 'sapurbano',
    'suf.phtml' => 'suf'
];

foreach ($archivos as $archivo => $slug) {
    $rutaArchivo = $baseDir . $archivo;
    
    if (!file_exists($rutaArchivo)) {
        echo "❌ No existe: $archivo\n";
        continue;
    }
    
    $contenidoNuevo = "<?php\n";
    $contenidoNuevo .= "// Este archivo ha sido convertido para usar el template dinámico\n";
    $contenidoNuevo .= "echo \$this->render('departamentos/subsidioypensiones/_tramite_detalle', ['tramite' => \$tramite]);\n";
    
    if (file_put_contents($rutaArchivo, $contenidoNuevo)) {
        echo "✅ Convertido: $archivo (slug: $slug)\n";
    } else {
        echo "❌ Error al escribir: $archivo\n";
    }
}

echo "\n✅ Conversión completada\n";
