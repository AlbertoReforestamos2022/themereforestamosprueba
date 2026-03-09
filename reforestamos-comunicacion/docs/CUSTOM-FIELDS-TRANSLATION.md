# Custom Fields Translation - Implementation Documentation

## Overview

The DeepL integration now supports automatic translation of custom fields (post meta) in addition to post title, content, and excerpt. This feature intelligently identifies which custom fields should be translated and which should be copied as-is.

## Implementation Details

### Translatable Fields by Post Type

The system identifies translatable fields based on their data type. Only text-based fields that contain human-readable content are translated.

#### Eventos (Events)
- **Translatable:**
  - `evento_ubicacion` - Location/venue name (text)
- **Non-translatable (copied as-is):**
  - `evento_fecha` - Event date (datetime)
  - `evento_lat` - Latitude (number)
  - `evento_lng` - Longitude (number)
  - `evento_capacidad` - Capacity (number)
  - `evento_registro_activo` - Registration active (checkbox)

#### Integrantes (Team Members)
- **Translatable:**
  - `integrante_cargo` - Position/role (text)
- **Non-translatable (copied as-is):**
  - `integrante_email` - Email address (email)
  - `integrante_redes` - Social media links (group repeater)

#### Empresas (Companies)
- **All fields are non-translatable:**
  - `empresa_logo` - Logo (file)
  - `empresa_url` - Website URL (url)
  - `empresa_categoria` - Category (select)
  - `empresa_anio` - Year (number)
  - `empresa_arboles` - Trees planted (number)

#### Boletín (Newsletter)
- **All fields are non-translatable:**
  - `boletin_fecha_envio` - Send date (datetime)
  - `boletin_estado` - Status (select)
  - `boletin_destinatarios` - Recipients count (readonly)

### Translation Process

When a post is translated:

1. **Post content translation** (title, content, excerpt)
2. **Custom fields processing:**
   - Get all post meta from source post
   - Identify translatable fields using `get_translatable_fields()`
   - For each field:
     - If translatable: Translate using DeepL API
     - If non-translatable: Copy value as-is
     - If translation fails: Log error and copy original value as fallback
3. **Metadata linking** (original/translated post IDs)

### Error Handling

The system handles errors gracefully:

- **Translation failure:** If a custom field translation fails, the system:
  - Logs the error to PHP error log
  - Copies the original value as fallback
  - Continues processing other fields
  - Does NOT fail the entire translation

- **Empty values:** Empty custom fields are skipped (not translated or copied)

- **Missing fields:** If a field doesn't exist on the source post, it's not created on the translated post

### Code Structure

#### Main Methods

**`translate_post()`**
- Main translation orchestrator
- Calls `translate_custom_fields()` after creating/updating translated post

**`translate_custom_fields()`**
- Processes all custom fields for a post
- Identifies translatable vs non-translatable fields
- Handles translation and copying

**`get_translatable_fields()`**
- Returns array of translatable field keys for a post type
- Filterable via `reforestamos_translatable_fields` hook

### Extensibility

#### Adding Custom Translatable Fields

Developers can add custom translatable fields using the filter:

```php
add_filter( 'reforestamos_translatable_fields', function( $fields, $post_type ) {
    if ( $post_type === 'my_custom_post_type' ) {
        $fields[] = 'my_custom_text_field';
    }
    return $fields;
}, 10, 2 );
```

#### Field Type Guidelines

**Should be translated:**
- Plain text fields with human-readable content
- Textarea fields with descriptions
- WYSIWYG editor fields

**Should NOT be translated:**
- URLs and email addresses
- Numbers and dates
- File paths and IDs
- Select/dropdown values (use taxonomy translation instead)
- Checkboxes and boolean values
- Coordinates and technical data
- Repeater groups (complex structure)

## Usage

### For Content Editors

1. Edit a post with custom fields (Eventos, Integrantes, etc.)
2. Fill in the custom fields in the source language
3. Click "Translate to English" or "Traducir a Español"
4. The system automatically:
   - Translates the post content
   - Translates applicable custom fields
   - Copies non-translatable fields
   - Links the posts together

### For Developers

#### Checking Translation Status

```php
// Get translated post ID
$translated_id = get_post_meta( $post_id, '_translated_post_id', true );

// Get original post ID (if current post is a translation)
$original_id = get_post_meta( $post_id, '_original_post_id', true );

// Get translation language
$lang = get_post_meta( $post_id, '_translation_lang', true );
```

#### Debugging Translation Issues

Translation errors are logged to the PHP error log:

```
DeepL Translation: Failed to translate custom field "evento_ubicacion" for post 123: API error message
```

Check your WordPress debug log for these messages if translations are not working as expected.

## Testing

### Manual Testing Checklist

#### Test Eventos Translation
1. Create an Evento with:
   - Title: "Reforestación en Chapultepec"
   - Content: "Únete a nosotros para plantar árboles..."
   - Ubicación: "Bosque de Chapultepec, Ciudad de México"
   - Fecha: 2024-06-15
   - Capacidad: 50
2. Translate to English
3. Verify translated post has:
   - Translated title and content
   - Translated ubicación: "Chapultepec Forest, Mexico City"
   - Same fecha, capacidad (copied as-is)

#### Test Integrantes Translation
1. Create an Integrante with:
   - Title: "Juan Pérez"
   - Cargo: "Director de Operaciones"
   - Email: "juan@example.com"
2. Translate to English
3. Verify translated post has:
   - Translated cargo: "Operations Director"
   - Same email (copied as-is)

#### Test Empresas Translation
1. Create an Empresa with all fields filled
2. Translate to English
3. Verify all fields are copied as-is (no translation)

## Performance Considerations

- Each translatable custom field makes an additional API call to DeepL
- For posts with multiple translatable fields, translation may take longer
- API rate limits apply to custom field translations
- Consider the character count of custom fields in your DeepL quota

## Future Enhancements

Potential improvements for future versions:

1. **Batch translation:** Translate multiple custom fields in a single API call
2. **Repeater group support:** Handle complex repeater fields (e.g., social media links)
3. **Selective translation:** UI to choose which fields to translate
4. **Translation memory:** Cache translations to avoid re-translating identical content
5. **WYSIWYG field support:** Handle rich text custom fields with HTML

## Related Files

- `reforestamos-comunicacion/includes/class-deepl-integration.php` - Main implementation
- `reforestamos-core/includes/class-custom-fields.php` - Custom fields definitions
- `reforestamos-comunicacion/docs/TRANSLATION-IMPLEMENTATION.md` - General translation docs

## Support

For issues or questions about custom fields translation:
1. Check the PHP error log for translation errors
2. Verify DeepL API key is configured correctly
3. Ensure custom fields are properly registered in Core plugin
4. Check that field values are not empty
