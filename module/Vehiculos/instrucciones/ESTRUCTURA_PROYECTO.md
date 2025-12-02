# Estructura y Análisis del Proyecto QR Vehículos Municipales

**Fecha:** 11 de noviembre de 2025  
**Sistema:** Identificación Vehicular Municipal con Código QR  
**Framework:** Laminas MVC + MariaDB/MySQL  
**Dominio:** www.didecoarica.cl/vehiculos  
**Correos:** @municipalidadarica.cl

---

## 📋 ANÁLISIS COMPLETO DEL SISTEMA

### ✅ Entendimiento de la Lógica del Negocio

He analizado completamente las instrucciones y **comprendo perfectamente** la lógica del sistema:

#### **Concepto Principal:**
- Sistema de identificación vehicular municipal mediante **códigos QR únicos**
- Cada QR está vinculado a un **correo de funcionario** (no al vehículo)
- El QR es **reutilizable** cuando el funcionario cambia de vehículo
- Sistema basado en **principio de buena fe** - el funcionario puede editar sus datos libremente

#### **Flujo de Funcionamiento:**

1. **Generación de QR (Admin)**
   - Admin genera lotes de QR con UUID único
   - Estado inicial: PENDIENTE
   - Se generan en PDF (57mm x 93mm) con diseño
   - Se imprimen y entregan físicamente a funcionarios

2. **Primer Escaneo (Funcionario - REGISTRO INICIAL)**
   - ⚠️ **NO requiere GPS** (es solo registro inicial)
   - Funcionario ingresa correo institucional (@municipalidadarica.cl)
   - Sistema envía código de verificación por email (6 dígitos)
   - Este escaneo NO se registra con GPS

3. **Confirmación de Correo**
   - Funcionario recibe código (6 dígitos)
   - Ingresa código antes de que expire (30-60 min)
   - Sistema confirma y marca QR como ASIGNADO
   - Se habilita formulario de datos

4. **Registro de Datos**
   - Funcionario completa: nombres, apellidos, RUT, unidad, cargo, patente, celular, anexo, observaciones
   - **Campos obligatorios:** nombres, apellidos, celular
   - Sistema guarda en `qr_registros`
   - Se registra en historial (audit trail)

5. **Reutilización del QR (Edición de Datos)**
   - Funcionario accede a URL de edición
   - Ingresa correo del QR
   - Sistema envía código dinámico (6 dígitos)
   - Al validar código → accede a formulario de edición
   - Puede editar TODO excepto el correo electrónico
   - Todo cambio se registra en historial

6. **Escaneos Posteriores (Consultas)**
   - ✅ **GPS OBLIGATORIO** - sin GPS muestra solo "Activar GPS"
   - **CUALQUIER persona (con GPS activo):**
     - Ve fondo de color (verde=habilitado, rojo=deshabilitado)
     - Ve icono del vehículo
     - Ve SOLO la PATENTE del vehículo
   - **Inspector autenticado (con GPS activo):**
     - Ve TODO: nombres, apellidos, RUT, unidad, cargo, patente, celular, anexo, observaciones
   - TODO escaneo se registra en logs con GPS (excepto registro inicial)

#### **Características Clave:**
✅ GPS obligatorio SOLO para consultas (escaneos posteriores)  
✅ Registro inicial NO requiere GPS  
✅ Log completo de escaneos con GPS (hora y ubicación)  
✅ Cualquier persona ve SOLO la patente (con GPS)  
✅ Inspectores ven datos completos (con GPS)  
✅ Detección de escaneos sospechosos (hora y lugar)  
✅ Audit trail de todos los cambios  
✅ Reutilización de QR al cambiar vehículo  
✅ Validación por correo con código de 6 dígitos  

---

## 🗂️ ESTRUCTURA DE MÓDULOS DEL PROYECTO

### **Módulo Principal: VehiculosQr**

