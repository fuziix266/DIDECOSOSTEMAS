<?php
$pdo = new PDO('mysql:host=localhost;dbname=dideco;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$icons = [
    'devolucion-de-ayuda-tecnica' => 'bi bi-arrow-counterclockwise',
    'informes-de-ayudas-tecnicas' => 'bi bi-file-earmark-medical',
    'informes-sociales-para-compras-de-ayudas-tecnicas' => 'bi bi-file-earmark-text',
    'infornes-para-tribunales' => 'bi bi-building',
    'orientacion-sobre-el-estipendio' => 'bi bi-cash',
    'orientacion-sobre-el-subsidio-de-discapacidad-para-menores-de-18-anos' => 'bi bi-person-fill-down',
    'orientacion-sobre-la-pension-basica-solidaria-de-invalidez-pbsi' => 'bi bi-wallet2',
    'orientaciones-generales-sobre-beneficios-semestrales-del-estado-contemplados-ante-la-ley-20-422' => 'bi bi-info-circle',
    'solicitud-de-apoyo-en-postulacion-de-ayudas-tecnicas-a-senadis' => 'bi bi-clipboard-check',
    'solicitud-informe-ayuda-social' => 'bi bi-file-text',
    'solicitud-informe-orasmi' => 'bi bi-file-earmark-person',
    'solicitud-informe-social-y-redes-de-apoyo' => 'bi bi-people',
    'solicitud-ivadec' => 'bi bi-person-badge',
    'solicitud-prestamo-de-ayuda-tecnica' => 'bi bi-tools',
    'solicitud-taller-de-habla-y-lenguaje' => 'bi bi-chat-quote',
    'solicitud-taller-deportivo-recreativo' => 'bi bi-bicycle',
    'solicitud-terapia-kinesica' => 'bi bi-activity',
    'solicitud-terapia-ocupacional' => 'bi bi-puzzle'
];

$stmt = $pdo->prepare("UPDATE tramites SET icono_bootstrap = :icon WHERE slug = :slug AND departamento_id = 11");

foreach ($icons as $slug => $icon) {
    $stmt->execute(['icon' => $icon, 'slug' => $slug]);
    if ($stmt->rowCount() > 0) {
        echo "Updated $slug -> $icon\n";
    } else {
        echo "No match for $slug\n";
    }
}
