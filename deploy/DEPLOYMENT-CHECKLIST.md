# Deployment Checklist — Reforestamos México

Use this checklist for every production deployment. Copy the checklist into your deployment ticket or PR and check off each item.

---

## Pre-Deployment

### Code Quality
- [ ] All feature branches merged to `main`
- [ ] No merge conflicts remaining
- [ ] Code review completed and approved
- [ ] ESLint passes: `npm run lint:js`
- [ ] PHP_CodeSniffer passes: `composer run lint:php`

### Testing
- [ ] PHPUnit tests pass: `composer run test`
- [ ] JavaScript tests pass: `npm test`
- [ ] Manual smoke test on staging environment
- [ ] Contact form submission works
- [ ] Newsletter subscription works
- [ ] Micrositios maps load correctly
- [ ] Company grid and galleries display properly
- [ ] Language switcher works (ES ↔ EN)

### Security
- [ ] Security audit passes: `bash deploy/security-audit.sh`
- [ ] No hardcoded credentials in codebase
- [ ] `.env` files excluded from version control
- [ ] All form inputs sanitized
- [ ] All outputs escaped
- [ ] Nonce verification on all forms and AJAX

### Build
- [ ] Production build succeeds: `bash deploy/build.sh`
- [ ] No source maps in `build/` directory
- [ ] CSS and JS are minified
- [ ] Asset manifests generated for cache busting

### Configuration
- [ ] `.env.production` has correct values
- [ ] Database credentials verified
- [ ] SMTP settings verified
- [ ] Google Analytics Measurement ID set
- [ ] Sentry DSN configured (optional but recommended)
- [ ] DeepL API key configured (if using translations)

### Backup
- [ ] Database backup created: `bash deploy/backup.sh --env production --db-only`
- [ ] File backup created: `bash deploy/backup.sh --env production --files-only`
- [ ] Backup files verified (non-zero size)
- [ ] Rollback procedure reviewed

### Communication
- [ ] Stakeholders notified of deployment window
- [ ] Maintenance window scheduled (if needed)
- [ ] Support team aware of changes

---

## Deployment Steps

1. **Create backups**
   ```bash
   bash deploy/backup.sh --env production
   ```

2. **Run production build**
   ```bash
   bash deploy/build.sh
   ```

3. **Run security audit**
   ```bash
   bash deploy/security-audit.sh
   ```

4. **Deploy to production**
   ```bash
   bash deploy/deploy-production.sh
   ```

5. **Verify health check**
   ```bash
   curl -s https://reforestamos.org/wp-json/reforestamos/v1/health
   ```

---

## Post-Deployment Verification

### Immediate (within 5 minutes)
- [ ] Site loads without errors
- [ ] Health check endpoint returns `200 OK`
- [ ] Homepage renders correctly
- [ ] Navigation works on desktop and mobile
- [ ] No JavaScript console errors
- [ ] CSS loads properly (no unstyled content)

### Functional (within 30 minutes)
- [ ] Contact form sends email successfully
- [ ] Newsletter subscription form works
- [ ] Micrositios maps load and display markers
- [ ] Company logos grid displays correctly
- [ ] Search functionality works
- [ ] Events calendar displays upcoming events
- [ ] Language switcher toggles between ES and EN
- [ ] Block editor works for content editors
- [ ] Custom blocks render in editor and frontend

### Performance
- [ ] Page load time < 3 seconds
- [ ] No 404 errors in browser console
- [ ] Images load with lazy loading
- [ ] Google Analytics tracking fires (check Real-Time in GA4)

### Monitoring
- [ ] Error logs checked: no new critical errors
- [ ] Sentry dashboard checked (if configured)
- [ ] Uptime monitoring confirms site is up

---

## Post-Deployment Communication

- [ ] Stakeholders notified of successful deployment
- [ ] Release notes shared with team
- [ ] CHANGELOG.md updated
- [ ] Any known issues documented

---

## Rollback Criteria

Initiate rollback if any of the following occur:
- Site returns 500 errors
- Database connection failures
- Critical functionality broken (forms, navigation, content display)
- Security vulnerability discovered

**Rollback command:**
```bash
bash deploy/rollback.sh --env production
```

See [ROLLBACK.md](./ROLLBACK.md) for detailed rollback procedures.

---

## Emergency Contacts

| Role | Contact |
|------|---------|
| Lead Developer | [contact info] |
| Server Admin | [contact info] |
| Project Manager | [contact info] |

---

*Last updated: 2025*
