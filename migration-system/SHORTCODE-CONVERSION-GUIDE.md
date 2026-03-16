# Guía de Conversión de Shortcodes

## Descripción General

El sistema de conversión de shortcodes transforma automáticamente los shortcodes del tema legacy a bloques Gutenberg equivalentes en el Block Theme. Este documento describe el proceso de conversión, los shortcodes soportados y cómo manejar casos especiales.

## Shortcodes Soportados

El sistema convierte automáticamente los siguientes shortcodes:

| Shortcode Legacy | Bloque Gutenberg | Descripción |
|-----------------|------------------|-------------|
| `[contact-form]` | `reforestamos/contacto` | Formulario de contacto |
| `[arboles-ciudades]` | `reforestamos/arboles-ciudades` | Micrositio Árboles y Ciudades |
| `[red-oja]` | `reforestamos/red-oja` | Micrositio Red OJA |
| `[companies-grid]` | `reforestamos/logos-aliados` | Grid de logos de empresas |
| `[newsletter-subscribe]` | `reforestamos/newsletter-subscribe` | Formulario de suscripción |
| `[eventos-proximos]` | `reforestamos/eventos-proximos` | Lista de próximos eventos |
| `[company-gallery]` | `reforestamos/galeria-tabs` | Galería de empresa |

## Conversión de Atributos

### Contact Form

**Antes:**
```
[contact-form id="main" show_phone="true" show_address="false"]
```

**Después:**
```html
<!-- wp:reforestamos/contacto {"formId":"main","showPhone":true,"showAddress":false} /-->
```

**Atributos soportados:**
- `id` → `formId`: ID del formulario
- `show_phone` → `showPhone`: Mostrar campo de teléfono
- `show_address` → `showAddress`: Mostrar campo de dirección

### Companies Grid

**Antes:**
```
[companies-grid columns="4" category="corporativo" linkable="true"]
```

**Después:**
```html
<!-- wp:reforestamos/logos-aliados {"columns":4,"category":"corporativo","linkable":true} /-->
```

**Atributos soportados:**
- `columns` → `columns`: Número de columnas
- `category` → `category`: Categoría de empresas
- `linkable` → `linkable`: Enlaces clickeables

### Company Gallery

**Antes:**
```
[company-gallery id="123" columns="3" lightbox="true"]
```

**Después:**
```html
<!-- wp:reforestamos/galeria-tabs {"companyId":123,"columns":3,"lightbox":true} /-->
```

**Atributos soportados:**
- `id` → `companyId`: ID de la empresa
- `columns` → `columns`: Número de columnas
- `lightbox` → `lightbox`: Activar lightbox

### Eventos Próximos

**Antes:**
```
[eventos-proximos count="5" show_past="false" layout="grid"]
```

**Después:**
```html
<!-- wp:reforestamos/eventos-proximos {"count":5,"showPast":false,"layout":"grid"} /-->
```

**Atributos soportados:**
- `count` → `count`: Número de eventos a mostrar
- `show_past` → `showPast`: Mostrar eventos pasados
- `layout` → `layout`: Diseño (grid, list)

### Newsletter Subscribe

**Antes:**
```
[newsletter-subscribe show_name="true" button_text="Suscribirse"]
```

**Después:**
```html
<!-- wp:reforestamos/newsletter-subscribe {"showName":true,"buttonText":"Suscribirse"} /-->
```

**Atributos soportados:**
- `show_name` → `showName`: Mostrar campo de nombre
- `button_text` → `buttonText`: Texto del botón

### Micrositios (Árboles y Ciudades / Red OJA)

**Antes:**
```
[arboles-ciudades height="600px" zoom="10" center_lat="19.4326" center_lng="-99.1332"]
```

**Después:**
```html
<!-- wp:reforestamos/arboles-ciudades {"height":"600px","zoom":10,"centerLat":19.4326,"centerLng":-99.1332} /-->
```

**Atributos soportados:**
- `height` → `height`: Altura del mapa
- `zoom` → `zoom`: Nivel de zoom
- `center_lat` → `centerLat`: Latitud del centro
- `center_lng` → `centerLng`: Longitud del centro

## Shortcodes con Contenido

El sistema también maneja shortcodes que contienen contenido interno:

**Antes:**
```
[custom-section]
Este es el contenido interno
[/custom-section]
```

**Después:**
```html
<!-- wp:reforestamos/custom-section -->
Este es el contenido interno
<!-- /wp:reforestamos/custom-section -->
```

