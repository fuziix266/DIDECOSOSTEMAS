# ESTRUCTURA DE VISTAS - MÓDULO DEPARTAMENTOS DIDECO

**Fecha de análisis:** 18 de noviembre de 2025  
**Módulo:** Departamentos  
**Sistema:** DIDECO - Ilustre Municipalidad de Arica  

---

## RESUMEN EJECUTIVO

Este documento analiza la estructura de las vistas del módulo Departamentos, identificando patrones, secciones comunes y oportunidades de normalización de datos para migración a base de datos.

### Estadísticas Generales
- **Total de departamentos identificados:** 17
- **Total de archivos .phtml analizados:** 153+
- **Tipos de vistas:** 
  - Vistas índice (menús principales): ~17
  - Vistas de detalle (trámites específicos): ~136

---

## ESTRUCTURA GENERAL DE DEPARTAMENTOS

### Departamentos Principales (Menú Principal)

| # | Nombre Departamento | Ruta | Icono Bootstrap | Subdirectorios |
|---|---------------------|------|-----------------|----------------|
| 1 | Enlace Norte | `/departamentos/enlacenorte` | `bi-signpost-2` | 19 trámites |
| 2 | Acción Social | `/departamentos/serviciosocial` | `bi-people-fill` | 8 servicios |
| 3 | Adulto Mayor (OCAM) | `/departamentos/ocam` | `bi-person-arms-up` | 8 servicios |
| 4 | RSH | `/departamentos/rsh` | `bi-people` | 1 trámite |
| 5 | Subsidio y Pensiones | `/departamentos/subsidioypensiones` | `bi-cash-coin` | 14 trámites |
| 6 | Oficina Local de la Niñez (OLN) | `/departamentos/oln` | `bi-person-hearts` | 4 trámites |
| 7 | Mujer y Equidad de Género | `/departamentos/mujeryequidad` | `bi-gender-female` | 11 servicios |
| 8 | ODIMA | `/departamentos/odima` | `bi-globe-americas` | 7 servicios |
| 9 | Afrodescendientes | `/departamentos/afrodescendientes` | `bi-person-badge` | 4 trámites |
| 10 | Juventud | `/departamentos/juventud` | `bi-lightning-charge` | 4 servicios |
| 11 | Discapacidad | `/departamentos/discapacidad` | `bi-person-wheelchair` | 18 servicios |
| 12 | Gestión Habitacional | `/departamentos/gestionhabitacional` | `bi-building` | 6 trámites |
| 13 | Comodatos | `/departamentos/comodato` | `bi-file-earmark-text` | 1 trámite |
| 14 | OMIL | `/departamentos/omil` | `bi-briefcase` | 7 trámites |
| 15 | Derechos Humanos (DDHH) | `/departamentos/derechoshumanos` | `bi-person-check` | 7 trámites |
| 16 | Defensoría Ciudadana | `/departamentos/defensoriaciudadana` | `bi-shield-check` | 1 servicio |
| 17 | Presupuesto Participativo | `/departamentos/presupuestoparticipativo` | `bi-cash-coin` | 3 programas |

---

## PATRÓN DE ESTRUCTURA DE VISTAS

### TIPO 1: Vista Índice (Menú de Departamento)

**Archivos identificados:** `index.phtml` en cada carpeta de departamento

