# Migration Guide: Legacy Theme → Block Theme

Step-by-step guide for migrating from the legacy PHP-based Reforestamos México theme to the modern Block Theme architecture.

## Pre-Migration Checklist

- [ ] WordPress 6.0+ installed
- [ ] PHP 7.4+ available
- [ ] WP-CLI installed and working (`wp --info`)
- [ ] All four plugins uploaded to `wp-content/plugins/`:
  - `reforestamos-core/`
  - `reforestamos-micrositios/`
  - `reforestamos-comunicacion/`
  - `reforestamos-empresas/`
- [ ] Block theme uploaded to `wp-content/themes/reforestamos-block-theme/`
- [ ] Theme assets built (`cd reforestamos-block-theme && npm install && npm run build`)
- [ ] Core plugin dependencies installed (`cd reforestamos-core && composer install`)
- [ ] Database backup created (manual or via hosting panel)
- [ ] Staging environment ready (never migrate on production first)

## Migration Steps

### Step 1: Verify Environment

```bash
wp reforestamos check
```

This validates WordPress version, PHP version, database connectivity, and write permissions. Fix any reported issues before proceeding.

### Step 2: Review What Will Be Migrated

```bash
wp reforestamos stats
```

Review the output to understand:
- How many posts/pages will be affected
- Which shortcodes will be converted
- Which shortcodes cannot be auto-converted

### Step 3: Run a Dry Run

```bash
wp reforestamos migrate --dry-run --verbose
```

This simulates the entire migration without making changes. Review the output carefully:
- Check for errors or warnings
- Verify shortcode conversion mappings look correct
- Note any items flagged for manual review

### Step 4: Activate Plugins (in order)

```bash
# 1. Core plugin first (provides CPTs and taxonomies)
wp plugin activate reforestamos-core

# 2. Independent plugins (any order)
wp plugin activate reforestamos-micrositios
wp plugin activate reforestamos-comunicacion

# 3. Empresas last (depends on Core)
wp plugin activate reforestamos-empresas
```

### Step 5: Run the Migration

```bash
wp reforestamos migrate --verbose
```

The system will:
1. Create an automatic database backup
2. Migrate custom fields to the new structure
3. Convert shortcodes to Gutenberg blocks
4. Update page template assignments
5. Generate a detailed migration report

### Step 6: Activate the Block Theme

```bash
wp theme activate reforestamos-block-theme
```

### Step 7: Verify the Migration

Check these critical areas:

1. **Homepage** — Does it render correctly?
2. **Navigation** — Do menus work?
3. **Custom Post Types** — Are Empresas, Eventos, Integrantes, Boletín visible in admin?
4. **Custom Fields** — Open a few posts of each CPT and verify field data
5. **Shortcodes** — Visit pages that had shortcodes and verify block rendering
6. **Maps** — Test `[arboles-ciudades]` and `[red-oja]` pages
7. **Forms** — Submit a test contact form
8. **Media** — Verify images display correctly
9. **Permalinks** — Flush permalinks: Settings → Permalinks → Save

### Step 8: Post-Migration Cleanup

```bash
# Flush rewrite rules
wp rewrite flush

# Regenerate thumbnails if needed
wp media regenerate --yes

# Clear any caches
wp cache flush
```

## Shortcode Conversion Reference

| Legacy Shortcode | Converted To | Notes |
|-----------------|-------------|-------|
| `[contact-form]` | `reforestamos/contacto` block | Attributes preserved |
| `[arboles-ciudades]` | Kept as shortcode | Rendered by Micrositios plugin |
| `[red-oja]` | Kept as shortcode | Rendered by Micrositios plugin |
| `[companies-grid]` | `reforestamos/logos-aliados` block | `columns` and `category` mapped |
| `[newsletter-subscribe]` | Kept as shortcode | Rendered by Comunicación plugin |
| `[eventos-proximos]` | `reforestamos/eventos-proximos` block | `count` and `layout` mapped |
| `[company-gallery id="X"]` | `reforestamos/galeria-tabs` block | Gallery ID mapped |

## Troubleshooting

### Problem: White screen after theme activation

**Cause:** Missing build assets or PHP error.

**Fix:**
```bash
cd wp-content/themes/reforestamos-block-theme
npm run build
```

If the issue persists, check `wp-content/debug.log` (enable `WP_DEBUG` and `WP_DEBUG_LOG` in `wp-config.php`).

### Problem: Custom Post Types not showing in admin

**Cause:** Core plugin not activated or activation failed.

**Fix:**
```bash
wp plugin activate reforestamos-core
wp rewrite flush
```

### Problem: Custom fields data missing after migration

**Cause:** Field key mapping mismatch.

**Fix:** Check the migration report in `wp-content/reforestamos-migration-reports/` for field mapping errors. You may need to manually update meta keys:

```bash
# Example: check if old meta key still exists
wp post meta list <post-id>
```

### Problem: Shortcodes showing as raw text

**Cause:** The shortcode was not converted and the plugin providing it is not active.

**Fix:** Activate the relevant plugin, or manually convert the shortcode to a block in the editor.

### Problem: Maps not loading

**Cause:** Micrositios plugin not active or JSON data files missing.

**Fix:**
```bash
wp plugin activate reforestamos-micrositios
```

Verify data files exist:
- `reforestamos-micrositios/data/arboles-ciudades.json`
- `reforestamos-micrositios/data/red-oja.json`

### Problem: Empresas plugin shows dependency warning

**Cause:** Core plugin is not active.

**Fix:**
```bash
wp plugin activate reforestamos-core
```

### Problem: Styles look broken

**Cause:** Build assets not compiled or Bootstrap CDN blocked.

**Fix:**
```bash
cd wp-content/themes/reforestamos-block-theme
npm run build
```

Check that your server/CDN doesn't block external resources (Bootstrap CSS/JS from jsdelivr.net).

### Problem: Permalinks return 404

**Fix:**
```bash
wp rewrite flush
```

Or go to Settings → Permalinks and click "Save Changes".

### Problem: Translation/language switcher not working

**Cause:** Language persistence cookie not set or i18n files missing.

**Fix:** Verify the `.pot` file exists at `languages/reforestamos.pot` and that the language persistence module is loaded in `functions.php`.

## Rollback Procedure

If the migration fails or produces unacceptable results:

### Option A: Restore from backup

```bash
# Find your backup
ls wp-content/reforestamos-backups/

# Restore using WP-CLI
wp db import wp-content/reforestamos-backups/backup-YYYY-MM-DD-HH-MM-SS.sql

# Reactivate the legacy theme
wp theme activate <legacy-theme-slug>

# Deactivate new plugins
wp plugin deactivate reforestamos-core reforestamos-micrositios reforestamos-comunicacion reforestamos-empresas
```

### Option B: Restore from hosting backup

Use your hosting provider's backup/restore feature to restore the database to the pre-migration state.

## Post-Migration Optimization

After a successful migration:

1. **Run PageSpeed Insights** on key pages to verify performance
2. **Test on mobile devices** to verify responsive design
3. **Check SEO** — verify meta tags, sitemaps, and structured data
4. **Monitor error logs** for the first 48 hours
5. **Update DNS/CDN** if switching domains or servers
6. **Remove migration system** once confirmed stable:
   ```bash
   rm -rf migration-system/
   ```

## Support

If you encounter issues not covered here:

1. Check the migration report: `wp-content/reforestamos-migration-reports/`
2. Enable debug logging: `define('WP_DEBUG', true); define('WP_DEBUG_LOG', true);`
3. Review `wp-content/debug.log`
4. Contact the development team at contacto@reforestamos.org
