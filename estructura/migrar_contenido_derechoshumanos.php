<?php

$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "MIGRACIÓN DE CONTENIDO - DERECHOS HUMANOS\n";
echo str_repeat("=", 80) . "\n\n";

try {
    // Contenido detallado para cada trámite de Derechos Humanos
    $contenidos = [
        'vigenciacedula' => [
            'descripcion_larga' => 'Orientación sobre vigencia de la cédula de identidad, plazos de renovación y acceso a información en la plataforma ChileAtiende para gestionar documentación oficial.',
            'requisitos_usuario' => "• Cédula de identidad vigente o vencida\n• Datos personales actualizados",
            'instrucciones_paso_paso' => "1. Acercarse a la Oficina Municipal de Derechos Humanos\n2. Exponer su consulta al funcionario de turno\n3. Recibir orientación sobre el estado de su documento\n4. Acceder a información sobre renovación en ChileAtiende",
            'tiempo_estimado' => '15-30 minutos',
            'responsable_nombre' => 'Oficina Municipal de Derechos Humanos',
            'responsable_cargo' => 'Encargado/a de Atención'
        ],
        'permanenciatransitoria' => [
            'descripcion_larga' => 'Asesoría y seguimiento a trámites relacionados con la permanencia transitoria de migrantes en Chile. Se entrega orientación sobre documentación necesaria y procesos ante el Servicio Nacional de Migraciones.',
            'requisitos_usuario' => "• Pasaporte vigente\n• Documento que acredite ingreso legal al país\n• Certificado de antecedentes de país de origen (apostillado o legalizado)\n• Comprobante de pago de derechos",
            'instrucciones_paso_paso' => "1. Solicitar hora de atención en la Oficina\n2. Presentar documentación requerida\n3. Recibir orientación sobre el proceso\n4. Seguimiento del trámite ante Migraciones",
            'tiempo_estimado' => '30-45 minutos por sesión',
            'responsable_nombre' => 'Oficina Municipal de Derechos Humanos',
            'responsable_cargo' => 'Encargado/a de Migraciones'
        ],
        'residenciatemporal' => [
            'descripcion_larga' => 'Orientación integral para la obtención de residencia temporal en Chile. Se asesora sobre los diferentes tipos de residencia temporal disponibles según la situación migratoria del solicitante.',
            'requisitos_usuario' => "• Pasaporte vigente\n• Certificado de antecedentes penales (apostillado)\n• Certificado de nacimiento (apostillado)\n• Contrato de trabajo o medio de vida lícito\n• Comprobante de domicilio en Chile\n• Certificado médico\n• 4 fotografías tamaño pasaporte",
            'documentos_requeridos' => "• Formulario de solicitud completado\n• Pasaporte con vigencia mínima de 6 meses\n• Certificados apostillados o legalizados\n• Comprobantes de pago",
            'instrucciones_paso_paso' => "1. Agendar cita en Oficina de Derechos Humanos\n2. Revisión de documentación\n3. Completar formularios\n4. Orientación sobre proceso en Servicio de Migraciones\n5. Seguimiento del estado del trámite",
            'tiempo_estimado' => '45-60 minutos',
            'responsable_nombre' => 'Oficina Municipal de Derechos Humanos',
            'responsable_cargo' => 'Asesor/a Legal en Migración'
        ],
        'residenciadefinitiva' => [
            'descripcion_larga' => 'Apoyo completo para gestionar el proceso de regularización migratoria definitiva. Se brinda asesoría sobre requisitos, documentación y seguimiento del trámite ante las autoridades migratorias.',
            'requisitos_usuario' => "• Residencia temporal vigente por al menos 2 años\n• Pasaporte vigente\n• Certificado de antecedentes (Chile y país de origen)\n• Certificado de residencia\n• Comprobante de medios económicos\n• Certificado médico actualizado",
            'documentos_requeridos' => "• Formulario de solicitud\n• Certificados actualizados (apostillados)\n• Declaración jurada de domicilio\n• Comprobante de pago de derechos",
            'instrucciones_paso_paso' => "1. Verificar cumplimiento de tiempo de residencia temporal\n2. Reunir documentación completa\n3. Agendar atención en la Oficina\n4. Revisión y validación de documentos\n5. Orientación sobre presentación ante Migraciones\n6. Acompañamiento en el proceso",
            'tiempo_estimado' => '1 hora',
            'responsable_nombre' => 'Oficina Municipal de Derechos Humanos',
            'responsable_cargo' => 'Asesor/a Legal en Migración'
        ],
        'nacionalizacion' => [
            'descripcion_larga' => 'Orientación detallada sobre el proceso de obtención de la nacionalidad chilena por carta de nacionalización. Se explican requisitos, plazos y procedimientos ante el Ministerio del Interior.',
            'requisitos_usuario' => "• Residencia definitiva vigente\n• 5 años de residencia continuada en Chile\n• Mayoría de edad (18 años)\n• Solvencia económica demostrable\n• Conocimientos del idioma español\n• Conocimientos básicos de historia y geografía de Chile",
            'documentos_requeridos' => "• Certificado de permanencia definitiva\n• Certificado de antecedentes\n• Certificado de residencia continuada\n• Comprobante de medios de vida\n• Fotografías tamaño carné\n• Timbre de nacionalización",
            'instrucciones_paso_paso' => "1. Verificar cumplimiento de años de residencia\n2. Solicitar certificados necesarios\n3. Agendar entrevista en la Oficina\n4. Preparación para examen de conocimientos\n5. Presentación de solicitud ante autoridades\n6. Seguimiento del proceso",
            'tiempo_estimado' => '1-2 horas (proceso puede tomar varios meses)',
            'responsable_nombre' => 'Oficina Municipal de Derechos Humanos',
            'responsable_cargo' => 'Asesor/a Legal en Nacionalización'
        ],
        'capacitacion' => [
            'descripcion_larga' => 'Talleres y charlas de formación en derechos humanos, derechos de migrantes y orientación sobre procesos migratorios. Actividades gratuitas y abiertas a la comunidad.',
            'requisitos_usuario' => "• Interés en temáticas de derechos humanos y migración\n• Inscripción previa (cuando se requiera)\n• Disponibilidad de horario",
            'instrucciones_paso_paso' => "1. Consultar calendario de capacitaciones\n2. Inscribirse en talleres de interés\n3. Asistir puntualmente a las sesiones\n4. Participar activamente\n5. Recibir certificado de asistencia (cuando aplique)",
            'tiempo_estimado' => 'Variable según taller (2-4 horas)',
            'responsable_nombre' => 'Oficina Municipal de Derechos Humanos',
            'responsable_cargo' => 'Coordinador/a de Capacitaciones',
            'observaciones' => 'Las capacitaciones se realizan periódicamente. Consultar fechas disponibles en la Oficina.'
        ],
        'lgbt' => [
            'descripcion_larga' => 'Atención especializada y orientación a personas LGBTQIANB+ en situación de vulnerabilidad, violencia o discriminación. Se brinda acompañamiento, derivación a redes de apoyo y asesoría en derechos.',
            'requisitos_usuario' => "• Identificación personal\n• Relato de la situación (opcional)",
            'instrucciones_paso_paso' => "1. Solicitar atención en la Oficina (presencial o telefónica)\n2. Entrevista confidencial con profesional especializado\n3. Evaluación de la situación\n4. Orientación sobre derechos y recursos disponibles\n5. Derivación a redes de apoyo cuando corresponda\n6. Seguimiento del caso",
            'tiempo_estimado' => '45 minutos - 1 hora',
            'responsable_nombre' => 'Oficina Municipal de Derechos Humanos',
            'responsable_cargo' => 'Profesional de Atención en Diversidad Sexual',
            'observaciones' => 'Atención confidencial y respetuosa. Se garantiza privacidad y no discriminación.'
        ],
        'diversidadsexual' => [
            'descripcion_larga' => 'Actividades de formación, sensibilización y entrega de kits informativos sobre diversidad sexual y de género. Jornadas de conmemoración de fechas relevantes como el Día del Orgullo y Día contra la Homofobia.',
            'requisitos_usuario' => "• Interés en participar en actividades de diversidad sexual\n• Inscripción previa en algunas actividades",
            'instrucciones_paso_paso' => "1. Consultar calendario de actividades en la Oficina\n2. Inscribirse cuando sea necesario\n3. Participar en jornadas y actividades\n4. Recibir material informativo\n5. Sumarse a redes comunitarias",
            'tiempo_estimado' => 'Variable según actividad',
            'responsable_nombre' => 'Oficina Municipal de Derechos Humanos',
            'responsable_cargo' => 'Coordinador/a de Actividades LGBTQIANB+',
            'observaciones' => 'Actividades abiertas a toda la comunidad. Se promueve el respeto, la inclusión y la no discriminación.'
        ]
    ];

    $actualizados = 0;
    
    foreach ($contenidos as $slug => $datos) {
        $set_clauses = [];
        $params = [':slug' => $slug, ':departamento_id' => 15];
        
        foreach ($datos as $campo => $valor) {
            $set_clauses[] = "$campo = :$campo";
            $params[":$campo"] = $valor;
        }
        
        $sql = "UPDATE tramites SET " . implode(', ', $set_clauses) . " WHERE slug = :slug AND departamento_id = :departamento_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        if ($stmt->rowCount() > 0) {
            echo "✓ Actualizado: $slug\n";
            $actualizados++;
        }
    }
    
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "TOTAL: $actualizados trámites actualizados con contenido detallado\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
