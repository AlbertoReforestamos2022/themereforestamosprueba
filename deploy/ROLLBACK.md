# Rollback Plan — Reforestamos México

## Overview

This document describes the complete rollback procedure for failed deployments. Rollback restores the site to its pre-deployment state using automated backups created during each deploy.

---

## Quick Reference

```bash
# Rollback staging to latest backup
bash deploy/rollback.sh --env staging

# Rollback production to latest backup
bash deploy/rollback.sh --env production

# Rollback to a specific backup
bash deploy/rollback.sh --env production --backup pre-deploy-20250101_120000.tar.gz
```

---

## When to Rollback

Initiate a rollback immediately if any of the following occur after deployment:

| Severity | Symptom | Action |
|----------|---------|--------|
| Critical | Site returns 500 errors | Rollback immediately |
| Critical | White screen of death | Rollback immediately |
| Critical | Database connection errors | Rollback immediately |
| High | Contact forms not sending | Rollback within 15 min |
| High | Navigation or layout completely broken | Rollback within 15 min |
| Medium | Minor visual issues | Hotfix preferred over rollback |
| Low | Non-critical feature regression | Hotfix in next release |

---

## Automated Rollback Procedure

### Step 1: Run the Rollback Script

```bash
bash deploy/rollback.sh --env production
```

The script performs these steps automatically:
1. Connects to the server via SSH
2. Finds the most recent backup (or uses the specified one)
3. Enables WordPress maintenance mode
4. Restores theme and plugin files from the `.tar.gz` backup
5. Restores the database from the `.sql.gz` backup (if available)
6. Flushes WordPress caches and rewrite rules
7. Disables maintenance mode

### Step 2: Verify the Rollback

```bash
# Check health endpoint
curl -s https://reforestamos.org/wp-json/reforestamos/v1/health

# Check homepage loads
curl -s -o /dev/null -w "%{http_code}" https://reforestamos.org/
```

### Step 3: Notify the Team

- Inform stakeholders that a rollback was performed
- Document the reason for rollback
- Create a ticket to investigate and fix the issue

---

## Manual Rollback Procedure

Use this if the automated script is unavailable or fails.

### 1. SSH into the Server

```bash
ssh user@production-host
cd /path/to/wordpress
```

### 2. Enable Maintenance Mode

```bash
wp maintenance-mode activate
# Or manually:
echo '<?php $upgrading = time(); ?>' > .maintenance
```

### 3. List Available Backups

```bash
ls -lht backups/pre-deploy-*.tar.gz | head -5
ls -lht backups/pre-deploy-*.sql.gz | head -5
```

### 4. Restore Files

```bash
# Replace TIMESTAMP with the backup timestamp
tar -xzf backups/pre-deploy-YYYYMMDD_HHMMSS.tar.gz
```

### 5. Restore Database (if backup exists)

```bash
gunzip -c backups/pre-deploy-YYYYMMDD_HHMMSS.sql.gz | wp db import -
```

### 6. Flush Caches

```bash
wp cache flush
wp rewrite flush
wp transient delete --all
```

### 7. Disable Maintenance Mode

```bash
wp maintenance-mode deactivate
# Or manually:
rm -f .maintenance
```

### 8. Verify

```bash
# Check site responds
curl -I https://reforestamos.org/

# Check health endpoint
curl -s https://reforestamos.org/wp-json/reforestamos/v1/health | python3 -m json.tool
```

---

## Backup Locations

| Type | Location | Format | Created By |
|------|----------|--------|------------|
| Files | `{REMOTE_PATH}/backups/pre-deploy-*.tar.gz` | Compressed tar archive | `deploy-production.sh` |
| Database | `{REMOTE_PATH}/backups/pre-deploy-*.sql.gz` | Gzipped SQL dump | `backup.sh` |

### What's Included in File Backups

- `wp-content/themes/reforestamos-block-theme/`
- `wp-content/plugins/reforestamos-core/`
- `wp-content/plugins/reforestamos-micrositios/`
- `wp-content/plugins/reforestamos-comunicacion/`
- `wp-content/plugins/reforestamos-empresas/`

### Backup Retention

Backups older than 30 days are automatically cleaned up. Adjust `BACKUP_RETENTION_DAYS` in your environment file to change this.

---

## Partial Rollback

If only one component is affected, you can do a partial rollback:

### Rollback Theme Only

```bash
ssh user@host "cd /path/to/wordpress && \
  tar -xzf backups/pre-deploy-TIMESTAMP.tar.gz wp-content/themes/reforestamos-block-theme/"
```

### Rollback a Single Plugin

```bash
ssh user@host "cd /path/to/wordpress && \
  tar -xzf backups/pre-deploy-TIMESTAMP.tar.gz wp-content/plugins/reforestamos-core/"
```

### Rollback Database Only

```bash
ssh user@host "cd /path/to/wordpress && \
  gunzip -c backups/pre-deploy-TIMESTAMP.sql.gz | wp db import -"
```

---

## Testing Rollback on Staging

Before any production deployment, verify the rollback procedure works:

```bash
# 1. Deploy to staging
bash deploy/deploy-staging.sh

# 2. Verify staging works
curl -s https://staging.reforestamos.org/wp-json/reforestamos/v1/health

# 3. Run rollback on staging
bash deploy/rollback.sh --env staging

# 4. Verify staging is restored
curl -s https://staging.reforestamos.org/wp-json/reforestamos/v1/health

# Or use the automated test script:
bash deploy/test-rollback.sh --env staging
```

---

## Troubleshooting

### Rollback Script Fails to Connect

- Verify SSH keys are configured: `ssh -T user@host`
- Check environment variables in `.env.production`
- Try manual SSH: `ssh user@host "ls /path/to/wordpress/backups/"`

### No Backups Found

- Backups are created during deployment; if this is the first deploy, no backups exist
- Check if backups were cleaned up (retention policy)
- Check disk space on server: `ssh user@host "df -h"`

### Site Still Broken After Rollback

- Check PHP error logs: `ssh user@host "tail -50 /path/to/wordpress/wp-content/debug.log"`
- Check Reforestamos logs: `ssh user@host "tail -50 /path/to/wordpress/wp-content/reforestamos-logs/*.log"`
- Verify WordPress core files are intact: `wp core verify-checksums`
- Check plugin activation status: `wp plugin list`

### Database Rollback Causes Issues

- If the database schema changed, a file-only rollback may cause errors
- Always rollback both files AND database together
- If only the DB was rolled back, re-deploy the matching code version

---

## Post-Rollback Actions

1. **Document the incident**: Record what went wrong and why rollback was needed
2. **Investigate root cause**: Review deployment logs and error logs
3. **Fix the issue**: Create a hotfix branch
4. **Test thoroughly**: Deploy fix to staging first
5. **Re-deploy**: Follow the normal deployment checklist

---

*Last updated: 2025*
