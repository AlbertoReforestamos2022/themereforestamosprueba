# Bloque Footer

Pie de página personalizable con múltiples columnas, enlaces a redes sociales y texto de copyright.

## Características

- **Columnas configurables**: 2-4 columnas con contenido personalizable
- **Enlaces por columna**: Cada columna puede tener múltiples enlaces
- **Redes sociales**: Sección dedicada con iconos de Font Awesome
- **Copyright personalizable**: Texto de copyright editable
- **Diseño responsive**: Se adapta a móvil, tablet y desktop
- **Colores personalizables**: Fondo y texto configurables
- **Hover effects**: Efectos de transición en enlaces e iconos

## Atributos

### columns (array)
Array de objetos que definen cada columna del footer:
- `title` (string): Título de la columna
- `content` (string): Contenido de texto de la columna
- `links` (array): Array de enlaces con `text` y `url`

**Default**: 3 columnas predefinidas

### social (array)
Array de objetos con enlaces a redes sociales:
- `name` (string): Nombre de la red social
- `url` (string): URL del perfil
- `icon` (string): Nombre del icono de Font Awesome (sin prefijo 'fa-')

**Default**: Facebook, Twitter, Instagram

### copyright (string)
Texto de copyright que aparece al final del footer.

**Default**: "© 2024 Reforestamos México. Todos los derechos reservados."

### backgroundColor (string)
Color de fondo del footer en formato hexadecimal.

**Default**: "#1B5E20" (verde oscuro)

### textColor (string)
Color del texto en formato hexadecimal.

**Default**: "#FFFFFF" (blanco)

### columnCount (number)
Número de columnas a mostrar (2-4).

**Default**: 3

### showSocial (boolean)
Mostrar u ocultar la sección de redes sociales.

**Default**: true

## Uso

### En el Editor de Bloques

1. Busca "Footer" en el insertor de bloques
2. Inserta el bloque en tu template o página
3. Configura las columnas desde el panel lateral:
   - Ajusta el número de columnas (2-4)
   - Edita títulos y contenido de cada columna
   - Agrega enlaces a cada columna
4. Configura las redes sociales:
   - Edita URLs de redes sociales existentes
   - Agrega nuevas redes sociales
   - Especifica el icono de Font Awesome
5. Personaliza colores desde el panel de configuración
6. Edita el texto de copyright directamente en el bloque

### En Templates HTML

```html
<!-- wp:reforestamos/footer /-->
```

Con atributos personalizados:

```html
<!-- wp:reforestamos/footer {
  "columnCount": 4,
  "backgroundColor": "#2E7D32",
  "showSocial": true
} /-->
```

## Iconos de Redes Sociales

El bloque utiliza Font Awesome para los iconos. Iconos comunes:

- `facebook` - Facebook
- `twitter` - Twitter/X
- `instagram` - Instagram
- `linkedin` - LinkedIn
- `youtube` - YouTube
- `tiktok` - TikTok
- `github` - GitHub
- `whatsapp` - WhatsApp

**Nota**: Asegúrate de que Font Awesome esté cargado en tu sitio. Puedes agregarlo al tema o usar el CDN:

```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
```

## Diseño Responsive

### Desktop (>768px)
- Columnas en fila horizontal
- Iconos sociales de 45px
- Hover effects completos

### Tablet (576px - 768px)
- Columnas en grid de 2 columnas
- Texto centrado
- Iconos sociales de 40px

### Mobile (<576px)
- Columnas apiladas verticalmente
- Todo el contenido centrado
- Iconos sociales más pequeños
- Hover effects simplificados

## Estilos CSS

El bloque incluye estilos predefinidos en `style.scss`:

- `.reforestamos-footer`: Contenedor principal
- `.footer-column`: Cada columna individual
- `.footer-links`: Lista de enlaces
- `.footer-social`: Sección de redes sociales
- `.footer-copyright`: Sección de copyright

### Personalización CSS

Puedes sobrescribir los estilos en tu tema:

```css
.reforestamos-footer {
  padding: 4rem 0 2rem;
}

.reforestamos-footer .footer-column-title {
  font-size: 1.5rem;
  border-bottom: 2px solid currentColor;
}

.reforestamos-footer .social-link {
  width: 50px;
  height: 50px;
}
```

## Accesibilidad

- Todos los enlaces sociales incluyen `aria-label` con el nombre de la red
- Enlaces externos incluyen `rel="noopener noreferrer"`
- Contraste de colores configurable
- Navegación por teclado soportada
- Estructura semántica con `<footer>` tag

## Requisitos

- WordPress 6.0+
- Bootstrap 5 (para grid system)
- Font Awesome 6.x (para iconos sociales)

## Validación de Requirements

Este bloque cumple con los siguientes requisitos del spec:

- **2.2**: Incluye block.json, edit.js, save.js, style.scss
- **2.3**: Atributos: columns[], social[], copyright
- **2.4**: Interfaz de edición con InspectorControls
- **2.5**: Renderizado frontend con save.js
