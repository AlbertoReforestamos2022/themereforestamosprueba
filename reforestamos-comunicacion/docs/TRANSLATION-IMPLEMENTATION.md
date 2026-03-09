# DeepL Translation Implementation - Task 22.3

## Overview
This document describes the implementation of the content translation functionality for the Reforestamos Comunicación plugin, completed in task 22.3.

## Implementation Summary

### Features Implemented

#### 1. AJAX Handler for Post Translation
- **Endpoint:** `wp_ajax_translate_post`
- **Location:** `class-deepl-integration.php::ajax_translate_post()`
- **Security:**
  - Nonce verification using `reforestamos_translate` nonce
  - User capability check (`edit_posts`)
  - Input sanitization and validation
- **Parameters:**
  - `post_id` (int): ID of the post to translate
  - `target_lang` (string): Target language code (EN or ES)
  - `nonce` (string): Security nonce

#### 2. Post Translation Logic
- **Method:** `translate_post()`
- **Functionality:**
  - Translates post title using `translate_text()` (plain text)
  - Translates post content using `translate_html()` (HTML with tag preservation)
  - Translates post excerpt using `translate_text()` (plain text)
  - Translates custom fields using `translate_custom_fields()` (see Task 22.4)
  - Creates new translated post or updates existing one
  - Copies post metadata (status, author, featured image)
  - Copies taxonomies (categories, tags, custom taxonomies)
  - Links original and translated posts using post meta

#### 3. Post Meta Linking
Three post meta fields are used to link translations:

**On Original Post:**
- `_translated_post_id`: Stores the ID of the translated post

**On Translated Post:**
- `_original_post_id`: Stores the ID of the original post
- `_translation_lang`: Stores the language code (EN or ES)

#### 4. JavaScript Interface
- **Location:** `get_translation_script()`
- **Features:**
  - Real-time AJAX requests to translation endpoint
  - Progress indicator with spinner
  - Success message with link to translated post
  - Error handling with user-friendly messages
  - Automatic page reload after successful translation
  - Button state management (disable during translation)

## Technical Details

### Translation Workflow

```
1. User clicks translation button
   ↓
2. JavaScript validates post ID and nonce
   ↓
3. AJAX request sent to wp_ajax_translate_post
   ↓
4. Server validates security and permissions
   ↓
5. Post content retrieved
   ↓
6. Title translated (plain text)
   ↓
7. Content translated (HTML preserved)
   ↓
8. Excerpt translated (if exists)
   ↓
9. Check if translation exists
   ↓
10a. If exists: Update existing post
10b. If not: Create new post
   ↓
11. Copy metadata and taxonomies
   ↓
12. Set post meta links
   ↓
13. Return success with translated post ID
   ↓
14. JavaScript shows success message
   ↓
15. Page reloads to show updated status
```

### Post Creation vs Update Logic

**When Translation Doesn't Exist:**
- New post created with `wp_insert_post()`
- Featured image copied using `set_post_thumbnail()`
- Categories and tags copied for standard posts
- Custom taxonomies copied for custom post types
- Post meta links established

**When Translation Exists:**
- Existing post updated with `wp_update_post()`
- Post ID remains the same
- Metadata and taxonomies preserved
- Only content fields updated

### HTML Preservation

The implementation uses DeepL's `tag_handling: 'html'` parameter to preserve HTML structure during translation:

- HTML tags are not translated
- Tag attributes are preserved
- Text content within tags is translated
- Nested HTML structures maintained

Example:
```html
Input:  <p>Texto con <strong>negrita</strong></p>
Output: <p>Text with <strong>bold</strong></p>
```

### Error Handling

The implementation handles multiple error scenarios:

1. **Security Errors:**
   - Invalid nonce
   - Insufficient permissions
   - Invalid post ID

2. **Translation Errors:**
   - API connection failures
   - Invalid API key
   - Rate limit exceeded
   - Invalid response format

3. **Post Creation Errors:**
   - Failed to create post
   - Failed to update post
   - Failed to copy metadata

All errors return user-friendly messages in Spanish.

## API Integration

### DeepL API Calls

**For Title and Excerpt (Plain Text):**
```php
$this->translate_text($text, $target_lang, $source_lang)
```

