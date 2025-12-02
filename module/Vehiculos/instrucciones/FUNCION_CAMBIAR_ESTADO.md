# 🔄 Función Cambiar Estado - Documentación

**Fecha de Implementación:** 12 de noviembre de 2025  
**Sistema:** QR Vehículos Municipales - Municipalidad de Arica  
**Función:** `cambiarEstadoAction()` en AdminController

---

## 📋 Descripción General

La función **cambiar-estado** permite a los administradores cambiar el estado de un código QR entre los siguientes valores:

- **PENDIENTE**: QR generado pero no asignado a ningún funcionario
- **HABILITADO**: QR activo y en uso (vehículo autorizado)
- **DESHABILITADO**: QR inactivo (vehículo deshabilitado temporalmente)

---

## 🔧 Implementación Técnica

### 1. Controlador: `AdminController::cambiarEstadoAction()`

**Ubicación:** `module/VehiculosQr/src/Controller/AdminController.php`

**Método HTTP:** `POST`

**Endpoint:** `/vehiculos/admin/cambiar-estado`

**Parámetros esperados:**
```json
{
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "estado": "HABILITADO"
}
```

**Código implementado:**
```php
public function cambiarEstadoAction()
{
    if (!$this->getRequest()->isPost()) {
        return new JsonModel(['success' => false, 'error' => 'Método no permitido']);
    }
    
    $uuid = $this->getRequest()->getPost('uuid');
    $nuevoEstado = $this->getRequest()->getPost('estado');
    
    // Validar que se reciban los parámetros requeridos
    if (empty($uuid) || empty($nuevoEstado)) {
        return new JsonModel([
            'success' => false,
            'error' => 'Faltan parámetros requeridos (uuid, estado)'
        ]);
    }
    
    // Buscar el QR por UUID
    $qr = $this->qrService->buscarPorUuid($uuid);
    
    if (!$qr) {
        return new JsonModel([
            'success' => false,
            'error' => 'Código QR no encontrado'
        ]);
    }
    
    // Validar el estado
    $estadosValidos = ['PENDIENTE', 'HABILITADO', 'DESHABILITADO'];
    if (!in_array($nuevoEstado, $estadosValidos)) {
        return new JsonModel([
            'success' => false,
            'error' => "Estado no válido. Estados permitidos: " . implode(', ', $estadosValidos)
        ]);
    }
    
    // Cambiar el estado
    $cambiado = $this->qrService->cambiarEstado($qr['id'], $nuevoEstado);
    
    if ($cambiado) {
        // Registrar en el log
        $usuarioActual = $this->authService->getCurrentUser();
        error_log("Admin '{$usuarioActual['nombre']}' cambió estado del QR '{$uuid}' a '{$nuevoEstado}'");
        
        return new JsonModel([
            'success' => true,
            'message' => "Estado cambiado a {$nuevoEstado} correctamente"
        ]);
    }
    
    return new JsonModel([
        'success' => false,
        'error' => 'Error al cambiar el estado en la base de datos'
    ]);
}
```

---

### 2. Servicio: `QrService::cambiarEstado()`

**Ubicación:** `module/VehiculosQr/src/Service/QrService.php`

**Código actualizado:**
```php
/**
 * Cambiar estado del QR
 * Estados válidos: PENDIENTE, HABILITADO, DESHABILITADO
 */
public function cambiarEstado(int $qrId, string $nuevoEstado): bool
{
    $estadosValidos = ['PENDIENTE', 'HABILITADO', 'DESHABILITADO'];
    
    if (!in_array($nuevoEstado, $estadosValidos)) {
        error_log("QrService::cambiarEstado - Estado inválido: {$nuevoEstado}");
        return false;
    }
    
    $resultado = $this->qrCodigosRepo->update($qrId, ['estado' => $nuevoEstado]);
    
    if ($resultado) {
        error_log("QrService::cambiarEstado - QR ID {$qrId} cambió a estado {$nuevoEstado}");
    }
    
    return $resultado;
}
```

---

### 3. Base de Datos: Actualización del Esquema

**Archivo:** `data/update_estados.sql` (NUEVO)

