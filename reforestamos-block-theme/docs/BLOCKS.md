# Custom Blocks Reference

Complete documentation for all 16 custom Gutenberg blocks in the Reforestamos Block Theme.

All blocks are registered under the `reforestamos` category and follow the WordPress Block API v2.

## Block Structure

Each block lives in `blocks/<name>/` and contains:

```
blocks/<name>/
├── block.json      # Metadata: name, attributes, supports, keywords
├── index.js        # Block registration
├── edit.js         # Editor component (React)
├── save.js         # Frontend output (static HTML)
├── style.scss      # Styles (editor + frontend)
└── frontend.js     # Optional: client-side JS for interactivity
```

---

## 1. Hero (`reforestamos/hero`)

Full-width hero section with background image, overlay, title, subtitle, and CTA button.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `title` | `string` | `""` | Main heading text |
| `subtitle` | `string` | `""` | Subheading text |
| `backgroundImage` | `object` | `{ url: "", alt: "", id: null }` | Background image object |
| `buttonText` | `string` | `"Conoce más"` | CTA button label |
| `buttonUrl` | `string` | `""` | CTA button link |
| `overlayOpacity` | `number` | `0.5` | Dark overlay opacity (0–1) |
| `textAlign` | `string` | `"center"` | Text alignment |
| `minHeight` | `string` | `"500px"` | Minimum section height |

### Supports

- Alignment: `wide`, `full`
- Anchor
- Spacing: padding, margin

### Usage Example

```html
<!-- wp:reforestamos/hero {"title":"Plantemos juntos","subtitle":"Únete a la reforestación","backgroundImage":{"url":"/hero.jpg"},"buttonText":"Participa","buttonUrl":"/eventos"} /-->
```

---

## 2. Carousel (`reforestamos/carousel`)

Image carousel powered by Bootstrap 5 carousel component.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `images` | `array` | `[]` | Array of image objects `{ url, alt, id, caption }` |
| `autoplay` | `boolean` | `true` | Auto-advance slides |
| `interval` | `number` | `5000` | Slide interval in milliseconds |

### Usage Example

```html
<!-- wp:reforestamos/carousel {"images":[{"url":"/slide1.jpg","alt":"Slide 1"},{"url":"/slide2.jpg","alt":"Slide 2"}],"autoplay":true,"interval":4000} /-->
```

---

## 3. Contacto (`reforestamos/contacto`)

Contact form block that integrates with the Comunicación plugin for form processing.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `formId` | `string` | `""` | Form identifier |
| `showPhone` | `boolean` | `true` | Show phone field |
| `showAddress` | `boolean` | `true` | Show address info |

### Notes

- Requires the Reforestamos Comunicación plugin for form submission handling
- Includes honeypot anti-spam protection
- Client-side validation via `frontend.js`

---

## 4. Documents (`reforestamos/documents`)

Downloadable documents list with file type icons and sorting.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `documents` | `array` | `[]` | Array of `{ title, url, fileType, fileSize, description }` |
| `category` | `string` | `""` | Filter by document category |
| `sortBy` | `string` | `"title"` | Sort order: `title`, `date`, `fileSize` |

---

## 5. FAQs (`reforestamos/faqs`)

Accordion-style FAQ section using Bootstrap 5 collapse component.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `faqs` | `array` | `[]` | Array of `{ question, answer }` objects |
| `openFirst` | `boolean` | `true` | Auto-open first item |
| `allowMultiple` | `boolean` | `false` | Allow multiple items open simultaneously |

### Usage Example

```html
<!-- wp:reforestamos/faqs {"faqs":[{"question":"¿Cómo puedo participar?","answer":"Visita nuestra sección de eventos..."}],"openFirst":true} /-->
```

---

## 6. Galería Tabs (`reforestamos/galeria-tabs`)

Tabbed image gallery with category filtering and lightbox support.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `galleries` | `array` | `[]` | Array of `{ tabName, images[] }` |
| `defaultTab` | `number` | `0` | Index of initially active tab |

### Notes

- Integrates with GLightbox for fullscreen image viewing
- Responsive grid layout adapts to screen size

---

## 7. Logos Aliados (`reforestamos/logos-aliados`)

