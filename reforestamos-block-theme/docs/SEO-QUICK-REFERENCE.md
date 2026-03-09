# SEO Quick Reference Guide

## For Content Editors

### Adding Custom Meta Descriptions

1. Edit any post, page, evento, or empresa
2. Find the "SEO Meta Description" metabox
3. Write a compelling description (150-160 characters)
4. Watch the character counter
5. Save your changes

**Tips:**
- Include your main keyword
- Make it compelling (encourages clicks)
- Accurately describe the content
- Keep it under 160 characters

### Adding Breadcrumbs to Pages

Add this code to your template:

```php
<?php do_action('reforestamos_breadcrumbs'); ?>
```

### Optimizing Images

**When uploading images:**
1. Use descriptive filenames: `reforestacion-cdmx-2024.jpg`
2. Add alt text in media library
3. Add a title for the image
4. Keep file sizes reasonable (< 1MB)

**Alt Text Best Practices:**
- Describe what's in the image
- Include relevant keywords naturally
- Keep it concise (< 125 characters)
- Don't start with "Image of..." or "Picture of..."

---

## For Developers

### Features Included

✅ Open Graph meta tags  
✅ Twitter Card meta tags  
✅ Schema.org structured data (JSON-LD)  
✅ XML sitemap at `/sitemap.xml`  
✅ Canonical URLs  
✅ Breadcrumbs with schema  
✅ Optimized page titles  
✅ Automatic image alt text  

### Key Functions

```php
// Display breadcrumbs
do_action('reforestamos_breadcrumbs');

// Get breadcrumbs array
$breadcrumbs = reforestamos_get_breadcrumbs_array();

// Get meta description
$description = reforestamos_get_meta_description();

// Get current URL
$url = reforestamos_get_current_url();

// Get featured image URL
$image = reforestamos_get_featured_image_url();
```

### Customization Hooks

```php
// Modify structured data
add_filter('reforestamos_structured_data', function($schema) {
    // Add custom schema
    return $schema;
});

// Modify meta tags
add_filter('reforestamos_meta_tags', function($meta_tags) {
    // Add custom meta tags
    return $meta_tags;
});

// Modify breadcrumbs
add_filter('reforestamos_breadcrumbs', function($breadcrumbs) {
    // Modify breadcrumb items
    return $breadcrumbs;
});
```

### Adding Social Profiles

Edit `inc/seo.php`, function `reforestamos_get_social_profiles()`:

```php
function reforestamos_get_social_profiles() {
    return array(
        'https://facebook.com/reforestamos',
        'https://twitter.com/reforestamos',
        'https://instagram.com/reforestamos',
    );
}
```

### Compatibility

The SEO system automatically detects and defers to:
- Yoast SEO
- Rank Math
- Other major SEO plugins

Check in code:
```php
if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) {
    // SEO plugin active, skip our implementation
}
```

---

## Testing Tools

### Meta Tags
- **Facebook**: https://developers.facebook.com/tools/debug/
- **Twitter**: https://cards-dev.twitter.com/validator

### Structured Data
- **Google**: https://search.google.com/test/rich-results
- **Schema.org**: https://validator.schema.org/

### General SEO
- **PageSpeed**: https://pagespeed.web.dev/
- **Mobile-Friendly**: https://search.google.com/test/mobile-friendly

### Sitemap
- Visit: `https://yoursite.com/sitemap.xml`
- Submit to Google Search Console

---

## Common Issues

### Sitemap 404 Error
**Solution:** Go to Settings > Permalinks > Save Changes

### Meta Tags Not Showing
**Solution:** Check if SEO plugin is active, clear cache

### Breadcrumbs Not Displaying
**Solution:** Add `do_action('reforestamos_breadcrumbs')` to template

### Images Missing Alt Text
**Solution:** Add alt text in media library when uploading

---

## File Locations

```
reforestamos-block-theme/
├── inc/
│   ├── seo.php              # Main SEO functionality
│   └── breadcrumbs.php      # Breadcrumb styles
└── functions.php            # Includes SEO files
```

---

## Support

For detailed documentation, see:
- `TASK-32-IMPLEMENTATION.md` - Complete implementation guide
- WordPress SEO Guide: https://wordpress.org/support/article/search-engine-optimization/
- Schema.org: https://schema.org/

---

**Quick Tip:** Always test your pages with Google Rich Results Test after publishing!