## Detección de Shortcodes No Convertibles

El sistema identifica automáticamente shortcodes que no pueden ser convertidos:

### Shortcodes Excluidos

Los siguientes shortcodes de WordPress core NO se convierten (ya tienen soporte nativo):
- `[caption]`
- `[gallery]`
- `[audio]`
- `[video]`
- `[embed]`

### Shortcodes Personalizados

Cualquier shortcode que no esté en el mapeo será marcado como no convertible y se generará una advertencia:

```html
<!-- Advertencia: Este shortcode requiere conversión manual -->
[custom-shortcode]
```

## Conversión de Templates

El sistema también convierte templates de página personalizados:

| Template Legacy | Template Block Theme |
|----------------|---------------------|
| `template-full-width.php` | `page-full-width` |
| `template-landing.php` | `page-landing` |
| `template-contact.php` | `page-contact` |
| `template-about.php` | `page-about` |
| `page-empresas.php` | `single-empresas` |
| `page-eventos.php` | `archive-eventos` |
| `single-empresa.php` | `single-empresas` |
| `single-evento.php` | `single-eventos` |

## Uso del Sistema

### Ver Estadísticas

Antes de ejecutar la migración, revisa las estadísticas:

```bash
wp reforestamos stats
```

**Salida:**
```
=== ESTADÍSTICAS DE MIGRACIÓN ===

Total de shortcodes encontrados: 45
Posts afectados: 23

Shortcodes convertibles por tipo:
  [contact-form]: 5
  [companies-grid]: 12
  [eventos-proximos]: 8
  [newsletter-subscribe]: 20

Shortcodes NO convertibles encontrados:
  [old-custom-shortcode]: 3 ocurrencias

Ejecute "wp reforestamos non-convertible" para ver detalles completos
```

### Ver Shortcodes No Convertibles

Para ver detalles de shortcodes que requieren conversión manual:

```bash
wp reforestamos non-convertible
```

**Salida:**
```
Shortcodes que requieren conversión manual:

[old-custom-shortcode] - 3 ocurrencias
Posts afectados:
+----+---------------------------+------+
| ID | Título                    | Tipo |
+----+---------------------------+------+
| 42 | Página de Ejemplo         | page |
| 58 | Post Antiguo              | post |
| 91 | Otra Página               | page |
+----+---------------------------+------+
```

### Exportar Reporte

Puedes exportar el reporte en diferentes formatos:

**JSON:**
```bash
wp reforestamos non-convertible --format=json > non-convertible.json
```

**CSV:**
```bash
wp reforestamos non-convertible --format=csv > non-convertible.csv
```

### Ejecutar Conversión

**Modo Dry-Run (Simulación):**
```bash
wp reforestamos migrate --dry-run --verbose
```

**Conversión Real:**
```bash
wp reforestamos migrate --verbose
```

## Proceso de Conversión

El sistema sigue estos pasos:

1. **Análisis**: Escanea todos los posts y páginas buscando shortcodes
2. **Validación**: Verifica si cada shortcode puede ser convertido
3. **Conversión**: Transforma shortcodes a bloques Gutenberg
4. **Preservación**: Mantiene atributos y configuraciones
5. **Advertencias**: Marca shortcodes no convertibles
6. **Actualización**: Guarda el contenido convertido

## Manejo de Errores

### Shortcode Sin Atributos Requeridos

Si un shortcode requiere atributos específicos que no están presentes:

```
[company-gallery]  <!-- Sin ID -->
```

El sistema generará una advertencia:
```
Shortcode [company-gallery] no puede ser convertido automáticamente. Requiere conversión manual.
```

### Shortcode Anidado

Los shortcodes anidados se convierten recursivamente:

```
[outer-shortcode]
  [inner-shortcode]
  Contenido
  [/inner-shortcode]
[/outer-shortcode]
```

### Error de Actualización

Si hay un error al actualizar un post, el sistema:
1. Registra el error en el log
2. Continúa con el siguiente post
3. Incluye el error en el reporte final

## Conversión Manual

Para shortcodes no convertibles, sigue estos pasos:

### 1. Identificar el Shortcode

Usa el comando `non-convertible` para listar todos los shortcodes que requieren atención manual.

### 2. Crear el Bloque Equivalente

Si el shortcode tiene funcionalidad que debe preservarse:

