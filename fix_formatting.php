<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $deptId = 11;

    // Columns to fix
    $cols = ['documentos_requeridos', 'requisitos_usuario', 'instrucciones_paso_paso'];

    foreach ($cols as $col) {
        // Update query: Replace "•" with "\n•" (except if it's already nicely formatted, but simpler to just replace all and then maybe fix double newlines)
        // Actually, let's just replace "•" with "<br>•" or just "\n•". The view uses nl2br(), so "\n" is enough.
        // We also want to trim leading/trailing spaces.

        // This regex replacement in MySQL 8.0 would be easier, but let's do it in PHP to be safe and precise.

        $stmt = $pdo->prepare("SELECT id, $col FROM tramites WHERE departamento_id = :id");
        $stmt->execute(['id' => $deptId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $content = $row[$col];
            if (empty($content)) continue;

            // Strategy: 
            // 1. Remove existing newlines to start fresh? No, that might break paragraphs.
            // 2. Just ensure there is a newline before every "•", unless it's the start of the string.

            // Replace "• " or "•" with "\n• "
            $newContent = str_replace('•', "\n•", $content);

            // Fix double newlines if any
            $newContent = str_replace("\n\n•", "\n•", $newContent);

            // Trim initial newline if it exists
            $newContent = trim($newContent);

            if ($newContent !== $content) {
                $update = $pdo->prepare("UPDATE tramites SET $col = :content WHERE id = :id");
                $update->execute(['content' => $newContent, 'id' => $row['id']]);
                echo "Updated {$col} for ID {$row['id']}\n";
            }
        }
    }
    echo "Formatting complete.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
