# Sistema de Migración Reforestamos

Sistema completo de migración para transformar el tema WordPress legacy "Reforestamos México" a una arquitectura moderna de Block Theme con plugins modulares.

## Descripción

Este sistema proporciona herramientas automatizadas para:

- **Backup automático** de la base de datos antes de cualquier migración
- **Migración de contenido** (posts, páginas, Custom Post Types)
- **Conversión de shortcodes** a bloques Gutenberg
- **Migración de custom fields** a la nueva estructura
- **Migración de templates** de PHP a HTML
- **Reportes detallados** de todo el proceso

## Requisitos

- WordPress 6.0 o superior
- PHP 7.4 o superior
- WP-CLI instalado (para comandos de línea)
- Permisos de escritura en `wp-content/`
- Conexión activa a la base de datos

## Instalación

1. Copiar el directorio `migration-system/` a la raíz de WordPress
2. Cargar el sistema en `wp-config.php` o `functions.php`:

```php
require_once ABSPATH . 'migration-system/reforestamos-migration.php';
```

## Uso con WP-CLI

### Verificar requisitos

Antes de ejecutar la migración, verifica que todos los requisitos estén satisfechos:

```bash
wp reforestamos check
```

### Ver estadísticas

Obtén un resumen de los elementos que serán migrados:

```bash
wp reforestamos stats
```

### Ejecutar migración en modo dry-run

Simula la migración sin realizar cambios permanentes:

```bash
wp reforestamos migrate --dry-run --verbose
```

### Ejecutar migración completa

Ejecuta la migración con backup automático:

```bash
wp reforestamos migrate --verbose
```

### Crear backup manual

Crea un backup de la base de datos sin ejecutar la migración:

```bash
wp reforestamos backup
```

## Opciones de Comandos

### `wp reforestamos migrate`

Ejecuta la migración completa.

**Opciones:**

- `--dry-run` - Modo simulación, no realiza cambios permanentes
- `--backup` - Crea backup antes de migrar (activado por defecto)
- `--no-backup` - Omite la creación del backup (no recomendado)
- `--verbose` - Muestra información detallada durante el proceso

**Ejemplos:**

```bash
# Simulación con salida detallada
wp reforestamos migrate --dry-run --verbose

# Migración completa con backup
wp reforestamos migrate

# Migración sin backup (no recomendado)
wp reforestamos migrate --no-backup
```

### `wp reforestamos stats`

Muestra estadísticas sobre los shortcodes que serán convertidos y detecta shortcodes no convertibles.

```bash
wp reforestamos stats
```

**Salida:**
- Total de shortcodes encontrados
- Posts afectados
- Desglose por tipo de shortcode
- Lista de shortcodes no convertibles

### `wp reforestamos non-convertible`

Genera un reporte detallado de shortcodes que no pueden ser convertidos automáticamente.

```bash
# Ver reporte en tabla
wp reforestamos non-convertible

# Exportar a JSON
wp reforestamos non-convertible --format=json > report.json

# Exportar a CSV
wp reforestamos non-convertible --format=csv > report.csv
```

**Formatos soportados:**
- `table` (default): Tabla formateada en consola
- `json`: Exportación JSON
- `csv`: Exportación CSV

### `wp reforestamos backup`

Crea un backup manual de la base de datos.

```bash
wp reforestamos backup
```

### `wp reforestamos check`

Verifica que todos los requisitos previos estén satisfechos.

```bash
wp reforestamos check
```

## Estructura del Sistema

```
migration-system/
├── includes/
│   ├── class-migration-manager.php      # Gestor principal de migración
│   ├── class-backup-manager.php         # Gestor de backups
│   ├── class-content-migrator.php       # Migración de contenido
│   ├── class-shortcode-converter.php    # Conversión de shortcodes
│   └── class-migration-command.php      # Comandos WP-CLI
├── reforestamos-migration.php           # Archivo principal
└── README.md                            # Esta documentación
```

## Proceso de Migración

El sistema ejecuta la migración en las siguientes fases:

### 1. Backup (opcional, recomendado)

- Crea un backup completo de la base de datos
- Guarda el backup en `wp-content/reforestamos-backups/`
- Protege el directorio con `.htaccess`
- Genera metadata en formato JSON

### 2. Migración de Contenido

- Migra custom fields de la estructura antigua a la nueva
- Actualiza page templates de PHP a HTML
- Verifica taxonomías y términos
- Preserva todos los metadatos

### 3. Conversión de Shortcodes