Grid display of partner/ally logos with optional links.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `logos` | `array` | `[]` | Array of `{ image, alt, url, name }` |
| `columns` | `number` | `4` | Number of grid columns |
| `linkable` | `boolean` | `true` | Make logos clickable links |

---

## 8. Timeline (`reforestamos/timeline`)

Vertical timeline for displaying chronological events or milestones.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `events` | `array` | `[]` | Array of `{ date, title, description, icon }` |
| `orientation` | `string` | `"vertical"` | Timeline orientation |

---

## 9. Cards Enlaces (`reforestamos/cards-enlaces`)

Grid of link cards for navigation or resource listing.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `cards` | `array` | `[]` | Array of `{ title, description, url, icon, image }` |
| `columns` | `number` | `3` | Grid columns |
| `style` | `string` | `"default"` | Card style variant |

---

## 10. Cards Iniciativas (`reforestamos/cards-iniciativas`)

Specialized cards for displaying organizational initiatives.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `initiatives` | `array` | `[]` | Array of `{ title, description, image, url, stats }` |
| `layout` | `string` | `"grid"` | Layout: `grid` or `list` |

---

## 11. Texto Imagen (`reforestamos/texto-imagen`)

Two-column layout with rich text content and an image.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `content` | `string` | `""` | Rich text content (HTML) |
| `image` | `object` | `{}` | Image object `{ url, alt, id }` |
| `imagePosition` | `string` | `"right"` | Image position: `left` or `right` |

---

## 12. List (`reforestamos/list`)

Custom list with configurable icons and styles.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `items` | `array` | `[]` | Array of `{ text, icon }` |
| `icon` | `string` | `"check"` | Default icon for all items |
| `style` | `string` | `"default"` | List style variant |

---

## 13. Sobre Nosotros (`reforestamos/sobre-nosotros`)

Complex "About Us" section with team members and statistics.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `title` | `string` | `""` | Section title |
| `content` | `string` | `""` | Section description |
| `team` | `array` | `[]` | Array of `{ name, role, photo, bio }` |
| `stats` | `array` | `[]` | Array of `{ label, value, icon }` |

---

## 14. Header Navbar (`reforestamos/header-navbar`)

Main site navigation bar with responsive menu and language switcher.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `menuId` | `number` | `0` | WordPress menu ID |
| `logo` | `object` | `{}` | Site logo image |
| `sticky` | `boolean` | `true` | Sticky header on scroll |

### Notes

- Responsive hamburger menu on mobile (Bootstrap 5 offcanvas)
- Integrated language switcher (ES/EN)
- Server-side rendered via `render.php`

---

## 15. Footer (`reforestamos/footer`)

Multi-column site footer with social links and copyright.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `columns` | `array` | `[]` | Array of `{ title, content, links[] }` |
| `social` | `array` | `[]` | Array of `{ platform, url, icon }` |
| `copyright` | `string` | `""` | Copyright text |

---

## 16. Eventos Próximos (`reforestamos/eventos-proximos`)

Dynamic list of upcoming events fetched from the REST API.

### Attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `count` | `number` | `3` | Number of events to display |
| `showPast` | `boolean` | `false` | Include past events |
| `layout` | `string` | `"list"` | Display layout: `list` or `grid` |

### Notes

- Fetches data from `/wp-json/reforestamos/v1/eventos/upcoming`
- Requires the Reforestamos Core plugin for the Eventos CPT
- Client-side rendering via `frontend.js`

---

## Adding a New Block

1. Create directory `blocks/<name>/`
2. Add `block.json` with metadata and attributes
3. Create `edit.js` (editor UI) and `save.js` (frontend output)
4. Add `style.scss` for styling
5. Create `index.js` to register the block
6. The block is auto-registered via `inc/block-registration.php`
7. Run `npm run build` to compile

### block.json Template

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 2,
  "name": "reforestamos/<name>",
  "title": "Block Title",
  "category": "reforestamos",
  "icon": "dashicons-icon",
  "description": "Block description",
  "keywords": ["keyword1", "keyword2"],
  "textdomain": "reforestamos",
  "attributes": {},
  "supports": {
    "align": ["wide", "full"],
    "anchor": true
  },
  "editorScript": "file:./index.js",
  "style": "file:./style.scss"
}
```
