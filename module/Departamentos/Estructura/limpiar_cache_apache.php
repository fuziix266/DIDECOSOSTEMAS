<?php
/**
 * Limpiar caché de OPcache para Apache
 */

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "✅ OPcache limpiado\n";
} else {
    echo "ℹ️ OPcache no está habilitado\n";
}

// También limpiar caché de configuración de Laminas
$cacheFile = __DIR__ . '/../../../data/cache/module-config-cache.application.config.cache.php';
if (file_exists($cacheFile)) {
    unlink($cacheFile);
    echo "✅ Caché de módulos eliminado\n";
} else {
    echo "ℹ️ No hay caché de módulos\n";
}

echo "\n✅ Listo. Recarga la página en el navegador.\n";