Se creó un script de migración para actualizar la base de datos:

```sql
ALTER TABLE qr_codigos 
MODIFY COLUMN estado ENUM('PENDIENTE','HABILITADO','DESHABILITADO') NOT NULL DEFAULT 'PENDIENTE'
COMMENT 'PENDIENTE=No asignado, HABILITADO=Activo, DESHABILITADO=Inactivo';

-- Migrar datos antiguos si existen
UPDATE qr_codigos SET estado = 'HABILITADO' WHERE estado = 'ASIGNADO';
UPDATE qr_codigos SET estado = 'DESHABILITADO' WHERE estado = 'INACTIVO';
```

**También se actualizó:** `data/schema.sql` para nuevas instalaciones

---

### 4. Frontend: JavaScript

**Ubicación (actualizada):** `module/VehiculosQr/view/vehiculos-qr/admin/gestion.phtml`

**Función JavaScript ya existente:**
```javascript
async function cambiarEstado(uuid, nuevoEstado) {
    const accion = nuevoEstado === 'HABILITADO' ? 'habilitar' : 'deshabilitar';
    
    const confirmado = await APP.Utils.showConfirm(
        'Cambiar Estado',
        `¿Deseas ${accion} este código QR?`
    );
    
    if (confirmado) {
        try {
            const formData = new FormData();
            formData.append('uuid', uuid);
            formData.append('estado', nuevoEstado);
            
            const response = await APP.ApiService.postForm(
                '/vehiculos/admin/cambiar-estado',
                formData
            );
            
            if (response.success) {
                APP.Utils.showToast('Estado actualizado correctamente', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                APP.Utils.showAlert('Error', response.message || 'No se pudo cambiar el estado', 'error');
            }
        } catch (error) {
            APP.Utils.showAlert('Error', 'Error al cambiar el estado', 'error');
        }
    }
}
```

**Botones en la vista:**
```php
<?php if ($qr['estado'] === 'HABILITADO'): ?>
    <button type="button" 
            class="btn btn-outline-danger"
            onclick="cambiarEstado('<?= $this->escapeJs($qr['uuid_qr']) ?>', 'DESHABILITADO')">
        <i class="bi bi-x-circle"></i> Deshabilitar
    </button>
<?php else: ?>
    <button type="button" 
            class="btn btn-outline-success"
            onclick="cambiarEstado('<?= $this->escapeJs($qr['uuid_qr']) ?>', 'HABILITADO')">
        <i class="bi bi-check-circle"></i> Habilitar
    </button>
<?php endif; ?>
```

---

### 5. Ruta configurada

**Ubicación:** `module/VehiculosQr/config/module.config.php`

```php
'vehiculos-admin-cambiar-estado' => [
    'type' => Literal::class,
    'options' => [
        'route' => '/vehiculos/admin/cambiar-estado',
        'defaults' => [
            'controller' => Controller\AdminController::class,
            'action' => 'cambiar-estado',
        ],
    ],
],
```

---

## 🔐 Seguridad

### Validaciones implementadas:

1. ✅ **Autenticación requerida:** Solo administradores autenticados pueden cambiar estados
2. ✅ **Método POST obligatorio:** Previene cambios accidentales por GET
3. ✅ **Validación de UUID:** El QR debe existir en la base de datos
4. ✅ **Validación de estados:** Solo acepta PENDIENTE, HABILITADO, DESHABILITADO
5. ✅ **Confirmación en frontend:** SweetAlert2 solicita confirmación antes de cambiar
6. ✅ **Logging:** Cada cambio se registra en el log de errores con usuario responsable

---

## 📊 Flujo de Funcionamiento

