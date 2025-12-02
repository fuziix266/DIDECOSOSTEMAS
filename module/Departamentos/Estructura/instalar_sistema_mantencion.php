<?php
/**
 * Script para crear las tablas del sistema de mantención
 * y el usuario administrador inicial
 */

$config = require '../../../config/autoload/local.php';

try {
    $pdo = new PDO(
        $config['db_departamentos']['dsn'],
        $config['db_departamentos']['username'],
        $config['db_departamentos']['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");

    echo "🔧 Creando tablas del sistema de mantención...\n\n";

    // Tabla usuarios_sistema
    $sql = "CREATE TABLE IF NOT EXISTS usuarios_sistema (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Tabla usuarios_sistema creada\n";

    // Tabla auditoria_cambios
    $sql = "CREATE TABLE IF NOT EXISTS auditoria_cambios (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Tabla auditoria_cambios creada\n";

    // Tabla sesiones_usuarios
    $sql = "CREATE TABLE IF NOT EXISTS sesiones_usuarios (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Tabla sesiones_usuarios creada\n";

    // Agregar columnas a departamentos
    try {
        $pdo->exec("ALTER TABLE departamentos ADD COLUMN email_contacto VARCHAR(100) NULL AFTER descripcion");
        echo "✅ Columna email_contacto agregada a departamentos\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') === false) {
            echo "⚠️  Error agregando email_contacto: " . $e->getMessage() . "\n";
        }
    }

    try {
        $pdo->exec("ALTER TABLE departamentos ADD COLUMN telefono_contacto VARCHAR(20) NULL AFTER email_contacto");
        echo "✅ Columna telefono_contacto agregada a departamentos\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') === false) {
            echo "⚠️  Error agregando telefono_contacto: " . $e->getMessage() . "\n";
        }
    }

    try {
        $pdo->exec("ALTER TABLE departamentos ADD COLUMN horario_atencion TEXT NULL AFTER telefono_contacto");
        echo "✅ Columna horario_atencion agregada a departamentos\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') === false) {
            echo "⚠️  Error agregando horario_atencion: " . $e->getMessage() . "\n";
        }
    }

    try {
        $pdo->exec("ALTER TABLE departamentos ADD COLUMN direccion VARCHAR(200) NULL AFTER horario_atencion");
        echo "✅ Columna direccion agregada a departamentos\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') === false) {
            echo "⚠️  Error agregando direccion: " . $e->getMessage() . "\n";
        }
    }

    // Crear usuario administrador inicial
    $passwordHash = password_hash('Admin123!', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("SELECT id FROM usuarios_sistema WHERE email = ?");
    $stmt->execute(['admin@municipalidadarica.cl']);
    
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO usuarios_sistema (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Administrador Sistema', 'admin@municipalidadarica.cl', $passwordHash, 'administrador']);
        echo "\n✅ Usuario administrador creado\n";
        echo "   Email: admin@municipalidadarica.cl\n";
        echo "   Password: Admin123!\n";
        echo "   ⚠️  IMPORTANTE: Cambiar la contraseña después del primer inicio de sesión\n";
    } else {
        echo "\n✅ Usuario administrador ya existe\n";
    }

    echo "\n✅ ¡Todas las tablas creadas exitosamente!\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
