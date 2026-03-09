# Multilingual Implementation Documentation

## Overview

This document describes the complete multilingual (i18n) implementation for the Reforestamos Block Theme. The system supports Spanish (default) and English languages with automatic translation integration via DeepL API.

## Architecture

### Components

1. **Language Persistence** (`inc/language-persistence.php`)
   - Manages language storage across sessions
   - Uses cookies, sessions, and user meta
   - Detects browser language preferences
   - Priority: URL param → Cookie → Session → User Meta → Browser → Default (Spanish)

2. **i18n Functions** (`inc/i18n-functions.php`)
   - Language switcher UI generation
   - Language switching handlers
   - Translation link helpers
   - AJAX endpoints for language switching

3. **Translation Links** (`inc/translation-links.php`)
   - Links between translated content versions
   - Admin notices for translations
   - Shortcodes for translation links
   - Translation statistics

4. **Header Language Switcher** (`blocks/header-navbar/`)
   - Integrated language switcher in navigation
   - Bootstrap 5 styled buttons
   - Mobile-responsive design

## Features Implemented

### ✅ Requirement 17.1: Spanish and English Support
- Default language: Spanish (es-MX)
- Secondary language: English (en-US)
- Automatic locale switching

### ✅ Requirement 17.2: Language Switcher in Header
- Integrated in header-navbar block
- ES/EN buttons with active state
- Optional flag icons
- Mobile-responsive

### ✅ Requirement 17.3: Language Persistence
- Cookie storage (1 year expiration)
- Session storage (per visit)
- User meta storage (logged-in users)
- Browser language detection

### ✅ Requirement 17.4: WordPress i18n Functions
- All strings wrapped in `__()`
- Text domain: `reforestamos`
- POT file generated
- Translation-ready

### ✅ Requirement 17.5: POT Files
- Location: `languages/reforestamos.pot`
- Includes all translatable strings
- Ready for translation tools

### ✅ Requirement 17.7: Translation Links
- Automatic links to translated versions
- Displayed after post content
- Shortcode support: `[translation_link]`
- Admin bar integration

### ✅ Requirement 17.8: Language Persistence in Cookie/Session
- Secure HTTP-only cookies
- Session fallback
- User preference storage

### ✅ Requirement 17.9: i18n in All Blocks
- All custom blocks use `__()` functions
- Block editor strings translatable
- Frontend strings translatable

## Usage

### For Developers

#### Get Current Language
```php
$current_lang = reforestamos_get_current_language();
// Returns: 'es' or 'en'
```

#### Set Language
```php
reforestamos_set_language('en');
// Sets language to English
```

#### Display Language Switcher
```php
// In template
reforestamos_language_switcher();

// With custom args
reforestamos_language_switcher(array(
    'show_flags' => true,
    'show_text' => true,
    'class' => 'my-custom-class',
));
```

#### Display Translation Link
```php
// In template
reforestamos_the_translation_link();

// With custom args
reforestamos_the_translation_link(null, array(
    'class' => 'btn btn-primary',
    'show_icon' => true,
));
```

#### Get Translated Post ID
```php
$translated_id = reforestamos_get_translated_post_id($post_id, 'en');
if ($translated_id) {
    $translated_url = get_permalink($translated_id);
}
```

### For Content Editors

#### Creating Translations

1. **Using DeepL Integration** (Recommended)
   - Edit the post you want to translate
   - Look for "Traducción Automática" metabox in sidebar
   - Click "Translate to English" or "Traducir a Español"
   - System creates/updates translated version automatically
   - Links are created between original and translation

2. **Manual Translation**
   - Create a new post in target language
   - Use post meta to link posts:
     - `_translated_post_id`: ID of translated version
     - `_original_post_id`: ID of original version
     - `_translation_lang`: Language code (EN or ES)

#### Translation Metabox Features

- Shows current language
- Displays translation status
- Quick links to translated versions
- One-click translation buttons
- Queue system for rate-limited translations

### For End Users

#### Switching Languages

1. **Using Header Switcher**
   - Click ES or EN button in navigation
   - Language preference is saved
   - Page reloads in selected language

2. **Using Translation Links**
   - Look for translation box after content
   - Click "View in English" or "Ver en Español"
   - Navigates to translated version

3. **Direct URL**
   - Add `?lang=en` or `?lang=es` to any URL
   - Language is set and parameter removed

## Shortcodes

### [translation_link]

Display a link to the translated version of current post.

**Basic Usage:**
```
[translation_link]
```

**With Attributes:**
```
[translation_link lang="en" text="Read in English" class="btn btn-primary" show_icon="yes"]
```

**Attributes:**
- `post_id`: Post ID (default: current post)
- `lang`: Target language (default: opposite of current)
- `text`: Link text (default: auto-generated)
- `class`: CSS classes (default: `btn btn-outline-primary`)
- `show_icon`: Show globe icon (default: `yes`)

## File Structure

```
reforestamos-block-theme/
├── inc/
│   ├── language-persistence.php    # Language storage & retrieval
│   ├── i18n-functions.php          # i18n helper functions
│   └── translation-links.php       # Translation link system
├── src/
│   ├── js/
│   │   └── language-switcher.js    # Frontend JS for switcher
│   └── scss/
│       └── components/
│           └── _language-switcher.scss  # Switcher styles
├── languages/
│   └── reforestamos.pot            # Translation template
└── blocks/
    └── header-navbar/
        ├── edit.js                 # Editor with switcher
        ├── save.js                 # Frontend with switcher
        └── render.php              # Server-side rendering
```

