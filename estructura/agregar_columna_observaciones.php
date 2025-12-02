<?php

/**

 * Script para agregar columna observaciones a la tabla tramites

 */



try {

    $pdo = new PDO(

        'mysql:host=localhost;dbname=dideco;charset=utf8mb4',

        'root',

        '',

        [

            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"

        ]

    );



    echo "Conexión establecida correctamente\n\n";



    // Verificar si la columna ya existe

    $stmt = $pdo->query("SHOW COLUMNS FROM tramites LIKE 'observaciones'");

    $columnExists = $stmt->fetch();



    if ($columnExists) {

        echo "⚠️  La columna 'observaciones' ya existe en la tabla tramites\n";

    } else {

        // Agregar la columna observaciones después de responsable_telefono

        $pdo->exec("

            ALTER TABLE tramites 

            ADD COLUMN observaciones TEXT NULL 

            AFTER responsable_telefono

        ");

        

        echo "✅ Columna 'observaciones' agregada exitosamente a la tabla tramites\n";

    }



    // Verificar la estructura actualizada

    echo "\n=== ESTRUCTURA ACTUALIZADA ===\n\n";

    $stmt = $pdo->query("DESCRIBE tramites");

    

    echo "Columnas de la tabla tramites:\n";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        echo "  - {$row['Field']} ({$row['Type']})\n";

    }



    echo "\n✅ Proceso completado\n";



} catch (PDOException $e) {

    echo "❌ Error: " . $e->getMessage() . "\n";

}
