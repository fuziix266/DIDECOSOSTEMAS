<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$summaries = [
    'devolucion-de-ayuda-tecnica' => 'Trámite para devolver ayudas técnicas prestadas por SENADIS o el municipio una vez que ya no se requieren.',
    'informes-de-ayudas-tecnicas' => 'Evaluación profesional para postular a financiamiento de ayudas técnicas de SENADIS u otros organismos.',
    'informes-sociales-para-compras-de-ayudas-tecnicas' => 'Evaluación socioeconómica para gestionar la adquisición de ayudas técnicas mediante fondos municipales o externos.',
    'infornes-para-tribunales' => 'Emisión de informes sociales solicitados por tribunales para causas de familia o civiles involucrando psd.',
    'orientacion-sobre-el-estipendio' => 'Información sobre el subsidio monetario estatal destinado a cuidadores de personas con dependencia severa.',
    'orientacion-sobre-el-subsidio-de-discapacidad-para-menores-de-18-anos' => 'Guía para postular a beneficios estatales monetarios para menores de 18 años con discapacidad.',
    'orientacion-sobre-la-pension-basica-solidaria-de-invalidez-pbsi' => 'Asesoría para acceder a la Pensión Garantizada Universal o Básica Solidaria de Invalidez.',
    'orientaciones-generales-sobre-beneficios-semestrales-del-estado-contemplados-ante-la-ley-20-422' => 'Información sobre derechos, beneficios legales y franquicias para personas con discapacidad.',
    'solicitud-de-apoyo-en-postulacion-de-ayudas-tecnicas-a-senadis' => 'Asistencia técnica profesional para la postulación a programas y fondos concursables de SENADIS.',
    'solicitud-informe-ayuda-social' => 'Evaluación para acceder a beneficios municipales paliativos (alimentos, pañales, enseres básicos).',
    'solicitud-informe-orasmi' => 'Gestión de informes sociales para postular a fondos de emergencia del Ministerio del Interior (ORASMI).',
    'solicitud-informe-social-y-redes-de-apoyo' => 'Documento que acredita situación socioeconómica y activación de redes familiares para diversos trámites.',
    'solicitud-ivadec' => 'Gestión para la evaluación y certificación de discapacidad ante el COMPIN (Credencial de Discapacidad).',
    'solicitud-prestamo-de-ayuda-tecnica' => 'Solicitud de préstamo temporal de sillas de ruedas, catres clínicos u otros implementos del banco municipal.',
    'solicitud-taller-de-habla-y-lenguaje' => 'Ingreso a talleres grupales de estimulación del lenguaje y comunicación para usuarios de la oficina.',
    'solicitud-taller-deportivo-recreativo' => 'Inscripción en actividades deportivas y recreativas adaptadas para fomentar la participación social.',
    'solicitud-terapia-kinesica' => 'Atención profesional para rehabilitación física, manejo del dolor y mantención de la funcionalidad.',
    'solicitud-terapia-ocupacional' => 'Intervención profesional para promover la autonomía, independencia y habilidades de la vida diaria.'
];

$stmt = $pdo->prepare("UPDATE tramites SET descripcion_corta = :desc WHERE slug = :slug AND departamento_id = 11");

foreach ($summaries as $slug => $desc) {
    // Truncate just in case DB col is short, though typically it's text or varchar(255)
    if (strlen($desc) > 250) $desc = substr($desc, 0, 247) . '...';

    $stmt->execute(['desc' => $desc, 'slug' => $slug]);
    if ($stmt->rowCount() > 0) {
        echo "Updated $slug\n";
    } else {
        echo "No change/match for $slug\n";
    }
}
