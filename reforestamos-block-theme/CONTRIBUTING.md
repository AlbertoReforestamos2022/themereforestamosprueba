# Contributing to Reforestamos Block Theme

Thank you for your interest in contributing to the Reforestamos México project. This guide covers the development workflow, coding standards, and conventions used across the theme and plugins.

## Table of Contents

- [Getting Started](#getting-started)
- [Development Environment](#development-environment)
- [Project Structure](#project-structure)
- [Coding Standards](#coding-standards)
- [Creating a Custom Block](#creating-a-custom-block)
- [Working with Plugins](#working-with-plugins)
- [Hooks and Filters Reference](#hooks-and-filters-reference)
- [Customization Examples](#customization-examples)
- [Testing](#testing)
- [Submitting Changes](#submitting-changes)

## Getting Started

### Prerequisites

- WordPress 6.0+ local installation (Local by Flywheel, MAMP, Docker, etc.)
- PHP 7.4+
- Node.js 16+ and npm 8+
- Composer (for plugin dependencies)
- Git

### Setup

```bash
# Clone the repository
git clone <repo-url>

# Install theme dependencies
cd reforestamos-block-theme
npm install

# Build assets
npm run build

# Install Core plugin dependencies
cd ../reforestamos-core
composer install

# Activate theme and plugins in WordPress Admin
```

## Development Environment

### Theme Development

```bash
cd reforestamos-block-theme

# Start development mode (watches for changes)
npm run start

# Build for production
npm run build

# Lint JavaScript
npm run lint:js

# Lint CSS/SCSS
npm run lint:css

# Format code
npm run format

# Run tests
npm test
```

### Plugin Development

Plugins are plain PHP — no build step required. Just edit files and refresh.

For Core plugin tests:
```bash
cd reforestamos-core
composer install
composer test
```

## Project Structure

```
project-root/
├── reforestamos-block-theme/    # Block Theme (presentation layer)
├── reforestamos-core/           # Core Plugin (CPTs, fields, REST API)
├── reforestamos-micrositios/    # Micrositios Plugin (maps)
├── reforestamos-comunicacion/   # Comunicación Plugin (newsletter, forms, chatbot)
├── reforestamos-empresas/       # Empresas Plugin (companies, analytics, galleries)
└── migration-system/            # Data migration tools
```

### Separation of Concerns

- **Theme** → Presentation only (blocks, templates, styles)
- **Core Plugin** → Data layer (CPTs, taxonomies, custom fields, REST API)
- **Feature Plugins** → Domain-specific functionality
- **Migration System** → One-time data migration tools

## Coding Standards

### PHP

Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/):

- Use tabs for indentation
- Use `snake_case` for function and variable names
- Prefix all functions with `reforestamos_` or use class namespacing
- Add PHPDoc blocks to all functions and classes
- Sanitize all input, escape all output
- Use nonces for form submissions
- Use prepared statements for database queries

```php
/**
 * Get upcoming events.
 *
 * @since 1.0.0
 *
 * @param int    $count    Number of events to retrieve.
 * @param string $location Optional. Filter by location.
 * @return array Array of event post objects.
 */
function reforestamos_get_upcoming_events( $count = 5, $location = '' ) {
    $args = array(
        'post_type'      => 'eventos',
        'posts_per_page' => absint( $count ),
        'post_status'    => 'publish',
    );

    if ( ! empty( $location ) ) {
        $args['meta_query'] = array(
            array(
                'key'     => 'evento_ubicacion',
                'value'   => sanitize_text_field( $location ),
                'compare' => 'LIKE',
            ),
        );
    }

    return get_posts( $args );
}
```

### JavaScript

Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/):

- Use ES6+ syntax (compiled by webpack)
- Use JSDoc for all exported functions
- Use `camelCase` for variables and functions
- Import WordPress packages from `@wordpress/*`

```javascript
/**
 * Fetch upcoming events from the REST API.
 *
 * @param {number} count - Number of events to fetch.
 * @return {Promise<Array>} Array of event objects.
 */
async function fetchUpcomingEvents(count = 5) {
    const response = await apiFetch({
        path: `/reforestamos/v1/eventos/upcoming?per_page=${count}`,
    });
    return response;
}
```

### SCSS

- Use BEM naming convention: `.block__element--modifier`
- Use theme variables from `_variables.scss`
- Mobile-first responsive design
- Prefix custom classes with `reforestamos-`

```scss
.reforestamos-hero {
    &__content {
        padding: 2rem;
    }

    &__title {
        font-size: var(--wp--preset--font-size--xx-large);

        @media (max-width: 768px) {
            font-size: var(--wp--preset--font-size--x-large);
        }
    }

    &--dark {
        background-color: var(--wp--preset--color--dark);
    }
}
```

## Creating a Custom Block

### Step 1: Create the block directory

```bash
mkdir blocks/my-block
```

### Step 2: Create block.json

```json
{
    "$schema": "https://schemas.wp.org/trunk/block.json",
    "apiVersion": 2,
    "name": "reforestamos/my-block",
    "title": "My Block",
    "category": "reforestamos",
    "icon": "smiley",
    "description": "Description of my block",
    "keywords": ["keyword1", "keyword2"],
    "textdomain": "reforestamos",
    "attributes": {
        "title": {
            "type": "string",
            "default": ""
        }
    },
    "supports": {
        "align": ["wide", "full"],
        "anchor": true
    },
    "editorScript": "file:./index.js",
    "style": "file:./style.scss"
}
```

### Step 3: Create index.js

```javascript
import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import metadata from './block.json';

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save,
});
```

### Step 4: Create edit.js and save.js

See `blocks/hero/edit.js` and `blocks/hero/save.js` for reference implementations.

### Step 5: Create style.scss

```scss
.wp-block-reforestamos-my-block {
    // Block styles here
}
```

### Step 6: Build and test

```bash
npm run build
# Refresh the WordPress editor — your block appears under "Reforestamos" category
```

The block is auto-registered by `inc/block-registration.php` which scans `blocks/*/block.json`.

## Working with Plugins

### Adding a REST API Endpoint (Core Plugin)

Edit `reforestamos-core/includes/class-rest-api.php`:

```php
// In register_routes() method:
register_rest_route(
    $this->namespace,
    '/my-endpoint',
    array(
        'methods'             => 'GET',
        'callback'            => array( $this, 'get_my_data' ),
        'permission_callback' => '__return_true',
    )
);
```

### Adding a Custom Field (Core Plugin)

Edit `reforestamos-core/includes/class-custom-fields.php` and add a new field to the appropriate metabox.

### Adding a Shortcode (Any Plugin)

```php
add_shortcode( 'my-shortcode', array( $this, 'render_shortcode' ) );

public function render_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'param' => 'default',
    ), $atts, 'my-shortcode' );

    ob_start();
    // Render output
    return ob_get_clean();
}
```

## Hooks and Filters Reference

### Theme Hooks

#### Actions

| Hook | Location | Description |
|------|----------|-------------|
| `after_setup_theme` | `functions.php` | Theme setup (supports, menus, textdomain) |
| `wp_enqueue_scripts` | `inc/enqueue-assets.php` | Frontend asset loading |
| `init` | `inc/block-registration.php` | Block registration |

#### Filters

| Filter | Description | Parameters |
|--------|-------------|------------|
| `block_categories_all` | Add custom block category | `$categories` |
| `reforestamos_seo_meta_tags` | Filter SEO meta tags | `$tags`, `$post` |
| `reforestamos_breadcrumb_items` | Filter breadcrumb items | `$items` |
| `reforestamos_performance_critical_css` | Filter critical CSS | `$css` |

### Core Plugin Hooks

#### Actions

| Hook | Description | Parameters |
|------|-------------|------------|
| `reforestamos_core_init` | After plugin init | — |
| `reforestamos_core_post_types_registered` | After CPTs registered | — |
| `reforestamos_core_taxonomies_registered` | After taxonomies registered | — |

#### Filters

| Filter | Description | Parameters |
|--------|-------------|------------|
| `reforestamos_core_post_type_args` | Filter CPT args | `$args`, `$post_type` |
| `reforestamos_core_taxonomy_args` | Filter taxonomy args | `$args`, `$taxonomy` |

### Comunicación Plugin Hooks

#### Actions

| Hook | Description | Parameters |
|------|-------------|------------|
| `reforestamos_newsletter_sent` | After newsletter sent | `$campaign_id`, `$count` |
| `reforestamos_contact_form_submitted` | After form submission | `$form_data` |
| `reforestamos_chatbot_message` | On chatbot message | `$message`, `$response` |
| `reforestamos_translation_complete` | After translation | `$post_id`, `$lang` |
| `reforestamos_subscriber_added` | Subscriber added | `$email` |
| `reforestamos_subscriber_removed` | Subscriber removed | `$email` |

#### Filters

| Filter | Description | Parameters |
|--------|-------------|------------|
| `reforestamos_contact_form_fields` | Filter form fields | `$fields` |
| `reforestamos_newsletter_template` | Filter email template | `$template` |
| `reforestamos_chatbot_response` | Filter bot response | `$response`, `$message` |
| `reforestamos_deepl_content` | Filter pre-translation content | `$content` |
| `reforestamos_deepl_translated` | Filter translated content | `$translated` |
| `reforestamos_mailer_headers` | Filter email headers | `$headers` |
| `reforestamos_spam_check` | Filter spam detection | `$is_spam`, `$data` |

### Empresas Plugin Hooks

#### Actions

| Hook | Description | Parameters |
|------|-------------|------------|
| `reforestamos_company_click` | On company click | `$company_id`, `$data` |
| `reforestamos_gallery_updated` | After gallery update | `$company_id` |

#### Filters

| Filter | Description | Parameters |
|--------|-------------|------------|
| `reforestamos_companies_grid_args` | Filter grid query args | `$args` |
| `reforestamos_company_profile_fields` | Filter profile fields | `$fields` |
| `reforestamos_gallery_image_sizes` | Filter image sizes | `$sizes` |
| `reforestamos_analytics_csv_columns` | Filter CSV columns | `$columns` |

### Micrositios Plugin Hooks

#### Filters

| Filter | Description | Parameters |
|--------|-------------|------------|
| `reforestamos_micrositios_map_options` | Filter map init options | `$options` |
| `reforestamos_micrositios_marker_popup` | Filter popup HTML | `$html`, `$data` |

## Customization Examples

### Example 1: Add a field to company profiles

```php
// In your theme's functions.php or a custom plugin:
add_filter( 'reforestamos_company_profile_fields', function( $fields ) {
    $fields['website_visits'] = array(
        'label' => __( 'Website Visits', 'my-plugin' ),
        'type'  => 'number',
    );
    return $fields;
});
```

### Example 2: Customize newsletter email template

```php
add_filter( 'reforestamos_newsletter_template', function( $template ) {
    // Add custom header
    $custom_header = '<div style="background:#2E7D32;padding:20px;text-align:center;">';
    $custom_header .= '<img src="' . esc_url( get_theme_mod( 'custom_logo' ) ) . '" alt="Logo">';
    $custom_header .= '</div>';

    return $custom_header . $template;
});
```

### Example 3: Add custom map markers

```php
add_filter( 'reforestamos_micrositios_marker_popup', function( $html, $data ) {
    // Add a "Directions" link to each popup
    $html .= sprintf(
        '<a href="https://maps.google.com/?q=%s,%s" target="_blank">%s</a>',
        esc_attr( $data['lat'] ),
        esc_attr( $data['lng'] ),
        esc_html__( 'Get Directions', 'my-plugin' )
    );
    return $html;
}, 10, 2 );
```

### Example 4: Track custom analytics events

```javascript
// In your custom JS file:
jQuery(document).on('click', '.custom-cta-button', function() {
    jQuery.post(reforestamosEmpresas.ajaxUrl, {
        action: 'reforestamos_track_click',
        nonce: reforestamosEmpresas.nonce,
        company_id: jQuery(this).data('company-id'),
        click_type: 'cta_button'
    });
});
```

### Example 5: Register a new block pattern

```php
// In your theme's functions.php:
add_action( 'init', function() {
    register_block_pattern(
        'reforestamos/my-pattern',
        array(
            'title'       => __( 'My Custom Pattern', 'reforestamos' ),
            'categories'  => array( 'reforestamos' ),
            'content'     => '<!-- wp:reforestamos/hero {"title":"Custom Title"} /-->',
        )
    );
});
```

## Testing

### JavaScript Tests (Jest)

```bash
cd reforestamos-block-theme
npm test
```

Tests live in `tests/blocks/` and `tests/__mocks__/`.

### PHP Tests (PHPUnit)

```bash
cd reforestamos-core
composer test
```

### Manual Testing Checklist

- [ ] All 16 blocks render correctly in the editor
- [ ] All blocks render correctly on the frontend
- [ ] Responsive design works on mobile/tablet/desktop
- [ ] Language switcher works (ES ↔ EN)
- [ ] Contact form submits and sends email
- [ ] Newsletter subscription works
- [ ] Chatbot responds to messages
- [ ] Company logos track clicks
- [ ] Maps load and filters work
- [ ] Search returns relevant results

## Submitting Changes

1. Create a feature branch: `git checkout -b feature/my-feature`
2. Make your changes following the coding standards above
3. Run linters: `npm run lint:js && npm run lint:css`
4. Run tests: `npm test`
5. Build: `npm run build`
6. Commit with a descriptive message
7. Push and create a pull request

### Commit Message Format

```
type: short description

Longer description if needed.

Refs: #issue-number
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`
