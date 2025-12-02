-- =====================================================
-- SCRIPT DE INSERCIÓN DE DEPARTAMENTOS - DIDECO
-- Base de datos: dideco
-- Fecha: 18 de noviembre de 2025
-- Descripción: Datos de los 17 departamentos de DIDECO
--              extraídos desde las vistas .phtml
-- =====================================================

USE dideco;

-- =====================================================
-- DEPARTAMENTOS PRINCIPALES
-- =====================================================

INSERT INTO departamentos (nombre, slug, descripcion, icono_bootstrap, icono_fontawesome, color_primario, color_secundario, email_contacto, mensaje_contacto, orden, activo) VALUES

-- 1. Enlace Norte
('Enlace Norte - Servicios DIDECO', 'enlacenorte', 
'Accede a todos los servicios de desarrollo social, subsidios, pensiones y beneficios disponibles para las familias del sector norte de nuestra comuna.',
'bi-signpost-2', 'fas fa-compass', '#15356c', '#6537b6',
'enlacenorte@municipalidadarica.cl',
'El equipo de Enlace Norte está disponible para orientarte y acompañarte en tu proceso.',
1, 1),

-- 2. Acción Social
('Servicios de Acción Social', 'serviciosocial',
'Brindamos apoyo integral a personas y familias en situación de vulnerabilidad a través de diversos beneficios sociales y programas de asistencia municipal.',
'bi-people-fill', 'fas fa-hands-helping', '#15356c', '#6537b6',
'serviciosocial@municipalidadarica.cl',
'El equipo de la Oficina de Servicios Sociales está disponible para orientarte y acompañarte en tu proceso.',
2, 1),

-- 3. Adulto Mayor (OCAM)
('Servicios para Personas Mayores', 'ocam',
'La Oficina Comunal del Adulto Mayor ofrece orientación y servicios para mejorar la calidad de vida de personas mayores de nuestra comuna.',
'bi-person-arms-up', 'fas fa-user-clock', '#15356c', '#6537b6',
'oficinaadultomayor@municipalidadarica.cl',
'El equipo de la Oficina Comunal del Adulto Mayor está disponible para brindarte apoyo y asesoramiento.',
3, 1),

-- 4. RSH
('Registro Social de Hogares', 'rsh',
'Gestiona la incorporación o actualización de tu hogar en el RSH para acceder a beneficios sociales del Estado.',
'bi-people', 'bi bi-people-fill', '#15356c', '#6537b6',
'registrosocialhogares@municipalidadarica.cl',
'El equipo de la Oficina de Registro Social de Hogares está disponible para orientarte y acompañarte en tu proceso.',
4, 1),

-- 5. Subsidio y Pensiones
('Subsidios y Pensiones', 'subsidioypensiones',
'Información sobre beneficios económicos disponibles para personas en situación de vulnerabilidad, incluyendo subsidios familiares, pensiones y ayudas para el pago de servicios básicos.',
'bi-cash-coin', 'fas fa-hand-holding-usd', '#15356c', '#6537b6',
'oficina.subsidios@municipalidadarica.cl',
'El equipo de la Oficina de Subsidios y Pensiones está disponible para orientarte y acompañarte en tu proceso.',
5, 1),

-- 6. Oficina Local de la Niñez (OLN)
('Oficina Local de la Niñez (OLN)', 'oln',
'Accede a los trámites y servicios orientados a la protección y promoción de los derechos de niños, niñas y adolescentes.',
'bi-person-hearts', 'fas fa-child', '#15356c', '#e67e22',
'oln@municipalidadarica.cl',
'El equipo de la Oficina Local de la Niñez está disponible para orientarte y acompañarte en tu proceso.',
6, 1),

-- 7. Mujer y Equidad de Género
('Servicios Oficina de la Mujer', 'mujeryequidad',
'Promovemos la equidad de género, el empoderamiento y el bienestar de las mujeres en Arica, a través de programas de formación, orientación y acompañamiento.',
'bi-gender-female', 'fas fa-venus', '#9c2d6a', '#e83e8c',
'oficinamujer.eyg@municipalidadarica.cl',
'El equipo de la Oficina de la Mujer y Equidad de Género está disponible para orientarte y acompañarte en tu proceso.',
7, 1),

-- 8. ODIMA
('Servicios para Pueblos Indígenas', 'odima',
'Ofrecemos orientación y asesoramiento especializado para el fortalecimiento de la identidad cultural y el desarrollo de los pueblos indígenas en nuestra comuna.',
'bi-globe-americas', 'fas fa-feather-alt', '#15356c', '#6537b6',
'desarrollo.indigena@municipalidadarica.cl',
'El equipo de ODIMA está disponible para orientarte y acompañarte en tu proceso.',
8, 1),

