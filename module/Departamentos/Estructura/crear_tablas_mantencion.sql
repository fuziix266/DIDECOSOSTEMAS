-- Tablas para sistema de mantención
-- Ejecutar en base de datos 'dideco'

-- Tabla de usuarios del sistema
CREATE TABLE IF NOT EXISTS usuarios_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'editor') NOT NULL DEFAULT 'editor',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME NULL,
    creado_por INT NULL,
    FOREIGN KEY (creado_por) REFERENCES usuarios_sistema(id) ON DELETE SET NULL,
    INDEX idx_email (email),
    INDEX idx_rol (rol),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Agregar columnas de contacto a la tabla departamentos si no existen
ALTER TABLE departamentos 
ADD COLUMN IF NOT EXISTS email_contacto VARCHAR(100) NULL AFTER descripcion,
ADD COLUMN IF NOT EXISTS telefono_contacto VARCHAR(20) NULL AFTER email_contacto,
ADD COLUMN IF NOT EXISTS horario_atencion TEXT NULL AFTER telefono_contacto,
ADD COLUMN IF NOT EXISTS direccion VARCHAR(200) NULL AFTER horario_atencion;

-- Tabla de auditoría para cambios
CREATE TABLE IF NOT EXISTS auditoria_cambios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tabla_afectada VARCHAR(50) NOT NULL,
    registro_id INT NOT NULL,
    accion ENUM('crear', 'editar', 'eliminar') NOT NULL,
    datos_anteriores TEXT NULL,
    datos_nuevos TEXT NULL,
    fecha_cambio DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios_sistema(id) ON DELETE CASCADE,
    INDEX idx_tabla (tabla_afectada),
    INDEX idx_fecha (fecha_cambio),
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador inicial (password: Admin123!)
-- La contraseña debe ser hasheada con password_hash() en PHP
INSERT INTO usuarios_sistema (nombre, email, password, rol) 
VALUES ('Administrador', 'admin@municipalidadarica.cl', '$2y$10$YourHashedPasswordHere', 'administrador')
ON DUPLICATE KEY UPDATE email=email;

-- Agregar índices para mejorar rendimiento en tramites
ALTER TABLE tramites
ADD INDEX IF NOT EXISTS idx_departamento_slug (departamento_id, slug);

-- Tabla de sesiones para seguridad mejorada
CREATE TABLE IF NOT EXISTS sesiones_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255) NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion DATETIME NOT NULL,
    activa TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (usuario_id) REFERENCES usuarios_sistema(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_usuario_activa (usuario_id, activa),
    INDEX idx_expiracion (fecha_expiracion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