#### Estructura HTML Común:
```html
<div class="container">
    <!-- Encabezado -->
    <div class="text-center mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3" style="color: #15356c;">
                    <i class="[ICONO_FONTAWESOME/BOOTSTRAP]"></i>
                    [NOMBRE_DEPARTAMENTO]
                </h1>
                <p class="lead text-muted">
                    [DESCRIPCIÓN_DEPARTAMENTO]
                </p>
            </div>
        </div>
    </div>

    <!-- Grid de servicios/trámites -->
    <div class="grid-container">
        <!-- Tarjetas de trámites -->
        <a href="[URL_TRAMITE]" class="card-departamento">
            <div class="card-icon">
                <i class="[ICONO_BOOTSTRAP]"></i>
            </div>
            <div class="card-content">
                <div class="card-title">[NOMBRE_TRAMITE]</div>
                <div class="descripcion">
                    [DESCRIPCIÓN_BREVE_TRAMITE]
                </div>
            </div>
        </a>
    </div>

    <!-- Banner de contacto -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-10">
            <div class="info-banner">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <i class="bi bi-info-circle info-icon"></i>
                    </div>
                    <div class="col-md-8">
                        <h6 class="mb-2 fw-bold">¿Tienes dudas o quieres postular?</h6>
                        <p class="mb-0">
                            [MENSAJE_CONTACTO]
                        </p>
                    </div>
                    <div class="col-md-2 text-center">
                        <a href="/didecosistemas/public/departamentos/correos?correo=[EMAIL]" 
                           class="btn btn-outline-primary btn-sm">Contactar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

#### Elementos Estructurales:

| Sección | Tipo | Campos Identificados | Obligatorio |
|---------|------|---------------------|-------------|
| **Head PHP** | Metadata | `headTitle`, `pageTitle` | ✅ Sí |
| **Encabezado** | Header | Icono, Título, Descripción | ✅ Sí |
| **Grid de Tarjetas** | Content | Array de trámites/servicios | ✅ Sí |
| **Banner Contacto** | Footer | Mensaje, Email, Botón | ✅ Sí |

---

### TIPO 2: Vista de Detalle de Trámite

**Archivos identificados:** Vistas específicas (ej: `suf.phtml`, `alimentos.phtml`, etc.)

#### Estructura HTML Común:
```html
<?php
$this->layout()->menuBackUrl = '[URL_DEPARTAMENTO]';
$this->headTitle('[NOMBRE_TRAMITE]');
?>

<div class="container py-5">
    <h2 class="mb-4">DIDECO - [NOMBRE_DEPARTAMENTO]</h2>
    
    <div class="mb-4">
        <h4>[NOMBRE_TRAMITE]</h4>
        <p>[DESCRIPCIÓN_LARGA]</p>
    </div>

    <div class="accordion" id="accordion[ID]">
        <!-- Documentos Requeridos -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed">
                    Documentos Requeridos
                </button>
            </h2>
            <div class="accordion-collapse collapse">
                <div class="accordion-body">
                    [LISTA_DOCUMENTOS]
                </div>
            </div>
        </div>

        <!-- Requisitos del Usuario -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed">
                    Requisitos del Usuario
                </button>
            </h2>
            <div class="accordion-collapse collapse">
                <div class="accordion-body">
                    [LISTA_REQUISITOS]
                </div>
            </div>
        </div>

        <!-- Instrucciones Paso a Paso -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed">
                    Instrucciones Paso a Paso
                </button>
            </h2>
            <div class="accordion-collapse collapse">
                <div class="accordion-body">
                    [PASOS]
                </div>
            </div>
        </div>

        <!-- Tiempo Estimado de Respuesta -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed">
                    Tiempo Estimado de Respuesta
                </button>
            </h2>
            <div class="accordion-collapse collapse">
                <div class="accordion-body">
                    [TIEMPO]
                </div>
            </div>
        </div>

        <!-- Responsable del Trámite -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed">
                    Responsable del Trámite
                </button>
            </h2>
            <div class="accordion-collapse collapse">
                <div class="accordion-body">
                    [NOMBRE_RESPONSABLE]
                </div>
            </div>
        </div>
    </div>
</div>
```

#### Secciones de Detalle de Trámite:

| Sección | Contenido | Formato | Observaciones |
|---------|-----------|---------|---------------|
| **Documentos Requeridos** | Lista de documentos necesarios | HTML (lista `<br>`) | Varía por trámite |
| **Requisitos del Usuario** | Condiciones del usuario | HTML (lista `<br>`) | Similar a documentos |
| **Instrucciones Paso a Paso** | Proceso del trámite | HTML (lista numerada) | Puede ser texto simple |
| **Tiempo Estimado** | Plazo de respuesta | Texto plano | Ej: "2 meses", "inmediato" |
| **Responsable** | Nombre del funcionario | Texto plano | Puede incluir cargo |

---

## ESTRUCTURA DE DATOS NORMALIZADA

### Base de Datos Propuesta

#### Tabla: `departamentos`
```sql
CREATE TABLE departamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    descripcion TEXT,
    icono_bootstrap VARCHAR(100),
    icono_fontawesome VARCHAR(100),
    color_primario VARCHAR(7) DEFAULT '#15356c',
    color_secundario VARCHAR(7) DEFAULT '#6537b6',
    email_contacto VARCHAR(255),
    mensaje_contacto TEXT,
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Campos:**
- `id`: Identificador único
- `nombre`: Nombre completo del departamento
- `slug`: URL amigable (ej: "enlace-norte")
- `descripcion`: Texto descriptivo (lead)
- `icono_bootstrap`: Clase del icono Bootstrap Icons
- `icono_fontawesome`: Clase del icono FontAwesome (opcional)
- `color_primario`: Color del título
- `color_secundario`: Color del icono
- `email_contacto`: Email de contacto del departamento
- `mensaje_contacto`: Mensaje del banner de contacto
- `orden`: Orden de aparición en el menú principal
- `activo`: Si está visible o no

