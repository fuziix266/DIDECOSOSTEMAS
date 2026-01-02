<?php
ini_set('max_execution_time', 300); // 5 minutes just in case
try {
    $pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Fetching tables...\n";
    $stmt = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    $totalUpdated = 0;

    foreach ($tables as $table) {
        $stmt = $pdo->query("DESCRIBE `$table`");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $textCols = [];
        foreach ($cols as $col) {
            // Identify text columns
            if (preg_match('/(char|text|varchar|enum|set)/i', $col['Type'])) {
                $textCols[] = $col['Field'];
            }
        }

        if (empty($textCols)) continue;

        echo "Processing table: $table (" . count($textCols) . " text columns)\n";

        foreach ($textCols as $field) {
            // Construct the nested REPLACE query
            $sql = "UPDATE `$table` SET `$field` = 
                REPLACE(
                REPLACE(
                REPLACE(
                REPLACE(
                REPLACE(
                REPLACE(
                REPLACE(`$field`, 
                    '├í', 'á'), 
                    '├®', 'é'), 
                    '├¡', 'í'), 
                    '├│', 'ó'), 
                    '├║', 'ú'), 
                    '├▒', 'ñ'), 
                    '├æ', 'Ñ')
            WHERE `$field` LIKE '%├%'"; // Optimization: only touch rows with the bad char start

            $update = $pdo->prepare($sql);
            $update->execute();

            $count = $update->rowCount();
            if ($count > 0) {
                echo "  - Updated column '$field': $count rows affected.\n";
                $totalUpdated += $count;
            }
        }
    }

    echo "\nGlobal fix complete. Total rows updated: $totalUpdated\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
