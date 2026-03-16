# Guía de Migración de Contenido

## Descripción General

El sistema de migración de contenido (`class-content-migrator.php`) maneja la transferencia completa de datos del tema legacy al Block Theme moderno. Este documento describe la funcionalidad implementada y cómo utilizarla.

## Funcionalidades Implementadas

### 1. Migración de Posts y Pages (Subtask 43.1)

**Funcionalidad:**
- Verifica y migra todos los posts y páginas del sitio
- Preserva fechas de publicación, autores y metadata
- Valida y corrige datos inconsistentes
- Verifica imágenes destacadas
- Mantiene relaciones padre-hijo en páginas

**Campos Preservados:**
- `post_date` - Fecha de publicación
- `post_author` - Autor del post
- `post_status` - Estado (publicado, borrador, etc.)
- `post_parent` - Relación padre-hijo (páginas)
- `_thumbnail_id` - Imagen destacada

**Validaciones:**
- Fechas inválidas se corrigen a la fecha actual
- Autores inexistentes se asignan al usuario actual
- Imágenes destacadas faltantes se eliminan
- Relaciones padre-hijo inválidas se corrigen

**Ejemplo de uso:**
```php
$migrator = new Reforestamos_Content_Migrator($dry_run = false, $verbose = true);
$result = $migrator->migrate_posts_and_pages();
// Retorna: ['count' => 150, 'errors' => []]
```

### 2. Migración de Custom Post Types (Subtask 43.2)

**Custom Post Types Soportados:**
- **Empresas** - Empresas colaboradoras
- **Eventos** - Eventos de reforestación
- **Integrantes** - Miembros del equipo
- **Boletín** - Boletines informativos

**Funcionalidad:**
- Migra todos los CPTs preservando sus datos
- Verifica metadata específica de cada tipo
- Mantiene relaciones y taxonomías
- Valida campos requeridos

**Campos Verificados por CPT:**

**Empresas:**
- `empresa_logo` - Logo de la empresa
- `empresa_url` - Sitio web
- `empresa_descripcion` - Descripción

**Eventos:**
- `evento_fecha` - Fecha del evento
- `evento_ubicacion` - Ubicación

**Integrantes:**
- `integrante_cargo` - Cargo/posición
- `integrante_foto` - Fotografía

**Boletín:**
- `boletin_fecha_publicacion` - Fecha de publicación

**Ejemplo de uso:**
```php
$result = $migrator->migrate_custom_post_types();
// Retorna: ['count' => 75, 'errors' => []]
```

### 3. Migración de Custom Fields (Subtask 43.3)

**Funcionalidad:**
- Mapea campos antiguos a nueva estructura CMB2
- Convierte formatos de datos cuando es necesario
- Maneja datos faltantes o inválidos gracefully
- Preserva toda la metadata existente

**Mapeo de Campos:**

```php
$field_mapping = [
    // Empresas
    'old_empresa_logo' => 'empresa_logo',
    'old_empresa_website' => 'empresa_url',
    'old_empresa_descripcion' => 'empresa_descripcion',
    'old_empresa_categoria' => 'empresa_categoria',
    
    // Eventos
    'old_evento_fecha' => 'evento_fecha',
    'old_evento_ubicacion' => 'evento_ubicacion',
    'old_evento_capacidad' => 'evento_capacidad',
    
    // Integrantes
    'old_integrante_cargo' => 'integrante_cargo',
    'old_integrante_foto' => 'integrante_foto',
    'old_integrante_bio' => 'integrante_biografia',
];
```

**Proceso:**
1. Identifica campos con nomenclatura antigua
2. Actualiza `meta_key` en la tabla `postmeta`
3. Preserva todos los valores (`meta_value`)
4. Registra campos migrados en el log

**Ejemplo de uso:**
```php
$result = $migrator->migrate_custom_fields();
// Retorna: ['count' => 250, 'errors' => []]
```

### 4. Migración de Taxonomías (Subtask 43.4)

**Taxonomías Soportadas:**
- `category` - Categorías estándar
- `post_tag` - Etiquetas estándar
- `categoria_empresa` - Categorías de empresas
- `tipo_evento` - Tipos de eventos
- `categoria_boletin` - Categorías de boletín

**Funcionalidad:**
- Verifica existencia de taxonomías
- Migra todos los términos
- Preserva jerarquías (términos padre-hijo)
- Mantiene relaciones post-término
- Valida y corrige relaciones inválidas

**Validaciones:**
- Términos huérfanos (padre inexistente) se corrigen
- Relaciones post-término se verifican
- Taxonomías faltantes se reportan como warnings

