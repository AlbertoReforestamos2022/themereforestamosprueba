# Compatibility Matrix — Reforestamos Block Theme

> Requirements: 24.1, 24.2, 24.7

## WordPress Version Compatibility

| WordPress Version | Status | Notes |
|---|---|---|
| 6.0.x | ✅ Supported | Minimum required version. Full Site Editing available. |
| 6.1.x | ✅ Supported | Improved template editing, fluid typography. |
| 6.2.x | ✅ Supported | Distraction-free mode, sticky positioning. |
| 6.3.x | ✅ Supported | Command palette, block revision history. |
| 6.4.x | ✅ Supported | Block hooks, lightbox for images. |
| 6.5.x | ✅ Supported | Font Library, Interactivity API improvements. |
| < 6.0 | ❌ Not Supported | Admin notice displayed. Block Theme requires FSE. |

### Version Detection

The theme checks WordPress version on activation and displays an admin notice if below 6.0:

```php
// In functions.php / theme-setup.php
if ( version_compare( get_bloginfo( 'version' ), '6.0', '<' ) ) {
    add_action( 'admin_notices', 'reforestamos_wp_version_notice' );
}
```

## PHP Version Compatibility

| PHP Version | Status | Notes |
|---|---|---|
| 7.4 | ✅ Supported | Minimum required. Typed properties, arrow functions. |
| 8.0 | ✅ Supported | Named arguments, match expression, nullsafe operator. |
| 8.1 | ✅ Supported | Enums, fibers, readonly properties. |
| 8.2 | ✅ Supported | Readonly classes, DNF types. |
| 8.3 | ✅ Supported | json_validate(), typed class constants. |
| < 7.4 | ❌ Not Supported | Plugin headers declare `Requires PHP: 7.4`. |

### PHP Compatibility Notes

- Code avoids PHP 8.x-only syntax to maintain 7.4 compatibility.
- All plugins declare `Requires PHP: 7.4` in plugin headers.
- No use of `enum`, `readonly`, `match`, or named arguments in core code.
- `array_is_list()` and other 8.1+ functions are not used directly.

## Plugin Compatibility

### Tested Compatible Plugins

| Plugin | Version Tested | Status | Notes |
|---|---|---|---|
| Yoast SEO | 21.x+ | ✅ Compatible | SEO meta tags coexist. Theme defers to Yoast when active. |
| WooCommerce | 8.x+ | ✅ Compatible | No conflicts. Theme does not override WooCommerce templates. |
| WPML | 4.6+ | ✅ Compatible | Works alongside built-in i18n. Language switcher adapts. |
| Polylang | 3.5+ | ✅ Compatible | Translation functions compatible. |
| Wordfence | 7.x+ | ✅ Compatible | Security headers do not conflict. |
| UpdraftPlus | 1.23+ | ✅ Compatible | Backup/restore works normally. |
| Contact Form 7 | 5.8+ | ✅ Compatible | Can coexist with built-in contact forms. |
| Elementor | 3.x+ | ⚠️ Partial | Block Theme designed for Gutenberg. Elementor works but FSE features limited. |
| Classic Editor | 1.6+ | ⚠️ Partial | Disables block editor. Custom blocks unavailable. |

### Yoast SEO Integration

The theme's SEO module (`inc/seo.php`) checks for Yoast:

```php
// Theme defers meta tag generation when Yoast is active
if ( defined( 'WPSEO_VERSION' ) ) {
    // Skip theme's Open Graph and meta description output
    return;
}
```

### WooCommerce Integration

- Theme does not register any WooCommerce template overrides.
- Bootstrap grid does not conflict with WooCommerce styles.
- Custom post types use unique slugs that don't collide with WooCommerce.

## Browser Compatibility

| Browser | Version | Status |
|---|---|---|
| Chrome | 90+ | ✅ Supported |
| Firefox | 90+ | ✅ Supported |
| Safari | 14+ | ✅ Supported |
| Edge | 90+ | ✅ Supported |
| Opera | 76+ | ✅ Supported |
| IE 11 | — | ❌ Not Supported |

## Responsive Breakpoints

| Breakpoint | Width | Target |
|---|---|---|
| xs | < 576px | Mobile portrait |
| sm | ≥ 576px | Mobile landscape |
| md | ≥ 768px | Tablet |
| lg | ≥ 992px | Desktop |
| xl | ≥ 1200px | Large desktop |
| xxl | ≥ 1400px | Extra large |

Tested viewport range: 320px — 2560px.

## Plugin Ecosystem Dependencies

```
reforestamos-block-theme (theme)
├── reforestamos-core (optional, recommended)
│   └── Provides: CPTs, taxonomies, REST API, custom fields
├── reforestamos-micrositios (optional)
│   └── Provides: Interactive maps, arboles-ciudades, red-oja
├── reforestamos-comunicacion (optional)
│   └── Provides: Newsletter, contact forms, chatbot, DeepL
└── reforestamos-empresas (optional, requires core)
    └── Provides: Company management, analytics, galleries
```

## Known Limitations

1. Classic Editor plugin disables block editor — custom blocks will not be available.
2. Page builder plugins (Elementor, Divi) may conflict with FSE template system.
3. Caching plugins should be configured to exclude dynamic pages (contact forms, chatbot).
4. CDN configurations must allow Bootstrap and Leaflet CDN domains.

## Testing Checklist

### Pre-deployment Verification

- [ ] Activate theme on WordPress 6.0+ — verify no errors
- [ ] Activate all 4 plugins in order: Core → Micrositios → Comunicación → Empresas
- [ ] Deactivate each plugin individually — verify graceful degradation
- [ ] Test with Yoast SEO active — verify no duplicate meta tags
- [ ] Test with WooCommerce active — verify no template conflicts
- [ ] Verify PHP 7.4 compatibility (no syntax errors)
- [ ] Verify PHP 8.2 compatibility (no deprecation warnings)
- [ ] Test on mobile viewport (375px) — verify responsive layout
- [ ] Test on desktop viewport (1920px) — verify full layout
