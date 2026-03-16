# Reforestamos México — Block Theme

Modern WordPress Block Theme for Reforestamos México with Full Site Editing, 16 custom Gutenberg blocks, and a modular plugin architecture.

## Table of Contents

- [Overview](#overview)
- [Architecture](#architecture)
- [Requirements](#requirements)
- [Installation](#installation)
- [Development](#development)
- [Directory Structure](#directory-structure)
- [Custom Blocks](#custom-blocks)
- [Templates & Parts](#templates--parts)
- [Block Patterns](#block-patterns)
- [Theme Configuration](#theme-configuration)
- [Included Modules](#included-modules)
- [Plugin Ecosystem](#plugin-ecosystem)
- [Internationalization](#internationalization)
- [Security](#security)
- [Performance](#performance)
- [Testing](#testing)
- [Deployment](#deployment)
- [License](#license)

## Overview

This theme is the frontend layer of the Reforestamos México website. It replaces the legacy PHP-based theme with a modern Block Theme that leverages WordPress Full Site Editing (FSE). All content types (CPTs, taxonomies, fields) are managed by companion plugins, keeping the theme focused on presentation.

Key highlights:
- **16 custom Gutenberg blocks** for all site-specific UI components
- **HTML-based templates** — no PHP template files
- **theme.json** centralizes colors, typography, spacing, and layout
- **Bootstrap 5** loaded from CDN for responsive grid and utilities
- **@wordpress/scripts** build pipeline (webpack) for SCSS and ES6+
- **Bilingual** — Spanish (es_MX) and English (en_US) with `.pot` file

## Architecture

```
┌─────────────────────────────────────────────────────┐
│                   WordPress Core                     │
├─────────────────────────────────────────────────────┤
│              Reforestamos Block Theme                │
│  ┌───────────┐ ┌───────────┐ ┌───────────────────┐  │
│  │ Templates  │ │  Blocks   │ │  theme.json       │  │
│  │ (HTML)     │ │ (16 custom│ │  (colors, fonts,  │  │
│  │            │ │  blocks)  │ │   layout)         │  │
│  └───────────┘ └───────────┘ └───────────────────┘  │
├─────────────────────────────────────────────────────┤
│                    Plugins                            │
│  ┌──────────┐ ┌────────────┐ ┌──────────────────┐   │
│  │  Core    │ │ Micrositios│ │  Comunicación     │   │
│  │  (CPTs,  │ │ (Maps,     │ │  (Newsletter,     │   │
│  │  Fields, │ │  Leaflet)  │ │   Forms, Chatbot, │   │
│  │  REST)   │ │            │ │   DeepL)          │   │
│  └──────────┘ └────────────┘ └──────────────────┘   │
│  ┌──────────┐ ┌────────────┐                         │
│  │ Empresas │ │ Migration  │                         │
│  │(Companies│ │  System    │                         │
│  │Analytics)│ │            │                         │
│  └──────────┘ └────────────┘                         │
└─────────────────────────────────────────────────────┘
```

### Design Principles

1. **Separation of concerns** — Theme handles presentation only; data lives in plugins.
2. **Plugin independence** — Each plugin can be activated/deactivated independently (except Empresas, which requires Core).
3. **Standards-first** — WordPress Coding Standards, Block API v2, REST API.
4. **Performance** — Lazy loading, critical CSS, deferred JS, WebP images.
5. **Accessibility** — ARIA labels, keyboard navigation, skip-to-content, 4.5:1 contrast.

## Requirements

| Dependency | Minimum Version |
|------------|----------------|
| WordPress  | 6.0            |
| PHP        | 7.4            |
| Node.js    | 16.x           |
| npm        | 8.x            |

## Installation

```bash
# 1. Clone into wp-content/themes/
git clone <repo-url> wp-content/themes/reforestamos-block-theme

# 2. Install Node dependencies
cd wp-content/themes/reforestamos-block-theme
npm install

# 3. Build production assets
npm run build

# 4. Activate the theme in WordPress Admin → Appearance → Themes
```

### Plugin Setup (recommended order)

1. **Reforestamos Core** — Activate first (provides CPTs and taxonomies)
2. **Reforestamos Micrositios** — Independent
3. **Reforestamos Comunicación** — Independent
4. **Reforestamos Empresas** — Requires Core plugin

## Development

### Available Commands

| Command | Description |
|---------|-------------|
| `npm run start` | Development mode with file watching and hot reload |
| `npm run build` | Production build (minified, optimized) |
| `npm run format` | Auto-format code with Prettier |
| `npm run lint:css` | Lint SCSS/CSS files |
| `npm run lint:js` | Lint JavaScript files |
| `npm test` | Run Jest test suite |

### Build Pipeline

The theme uses `@wordpress/scripts` (webpack-based):

- **SCSS** → `src/scss/` compiled to `build/style.css`
- **JavaScript ES6+** → `src/js/` compiled to `build/index.js`
- **Block assets** → Each `blocks/<name>/` compiled to `build/blocks/<name>/`
- Source maps generated in development mode
- CSS and JS minified in production mode

## Directory Structure

```
reforestamos-block-theme/
├── blocks/                     # 16 custom Gutenberg blocks
│   ├── hero/                   # Hero section with background image
│   ├── carousel/               # Image carousel (Bootstrap 5)
│   ├── contacto/               # Contact form block
│   ├── documents/              # Downloadable documents list
│   ├── faqs/                   # FAQ accordion (Bootstrap)
│   ├── galeria-tabs/           # Tabbed image gallery with lightbox
│   ├── logos-aliados/          # Partner logos grid
│   ├── timeline/               # Vertical timeline
│   ├── cards-enlaces/          # Link cards grid
│   ├── cards-iniciativas/      # Initiative cards
│   ├── texto-imagen/           # Text + image two-column layout
│   ├── list/                   # Custom list with icons
│   ├── sobre-nosotros/         # About us section
│   ├── header-navbar/          # Main navigation bar
│   ├── footer/                 # Site footer
│   └── eventos-proximos/       # Upcoming events (REST API)
│
├── templates/                  # Full-page HTML templates
│   ├── index.html              # Default/fallback template
│   ├── front-page.html         # Homepage
│   ├── single.html             # Single post
│   ├── page.html               # Static page
│   ├── archive.html            # Archive listing
│   ├── search.html             # Search results
│   ├── single-empresas.html    # Company profile
│   ├── single-eventos.html     # Single event
│   ├── archive-eventos.html    # Events archive
│   └── page-calendar.html      # Calendar page
│
├── parts/                      # Reusable template parts
│   ├── header.html             # Site header
│   ├── footer.html             # Site footer
│   ├── footer-custom.html      # Alternative footer
│   └── navigation.html         # Navigation menu
│
├── patterns/                   # Block patterns
│   ├── hero-section.php
│   ├── call-to-action.php
│   ├── team-members.php
│   ├── testimonials.php
│   ├── contact-section.php
│   ├── footer-complete.php
│   ├── content-image-text.php
│   ├── page-header.php
│   └── statistics.php
│
├── src/                        # Source files (pre-compilation)
│   ├── scss/                   # SCSS stylesheets
│   │   ├── main.scss
│   │   ├── _variables.scss
│   │   ├── _mixins.scss
│   │   ├── _responsive.scss
│   │   ├── _accessibility.scss
│   │   └── components/         # Component-specific styles
│   ├── js/                     # JavaScript modules
│   │   └── responsive-navigation.js
│   └── index.js                # Main JS entry point
│
├── build/                      # Compiled assets (git-ignored)
│
├── inc/                        # PHP includes
│   ├── theme-setup.php         # Theme support and setup
│   ├── block-registration.php  # Block registration
│   ├── block-patterns.php      # Pattern registration
│   ├── pattern-manager.php     # Pattern management
│   ├── enqueue-assets.php      # Asset enqueueing
│   ├── security.php            # Security hardening
│   ├── performance.php         # Performance optimizations
│   ├── seo.php                 # SEO meta tags and schema
│   ├── breadcrumbs.php         # Breadcrumb navigation
│   ├── search.php              # Enhanced search
│   ├── media-management.php    # Media library enhancements
│   ├── i18n-functions.php      # Internationalization helpers
│   ├── language-persistence.php# Language cookie/session
│   ├── translation-links.php   # Translation link helpers
│   ├── skip-to-content.php     # Accessibility skip links
│   ├── events-calendar.php     # Events calendar functionality
│   ├── event-registration.php  # Event registration system
│   ├── events-archive.php      # Events archive handling
│   ├── ical-export.php         # iCal export for events
│   ├── analytics.php           # Site analytics
│   ├── cookie-consent.php      # GDPR cookie consent
│   ├── dashboard-widgets.php   # Admin dashboard widgets
│   ├── monthly-reports.php     # Monthly report generation
│   └── gdpr-compliance.php     # GDPR compliance tools
│
├── languages/                  # Translation files
│   └── reforestamos.pot        # Translation template
│
├── tests/                      # Test suites
│   ├── blocks/                 # Block-specific tests
│   ├── php/                    # PHP unit tests
│   └── setup.js                # Jest test setup
│
├── docs/                       # Additional documentation
│
├── functions.php               # Theme bootstrap
├── theme.json                  # Theme configuration (colors, fonts, layout)
├── style.css                   # Theme metadata header
├── package.json                # Node dependencies
├── webpack.config.js           # Webpack configuration
└── jest.config.js              # Jest test configuration
```

## Custom Blocks

All 16 blocks are registered under the `reforestamos` category. Each block lives in `blocks/<name>/` with:

| File | Purpose |
|------|---------|
| `block.json` | Block metadata, attributes, supports |
| `edit.js` | Editor interface (React component) |
| `save.js` | Frontend HTML output |
| `style.scss` | Block styles (editor + frontend) |
| `index.js` | Block registration entry point |
| `frontend.js` | Optional client-side interactivity |

See [docs/BLOCKS.md](docs/BLOCKS.md) for detailed block documentation.

## Templates & Parts

Templates use WordPress block markup (`<!-- wp:template-part -->`) and contain no PHP. They reference template parts from `parts/` and custom blocks from `blocks/`.

## Block Patterns

Pre-built page sections available in the block inserter under "Reforestamos" category. See [docs/BLOCK-PATTERNS-GUIDE.md](docs/BLOCK-PATTERNS-GUIDE.md).

## Theme Configuration

`theme.json` defines:

- **Color palette**: Verde Reforestamos (#2E7D32), Verde Claro (#66BB6A), Naranja Acento (#FFA726), Verde Oscuro (#1B5E20), Verde Muy Claro (#F1F8E9)
- **Typography**: Montserrat (headings), Open Sans (body)
- **Font sizes**: small (14px), medium (16px), large (20px), x-large (28px), xx-large (36px)
- **Layout**: Content width 1140px, wide width 1320px
- **Spacing**: px, em, rem, vh, vw, %

## Included Modules

| Module | File | Description |
|--------|------|-------------|
| Security | `inc/security.php` | Input sanitization, output escaping, nonce verification |
| Performance | `inc/performance.php` | Lazy loading, critical CSS, deferred JS, WebP |
| SEO | `inc/seo.php` | Open Graph, Twitter Cards, schema.org, sitemaps |
| i18n | `inc/i18n-functions.php` | Translation helpers, language switcher |
| Events | `inc/events-calendar.php` | Calendar, registration, iCal export |
| Analytics | `inc/analytics.php` | Site analytics tracking |
| GDPR | `inc/gdpr-compliance.php` | Cookie consent, data compliance |

## Plugin Ecosystem

| Plugin | Directory | Purpose | Dependencies |
|--------|-----------|---------|-------------|
| Reforestamos Core | `reforestamos-core/` | CPTs, taxonomies, custom fields, REST API | None |
| Reforestamos Micrositios | `reforestamos-micrositios/` | Interactive maps (Leaflet), Árboles y Ciudades, Red OJA | None |
| Reforestamos Comunicación | `reforestamos-comunicacion/` | Newsletter, contact forms, chatbot, DeepL translation | None |
| Reforestamos Empresas | `reforestamos-empresas/` | Company management, analytics, galleries | Core plugin |
| Migration System | `migration-system/` | WP-CLI data migration from legacy theme | None |

## Internationalization

- Text domain: `reforestamos`
- Languages: Spanish (es_MX), English (en_US)
- POT file: `languages/reforestamos.pot`
- All user-facing strings use `__()`, `_e()`, `esc_html__()`, etc.
- Language switcher in header with cookie persistence

## Security

- All inputs sanitized with `sanitize_text_field()`, `wp_kses_post()`, etc.
- All outputs escaped with `esc_html()`, `esc_attr()`, `esc_url()`, etc.
- Nonce verification on all forms
- Prepared statements for all database queries
- File upload validation (type and size)

## Performance

- Images lazy-loaded below the fold
- Non-critical JS deferred
- CSS/JS minified in production
- WebP image conversion with fallbacks
- `font-display: swap` for web fonts
- Critical CSS inlining for above-the-fold content
- Browser caching headers for static assets

## Testing

```bash
# JavaScript tests (Jest)
npm test

# PHP tests (PHPUnit) — requires WordPress test environment
composer test
```

## Deployment

1. Run `npm run build` to compile production assets
2. Upload theme directory to `wp-content/themes/`
3. Activate theme in WordPress Admin
4. Install and activate companion plugins in order (Core → others)

## License

GPL v2 or later — https://www.gnu.org/licenses/gpl-2.0.html

## Credits

Developed by Reforestamos México — https://reforestamos.org
