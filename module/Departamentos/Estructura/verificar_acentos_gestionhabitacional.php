<?php

$config = require '../../../config/autoload/local.php';

try {
    $pdo = new PDO(
        $config['db_departamentos']['dsn'],
        $config['db_departamentos']['username'],
        $config['db_departamentos']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar charset
    $pdo->exec("SET NAMES utf8mb4");
    
    $stmt = $pdo->prepare('SELECT slug, documentos_requeridos, requisitos_usuario, instrucciones_paso_paso FROM tramites WHERE departamento_id = 12 ORDER BY slug');
    $stmt->execute();
    $tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($tramites as $tramite) {
        echo "=== " . $tramite['slug'] . " ===\n";
        echo "Documentos: " . mb_substr($tramite['documentos_requeridos'], 0, 100) . "...\n";
        echo "Requisitos: " . mb_substr($tramite['requisitos_usuario'], 0, 100) . "...\n";
        echo "Instrucciones: " . mb_substr($tramite['instrucciones_paso_paso'], 0, 100) . "...\n\n";
        
        // Verificar si hay caracteres extraños
        if (preg_match('/├│|â€|Ã±|Ã³|Ã¡|Ã©|Ã­/', $tramite['documentos_requeridos'] . $tramite['requisitos_usuario'] . $tramite['instrucciones_paso_paso'])) {
            echo "⚠️  ADVERTENCIA: Se detectaron caracteres con problemas de codificación en: " . $tramite['slug'] . "\n\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
