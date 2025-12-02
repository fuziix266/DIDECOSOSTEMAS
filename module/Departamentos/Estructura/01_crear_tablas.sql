-- =====================================================
-- SCRIPT DE CREACIÓN DE TABLAS - DIDECO
-- Base de datos: dideco
-- Fecha: 18 de noviembre de 2025
-- Descripción: Estructura de base de datos para 
--              gestión de departamentos y trámites DIDECO
-- =====================================================

USE dideco;

-- =====================================================
-- TABLA: departamentos
-- Descripción: Almacena información de los departamentos
--              de DIDECO (Enlace Norte, OCAM, etc.)
-- =====================================================

DROP TABLE IF EXISTS pasos_tramite;
DROP TABLE IF EXISTS requisitos_tramite;
DROP TABLE IF EXISTS documentos_tramite;
DROP TABLE IF EXISTS tramites;
DROP TABLE IF EXISTS departamentos;

CREATE TABLE departamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL COMMENT 'Nombre completo del departamento',
    slug VARCHAR(255) UNIQUE NOT NULL COMMENT 'Identificador URL amigable',
    descripcion TEXT COMMENT 'Descripción breve para la vista principal',
    icono_bootstrap VARCHAR(100) COMMENT 'Clase de icono Bootstrap Icons (ej: bi-people)',
    icono_fontawesome VARCHAR(100) COMMENT 'Clase de icono FontAwesome (ej: fas fa-compass)',
    color_primario VARCHAR(7) DEFAULT '#15356c' COMMENT 'Color hexadecimal del título',
    color_secundario VARCHAR(7) DEFAULT '#6537b6' COMMENT 'Color hexadecimal del icono',
    email_contacto VARCHAR(255) COMMENT 'Email de contacto del departamento',
    mensaje_contacto TEXT COMMENT 'Mensaje del banner de contacto',
    url_externa VARCHAR(500) NULL COMMENT 'URL externa si aplica',
    orden INT DEFAULT 0 COMMENT 'Orden de aparición en el menú principal',
    activo TINYINT(1) DEFAULT 1 COMMENT '1 = visible, 0 = oculto',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_activo (activo),
    INDEX idx_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Departamentos de DIDECO';

-- =====================================================
-- TABLA: tramites
-- Descripción: Almacena información de trámites y
--              servicios de cada departamento
-- =====================================================

