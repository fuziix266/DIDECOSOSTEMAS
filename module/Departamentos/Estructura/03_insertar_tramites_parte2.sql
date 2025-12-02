-- =====================================================
-- SCRIPT DE INSERCIÓN DE TRÁMITES - DIDECO (PARTE 2)
-- Base de datos: dideco
-- Continuación de trámites restantes
-- =====================================================

USE dideco;

-- =====================================================
-- 7. MUJER Y EQUIDAD DE GÉNERO (11 servicios)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(7, 'Taller de Emprendimiento', 'emprendimiento',
'Formación para iniciar o fortalecer negocios liderados por mujeres.',
'bi-lightbulb', 'taller', 1, 1),

(7, 'Curso de Capacitación', 'capacitacion',
'Formación en herramientas digitales, inteligencia artificial, agricultura y más.',
'bi-journal-bookmark', 'taller', 2, 1),

(7, 'Terapias Complementarias', 'terapias',
'Atención con masoterapia y flores de Bach para mujeres víctimas de violencia.',
'bi-flower2', 'servicio', 3, 1),

(7, 'Taller de Prevención de la Violencia', 'pervencion',
'Autocuidado, autoestima y resignificación de experiencias de violencia.',
'bi-shield-plus', 'taller', 4, 1),

(7, 'Orientación Jurídica', 'orientacion',
'Asesoría en temas legales vinculados a violencia de género y derecho familiar.',
'bi-journal-check', 'asesoria', 5, 1),

(7, 'Análisis de Causas Judiciales', 'causasjudiciales',
'Revisión y explicación de causas judiciales vigentes.',
'bi-file-earmark-text', 'asesoria', 6, 1),

(7, 'Retención Fondos Bancarios/AFP', 'deudorespension',
'Apoyo en recuperación de pensiones de alimentos desde bancos o AFP.',
'bi-bank', 'asesoria', 7, 1),

(7, 'Presentación de Escritos', 'escritos',
'Redacción y envío de escritos judiciales relacionados a causas de familia.',
'bi-pencil-square', 'asesoria', 8, 1),

(7, 'Demanda Autorización de Salida del País', 'autorizaciones',
'Asesoría y seguimiento para autorización de viajes de NNA.',
'bi-pass', 'asesoria', 9, 1),

(7, 'Acompañamiento a Denuncias', 'denuncias',
'Apoyo presencial para interponer denuncias ante instituciones competentes.',
'bi-person-lines-fill', 'servicio', 10, 1),

(7, 'Festival Mujeres Creadoras', 'festival',
'Evento artístico y cultural para visibilizar el talento de mujeres locales.',
'bi-stars', 'actividad', 11, 1);

-- =====================================================
-- 8. ODIMA (7 servicios)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(8, 'Acreditación Indígena', 'acreditacionindigena',
'Asesoramiento completo para obtener tu acreditación indígena y acceder a beneficios específicos.',
'bi-award', 'tramite', 1, 1),

(8, 'Beca Indígena', 'becaindigena',
'Orientación para postular a becas educacionales destinadas a estudiantes indígenas.',
'bi-mortarboard', 'orientacion', 2, 1),

(8, 'Conformar Asociación Indígena', 'informacionasociacionindigena',
'Información detallada sobre cómo formar una asociación indígena y sus beneficios.',
'bi-people-fill', 'orientacion', 3, 1),

(8, 'Inscribir Asociación Indígena', 'inscripcionasociacionindigena',
'Asesoramiento para el proceso de inscripción oficial de tu asociación indígena.',
'bi-journal-plus', 'tramite', 4, 1),

(8, 'Micro Emprendimiento CONADI', 'microemprendimiento',
'Orientación para postular a fondos de micro emprendimiento indígena de CONADI.',
'bi-shop', 'orientacion', 5, 1),

(8, 'Subsidio Habitacional', 'subsidiohabitacional',
'Información sobre subsidios habitacionales específicos para pueblos indígenas.',
'bi-house-heart', 'orientacion', 6, 1),

