<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
    
    echo "Conectado a la base de datos dideco\n\n";
    
    // Departamento Derechos Humanos ID = 15
    $departamentoId = 15;
    
    // Correcciones necesarias basadas en el análisis
    $correcciones = [
        'C├®dula' => 'Cédula',
        'Nacionalizaci├│n' => 'Nacionalización',
        'Atenci├│n' => 'Atención',
        'orientaci├│n' => 'orientación',
        'obtenci├│n' => 'obtención',
        'regularizaci├│n' => 'regularización',
        'formaci├│n' => 'formación',
        'conmemoraci├│n' => 'conmemoración',
        'situaci├│n' => 'situación'
    ];
    
    // Obtener todos los trámites del departamento
    $stmt = $pdo->prepare("SELECT id, nombre, descripcion_corta FROM tramites WHERE departamento_id = ?");
    $stmt->execute([$departamentoId]);
    $tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Trámites encontrados: " . count($tramites) . "\n\n";
    
    $totalActualizados = 0;
    
    foreach ($tramites as $tramite) {
        $actualizado = false;
        $nombre = $tramite['nombre'];
        $descripcionCorta = $tramite['descripcion_corta'];
        
        // Aplicar correcciones
        foreach ($correcciones as $mal => $bien) {
            if (strpos($nombre, $mal) !== false) {
                $nombre = str_replace($mal, $bien, $nombre);
                $actualizado = true;
            }
            if (strpos($descripcionCorta, $mal) !== false) {
                $descripcionCorta = str_replace($mal, $bien, $descripcionCorta);
                $actualizado = true;
            }
        }
        
        if ($actualizado) {
            $updateStmt = $pdo->prepare(
                "UPDATE tramites 
                SET nombre = ?, descripcion_corta = ? 
                WHERE id = ?"
            );
            $updateStmt->execute([$nombre, $descripcionCorta, $tramite['id']]);
            
            echo "✓ Trámite ID {$tramite['id']} actualizado:\n";
            echo "  Nombre: $nombre\n";
            echo "  Descripción corta: $descripcionCorta\n\n";
            
            $totalActualizados++;
        }
    }
    
    echo "\n=== RESUMEN ===\n";
    echo "Total de trámites actualizados: $totalActualizados\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
