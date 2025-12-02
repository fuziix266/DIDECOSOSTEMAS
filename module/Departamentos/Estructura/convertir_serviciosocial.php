<?php
/**
 * Script para convertir archivos .phtml de Servicio Social
 */

$baseDir = __DIR__ . '/../view/departamentos/serviciosocial/';

$archivos = [
    'alimentos.phtml' => 'alimentos',
    'ayudasalud.phtml' => 'ayudasalud',
    'camionaljibe.phtml' => 'camionaljibe',
    'canonarrendamiento.phtml' => 'canonarrendamiento',
    'materialesconstruccion.phtml' => 'materialesconstruccion',
    'pagoservicios.phtml' => 'pagoservicios',
    'pasajes.phtml' => 'pasajes',
    'piezasprefabricadas.phtml' => 'piezasprefabricadas'
];

foreach ($archivos as $archivo => $slug) {
    $rutaArchivo = $baseDir . $archivo;
    
    if (!file_exists($rutaArchivo)) {
        echo "❌ No existe: $archivo\n";
        continue;
    }
    
    $contenidoNuevo = "<?php\n";
    $contenidoNuevo .= "// Este archivo ha sido convertido para usar el template dinámico\n";
    $contenidoNuevo .= "echo \$this->render('departamentos/serviciosocial/_tramite_detalle', ['tramite' => \$tramite]);\n";
    
    if (file_put_contents($rutaArchivo, $contenidoNuevo)) {
        echo "✅ Convertido: $archivo (slug: $slug)\n";
    } else {
        echo "❌ Error al escribir: $archivo\n";
    }
}

echo "\n✅ Conversión completada\n";