**Ejemplo de uso:**
```php
$result = $migrator->migrate_taxonomies();
// Retorna: ['count' => 45, 'errors' => []]
```

### 5. Migración de Media (Subtask 43.5)

**Funcionalidad:**
- Verifica que todos los archivos de media existen físicamente
- Valida metadata de attachments
- Regenera metadata faltante para imágenes
- Actualiza referencias en contenido de posts
- Identifica archivos faltantes

**Proceso de Verificación:**

1. **Verificación de Archivos:**
   - Obtiene ruta física del archivo con `get_attached_file()`
   - Verifica existencia con `file_exists()`
   - Reporta archivos faltantes

2. **Validación de Metadata:**
   - Verifica metadata con `wp_get_attachment_metadata()`
   - Regenera metadata para imágenes si falta
   - Usa `wp_generate_attachment_metadata()` para regenerar

3. **Actualización de Referencias:**
   - Busca posts que referencian cada archivo
   - Verifica URLs en `post_content`
   - Reporta número de referencias encontradas

**Ejemplo de uso:**
```php
$result = $migrator->migrate_media();
// Retorna: ['count' => 320, 'errors' => []]
```

**Estadísticas Reportadas:**
- Total de archivos verificados
- Archivos faltantes
- Metadata inválida regenerada

## Uso del Sistema

### Modo Dry-Run

El modo dry-run permite previsualizar cambios sin aplicarlos:

```php
$migrator = new Reforestamos_Content_Migrator($dry_run = true, $verbose = true);
$results = $migrator->migrate_all_content();
```

**Características:**
- No realiza cambios en la base de datos
- Reporta qué cambios se realizarían
- Útil para validar antes de migración real
- Genera log completo de operaciones

### Modo Verbose

El modo verbose proporciona logging detallado:

```php
$migrator = new Reforestamos_Content_Migrator($dry_run = false, $verbose = true);
```

**Output incluye:**
- Timestamp de cada operación
- Nivel de log (info, warning, error)
- Detalles de cada elemento procesado
- Errores y advertencias

### Migración Completa

Para ejecutar migración completa de contenido:

```php
// Crear instancia
$migrator = new Reforestamos_Content_Migrator(false, true);

// Ejecutar migración completa
$results = $migrator->migrate_all_content();

// Verificar resultados
if (!empty($results['errors'])) {
    foreach ($results['errors'] as $error) {
        error_log("Migration error: " . $error);
    }
}

echo "Migrados: {$results['migrated_count']} elementos\n";
echo "Errores: " . count($results['errors']) . "\n";
```

### Integración con Migration Manager

El Content Migrator se integra automáticamente con el Migration Manager:

```php
$manager = Reforestamos_Migration_Manager::get_instance();
$results = $manager->run_migration([
    'dry_run' => false,
    'verbose' => true,
    'backup' => true,
]);
```

El Migration Manager:
1. Crea backup automático
2. Ejecuta Content Migrator
3. Ejecuta Shortcode Converter
4. Genera reporte completo

## Comandos WP-CLI

### Migración Completa

```bash
# Dry-run (previsualización)
wp reforestamos migrate --dry-run

# Migración real
wp reforestamos migrate

# Con verbose
wp reforestamos migrate --verbose
```

### Migración Específica

```bash
# Solo posts y páginas
wp reforestamos migrate-posts

# Solo Custom Post Types
wp reforestamos migrate-cpts

# Solo custom fields
wp reforestamos migrate-fields

# Solo taxonomías
wp reforestamos migrate-taxonomies

# Solo media
wp reforestamos migrate-media
```

## Manejo de Errores

### Estrategia de Errores

El sistema implementa una estrategia de "continuar en error":

1. **Errores No Críticos:**
   - Se registran en el array de errores
   - La migración continúa con el siguiente elemento
   - Se reportan al final

2. **Errores Críticos:**
   - Se capturan con try-catch
   - Se registran con nivel 'error'
   - Se incluyen en el reporte final

3. **Warnings:**
   - Situaciones no ideales pero no bloqueantes
   - Campos faltantes
   - Relaciones inválidas corregidas
   - Archivos faltantes

### Tipos de Errores Comunes

**1. Datos Inconsistentes:**
```
Error: Fecha inválida para post 123
Solución: Se asigna fecha actual
```

**2. Relaciones Rotas:**
```
Warning: Relación padre inválida para página 456
Solución: Se elimina relación padre
```

