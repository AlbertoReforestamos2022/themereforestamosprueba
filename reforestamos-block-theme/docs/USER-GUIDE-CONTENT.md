# Guía de Gestión de Contenido — Reforestamos México

## Introducción

Esta guía explica cómo gestionar los diferentes tipos de contenido del sitio: empresas, eventos, integrantes, boletines, micrositios y más.

---

## Tipos de Contenido

### Empresas Colaboradoras

Las empresas colaboradoras se gestionan desde el menú **Empresas** en el panel de administración.

#### Crear una Empresa

1. Ve a **Empresas → Añadir Nueva**
2. Completa los campos:
   - **Título**: Nombre de la empresa
   - **Contenido**: Descripción de la empresa
   - **Imagen destacada**: Logo principal de la empresa
3. En el panel lateral, completa los campos personalizados:
   - **Logo**: Sube el logo de la empresa
   - **URL del sitio web**: Enlace al sitio de la empresa
   - **Categoría**: Tipo de empresa (industria, alianza, etc.)
   - **Año de alianza**: Año en que comenzó la colaboración
   - **Árboles plantados**: Número de árboles aportados
4. Haz clic en **Publicar**

#### Gestionar Galerías de Empresas

1. Edita una empresa existente
2. En la sección **Galería**, haz clic en **Agregar imágenes**
3. Selecciona o sube las imágenes
4. Agrega títulos y descripciones a cada imagen
5. Guarda los cambios

#### Mostrar Empresas en una Página

Usa el shortcode `[companies-grid]` en cualquier página para mostrar el grid de logos de empresas.

Opciones del shortcode:
- `[companies-grid columns="4"]` — Número de columnas
- `[companies-grid category="aliados"]` — Filtrar por categoría

---

### Eventos

Los eventos se gestionan desde el menú **Eventos**.

#### Crear un Evento

1. Ve a **Eventos → Añadir Nuevo**
2. Completa los campos:
   - **Título**: Nombre del evento
   - **Contenido**: Descripción detallada
   - **Imagen destacada**: Imagen del evento
3. Campos personalizados:
   - **Fecha y hora**: Cuándo se realizará el evento
   - **Ubicación**: Dirección del evento
   - **Coordenadas**: Latitud y longitud (para el mapa)
   - **Capacidad**: Número máximo de asistentes
   - **Registro activo**: Habilitar/deshabilitar inscripciones
4. Publica el evento

#### Calendario de Eventos

El calendario se muestra automáticamente en la página de archivo de eventos (`/eventos/`). Los eventos próximos también aparecen en el bloque **Eventos Próximos** en la página principal.

#### Exportar a Calendario

Los visitantes pueden descargar eventos en formato iCal (.ics) para agregarlos a su calendario personal.

---

### Integrantes del Equipo

1. Ve a **Integrantes → Añadir Nuevo**
2. Completa:
   - **Nombre**: Nombre completo
   - **Cargo**: Puesto en la organización
   - **Email**: Correo electrónico
   - **Biografía**: Descripción breve
   - **Foto**: Imagen del integrante
   - **Redes sociales**: Enlaces a perfiles sociales
3. Publica

---

### Boletín de Noticias

#### Crear una Campaña

1. Ve a **Comunicación → Newsletter → Crear Campaña**
2. Selecciona el contenido del boletín (puede ser un post tipo "Boletín")
3. Selecciona la lista de destinatarios
4. Programa o envía inmediatamente

#### Gestionar Suscriptores

1. Ve a **Comunicación → Suscriptores**
2. Puedes ver, buscar y exportar la lista de suscriptores
3. Los suscriptores se agregan automáticamente cuando usan el formulario de suscripción

#### Formulario de Suscripción

Usa el shortcode `[newsletter-subscribe]` en cualquier página para mostrar el formulario de suscripción al boletín.

---

### Formularios de Contacto

Los formularios de contacto se insertan con el shortcode `[contact-form]`.

#### Ver Mensajes Recibidos

1. Ve a **Comunicación → Mensajes**
2. Verás todos los mensajes enviados a través del formulario
3. Los mensajes también se envían por email al administrador

---

### Micrositios

#### Árboles y Ciudades

El micrositio de Árboles y Ciudades muestra un mapa interactivo con ubicaciones de reforestación.

- **Insertar en una página**: Usa el shortcode `[arboles-ciudades]`
- **Actualizar datos**: Ve a **Micrositios → Gestión de Datos** y sube el archivo JSON actualizado
- **Formato del JSON**: Consulta la documentación técnica para el formato requerido

#### Red OJA

El micrositio de Red OJA muestra organizaciones juveniles ambientales.

- **Insertar en una página**: Usa el shortcode `[red-oja]`
- **Actualizar datos**: Ve a **Micrositios → Gestión de Datos** y sube el archivo JSON de organizaciones

---

## Gestión de Medios

### Subir Imágenes

1. Ve a **Medios → Añadir nuevo**
2. Arrastra archivos o haz clic en **Seleccionar archivos**
3. Formatos aceptados: JPG, PNG, GIF, WebP
4. Tamaño máximo recomendado: 2 MB por imagen

### Optimización Automática

El sistema optimiza automáticamente las imágenes:
- Genera múltiples tamaños (thumbnail, medium, large)
- Convierte a formato WebP cuando es posible
- Aplica lazy loading para mejorar la velocidad de carga

### Buenas Prácticas

- Usa imágenes de al menos 1200px de ancho para secciones hero
- Agrega siempre **texto alternativo** (alt text) para accesibilidad
- Comprime las imágenes antes de subirlas si son muy grandes

---

## Idiomas

### Cambiar el Idioma de una Página

1. Edita la página
2. En el panel lateral, busca la sección **Traducción**
3. Haz clic en **Traducir a Inglés** (o **Traducir a Español**)
4. El sistema usará DeepL para generar la traducción automática
5. Revisa y ajusta la traducción antes de publicar

### Selector de Idioma

El selector de idioma aparece automáticamente en el header del sitio. Los visitantes pueden cambiar entre español e inglés.

---

## Búsqueda

El sitio incluye un buscador en el header que busca en:
- Páginas
- Entradas del blog
- Empresas
- Eventos
- Documentos

Los resultados se pueden filtrar por tipo de contenido.

---

## Permisos de Usuario

| Rol | Puede hacer |
|-----|-------------|
| Administrador | Todo: configuración, plugins, usuarios, contenido |
| Editor | Crear, editar y publicar cualquier contenido |
| Autor | Crear y publicar su propio contenido |
| Colaborador | Crear contenido (requiere aprobación para publicar) |

---

## Preguntas Frecuentes

**¿Cómo cambio el logo del sitio?**
Ve a **Apariencia → Editor** y edita la parte del header. Haz clic en el logo y selecciona uno nuevo.

**¿Cómo cambio el menú de navegación?**
Ve a **Apariencia → Menús** o edita directamente en el Editor del sitio.

**¿Cómo agrego una nueva página al menú?**
Ve a **Apariencia → Menús**, selecciona la página y haz clic en **Añadir al menú**.

**¿Puedo deshacer cambios?**
Sí, usa **Ctrl+Z** en el editor. También puedes ver el historial de revisiones de cada página.

**¿Cómo veo las estadísticas del sitio?**
Ve a **Escritorio** para ver los widgets de estadísticas, o accede a Google Analytics para métricas detalladas.

---

## Soporte

Para problemas técnicos o preguntas sobre el contenido, contacta al equipo de desarrollo.
