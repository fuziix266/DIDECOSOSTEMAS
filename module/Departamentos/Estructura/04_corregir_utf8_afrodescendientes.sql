-- =====================================================
-- SCRIPT DE CORRECCIÓN DE CODIFICACIÓN UTF-8
-- Actualiza los datos de Afrodescendientes con acentos correctos
-- =====================================================

USE dideco;

-- Actualizar trámites de Afrodescendientes
UPDATE tramites SET 
    nombre = 'Apoyo a Organizaciones Afrodescendientes',
    descripcion_corta = 'Colaboración logística y organizativa para actividades y eventos impulsados por agrupaciones afro.',
    descripcion_larga = 'Se entrega apoyo mayormente logístico a organizaciones y agrupaciones en actividades realizadas por ellos, donde se solicita a la oficina colaboración.',
    documentos_requeridos = '• Agrupaciones y organizaciones afrodescendientes: personalidad jurídica vigente.\n• Personas: carta de respaldo de organización afrodescendiente y copia de la personalidad jurídica vigente de quien lo respalda.\n• Carta de invitación, de ser necesario.',
    requisitos_usuario = 'Ser afrodescendiente chileno, bajo la ley 21.151.',
    instrucciones_paso_paso = 'Traer documentación y propuesta.',
    tiempo_estimado = 'Entre 2 y 3 días hábiles.',
    responsable_nombre = 'Lorna Llerena'
WHERE slug = 'organizaciones' AND departamento_id = 9;

UPDATE tramites SET 
    nombre = 'Inscripción Emprendedora',
    descripcion_corta = 'Registro de emprendimientos liderados por personas afrodescendientes para futuras convocatorias o actividades.',
    descripcion_larga = 'Registro voluntario de emprendedoras/es afrodescendientes que deseen visibilizar sus iniciativas y acceder a futuras convocatorias, talleres u oportunidades de difusión desde la oficina.',
    documentos_requeridos = 'No se requiere documentación.',
    requisitos_usuario = 'Ser afrodescendiente chileno, bajo la ley 21.152.',
    instrucciones_paso_paso = 'Asistir a la oficina, llenar la ficha de emprendimiento.',
    tiempo_estimado = 'Entre 1 y 2 días hábiles.',
    responsable_nombre = 'Nicole Serrano'
WHERE slug = 'emprendimiento' AND departamento_id = 9;

UPDATE tramites SET 
    nombre = 'Catastro Afrodescendiente',
    descripcion_corta = 'Inscripción voluntaria para recibir información sobre eventos, actividades y programas municipales.',
    descripcion_larga = 'Inscripción en catastro interno de personas afrodescendientes que pertenecen al pueblo. Esta información se utiliza para enviar invitaciones e información de actividades realizadas por la oficina y la municipalidad, además de eventos organizados por agrupaciones afrodescendientes.',
    documentos_requeridos = 'Cédula de identidad, certificados o fotografías que respalden el tronco familiar afrodescendiente.',
    requisitos_usuario = 'Ser afrodescendiente chileno, bajo la ley 21.153.',
    instrucciones_paso_paso = 'Asistir a la Oficina con los documentos requeridos y solicitar la inscripción en el catastro.',
    tiempo_estimado = 'Inscripción instantánea.',
    responsable_nombre = 'Todo el equipo'
WHERE slug = 'catastro' AND departamento_id = 9;

UPDATE tramites SET 
    nombre = 'Inscripción a Talleres',
    descripcion_corta = 'Participa en los diversos talleres formativos y culturales organizados por la oficina para la comunidad afro.',
    descripcion_larga = 'Inscripción en talleres generados por la oficina, dirigidos exclusivamente a personas afrodescendientes reconocidas bajo la ley 21.154.',
    documentos_requeridos = 'No requiere documentos.',
    requisitos_usuario = 'Ser afrodescendiente chileno, bajo la ley 21.154.',
    instrucciones_paso_paso = 'Enviar correo electrónico con los datos solicitados a la Oficina Afrodescendiente.',
    tiempo_estimado = 'Entre 1 y 2 días hábiles.',
    responsable_nombre = 'Todo el equipo'
WHERE slug = 'talleres' AND departamento_id = 9;

-- Verificar resultados
SELECT 
    nombre, 
    descripcion_corta,
    CHAR_LENGTH(documentos_requeridos) as docs_len,
    CHAR_LENGTH(requisitos_usuario) as req_len,
    CHAR_LENGTH(instrucciones_paso_paso) as pasos_len
FROM tramites 
WHERE departamento_id = 9
ORDER BY orden;
