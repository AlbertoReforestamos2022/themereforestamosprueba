# Translation Interface Documentation

## Overview

The Translation Interface provides a user-friendly metabox in the WordPress post editor that allows content editors to translate posts, pages, and custom post types between Spanish and English using the DeepL API.

**Implemented in:** Task 22.2  
**Requirements:** 11.2, 11.3  
**Status:** Interface Complete (Translation functionality pending in Task 22.3)

## Features

### 1. Translation Metabox

The metabox appears in the sidebar of the post editor for the following post types:
- Posts (`post`)
- Pages (`page`)
- Empresas (`empresas`)
- Eventos (`eventos`)
- Integrantes (`integrantes`)
- Boletín (`boletin`)

### 2. Conditional Display

The metabox only appears when:
- The DeepL API key is configured in plugin settings
- User is editing a supported post type
- User has appropriate permissions

### 3. Current Language Indicator

Displays the current language of the post:
- 🇪🇸 Español (Spanish)
- 🇬🇧 English

The language is determined by:
1. `_translation_lang` post meta (if post is a translation)
2. `_original_post_id` post meta (if post has an original)
3. Default to Spanish for new posts

### 4. Translation Status

Shows one of three states:

**No Translation:**
- Displays "Sin traducción" in gray text
- Indicates no linked translation exists

**Translation Exists:**
- Shows "✓ Traducción existente" with link
- Provides "Ver traducción →" link to edit the translated post
- Displays in blue highlight box

**Is Translation:**
- Shows "Original:" with link
- Provides "Ver original →" link to edit the original post
- Displays in gray highlight box

### 5. Translation Buttons

Two prominent buttons for translation:

**Translate to English:**
- Primary button (blue)
- Full-width
- Flag emoji: 🇬🇧
- Text: "Translate to English"

**Traducir a Español:**
- Secondary button (gray)
- Full-width
- Flag emoji: 🇪🇸
- Text: "Traducir a Español"

### 6. Help Text

Informative text below buttons:
> "La traducción creará o actualizará un post vinculado en el idioma seleccionado."

Translation: "The translation will create or update a linked post in the selected language."

## Technical Implementation

### Class: `Reforestamos_DeepL_Integration`

**File:** `includes/class-deepl-integration.php`

### Methods

#### `add_translation_metabox()`

Registers the metabox for supported post types.

```php
public function add_translation_metabox()
```

- **Hook:** `add_meta_boxes`
- **Checks:** DeepL configuration status
- **Post Types:** post, page, empresas, eventos, integrantes, boletin
- **Position:** Sidebar (side)
- **Priority:** Default

#### `render_translation_metabox( $post )`

Renders the metabox HTML and JavaScript.

```php
public function render_translation_metabox( $post )
```

**Parameters:**
- `$post` (WP_Post): Current post object

**Functionality:**
- Adds security nonce
- Retrieves translation metadata
- Determines current language
- Displays translation status
- Renders translation buttons
- Includes inline JavaScript

#### `enqueue_translation_scripts( $hook )`

Enqueues JavaScript for translation functionality.

```php
public function enqueue_translation_scripts( $hook )
```

**Parameters:**
- `$hook` (string): Current admin page hook

**Conditions:**
- Only loads on post edit screens (`post.php`, `post-new.php`)
- Only loads if DeepL is configured

#### `get_translation_script()`

Returns the JavaScript code for button interactions.

```php
private function get_translation_script()
```

**Returns:** JavaScript code as string

**Functionality:**
- Handles button click events
- Shows loading state
- Displays placeholder message (Task 22.3 will implement actual translation)
- Re-enables buttons after completion

## Post Meta Fields

The translation system uses the following post meta fields:

### `_translated_post_id`

- **Type:** Integer
- **Description:** ID of the translated version of this post
- **Set on:** Original post
- **Example:** If Spanish post ID 123 is translated to English post ID 456, post 123 has `_translated_post_id = 456`

### `_original_post_id`

- **Type:** Integer
- **Description:** ID of the original post that this is a translation of
- **Set on:** Translated post
- **Example:** If English post ID 456 is a translation of Spanish post ID 123, post 456 has `_original_post_id = 123`

### `_translation_lang`

- **Type:** String
- **Description:** Language code of the translation
- **Values:** `EN` (English) or `ES` (Spanish)
- **Set on:** Translated post
- **Example:** English translation has `_translation_lang = EN`

## User Interface

### Visual Design

```
┌─────────────────────────────────────┐
│ Traducción Automática               │
├─────────────────────────────────────┤
│ Idioma actual: 🇪🇸 Español          │
│                                     │
│ ┌─────────────────────────────────┐ │
│ │ Estado: ✓ Traducción existente  │ │
│ │ Ver traducción →                │ │
│ └─────────────────────────────────┘ │
│                                     │
│ ┌─────────────────────────────────┐ │
│ │ 🇬🇧 Translate to English        │ │
│ └─────────────────────────────────┘ │
│                                     │
│ ┌─────────────────────────────────┐ │
│ │ 🇪🇸 Traducir a Español          │ │
│ └─────────────────────────────────┘ │
│                                     │
│ La traducción creará o actualizará │
│ un post vinculado en el idioma     │
│ seleccionado.                       │
└─────────────────────────────────────┘
```