---

#### Tabla: `tramites`
```sql
CREATE TABLE tramites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    departamento_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    descripcion_corta TEXT,
    descripcion_larga TEXT,
    icono_bootstrap VARCHAR(100),
    documentos_requeridos TEXT,
    requisitos_usuario TEXT,
    instrucciones_paso_paso TEXT,
    tiempo_estimado VARCHAR(255),
    responsable_nombre VARCHAR(255),
    responsable_cargo VARCHAR(255),
    monto_beneficio DECIMAL(10, 2) NULL,
    url_externa VARCHAR(500) NULL,
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_slug_departamento (departamento_id, slug)
);
```

**Campos:**
- `id`: Identificador único
- `departamento_id`: Relación con departamentos
- `nombre`: Nombre del trámite/servicio
- `slug`: URL amigable
- `descripcion_corta`: Para tarjetas (grid)
- `descripcion_larga`: Para vista de detalle
- `icono_bootstrap`: Icono de la tarjeta
- `documentos_requeridos`: HTML/Texto de documentos
- `requisitos_usuario`: HTML/Texto de requisitos
- `instrucciones_paso_paso`: HTML/Texto de pasos
- `tiempo_estimado`: Ej: "2 meses", "inmediato"
- `responsable_nombre`: Nombre del responsable
- `responsable_cargo`: Cargo del responsable
- `monto_beneficio`: Monto monetario (si aplica)
- `url_externa`: Link externo (si requiere)
- `orden`: Orden dentro del departamento

---

#### Tabla: `documentos_tramite` (Opcional - Normalización completa)
```sql
CREATE TABLE documentos_tramite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tramite_id INT NOT NULL,
    nombre_documento VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    es_obligatorio TINYINT(1) DEFAULT 1,
    orden INT DEFAULT 0,
    FOREIGN KEY (tramite_id) REFERENCES tramites(id) ON DELETE CASCADE
);
```

---

#### Tabla: `requisitos_tramite` (Opcional - Normalización completa)
```sql
CREATE TABLE requisitos_tramite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tramite_id INT NOT NULL,
    descripcion TEXT NOT NULL,
    tipo ENUM('documento', 'condicion', 'edad', 'residencia', 'rsh', 'otro') DEFAULT 'condicion',
    orden INT DEFAULT 0,
    FOREIGN KEY (tramite_id) REFERENCES tramites(id) ON DELETE CASCADE
);
```

---

#### Tabla: `pasos_tramite` (Opcional - Normalización completa)
```sql
CREATE TABLE pasos_tramite (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tramite_id INT NOT NULL,
    numero_paso INT NOT NULL,
    titulo VARCHAR(255) NULL,
    descripcion TEXT NOT NULL,
    FOREIGN KEY (tramite_id) REFERENCES tramites(id) ON DELETE CASCADE
);
```

---

## INVENTARIO COMPLETO DE DEPARTAMENTOS Y TRÁMITES

