<?php
/**
 * Extraer y actualizar datos de trámites de Mujer y Equidad
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

    $viewsPath = __DIR__ . '/../view/departamentos/mujeryequidad/';
    $files = glob($viewsPath . '*.phtml');
    
    $departamentoId = 7; // Mujer y Equidad
    $tramitesData = [];

    foreach ($files as $file) {
        $filename = basename($file);
        if ($filename === 'index.phtml') {
            continue;
        }

        $slug = str_replace('.phtml', '', $filename);
        $content = file_get_contents($file);

        // Extraer título
        preg_match('/<h4>(.*?)<\/h4>/s', $content, $matchTitulo);
        $nombre = isset($matchTitulo[1]) ? trim(strip_tags($matchTitulo[1])) : '';

        // Extraer descripción
        preg_match('/<h4>.*?<\/h4>\s*<p>(.*?)<\/p>/s', $content, $matchDesc);
        $descripcion = isset($matchDesc[1]) ? trim(strip_tags($matchDesc[1])) : '';

        // Extraer documentos requeridos
        preg_match('/Documentos Requeridos.*?<div class="accordion-body">(.*?)<\/div>/s', $content, $matchDoc);
        $documentos = isset($matchDoc[1]) ? trim(strip_tags($matchDoc[1])) : '';
        $documentos = str_replace('<br>', "\n", $documentos);
        $documentos = preg_replace('/\s+/', ' ', $documentos);
        $documentos = trim($documentos);

        // Extraer requisitos
        preg_match('/Requisitos del Usuario.*?<div class="accordion-body">(.*?)<\/div>/s', $content, $matchReq);
        $requisitos = isset($matchReq[1]) ? trim(strip_tags($matchReq[1])) : '';
        $requisitos = str_replace('<br>', "\n", $requisitos);
        $requisitos = preg_replace('/\s+/', ' ', $requisitos);
        $requisitos = trim($requisitos);

        // Extraer instrucciones
        preg_match('/Instrucciones Paso a Paso.*?<div class="accordion-body">(.*?)<\/div>/s', $content, $matchInst);
        $instrucciones = isset($matchInst[1]) ? trim(strip_tags($matchInst[1])) : '';
        $instrucciones = str_replace('<br>', "\n", $instrucciones);
        $instrucciones = preg_replace('/\s+/', ' ', $instrucciones);
        $instrucciones = trim($instrucciones);

        // Extraer tiempo estimado
        preg_match('/Tiempo Estimado.*?<div class="accordion-body">(.*?)<\/div>/s', $content, $matchTiempo);
        $tiempo = isset($matchTiempo[1]) ? trim(strip_tags($matchTiempo[1])) : '';
        $tiempo = preg_replace('/\s+/', ' ', $tiempo);
        $tiempo = trim($tiempo);

        // Extraer responsable
        preg_match('/Responsable.*?<div class="accordion-body">(.*?)<\/div>/s', $content, $matchResp);
        $responsable = isset($matchResp[1]) ? trim(strip_tags($matchResp[1])) : '';
        $responsable = preg_replace('/\s+/', ' ', $responsable);
        $responsable = trim($responsable);

        // Extraer observaciones si existen
        preg_match('/Observaciones.*?<div class="accordion-body">(.*?)<\/div>/s', $content, $matchObs);
        $observaciones = isset($matchObs[1]) ? trim(strip_tags($matchObs[1])) : '';
        $observaciones = preg_replace('/\s+/', ' ', $observaciones);
        $observaciones = trim($observaciones);

        $tramitesData[] = [
            'slug' => $slug,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'documentos' => $documentos,
            'requisitos' => $requisitos,
            'instrucciones' => $instrucciones,
            'tiempo' => $tiempo,
            'responsable' => $responsable,
            'observaciones' => $observaciones
        ];

        echo "📄 $filename:\n";
        echo "   Nombre: $nombre\n";
        echo "   Documentos: " . (!empty($documentos) ? "SÍ" : "NO") . "\n";
        echo "   Requisitos: " . (!empty($requisitos) ? "SÍ" : "NO") . "\n";
        echo "   Instrucciones: " . (!empty($instrucciones) ? "SÍ" : "NO") . "\n";
        echo "   Tiempo: " . (!empty($tiempo) ? "SÍ" : "NO") . "\n";
        echo "   Responsable: " . (!empty($responsable) ? "SÍ" : "NO") . "\n";
        echo "   Observaciones: " . (!empty($observaciones) ? "SÍ" : "NO") . "\n\n";
    }

    // Actualizar base de datos
    $stmt = $pdo->prepare("
        UPDATE tramites 
        SET documentos_requeridos = ?, 
            requisitos_usuario = ?, 
            instrucciones_paso_paso = ?, 
            tiempo_estimado = ?, 
            responsable_nombre = ?,
            observaciones = ?
        WHERE departamento_id = ? AND slug = ?
    ");

    $actualizados = 0;
    foreach ($tramitesData as $data) {
        $stmt->execute([
            $data['documentos'],
            $data['requisitos'],
            $data['instrucciones'],
            $data['tiempo'],
            $data['responsable'],
            $data['observaciones'],
            $departamentoId,
            $data['slug']
        ]);
        if ($stmt->rowCount() > 0) {
            $actualizados++;
        }
    }

    echo "✅ Trámites actualizados: $actualizados\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