1. Crea un nuevo bloque Gutenberg en `reforestamos-block-theme/blocks/`
2. Implementa la funcionalidad del shortcode
3. Registra el bloque en `functions.php`

### 3. Agregar al Mapeo

Edita `class-shortcode-converter.php`:

```php
private $shortcode_mapping = [
    // ... shortcodes existentes
    'mi-shortcode-custom' => 'reforestamos/mi-bloque-custom',
];
```

### 4. Configurar Conversión de Atributos

Si el shortcode tiene atributos especiales, agrégalos en `convert_attributes_to_block_format()`:

```php
case 'mi-shortcode-custom':
    if (isset($attributes['custom_attr'])) {
        $block_attributes['customAttr'] = $attributes['custom_attr'];
    }
    break;
```

### 5. Re-ejecutar Migración

Ejecuta nuevamente la conversión para los posts afectados.

## Validación Post-Conversión

Después de la conversión, verifica:

### 1. Bloques Renderizados

Visita las páginas convertidas y verifica que los bloques se muestren correctamente.

### 2. Atributos Preservados

Verifica que los atributos del shortcode se hayan convertido correctamente:

```bash
wp post get <POST_ID> --field=post_content
```

### 3. Funcionalidad

Prueba la funcionalidad de cada bloque convertido (formularios, mapas, etc.).

## Rollback

Si necesitas revertir la conversión:

### 1. Restaurar desde Backup

```bash
# Listar backups disponibles
ls -lh wp-content/reforestamos-backups/

# Restaurar backup (requiere acceso a MySQL)
mysql -u usuario -p nombre_db < wp-content/reforestamos-backups/backup-YYYY-MM-DD-HH-MM-SS.sql
```

### 2. Conversión Inversa

No hay conversión automática de bloques a shortcodes. Si necesitas revertir:

1. Restaura desde el backup
2. O edita manualmente los posts afectados

## Mejores Prácticas

### Antes de la Migración

1. ✅ Ejecuta `wp reforestamos stats` para conocer el alcance
2. ✅ Revisa shortcodes no convertibles con `wp reforestamos non-convertible`
3. ✅ Crea un backup manual: `wp reforestamos backup`
4. ✅ Ejecuta en modo dry-run: `wp reforestamos migrate --dry-run`
5. ✅ Revisa el reporte generado

### Durante la Migración

1. ✅ Usa `--verbose` para ver el progreso detallado
2. ✅ Monitorea los logs en tiempo real
3. ✅ No interrumpas el proceso

### Después de la Migración

1. ✅ Revisa el reporte completo
2. ✅ Verifica páginas críticas manualmente
3. ✅ Prueba formularios y funcionalidades interactivas
4. ✅ Convierte manualmente shortcodes no convertibles
5. ✅ Mantén el backup por al menos 30 días

## Troubleshooting

### Problema: "Shortcode no convertible"

**Causa:** El shortcode no tiene mapeo definido.

**Solución:** 
1. Verifica si el shortcode es necesario
2. Crea un bloque equivalente si es necesario
3. Agrega el mapeo en `class-shortcode-converter.php`

### Problema: "Error al actualizar post"

**Causa:** Permisos insuficientes o post bloqueado.

**Solución:**
1. Verifica permisos de usuario
2. Desbloquea el post si está siendo editado
3. Revisa logs de WordPress para más detalles

### Problema: Atributos no se convierten correctamente

**Causa:** Formato de atributos no estándar.

**Solución:**
1. Revisa el formato del shortcode original
2. Ajusta el parser en `parse_shortcode_attributes()`
3. Agrega conversión específica en `convert_attributes_to_block_format()`

### Problema: Contenido interno se pierde

**Causa:** Shortcode con contenido no detectado correctamente.

**Solución:**
1. Verifica que el shortcode tenga tag de cierre `[/shortcode]`
2. Revisa el regex en `convert_shortcode_to_block()`

## Soporte

Para problemas o preguntas:

1. Revisa los logs en `wp-content/reforestamos-migration-reports/`
2. Consulta la documentación del sistema de migración
3. Contacta al equipo de desarrollo

## Referencias

- [Documentación de Bloques Gutenberg](https://developer.wordpress.org/block-editor/)
- [Shortcode API de WordPress](https://developer.wordpress.org/plugins/shortcodes/)
- [Guía de Migración Completa](./README.md)
- [Implementación de Task 43](./TASK-43-IMPLEMENTATION.md)