### 1. ENLACE NORTE (19 trámites)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Subsidio Único Familiar (SUF) | `subsidiofamiliar` | `bi-currency-dollar` | Subsidio |
| Subsidio Maternal | `subsidiomaternal` | `bi-heart-pulse` | Subsidio |
| Subsidio Recién Nacido | `subsidioreciennacido` | `bi-emoji-smile` | Subsidio |
| Subsidio Madre | `subsidiomadre` | `bi-person-heart` | Subsidio |
| SUF Menores de 18 años | `subsidiocesantiamenores` | `bi-people` | Subsidio |
| Subsidio Familiar Duplo | `subsidiofamiliarduplo` | `bi-plus-circle` | Subsidio |
| Pensiones | `pensiones` | `bi-bank` | Información |
| Pensión Garantizada Universal (PGU) | `pensionuniversal` | `bi-shield-check` | Pensión |
| Pensión Básica Solidaria de Invalidez | `pensioninvalidez` | `bi-person-wheelchair` | Pensión |
| Aporte Previsional Solidario de Invalidez | `aporteinvalidez` | `bi-graph-up-arrow` | Aporte |
| Bono Por Hijo | `bonoporhijo` | `bi-gift` | Beneficio |
| Subsidio de Discapacidad | `subsidiodiscapacidad` | `bi-universal-access` | Subsidio |
| Subsidios Agua Potable | `subsidioagua` | `bi-droplet` | Subsidio |
| SAP Rural | `saprural` | `bi-tree` | Subsidio |
| SAP Urbano | `sapurbano` | `bi-buildings` | Subsidio |
| Comodato | `comodato` | `bi-file-earmark-text` | Asesoría |
| Territorial Juntas Vecinales | `juntasvecinales` | `bi-house-door` | Asesoría |
| Territorial Organizaciones Funcionales | `organizacionesfuncionales` | `bi-diagram-3` | Asesoría |
| Equipo de Logística y Emergencia | `emergencias` | `bi-exclamation-triangle` | Servicio |

---

### 2. ACCIÓN SOCIAL (8 servicios)

| Servicio | Slug | Icono | Tipo |
|----------|------|-------|------|
| Alimentos | `alimentos` | `bi-basket` | Ayuda Social |
| Materiales de Construcción | `materialesconstruccion` | `bi-hammer` | Ayuda Social |
| Piezas Prefabricadas | `piezasprefabricadas` | `bi-bricks` | Ayuda Social |
| Ayuda en Materias de Salud | `ayudasalud` | `bi-heart-pulse` | Ayuda Social |
| Pasajes | `pasajes` | `bi-bus-front` | Ayuda Social |
| Pago de Servicios Básicos | `pagoservicios` | `bi-receipt` | Ayuda Social |
| Agua Potable por Camión Aljibe | `camionaljibe` | `bi-truck` | Servicio |
| Canon de Arrendamiento | `canonarrendamiento` | `bi-house-heart` | Ayuda Social |

---

### 3. ADULTO MAYOR - OCAM (8 servicios)

| Servicio | Slug | Icono | Tipo |
|----------|------|-------|------|
| Atención Psicológica | `atencionpsicologica` | `bi-emoji-smile` | Atención |
| Atención Social | `atencionsocial` | `bi-people` | Atención |
| Talleres y Profesores | `atencionprofesores` | `bi-journal-richtext` | Taller |
| Gestores en Terreno | `gestoresenterreno` | `bi-person-walking` | Servicio |
| Uso de Salones | `atencionsalones` | `bi-door-open` | Servicio |
| Atención Kinésica | `atencionkinesica` | `bi-heart-pulse` | Atención |
| Clases Personalizadas | `clasespersonalizadas` | `bi-calendar-check` | Taller |
| Asesorías en Subsidios | `asesorias` | `bi-cash-coin` | Asesoría |

---

### 4. RSH (1 trámite)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Solicitud de Registro Social de Hogares | `solicitud` | `bi-card-list` | Trámite |

---

### 5. SUBSIDIO Y PENSIONES (14 trámites)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Subsidio Único Familiar (SUF) | `suf` | `bi-person-bounding-box` | Subsidio |
| Subsidio Maternal | `maternal` | `bi-gender-female` | Subsidio |
| Subsidio Recién Nacido | `reciennacido` | `bi-baby` | Subsidio |
| Subsidio Madre | `madre` | `bi-person-hearts` | Subsidio |
| SUF por Menores de 18 años | `menores` | `bi-emoji-smile` | Subsidio |
| Subsidio Familiar Duplo | `familiarduplo` | `bi-discord` | Subsidio |
| Pensión Garantizada Universal | `pgu` | `bi-cash-coin` | Pensión |
| PBSI - Invalidez | `pbsi` | `bi-person-x` | Pensión |
| APSI | `apsi` | `bi-heart-pulse` | Aporte |
| Bono por Hijo | `bonoporhijo` | `bi-gift` | Beneficio |
| Subsidio de Discapacidad | `discapacidad` | `bi-universal-access` | Subsidio |
| Subsidio Agua Potable | `aguapotable` | `bi-droplet-half` | Subsidio |
| SAP Rural | `saprural` | `bi-tree` | Subsidio |
| SAP Urbano | `sapurbano` | `bi-house-door` | Subsidio |

