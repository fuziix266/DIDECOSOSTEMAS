<?php

/**

 * Script para actualizar datos de Comodato con UTF-8 correcto

 */



try {

    // Conexión a la base de datos

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



    // Actualizar departamento Comodato (ID 13)

    $stmt = $pdo->prepare("

        UPDATE departamentos 

        SET nombre = :nombre,

            descripcion = :descripcion,

            icono_fontawesome = :icono_fontawesome,

            email_contacto = :email_contacto

        WHERE id = 13

    ");



    $stmt->execute([

        'nombre' => 'Oficina de Comodatos',

        'descripcion' => 'Información y tramitación sobre la administración de bienes inmuebles municipales y bienes nacionales de uso público.',

        'icono_fontawesome' => 'fas fa-file-contract',

        'email_contacto' => 'comodato@municipalidadarica.cl'

    ]);



    echo "✓ Departamento Comodato actualizado\n";



    // Actualizar trámite de Comodato

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

            icono_bootstrap = :icono_bootstrap

        WHERE slug = 'comodato' AND departamento_id = 13

    ");



    $stmt->execute([

        'nombre' => 'Solicitud de Comodato',

        'descripcion_corta' => 'Tramitación de solicitud para uso de bienes municipales o nacionales de uso público, con duración de hasta 5 años.',

        'descripcion_larga' => 'La Oficina de Comodatos es responsable de la administración de todos los bienes inmuebles municipales, así como también de los bienes nacionales de uso público. Los comodatos deben ser aprobados por el Honorable Concejo Municipal y el Alcalde, mientras que para los BNUP se requiere la dictación de un Decreto Municipal. Ambos tienen una duración de 5 años.',

        'documentos_requeridos' => "• Carta dirigida al Alcalde solicitando comodato\n• Formulario de Solicitud de Comodato (disponible en la oficina)\n• Personalidad Jurídica vigente (certificado emitido hace máximo 90 días)\n• Fotocopias de cédulas de identidad de todos los miembros de la directiva\n• Certificados de antecedentes de todos los miembros de la directiva\n• Copia actualizada de los estatutos de la organización",

        'requisitos_usuario' => "• Contar con personalidad jurídica y directiva vigentes (certificado emitido hace máximo 90 días)\n• Presentar solicitud con al menos 90 días de anticipación a la fecha de inicio del comodato solicitado",

        'instrucciones_paso_paso' => "1. Cumplir con los requisitos de admisibilidad establecidos en el Reglamento de Otorgamiento en Comodato de Bienes Municipales (Decreto Alcaldicio N°1500 del 14 de agosto de 2020)\n2. Reunir y presentar toda la documentación requerida en la Oficina de Comodatos\n3. Ingreso de antecedentes a evaluación por las direcciones vinculadas (Asesoría Jurídica, Administración y Finanzas, Obras Municipales, DIDECO)",

        'tiempo_estimado' => 'Entre 60 y 90 días hábiles',

        'responsable_nombre' => 'Oficina de Comodatos (La información proporcionada no tiene carácter vinculante ni resolutivo)',

        'icono_bootstrap' => 'bi bi-building'

    ]);



    echo "✓ Trámite de Comodato actualizado\n\n";



    // Verificar los datos actualizados

    echo "=== DATOS ACTUALIZADOS ===\n\n";



    $stmt = $pdo->query("SELECT * FROM departamentos WHERE id = 13");

    $dept = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "Departamento:\n";

    echo "  Nombre: {$dept['nombre']}\n";

    echo "  Descripción: {$dept['descripcion']}\n";

    echo "  Email: {$dept['email_contacto']}\n\n";



    $stmt = $pdo->query("SELECT * FROM tramites WHERE slug = 'comodato' AND departamento_id = 13");

    $tramite = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "Trámite:\n";

    echo "  Nombre: {$tramite['nombre']}\n";

    echo "  Descripción corta: {$tramite['descripcion_corta']}\n";

    echo "  Tiempo: {$tramite['tiempo_estimado']}\n";



    echo "\n✅ Proceso completado exitosamente\n";



} catch (PDOException $e) {

    echo "❌ Error: " . $e->getMessage() . "\n";

}
