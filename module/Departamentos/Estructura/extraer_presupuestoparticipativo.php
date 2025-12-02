<?php
/**
 * Extraer y actualizar datos de trámites de Presupuesto Participativo
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

    $viewsPath = __DIR__ . '/../view/departamentos/presupuestoparticipativo/';
    $files = glob($viewsPath . '*.phtml');
    
    $departamentoId = 17; // Presupuesto Participativo
    $tramitesData = [];

    foreach ($files as $file) {
        $filename = basename($file);
        if ($filename === 'index.phtml') {
            continue;
        }

        $slug = str_replace('.phtml', '', $filename);
        $content = file_get_contents($file);

        preg_match('/<h2[^>]*>(.*?)<\/h2>/is', $content, $matchTitulo);
        $nombre = isset($matchTitulo[1]) ? trim(strip_tags($matchTitulo[1])) : '';

        preg_match('/Documentos Requeridos.*?<div class="(?:accordion-body|card-body)">(.*?)<\/div>/s', $content, $matchDoc);
        $documentos = isset($matchDoc[1]) ? $matchDoc[1] : '';
        $documentos = preg_replace('/<br\s*\/?>/i', "\n", $documentos);
        $documentos = strip_tags($documentos);
        $lineas = explode("\n", $documentos);
        $lineas = array_map('trim', $lineas);
        $lineas = array_filter($lineas);
        $documentos = implode("\n", $lineas);

        preg_match('/Requisitos del Usuario.*?<div class="(?:accordion-body|card-body)">(.*?)<\/div>/s', $content, $matchReq);
        $requisitos = isset($matchReq[1]) ? $matchReq[1] : '';
        $requisitos = preg_replace('/<br\s*\/?>/i', "\n", $requisitos);
        $requisitos = strip_tags($requisitos);
        $lineas = explode("\n", $requisitos);
        $lineas = array_map('trim', $lineas);
        $lineas = array_filter($lineas);
        $requisitos = implode("\n", $lineas);

        preg_match('/Instrucciones Paso a Paso.*?<div class="(?:accordion-body|card-body)">(.*?)<\/div>/s', $content, $matchInst);
        $instrucciones = isset($matchInst[1]) ? $matchInst[1] : '';
        $instrucciones = preg_replace('/<br\s*\/?>/i', "\n", $instrucciones);
        $instrucciones = strip_tags($instrucciones);
        $lineas = explode("\n", $instrucciones);
        $lineas = array_map('trim', $lineas);
        $lineas = array_filter($lineas);
        $instrucciones = implode("\n", $lineas);

        preg_match('/Tiempo Estimado.*?<div class="(?:accordion-body|card-body)">(.*?)<\/div>/s', $content, $matchTiempo);
        $tiempo = isset($matchTiempo[1]) ? $matchTiempo[1] : '';
        $tiempo = preg_replace('/<br\s*\/?>/i', "\n", $tiempo);
        $tiempo = strip_tags($tiempo);
        $tiempo = trim($tiempo);

        preg_match('/Responsable.*?<div class="(?:accordion-body|card-body)">(.*?)<\/div>/s', $content, $matchResp);
        $responsable = isset($matchResp[1]) ? $matchResp[1] : '';
        $responsable = preg_replace('/<br\s*\/?>/i', "\n", $responsable);
        $responsable = strip_tags($responsable);
        $responsable = trim($responsable);

        preg_match('/Observaciones.*?<div class="(?:accordion-body|card-body)">(.*?)<\/div>/s', $content, $matchObs);
        $observaciones = isset($matchObs[1]) ? $matchObs[1] : '';
        $observaciones = preg_replace('/<br\s*\/?>/i', "\n", $observaciones);
        $observaciones = strip_tags($observaciones);
        $observaciones = trim($observaciones);

        $tramitesData[] = [
            'slug' => $slug,
            'nombre' => $nombre,
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

    $stmt = $pdo->prepare("
        INSERT INTO tramites (departamento_id, slug, nombre, documentos_requeridos, requisitos_usuario, 
                             instrucciones_paso_paso, tiempo_estimado, responsable_nombre, observaciones)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            nombre = VALUES(nombre),
            documentos_requeridos = VALUES(documentos_requeridos),
            requisitos_usuario = VALUES(requisitos_usuario),
            instrucciones_paso_paso = VALUES(instrucciones_paso_paso),
            tiempo_estimado = VALUES(tiempo_estimado),
            responsable_nombre = VALUES(responsable_nombre),
            observaciones = VALUES(observaciones)
    ");

    $actualizados = 0;
    foreach ($tramitesData as $data) {
        $stmt->execute([
            $departamentoId,
            $data['slug'],
            $data['nombre'],
            $data['documentos'],
            $data['requisitos'],
            $data['instrucciones'],
            $data['tiempo'],
            $data['responsable'],
            $data['observaciones']
        ]);
        $actualizados++;
    }

    echo "✅ Trámites actualizados: $actualizados\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
