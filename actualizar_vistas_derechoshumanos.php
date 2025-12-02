<?php

$archivos = [
    'capacitacion.phtml',
    'diversidadsexual.phtml',
    'lgbt.phtml',
    'nacionalizacion.phtml',
    'permanenciatransitoria.phtml',
    'residenciadefinitiva.phtml',
    'residenciatemporal.phtml',
    'vigenciacedula.phtml'
];

$ruta = __DIR__ . '/module/Departamentos/view/departamentos/derechoshumanos/';
$contenido = "<?= \$this->render('departamentos/derechoshumanos/_tramite_detalle', ['tramite' => \$tramite]) ?>";

foreach ($archivos as $archivo) {
    $rutaCompleta = $ruta . $archivo;
    if (file_exists($rutaCompleta)) {
        file_put_contents($rutaCompleta, $contenido);
        echo "✓ Actualizado: $archivo\n";
    } else {
        echo "✗ No existe: $archivo\n";
    }
}

echo "\nProceso completado.\n";