(8, 'Actividades Culturales Indígenas', 'actividadestematicas',
'Participa en actividades, ceremonias y eventos que celebran y fortalecen la cultura indígena.',
'bi-calendar-event', 'actividad', 7, 1);

-- =====================================================
-- 9. AFRODESCENDIENTES (4 trámites)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(9, 'Apoyo a Organizaciones Afrodescendientes', 'organizaciones',
'Colaboración logística y organizativa para actividades y eventos impulsados por agrupaciones afro.',
'bi-people-fill', 'servicio', 1, 1),

(9, 'Inscripción Emprendedora', 'emprendimiento',
'Registro de emprendimientos liderados por personas afrodescendientes para futuras convocatorias o actividades.',
'bi-clipboard-check', 'tramite', 2, 1),

(9, 'Catastro Afrodescendiente', 'catastro',
'Inscripción voluntaria para recibir información sobre eventos, actividades y programas municipales.',
'bi-journal-text', 'tramite', 3, 1),

(9, 'Inscripción a Talleres', 'talleres',
'Participa en los diversos talleres formativos y culturales organizados por la oficina para la comunidad afro.',
'bi-easel3', 'taller', 4, 1);

-- =====================================================
-- 10. JUVENTUD (4 servicios)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(10, 'Voluntariado Juvenil', 'voluntariado',
'Participa en operativos comunitarios, medioambientales y de emergencia con formación especializada.',
'bi-people', 'servicio', 1, 1),

(10, 'Iniciativas Comunitarias', 'apoyoiniciativas',
'Apoyo a proyectos impulsados por agrupaciones juveniles con o sin personalidad jurídica.',
'bi-lightbulb', 'servicio', 2, 1),

(10, 'Fortalecimiento Centros de Alumnos', 'centrosdealumnos',
'Capacitaciones en liderazgo, salud mental y gestión de emergencias para estudiantes secundarios.',
'bi-person-lines-fill', 'taller', 3, 1),

(10, 'Aprende y Emprende', 'aprendeyemprende',
'Cursos de barbería y maquillaje para jóvenes en situación de vulnerabilidad.',
'bi-tools', 'taller', 4, 1);

-- =====================================================
-- 11. DISCAPACIDAD (18 servicios)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(11, 'Terapia Ocupacional', 'terapiaocupacional',
'Atención y talleres de terapia ocupacional para personas en situación de discapacidad.',
'bi-people', 'servicio', 1, 1),

(11, 'Terapia Kinésica', 'terapiakinesica',
'Terapias kinésicas personalizadas en sala de rehabilitación integral.',
'bi-person-walking', 'servicio', 2, 1),

(11, 'Taller de Habla y Lenguaje', 'terapiafonoaudiologia',
'Terapias fonoaudiológicas individuales y grupales para mejorar la comunicación.',
'bi-chat-dots', 'servicio', 3, 1),

(11, 'Taller Deportivo Recreativo', 'tallerdeportivo',
'Actividades físicas adaptadas a distintos tipos de discapacidad.',
'bi-activity', 'taller', 4, 1),

(11, 'Préstamo de Ayuda Técnica', 'ayudatecnica',
'Sillas de ruedas, bastones y otras ayudas técnicas en modalidad de préstamo.',
'bi-box', 'servicio', 5, 1),

(11, 'Devolución de Ayuda Técnica', 'devolucionayudatecnica',
'Proceso formal para devolver ayudas técnicas previamente entregadas.',
'bi-arrow-counterclockwise', 'tramite', 6, 1),

(11, 'Informe de Ayuda Social', 'ayudasocial',
'Solicitud de gift card mediante evaluación social y requisitos específicos.',
'bi-file-earmark-text', 'tramite', 7, 1),

(11, 'Informe Social y Redes de Apoyo', 'informesocialyredes',
'Trámite para obtener la credencial de discapacidad con respaldo profesional.',
'bi-diagram-3', 'tramite', 8, 1),

(11, 'Informe ORASMI', 'orasmi',
'Gestión de ayuda social a través de informes dirigidos a entidades del Estado.',
'bi-bank', 'tramite', 9, 1),

(11, 'Informe de Ayudas Técnicas', 'informeayudatecnica',
'Informe profesional para obtención de ayudas técnicas según diagnóstico.',
'bi-tools', 'tramite', 10, 1),