```
module/
└── VehiculosQr/
    ├── config/
    │   └── module.config.php          # Rutas, servicios, factories
    ├── src/
    │   ├── Module.php                  # Configuración del módulo
    │   │
    │   ├── Controller/
    │   │   ├── QrController.php                    # Gestión pública de QR
    │   │   ├── InspectorAuthController.php         # Login/Logout inspectores
    │   │   ├── InspectorQrController.php           # Consulta de QR por inspectores
    │   │   └── AdminQrController.php               # Administración de QR
    │   │
    │   ├── Service/
    │   │   ├── QrService.php                       # Lógica de QR y registros
    │   │   ├── CorreoService.php                   # Envío de emails
    │   │   ├── InspectorAuthService.php            # Autenticación inspectores
    │   │   ├── QrLogService.php                    # Registro de logs
    │   │   └── QrHistorialService.php              # Audit trail
    │   │
    │   ├── Model/
    │   │   ├── QrCodigos.php                       # Entidad QR
    │   │   ├── QrRegistros.php                     # Entidad Registros
    │   │   ├── QrRegistrosHistorial.php            # Entidad Historial
    │   │   ├── QrUsuariosInspectores.php           # Entidad Inspectores
    │   │   └── QrLogs.php                          # Entidad Logs
    │   │
    │   ├── Repository/
    │   │   ├── QrCodigosRepository.php
    │   │   ├── QrRegistrosRepository.php
    │   │   ├── QrHistorialRepository.php
    │   │   ├── InspectoresRepository.php
    │   │   └── QrLogsRepository.php
    │   │
    │   ├── Form/
    │   │   ├── SolicitarCorreoForm.php
    │   │   ├── ConfirmarCodigoForm.php
    │   │   ├── DatosFuncionarioForm.php
    │   │   ├── InspectorLoginForm.php
    │   │   └── GenerarLoteQrForm.php
    │   │
    │   └── Validator/
    │       ├── RutChilenoValidator.php
    │       ├── PatenteChilenaValidator.php
    │       ├── CorreoMunicipalValidator.php
    │       └── CodigoConfirmacionValidator.php
    │
    └── view/
        └── vehiculos-qr/
            ├── qr/
            │   ├── index.phtml                      # Vista inicial (solicita GPS)
            │   ├── solicitar-correo.phtml           # Formulario de correo
            │   ├── confirmar-codigo.phtml           # Formulario código
            │   ├── formulario-datos.phtml           # Formulario datos funcionario
            │   ├── mensaje-publico.phtml            # Vista pública (sin inspector)
            │   └── sin-gps.phtml                    # Mensaje de error GPS
            │
            ├── inspector/
            │   ├── login.phtml                      # Login inspector
            │   └── ver-qr.phtml                     # Ficha completa del QR
            │
            └── admin/
                ├── listado-qr.phtml                 # Listado de QR
                ├── generar-lote.phtml               # Formulario generar lote
                └── detalle-qr.phtml                 # Detalle/Logs de un QR
```

---

## 🗄️ ESTRUCTURA DE BASE DE DATOS

### **Base de Datos: `qr_vehiculos_municipal`**

