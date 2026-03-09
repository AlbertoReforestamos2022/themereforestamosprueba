# Reforestamos Core Plugin

Core functionality plugin for Reforestamos México WordPress site. Provides Custom Post Types, Taxonomies, Custom Fields, and REST API endpoints.

## Overview

This plugin is part of the modular architecture for the Reforestamos México website. It handles all core content types and data structures that other plugins and the theme depend on.

## Features

- **Custom Post Types**: Empresas, Eventos, Integrantes, Boletín
- **Custom Taxonomies**: Organized categorization for all post types
- **Custom Fields**: Structured data entry using CMB2
- **REST API**: Full REST API support with custom endpoints and exposed custom fields
- **Admin UI**: Enhanced admin interface for content management

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- Composer (for installing dependencies including CMB2)

## Installation

1. Upload the `reforestamos-core` folder to `/wp-content/plugins/`
2. Run `composer install` in the plugin directory to install CMB2 and other dependencies
3. Activate the plugin through the WordPress admin
4. Custom post types will be immediately available

**Note:** CMB2 is bundled with the plugin via Composer. You don't need to install it separately.

## Directory Structure

```
reforestamos-core/
├── reforestamos-core.php          # Main plugin file
├── includes/                       # Core classes
│   ├── class-reforestamos-core.php    # Main plugin class
│   ├── class-post-types.php           # Post types registration
│   ├── class-taxonomies.php           # Taxonomies registration
│   ├── class-custom-fields.php        # Custom fields setup with CMB2
│   ├── class-rest-api.php             # REST API endpoints
│   └── class-reforestamos-core.php    # Main plugin class
├── lib/                            # Bundled libraries
│   └── cmb2/                       # CMB2 library (installed via Composer)
├── admin/                          # Admin-specific files
│   ├── css/                        # Admin styles
│   ├── js/                         # Admin scripts
│   └── views/                      # Admin page templates
├── languages/                      # Translation files
│   └── reforestamos-core.pot      # Translation template
├── tests/                          # PHPUnit tests
│   ├── test-post-types.php        # Post types tests
│   └── test-rest-api.php          # REST API tests
├── composer.json                   # Composer dependencies
├── readme.txt                      # WordPress.org readme
└── README.md                       # This file
```

## Custom Post Types

### Empresas (Companies)
Partner companies and collaborators.

**Fields:**
- Logo
- Description
- URL
- Category

**REST API:** `/wp-json/wp/v2/empresas`

### Eventos (Events)
Reforestation events and activities.

**Fields:**
- Date
- Location
- Description
- Featured Image

**REST API:** `/wp-json/wp/v2/eventos`

### Integrantes (Team Members)
Team member profiles.

**Fields:**
- Position
- Photo
- Biography
- Social Media Links

**REST API:** `/wp-json/wp/v2/integrantes`

### Boletín (Newsletter)
Newsletter content and campaigns.

**Fields:**
- Publication Date
- HTML Content
- Recipient List

**REST API:** `/wp-json/wp/v2/boletin`

## Usage

### Accessing Custom Post Types

After activation, custom post types are available in the WordPress admin menu:

- Empresas
- Eventos
- Integrantes
- Boletín

### REST API Endpoints

All custom post types are accessible via the standard WordPress REST API:

```bash
# Get all companies
GET /wp-json/wp/v2/empresas

# Get single company
GET /wp-json/wp/v2/empresas/{id}

# Create company (requires authentication)
POST /wp-json/wp/v2/empresas

# Update company (requires authentication)
PUT /wp-json/wp/v2/empresas/{id}

# Delete company (requires authentication)
DELETE /wp-json/wp/v2/empresas/{id}
```

#### Custom REST API Endpoints

The plugin also provides custom endpoints with advanced filtering:

**GET /wp-json/reforestamos/v1/empresas**

Get companies with custom filters.

Query Parameters:
- `categoria` (string) - Filter by category
- `anio` (string) - Filter by year
- `arboles_min` (integer) - Minimum number of trees
- `per_page` (integer, default: 10) - Items per page
- `page` (integer, default: 1) - Page number

Example:
```bash
GET /wp-json/reforestamos/v1/empresas?categoria=corporativo&anio=2020&per_page=20
```

Response:
```json
[
  {
    "id": 123,
    "title": "Company Name",
    "content": "Description...",
    "excerpt": "Short description...",
    "link": "https://example.com/empresas/company-name",
    "meta": {
      "empresa_logo": "https://example.com/logo.jpg",
      "empresa_url": "https://company.com",
      "empresa_categoria": "corporativo",
      "empresa_anio": "2020",
      "empresa_arboles": "5000"
    }
  }
]
```

