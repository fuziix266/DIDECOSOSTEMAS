<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function slugify($text)
{
    // Replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $htmlFile = __DIR__ . '/Informacion tramites excel/⋆ Discapacidad.html';

    if (!file_exists($htmlFile)) {
        die("File not found: $htmlFile\n");
    }

    $dom = new DOMDocument();
    // Suppress warnings due to malformed HTML often found in exports
    libxml_use_internal_errors(true);
    // Load with UTF-8 encoding hint
    $dom->loadHTML('<?xml encoding="UTF-8">' . file_get_contents($htmlFile));
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    // The table rows seem to be in tbody
    $rows = $xpath->query('//table/tbody/tr');

    echo "Found " . $rows->length . " rows.\n";

    $deptId = 11;
    $count = 0;

    $stmt = $pdo->prepare("INSERT INTO tramites (
        departamento_id, nombre, slug, descripcion_corta, descripcion_larga, 
        documentos_requeridos, requisitos_usuario, instrucciones_paso_paso, 
        tiempo_estimado, responsable_nombre, observaciones, activo, created_at, updated_at
    ) VALUES (
        :dept_id, :nombre, :slug, :desc_corta, :desc_larga,
        :docs, :reqs, :instr,
        :tiempo, :resp, :obs, 1, NOW(), NOW()
    )");

    foreach ($rows as $index => $row) {
        // Skip header row (checking if it has th or based on index)
        if ($index == 0) continue;

        $cells = $xpath->query('.//td', $row);

        // Ensure we have enough cells (cols B through I are indices 1 through 8 in the 0-based list matching the columns)
        // Wait, let's verify visual index.
        // In the viewed file: 
        // Header had: th (empty), th (A), th (B)...
        // Data row 2 had: th (1), td (1 - A), td (Name - B), etc.
        // So in keys:
        // 0 -> A (Nº)
        // 1 -> B (Nombre)
        // 2 -> C (Descerna)
        // 3 -> D (Docs)
        // 4 -> E (Reqs)
        // 5 -> F (Instr)
        // 6 -> G (Tiempo)
        // 7 -> H (Resp)
        // 8 -> I (Obs)

        if ($cells->length < 9) {
            // Maybe empty row
            continue;
        }

        $nombre = trim($cells->item(1)->textContent);

        if (empty($nombre)) continue;

        $descLarga = trim($cells->item(2)->textContent);
        // Short desc: First 200 chars or split by dot
        $descCorta = substr($descLarga, 0, 200);
        if (strlen($descLarga) > 200) {
            $descCorta .= '...';
        }

        $docs = trim($cells->item(3)->textContent);
        $reqs = trim($cells->item(4)->textContent);
        $instr = trim($cells->item(5)->textContent);
        $tiempo = trim($cells->item(6)->textContent);
        $resp = trim($cells->item(7)->textContent);
        $obs = trim($cells->item(8)->textContent);

        $slug = slugify($nombre);

        // Check availability just in case
        $check = $pdo->prepare("SELECT id FROM tramites WHERE slug = :slug");
        $check->execute(['slug' => $slug]);
        if ($check->fetch()) {
            $slug .= '-' . time();
        }

        $stmt->execute([
            'dept_id' => $deptId,
            'nombre' => $nombre,
            'slug' => $slug,
            'desc_corta' => $descCorta,
            'desc_larga' => $descLarga,
            'docs' => $docs,
            'reqs' => $reqs,
            'instr' => $instr,
            'tiempo' => $tiempo,
            'resp' => $resp,
            'obs' => $obs
        ]);

        $count++;
        echo "Inserted: $nombre\n";
    }

    echo "Done. Inserted $count tramites.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
