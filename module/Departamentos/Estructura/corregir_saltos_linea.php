<?php
/**
 * Corregir saltos de línea en todos los departamentos
 * Reemplaza "• texto • texto" con "• texto\n• texto"
 */

$config = require '../../../config/autoload/local.php';

try {
    $pdo = new PDO(
        $config['db_departamentos']['dsn'],
        $config['db_departamentos']['username'],
        $config['db_departamentos']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");

    // Obtener todos los trámites
    $stmt = $pdo->query("SELECT id, slug, departamento_id, documentos_requeridos, requisitos_usuario, instrucciones_paso_paso FROM tramites");
    $tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $updateStmt = $pdo->prepare("UPDATE tramites SET documentos_requeridos = ?, requisitos_usuario = ?, instrucciones_paso_paso = ? WHERE id = ?");

    $actualizados = 0;

    foreach ($tramites as $tramite) {
        $cambio = false;
        
        // Corregir documentos
        $docs = $tramite['documentos_requeridos'];
        $docs_nuevo = preg_replace('/\s*•\s*/u', "\n• ", $docs);
        $docs_nuevo = trim($docs_nuevo);
        if ($docs_nuevo !== $docs && !empty($docs)) {
            $cambio = true;
        }

        // Corregir requisitos  
        $reqs = $tramite['requisitos_usuario'];
        $reqs_nuevo = preg_replace('/\s*•\s*/u', "\n• ", $reqs);
        $reqs_nuevo = trim($reqs_nuevo);
        if ($reqs_nuevo !== $reqs && !empty($reqs)) {
            $cambio = true;
        }

        // Corregir instrucciones
        $inst = $tramite['instrucciones_paso_paso'];
        $inst_nuevo = preg_replace('/\s*•\s*/u', "\n• ", $inst);
        $inst_nuevo = trim($inst_nuevo);
        if ($inst_nuevo !== $inst && !empty($inst)) {
            $cambio = true;
        }

        if ($cambio) {
            $updateStmt->execute([$docs_nuevo, $reqs_nuevo, $inst_nuevo, $tramite['id']]);
            echo "✅ Actualizado ID {$tramite['id']} - {$tramite['slug']}\n";
            $actualizados++;
        }
    }

    echo "\n📊 Total actualizados: $actualizados trámites\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