---

### 6. OFICINA LOCAL DE LA NIÑEZ - OLN (4 trámites)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Demandas Espontáneas | `demanda` | `bi-exclamation-triangle` | Denuncia |
| Protección Universal | `proteccionuniversal` | `bi-people` | Protección |
| Protección de Urgencia | `proteccionurgencia` | `bi-shield-exclamation` | Protección |
| Promoción de Derechos | `promocionderechosnna` | `bi-megaphone` | Actividad |

---

### 7. MUJER Y EQUIDAD DE GÉNERO (11 servicios)

| Servicio | Slug | Icono | Tipo |
|----------|------|-------|------|
| Taller de Emprendimiento | `emprendimiento` | `bi-lightbulb` | Taller |
| Curso de Capacitación | `capacitacion` | `bi-journal-bookmark` | Taller |
| Terapias Complementarias | `terapias` | `bi-flower2` | Atención |
| Taller de Prevención de la Violencia | `pervencion` | `bi-shield-plus` | Taller |
| Orientación Jurídica | `orientacion` | `bi-journal-check` | Asesoría |
| Análisis de Causas Judiciales | `causasjudiciales` | `bi-file-earmark-text` | Asesoría |
| Retención Fondos Bancarios/AFP | `deudorespension` | `bi-bank` | Asesoría |
| Presentación de Escritos | `escritos` | `bi-pencil-square` | Asesoría |
| Demanda Autorización de Salida del País | `autorizaciones` | `bi-pass` | Asesoría |
| Acompañamiento a Denuncias | `denuncias` | `bi-person-lines-fill` | Servicio |
| Festival Mujeres Creadoras | `festival` | `bi-stars` | Evento |

---

### 8. ODIMA (7 servicios)

| Servicio | Slug | Icono | Tipo |
|----------|------|-------|------|
| Acreditación Indígena | `acreditacionindigena` | `bi-award` | Trámite |
| Beca Indígena | `becaindigena` | `bi-mortarboard` | Orientación |
| Conformar Asociación Indígena | `informacionasociacionindigena` | `bi-people-fill` | Información |
| Inscribir Asociación Indígena | `inscripcionasociacionindigena` | `bi-journal-plus` | Trámite |
| Micro Emprendimiento CONADI | `microemprendimiento` | `bi-shop` | Orientación |
| Subsidio Habitacional | `subsidiohabitacional` | `bi-house-heart` | Información |
| Actividades Culturales Indígenas | `actividadestematicas` | `bi-calendar-event` | Actividad |

---

### 9. AFRODESCENDIENTES (4 trámites)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Apoyo a Organizaciones Afrodescendientes | `organizaciones` | `bi-people-fill` | Apoyo |
| Inscripción Emprendedora | `emprendimiento` | `bi-clipboard-check` | Registro |
| Catastro Afrodescendiente | `catastro` | `bi-journal-text` | Registro |
| Inscripción a Talleres | `talleres` | `bi-easel3` | Registro |

---

### 10. JUVENTUD (4 servicios)

| Servicio | Slug | Icono | Tipo |
|----------|------|-------|------|
| Voluntariado | `voluntariado` | (pendiente) | Actividad |
| Centros de Alumnos | `centrosdealumnos` | (pendiente) | Apoyo |
| Aprende y Emprende | `aprendeyemprende` | (pendiente) | Programa |
| Apoyo a Iniciativas | `apoyoiniciativas` | (pendiente) | Apoyo |

---

### 11. DISCAPACIDAD (18 servicios)

