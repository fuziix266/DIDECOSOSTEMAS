# Sistema de Identificación Vehicular Municipal con Código QR

Documento de arquitectura e instrucciones de desarrollo

---

## 1. Visión general del sistema

El objetivo es implementar un sistema web que permita identificar vehículos de funcionarios municipales mediante **códigos QR únicos**, asociados a un **correo institucional de funcionario** y reutilizables cuando el funcionario cambie de vehículo.

Características clave:

- Cada QR está asociado a **un correo de funcionario** (propietario del QR).
- El funcionario puede **editar todos sus datos**, incluyendo la patente, para reutilizar el mismo QR con un vehículo nuevo.
- La primera vez que se escanea el QR se realiza un flujo de **validación de correo** mediante código enviado por email.
- Todo escaneo del QR (de quien sea) queda registrado en un **log de escaneos**, con trazabilidad completa.
- El sistema exige **GPS obligatorio**: si el dispositivo no entrega coordenadas, NO se muestran datos y solo se muestra un mensaje de "activa el GPS".
- Solo los **inspectores municipales autenticados** pueden ver los datos personales del funcionario y del vehículo.
- El sistema se desarrollará con **Laminas MVC** y **MariaDB/MySQL**.

---

## 2. Equipo y roles para el análisis y desarrollo

Este apartado define los roles que intervienen en el diseño y ejecución del sistema y cómo deben interpretar la conversación previa y este documento.

### 2.1. Analista de procesos

Responsabilidades:

- Interpretar la necesidad funcional de identificar vehículos municipales.
- Definir el flujo de registro, confirmación de correo y uso del QR.
- Asegurar que las reglas de negocio se entiendan claramente (reutilización de QR, edición libre del funcionario, principio de buena fe, etc.).
- Documentar escenarios de uso y excepciones.

### 2.2. Arquitecto de software

Responsabilidades:

- Definir la arquitectura general basada en Laminas MVC + MariaDB/MySQL.
- Definir módulos, capas (controllers, services, models) y separación de responsabilidades.
- Proponer el modelo de datos y cómo se relacionan las tablas.
- Definir la interacción entre frontend, backend y servicios externos (SMTP para correos).

### 2.3. Desarrollador backend (Laminas)

Responsabilidades:

- Implementar controladores, servicios, factories, formularios y validadores.
- Implementar la lógica de generación de códigos, confirmación de correo, registro y edición de datos.
- Implementar la lógica de logs y validación de GPS.

### 2.4. Desarrollador frontend (HTML/JS/Bootstrap)

Responsabilidades:

- Crear las vistas de registro, confirmación, edición de datos y visualización para inspectores.
- Implementar en JavaScript la obtención obligatoria de GPS (geolocalización) y la comunicación con el backend.
- Presentar mensajes claros al usuario (funcionario/inspector/público).

### 2.5. DBA (Administrador de Base de Datos)

Responsabilidades:

- Crear las tablas según este documento.
- Ajustar tipos de datos, índices, constraints y opciones de engine.
- Revisar performance y planes de crecimiento (cantidad de registros de logs, limpieza, archivado).

### 2.6. Especialista en seguridad de la información

Responsabilidades:

- Revisar el flujo de autenticación y autorización.
- Revisar el tratamiento de datos personales (RUT, cargo, patente, GPS).
- Proponer políticas de retención de logs y GPS.
- Revisar la configuración de HTTPS, cookies, sesiones y cabeceras de seguridad.

### 2.7. Responsable de infraestructura

Responsabilidades:

- Asegurar que el entorno de Laminas y MariaDB/MySQL esté correctamente configurado.
- Configurar SMTP o servicio de correo para envío de códigos de verificación.
- Gestionar copias de seguridad de la base de datos.

---

## 3. Reglas de negocio principales

1. **QR asociado a correo de funcionario**

   - Cada QR se asocia a un único correo institucional de funcionario.
   - El correo pasa por un proceso de verificación mediante código enviado por email.

2. **Edición libre de datos por el funcionario (principio de buena fe)**

   - El funcionario, una vez autenticado/validado, puede editar todos sus datos: nombre, RUT, unidad, cargo, patente, etc.
   - Si cambia de vehículo, puede cambiar la patente del vehículo antiguo por la del nuevo reutilizando el mismo QR.