**3. Archivos Faltantes:**
```
Warning: Archivo faltante: /uploads/2023/01/image.jpg (ID: 789)
Solución: Se reporta, attachment permanece
```

**4. Metadata Inválida:**
```
Warning: Metadata faltante para attachment 321
Solución: Se regenera metadata automáticamente
```

## Resultados y Reportes

### Estructura de Resultados

```php
$results = [
    'migrated_count' => 850,  // Total de elementos procesados
    'errors' => [             // Array de errores
        'Error al actualizar post 123: ...',
        'Excepción al migrar campo: ...',
    ],
];
```

### Reporte de Migración

El sistema genera un reporte detallado en:
```
wp-content/reforestamos-migration-reports/migration-report-YYYY-MM-DD-HH-ii-ss.txt
```

**Contenido del reporte:**
- Fecha y hora de migración
- Modo (dry-run o producción)
- Estadísticas por tipo de contenido
- Lista de errores y warnings
- Log detallado de operaciones
- Archivo de backup utilizado

## Rollback

En caso de problemas, se puede hacer rollback:

```bash
# Restaurar desde backup
wp reforestamos rollback /path/to/backup.sql

# Listar backups disponibles
wp reforestamos list-backups
```

## Mejores Prácticas

### Antes de Migrar

1. **Crear Backup Manual:**
   ```bash
   wp db export backup-pre-migration.sql
   ```

2. **Ejecutar Dry-Run:**
   ```bash
   wp reforestamos migrate --dry-run
   ```

3. **Revisar Reporte:**
   - Verificar número de elementos a migrar
   - Revisar warnings y errores potenciales
   - Confirmar que todo está correcto

### Durante la Migración

1. **Usar Modo Verbose:**
   - Monitorear progreso en tiempo real
   - Identificar problemas inmediatamente

2. **No Interrumpir:**
   - Dejar que el proceso complete
   - El sistema maneja errores gracefully

### Después de Migrar

1. **Verificar Resultados:**
   - Revisar reporte de migración
   - Verificar contenido en el sitio
   - Probar funcionalidades críticas

2. **Validar Datos:**
   - Posts y páginas visibles
   - Custom Post Types funcionando
   - Taxonomías correctas
   - Media cargando correctamente

3. **Mantener Backup:**
   - Guardar backup por al menos 30 días
   - No eliminar hasta confirmar éxito total

## Solución de Problemas

### Problema: Muchos Archivos Faltantes

**Síntoma:** Reporte muestra muchos archivos de media faltantes

**Solución:**
1. Verificar directorio `wp-content/uploads/`
2. Restaurar archivos desde backup del servidor
3. Re-ejecutar migración de media

### Problema: Custom Fields No Migran

**Síntoma:** Campos personalizados aparecen vacíos

**Solución:**
1. Verificar mapeo de campos en `migrate_custom_fields()`
2. Agregar campos faltantes al array `$field_mapping`
3. Re-ejecutar migración

### Problema: Taxonomías No Aparecen

**Síntoma:** Términos de taxonomía no visibles

**Solución:**
1. Verificar que Core Plugin está activado
2. Confirmar que taxonomías están registradas
3. Re-ejecutar migración de taxonomías

## Extensión del Sistema

### Agregar Nuevo Custom Post Type

1. Agregar tipo al array en `migrate_custom_post_types()`:
```php
$cpt_types = ['empresas', 'eventos', 'integrantes', 'boletin', 'nuevo_tipo'];
```

2. Agregar validación de metadata en `verify_cpt_metadata()`:
```php
case 'nuevo_tipo':
    $required_fields = ['campo1', 'campo2'];
    break;
```

### Agregar Nuevo Mapeo de Campos

Agregar al array en `migrate_custom_fields()`:
```php
$field_mapping = [
    // ... campos existentes
    'old_nuevo_campo' => 'nuevo_campo',
];
```

### Agregar Nueva Taxonomía

Agregar al array en `migrate_taxonomies()`:
```php
$taxonomies_to_migrate = [
    // ... taxonomías existentes
    'nueva_taxonomia' => 'Etiqueta de Nueva Taxonomía',
];
```

## Conclusión

El sistema de migración de contenido proporciona una solución robusta y completa para transferir datos del tema legacy al Block Theme moderno. Con soporte para dry-run, logging detallado, manejo de errores y rollback, garantiza una migración segura y confiable.

Para soporte adicional, consultar:
- `migration-system/README.md` - Documentación general
- `migration-system/IMPLEMENTATION-SUMMARY.md` - Resumen de implementación
- Logs en `wp-content/reforestamos-migration-reports/`