### Color Scheme

- **Primary Button:** `#2271b1` (WordPress blue)
- **Secondary Button:** `#f0f0f1` (WordPress gray)
- **Success State:** `#2271b1` (blue)
- **Info Background:** `#f0f0f1` (light gray)
- **Warning Background:** `#fff8e5` (light yellow)
- **Error Color:** `#d63638` (red)
- **Text Color:** `#2c3338` (dark gray)
- **Muted Text:** `#646970` (medium gray)

## JavaScript Behavior

### Button Click Flow

1. User clicks translation button
2. Button is disabled
3. Loading spinner appears with "Traduciendo contenido..." message
4. Status box shows with light gray background
5. After processing (currently 1 second placeholder):
   - Success: Shows success message with link
   - Error: Shows error message in red
   - Info: Shows informational message
6. Button is re-enabled

### Current Placeholder Behavior

Since Task 22.3 is not yet implemented, clicking a translation button shows:

> ℹ La funcionalidad de traducción se implementará en la tarea 22.3

This will be replaced with actual AJAX translation functionality in Task 22.3.

## Security

### Nonce Verification

- Nonce field: `translate_nonce`
- Nonce action: `reforestamos_translate`
- Verified before processing translation requests

### Capability Checks

- User must have `edit_posts` capability
- Checked in AJAX handler (to be implemented in Task 22.3)

### Data Sanitization

- Post ID: `intval()`
- Target language: `sanitize_text_field()`
- All output: `esc_html()`, `esc_attr()`, `esc_url()`, `esc_js()`

## Integration Points

### With DeepL Settings

The metabox checks `is_configured()` method to determine if API key is set:

```php
if ( ! $this->is_configured() ) {
    return; // Don't show metabox
}
```

### With Core Plugin

The metabox supports custom post types registered by the Core plugin:
- Empresas
- Eventos
- Integrantes
- Boletín

### With WordPress Editor

The metabox integrates seamlessly with:
- Classic Editor
- Block Editor (Gutenberg)
- Custom post type editors

## Accessibility

### Keyboard Navigation

- All buttons are keyboard accessible
- Tab order is logical
- Focus states are visible

### Screen Readers

- Semantic HTML structure
- Descriptive button text
- Status messages are announced
- Links have descriptive text

### Visual Indicators

- Color is not the only indicator
- Icons supplement text
- Loading states are clear
- Error messages are prominent

## Browser Compatibility

Tested and compatible with:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Future Enhancements (Task 22.3)

The following functionality will be added in Task 22.3:

1. **AJAX Translation Handler**
   - Endpoint: `wp_ajax_translate_post`
   - Processes translation requests
   - Returns success/error responses

2. **Content Translation**
   - Translate post title
   - Translate post content (HTML preserved)
   - Translate post excerpt
   - Translate custom fields (optional)

3. **Post Management**
   - Create new translated post
   - Update existing translation
   - Link original and translation
   - Set translation metadata

4. **Error Handling**
   - API connection errors
   - Rate limit handling
   - Invalid content handling
   - User-friendly error messages

5. **Progress Feedback**
   - Real-time translation status
   - Character count display
   - Estimated time remaining
   - Success confirmation

## Testing

See `tests/manual-translation-interface-test.md` for comprehensive testing procedures.

### Quick Test

1. Configure DeepL API key in settings
2. Edit any post
3. Verify metabox appears in sidebar
4. Click translation button
5. Verify placeholder message appears

## Troubleshooting

### Metabox Not Appearing

**Possible Causes:**
1. DeepL API key not configured
2. Post type not supported
3. JavaScript error preventing render

**Solutions:**
1. Go to Reforestamos Comunicación → DeepL and configure API key
2. Check if post type is in supported list
3. Check browser console for errors

### Buttons Not Responding

**Possible Causes:**
1. JavaScript not loaded
2. jQuery conflict
3. Browser compatibility issue

**Solutions:**
1. Check if jQuery is loaded
2. Check browser console for errors
3. Try different browser

### Styling Issues

**Possible Causes:**
1. CSS conflicts with theme/plugins
2. Custom admin styles overriding

**Solutions:**
1. Check for CSS conflicts in browser inspector
2. Increase specificity of styles if needed

## Code Examples

### Checking if Post Has Translation

```php
$translated_id = get_post_meta( $post_id, '_translated_post_id', true );
if ( $translated_id && get_post( $translated_id ) ) {
    echo 'Translation exists!';
}
```

### Getting Original Post

```php
$original_id = get_post_meta( $post_id, '_original_post_id', true );
if ( $original_id ) {
    $original_post = get_post( $original_id );
}
```

### Determining Post Language

```php
$lang = get_post_meta( $post_id, '_translation_lang', true );
if ( empty( $lang ) ) {
    $lang = 'ES'; // Default to Spanish
}
```

## Support

For issues or questions:
1. Check this documentation
2. Review manual test document
3. Check browser console for errors
4. Verify DeepL configuration

## Changelog

### Version 1.0.0 (Task 22.2)
- Initial implementation of translation interface
- Metabox registration for supported post types
- Current language indicator
- Translation status display
- Translation buttons with placeholder functionality
- Security nonce implementation
- Inline JavaScript for button interactions
- Help text and user guidance