- Identifica shortcodes en posts y páginas
- Convierte shortcodes a bloques Gutenberg equivalentes
- Preserva atributos y configuraciones
- Genera advertencias para shortcodes no convertibles

### 4. Generación de Reporte

- Crea un reporte detallado en `wp-content/reforestamos-migration-reports/`
- Incluye estadísticas de migración
- Lista errores y advertencias
- Proporciona log completo del proceso

## Backups

### Ubicación

Los backups se guardan en:

```
wp-content/reforestamos-backups/
├── backup-2024-01-15-10-30-00.sql
├── backup-2024-01-15-10-30-00.json
├── .htaccess
├── index.php
└── .gitignore
```

### Seguridad

- El directorio está protegido con `.htaccess` (Deny from all)
- Incluye `index.php` para prevenir listado de directorios
- Excluido de Git mediante `.gitignore`
- Solo accesible desde el servidor

### Metadata

Cada backup incluye un archivo JSON con:

- Fecha y hora de creación
- Tamaño del archivo
- Número de tablas respaldadas
- Versiones de WordPress, PHP y MySQL
- Descripción opcional

### Limpieza Automática

Los backups antiguos (más de 30 días) pueden eliminarse automáticamente:

```php
$backup_manager = new Reforestamos_Backup_Manager();
$deleted = $backup_manager->cleanup_old_backups(30);
```

## Conversión de Shortcodes

El sistema convierte automáticamente los siguientes shortcodes:

| Shortcode Legacy | Bloque Gutenberg |
|-----------------|------------------|
| `[contact-form]` | `reforestamos/contacto` |
| `[arboles-ciudades]` | `reforestamos/arboles-ciudades` |
| `[red-oja]` | `reforestamos/red-oja` |
| `[companies-grid]` | `reforestamos/logos-aliados` |
| `[newsletter-subscribe]` | `reforestamos/newsletter-subscribe` |
| `[eventos-proximos]` | `reforestamos/eventos-proximos` |
| `[company-gallery]` | `reforestamos/galeria-tabs` |

### Preservación de Atributos

Los atributos de los shortcodes se convierten automáticamente:

**Antes:**
```
[companies-grid columns="3" category="aliados"]
```

**Después:**
```html
<!-- wp:reforestamos/logos-aliados {"columns":3,"category":"aliados"} /-->
```

## Reportes

Los reportes de migración se guardan en:

```
wp-content/reforestamos-migration-reports/
└── migration-report-2024-01-15-10-30-00.txt
```

### Contenido del Reporte

- Fecha y hora de ejecución
- Modo de ejecución (dry-run o producción)
- Estadísticas de migración
- Lista de errores
- Lista de advertencias
- Log detallado del proceso

## Solución de Problemas

### Error: "No se pudo crear el directorio de backups"

**Causa:** Permisos insuficientes en `wp-content/`

**Solución:**
```bash
chmod 755 wp-content/
```

### Error: "No se encontraron tablas en la base de datos"

**Causa:** Problema de conexión a la base de datos

**Solución:** Verificar credenciales en `wp-config.php`

### Advertencia: "Shortcode no convertible"

**Causa:** El shortcode no tiene un bloque equivalente definido

**Solución:** Agregar el mapeo en `class-shortcode-converter.php`:

```php
private $shortcode_mapping = [
    'mi-shortcode' => 'reforestamos/mi-bloque',
];
```

## Desarrollo

### Agregar Nuevo Shortcode

1. Editar `includes/class-shortcode-converter.php`
2. Agregar mapeo en `$shortcode_mapping`
3. Opcionalmente, personalizar conversión de atributos

### Agregar Nueva Migración de Custom Fields

1. Editar `includes/class-content-migrator.php`
2. Agregar mapeo en `migrate_custom_fields()`

### Extender Comandos WP-CLI

1. Editar `includes/class-migration-command.php`
2. Agregar nuevo método público con docblock
3. El comando estará disponible automáticamente

## Seguridad

- Todos los inputs son sanitizados
- Las consultas SQL usan prepared statements
- Los backups están protegidos contra acceso web
- Los reportes no contienen información sensible
- El sistema verifica permisos antes de ejecutar

## Soporte

Para reportar problemas o solicitar funcionalidades:

1. Revisar la documentación completa
2. Verificar los logs de migración
3. Contactar al equipo de desarrollo de Reforestamos

## Licencia

Este sistema es parte del proyecto Reforestamos México.
© 2024 Reforestamos México. Todos los derechos reservados.