## Integration with DeepL

The theme integrates with the DeepL translation system provided by the `reforestamos-comunicacion` plugin.

### Translation Workflow

1. **Manual Translation Request**
   - User clicks translate button in metabox
   - AJAX request sent to DeepL integration
   - Content translated via DeepL API
   - New post created/updated with translation
   - Post meta links established

2. **Automatic Queue System**
   - If API rate limit reached, translation queued
   - Cron job processes queue hourly
   - User notified when translation complete

3. **Post Linking**
   - Original post: `_translated_post_id` meta
   - Translated post: `_original_post_id` meta
   - Translated post: `_translation_lang` meta (EN or ES)

### Custom Fields Translation

The DeepL integration automatically translates:
- Post title
- Post content (with HTML preservation)
- Post excerpt
- Custom fields (configurable)

## Styling

### Language Switcher Styles

The language switcher is fully styled with Bootstrap 5 and custom SCSS:

- Active state highlighting
- Hover effects
- Mobile-responsive
- Dark/light navbar support
- Accessible (ARIA labels)

### Translation Link Styles

Translation links appear as attractive call-to-action boxes:

- Gradient background
- Icon + text layout
- Hover animations
- Mobile-responsive
- Customizable via CSS classes

## Accessibility

### ARIA Labels
- Language buttons have descriptive labels
- Current language indicated with `aria-current`
- Screen reader friendly

### Keyboard Navigation
- All interactive elements keyboard accessible
- Focus states clearly visible
- Tab order logical

### Semantic HTML
- Proper heading hierarchy
- Meaningful link text
- Language attributes on HTML element

## Performance

### Optimizations
- Language stored in cookie (no DB query)
- Session fallback for cookie-disabled browsers
- Minimal JavaScript (jQuery-based)
- CSS compiled and minified
- Lazy loading of translation links

### Caching Considerations
- Language preference cached per user
- Translation links cached with post
- No impact on page load time

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ (with polyfills)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Testing

### Manual Testing Checklist

- [ ] Language switcher appears in header
- [ ] Clicking ES/EN switches language
- [ ] Language persists across page loads
- [ ] Translation links appear on translated posts
- [ ] Shortcode works correctly
- [ ] Admin notices show for translations
- [ ] Mobile responsive design works
- [ ] Keyboard navigation works
- [ ] Screen reader announces language changes

### Test Scenarios

1. **First Visit**
   - User visits site
   - Browser language detected
   - Appropriate language displayed

2. **Language Switch**
   - User clicks language button
   - Page reloads in new language
   - Cookie set with 1-year expiration

3. **Logged-in User**
   - User logs in
   - Language preference loaded from user meta
   - Preference synced across devices

4. **Translation Navigation**
   - User views post with translation
   - Translation link displayed
   - Clicking link navigates to translation
   - Language automatically switched

## Troubleshooting

### Language Not Persisting

**Problem:** Language resets on page reload

**Solutions:**
1. Check if cookies are enabled in browser
2. Verify COOKIEPATH and COOKIE_DOMAIN constants
3. Check for conflicting plugins
4. Clear browser cache

### Translation Links Not Showing

**Problem:** Translation links don't appear

**Solutions:**
1. Verify post has `_translated_post_id` meta
2. Check if translated post exists and is published
3. Ensure `reforestamos_get_translation_link()` returns URL
4. Check content filter priority

### Language Switcher Not Visible

**Problem:** Switcher doesn't appear in header

**Solutions:**
1. Verify header-navbar block is used
2. Check `showLanguageSwitcher` attribute is true
3. Rebuild theme assets (`npm run build`)
4. Clear WordPress cache

### DeepL Translation Fails

**Problem:** Translation button doesn't work

**Solutions:**
1. Verify DeepL API key is configured
2. Check API usage limits
3. Review translation queue status
4. Check error logs in DeepL settings

## Future Enhancements

### Planned Features
- [ ] Additional language support (French, Portuguese)
- [ ] RTL language support (Arabic, Hebrew)
- [ ] Translation memory system
- [ ] Glossary management
- [ ] Bulk translation interface
- [ ] Translation quality scoring
- [ ] SEO hreflang tags
- [ ] Language-specific menus
- [ ] Language-specific widgets

### Integration Opportunities
- WPML compatibility layer
- Polylang integration
- TranslatePress support
- Google Translate fallback
- Microsoft Translator integration

## Support

For issues or questions:
- Check this documentation first
- Review code comments in source files
- Test with WordPress debug mode enabled
- Contact development team

## Changelog

### Version 1.0.0 (2024-01-01)
- Initial multilingual implementation
- Spanish and English support
- Language switcher in header
- Translation links system
- DeepL integration
- Cookie/session persistence
- User preference storage
- Admin interface enhancements
- Comprehensive documentation

## Credits

- **Development:** Reforestamos México Development Team
- **Translation API:** DeepL
- **Framework:** WordPress Block Theme
- **UI Framework:** Bootstrap 5
- **Icons:** Unicode Emoji

## License

This multilingual system is part of the Reforestamos Block Theme and is licensed under the same terms as the theme.
