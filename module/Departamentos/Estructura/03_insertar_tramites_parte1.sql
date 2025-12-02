-- =====================================================
-- SCRIPT DE INSERCIÓN DE TRÁMITES - DIDECO
-- Base de datos: dideco
-- Fecha: 18 de noviembre de 2025
-- Descripción: Datos de trámites y servicios de DIDECO
--              extraídos desde las vistas .phtml individuales
-- =====================================================

USE dideco;

-- =====================================================
-- TRÁMITES POR DEPARTAMENTO
-- =====================================================

-- =====================================================
-- 1. ENLACE NORTE (19 trámites)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, descripcion_larga, icono_bootstrap, tipo_tramite, orden, activo) VALUES

-- Enlace Norte - Departamento ID: 1
(1, 'Subsidio Único Familiar (SUF)', 'subsidiofamiliar',
'Beneficio económico mensual para familias en situación de vulnerabilidad social.',
'Beneficio económico que entrega el Estado para niños, niñas y adolescentes menores de 18 años.',
'bi-currency-dollar', 'subsidio', 1, 1),

(1, 'Subsidio Maternal', 'subsidiomaternal',
'Apoyo económico para mujeres embarazadas que requieren atención médica especializada.',
'Beneficio para mujeres embarazadas sin previsión social.',
'bi-heart-pulse', 'subsidio', 2, 1),

(1, 'Subsidio Recién Nacido', 'subsidioreciennacido',
'Beneficio económico destinado a cubrir gastos asociados al cuidado del recién nacido.',
'Subsidio para recién nacidos de madres beneficiarias.',
'bi-emoji-smile', 'subsidio', 3, 1),

(1, 'Subsidio Madre', 'subsidiomadre',
'Apoyo económico mensual para madres en situación de vulnerabilidad social.',
'Beneficio para madres beneficiarias del SUF por un periodo de 3 años.',
'bi-person-heart', 'subsidio', 4, 1),

(1, 'SUF Menores de 18 años', 'subsidiocesantiamenores',
'Subsidio específico para familias con causantes menores de 18 años.',
'Subsidio para niños y niñas sin previsión social según edad y condición escolar.',
'bi-people', 'subsidio', 5, 1),

(1, 'Subsidio Familiar Duplo', 'subsidiofamiliarduplo',
'Beneficio adicional para familias que califican para recibir apoyo económico reforzado.',
'Subsidio duplicado para menores con discapacidad acreditada.',
'bi-plus-circle', 'subsidio', 6, 1),

(1, 'Pensiones', 'pensiones',
'Información general sobre trámites y beneficios relacionados con pensiones.',
'Orientación sobre pensiones y beneficios previsionales.',
'bi-bank', 'orientacion', 7, 1),

(1, 'Pensión Garantizada Universal (PGU)', 'pensionuniversal',
'Beneficio previsional universal para adultos mayores de 65 años que cumplan los requisitos.',
'Apoyo a mayores de 65 años que pertenezcan al 90% más vulnerable.',
'bi-shield-check', 'pension', 8, 1),

(1, 'Pensión Básica Solidaria de Invalidez', 'pensioninvalidez',
'Pensión para personas entre 18 y 65 años con invalidez, que no tienen derecho a pensión.',
'Pensión mensual para personas sin régimen previsional con diagnóstico de invalidez.',
'bi-person-wheelchair', 'pension', 9, 1),

(1, 'Aporte Previsional Solidario de Invalidez (APSI)', 'aporteinvalidez',
'Complemento a la pensión de invalidez para personas que reciben pensiones de bajo monto.',
'Aporte solidario para personas con pensión de invalidez inferior a la PGU.',
'bi-graph-up-arrow', 'beneficio', 10, 1),

(1, 'Bono Por Hijo', 'bonoporhijo',
'Beneficio económico adicional por cada hijo para mujeres pensionadas.',
'Beneficio que incrementa los fondos previsionales de madres por cada hijo nacido.',
'bi-gift', 'beneficio', 11, 1),

(1, 'Subsidio de Discapacidad (S.D.)', 'subsidiodiscapacidad',
'Apoyo económico mensual para personas con discapacidad mental en situación de vulnerabilidad.',
'Subsidio mensual para menores con discapacidad mental, física o sensorial severa.',
'bi-universal-access', 'subsidio', 12, 1),

(1, 'Subsidios Agua Potable', 'subsidioagua',
'Subsidios para el pago del consumo de agua potable y alcantarillado para familias vulnerables.',
'Aporte al pago mensual de agua potable y alcantarillado.',
'bi-droplet', 'subsidio', 13, 1),

(1, 'SAP Rural', 'saprural',
'Subsidio para usuarios de sistemas de agua potable rural (APR).',
'Subsidio para usuarios de agua potable rural.',
'bi-tree', 'subsidio', 14, 1),

(1, 'SAP Urbano', 'sapurbano',
'Subsidio de Agua Potable para sectores urbanos con conexión domiciliaria de agua potable.',
'Subsidio de agua potable para hogares urbanos vulnerables.',
'bi-buildings', 'subsidio', 15, 1),

