# Changelog

All notable changes to the Reforestamos Core plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- CMB2 library integration via Composer (v2.11.0)
- Custom Fields class (Reforestamos_Core_Custom_Fields) with singleton pattern
- Metabox registration for Empresas Custom Post Type (logo, URL, category, year, trees planted)
- Metabox registration for Eventos Custom Post Type (date, location, coordinates, capacity, registration status)
- Metabox registration for Integrantes Custom Post Type (position, email, social media links)
- Metabox registration for Boletín Custom Post Type (send date, status, recipients)
- Automatic CMB2 loading from lib/ directory
- Admin notice for missing CMB2 dependency

### Changed
- Updated composer.json to include CMB2 as dependency
- Updated .gitignore to exclude lib/ directory
- Updated README.md with CMB2 installation instructions
- Modified main class to initialize Custom Fields component

### Planned
- REST API endpoints for all post types
- Admin UI enhancements
- PHPUnit test suite

## [1.0.0] - 2024-01-XX

### Added
- Initial plugin structure
- Main plugin file with proper WordPress headers
- Singleton pattern implementation for main class
- Plugin activation and deactivation hooks
- Text domain loading for internationalization
- Directory structure (includes/, admin/, languages/, tests/)
- readme.txt following WordPress plugin standards
- README.md with comprehensive documentation
- uninstall.php for clean plugin removal
- composer.json for dependency management
- .gitignore for version control

### Security
- Implemented ABSPATH checks to prevent direct access
- Namespaced all functions and classes
- Prepared for nonce verification and data sanitization

### Documentation
- Complete README.md with usage instructions
- WordPress.org compatible readme.txt
- Inline code documentation with PHPDoc comments
- Installation and configuration guide

[Unreleased]: https://github.com/reforestamos/reforestamos-core/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/reforestamos/reforestamos-core/releases/tag/v1.0.0
