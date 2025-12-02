-- =====================================================
-- SCRIPT MAESTRO DE MIGRACIÓN - DIDECO
-- Base de datos: dideco
-- Fecha: 18 de noviembre de 2025
-- Descripción: Ejecuta todos los scripts de migración
--              en el orden correcto
-- =====================================================

-- =====================================================
-- INSTRUCCIONES DE EJECUCIÓN
-- =====================================================
-- 1. Asegúrate de tener una copia de seguridad de tu BD actual
-- 2. Ejecuta este script desde phpMyAdmin, MySQL Workbench o línea de comandos
-- 3. Verifica que no haya errores después de cada paso
-- 4. Los datos se insertarán preservando las tablas existentes (evaluaciones y respuestas)
-- =====================================================

USE dideco;

-- =====================================================
-- PASO 0: Verificación de conexión y base de datos
-- =====================================================

SELECT 
    'Conexión exitosa a la base de datos dideco' as mensaje,
    DATABASE() as base_datos_actual,
    NOW() as fecha_hora;

-- Mostrar tablas existentes ANTES de la migración
SELECT 
    'Tablas existentes ANTES de la migración:' as informacion;
    
SHOW TABLES;

SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    CREATE_TIME,
    UPDATE_TIME
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'dideco'
ORDER BY TABLE_NAME;

-- =====================================================
-- PASO 1: Crear estructura de tablas
-- =====================================================

SELECT '========================================' as separador;
SELECT 'PASO 1: Creando estructura de tablas...' as mensaje;
SELECT '========================================' as separador;

SOURCE 01_crear_tablas.sql;

-- Pausar para revisar (comentar si ejecutas todo junto)
-- SELECT 'Tablas creadas. Presiona Enter para continuar...';

-- =====================================================
-- PASO 2: Insertar datos de departamentos
-- =====================================================

SELECT '========================================' as separador;
SELECT 'PASO 2: Insertando datos de departamentos...' as mensaje;
SELECT '========================================' as separador;

SOURCE 02_insertar_departamentos.sql;

-- =====================================================
-- PASO 3: Insertar datos de trámites (Parte 1)
-- =====================================================

SELECT '========================================' as separador;
SELECT 'PASO 3A: Insertando trámites (Parte 1)...' as mensaje;
SELECT '========================================' as separador;

SOURCE 03_insertar_tramites_parte1.sql;

-- =====================================================
-- PASO 4: Insertar datos de trámites (Parte 2)
-- =====================================================

SELECT '========================================' as separador;
SELECT 'PASO 4: Insertando trámites (Parte 2)...' as mensaje;
SELECT '========================================' as separador;

SOURCE 03_insertar_tramites_parte2.sql;

-- =====================================================
-- PASO 5: Verificación final de datos
-- =====================================================

SELECT '========================================' as separador;
SELECT 'PASO 5: Verificando datos insertados...' as mensaje;
SELECT '========================================' as separador;

-- Total de registros por tabla
SELECT 'Resumen de registros insertados:' as informacion;

SELECT 
    'departamentos' as tabla,
    COUNT(*) as total_registros
FROM departamentos
UNION ALL
SELECT 
    'tramites' as tabla,
    COUNT(*) as total_registros
FROM tramites
UNION ALL
SELECT 
    'documentos_tramite' as tabla,
    COUNT(*) as total_registros
FROM documentos_tramite
UNION ALL
SELECT 
    'requisitos_tramite' as tabla,
    COUNT(*) as total_registros
FROM requisitos_tramite
UNION ALL
SELECT 
    'pasos_tramite' as tabla,
    COUNT(*) as total_registros
FROM pasos_tramite;

-- Detalle de trámites por departamento
SELECT '----------------------------------------' as separador;
SELECT 'Trámites por departamento:' as informacion;

SELECT 
    d.orden,
    d.nombre as departamento,
    d.slug,
    COUNT(t.id) as total_tramites,
    SUM(CASE WHEN t.activo = 1 THEN 1 ELSE 0 END) as tramites_activos,
    d.email_contacto
FROM departamentos d
LEFT JOIN tramites t ON d.id = t.departamento_id
WHERE d.activo = 1
GROUP BY d.id, d.nombre, d.slug, d.orden, d.email_contacto
ORDER BY d.orden;

-- Trámites por tipo
SELECT '----------------------------------------' as separador;
SELECT 'Distribución de trámites por tipo:' as informacion;

SELECT 
    tipo_tramite,
    COUNT(*) as cantidad,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM tramites), 2) as porcentaje
FROM tramites
GROUP BY tipo_tramite
ORDER BY cantidad DESC;

-- Verificar índices creados
SELECT '----------------------------------------' as separador;
SELECT 'Índices creados:' as informacion;

SELECT 
    TABLE_NAME,
    INDEX_NAME,
    INDEX_TYPE,
    NON_UNIQUE
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = 'dideco'
  AND TABLE_NAME IN ('departamentos', 'tramites', 'documentos_tramite', 'requisitos_tramite', 'pasos_tramite')
ORDER BY TABLE_NAME, INDEX_NAME;

-- Verificar vistas creadas
SELECT '----------------------------------------' as separador;
SELECT 'Vistas creadas:' as informacion;

SHOW FULL TABLES 
WHERE TABLE_TYPE LIKE 'VIEW';

-- =====================================================
-- PASO 6: Mensajes finales y recomendaciones
-- =====================================================

SELECT '========================================' as separador;
SELECT 'MIGRACIÓN COMPLETADA EXITOSAMENTE' as mensaje;
SELECT '========================================' as separador;

SELECT 'SIGUIENTES PASOS RECOMENDADOS:' as informacion;
SELECT '1. Revisar los datos insertados en cada tabla' as paso;
SELECT '2. Probar las vistas SQL creadas (v_departamentos_con_tramites, v_tramites_completos)' as paso;
SELECT '3. Actualizar los archivos .phtml para que lean desde la BD' as paso;
SELECT '4. Crear modelos en Laminas para acceder a los datos' as paso;
SELECT '5. Implementar el panel de administración (CRUD)' as paso;
SELECT '6. Configurar búsqueda full-text en departamentos y trámites' as paso;

-- Mostrar estructura final de la base de datos
SELECT '----------------------------------------' as separador;
SELECT 'Estructura final de la base de datos:' as informacion;

SHOW TABLES;

-- =====================================================
-- FIN DEL SCRIPT MAESTRO
-- =====================================================

SELECT '========================================' as separador;
SELECT CONCAT('Migración finalizada el: ', NOW()) as mensaje_final;
SELECT '========================================' as separador;