(11, 'Informes para Tribunales', 'tribunales',
'Informes sociales requeridos por tribunales en causas judiciales vigentes.',
'bi-clipboard-check', 'tramite', 11, 1),

(11, 'Informe para Compras de Ayudas Técnicas', 'comprasayudastecnicas',
'Solicitudes al Departamento Social cuando no hay stock en la oficina.',
'bi-cart4', 'tramite', 12, 1),

(11, 'Subsidio de Discapacidad para Menores', 'subsidiomenores',
'Orientación sobre beneficio monetario a menores con discapacidad severa.',
'bi-emoji-smile', 'orientacion', 13, 1),

(11, 'Orientación sobre Estipendio', 'tipendio',
'Apoyo a cuidadores de personas con dependencia severa sin ingresos.',
'bi-person-hearts', 'orientacion', 14, 1),

(11, 'Pensión Básica Solidaria de Invalidez', 'pensionbasicainvalidez',
'Orientación para solicitar PBSI a personas declaradas inválidas sin pensión.',
'bi-cash', 'orientacion', 15, 1),

(11, 'Beneficios Semestrales Ley 20.422', 'beneficiossemestrales',
'Llamados especiales para subsidios de arriendo y otros beneficios focalizados.',
'bi-calendar-week', 'beneficio', 16, 1),

(11, 'Solicitud IVADEC', 'ivadec',
'Aplicación de batería de preguntas para acreditación del porcentaje de discapacidad.',
'bi-ui-checks', 'tramite', 17, 1),

(11, 'Postulación a Ayudas Técnicas SENADIS', 'senadis',
'Gestión de postulaciones a SENADIS durante períodos oficiales.',
'bi-upload', 'tramite', 18, 1);

-- =====================================================
-- 12. GESTIÓN HABITACIONAL (6 trámites)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(12, 'Organizaciones Funcionales', 'comitevivienda',
'Constitución de comités de vivienda según Decreto Ley 19.418, con presencia de ministro de fe.',
'bi-building', 'tramite', 1, 1),

(12, 'Junta de Vecinos', 'juntavecinal',
'Constitución de organizaciones territoriales bajo Ley 19.418, con requisitos por zona urbana o rural.',
'bi-people', 'tramite', 2, 1),

(12, 'Fundación (Ley 20.500)', 'fundacion',
'Trámite para constitución de fundaciones según normativa vigente.',
'bi-journal-text', 'tramite', 3, 1),

(12, 'ONG / Corporación / Asociación Cultural', 'ong',
'Apoyo para la constitución de organizaciones civiles sin fines de lucro.',
'bi-file-earmark-person', 'tramite', 4, 1),

(12, 'Asociación de Adultos Mayores', 'adultomayor',
'Apoyo a la formalización de asociaciones de clubes de adultos mayores.',
'bi-person-arms-up', 'tramite', 5, 1),

(12, 'Centro de Padres y Apoderados', 'padresyapoderados',
'Formalización de centros de padres en establecimientos de enseñanza media.',
'bi-person-vcard', 'tramite', 6, 1);

-- =====================================================
-- 13. COMODATOS (1 trámite)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(13, 'Solicitud de Comodato', 'comodato',
'Tramitación de solicitud para uso de bienes municipales o nacionales de uso público, con duración de hasta 5 años.',
'bi-building', 'tramite', 1, 1);

-- =====================================================
-- 14. OMIL (7 trámites)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(14, 'Inscripción o Actualización de Datos', 'inscripcion',
'Registro de datos de usuarios en base comunal OMIL para acceder a ofertas laborales.',
'bi-person-check', 'tramite', 1, 1),

(14, 'Postulación a Oferta Laboral', 'postulacion',
'Acceso a ofertas laborales vigentes publicadas en muniarica.cl u oficina OMIL.',
'bi-laptop', 'tramite', 2, 1),

(14, 'Certificado de Cesantía', 'certificadocesantia',
'Documento para acreditar condición de cesantía con fines administrativos.',
'bi-file-earmark-text', 'certificado', 3, 1),