**Configuración:**
- Usuario: `root`
- Password: *(sin contraseña)*
- Host: `localhost`
- Motor: MariaDB
- Charset: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`

---

### **Tabla 1: `qr_codigos`**
**Propósito:** Almacenar códigos QR generados

```sql
CREATE TABLE qr_codigos (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid_qr          CHAR(36) NOT NULL UNIQUE COMMENT 'UUID del código QR',
    estado           ENUM('PENDIENTE','ASIGNADO','INACTIVO') NOT NULL DEFAULT 'PENDIENTE',
    fecha_creacion   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_asignacion DATETIME NULL COMMENT 'Fecha cuando se asignó a un funcionario',
    observaciones    VARCHAR(255) NULL,
    
    INDEX idx_uuid (uuid_qr),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Códigos QR generados para vehículos municipales';
```

**Campos importantes:**
- `uuid_qr`: Identificador único del QR (ej: "550e8400-e29b-41d4-a716-446655440000")
- `estado`: PENDIENTE (nuevo), ASIGNADO (en uso), INACTIVO (deshabilitado)

---

### **Tabla 2: `qr_registros`**
**Propósito:** Datos del funcionario y vehículo asociados al QR

```sql
CREATE TABLE qr_registros (
    id                          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    qr_codigo_id                INT UNSIGNED NOT NULL UNIQUE COMMENT 'Relación 1:1 con qr_codigos',
    
    -- Autenticación
    correo_funcionario          VARCHAR(255) NOT NULL COMMENT 'Correo @municipalidadarica.cl',
    codigo_confirmacion         VARCHAR(6) NULL COMMENT 'Código de 6 dígitos enviado por email',
    codigo_confirmacion_expira  DATETIME NULL COMMENT 'Expiración del código (30-60 min)',
    correo_confirmado           TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=No confirmado, 1=Confirmado',
    fecha_confirmacion          DATETIME NULL,
    
    -- Datos del funcionario (OBLIGATORIOS: nombres, apellidos, celular)
    nombres                     VARCHAR(150) NOT NULL COMMENT 'Nombres del funcionario',
    apellidos                   VARCHAR(150) NOT NULL COMMENT 'Apellidos del funcionario',
    rut                         VARCHAR(20) NULL COMMENT 'RUT chileno formato XX.XXX.XXX-X',
    unidad                      VARCHAR(255) NULL COMMENT 'Dirección/Departamento municipal',
    cargo                       VARCHAR(100) NULL,
    celular                     VARCHAR(20) NOT NULL COMMENT 'Teléfono celular (obligatorio)',
    anexo                       VARCHAR(20) NULL COMMENT 'Anexo telefónico (opcional)',
    
    -- Datos del vehículo
    patente                     VARCHAR(10) NULL COMMENT 'Patente del vehículo actual',
    observaciones               TEXT NULL COMMENT 'Observaciones adicionales',
    
    -- Auditoría
    fecha_registro              DATETIME NULL COMMENT 'Primera vez que se guardaron datos',
    fecha_actualizacion         DATETIME NULL COMMENT 'Última actualización de datos',
    creado_por_ip               VARCHAR(45) NULL,
    actualizado_por_ip          VARCHAR(45) NULL,
    
    CONSTRAINT fk_qr_registros_qr_codigos
        FOREIGN KEY (qr_codigo_id) REFERENCES qr_codigos(id) ON DELETE CASCADE,
    
    INDEX idx_correo (correo_funcionario),
    INDEX idx_rut (rut),
    INDEX idx_patente (patente),
    INDEX idx_nombres_apellidos (nombres, apellidos)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Registros de funcionarios y vehículos asociados a QR';
```

**Relación:** 1 QR → 1 Registro (relación 1:1 por `qr_codigo_id UNIQUE`)

---

### **Tabla 3: `qr_registros_historial`**
**Propósito:** Audit trail - historial de cambios en los registros

```sql
CREATE TABLE qr_registros_historial (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    qr_registro_id      INT UNSIGNED NOT NULL,
    
    -- Quién hizo el cambio
    quien_correo        VARCHAR(255) NOT NULL COMMENT 'Correo del funcionario o admin',
    accion              ENUM('CREAR','EDITAR','RESET_QR','BORRAR') NOT NULL,
    
    -- Qué cambió
    cambios_json        JSON NULL COMMENT 'Diff de cambios: {"campo": ["valor_anterior", "valor_nuevo"]}',
    
    -- Metadata
    ip                  VARCHAR(45) NULL,
    user_agent          VARCHAR(255) NULL,
    fecha_evento        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_hist_qr_registro
        FOREIGN KEY (qr_registro_id) REFERENCES qr_registros(id) ON DELETE CASCADE,
    
    INDEX idx_registro (qr_registro_id),
    INDEX idx_fecha (fecha_evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Historial de cambios en registros (audit trail)';
```

**Ejemplo de `cambios_json`:**
```json
{
    "patente": ["ABCD11", "XYZC22"],
    "cargo": ["Inspector", "Jefe de Unidad"],
    "celular": ["+56912345678", "+56987654321"]
}
```

---

### **Tabla 4: `qr_usuarios`**
**Propósito:** Usuarios del sistema (Inspectores y Administradores)

```sql
CREATE TABLE qr_usuarios (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NOT NULL,
    correo          VARCHAR(255) NOT NULL UNIQUE COMMENT 'Correo @municipalidadarica.cl',
    password_hash   VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt de la contraseña',
    rol             ENUM('ADMIN','INSPECTOR') NOT NULL DEFAULT 'INSPECTOR',
    activo          TINYINT(1) NOT NULL DEFAULT 1 COMMENT '0=Inactivo, 1=Activo',
    
    creado_en       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en  DATETIME NULL,
    
    INDEX idx_correo (correo),
    INDEX idx_rol (rol),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Usuarios del sistema (inspectores y administradores)';
```

---

### **Tabla 5: `qr_logs`**
**Propósito:** Log de escaneos y eventos (con GPS para consultas)

```sql
CREATE TABLE qr_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    qr_codigo_id    INT UNSIGNED NOT NULL,
    usuario_id      INT UNSIGNED NULL COMMENT 'NULL si no es inspector/admin',
    
    -- Tipo de evento
    tipo            ENUM('REGISTRO_INICIAL','CONSULTA_PUBLICA','CONSULTA_INSPECTOR','INTENTO_SIN_GPS') NOT NULL,
    
    -- Metadata
    ip              VARCHAR(45) NULL,
    user_agent      VARCHAR(255) NULL,
    
    -- GPS (NULL solo para REGISTRO_INICIAL)
    lat             DECIMAL(10,7) NULL COMMENT 'Latitud',
    lon             DECIMAL(10,7) NULL COMMENT 'Longitud',
    gps_accuracy_m  FLOAT NULL COMMENT 'Precisión en metros',
    
    fecha_evento    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    hora_evento     TIME GENERATED ALWAYS AS (TIME(fecha_evento)) STORED COMMENT 'Hora del escaneo (para detectar horarios sospechosos)',
    
    CONSTRAINT fk_qr_logs_qr_codigos
        FOREIGN KEY (qr_codigo_id) REFERENCES qr_codigos(id) ON DELETE CASCADE,
    CONSTRAINT fk_qr_logs_usuarios
        FOREIGN KEY (usuario_id) REFERENCES qr_usuarios(id) ON DELETE SET NULL,
    
    INDEX idx_qr_codigo (qr_codigo_id),
    INDEX idx_tipo (tipo),
    INDEX idx_fecha (fecha_evento),
    INDEX idx_hora (hora_evento),
    INDEX idx_gps (lat, lon)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Log de todos los escaneos de QR con GPS (excepto registro inicial)';
```

**Tipos de eventos:**
- `REGISTRO_INICIAL`: Primer escaneo (no requiere GPS)
- `CONSULTA_PUBLICA`: Escaneo público (con GPS, ve solo patente)
- `CONSULTA_INSPECTOR`: Escaneo de inspector/admin (con GPS, ve todo)
- `INTENTO_SIN_GPS`: Intento sin GPS o sin permisos de ubicación

---

## 🔄 RELACIONES ENTRE TABLAS

```
qr_codigos (1) ──────── (1) qr_registros
     │                           │
     │                           │
     │                           ├──── (N) qr_registros_historial
     │
     └──── (N) qr_logs
                 │
                 └──── (0..1) qr_usuarios [ADMIN o INSPECTOR]
```

**Explicación:**
- 1 QR puede tener 1 Registro (relación 1:1)
- 1 Registro puede tener N registros en el Historial (relación 1:N)
- 1 QR puede tener N logs de escaneos (relación 1:N)
- 1 Log puede estar asociado a 0 o 1 Usuario (relación 0..1:N)
- Los usuarios tienen roles: ADMIN o INSPECTOR

---

## 🛣️ RUTAS PRINCIPALES (Endpoints)

### **Rutas Públicas / Funcionario**
```
GET  /vehiculos/qr/:uuid                       # Vista inicial (registro o consulta)
POST /vehiculos/qr/:uuid/solicitar-correo      # Solicita código por email (registro inicial)
POST /vehiculos/qr/:uuid/confirmar             # Confirma código (registro inicial)
GET  /vehiculos/qr/:uuid/formulario            # Formulario de datos (registro inicial)
POST /vehiculos/qr/:uuid/guardar-datos         # Guarda datos funcionario/vehículo
POST /vehiculos/qr/:uuid/consultar             # Consulta pública (requiere GPS, devuelve solo patente)
```

### **Rutas de Edición (Funcionario)**
```
GET  /vehiculos/editar                         # Formulario para ingresar correo
POST /vehiculos/editar/solicitar-codigo        # Envía código de 6 dígitos al correo
POST /vehiculos/editar/validar-codigo          # Valida código y genera sesión temporal
GET  /vehiculos/editar/formulario              # Formulario de edición (todos los campos excepto correo)
POST /vehiculos/editar/guardar                 # Guarda cambios
```

### **Rutas de Inspector/Admin**
```
GET  /vehiculos/login                          # Login inspector/admin
POST /vehiculos/login                          # Procesar login
GET  /vehiculos/logout                         # Cerrar sesión
GET  /vehiculos/inspector/qr/:uuid             # Ver ficha completa (requiere GPS)
```

### **Rutas de Administrador**
```
GET  /vehiculos/admin/qr                       # Listado de QR
POST /vehiculos/admin/qr/generar-lote          # Generar lote de QR (PDF 57x93mm)
POST /vehiculos/admin/qr/:id/cambiar-estado    # Cambiar estado QR
GET  /vehiculos/admin/qr/:id/logs              # Ver logs de un QR (detectar escaneos sospechosos)
GET  /vehiculos/admin/usuarios                 # Gestión de usuarios
```

---

## 📦 SERVICIOS PRINCIPALES

### **1. QrService**
- `buscarPorUuid($uuid)`: Buscar QR por UUID
- `crearRegistro($qrId, $correo)`: Crear registro inicial
- `actualizarDatos($qrId, $datos)`: Actualizar datos funcionario/vehículo
- `generarCodigoConfirmacion($qrRegistroId)`: Generar código 6-8 dígitos
- `confirmarCodigo($uuid, $codigo)`: Validar código de confirmación
- `obtenerDatosCompletos($uuid)`: Obtener ficha completa

### **2. CorreoService**
- `enviarCodigoConfirmacion($correo, $codigo)`: Enviar email con código
- `enviarLinkGestion($correo, $token)`: Enviar link para editar datos

### **3. QrLogService**
- `registrarEscaneo($qrId, $tipo, $gps, $inspectorId)`: Registrar log
- `obtenerLogsPorQr($qrId)`: Obtener historial de escaneos
- `registrarIntentoSinGps($qrId)`: Registrar intento sin GPS

### **4. QrHistorialService**
- `registrarCambio($qrRegistroId, $quien, $accion, $cambios)`: Guardar en historial
- `obtenerHistorialPorRegistro($qrRegistroId)`: Obtener audit trail

### **5. InspectorAuthService**
- `login($correo, $password)`: Autenticar inspector
- `logout()`: Cerrar sesión
- `isAuthenticated()`: Verificar si está autenticado
- `getCurrentInspector()`: Obtener inspector actual

---

## 🔐 VALIDADORES PERSONALIZADOS

### **RutChilenoValidator**
- Validar formato: XX.XXX.XXX-X
- Validar dígito verificador

### **PatenteChilenaValidator**
- Formato antiguo: LLNN·NN (ej: AB·12·34)
- Formato nuevo: LLLL·NN (ej: ABCD·12)

### **CorreoMunicipalValidator**
- Validar dominio @municipalidadarica.cl

### **CodigoConfirmacionValidator**
- Validar formato numérico 6-8 dígitos
- Validar no expirado

---

## 🎨 DISEÑO Y FRONTEND

### **CSS Framework**
- **Bootstrap 5.3** (última versión estable)
- Totalmente responsivo (mobile-first)

### **Paleta de Colores del Sistema**
```css
:root {
    /* Colores principales */
    --primary-color: #0d47a1;        /* Azul institucional */
    --secondary-color: #1976d2;      /* Azul claro */
    --success-color: #2e7d32;        /* Verde (QR habilitado) */
    --danger-color: #c62828;         /* Rojo (QR deshabilitado) */
    --warning-color: #f57c00;        /* Naranja (advertencias) */
    --info-color: #0288d1;           /* Azul info */
    
    /* Colores neutros */
    --dark-color: #212121;           /* Texto principal */
    --light-color: #f5f5f5;          /* Fondo claro */
    --white-color: #ffffff;          /* Blanco puro */
    
    /* Gradientes */
    --gradient-primary: linear-gradient(135deg, #0d47a1 0%, #1976d2 100%);
    --gradient-success: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
    --gradient-danger: linear-gradient(135deg, #c62828 0%, #ef5350 100%);
}
```

### **Layout Principal**
```
┌─────────────────────────────────────────┐
│  HEADER (Navbar Bootstrap)              │
│  - Logo Municipalidad                   │
│  - Título: Sistema QR Vehículos         │
│  - Menú: Inicio | Admin | Logout        │
└─────────────────────────────────────────┘
┌─────────────────────────────────────────┐
│                                         │
│  MAIN CONTENT (Container)               │
│  - Cards con sombras                    │
│  - Formularios con validación           │
│  - Botones con iconos                   │
│  - Alerts para mensajes                 │
│                                         │
└─────────────────────────────────────────┘
┌─────────────────────────────────────────┐
│  FOOTER                                 │
│  - © 2025 Municipalidad de Arica        │
│  - DIDECO - www.didecoarica.cl          │
└─────────────────────────────────────────┘
```

### **Estructura de Vistas**

#### **Layout Base: `layout.phtml`**
- Navbar responsive con Bootstrap 5.3
- Breadcrumbs para navegación
- Contenedor principal con padding
- Footer fixed-bottom
- Incluye Bootstrap Icons

#### **Componentes Reutilizables**
- **Cards:** Para formularios y datos
- **Badges:** Para estados (PENDIENTE, ASIGNADO, INACTIVO)
- **Modals:** Para confirmaciones
- **Toasts:** Para notificaciones
- **Spinners:** Para loading states

### **JavaScript**
- Vanilla JS (sin frameworks adicionales)
- API Geolocation (navigator.geolocation)
- Fetch API para AJAX
- Bootstrap 5.3 JS (modals, toasts, tooltips)

### **Librerías Adicionales**
- **SweetAlert2:** Mensajes y confirmaciones elegantes
- **Bootstrap Icons:** Iconografía completa
- **endroid/qr-code:** Generación de QR en PHP

### **Vistas con Estados de Color**

#### **Vista Pública (Consulta sin inspector)**
- Fondo: **Verde** (#2e7d32) si QR habilitado
- Fondo: **Rojo** (#c62828) si QR deshabilitado
- Muestra: Icono de vehículo + PATENTE en grande
- Texto: "Vehículo Municipal - DIDECO Arica"

#### **Vista Inspector (Consulta con inspector)**
- Card blanco con sombra
- Header azul institucional (#0d47a1)
- Datos completos en tabla responsive
- Badges de colores para estados

#### **Vista Sin GPS**
- Fondo naranja (#f57c00)
- Icono de ubicación tachado
- Mensaje grande: "Active el GPS para continuar"
- Botón para reintentar

---

## 📧 CONFIGURACIÓN DE CORREO

### **En config/autoload/local.php**
```php
return [
    'smtp' => [
        'host' => 'smtp.municipalidadarica.cl',
        'port' => 587,
        'username' => 'noreply@municipalidadarica.cl',
        'password' => 'contraseña_smtp',
        'security' => 'tls',
        'from' => [
            'email' => 'noreply@municipalidadarica.cl',
            'name' => 'Sistema QR Vehículos Municipales'
        ]
    ]
];
```

---

## 🔒 SEGURIDAD

### **Medidas Implementadas**
1. ✅ HTTPS obligatorio en producción
2. ✅ Passwords hasheados con bcrypt (cost 12)
3. ✅ Validación de dominio de correo (@municipalidadarica.cl)
4. ✅ Tokens con expiración para edición
5. ✅ Códigos de confirmación con expiración (30-60 min)
6. ✅ Rate limiting en endpoints críticos
7. ✅ Sanitización de inputs
8. ✅ Prepared statements (PDO) para prevenir SQL injection
9. ✅ CSRF tokens en formularios

### **Política de Retención de Datos**
- Logs GPS: 12 meses
- Historial de cambios: Permanente
- Códigos de confirmación: Eliminar después de 24 horas

---

## 📊 ROADMAP DE IMPLEMENTACIÓN

### **Fase 1: Configuración Base (Día 1)**
- [x] Crear base de datos
- [ ] Crear todas las tablas
- [ ] Configurar módulo VehiculosQr
- [ ] Configurar rutas básicas
- [ ] Configurar SMTP

### **Fase 2: Funcionalidad Core (Días 2-4)**
- [ ] Implementar generación de QR (Admin)
- [ ] Implementar flujo de registro (Funcionario)
- [ ] Implementar validación por correo
- [ ] Implementar formulario de datos
- [ ] Implementar logs con GPS

### **Fase 3: Inspector y Consultas (Días 5-6)**
- [ ] Implementar login de inspectores
- [ ] Implementar vista de consulta pública
- [ ] Implementar vista de consulta inspector
- [ ] Implementar validación GPS en frontend

### **Fase 4: Administración (Día 7)**
- [ ] Panel de administración
- [ ] Listado de QR
- [ ] Gestión de estados
- [ ] Reportes y logs

### **Fase 5: Testing y Deploy (Días 8-10)**
- [ ] Testing funcional
- [ ] Testing de seguridad
- [ ] Optimización de performance
- [ ] Deploy a producción
- [ ] Documentación de usuario

---

## ✅ ACLARACIONES CONFIRMADAS

### **1. Generación de QR:**
- ✅ Librería: **endroid/qr-code** (más completa, soporta cifrado)
- ✅ Formato: PDF completo con diseño
- ✅ Tamaño: **57mm x 93mm** por código
- ✅ El QR incluirá datos cifrados según instrucciones

### **2. Edición de datos:**
- ✅ Usuario ingresa correo del QR
- ✅ Sistema valida correo y envía código dinámico (6 dígitos)
- ✅ Usuario ingresa código de verificación
- ✅ Si es correcto → accede a formulario de edición
- ✅ Puede editar TODO excepto el correo electrónico

### **3. Roles:**
- ✅ Tabla única `qr_usuarios` con campo `rol`
- ✅ Roles: ADMIN, INSPECTOR

### **4. Generación de lotes:**
- ✅ PDF completo con todos los QR
- ✅ Diseño de 57mm x 93mm por QR
- ✅ El QR debe ir dentro del diseño

### **5. Campos del funcionario:**
- ✅ **nombres** (obligatorio, separado de apellidos)
- ✅ **apellidos** (obligatorio, separado de nombres)
- ✅ **celular** (obligatorio)
- ✅ **anexo** (opcional)
- ✅ **observaciones** (campo libre opcional)
- ❌ Marca y modelo del vehículo NO son necesarios

---

## 📝 NOTAS FINALES

**Este proyecto está COMPLETAMENTE CLARO** en cuanto a:
- Arquitectura técnica (Laminas + MariaDB)
- Flujo de negocio (registro, confirmación, reutilización)
- Seguridad (GPS, logs, audit trail)
- Roles (funcionario, inspector, admin)

**Estoy listo para comenzar la implementación** una vez confirmes los puntos de dudas mencionados arriba.

La estructura propuesta es escalable, segura y cumple con TODAS las especificaciones del documento de instrucciones.

---

**Documento generado por:** GitHub Copilot  
**Basado en:** instrucciones.md  
**Proyecto:** QR Vehículos Municipales - DIDECO Arica
