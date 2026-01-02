# Presentación del Sistema DIDECO: Departamentos y Geoportal

Este documento detalla los aspectos fundamentales y el flujo de uso de la aplicación web de DIDECO, enfocándose en la accesibilidad de la información y la georreferenciación.

## 1. Módulo de Departamentos y Buscador Centralizado

El corazón de la accesibilidad del sistema reside en su **Buscador Inteligente**. Diseñado para ser el primer punto de contacto, permite al usuario encontrar rápidamente cualquier trámite, servicio o unidad municipal.

### Aspectos Fundamentales:

- **Búsqueda Predictiva**: A medida que el usuario escribe, el sistema sugiere coincidencias relevantes, reduciendo el tiempo de búsqueda.
- **Gestión Centralizada**: Todos los datos (nombres, descripciones, requisitos) son administrables desde una base de datos unificada. Esto significa que cualquier cambio en un procedimiento se refleja instantáneamente para todos los usuarios, eliminando la desinformación.
- **Interfaz Limpia**: Se prioriza la caja de búsqueda sobre el contenido denso, "googleando" la experiencia municipal.

> **Referencia Visual**: `buscador_principal.png` > _(Captura del buscador en la página de inicio, mostrando la barra de búsqueda limpia y accesible)_

### Ventajas:

1.  **Eficiencia**: Reduce la navegación por menús complejos.
2.  **Flexibilidad**: El sistema escala fácilmente; agregar un nuevo departamento es tan simple como insertar un registro en la base de datos.
3.  **Orientación al Ciudadano**: Lenguaje claro y resultados directos.

---

## 2. Geoportal: Visualización Territorial

El **Geoportal** transforma la lista de direcciones estáticas en un mapa interactivo vivo. Su objetivo es facilitar la ubicación física de las unidades, algo crítico para la atención presencial.

### Aspectos Fundamentales:

- **Mapa Interactivo**: Utiliza tecnología de mapas (Leaflet/OSM) para mostrar la ciudad de Arica con marcadores precisos de cada unidad.
- **Interacción Móvil**: Diseñado "Mobile-First". En celulares, el menú se adapta para maximizar la visión del mapa, permitiendo alternar fácilmente entre la lista de lugares y su ubicación.
- **Botón de Dirección**: Funcionalidad clave para re-centrar y listar las opciones disponibles rápidamente.

> **Referencia Visual**: `geoportal_mapa_general.png` > _(Vista general del mapa con los marcadores distribuidos por la ciudad)_

### Detalle de Información:

Al seleccionar una unidad, el sistema no solo muestra un punto en el mapa, sino que despliega una ficha completa con:

- **Nombre y Dirección Exacta**.
- **Información de Contacto** (Teléfono, Correo).
- **Horarios y Enlaces Directos**.

> **Referencia Visual**: `geoportal_detalle_unidad.png` > _(Visualización del modal/ficha con la información detallada de una unidad seleccionada)_

---

## 3. Impacto en la Colaboración Pública

La integración de estos módulos crea un ecosistema donde:

1.  **El Ciudadano no se pierde**: Encuentra "qué" necesita (Buscador) y "dónde" ir (Geoportal).
2.  **Transparencia**: La información de contacto y ubicación es visible y pública.
3.  **Autonomía**: Reduce la carga sobre mesas de ayuda telefónicas al proveer respuestas inmediatas online.
