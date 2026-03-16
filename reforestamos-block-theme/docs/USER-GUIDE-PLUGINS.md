# Guía de Configuración de Plugins — Reforestamos México

## Introducción

El sitio utiliza 4 plugins personalizados que extienden la funcionalidad del tema. Esta guía explica cómo configurar cada uno.

---

## Orden de Activación

Los plugins deben activarse en este orden:

1. **Reforestamos Core** (siempre primero)
2. **Reforestamos Micrositios**
3. **Reforestamos Comunicación**
4. **Reforestamos Empresas** (requiere Core activo)

Para activar: ve a **Plugins** en el panel de administración y haz clic en **Activar** para cada plugin.

---

## Plugin 1: Reforestamos Core

### Descripción
Gestiona los tipos de contenido personalizados (Custom Post Types), taxonomías y la API REST.

### Configuración
Este plugin funciona automáticamente al activarse. No requiere configuración adicional.

### Qué proporciona
- **Empresas**: Tipo de contenido para empresas colaboradoras
- **Eventos**: Tipo de contenido para eventos de reforestación
- **Integrantes**: Tipo de contenido para miembros del equipo
- **Boletín**: Tipo de contenido para newsletters
- **Taxonomías**: Categorías y etiquetas para cada tipo de contenido
- **API REST**: Endpoints para acceder a datos programáticamente

### Verificación
Después de activar, verifica que los menús **Empresas**, **Eventos**, **Integrantes** y **Boletín** aparecen en el panel lateral de administración.

---

## Plugin 2: Reforestamos Micrositios

### Descripción
Proporciona los micrositios interactivos de Árboles y Ciudades y Red OJA con mapas.

### Configuración

1. **Activar el plugin**
2. Ve a **Micrositios → Gestión de Datos** en el menú de administración
3. Sube los archivos JSON con los datos:
   - `arboles-ciudades.json` — Datos de árboles y ubicaciones
   - `red-oja.json` — Datos de organizaciones juveniles

### Formato de Datos JSON

#### Árboles y Ciudades
```json
[
  {
    "nombre": "Encino",
    "especie": "Quercus robur",
    "ciudad": "Ciudad de México",
    "lat": 19.4326,
    "lng": -99.1332,
    "fecha": "2024-03-15",
    "cantidad": 50
  }
]
```

#### Red OJA
```json
[
  {
    "nombre": "Organización Ejemplo",
    "estado": "Jalisco",
    "tipo": "ONG",
    "actividad": "Reforestación urbana",
    "lat": 20.6597,
    "lng": -103.3496,
    "contacto": "contacto@ejemplo.org"
  }
]
```

### Uso en Páginas
- Micrositio de árboles: `[arboles-ciudades]`
- Micrositio Red OJA: `[red-oja]`

---

## Plugin 3: Reforestamos Comunicación

### Descripción
Gestiona el boletín de noticias, formularios de contacto, chatbot y traducciones con DeepL.

### Configuración del Newsletter

1. Ve a **Comunicación → Configuración del Newsletter**
2. Configura los ajustes de SMTP:
   - **Host SMTP**: Servidor de correo (ej: `smtp.gmail.com`)
   - **Puerto**: Puerto del servidor (ej: `587`)
   - **Usuario**: Dirección de email
   - **Contraseña**: Contraseña o contraseña de aplicación
   - **Cifrado**: TLS o SSL
3. Configura el remitente:
   - **Nombre del remitente**: "Reforestamos México"
   - **Email del remitente**: `boletin@reforestamos.org`
4. Guarda los cambios

### Configuración del Formulario de Contacto

1. El formulario funciona automáticamente con el shortcode `[contact-form]`
2. Los emails se envían usando la configuración SMTP del newsletter
3. Para cambiar el destinatario, ve a **Comunicación → Configuración** y actualiza el email de contacto

### Configuración del Chatbot

1. Ve a **Comunicación → Chatbot → Configuración**
2. **Activar/Desactivar**: Usa el toggle para habilitar el chatbot globalmente
3. **Configurar respuestas**:
   - Agrega preguntas frecuentes y sus respuestas
   - Configura flujos de conversación
   - Define el mensaje de bienvenida
4. **Ver logs**: Ve a **Comunicación → Chatbot → Logs** para ver las conversaciones

### Configuración de DeepL (Traducciones)

