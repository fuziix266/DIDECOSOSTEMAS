<?php
/**
 * Script universal para re-extraer datos preservando saltos de línea
 * Este script lee los archivos .phtml ANTES de ser convertidos desde Git
 */

// Función para procesar el contenido HTML y extraer texto preservando saltos
function extraerConSaltos($html) {
    // Reemplazar <br>, <br/>, <br /> con saltos de línea
    $texto = preg_replace('/<br\s*\/?>/i', "\n", $html);
    // Eliminar otros tags HTML
    $texto = strip_tags($texto);
    // Separar por líneas
    $lineas = explode("\n", $texto);
    // Limpiar cada línea individualmente
    $lineas = array_map('trim', $lineas);
    // Eliminar líneas vacías
    $lineas = array_filter($lineas, function($linea) {
        return $linea !== '';
    });
    // Reunir con saltos de línea
    return implode("\n", $lineas);
}

echo "Este script necesita ejecutarse con los archivos ORIGINALES .phtml\n";
echo "Por favor, restaura los archivos desde Git antes de ejecutar.\n\n";
echo "Comando sugerido:\n";
echo "git checkout HEAD -- module/Departamentos/view/departamentos/\n\n";
echo "Luego ejecuta este script para extraer correctamente.\n";