(1, 'Comodato', 'comodato',
'Asesoramiento para trámites de comodato y cesión de uso de bienes municipales.',
'Orientación sobre uso de bienes municipales.',
'bi-file-earmark-text', 'asesoria', 16, 1),

(1, 'Territorial Juntas Vecinales', 'juntasvecinales',
'Apoyo y orientación para la formación, gestión y fortalecimiento de juntas de vecinos.',
'Apoyo a juntas de vecinos del sector norte.',
'bi-house-door', 'asesoria', 17, 1),

(1, 'Territorial Organizaciones Funcionales', 'organizacionesfuncionales',
'Asesoramiento para organizaciones comunitarias, clubes deportivos, centros de madres y agrupaciones.',
'Apoyo a organizaciones funcionales.',
'bi-diagram-3', 'asesoria', 18, 1),

(1, 'Equipo de Logística y Emergencia', 'emergencias',
'Coordinación de apoyo logístico y respuesta ante situaciones de emergencia en el sector norte.',
'Apoyo en situaciones de emergencia.',
'bi-exclamation-triangle', 'servicio', 19, 1);

-- =====================================================
-- 2. ACCIÓN SOCIAL (8 servicios)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, descripcion_larga, icono_bootstrap, tipo_tramite, requiere_evaluacion_social, requiere_rsh, orden, activo) VALUES

(2, 'Alimentos', 'alimentos',
'Entrega de canastas familiares y/o GiftCard para cubrir necesidades alimentarias básicas.',
'Este beneficio comprende la entrega de una canasta familiar de mercadería o GiftCard (lo que se encuentre con disponibilidad).',
'bi-basket', 'servicio', 1, 1, 1, 1),

(2, 'Materiales de Construcción', 'materialesconstruccion',
'Apoyo con materiales para mejoramiento y reparación de viviendas familiares.',
'Entrega de materiales para mejoras habitacionales.',
'bi-hammer', 'servicio', 1, 1, 2, 1),

(2, 'Piezas Prefabricadas', 'piezasprefabricadas',
'Entrega de elementos prefabricados para construcción y mejoramiento habitacional.',
'Apoyo con elementos prefabricados.',
'bi-bricks', 'servicio', 1, 1, 3, 1),

(2, 'Ayuda en Materias de Salud', 'ayudasalud',
'Apoyo económico para gastos médicos, medicamentos y tratamientos de salud.',
'Apoyo para gastos de salud.',
'bi-heart-pulse', 'servicio', 1, 1, 4, 1),

(2, 'Pasajes', 'pasajes',
'Financiamiento de pasajes para traslados por motivos de salud, trabajo o emergencias.',
'Apoyo con pasajes.',
'bi-bus-front', 'servicio', 1, 1, 5, 1),

(2, 'Pago de Servicios Básicos', 'pagoservicios',
'Apoyo para el pago de servicios básicos domiciliarios como luz, agua y gas.',
'Ayuda para servicios básicos.',
'bi-receipt', 'servicio', 1, 1, 6, 1),

(2, 'Agua Potable por Camión Aljibe', 'camionaljibe',
'Servicio de abastecimiento de agua potable a través de camiones aljibe.',
'Abastecimiento de agua potable.',
'bi-truck', 'servicio', 0, 0, 7, 1),

(2, 'Canon de Arrendamiento', 'canonarrendamiento',
'Apoyo económico para el pago de arriendos en situaciones de emergencia social o vulnerabilidad habitacional.',
'Ayuda para pago de arriendo.',
'bi-house-heart', 'servicio', 1, 1, 8, 1);

-- =====================================================
-- 3. ADULTO MAYOR - OCAM (8 servicios)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(3, 'Atención Psicológica', 'atencionpsicologica',
'Sesiones de apoyo psicológico en casos de crisis o vulnerabilidad.',
'bi-emoji-smile', 'servicio', 1, 1),

(3, 'Atención Social', 'atencionsocial',
'Apoyo social personalizado para personas mayores en situación de necesidad.',
'bi-people', 'servicio', 2, 1),

(3, 'Talleres y Profesores', 'atencionprofesores',
'Participa en talleres recreativos, culturales y físicos impartidos por profesores especializados.',
'bi-journal-richtext', 'taller', 3, 1),

(3, 'Gestores en Terreno', 'gestoresenterreno',
'Solicita visitas a domicilio para resolver dudas y recibir orientación en terreno.',
'bi-person-walking', 'servicio', 4, 1),

(3, 'Uso de Salones', 'atencionsalones',
'Solicita salones para actividades y reuniones de adultos mayores.',
'bi-door-open', 'servicio', 5, 1),

(3, 'Atención Kinésica', 'atencionkinesica',
'Atención kinésica personalizada con profesionales para tu bienestar físico.',
'bi-heart-pulse', 'servicio', 6, 1),

(3, 'Clases Personalizadas', 'clasespersonalizadas',
'Organización de clases personalizadas para clubes de adulto mayor.',
'bi-calendar-check', 'taller', 7, 1),

