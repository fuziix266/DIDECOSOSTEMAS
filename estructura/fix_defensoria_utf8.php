<?php

/**

 * Script para actualizar datos de Defensoría Ciudadana con UTF-8 correcto

 * IMPORTANTE: Defensoría Ciudadana es departamento_id = 16

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



    // Actualizar departamento Defensoría Ciudadana (ID 16)

    $stmt = $pdo->prepare("

        UPDATE departamentos 

        SET nombre = :nombre,

            descripcion = :descripcion,

            icono_fontawesome = :icono_fontawesome,

            color_primario = :color_primario,

            color_secundario = :color_secundario,

            email_contacto = :email_contacto

        WHERE id = 16

    ");



    $stmt->execute([

        'nombre' => 'Defensoría Ciudadana',

        'descripcion' => 'Accede a los servicios jurídicos gratuitos que brinda la Defensoría Ciudadana para orientación en materias legales civiles, penales y de familia.',

        'icono_fontawesome' => 'fas fa-balance-scale',

        'color_primario' => '#15356c',

        'color_secundario' => '#e67e22',

        'email_contacto' => 'defensoria.ciudadana@municipalidadarica.cl'

    ]);



    echo "✓ Departamento Defensoría Ciudadana actualizado (ID: 16)\n";



    // Verificar si existe el trámite

    $stmt = $pdo->query("SELECT COUNT(*) as count FROM tramites WHERE slug = 'orientacionlegal' AND departamento_id = 16");

    $result = $stmt->fetch(PDO::FETCH_ASSOC);



    if ($result['count'] == 0) {

        // Insertar el trámite si no existe

        $stmt = $pdo->prepare("

            INSERT INTO tramites 

            (departamento_id, nombre, slug, descripcion_corta, descripcion_larga, documentos_requeridos, 

             requisitos_usuario, instrucciones_paso_paso, tiempo_estimado, responsable_nombre, observaciones, icono_bootstrap,

             tipo_tramite, es_presencial, activo)

            VALUES (16, :nombre, 'orientacionlegal', :descripcion_corta, :descripcion_larga, :documentos_requeridos,

                    :requisitos_usuario, :instrucciones_paso_paso, :tiempo_estimado, :responsable_nombre, :observaciones, :icono_bootstrap,

                    'asesoria', 1, 1)

        ");

    } else {

        // Actualizar trámite existente

        $stmt = $pdo->prepare("

            UPDATE tramites 

            SET nombre = :nombre,

                descripcion_corta = :descripcion_corta,

                descripcion_larga = :descripcion_larga,

                documentos_requeridos = :documentos_requeridos,

                requisitos_usuario = :requisitos_usuario,

                instrucciones_paso_paso = :instrucciones_paso_paso,

                tiempo_estimado = :tiempo_estimado,

                responsable_nombre = :responsable_nombre,

                observaciones = :observaciones,

                icono_bootstrap = :icono_bootstrap

            WHERE slug = 'orientacionlegal' AND departamento_id = 16

        ");

    }



    $stmt->execute([

        'nombre' => 'Orientación y Asesoría Jurídica',

        'descripcion_corta' => 'Atención presencial para orientación legal en diversas áreas del derecho.',

        'descripcion_larga' => 'Permite a personas acceder a orientación jurídica en materias de familia, civil, penal, propiedad, contratos, sucesiones, entre otras áreas del derecho.',

        'documentos_requeridos' => "• Cédula de identidad\n• Clave Única\n• Informar si posee causas judiciales en curso",

        'requisitos_usuario' => 'Permite a toda persona sin exclusividad acceder a orientación jurídica gratuita.',

        'instrucciones_paso_paso' => "1. Solicitar atención en la oficina de Defensoría Ciudadana\n2. Atención por orden de llegada (no requiere hora previa)",

        'tiempo_estimado' => 'Depende del trámite solicitado. El abogado informa a cada usuario el tiempo estimado de respuesta según el caso.',

        'responsable_nombre' => "Abogado/a que realizó la atención al usuario/a\nEncargada de Oficina",

        'observaciones' => 'La atención es por orden de llegada, no se necesita agendar una hora previa. Traer cédula de identidad.',

        'icono_bootstrap' => 'bi bi-journal-text'

    ]);



    echo "✓ Trámite de Orientación Jurídica actualizado\n\n";



    // Verificar los datos actualizados

    echo "=== DATOS ACTUALIZADOS ===\n\n";



    $stmt = $pdo->query("SELECT * FROM departamentos WHERE id = 16");

    $dept = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "Departamento:\n";

    echo "  Nombre: {$dept['nombre']}\n";

    echo "  Descripción: {$dept['descripcion']}\n";

    echo "  Email: {$dept['email_contacto']}\n\n";



    $stmt = $pdo->query("SELECT * FROM tramites WHERE slug = 'orientacionlegal' AND departamento_id = 16");

    $tramite = $stmt->fetch(PDO::FETCH_ASSOC);

    

    if ($tramite) {

        echo "Trámite:\n";

        echo "  Nombre: {$tramite['nombre']}\n";

        echo "  Descripción corta: {$tramite['descripcion_corta']}\n";

        echo "  Tiempo: {$tramite['tiempo_estimado']}\n";

        echo "  Observaciones: {$tramite['observaciones']}\n";

    } else {

        echo "⚠️  No se encontró el trámite\n";

    }



    echo "\n✅ Proceso completado exitosamente\n";



} catch (PDOException $e) {

    echo "❌ Error: " . $e->getMessage() . "\n";

}
