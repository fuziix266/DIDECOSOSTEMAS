<?php

/**

 * Script para corregir UTF-8 en todos los trámites y limpiar asignaciones incorrectas

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



    // Obtener todos los trámites con problemas de UTF-8

    $stmt = $pdo->query("

        SELECT id, nombre, departamento_id

        FROM tramites 

        WHERE nombre LIKE '%├%' OR nombre LIKE '%�%'

        ORDER BY departamento_id, id

    ");



    $tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    

    echo "=== TRÁMITES CON PROBLEMAS DE UTF-8 ===\n\n";

    echo "Total encontrados: " . count($tramites) . "\n\n";



    // Mapeo de correcciones

    $correcciones = [

        'Log├¡stica' => 'Logística',

        'Pensi├│n' => 'Pensión',

        'B├ísica' => 'Básica',

        'a├▒os' => 'años',

        '├Ünico' => 'Único',

        'Reci├®n' => 'Recién',

        'Inscripci├│n' => 'Inscripción',

        'Colaboraci├│n' => 'Colaboración',

        'Tramitaci├│n' => 'Tramitación',

        'administraci├│n' => 'administración',

        'orientaci├│n' => 'orientación',

        'Atenci├│n' => 'Atención',

        'Orientaci├│n' => 'Orientación',

        'Asesor├¡a' => 'Asesoría',

        'Jur├¡dica' => 'Jurídica'

    ];



    $corregidos = 0;



    foreach ($tramites as $tramite) {

        $nombre_original = $tramite['nombre'];

        $nombre_corregido = $nombre_original;



        foreach ($correcciones as $incorrecto => $correcto) {

            $nombre_corregido = str_replace($incorrecto, $correcto, $nombre_corregido);

        }



        if ($nombre_original !== $nombre_corregido) {

            $stmt = $pdo->prepare("UPDATE tramites SET nombre = :nombre WHERE id = :id");

            $stmt->execute([

                'nombre' => $nombre_corregido,

                'id' => $tramite['id']

            ]);



            echo "✓ ID {$tramite['id']} (Dept {$tramite['departamento_id']}): \n";

            echo "  Antes: {$nombre_original}\n";

            echo "  Después: {$nombre_corregido}\n\n";



            $corregidos++;

        }

    }



    echo "\n=== RESUMEN ===\n";

    echo "Trámites corregidos: {$corregidos}\n";



    // Verificar trámites del departamento 1

    echo "\n=== TRÁMITES ACTUALES DE DEFENSORÍA (Dept 1) ===\n\n";

    $stmt = $pdo->query("SELECT id, slug, nombre FROM tramites WHERE departamento_id = 1 ORDER BY id");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        echo "{$row['id']} | {$row['slug']} | {$row['nombre']}\n";

    }



    echo "\n✅ Proceso completado\n";



} catch (PDOException $e) {

    echo "❌ Error: " . $e->getMessage() . "\n";

}