| Servicio | Slug | Icono | Tipo |
|----------|------|-------|------|
| Terapia Ocupacional | `terapiaocupacional` | `bi-people` | Atención |
| Terapia Kinésica | `terapiakinesica` | `bi-person-walking` | Atención |
| Taller de Habla y Lenguaje | `terapiafonoaudiologia` | `bi-chat-dots` | Atención |
| Taller Deportivo Recreativo | `tallerdeportivo` | `bi-activity` | Taller |
| Préstamo de Ayuda Técnica | `ayudatecnica` | `bi-box` | Servicio |
| Devolución de Ayuda Técnica | `devolucionayudatecnica` | `bi-arrow-counterclockwise` | Trámite |
| Informe de Ayuda Social | `ayudasocial` | `bi-file-earmark-text` | Informe |
| Informe Social y Redes de Apoyo | `informesocialyredes` | `bi-diagram-3` | Informe |
| Informe ORASMI | `orasmi` | `bi-bank` | Informe |
| Informe de Ayudas Técnicas | `informeayudatecnica` | `bi-tools` | Informe |
| Informes para Tribunales | `tribunales` | `bi-clipboard-check` | Informe |
| Informe para Compras de Ayudas Técnicas | `comprasayudastecnicas` | `bi-cart4` | Informe |
| Subsidio de Discapacidad para Menores | `subsidiomenores` | `bi-emoji-smile` | Orientación |
| Orientación sobre Estipendio | `tipendio` | `bi-person-hearts` | Orientación |
| Pensión Básica Solidaria de Invalidez | `pensionbasicainvalidez` | `bi-cash` | Orientación |
| Beneficios Semestrales Ley 20.422 | `beneficiossemestrales` | `bi-calendar-week` | Beneficio |
| Solicitud IVADEC | `ivadec` | `bi-ui-checks` | Trámite |
| Postulación a Ayudas Técnicas SENADIS | `senadis` | `bi-upload` | Trámite |

---

### 12. GESTIÓN HABITACIONAL (6 trámites)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Organizaciones Funcionales | `comitevivienda` | `bi-building` | Constitución |
| Junta de Vecinos | `juntavecinal` | `bi-people` | Constitución |
| Fundación (Ley 20.500) | `fundacion` | `bi-journal-text` | Constitución |
| ONG / Corporación / Asociación Cultural | `ong` | `bi-file-earmark-person` | Constitución |
| Asociación de Adultos Mayores | `adultomayor` | `bi-person-arms-up` | Constitución |
| Centro de Padres y Apoderados | `padresyapoderados` | `bi-person-vcard` | Constitución |

---

### 13. COMODATOS (1 trámite)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Comodato | `comodato` | (pendiente) | Trámite |

---

### 14. OMIL (7 trámites)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Inscripción o Actualización de Datos | `inscripcion` | `bi-person-check` | Registro |
| Postulación a Oferta Laboral | `postulacion` | `bi-laptop` | Postulación |
| Certificado de Cesantía | `certificadocesantia` | `bi-file-earmark-text` | Certificado |
| Certificado de Inscripción | `certificadoinscripcion` | `bi-person-lines-fill` | Certificado |
| Certificado Usuario con Discapacidad | `solicituddiscapacidad` | `bi-universal-access` | Certificado |
| Actualización por Trámite de Cesantía Solidaria | `cesantiasolidaria` | `bi-arrow-repeat` | Actualización |
| Solicitud de Requerimiento de Personal | `requerimiento` | `bi-person-plus` | Solicitud |

---

### 15. DERECHOS HUMANOS (7 trámites)

| Trámite | Slug | Icono | Tipo |
|---------|------|-------|------|
| Vigencia de Cédula | `vigenciacedula` | (pendiente) | Trámite |
| Residencia Temporal | `residenciatemporal` | (pendiente) | Trámite |
| Residencia Definitiva | `residenciadefinitiva` | (pendiente) | Trámite |
| Permanencia Transitoria | `permanenciatransitoria` | (pendiente) | Trámite |
| Nacionalización | `nacionalizacion` | (pendiente) | Trámite |
| LGBT | `lgbt` | (pendiente) | Información |
| Diversidad Sexual | `diversidadsexual` | (pendiente) | Información |
| Capacitación | `capacitacion` | (pendiente) | Taller |

---

### 16. DEFENSORÍA CIUDADANA (1 servicio)

| Servicio | Slug | Icono | Tipo |
|----------|------|-------|------|
| Orientación Legal | `orientacionlegal` | (pendiente) | Asesoría |

---

### 17. PRESUPUESTO PARTICIPATIVO (3 programas)

| Programa | Slug | Icono | Tipo |
|----------|------|-------|------|
| FONDEVE | `fondeve` | (pendiente) | Programa |
| FONDECO | `fondeco` | (pendiente) | Programa |
| Presupuesto Participativo | `presupuestoparticipativo` | (pendiente) | Programa |

---

## CAMPOS COMUNES IDENTIFICADOS