**GET /wp-json/reforestamos/v1/eventos/upcoming**

Get upcoming events (fecha >= today).

Query Parameters:
- `ubicacion` (string) - Filter by location
- `tipo` (string) - Filter by event type (taxonomy slug)
- `per_page` (integer, default: 10) - Items per page
- `page` (integer, default: 1) - Page number

Example:
```bash
GET /wp-json/reforestamos/v1/eventos/upcoming?ubicacion=CDMX&per_page=5
```

Response:
```json
[
  {
    "id": 456,
    "title": "Event Name",
    "content": "Event description...",
    "excerpt": "Short description...",
    "link": "https://example.com/eventos/event-name",
    "thumbnail": "https://example.com/event-image.jpg",
    "meta": {
      "evento_fecha": "2024-12-25T10:00:00+00:00",
      "evento_ubicacion": "CDMX",
      "evento_lat": "19.4326",
      "evento_lng": "-99.1332",
      "evento_capacidad": "100",
      "evento_registro_activo": "1"
    }
  }
]
```

**GET /wp-json/reforestamos/v1/integrantes**

Get team members.

Query Parameters:
- `area` (string) - Filter by area (taxonomy slug)
- `per_page` (integer, default: 10) - Items per page
- `page` (integer, default: 1) - Page number

Example:
```bash
GET /wp-json/reforestamos/v1/integrantes?area=direccion
```

Response:
```json
[
  {
    "id": 789,
    "name": "Team Member Name",
    "content": "Biography...",
    "excerpt": "Short bio...",
    "link": "https://example.com/integrantes/member-name",
    "photo": "https://example.com/photo.jpg",
    "meta": {
      "integrante_cargo": "Director",
      "integrante_email": "email@example.com",
      "integrante_redes": {
        "twitter": "https://twitter.com/username",
        "linkedin": "https://linkedin.com/in/username"
      }
    }
  }
]
```

#### Custom Fields in REST API

All custom fields are automatically exposed in the REST API responses under the `meta` property:

- **Empresas**: `empresa_logo`, `empresa_url`, `empresa_categoria`, `empresa_anio`, `empresa_arboles`
- **Eventos**: `evento_fecha`, `evento_ubicacion`, `evento_lat`, `evento_lng`, `evento_capacidad`, `evento_registro_activo`
- **Integrantes**: `integrante_cargo`, `integrante_email`, `integrante_redes`
- **Boletín**: `boletin_fecha_envio`, `boletin_estado`, `boletin_destinatarios`

## Development

### Coding Standards

This plugin follows:
- WordPress Coding Standards
- WordPress Plugin API best practices
- Singleton pattern for main class
- Namespaced functions and classes
- Security best practices (sanitization, escaping, nonce verification)

### Hooks and Filters

The plugin provides various hooks for extensibility:

**Actions:**
- `reforestamos_core_init` - Fires after plugin initialization
- `reforestamos_core_post_types_registered` - Fires after post types are registered
- `reforestamos_core_taxonomies_registered` - Fires after taxonomies are registered

**Filters:**
- `reforestamos_core_post_type_args` - Filter post type registration arguments
- `reforestamos_core_taxonomy_args` - Filter taxonomy registration arguments

### Testing

Run PHPUnit tests:

```bash
composer install
composer test
```

## Dependencies

### Required
- WordPress 6.0+
- PHP 7.4+
- CMB2 2.10+ (bundled via Composer)

### Development
- Composer (for dependency management)
- PHPUnit 9.0+ (for testing)

## Plugin Dependencies

- **Independent**: This plugin can be activated independently
- **Required by**: Reforestamos Empresas plugin requires this plugin

## Security

- All user input is sanitized
- All output is escaped
- Nonce verification for all forms
- Capability checks for all operations
- Prepared statements for database queries

## Internationalization

The plugin is translation-ready:
- Text domain: `reforestamos-core`
- POT file included in `/languages/`
- Supports Spanish (es_MX) and English (en_US)

## Changelog

### 1.0.0 - Initial Release
- Custom Post Types registration
- Custom Taxonomies
- Custom Fields with CMB2
- REST API endpoints
- Admin UI enhancements

## Support

For support and documentation, visit: https://reforestamos.org

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Credits

Developed by Reforestamos México
