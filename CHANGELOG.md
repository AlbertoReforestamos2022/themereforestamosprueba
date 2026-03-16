# Changelog

All notable changes to the "Reforestamos México" project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2025-01-01

### Added

#### Block Theme (reforestamos-block-theme)
- Full Site Editing block theme with `theme.json` configuration
- 16 custom Gutenberg blocks: Hero, Carousel, Texto-Imagen, List, Cards-Enlaces, Cards-Iniciativas, FAQs, Timeline, Documents, Galería-Tabs, Logos-Aliados, Sobre-Nosotros, Eventos-Próximos, Header-Navbar, Footer, Contacto
- HTML templates for index, single, page, archive, front-page, and CPT-specific views
- Template parts for header, footer, and navigation
- Block patterns: hero section, contact section, team members, testimonials, footer
- SCSS-based styling with Bootstrap 5 integration
- Responsive design with mobile-first approach
- Accessibility features: ARIA labels, keyboard navigation, skip-to-content links
- Multilingual support (ES/EN) with language switcher and `.pot` file
- Performance optimizations: lazy loading, critical CSS, WebP conversion
- SEO: Open Graph, Twitter Cards, schema.org structured data, XML sitemaps
- Security: input sanitization, output escaping, nonce verification
- Media management with automatic image optimization
- Events calendar with registration system and iCal export
- Advanced search with filters
- Cookie consent and GDPR compliance
- Analytics dashboard widgets

#### Core Plugin (reforestamos-core)
- Custom Post Types: Empresas, Eventos, Integrantes, Boletín
- Custom taxonomies for each CPT
- Custom fields via CMB2 for all CPTs
- REST API endpoints with filtering and pagination
- Admin UI with custom menus, filters, and bulk actions
- Uninstall cleanup handler

#### Micrositios Plugin (reforestamos-micrositios)
- Interactive map: Árboles y Ciudades with Leaflet
- Interactive map: Red OJA organizations directory
- JSON data management admin interface
- Filtering by city, species, state, and type
- Statistics counters

#### Comunicación Plugin (reforestamos-comunicacion)
- Newsletter system with campaigns, subscribers, and double opt-in
- Contact form with honeypot anti-spam and rate limiting
- ChatBot widget with configurable conversation flows and logging
- DeepL API integration for automatic content translation
- Email sending via PHPMailer with SMTP support
- Tree adoption system with dashboard

#### Empresas Plugin (reforestamos-empresas)
- Extended company management with profiles and galleries
- Click analytics with dashboard and CSV export
- Image optimization and responsive gallery with lightbox
- Dependency check on Core Plugin

#### Migration System (migration-system)
- Database backup before migration
- Content migrator for posts, pages, and CPTs
- Shortcode-to-block converter
- WP-CLI command support
- Error logging and validation reporting

#### Infrastructure
- Webpack build pipeline via @wordpress/scripts
- ESLint + Prettier + PHP_CodeSniffer linting
- Husky pre-commit hooks with lint-staged
- PHPUnit test suites for all components
- Deployment scripts for staging and production
- Environment-based configuration
- Health check endpoints
- Automated backup on deployment

[unreleased]: https://github.com/reforestamos/theme/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/reforestamos/theme/releases/tag/v1.0.0
