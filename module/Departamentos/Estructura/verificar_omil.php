<?php
/**
 * Verificar datos de OMIL en la base de datos
 */

try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SELECT id, nombre, slug FROM tramites WHERE departamento_id = 14 ORDER BY id');
    $tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Trámites de OMIL en base de datos:\n";
    echo "Total: " . count($tramites) . "\n\n";
    
    foreach ($tramites as $tramite) {
        echo "ID: {$tramite['id']}\n";
        echo "Slug: {$tramite['slug']}\n";
        echo "Nombre: {$tramite['nombre']}\n";
        echo "URL: http://localhost:8000/departamentos/omil/{$tramite['slug']}\n";
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