### Metadatos PHP (headTitle)
- **Tipo:** String
- **Ubicación:** Inicio de archivo PHP
- **Ejemplo:** `$this->headTitle('Subsidio Único Familiar (SUF)');`
- **Uso:** SEO y título de página

### Botón de Retorno (menuBackUrl)
- **Tipo:** URL
- **Ubicación:** Layout
- **Ejemplo:** `$this->layout()->menuBackUrl = '/didecosistemas/public/departamentos/serviciosocial';`
- **Uso:** Navegación

### Encabezados Departamento
- **Título:** H1 con clase `display-5 fw-bold mb-3`
- **Color:** Inline style (mayormente `#15356c`)
- **Icono:** FontAwesome o Bootstrap Icons
- **Descripción:** Párrafo con clase `lead text-muted`

### Tarjetas de Trámite (Grid)
- **Contenedor:** `<div class="grid-container">`
- **Elemento:** `<a href="..." class="card-departamento">`
- **Icono:** `<div class="card-icon"><i class="..."></i></div>`
- **Título:** `<div class="card-title">...</div>`
- **Descripción:** `<div class="descripcion">...</div>`

### Banner de Contacto
- **Email:** Parámetro GET en URL
- **Mensaje:** Texto configurable
- **Botón:** Clase `btn btn-outline-primary btn-sm`

### Accordion de Detalles
- **Framework:** Bootstrap Accordion
- **Secciones Estándar:**
  1. Documentos Requeridos
  2. Requisitos del Usuario
  3. Instrucciones Paso a Paso
  4. Tiempo Estimado de Respuesta
  5. Responsable del Trámite

---

## INCONSISTENCIAS DETECTADAS

### 1. Estructura de HTML
- ❌ Algunas vistas usan Bootstrap 4 (`data-toggle`), otras Bootstrap 5 (`data-bs-toggle`)
- ❌ Mezcla de iconos FontAwesome y Bootstrap Icons sin estándar
- ❌ Algunos archivos tienen clases CSS inline, otros usan hojas de estilo

### 2. Convenciones de Naming
- ❌ Nombres de archivos inconsistentes (ej: `reciennacido.phtml` vs `reciénnacido.phtml`)
- ❌ Slugs no estandarizados
- ❌ IDs de accordion varían (`accordionTramite`, `accordionSUF1`, etc.)

### 3. Datos Faltantes
- ⚠️ Algunos departamentos sin iconos definidos
- ⚠️ Emails de contacto duplicados o genéricos
- ⚠️ Responsables de trámite no siempre especificados
- ⚠️ Tiempos estimados muy genéricos o ausentes

### 4. Contenido Duplicado
- ❌ Enlace Norte y Subsidio y Pensiones tienen trámites duplicados (SUF, PGU, etc.)
- ❌ Información repetida entre departamentos

---

## RECOMENDACIONES DE NORMALIZACIÓN

### 1. Migración a Base de Datos
- ✅ Crear tablas `departamentos`, `tramites`, `documentos_tramite`, `requisitos_tramite`, `pasos_tramite`
- ✅ Migrar contenido HTML a campos de texto/JSON
- ✅ Establecer relaciones FK entre tablas
- ✅ Agregar campos de auditoría (`created_at`, `updated_at`, `created_by`, `updated_by`)

### 2. Estandarización de Vistas
- ✅ Crear plantillas reutilizables (Blade/Twig o ViewModel en Laminas)
- ✅ Unificar Bootstrap a versión 5
- ✅ Estandarizar sistema de iconos (recomendado: Bootstrap Icons exclusivamente)
- ✅ Crear componentes reutilizables (CardDepartamento, InfoBanner, AccordionTramite)

### 3. SEO y Accesibilidad
- ✅ Agregar meta descriptions
- ✅ Agregar breadcrumbs
- ✅ Mejorar estructura de headings (H1 único por página)
- ✅ Agregar atributos ARIA en accordions

### 4. Gestión de Contenido
- ✅ Implementar panel de administración (CRUD)
- ✅ Sistema de versionado de contenido
- ✅ Workflow de aprobación de cambios
- ✅ Historial de modificaciones

### 5. Performance
- ✅ Cachear datos de departamentos y trámites
- ✅ Lazy loading de iconos/imágenes
- ✅ Minificar CSS/JS
- ✅ CDN para assets estáticos

---

## PLAN DE MIGRACIÓN SUGERIDO