3. **Reutilización de QR**

   - El QR no muere con el vehículo; se mantiene asociado al funcionario y puede actualizarse con nuevas patentes cuando sea necesario.

4. **GPS obligatorio para mostrar cualquier información**

   - Todo flujo de visualización de datos requiere coordenadas GPS válidas.
   - Si el dispositivo deniega el acceso a la ubicación o no se pueden obtener coordenadas, el sistema **no muestra datos del funcionario ni del vehículo**, solo un mensaje solicitando activar el GPS.

5. **Registro de todos los escaneos con GPS**

   - Cada escaneo, sea de un funcionario, inspector o cualquier otra persona, genera un registro en `qr_logs`.
   - Se almacena: QR, tipo de evento, IP, user agent, coordenadas GPS, precisión, fecha y opcionalmente el inspector si está autenticado.

6. **Solo inspectores autenticados ven datos sensibles**

   - Un escaneo sin inspector autenticado solo muestra un mensaje genérico (no datos personales).
   - Un inspector autenticado puede ver la ficha del funcionario y del vehículo.

7. **Historial de cambios (audit trail)**

   - Cada vez que el funcionario o un admin modifican datos del registro, se genera un registro en `qr_registros_historial` con quién hizo el cambio, qué cambió y cuándo.

---

### 3.1. Restricción de dominio de correos y URL base del sistema