**For Content (HTML):**
```php
$this->translate_html($html, $target_lang, $source_lang)
```

Both methods:
- Use the configured API endpoint (free or pro)
- Include API key in Authorization header
- Handle API errors gracefully
- Return translated text or WP_Error

## User Interface

### Translation Metabox

The metabox displays:

1. **Current Language Indicator:**
   - 🇪🇸 Español or 🇬🇧 English
   - Determined from post meta

2. **Translation Status:**
   - "✓ Traducción existente" with link (if translation exists)
   - "Ver original →" link (if this is a translation)
   - "Sin traducción" (if no translation)

3. **Action Buttons:**
   - "🇬🇧 Translate to English" (primary button)
   - "🇪🇸 Traducir a Español" (secondary button)

4. **Status Messages:**
   - Loading: Spinner with "Traduciendo contenido..."
   - Success: Green background with "✓ Post traducido exitosamente"
   - Error: Red background with error message

5. **Help Text:**
   - "La traducción creará o actualizará un post vinculado en el idioma seleccionado."

## Supported Post Types

The translation metabox appears on:
- Posts (`post`)
- Pages (`page`)
- Empresas (`empresas`)
- Eventos (`eventos`)
- Integrantes (`integrantes`)
- Boletín (`boletin`)

## Requirements Fulfilled

This implementation fulfills the following requirements from the spec:

- **11.3:** Content is sent to DeepL API and translated post is created/updated
- **11.4:** Translated post is created or existing translation is updated
- **11.5:** HTML formatting is preserved during translation using `tag_handling: 'html'`
- **11.9:** Posts are linked using post meta fields (`_translated_post_id`, `_original_post_id`, `_translation_lang`)

## Testing

Manual testing procedures are documented in:
- `tests/manual-translation-test.md`

Key test scenarios:
1. Translate post to English
2. Translate post to Spanish
3. Update existing translation
4. Translate custom post types
5. Preserve complex HTML
6. Error handling
7. Security verification
8. Post meta linking

## Future Enhancements (Not in Current Scope)

The following features are planned for future tasks:

- **Task 22.5:** Rate limit handling and translation queue
- Bulk translation of multiple posts
- Translation history and versioning
- Language switcher on frontend

## Custom Fields Translation (Task 22.4)

Custom fields translation is now implemented. See detailed documentation in:
- `docs/CUSTOM-FIELDS-TRANSLATION.md`

**Summary:**
- Translatable fields: `evento_ubicacion`, `integrante_cargo`
- Non-translatable fields are copied as-is (URLs, emails, numbers, dates, etc.)
- Graceful error handling with fallback to original values
- Extensible via `reforestamos_translatable_fields` filter

## Code Location

All translation functionality is implemented in:
```
reforestamos-comunicacion/includes/class-deepl-integration.php
```

Key methods:
- `ajax_translate_post()` - AJAX handler
- `translate_post()` - Main translation logic
- `translate_custom_fields()` - Custom fields translation (Task 22.4)
- `get_translatable_fields()` - Field identification (Task 22.4)
- `get_translation_script()` - JavaScript interface

## Dependencies

- DeepL API (free or pro account)
- WordPress 6.0+
- Reforestamos Core plugin (for custom post types and fields)
- PHP 7.4+
- jQuery (WordPress core)

## Configuration

Translation requires:
1. DeepL API key configured in plugin settings
2. User with `edit_posts` capability
3. Post with content to translate

## Performance Considerations

- Translation is performed synchronously (user waits for completion)
- Typical translation time: 2-5 seconds depending on content length
- API timeout set to 30 seconds
- Page reloads after successful translation to update UI

## Security Measures

1. **Nonce Verification:** All AJAX requests verified
2. **Capability Checks:** User must have `edit_posts` permission
3. **Input Sanitization:** All inputs sanitized before use
4. **Output Escaping:** All outputs escaped for display
5. **API Key Protection:** API key stored securely in options table

## Conclusion

Task 22.3 successfully implements complete content translation functionality, allowing users to translate posts between Spanish and English with a single click. The implementation preserves HTML formatting, copies metadata, and maintains bidirectional links between original and translated posts.