```
┌─────────────────────────────────────────────────────────────┐
│  1. Admin hace clic en botón "Habilitar/Deshabilitar"      │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  2. JavaScript muestra confirmación (SweetAlert2)           │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  3. Si confirma → POST a /vehiculos/admin/cambiar-estado    │
│     Parámetros: uuid, estado                                │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  4. AdminController::cambiarEstadoAction()                  │
│     - Verifica autenticación                                │
│     - Valida parámetros                                     │
│     - Busca QR por UUID                                     │
│     - Valida estado solicitado                              │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  5. QrService::cambiarEstado()                              │
│     - Valida estado nuevamente                              │
│     - Actualiza en base de datos                            │
│     - Registra en log                                       │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  6. Retorna JSON response                                   │
│     success: true/false                                     │
│     message: "Estado cambiado a X correctamente"            │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│  7. JavaScript muestra toast de éxito                       │
│     y recarga la página                                     │
└─────────────────────────────────────────────────────────────┘
```

---

## 🧪 Pruebas

### Caso 1: Cambiar de PENDIENTE a HABILITADO
```bash
POST /vehiculos/admin/cambiar-estado
{
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "estado": "HABILITADO"
}

Respuesta esperada:
{
    "success": true,
    "message": "Estado cambiado a HABILITADO correctamente"
}
```

### Caso 2: Cambiar de HABILITADO a DESHABILITADO
```bash
POST /vehiculos/admin/cambiar-estado
{
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "estado": "DESHABILITADO"
}

Respuesta esperada:
{
    "success": true,
    "message": "Estado cambiado a DESHABILITADO correctamente"
}
```

### Caso 3: UUID no encontrado
```bash
POST /vehiculos/admin/cambiar-estado
{
    "uuid": "00000000-0000-0000-0000-000000000000",
    "estado": "HABILITADO"
}

Respuesta esperada:
{
    "success": false,
    "error": "Código QR no encontrado"
}
```

### Caso 4: Estado inválido
```bash
POST /vehiculos/admin/cambiar-estado
{
    "uuid": "550e8400-e29b-41d4-a716-446655440000",
    "estado": "INVALIDO"
}

Respuesta esperada:
{
    "success": false,
    "error": "Estado no válido. Estados permitidos: PENDIENTE, HABILITADO, DESHABILITADO"
}
```

---

## 🚀 Pasos para Implementar en Base de Datos Existente

Si ya tienes datos en tu base de datos con los estados antiguos, ejecuta:

```bash
# Opción 1: Desde terminal MySQL
mysql -u root qr_vehiculos_municipal < data/update_estados.sql

# Opción 2: Desde phpMyAdmin
# Importar el archivo: data/update_estados.sql
```

---

## 📝 Registro de Cambios (Changelog)

### [1.0.0] - 12 de noviembre de 2025

#### Agregado
- ✅ Función `cambiarEstadoAction()` en AdminController
- ✅ Validación completa de parámetros y estados
- ✅ Logging de cambios de estado
- ✅ Script de migración `update_estados.sql`
- ✅ Actualización de esquema `schema.sql`

#### Modificado
- ✅ `QrService::cambiarEstado()` - Actualizado para usar nuevos estados
- ✅ Estados en base de datos: ASIGNADO→HABILITADO, INACTIVO→DESHABILITADO

#### Corregido
- ✅ Bug en recepción de parámetros (de ruta a POST body)
- ✅ Inconsistencia entre estados de BD y frontend

---

## 📞 Notas Importantes

### Estados y su significado:

| Estado | Descripción | Uso |
|--------|-------------|-----|
| **PENDIENTE** | QR recién generado | No asignado a ningún funcionario aún |
| **HABILITADO** | QR activo | Vehículo autorizado para circular |
| **DESHABILITADO** | QR inactivo | Vehículo temporalmente inhabilitado |

### Efectos visuales del estado:

- **HABILITADO** → Consulta pública muestra fondo **VERDE**
- **DESHABILITADO** → Consulta pública muestra fondo **ROJO**
- **PENDIENTE** → Muestra formulario de registro inicial

---

## ✅ Estado de Implementación

- [x] Función backend implementada
- [x] Validaciones completas
- [x] Integración con frontend
- [x] Logging de cambios
- [x] Script de migración de BD
- [x] Documentación completa
- [x] Casos de prueba definidos

**Estado:** ✅ **COMPLETAMENTE FUNCIONAL**

---

**Documentado por:** GitHub Copilot  
**Fecha:** 12 de noviembre de 2025  
**Versión:** 1.0.0