- Todos los correos de funcionarios e inspectores que se utilicen para autenticación y registro deben pertenecer al dominio **@municipalidadarica.cl**.
- El sistema web se desplegará dentro del sitio [**www.didecoarica.cl**](http://www.didecoarica.cl), utilizando como raíz del proyecto la ruta:
  - [**https://www.didecoarica.cl/vehiculos**](https://www.didecoarica.cl/vehiculos)
- Todas las rutas y endpoints descritos más adelante se entienden prefijados por "/vehiculos" en el entorno de producción (por ejemplo, `/qr/:uuid` → `https://www.didecoarica.cl/vehiculos/qr/:uuid`).

---

## 4. Arquitectura tecnológica

- **Backend**: Laminas MVC (PHP 8.x).
- **Base de datos**: MariaDB en entorno local, luego migración a MySQL en producción.
- **Frontend**: HTML5, Bootstrap, JavaScript puro (o framework liviano si se decide).
- **Correo**: SMTP municipal (o proveedor externo) con librería de envío de correos (por ejemplo, PHPMailer integrado en Laminas).
- **QR**: generación de imágenes de QR en backend (por ejemplo, librerías PHP típicas) o pre-generación externa; a nivel de arquitectura, el QR contiene una URL con `uuid_qr`.
- **Geolocalización**: `navigator.geolocation` en el navegador, enviando coordenadas al backend mediante `fetch`/AJAX.

---

## 5. Modelo de datos (DDL sugerido)

### 5.1. Tabla `qr_codigos`

Almacena los códigos QR generados. No contiene datos personales, solo el identificador del QR y su estado.

```sql
CREATE TABLE qr_codigos (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid_qr          CHAR(36) NOT NULL UNIQUE,
    estado           ENUM('PENDIENTE','ASIGNADO','INACTIVO') NOT NULL DEFAULT 'PENDIENTE',
    fecha_creacion   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_asignacion DATETIME NULL,
    observaciones    VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 5.2. Tabla `qr_registros`

Almacena la información del funcionario y del vehículo asociados a un QR. El dueño del QR es el correo del funcionario.

```sql
CREATE TABLE qr_registros (
    id                          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    qr_codigo_id                INT UNSIGNED NOT NULL UNIQUE,
    correo_funcionario          VARCHAR(255) NOT NULL,
    codigo_confirmacion         VARCHAR(10) NULL,
    codigo_confirmacion_expira  DATETIME NULL,
    correo_confirmado           TINYINT(1) NOT NULL DEFAULT 0,
    nombre                      VARCHAR(255) NULL,
    rut                         VARCHAR(20) NULL,
    unidad                      VARCHAR(255) NULL,
    cargo                       VARCHAR(100) NULL,
    patente                     VARCHAR(10) NULL,
    otros_datos                 TEXT NULL,
    fecha_registro              DATETIME NULL,
    fecha_actualizacion         DATETIME NULL,
    fecha_confirmacion          DATETIME NULL,
    creado_por_ip               VARCHAR(45) NULL,
    actualizado_por_ip          VARCHAR(45) NULL,

    CONSTRAINT fk_qr_registros_qr_codigos
      FOREIGN KEY (qr_codigo_id) REFERENCES qr_codigos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Notas:

- `qr_codigo_id` es `UNIQUE` para mantener relación 1:1 entre QR y registro.
- El correo del funcionario no se marca como UNIQUE para permitir que un funcionario tenga más de un QR si se requiere en el futuro.

### 5.3. Tabla `qr_registros_historial`

Guardará el historial de cambios en los datos del registro (audit trail).

```sql
CREATE TABLE qr_registros_historial (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    qr_registro_id      INT UNSIGNED NOT NULL,
    quien_correo        VARCHAR(255) NOT NULL,
    accion              ENUM('CREAR','EDITAR','RESET_QR','BORRAR') NOT NULL,
    cambios_json        JSON NULL,
    ip                  VARCHAR(45) NULL,
    user_agent          VARCHAR(255) NULL,
    fecha_evento        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_hist_qr_registro
      FOREIGN KEY (qr_registro_id) REFERENCES qr_registros(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Ejemplo de `cambios_json`:

```json
{
  "patente": ["ABCD11", "XYZC22"],
  "cargo": ["Inspector", "Jefe de Unidad"]
}
```

### 5.4. Tabla `qr_usuarios_inspectores`

Almacena usuarios tipo inspector que podrán ver los datos completos del funcionario/vehículo.

```sql
CREATE TABLE qr_usuarios_inspectores (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255) NOT NULL,
    correo          VARCHAR(255) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    activo          TINYINT(1) NOT NULL DEFAULT 1,
    creado_en       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en  DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 5.5. Tabla `qr_logs`

Registra todos los escaneos y eventos relevantes (incluyendo GPS y si fue inspector o no).

```sql
CREATE TABLE qr_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    qr_codigo_id    INT UNSIGNED NOT NULL,
    inspector_id    INT UNSIGNED NULL,
    tipo            ENUM('REGISTRO','CONSULTA_PUBLICA','CONSULTA_INSPECTOR','INTENTO_SIN_GPS') NOT NULL,
    ip              VARCHAR(45) NULL,
    user_agent      VARCHAR(255) NULL,
    lat             DECIMAL(10,7) NULL,
    lon             DECIMAL(10,7) NULL,
    gps_accuracy_m  FLOAT NULL,
    fecha_evento    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_qr_logs_qr_codigos
      FOREIGN KEY (qr_codigo_id) REFERENCES qr_codigos(id) ON DELETE CASCADE,
    CONSTRAINT fk_qr_logs_inspectores
      FOREIGN KEY (inspector_id) REFERENCES qr_usuarios_inspectores(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Interpretación de `tipo`:

- `REGISTRO`: eventos ligados al proceso de registro inicial.
- `CONSULTA_PUBLICA`: escaneo de alguien sin sesión de inspector (con GPS válido).
- `CONSULTA_INSPECTOR`: escaneo con inspector autenticado (con GPS válido).
- `INTENTO_SIN_GPS`: intento de escaneo sin GPS o sin permisos de ubicación.

---

## 6. Flujos funcionales detallados

### 6.1. Generación y entrega de QR (Administrador)

1. El administrador accede a un panel `/admin/qr`.
2. Solicita generar un lote de N códigos QR.
3. El sistema genera N registros en `qr_codigos` con:
   - `uuid_qr` = UUID v4 (o similar aleatorio).
   - `estado = 'PENDIENTE'`.
4. El sistema genera los QR (imágenes) con la URL que contemple el `uuid_qr`, por ejemplo:\
   `https://www.didecoarica.cl/vehiculos/qr/{uuid_qr}`
5. Estos QR se imprimen y se entregan a los funcionarios para adherirlos a sus vehículos.

### 6.2. Primer escaneo (funcionario) con GPS obligatorio

1. El funcionario escanea el código QR con su celular, abriendo la URL `GET /qr/{uuid_qr}`.
2. La vista inicial ejecuta JavaScript que intenta obtener GPS:
   - `navigator.geolocation.getCurrentPosition` con `enableHighAccuracy: true`.
3. Si **no se obtiene GPS** (error o permiso denegado):
   - El front muestra un mensaje claro:
     > "Para continuar debes activar el GPS o permitir el acceso a tu ubicación."
   - Se puede registrar un log con `tipo='INTENTO_SIN_GPS'` llamando a un endpoint especial o no registrar hasta que haya GPS (según política que se adopte).
   - No se muestran datos ni formularios de registro.
4. Si **se obtiene GPS** (lat/lon válidos):
   - El front envía un `POST` a `/qr/{uuid_qr}/scan` con payload:

     ```json
     {
       "lat": -18.1234567,
       "lon": -70.1234567,
       "gps_accuracy": 15.2
     }
     ```

   - El backend:

     - Busca `qr_codigos` por `uuid_qr`.
     - Valida estado (no INACTIVO).
     - Inserta un registro en `qr_logs` con:
       - `qr_codigo_id`, `tipo='REGISTRO'` o `CONSULTA_PUBLICA` dependiendo del contexto.
       - IP (servidor), `user_agent` (cabecera).
       - `lat`, `lon`, `gps_accuracy_m`.
5. Si el QR no tiene aún un registro asociado en `qr_registros` o `correo_confirmado=0`:
   - Se muestra formulario de ingreso de **correo del funcionario**.
   - El funcionario ingresa su correo institucional.
   - El backend genera `codigo_confirmacion` (6–8 dígitos), setea `codigo_confirmacion_expira` y envía correo.
   - Se guarda/actualiza `qr_registros` con `correo_funcionario`, `codigo_confirmacion`, etc.

### 6.3. Confirmación por correo

1. El funcionario recibe un email con el código de confirmación, por ejemplo:

   > "Su código de confirmación para el registro del vehículo es: 483921"

2. En la web se presenta un formulario para ingresar el código:

   - `POST /qr/{uuid_qr}/confirmar` con body: `{ codigo: "483921" }`.

3. El backend valida:

   - Que el `codigo_confirmacion` coincida con el almacenado en `qr_registros`.
   - Que `codigo_confirmacion_expira` no esté vencido.

4. Si es válido:

   - Se marca `correo_confirmado=1`.
   - Se setea `fecha_confirmacion`.
   - Se actualiza `qr_codigos.estado='ASIGNADO'` y `fecha_asignacion`.
   - Se registra evento en `qr_registros_historial` con `accion='CREAR'` y `quien_correo = correo_funcionario`.
   - Se redirige a la página de formulario de datos completos.

### 6.4. Formulario de datos del funcionario y del vehículo

Campos mínimos sugeridos:

- `nombre` (obligatorio).
- `rut` (obligatorio, con validador chileno sugerido en etapa posterior).
- `unidad` / dirección municipal.
- `cargo`.
- `patente` del vehículo.
- `otros_datos` (campo libre opcional).

Flujo:

1. El funcionario, todavía validado por el flujo de confirmación, accede al formulario en `/qr/{uuid_qr}/formulario`.
2. Envía los datos con `POST /qr/{uuid_qr}/guardar-datos`.
3. El backend:
   - Actualiza `qr_registros` con los nuevos campos.
   - Setea `fecha_registro` (si es primera vez) o `fecha_actualizacion` (si ya existía).
   - Genera un diff de cambios para `qr_registros_historial` con `accion='CREAR'` (si es primera carga) o `accion='EDITAR'`.

### 6.5. Edición posterior de datos (reutilización del QR)

1. El funcionario puede recibir un link de "gestión" en su correo institucional, por ejemplo: `https://www.didecoarica.cl/vehiculos/qr/gestionar/{token}`.
2. Ese token se vincula al `qr_registros` (por ejemplo, por tabla extra o campo de token temporal) y tiene expiración.
3. Al acceder, el sistema autentica al funcionario, le muestra el formulario con los datos actuales y permite modificar:
   - `nombre`, `rut`, `unidad`, `cargo`, `patente`, `otros_datos`.
4. Al guardar cambios, el backend:
   - Actualiza los campos.
   - Registra en `qr_registros_historial` con `accion='EDITAR'`, `quien_correo = correo_funcionario` (o el correo del admin si corresponde) y el diff en `cambios_json`.

### 6.6. Escaneo sin inspector autenticado (consulta pública)

1. Persona escanea QR.
2. El front solicita GPS. Si no hay GPS → solo mensaje de activación, se puede registrar `INTENTO_SIN_GPS`.
3. Con GPS válido, se llama a `/qr/{uuid_qr}/scan`.
4. El backend registra el log (`CONSULTA_PUBLICA`).
5. Como no hay inspector autenticado, **no** se devuelven datos personales. Se devuelve únicamente un mensaje genérico, por ejemplo:
   > "Identificación vehicular municipal. Los datos de este vehículo solo son visibles para personal autorizado."

### 6.7. Escaneo con inspector autenticado

1. El inspector inicia sesión en `/inspector/login` con `correo` y `password`.
2. Una vez autenticado, puede:
   - Escanear el QR con su celular y acceder a una URL tipo: `/inspector/qr/{uuid_qr}`.
3. El front, igual que en los otros flujos, **siempre debe solicitar GPS** antes de mostrar los datos.
4. Con GPS válido, el backend:
   - Registra en `qr_logs` un evento con `tipo='CONSULTA_INSPECTOR'` y `inspector_id`.
   - Obtiene el registro asociado en `qr_registros`.
   - Devuelve al inspector la ficha con:
     - Nombre, RUT, unidad, cargo, patente, otros datos.
     - Opcionalmente, datos de auditoría (fecha de registro, fecha última actualización).

---

## 7. Endpoints y rutas sugeridas (Laminas)

Se propone un módulo llamado `VehiculosQr` dentro de Laminas.

### 7.1. Rutas principales

- Público / funcionario:

  - `GET  /qr/:uuid` → acción inicial (carga vista que pide GPS).
  - `POST /qr/:uuid/scan` → recibe GPS, registra log, decide siguiente paso.
  - `POST /qr/:uuid/solicitar-correo` → recibe correo, genera código y envía email.
  - `POST /qr/:uuid/confirmar` → confirma correo con código.
  - `GET  /qr/:uuid/formulario` → muestra formulario de datos.
  - `POST /qr/:uuid/guardar-datos` → guarda datos del funcionario/vehículo.

- Gestión funcionario (opcional con tokens):

  - `GET  /qr/gestionar/:token` → muestra formulario de edición.
  - `POST /qr/gestionar/:token` → guarda cambios.

- Inspectores:

  - `GET  /inspector/login` → formulario login.
  - `POST /inspector/login` → procesa login.
  - `GET  /inspector/logout` → cierra sesión.
  - `GET  /inspector/qr/:uuid` → ficha del QR con datos (requiere sesión de inspector y GPS).

- Administrador:

  - `GET  /admin/qr` → listado QR.
  - `POST /admin/qr/generar-lote` → genera lote de QR.
  - `POST /admin/qr/:id/cambiar-estado` → cambiar estado a INACTIVO o PENDIENTE.

### 7.2. Controladores sugeridos

- `VehiculosQr\Controller\QrController`

  - `indexAction()` → maneja `GET /qr/:uuid`.
  - `scanAction()` → maneja `POST /qr/:uuid/scan`.
  - `solicitarCorreoAction()` → maneja `POST /qr/:uuid/solicitar-correo`.
  - `confirmarAction()` → maneja `POST /qr/:uuid/confirmar`.
  - `formularioAction()` → maneja `GET /qr/:uuid/formulario`.
  - `guardarDatosAction()` → maneja `POST /qr/:uuid/guardar-datos`.

- `VehiculosQr\Controller\InspectorAuthController`

  - `loginAction()`
  - `logoutAction()`

- `VehiculosQr\Controller\InspectorQrController`

  - `verAction()` → maneja `GET /inspector/qr/:uuid`.

- `VehiculosQr\Controller\AdminQrController`

  - `indexAction()` → listado.
  - `generarLoteAction()`.
  - `cambiarEstadoAction()`.

### 7.3. Servicios principales

- `VehiculosQr\Service\QrService`

  - Búsqueda de QR por `uuid_qr`.
  - Creación/actualización de `qr_registros`.
  - Generación de código de confirmación.

- `VehiculosQr\Service\CorreoService`

  - Envío de correo con código de confirmación y links.

- `VehiculosQr\Service\InspectorAuthService`

  - Manejo de login, verificación de credenciales y sesión.

- `VehiculosQr\Service\QrLogService`

  - Registro de eventos en `qr_logs`.

- `VehiculosQr\Service\QrHistorialService`

  - Registro de cambios en `qr_registros_historial`.

---

## 8. Lógica de GPS en el frontend (JavaScript)

Pseudocódigo para el flujo de obtención de GPS en la vista `/qr/:uuid`:

```javascript
function solicitarGPSYEnviarScan(uuid) {
  if (!navigator.geolocation) {
    mostrarMensaje("Tu dispositivo no soporta geolocalización. Activa GPS o usa otro dispositivo.");
    return;
  }

  navigator.geolocation.getCurrentPosition(
    function success(pos) {
      const lat = pos.coords.latitude;
      const lon = pos.coords.longitude;
      const acc = pos.coords.accuracy;

      fetch(`/qr/${uuid}/scan`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ lat: lat, lon: lon, gps_accuracy: acc })
      })
      .then(response => response.json())
      .then(data => {
        // data.nextStep podría indicar qué hacer:
        // 'SOLICITAR_CORREO', 'MOSTRAR_MENSAJE_PUBLICO', 'MOSTRAR_FORMULARIO', etc.
        manejarRespuestaScan(data);
      })
      .catch(err => {
        mostrarMensaje("Ocurrió un error al procesar la solicitud.");
      });
    },
    function error(err) {
      mostrarMensaje("Para continuar debes activar el GPS o permitir el acceso a tu ubicación.");
      // Opcional: se puede llamar a un endpoint para registrar INTENTO_SIN_GPS
    },
    {
      enableHighAccuracy: true,
      timeout: 10000,
      maximumAge: 0
    }
  );
}
```

La función `manejarRespuestaScan(data)` en el frontend decidirá qué vista mostrar (formularios, mensajes, etc.) según lo que devuelva el backend.

---

## 9. Seguridad y consideraciones de privacidad

- **HTTPS obligatorio** para proteger datos personales y GPS.
- Limitar y auditar el acceso a la tabla `qr_logs` y a la visualización de coordenadas.
- Definir una política de retención de datos:
  - Por ejemplo, conservar logs de GPS solo por X meses.
- Validar bien el formato del RUT y la patente (recomendado a nivel de backend).
- Limitar la cantidad de intentos de código de confirmación por correo.
- Evitar que los códigos de confirmación tengan vida útil muy larga (ej.: 30–60 minutos).

---

## 10. Roadmap de implementación

1. **DBA**: Crear todas las tablas según el DDL de este documento en el entorno de desarrollo (MariaDB).
2. **Arquitecto/Backend**: Crear el módulo `VehiculosQr` en Laminas con:
   - `module.config.php` con rutas para `QrController`, `InspectorAuthController`, `InspectorQrController`, `AdminQrController`.
   - Factories para los servicios (`QrService`, `CorreoService`, `QrLogService`, `QrHistorialService`).
3. **Backend**: Implementar flujo mínimo:
   - `GET /qr/:uuid` → carga vista que ejecuta JS de GPS.
   - `POST /qr/:uuid/scan` → valida QR, registra log y devuelve `nextStep` como JSON.
   - `POST /qr/:uuid/solicitar-correo` → genera código y envía email.
   - `POST /qr/:uuid/confirmar` → confirma correo.
4. **Frontend**: Implementar vistas con Bootstrap:
   - Vista inicial con botón "Iniciar" que llama a `solicitarGPSYEnviarScan`.
   - Vista de ingreso de correo.
   - Vista de ingreso de código de confirmación.
   - Vista de formulario de datos.
5. **Inspectores**:
   - Implementar login básico con `qr_usuarios_inspectores`.
   - Implementar vista `/inspector/qr/:uuid` respetando el flujo de GPS.
6. **Administrador**:
   - Implementar generación de lotes de QR y listado básico.
7. **Seguridad y pulido**:
   - Revisar validaciones, tratamiento de errores, mensajes al usuario.
   - Revisar manejo de datos de GPS y cumplimiento de política de privacidad.

---

Este documento sirve como guía completa para el equipo de análisis, diseño y desarrollo del sistema de identificación vehicular municipal con QR, cubriendo reglas de negocio, arquitectura, modelo de datos, flujos, rutas y consideraciones de seguridad y privacidad.

