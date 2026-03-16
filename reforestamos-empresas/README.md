# Reforestamos Empresas

Company management plugin for Reforestamos México. Extends the Empresas CPT with company profiles, logo grids, click analytics, and photo galleries.

## Requirements

- WordPress 6.0+
- PHP 7.4+
- **Reforestamos Core plugin** (required dependency — must be active)

## Installation

1. Ensure the **Reforestamos Core** plugin is installed and active
2. Upload `reforestamos-empresas/` to `wp-content/plugins/`
3. Activate in WordPress Admin → Plugins

If Core is not active, the plugin will display an admin warning and deactivate gracefully.

## Features

### Company Management
- Extended company profiles with logo, description, gallery, contact info
- Frontend company profile template
- Company categorization by industry/partnership type

### Company Grid Display
- `[companies-grid]` shortcode for logo grids
- Filterable by category
- Responsive layout with lazy-loaded images

### Click Analytics
- Automatic click tracking on company logos and links
- Admin dashboard with metrics (total clicks, monthly, top companies)
- Unique vs. repeat click tracking (cookie-based)
- Date range filtering
- CSV export

### Photo Galleries
- Per-company gallery management
- Responsive grid with lightbox (GLightbox)
- Automatic thumbnail generation
- Image captions and descriptions
- `[company-gallery]` shortcode
- All-galleries page template

## Shortcodes

### `[companies-grid]`

Displays a responsive grid of company logos.

```html
<!-- All companies -->
[companies-grid]

<!-- Filter by category -->
[companies-grid category="corporativo"]

<!-- Custom columns -->
[companies-grid columns="6"]
```

**Parameters:**
| Parameter | Default | Description |
|-----------|---------|-------------|
| `category` | `""` | Filter by company category slug |
| `columns` | `4` | Number of grid columns |
| `orderby` | `title` | Sort order |

### `[company-gallery]`

Displays a specific company's photo gallery.

```html
[company-gallery id="123"]
```

**Parameters:**
| Parameter | Default | Description |
|-----------|---------|-------------|
| `id` | (required) | Company post ID |
| `columns` | `3` | Gallery grid columns |

## Configuration

### Analytics Dashboard

Navigate to **Empresas → Analytics** to view:
- Total clicks across all companies
- Monthly click trends (Chart.js graphs)
- Top companies by clicks
- Date range filtering
- CSV export button

### Gallery Management

When editing a company post, use the **Gallery** meta box to:
- Upload multiple images
- Reorder images via drag-and-drop
- Add captions and descriptions
- Remove individual images

## Directory Structure

```
reforestamos-empresas/
├── reforestamos-empresas.php          # Main plugin file
├── includes/
│   ├── class-reforestamos-empresas.php # Main plugin class
│   ├── class-company-manager.php       # Company management
│   ├── class-shortcodes.php            # Shortcode handlers
│   ├── class-analytics.php             # Click tracking & analytics
│   ├── class-gallery-manager.php       # Gallery management
│   └── class-image-optimizer.php       # Image optimization
├── admin/
│   ├── views/
│   │   └── analytics-dashboard.php     # Analytics admin page
│   ├── js/
│   │   ├── admin.js                    # Admin scripts
│   │   └── analytics.js               # Analytics dashboard JS
│   └── css/                            # Admin styles
├── assets/
│   ├── js/
│   │   └── companies-grid.js           # Frontend grid JS
│   └── css/
│       ├── companies-grid.css          # Grid styles
│       └── company-profile.css         # Profile styles
├── templates/
│   ├── single-empresa-template.php     # Company profile template
│   └── page-galleries.php              # All galleries page
├── languages/
│   └── reforestamos-empresas.pot       # Translation template
├── tests/                              # Test files
└── uninstall.php                       # Clean uninstall
```

## API & Hooks

### Actions

| Hook | Description |
|------|-------------|
| `reforestamos_empresas_init` | Fires after plugin initialization |
| `reforestamos_company_click` | Fires on company click. Args: `$company_id`, `$click_data` |
| `reforestamos_gallery_updated` | Fires after gallery update. Args: `$company_id` |

### Filters

| Filter | Description |
|--------|-------------|
| `reforestamos_companies_grid_args` | Filter WP_Query args for companies grid |
| `reforestamos_company_profile_fields` | Filter displayed profile fields |
| `reforestamos_gallery_image_sizes` | Filter generated image sizes |
| `reforestamos_analytics_csv_columns` | Filter CSV export columns |
| `reforestamos_company_logo_size` | Filter logo display size |

### AJAX Endpoints

| Action | Method | Description |
|--------|--------|-------------|
| `reforestamos_track_click` | POST | Record a company click |
| `reforestamos_export_analytics` | GET | Export analytics CSV (admin only) |

## Database Tables

| Table | Purpose |
|-------|---------|
| `{prefix}reforestamos_clicks` | Click tracking data |

## Dependencies

This plugin **requires** the Reforestamos Core plugin because it extends the `empresas` Custom Post Type registered by Core.

## Uninstall

Deleting the plugin via `uninstall.php` removes:
- Click tracking database table
- Plugin options from `wp_options`
- Generated image thumbnails (optional)

## License

GPL v2 or later
