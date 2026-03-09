# Admin UI Component

## Overview

The Admin UI component enhances the WordPress admin interface for Reforestamos Core Custom Post Types. It provides a better user experience with organized menus, custom styling, advanced filtering, and inline validation.

## Features

### 1. Custom Menu Structure (Requirement 27.2)
- Top-level "Reforestamos" menu that groups all CPTs
- Submenu items: Dashboard, Empresas, Eventos, Integrantes, Boletines
- Custom icon (dashicons-admin-site)
- Logical organization for easy navigation

### 2. Dashboard Widget (Requirement 27.4)
- Quick statistics for all CPTs
- Upcoming events list
- Quick action links for creating new content
- Visual cards with icons and counts

### 3. Custom Admin Styles (Requirement 27.1)
- Enhanced list tables with custom columns
- Styled edit screens with better visual hierarchy
- Custom color palette matching theme
- Responsive design for mobile admin
- Located in: `admin/css/admin-styles.css`

### 4. Custom Columns (Requirement 27.6)
- **Empresas**: Logo, Categoría, Sitio Web
- **Eventos**: Fecha, Ubicación, Capacidad
- **Integrantes**: Foto, Cargo

### 5. Taxonomy Filters (Requirement 27.6)
- Dropdown filters for all taxonomies
- Date range filter for eventos
- Automatic filtering on list tables

### 6. Enhanced Search (Requirement 27.6)
- Searches in custom fields in addition to title/content
- Configurable searchable meta keys per post type
- Extensible via filter hook

### 7. Bulk Actions (Requirement 27.7)
- Mark/Unmark as Featured
- Extensible for additional bulk actions
- Success notifications after bulk operations

### 8. Validation (Requirements 27.3, 27.8)
- Inline validation for required fields
- Real-time validation on blur
- Email, URL, and date format validation
- Clear error messages
- Located in: `admin/js/admin-validation.js`

### 9. Contextual Help (Requirement 27.9)
- Help tooltips on form fields
- Descriptive help text for complex fields
- Hover-activated tooltips

## Usage

The Admin UI component is automatically initialized when the plugin is activated. No additional configuration is required.

### Extending Searchable Fields

To add custom searchable meta keys:

```php
add_filter( 'reforestamos_searchable_meta_keys', function( $meta_keys, $post_type ) {
    if ( $post_type === 'empresas' ) {
        $meta_keys[] = '_empresa_custom_field';
    }
    return $meta_keys;
}, 10, 2 );
```

### Adding Custom Bulk Actions

Bulk actions are added via WordPress filters and can be extended in the `add_bulk_actions()` and `handle_bulk_actions()` methods.

## Files

- `includes/class-admin-ui.php` - Main Admin UI class
- `admin/css/admin-styles.css` - Custom admin styles
- `admin/js/admin-validation.js` - Validation JavaScript

## Requirements Fulfilled

- ✅ 27.1: Custom admin styles for improved UX
- ✅ 27.2: CPTs organized in logical menu structure
- ✅ 27.3: Validation errors displayed clearly
- ✅ 27.4: Custom admin interface
- ✅ 27.6: Search and filtering for all CPTs
- ✅ 27.7: Bulk actions available
- ✅ 27.8: Clear error messages
- ✅ 27.9: Contextual help text
