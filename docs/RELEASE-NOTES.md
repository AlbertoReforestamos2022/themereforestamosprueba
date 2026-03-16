# Release Notes — Reforestamos México v2.0.0

## Block Theme Modernization

**Release Date:** [YYYY-MM-DD]
**Version:** 2.0.0

---

## What's New

### Modern Block Theme
The Reforestamos México website has been completely modernized from a traditional PHP theme to a WordPress Block Theme. This brings Full Site Editing capabilities, a modern content editing experience, and improved performance.

### Key Changes

#### For Content Editors
- The site now uses the WordPress Block Editor (Gutenberg) for all content editing
- 16+ custom blocks are available in the editor under the "Reforestamos" category
- Block patterns provide pre-built layouts for common page sections (hero, testimonials, team members, contact)
- Templates can be customized directly in the Site Editor (Appearance → Editor)
- Language switching between Spanish and English is available in the header

#### Custom Blocks Available
| Block | Description |
|-------|-------------|
| Hero | Full-width hero section with background image |
| Carousel | Image carousel with autoplay |
| Cards Enlaces | Grid of link cards |
| Cards Iniciativas | Initiative showcase cards |
| FAQs | Accordion-style FAQ section |
| Timeline | Vertical timeline for events/history |
| Documents | Downloadable document list |
| Galería Tabs | Tabbed image gallery with lightbox |
| Logos Aliados | Partner logo grid |
| Sobre Nosotros | About section with team and stats |
| Texto Imagen | Two-column text and image layout |
| List | Custom icon list |
| Eventos Próximos | Upcoming events from the Events CPT |
| Contacto | Integrated contact form |
| Header Navbar | Site navigation with language switcher |
| Footer | Multi-column footer |

#### For Administrators
- Plugins are now modular and can be activated/deactivated independently
- New monitoring dashboard under Settings → Monitoring
- Health check endpoint: `/wp-json/reforestamos/v1/health`
- Improved admin interface with better search, filters, and bulk actions

#### Plugin Architecture
The site functionality is now organized into 4 independent plugins:

| Plugin | Functionality |
|--------|--------------|
| Reforestamos Core | Custom Post Types (Empresas, Eventos, Integrantes, Boletín), taxonomies, custom fields, REST API |
| Reforestamos Micrositios | Árboles y Ciudades interactive map, Red OJA directory and map |
| Reforestamos Comunicación | Newsletter system, contact forms, chatbot, DeepL translation integration |
| Reforestamos Empresas | Company profiles, logo grid, click analytics, photo galleries |

---

## Migration Notes

- All existing content (posts, pages, media, custom post types) has been migrated
- URLs and permalinks are preserved — no broken links
- Custom fields and metadata are maintained
- Shortcodes from the legacy theme have been converted to blocks where possible
- Any shortcodes that could not be auto-converted are logged in the migration report

---

## Performance Improvements

- Lazy loading for all images below the fold
- Minified and concatenated CSS/JavaScript
- Bootstrap 5 loaded from CDN
- WebP image format support with fallbacks
- Critical CSS inlining for above-the-fold content
- Deferred non-critical JavaScript loading

---

## Security Enhancements

- All form inputs sanitized with WordPress sanitization functions
- All outputs escaped with WordPress escaping functions
- Nonce verification on all forms and AJAX requests
- Rate limiting on contact forms
- Prepared statements for all database queries
- File upload validation (type and size)

---

## Known Issues

- [List any known issues or limitations here]
- DeepL translation requires a valid API key to be configured in Settings
- Sentry error tracking is optional and requires DSN configuration

---

## Support

For questions or issues after the launch:

- **Technical Issues:** [developer-email@reforestamos.org]
- **Content Questions:** [content-team@reforestamos.org]
- **Documentation:** See the [User Guide for Content](../reforestamos-block-theme/docs/USER-GUIDE-CONTENT.md) and [User Guide for Plugins](../reforestamos-block-theme/docs/USER-GUIDE-PLUGINS.md)

---

## Rollback Plan

If critical issues are discovered after launch, the site can be rolled back to the previous version:

```bash
bash deploy/rollback.sh --env production
```

See [ROLLBACK.md](../deploy/ROLLBACK.md) for detailed rollback procedures.

---

*Reforestamos México — Modernización del Tema WordPress*