1. Ve a **Comunicación → Configuración de DeepL**
2. Ingresa tu **API Key de DeepL** (obtén una en [deepl.com/pro](https://www.deepl.com/pro))
3. Selecciona el plan: **Free** o **Pro**
4. Guarda los cambios
5. Para traducir contenido, edita cualquier post/página y usa el botón **Traducir** en el panel lateral

### Shortcodes Disponibles

| Shortcode | Descripción |
|-----------|-------------|
| `[contact-form]` | Formulario de contacto |
| `[newsletter-subscribe]` | Formulario de suscripción al boletín |

---

## Plugin 4: Reforestamos Empresas

### Descripción
Extiende la funcionalidad de empresas con perfiles, galerías, analytics de clics y grid de logos.

### Requisito
**Reforestamos Core debe estar activo.** Si Core no está activo, verás un aviso de advertencia.

### Configuración

1. **Activar el plugin** (después de Core)
2. El plugin extiende automáticamente el tipo de contenido "Empresas"

### Funcionalidades

#### Grid de Empresas
Usa `[companies-grid]` para mostrar logos de empresas en una cuadrícula.

Opciones:
- `[companies-grid columns="3"]` — 3 columnas
- `[companies-grid category="aliados"]` — Solo empresas de categoría "aliados"

#### Galerías de Empresas
Usa `[company-gallery id="123"]` para mostrar la galería de una empresa específica.

#### Analytics de Clics
- Los clics en logos de empresas se rastrean automáticamente
- Ve las estadísticas en **Empresas → Analytics**
- Exporta datos a CSV desde el dashboard de analytics
- Filtra por rango de fechas

### Dashboard de Analytics

1. Ve a **Empresas → Analytics**
2. Verás:
   - Total de clics
   - Clics por mes (gráfico)
   - Top empresas más visitadas
   - Clics únicos vs. repetidos
3. Usa los filtros de fecha para ver períodos específicos
4. Haz clic en **Exportar CSV** para descargar los datos

---

## Configuración General

### Google Analytics

1. Ve a **Ajustes → Analytics**
2. Ingresa tu **GA4 Measurement ID** (formato: `G-XXXXXXXXXX`)
3. Activa **Anonimizar IPs** para cumplimiento GDPR
4. Activa **Consentimiento de cookies** para requerir aceptación antes de rastrear
5. Guarda los cambios

### Monitoreo

1. Ve a **Ajustes → Monitoring**
2. Configura **Sentry DSN** para rastreo de errores (opcional pero recomendado)
3. El endpoint de uptime está disponible en: `/wp-json/reforestamos/v1/uptime`
4. Configura tu servicio de monitoreo (UptimeRobot, Pingdom, etc.) para hacer ping a este endpoint

### Seguridad

La seguridad está configurada automáticamente e incluye:
- Headers de seguridad (X-Frame-Options, CSP, etc.)
- Sanitización de inputs
- Protección contra XSS y SQL injection
- Rate limiting en formularios
- Encriptación de credenciales API

---

## Desactivación de Plugins

Los plugins pueden desactivarse independientemente sin causar errores en el sitio:

- **Desactivar Core**: Los tipos de contenido se ocultan pero los datos se preservan
- **Desactivar Micrositios**: Los shortcodes de mapas dejan de funcionar
- **Desactivar Comunicación**: Newsletter, formularios y chatbot se desactivan
- **Desactivar Empresas**: Grid de logos y analytics dejan de funcionar

**Nota**: Si desactivas Core mientras Empresas está activo, verás un aviso de advertencia.

---

## Solución de Problemas

| Problema | Solución |
|----------|----------|
| Los menús de CPTs no aparecen | Verifica que Reforestamos Core está activo |
| Los mapas no cargan | Verifica que los archivos JSON están subidos en Micrositios |
| Los emails no se envían | Verifica la configuración SMTP en Comunicación |
| El chatbot no aparece | Verifica que está habilitado en Comunicación → Chatbot |
| Las traducciones fallan | Verifica la API Key de DeepL y el límite de uso |
| Analytics no muestra datos | Verifica el GA4 ID y que el consentimiento de cookies está aceptado |

---

## Soporte

Para problemas técnicos, consulta:
- [Guía del Editor Gutenberg](./USER-GUIDE-GUTENBERG.md)
- [Guía de Gestión de Contenido](./USER-GUIDE-CONTENT.md)
- [Documentación técnica del tema](../README.md)