(14, 'Certificado de Inscripción', 'certificadoinscripcion',
'Constancia de inscripción del usuario en la base de datos de OMIL.',
'bi-person-lines-fill', 'certificado', 4, 1),

(14, 'Certificado Usuario con Discapacidad', 'solicituddiscapacidad',
'Documento requerido para postular a programas de emprendimiento.',
'bi-universal-access', 'certificado', 5, 1),

(14, 'Actualización por Trámite de Cesantía Solidaria', 'cesantiasolidaria',
'Apoyo en actualización de datos en la Bolsa Nacional de Empleo (BNE).',
'bi-arrow-repeat', 'tramite', 6, 1),

(14, 'Solicitud de Requerimiento de Personal', 'requerimiento',
'Permite a empleadores levantar ofertas laborales a través de OMIL.',
'bi-person-plus', 'tramite', 7, 1);

-- =====================================================
-- 15. DERECHOS HUMANOS (8 trámites)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(15, 'Vigencia Cédula de Identidad', 'vigenciacedula',
'Asesoría sobre vigencia de documentos y acceso a información en plataforma de Migraciones.',
'bi-person-vcard', 'orientacion', 1, 1),

(15, 'Permanencia Transitoria', 'permanenciatransitoria',
'Consulta y seguimiento a trámites de migrantes con residencia transitoria.',
'bi-globe', 'orientacion', 2, 1),

(15, 'Residencia Temporal', 'residenciatemporal',
'Orientación para obtención de residencia temporal.',
'bi-hourglass-split', 'orientacion', 3, 1),

(15, 'Residencia Definitiva', 'residenciadefinitiva',
'Apoyo para completar proceso de regularización migratoria definitiva.',
'bi-check-circle', 'orientacion', 4, 1),

(15, 'Nacionalización', 'nacionalizacion',
'Orientación para proceso de obtención de nacionalidad chilena.',
'bi-flag', 'orientacion', 5, 1),

(15, 'Capacitaciones Migraciones', 'capacitacion',
'Talleres y charlas de formación en derechos humanos y migraciones.',
'bi-easel', 'taller', 6, 1),

(15, 'Atención Personas LGBTQIANB+', 'lgbt',
'Orientación a personas LGBTQIANB+ en situación de vulnerabilidad.',
'bi-gender-ambiguous', 'servicio', 7, 1),

(15, 'Actividades Diversidad Sexual', 'diversidadsexual',
'Actividades de formación, entrega de kits y jornadas de conmemoración.',
'bi-heart', 'actividad', 8, 1);

-- =====================================================
-- 16. DEFENSORÍA CIUDADANA (1 servicio)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(16, 'Orientación Legal', 'orientacionlegal',
'Ofrece orientación legal gratuita en diversas áreas del derecho.',
'bi-balance-scale', 'asesoria', 1, 1);

-- =====================================================
-- 17. PRESUPUESTO PARTICIPATIVO (3 programas)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(17, 'FONDEVE', 'fondeve',
'Fondo de desarrollo vecinal dirigido a organizaciones territoriales como juntas de vecinos y uniones comunales.',
'bi-cash-coin', 'tramite', 1, 1),

(17, 'FONDECO', 'fondeco',
'Fondo de desarrollo comunitario para organizaciones funcionales como clubes deportivos y de adultos mayores.',
'bi-piggy-bank', 'tramite', 2, 1),

(17, 'Presupuesto Participativo', 'presupuestoparticipativo',
'Proceso de elección ciudadana para decidir en qué proyectos invertir fondos comunales en distintas zonas.',
'bi-bar-chart-steps', 'tramite', 3, 1);

-- =====================================================
-- VERIFICACIÓN DE DATOS INSERTADOS
-- =====================================================

SELECT 
    'Trámites insertados correctamente' as mensaje,
    COUNT(*) as total_tramites
FROM tramites;

SELECT 
    d.nombre as departamento,
    COUNT(t.id) as total_tramites
FROM departamentos d
LEFT JOIN tramites t ON d.id = t.departamento_id
GROUP BY d.id, d.nombre
ORDER BY d.orden;

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================