### FASE 1: Análisis y Preparación ✅ (COMPLETADO)
- ✅ Inventario completo de vistas
- ✅ Identificación de patrones
- ✅ Diseño de estructura de BD

### FASE 2: Creación de Base de Datos
1. Crear tablas en MySQL/MariaDB
2. Definir índices y relaciones
3. Crear seeders con datos actuales
4. Validar integridad de datos

### FASE 3: Desarrollo de Modelos y Controladores
1. Crear modelos Laminas (Departamento, Tramite)
2. Crear servicios de negocio
3. Crear controladores dinámicos
4. Implementar repositorios

### FASE 4: Migración de Contenido
1. Script de extracción de datos desde .phtml
2. Limpieza y normalización de datos
3. Inserción masiva en BD
4. Validación y pruebas

### FASE 5: Actualización de Vistas
1. Crear vistas dinámicas con datos de BD
2. Reemplazar .phtml estáticos
3. Pruebas de regresión
4. Despliegue gradual

### FASE 6: Panel de Administración
1. CRUD de departamentos
2. CRUD de trámites
3. Gestión de usuarios admin
4. Dashboard de estadísticas

---

## ESTRUCTURA JSON DE EJEMPLO

### Departamento
```json
{
  "id": 1,
  "nombre": "Enlace Norte - Servicios DIDECO",
  "slug": "enlacenorte",
  "descripcion": "Accede a todos los servicios de desarrollo social, subsidios, pensiones y beneficios disponibles para las familias del sector norte de nuestra comuna.",
  "icono_bootstrap": "bi-signpost-2",
  "icono_fontawesome": "fas fa-compass",
  "color_primario": "#15356c",
  "color_secundario": "#6537b6",
  "email_contacto": "enlacenorte@municipalidadarica.cl",
  "mensaje_contacto": "El equipo de Enlace Norte está disponible para orientarte y acompañarte en tu proceso.",
  "orden": 1,
  "activo": true
}
```

### Trámite
```json
{
  "id": 1,
  "departamento_id": 1,
  "nombre": "Subsidio Único Familiar (SUF)",
  "slug": "subsidiofamiliar",
  "descripcion_corta": "Beneficio económico mensual para familias en situación de vulnerabilidad social.",
  "descripcion_larga": "Es un beneficio económico que entrega el Estado, el cual asciende a $21.243 mensual. Está dirigido a niños, niñas y adolescentes menores de 18 años de edad.",
  "icono_bootstrap": "bi-currency-dollar",
  "documentos_requeridos": [
    "Certificado Fonasa Tramo \"A\"",
    "Certificado AFP 12 últimos meses",
    "Documento cuidado personal",
    "Fotocopia finiquito",
    "Madres cesantes sin previsión"
  ],
  "requisitos_usuario": [
    "Pertenecer al 60% más vulnerable de la población",
    "Presentar la documentación requerida según subsidio al que postulará"
  ],
  "instrucciones_paso_paso": [
    "Realizar postulación según corresponda"
  ],
  "tiempo_estimado": "Después de 2 meses de realizado el trámite",
  "responsable_nombre": "Claudia Pizarro Valdivia",
  "responsable_cargo": "Coordinadora",
  "monto_beneficio": 21243.00,
  "orden": 1,
  "activo": true
}
```

---

## CONCLUSIONES

1. **Estructura bien definida:** Las vistas siguen un patrón consistente que facilita la migración
2. **Datos estáticos:** Todo el contenido está hardcodeado, ideal para migración a BD
3. **Oportunidad de mejora:** Normalización permitirá:
   - Búsqueda avanzada
   - Filtros por categoría
   - Gestión centralizada
   - Versionado de contenido
   - API REST para otras plataformas
4. **Escalabilidad:** Base de datos permitirá agregar nuevos departamentos sin modificar código

---

## PRÓXIMOS PASOS

1. ✅ **Revisar este documento** con el equipo
2. ⏭️ **Aprobar diseño de BD**
3. ⏭️ **Crear scripts de migración**
4. ⏭️ **Desarrollar modelos y controladores**
5. ⏭️ **Implementar panel de administración**
6. ⏭️ **Migrar contenido**
7. ⏭️ **Pruebas y despliegue**

---

**Documento generado automáticamente**  
**Fecha:** 18 de noviembre de 2025  
**Herramienta:** Análisis de estructura de vistas DIDECO
