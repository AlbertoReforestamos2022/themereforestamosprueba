# Reforestamos Empresas Plugin

Plugin de WordPress para gestión avanzada de empresas colaboradoras con analytics y galerías.

## Descripción

Este plugin extiende la funcionalidad del Custom Post Type "Empresas" definido en el plugin Reforestamos Core, proporcionando características adicionales como:

- Sistema de analytics para rastrear clics en logos y enlaces de empresas
- Gestión de galerías de fotos para cada empresa
- Shortcodes para mostrar grids de empresas
- Dashboard de analytics en el admin
- Templates personalizados para páginas de empresas

## Requisitos

- WordPress 6.0 o superior
- PHP 7.4 o superior
- **Plugin Reforestamos Core** (requerido)

## Instalación

1. Asegúrate de que el plugin **Reforestamos Core** esté instalado y activado
2. Sube la carpeta `reforestamos-empresas` al directorio `/wp-content/plugins/`
3. Activa el plugin desde el menú 'Plugins' en WordPress

## Dependencias

Este plugin **requiere** que el plugin Reforestamos Core esté activo. Si intentas activar este plugin sin tener el Core activo, recibirás un mensaje de error y el plugin no se activará.

## Estructura del Plugin

```
reforestamos-empresas/
├── includes/                          # Clases principales del plugin
│   └── class-reforestamos-empresas.php
├── admin/                             # Archivos del área de administración
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── assets/                            # Assets del frontend
│   ├── css/
│   │   └── frontend.css
│   └── js/
│       └── frontend.js
├── templates/                         # Templates personalizados
├── languages/                         # Archivos de traducción
├── reforestamos-empresas.php         # Archivo principal del plugin
└── README.md
```

## Características Principales

### 1. Sistema de Analytics
- Rastreo de clics en logos de empresas
- Rastreo de clics en enlaces de empresas
- Dashboard de analytics en el admin
- Exportación de datos a CSV
- Filtros por rango de fechas

### 2. Gestión de Galerías
- Añadir múltiples imágenes a cada empresa
- Ordenar imágenes mediante drag & drop
- Lightbox para visualización de imágenes
- Shortcode para mostrar galerías

### 3. Shortcodes Disponibles

#### [companies-grid]
Muestra un grid de logos de empresas.

```php
[companies-grid columns="3" category="tecnologia"]
```

Parámetros:
- `columns`: Número de columnas (default: 3)
- `category`: Filtrar por categoría de empresa
- `limit`: Número máximo de empresas a mostrar

#### [company-gallery]
Muestra la galería de fotos de una empresa.

```php
[company-gallery id="123" columns="4"]
```

Parámetros:
- `id`: ID de la empresa (requerido)
- `columns`: Número de columnas (default: 3)

### 4. Templates Personalizados
El plugin proporciona templates personalizados para:
- Página individual de empresa (`single-empresa.php`)
- Archivo de empresas (`archive-empresas.php`)

## Desarrollo

### Hooks Disponibles

#### Acciones
- `reforestamos_empresas_before_company_content` - Antes del contenido de empresa
- `reforestamos_empresas_after_company_content` - Después del contenido de empresa
- `reforestamos_empresas_analytics_tracked` - Después de rastrear un clic

#### Filtros
- `reforestamos_empresas_grid_columns` - Modificar número de columnas del grid
- `reforestamos_empresas_analytics_data` - Modificar datos de analytics antes de mostrar

### Estructura de Base de Datos

#### Tabla: wp_reforestamos_empresas_analytics

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint(20) | ID único del registro |
| company_id | bigint(20) | ID de la empresa (post ID) |
| click_type | varchar(50) | Tipo de clic (logo, card, link) |
| user_ip | varchar(45) | IP del usuario |
| user_agent | text | User agent del navegador |
| referrer | text | URL de referencia |
| clicked_at | datetime | Fecha y hora del clic |

## Changelog

### 1.0.0
- Versión inicial
- Estructura base del plugin
- Sistema de verificación de dependencias
- Preparación para funcionalidades futuras

## Autor

**Reforestamos México**
- Website: https://reforestamos.org

## Licencia

GPL v2 or later

## Soporte

Para soporte y preguntas, visita: https://reforestamos.org/contacto