CREATE TABLE tramites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    departamento_id INT NOT NULL COMMENT 'Relación con departamentos',
    nombre VARCHAR(255) NOT NULL COMMENT 'Nombre del trámite/servicio',
    slug VARCHAR(255) NOT NULL COMMENT 'Identificador URL dentro del departamento',
    descripcion_corta TEXT COMMENT 'Descripción breve para tarjeta (grid)',
    descripcion_larga TEXT COMMENT 'Descripción completa para vista de detalle',
    icono_bootstrap VARCHAR(100) COMMENT 'Clase de icono Bootstrap Icons',
    icono_fontawesome VARCHAR(100) COMMENT 'Clase de icono FontAwesome',
    
    -- Campos de información del trámite (para accordion)
    documentos_requeridos TEXT COMMENT 'HTML o JSON con lista de documentos',
    requisitos_usuario TEXT COMMENT 'HTML o JSON con lista de requisitos',
    instrucciones_paso_paso TEXT COMMENT 'HTML o JSON con pasos del trámite',
    tiempo_estimado VARCHAR(255) COMMENT 'Tiempo de respuesta (ej: "2 meses", "inmediato")',
    
    -- Responsable del trámite
    responsable_nombre VARCHAR(255) COMMENT 'Nombre del funcionario responsable',
    responsable_cargo VARCHAR(255) COMMENT 'Cargo del responsable',
    responsable_email VARCHAR(255) COMMENT 'Email del responsable',
    responsable_telefono VARCHAR(50) COMMENT 'Teléfono del responsable',
    
    -- Información adicional
    monto_beneficio DECIMAL(10, 2) NULL COMMENT 'Monto económico del beneficio (si aplica)',
    url_externa VARCHAR(500) NULL COMMENT 'Link a sitio externo (si requiere)',
    url_formulario VARCHAR(500) NULL COMMENT 'Link a formulario online',
    
    -- Metadata
    tipo_tramite ENUM('tramite', 'subsidio', 'pension', 'beneficio', 'servicio', 'asesoria', 
                      'certificado', 'orientacion', 'taller', 'actividad', 'otro') 
                DEFAULT 'tramite' COMMENT 'Categoría del trámite',
    requiere_evaluacion_social TINYINT(1) DEFAULT 0 COMMENT 'Requiere visita de trabajador social',
    requiere_rsh TINYINT(1) DEFAULT 0 COMMENT 'Requiere estar en Registro Social de Hogares',
    es_presencial TINYINT(1) DEFAULT 1 COMMENT 'Requiere presencia física',
    es_online TINYINT(1) DEFAULT 0 COMMENT 'Se puede hacer online',
    
    -- Control
    orden INT DEFAULT 0 COMMENT 'Orden dentro del departamento',
    activo TINYINT(1) DEFAULT 1 COMMENT '1 = visible, 0 = oculto',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_slug_departamento (departamento_id, slug),
    INDEX idx_slug (slug),
    INDEX idx_activo (activo),
    INDEX idx_tipo (tipo_tramite),
    INDEX idx_departamento (departamento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Trámites y servicios de DIDECO';

-- =====================================================
-- TABLA: documentos_tramite (Normalización)
-- Descripción: Lista detallada de documentos requeridos
--              para cada trámite
-- =====================================================

CREATE TABLE documentos_tramite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tramite_id INT NOT NULL COMMENT 'Relación con tramites',
    nombre_documento VARCHAR(255) NOT NULL COMMENT 'Nombre del documento',
    descripcion TEXT NULL COMMENT 'Descripción o aclaraciones',
    es_obligatorio TINYINT(1) DEFAULT 1 COMMENT '1 = obligatorio, 0 = opcional',
    tipo_documento ENUM('cedula', 'certificado', 'formulario', 'comprobante', 'informe', 'otro') 
                   DEFAULT 'otro' COMMENT 'Categoría del documento',
    url_descarga VARCHAR(500) NULL COMMENT 'URL para descargar formulario (si aplica)',
    orden INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tramite_id) REFERENCES tramites(id) ON DELETE CASCADE,
    INDEX idx_tramite (tramite_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Documentos requeridos por trámite';

-- =====================================================
-- TABLA: requisitos_tramite (Normalización)
-- Descripción: Requisitos que debe cumplir el usuario
--              para acceder al trámite
-- =====================================================

CREATE TABLE requisitos_tramite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tramite_id INT NOT NULL COMMENT 'Relación con tramites',
    descripcion TEXT NOT NULL COMMENT 'Descripción del requisito',
    tipo ENUM('documento', 'condicion', 'edad', 'residencia', 'rsh', 'evaluacion', 'otro') 
         DEFAULT 'condicion' COMMENT 'Tipo de requisito',
    valor_minimo VARCHAR(100) NULL COMMENT 'Valor mínimo (ej: edad mínima)',
    valor_maximo VARCHAR(100) NULL COMMENT 'Valor máximo (ej: edad máxima)',
    orden INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tramite_id) REFERENCES tramites(id) ON DELETE CASCADE,
    INDEX idx_tramite (tramite_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Requisitos por trámite';

-- =====================================================
-- TABLA: pasos_tramite (Normalización)
-- Descripción: Pasos del proceso para realizar el trámite
-- =====================================================

CREATE TABLE pasos_tramite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tramite_id INT NOT NULL COMMENT 'Relación con tramites',
    numero_paso INT NOT NULL COMMENT 'Número de orden del paso',
    titulo VARCHAR(255) NULL COMMENT 'Título del paso',
    descripcion TEXT NOT NULL COMMENT 'Descripción detallada del paso',
    es_presencial TINYINT(1) DEFAULT 1 COMMENT 'Requiere ir a oficina',
    ubicacion VARCHAR(255) NULL COMMENT 'Dónde se realiza este paso',
    tiempo_estimado VARCHAR(100) NULL COMMENT 'Tiempo que toma este paso',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (tramite_id) REFERENCES tramites(id) ON DELETE CASCADE,
    INDEX idx_tramite (tramite_id),
    INDEX idx_numero_paso (numero_paso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pasos del proceso de trámite';

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista: Departamentos con conteo de trámites
CREATE OR REPLACE VIEW v_departamentos_con_tramites AS
SELECT 
    d.id,
    d.nombre,
    d.slug,
    d.descripcion,
    d.icono_bootstrap,
    d.email_contacto,
    d.orden,
    COUNT(t.id) as total_tramites,
    SUM(CASE WHEN t.activo = 1 THEN 1 ELSE 0 END) as tramites_activos
FROM departamentos d
LEFT JOIN tramites t ON d.id = t.departamento_id
WHERE d.activo = 1
GROUP BY d.id
ORDER BY d.orden;

-- Vista: Trámites con nombre de departamento
CREATE OR REPLACE VIEW v_tramites_completos AS
SELECT 
    t.id,
    t.nombre,
    t.slug,
    t.descripcion_corta,
    t.tipo_tramite,
    t.monto_beneficio,
    d.nombre as departamento_nombre,
    d.slug as departamento_slug,
    d.email_contacto as departamento_email
FROM tramites t
INNER JOIN departamentos d ON t.departamento_id = d.id
WHERE t.activo = 1 AND d.activo = 1
ORDER BY d.orden, t.orden;

-- =====================================================
-- ÍNDICES ADICIONALES PARA BÚSQUEDA
-- =====================================================

-- Índice full-text para búsqueda en departamentos
ALTER TABLE departamentos ADD FULLTEXT INDEX ft_departamentos_busqueda (nombre, descripcion);

-- Índice full-text para búsqueda en trámites
ALTER TABLE tramites ADD FULLTEXT INDEX ft_tramites_busqueda (nombre, descripcion_corta, descripcion_larga);

-- =====================================================
-- COMENTARIOS Y DOCUMENTACIÓN
-- =====================================================

-- Comentario en la base de datos
ALTER DATABASE dideco CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================

SELECT 'Tablas creadas exitosamente!' as mensaje;
SELECT TABLE_NAME, TABLE_ROWS, CREATE_TIME 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'dideco' 
  AND TABLE_NAME IN ('departamentos', 'tramites', 'documentos_tramite', 'requisitos_tramite', 'pasos_tramite')
ORDER BY TABLE_NAME;
