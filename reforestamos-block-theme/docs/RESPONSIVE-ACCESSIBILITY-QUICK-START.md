# Responsive & Accessibility Quick Start Guide

## Quick Reference

### Responsive Breakpoints

```scss
Mobile:       320px - 575px
Tablet:       576px - 767px
Desktop:      768px - 991px
Large:        992px - 1199px
Extra Large:  1200px+
```

### Common Classes

#### Responsive Utilities
```html
<!-- Responsive container -->
<div class="container-responsive">Content</div>

<!-- Responsive grid (1 col mobile, 2 tablet, 3 desktop, 4 xl) -->
<div class="grid-responsive">
  <div>Item 1</div>
  <div>Item 2</div>
  <div>Item 3</div>
</div>

<!-- Responsive image -->
<img src="image.jpg" class="responsive-image" alt="Description">

<!-- Hide/Show by breakpoint -->
<div class="hide-mobile">Desktop only</div>
<div class="show-mobile">Mobile only</div>
```

#### Accessibility Classes
```html
<!-- Screen reader only text -->
<span class="sr-only">Additional context for screen readers</span>

<!-- Skip to content (add at top of page) -->
<a href="#main-content" class="skip-to-content">Skip to main content</a>

<!-- Touch target (minimum 44x44px) -->
<button class="touch-target">Button</button>
```

### Mobile Navigation Setup

#### 1. Add Mobile Menu Toggle
```html
<button class="mobile-menu-toggle" aria-expanded="false" aria-label="Toggle menu">
  <span class="hamburger-line"></span>
  <span class="hamburger-line"></span>
  <span class="hamburger-line"></span>
  <span class="menu-toggle-text">Menu</span>
</button>
```

#### 2. Add Off-Canvas Menu
```html
<div class="off-canvas-menu">
  <button class="off-canvas-close" aria-label="Close menu">
    <span aria-hidden="true">×</span>
    <span class="close-text">Close menu</span>
  </button>
  <nav class="off-canvas-content">
    <ul class="mobile-nav-menu">
      <li class="menu-item">
        <a href="/">Home</a>
      </li>
      <!-- More menu items -->
    </ul>
  </nav>
</div>

<div class="off-canvas-overlay"></div>
```

#### 3. Add Desktop Navigation
```html
<nav class="desktop-navigation">
  <ul class="desktop-nav-menu">
    <li class="menu-item">
      <a href="/">Home</a>
    </li>
    <li class="menu-item menu-item-has-children">
      <a href="/about">About</a>
      <ul class="sub-menu">
        <li><a href="/about/team">Team</a></li>
        <li><a href="/about/history">History</a></li>
      </ul>
    </li>
  </ul>
</nav>
```

### Accessibility Checklist

#### Every Page Must Have:
- [ ] Skip-to-content link
- [ ] Proper heading hierarchy (h1 → h2 → h3)
- [ ] Alt text on all images
- [ ] ARIA landmarks (header, nav, main, footer)
- [ ] Language attribute on HTML tag

#### Interactive Elements Must Have:
- [ ] Minimum 44x44px touch target
- [ ] Visible focus indicator
- [ ] Keyboard accessibility
- [ ] ARIA labels where needed
- [ ] Proper button/link semantics

#### Forms Must Have:
- [ ] Associated labels for all inputs
- [ ] Required field indicators
- [ ] Error messages with role="alert"
- [ ] Success messages with role="status"
- [ ] Proper input types

### Quick Fixes

#### Fix: Missing Alt Text
```html
<!-- Bad -->
<img src="logo.png">

<!-- Good -->
<img src="logo.png" alt="Reforestamos México logo">

<!-- Decorative image -->
<img src="decoration.png" alt="" role="presentation">
```

#### Fix: Poor Color Contrast
```scss
// Bad (2.5:1 ratio)
color: #999;
background: #fff;

// Good (4.5:1 ratio)
color: #666;
background: #fff;

// Better (7:1 ratio)
color: #333;
background: #fff;
```

#### Fix: Small Touch Targets
```scss
// Bad
.button {
  padding: 0.25rem 0.5rem; // Too small
}

// Good
.button {
  min-width: 44px;
  min-height: 44px;
  padding: 0.5rem 1rem;
}
```

#### Fix: Missing Focus Indicator
```scss
// Bad
button:focus {
  outline: none; // Never do this!
}

// Good
button:focus-visible {
  outline: 2px solid #2E7D32;
  outline-offset: 2px;
}
```

### Testing Commands

```bash
# Build theme
npm run build

# Watch for changes
npm run start

# Run accessibility tests (if configured)
npm run test:a11y
```

### Browser Testing

#### Desktop
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

#### Mobile
- iOS Safari (latest 2 versions)
- Chrome Mobile (latest)

#### Screen Readers
- NVDA (Windows) - Free
- JAWS (Windows) - Paid
- VoiceOver (macOS/iOS) - Built-in

### Common Issues & Solutions

#### Issue: Menu doesn't close on mobile
**Solution:** Ensure JavaScript is loaded:
```php
// In functions.php or enqueue-assets.php
wp_enqueue_script(
    'reforestamos-responsive-nav',
    get_template_directory_uri() . '/src/js/responsive-navigation.js',
    array(),
    '1.0.0',
    true
);
```

#### Issue: Focus indicator not visible
**Solution:** Check CSS specificity:
```scss
// Make sure this comes after other styles
*:focus-visible {
  outline: 2px solid $color-primary !important;
  outline-offset: 2px !important;
}
```

#### Issue: Skip link not working
**Solution:** Ensure main content has ID:
```html
<main id="main-content" tabindex="-1">
  <!-- Content -->
</main>
```

#### Issue: Images not responsive
**Solution:** Add responsive class or CSS:
```css
img {
  max-width: 100%;
  height: auto;
}
```

### Resources

- [Full Documentation](./RESPONSIVE-ACCESSIBILITY-IMPLEMENTATION.md)
- [WCAG Quick Reference](https://www.w3.org/WAI/WCAG21/quickref/)
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.3/)
- [MDN Accessibility](https://developer.mozilla.org/en-US/docs/Web/Accessibility)

### Need Help?

1. Check the full documentation
2. Test with browser DevTools
3. Use accessibility testing tools
4. Consult WCAG guidelines