-- 9. Afrodescendientes
('Oficina Afrodescendiente', 'afrodescendientes',
'Apoyo, visibilización e impulso de los derechos, actividades y reconocimiento del pueblo tribal afrodescendiente chileno.',
'bi-person-badge', 'fas fa-globe-africa', '#15356c', '#6537b6',
'afrodescendiente@municipalidadarica.cl',
'Visítanos y conoce todos los servicios que la municipalidad ofrece para visibilizar y promover tus derechos y cultura.',
9, 1),

-- 10. Juventud
('Oficina Municipal de la Juventud', 'juventud',
'Servicios, capacitaciones y programas dirigidos a fortalecer las oportunidades y el desarrollo de los y las jóvenes de Arica.',
'bi-lightning-charge', 'fas fa-user-graduate', '#15356c', '#2ecc71',
'juventud@municipalidadarica.cl',
'Visita la Oficina Municipal de la Juventud y descubre todas las oportunidades disponibles para ti.',
10, 1),

-- 11. Discapacidad
('Oficina de la Discapacidad', 'discapacidad',
'Servicios de atención y apoyo a personas en situación de discapacidad, promoviendo la inclusión y el acceso a derechos.',
'bi-person-wheelchair', 'fas fa-wheelchair', '#15356c', '#6537b6',
'discapacidad@municipalidadarica.cl',
'El equipo de la Oficina de la Discapacidad está disponible para orientarte y acompañarte en tu proceso.',
11, 1),

-- 12. Gestión Habitacional
('Organizaciones de Vivienda', 'gestionhabitacional',
'Trámites relacionados a la constitución y formalización de organizaciones funcionales y territoriales vinculadas a vivienda y participación ciudadana.',
'bi-building', 'fas fa-home', '#15356c', '#3498db',
'dideco@municipalidadarica.cl',
'El equipo de la Oficina de Gestión Habitacional está disponible para orientarte y acompañarte en tu proceso.',
12, 1),

-- 13. Comodatos
('Oficina de Comodatos', 'comodato',
'Información y tramitación sobre la administración de bienes inmuebles municipales y bienes nacionales de uso público.',
'bi-file-earmark-text', 'fas fa-file-contract', '#15356c', '#2c3e50',
'comodato@municipalidadarica.cl',
'El equipo de la Oficina de Comodatos está disponible para orientarte y acompañarte en tu proceso.',
13, 1),

-- 14. OMIL
('Oficina Municipal de Intermediación Laboral (OMIL)', 'omil',
'Accede a los servicios que ofrece la OMIL para la inscripción, postulación laboral y certificaciones.',
'bi-briefcase', 'fas fa-briefcase', '#15356c', '#2980b9',
'omil@municipalidadarica.cl',
'El equipo de la Oficina Municipal de Información Laboral (OMIL) está disponible para orientarte y acompañarte en tu proceso.',
14, 1),

-- 15. Derechos Humanos
('Oficina Municipal de Derechos Humanos', 'derechoshumanos',
'Trámites y servicios vinculados a la orientación, regularización migratoria, diversidad sexual y promoción de derechos humanos.',
'bi-person-check', 'fas fa-handshake', '#15356c', '#e67e22',
'derechoshumanos@municipalidadarica.cl',
'El equipo de la Oficina Municipal de Derechos Humanos está disponible para orientarte y acompañarte en tu proceso.',
15, 1),

-- 16. Defensoría Ciudadana
('Defensoría Ciudadana', 'defensoriaciudadana',
'Ofrece orientación legal gratuita en diversas áreas del derecho.',
'bi-shield-check', NULL, '#15356c', '#6537b6',
'defensoria@municipalidadarica.cl',
'El equipo de la Defensoría Ciudadana está disponible para orientarte y acompañarte en tu proceso.',
16, 1),

-- 17. Presupuesto Participativo
('Presupuesto Participativo', 'presupuestoparticipativo',
'Accede a los fondos y programas de apoyo a organizaciones comunitarias, territoriales y funcionales.',
'bi-cash-coin', 'fas fa-handshake', '#15356c', '#2d9cdb',
'fondos.participativos@municipalidadarica.cl',
'Cada programa tiene procesos de capacitación, postulación y rendición específicos. Asegúrate de revisar los detalles antes de postular.',
17, 1);

-- =====================================================
-- VERIFICACIÓN DE DATOS INSERTADOS
-- =====================================================

SELECT 
    'Departamentos insertados correctamente' as mensaje,
    COUNT(*) as total_departamentos
FROM departamentos;

SELECT 
    id,
    nombre,
    slug,
    email_contacto,
    orden
FROM departamentos
ORDER BY orden;

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================
