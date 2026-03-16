# Reforestamos Micrositios

Interactive map microsites plugin for Reforestamos México. Provides the "Árboles y Ciudades" and "Red OJA" microsites with Leaflet-powered maps, filtering, and admin data management.

## Requirements

- WordPress 6.0+
- PHP 7.4+

## Installation

1. Upload `reforestamos-micrositios/` to `wp-content/plugins/`
2. Activate in WordPress Admin → Plugins
3. Create pages and insert shortcodes (see below)

## Features

- Interactive maps powered by Leaflet.js (loaded from CDN)
- Two microsites: Árboles y Ciudades, Red OJA
- JSON-based data management with admin upload interface
- Filtering by city, species, state, type, and activity
- Marker clustering for large datasets
- Directory view alongside map view (Red OJA)

## Shortcodes

### `[arboles-ciudades]`

Renders the Árboles y Ciudades interactive map.

```html
<!-- In any page or post -->
[arboles-ciudades]
```

Displays:
- Interactive map with tree location markers
- Filter controls (city, species, date)
- Popup details on marker click
- Statistics counters (total trees, species, cities)

### `[red-oja]`

Renders the Red OJA (Red de Organizaciones Juveniles Ambientales) map.

```html
[red-oja]
```

Displays:
- Interactive map with organization markers
- Filter controls (state, type, activity)
- Organization directory list (synced with map)
- Popup details on marker click

## Configuration

### Admin Interface

Navigate to **Micrositios** in the WordPress admin menu to:

- Upload/replace JSON data files
- Preview data before saving
- Validate JSON structure

### Data Format

#### Árboles y Ciudades (`data/arboles-ciudades.json`)

```json
[
  {
    "nombre": "Ahuehuete",
    "especie": "Taxodium mucronatum",
    "ciudad": "Ciudad de México",
    "lat": 19.4326,
    "lng": -99.1332,
    "fecha": "2023-06-15",
    "cantidad": 50,
    "descripcion": "Plantación en Chapultepec"
  }
]
```

#### Red OJA (`data/red-oja.json`)

```json
[
  {
    "nombre": "Organización Ejemplo",
    "estado": "Jalisco",
    "tipo": "ONG",
    "actividad": "Reforestación",
    "lat": 20.6597,
    "lng": -103.3496,
    "contacto": "contacto@ejemplo.org",
    "descripcion": "Descripción de la organización"
  }
]
```

## Directory Structure

```
reforestamos-micrositios/
├── reforestamos-micrositios.php    # Main plugin file
├── includes/
│   ├── class-reforestamos-micrositios.php  # Main plugin class
│   ├── class-arboles-ciudades.php  # Árboles y Ciudades shortcode
│   ├── class-red-oja.php           # Red OJA shortcode
│   ├── class-map-renderer.php      # Shared map rendering
│   └── class-json-manager.php      # JSON data management
├── assets/
│   ├── js/
│   │   └── map-handler.js          # Map initialization and interaction
│   └── css/
│       └── maps.css                # Map styles
├── admin/
│   ├── js/
│   │   └── admin-uploader.js       # JSON upload interface
│   ├── css/                        # Admin styles
│   ├── views/                      # Admin page templates
│   └── templates/                  # Admin templates
├── data/
│   ├── arboles-ciudades.json       # Tree data
│   └── red-oja.json                # Organization data
├── templates/                      # Frontend templates
├── tests/                          # Test files
└── uninstall.php                   # Clean uninstall
```

## API & Hooks

### Actions

| Hook | Description |
|------|-------------|
| `reforestamos_micrositios_init` | Fires after plugin initialization |
| `reforestamos_micrositios_data_updated` | Fires after JSON data is updated |

### Filters

| Filter | Description |
|--------|-------------|
| `reforestamos_micrositios_map_options` | Filter Leaflet map initialization options |
| `reforestamos_micrositios_marker_popup` | Filter marker popup HTML content |
| `reforestamos_micrositios_allowed_file_types` | Filter allowed upload file types |

### JavaScript Events

The map handler dispatches custom events on the map container:

```javascript
// Listen for map ready
document.addEventListener('reforestamos-map-ready', (e) => {
  console.log('Map initialized:', e.detail.mapId);
});

// Listen for filter changes
document.addEventListener('reforestamos-filter-change', (e) => {
  console.log('Filters:', e.detail.filters);
});
```

## Uninstall

Deactivating the plugin hides the shortcode output. Deleting the plugin via `uninstall.php` removes:
- Plugin options from `wp_options`
- Uploaded JSON data files

## License

GPL v2 or later