(3, 'Asesorías en Subsidios', 'asesorias',
'Apoyo profesional para postular a subsidios y programas de beneficios.',
'bi-cash-coin', 'asesoria', 8, 1);

-- =====================================================
-- 4. RSH (1 trámite)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, es_presencial, orden, activo) VALUES

(4, 'Solicitud de Registro Social de Hogares', 'solicitud',
'Solicita la incorporación de tu hogar al sistema RSH o actualiza tus datos para acceder a beneficios.',
'bi-card-list', 'tramite', 1, 1, 1);

-- =====================================================
-- 5. SUBSIDIO Y PENSIONES (14 trámites)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, monto_beneficio, orden, activo) VALUES

(5, 'Subsidio Único Familiar (SUF)', 'suf',
'Aporte económico para menores de 18 años en familias sin previsión social.',
'bi-person-bounding-box', 'subsidio', 21243.00, 1, 1),

(5, 'Subsidio Maternal', 'maternal',
'Beneficio monetario para mujeres sin previsión a partir del 5to mes de embarazo.',
'bi-gender-female', 'subsidio', NULL, 2, 1),

(5, 'Subsidio Recién Nacido', 'reciennacido',
'Aporte para hijos de beneficiarias del Subsidio Maternal, hasta los 3 meses.',
'bi-baby', 'subsidio', NULL, 3, 1),

(5, 'Subsidio Madre', 'madre',
'Apoyo económico a madres beneficiarias del SUF, por un periodo de 3 años.',
'bi-person-hearts', 'subsidio', NULL, 4, 1),

(5, 'SUF por Menores de 18 años', 'menores',
'Subsidio para niños y niñas sin previsión social, según su edad y condición escolar.',
'bi-emoji-smile', 'subsidio', NULL, 5, 1),

(5, 'Subsidio Familiar Duplo', 'familiarduplo',
'Subsidio duplicado para menores con discapacidad acreditada.',
'bi-discord', 'subsidio', NULL, 6, 1),

(5, 'Pensión Garantizada Universal', 'pgu',
'Apoyo a mayores de 65 años que pertenezcan al 90% más vulnerable.',
'bi-cash-coin', 'pension', NULL, 7, 1),

(5, 'PBSI - Invalidez', 'pbsi',
'Pensión mensual para personas sin régimen previsional con diagnóstico de invalidez.',
'bi-person-x', 'pension', NULL, 8, 1),

(5, 'APSI', 'apsi',
'Aporte solidario para personas con pensión de invalidez inferior a la PGU.',
'bi-heart-pulse', 'beneficio', NULL, 9, 1),

(5, 'Bono por Hijo', 'bonoporhijo',
'Beneficio que incrementa los fondos previsionales de madres por cada hijo nacido.',
'bi-gift', 'beneficio', NULL, 10, 1),

(5, 'Subsidio de Discapacidad', 'discapacidad',
'Subsidio mensual para menores con discapacidad mental, física o sensorial severa.',
'bi-universal-access', 'subsidio', NULL, 11, 1),

(5, 'Subsidio Agua Potable', 'aguapotable',
'Aporte al pago mensual de agua potable y alcantarillado para familias vulnerables.',
'bi-droplet-half', 'subsidio', NULL, 12, 1),

(5, 'SAP Rural', 'saprural',
'Subsidio para usuarios de sistemas de agua potable rural (APR).',
'bi-tree', 'subsidio', NULL, 13, 1),

(5, 'SAP Urbano', 'sapurbano',
'Subsidio al pago de agua potable para hogares de sectores urbanos vulnerables.',
'bi-house-door', 'subsidio', NULL, 14, 1);

-- NOTA: Debido a la extensión del archivo, se continúa en la siguiente sección
-- Los siguientes departamentos (OLN, Mujer y Equidad, ODIMA, Afrodescendientes, 
-- Juventud, Discapacidad, Gestión Habitacional, Comodatos, OMIL, Derechos Humanos,
-- Defensoría y Presupuesto Participativo) serán agregados a continuación.

-- =====================================================
-- 6. OFICINA LOCAL DE LA NIÑEZ - OLN (4 trámites)
-- =====================================================

INSERT INTO tramites (departamento_id, nombre, slug, descripcion_corta, icono_bootstrap, tipo_tramite, orden, activo) VALUES

(6, 'Demandas Espontáneas', 'demanda',
'Denuncia de situaciones de vulneración de derechos de NNA para activar medidas de protección.',
'bi-exclamation-triangle', 'tramite', 1, 1),

(6, 'Protección Universal', 'proteccionuniversal',
'Acciones preventivas y de restitución de derechos para NNA en riesgo.',
'bi-people', 'servicio', 2, 1),

(6, 'Protección de Urgencia', 'proteccionurgencia',
'Medidas inmediatas frente a vulneraciones graves a los derechos de NNA.',
'bi-shield-exclamation', 'servicio', 3, 1),

(6, 'Promoción de Derechos', 'promocionderechosnna',
'Capacitaciones, stands y actividades para promover derechos de NNA en el territorio.',
'bi-megaphone', 'actividad', 4, 1);

-- Continúa en siguiente actualización...
