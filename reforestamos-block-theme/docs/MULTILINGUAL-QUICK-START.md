# Multilingual Quick Start Guide

## For Content Editors

### How to Translate a Post

1. **Open the post** you want to translate in the WordPress editor
2. **Look for the "Traducción Automática" box** in the right sidebar
3. **Click the translate button**:
   - 🇬🇧 "Translate to English" - for English translation
   - 🇪🇸 "Traducir a Español" - for Spanish translation
4. **Wait for the translation** to complete (usually 5-10 seconds)
5. **Review the translated post** by clicking the link provided
6. **Edit if needed** - automatic translations may need minor adjustments

### How to Add Language Switcher to Header

The language switcher is automatically included in the header-navbar block.

**To enable/disable:**
1. Edit the page with the header
2. Select the header-navbar block
3. In the right sidebar, find "Features"
4. Toggle "Show Language Switcher"

### How to Add Translation Links to Content

Translation links are automatically added to posts that have translations.

**To manually add a link:**
Use the shortcode: `[translation_link]`

**Custom text:**
`[translation_link text="Read in English"]`

## For Developers

### Quick Setup

1. **Include the files** (already done in functions.php):
```php
require_once REFORESTAMOS_THEME_DIR . '/inc/language-persistence.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/i18n-functions.php';
require_once REFORESTAMOS_THEME_DIR . '/inc/translation-links.php';
```

2. **Build assets**:
```bash
npm run build
```

3. **Configure DeepL** (optional, for automatic translation):
   - Go to WordPress Admin → Reforestamos Comunicación → DeepL
   - Enter your DeepL API key
   - Test the connection

### Common Code Snippets

**Get current language:**
```php
$lang = reforestamos_get_current_language(); // 'es' or 'en'
```

**Display language switcher:**
```php
<?php reforestamos_language_switcher(); ?>
```

**Display translation link:**
```php
<?php reforestamos_the_translation_link(); ?>
```

**Check if post has translation:**
```php
$translated_id = reforestamos_get_translated_post_id($post_id, 'en');
if ($translated_id) {
    echo 'Translation exists!';
}
```

**Conditional content by language:**
```php
<?php if (reforestamos_get_current_language() === 'es') : ?>
    <p>Contenido en español</p>
<?php else : ?>
    <p>Content in English</p>
<?php endif; ?>
```

## For End Users

### How to Change Language

**Method 1: Header Switcher**
- Look for ES | EN buttons in the top navigation
- Click your preferred language
- The page will reload in that language

**Method 2: Translation Links**
- Scroll to the bottom of an article
- Look for the translation box
- Click "View in English" or "Ver en Español"

**Method 3: URL Parameter**
- Add `?lang=en` to any URL for English
- Add `?lang=es` to any URL for Spanish

### Language Persistence

Your language choice is remembered:
- ✅ Across page visits (cookie, 1 year)
- ✅ In your browser session
- ✅ In your user account (if logged in)
- ✅ On all devices (if logged in)

## Troubleshooting

### Language doesn't change
- Clear your browser cache
- Check if cookies are enabled
- Try a different browser

### Translation link doesn't appear
- The post may not have a translation yet
- Ask an editor to create the translation

### Automatic translation fails
- Check with site administrator
- May be API rate limit (translations queued)
- Check DeepL API configuration

## Support

Need help? Contact: [support@reforestamos.org](mailto:support@reforestamos.org)
