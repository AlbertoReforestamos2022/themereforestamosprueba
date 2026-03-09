# Header Navbar Block

Bloque de navegación principal con Bootstrap 5, logo, menú responsive y language switcher.

## Características

- **Integración con menús de WordPress**: Selecciona cualquier menú registrado en WordPress
- **Logo personalizable**: Sube y configura el logo del sitio
- **Sticky Header**: Opción para mantener el header fijo al hacer scroll
- **Transparent on Top**: Header transparente en la parte superior, sólido al hacer scroll
- **Menú responsive**: Hamburger menu para dispositivos móviles con Bootstrap 5
- **Language Switcher**: Selector de idioma ES/EN preparado para integración multiidioma
- **Submenús**: Soporte para menús desplegables de dos niveles
- **Accesibilidad**: Navegación por teclado, ARIA labels, y focus visible
- **Colores personalizables**: Fondo y texto configurables

## Atributos

### menuId (string)
- ID del menú de WordPress a mostrar
- Se selecciona desde el panel de configuración
- El menú debe estar registrado en WordPress

### logo (object)
- `url`: URL de la imagen del logo
- `alt`: Texto alternativo
- `id`: ID de la imagen en la biblioteca de medios

### sticky (boolean)
- `true`: Header se mantiene fijo al hacer scroll
- `false`: Header normal (default)

### backgroundColor (string)
- Opciones: `white`, `light`, `primary`, `dark`
- Default: `white`

### textColor (string)
- Opciones: `dark`, `light`
- Default: `dark`

### showLanguageSwitcher (boolean)
- `true`: Muestra el selector de idioma (default)
- `false`: Oculta el selector de idioma

### transparentOnTop (boolean)
- `true`: Header transparente en la parte superior
- `false`: Header con fondo sólido siempre (default)

## Uso

### En el Editor

1. Agregar el bloque "Header Navbar" desde el inserter
2. En el panel de configuración (Inspector):
   - Seleccionar un menú de WordPress
   - Subir un logo (opcional)
   - Configurar colores de fondo y texto
   - Activar/desactivar sticky header
   - Activar/desactivar transparent on top
   - Mostrar/ocultar language switcher

### En Templates

El bloque se puede usar en template parts como `header.html`:

```html
<!-- wp:reforestamos/header-navbar {"menuId":"2","sticky":true,"showLanguageSwitcher":true} /-->
```

## Integración con Menús de WordPress

El bloque utiliza `wp_nav_menu()` para renderizar el menú en el frontend. Para crear un menú:

1. Ir a **Apariencia > Menús** en el admin de WordPress
2. Crear un nuevo menú o editar uno existente
3. Agregar páginas, enlaces personalizados, etc.
4. Guardar el menú
5. Seleccionar el menú en el bloque Header Navbar

## Language Switcher

El language switcher está preparado para integración futura con sistemas multiidioma como:
- WPML
- Polylang
- Custom translation system
- DeepL integration (via Communication Plugin)

Actualmente guarda la preferencia de idioma en `localStorage` y dispara un evento personalizado `reforestamosLanguageChange` que puede ser capturado por otros scripts.

## Responsive Behavior

- **Desktop (>991px)**: Menú horizontal con submenús desplegables
- **Tablet/Mobile (≤991px)**: Hamburger menu con menú colapsable

## Accesibilidad

- Navegación por teclado completa
- ARIA labels y roles apropiados
- Focus visible en todos los elementos interactivos
- Soporte para lectores de pantalla
- Contraste de colores adecuado

## Estilos Personalizados

El bloque usa variables CSS de `theme.json` para colores:
- `--wp--preset--color--primary`
- `--wp--preset--color--secondary`
- etc.

## JavaScript Frontend

El archivo `frontend.js` maneja:
- Comportamiento sticky header
- Transparent on top con detección de scroll
- Language switcher functionality
- Mobile menu accessibility
- Submenu toggle en móvil

## PHP Server-Side Rendering

El archivo `render.php` incluye:
- Callback de renderizado para inyectar el menú de WordPress
- Custom Walker (`Reforestamos_Bootstrap_Nav_Walker`) para Bootstrap 5
- Enqueue del script frontend

## Ejemplo de Configuración

```json
{
  "menuId": "2",
  "logo": {
    "url": "https://example.com/logo.png",
    "alt": "Reforestamos México",
    "id": 123
  },
  "sticky": true,
  "backgroundColor": "white",
  "textColor": "dark",
  "showLanguageSwitcher": true,
  "transparentOnTop": false
}
```

## Requirements Validados

- **2.2**: Incluye block.json, index.js, edit.js, save.js, style.scss ✓
- **2.3**: Navegación principal implementada ✓
- **2.4**: Atributos menuId, logo, sticky implementados ✓
- **2.5**: Diseño responsive con Bootstrap 5 ✓
- **17.2**: Language switcher integrado ✓
- **18.2**: Menú responsive para móvil ✓

## Notas de Desarrollo

- El bloque requiere Bootstrap 5 JS para el collapse del menú móvil
- El menú debe estar registrado en WordPress antes de poder seleccionarlo
- El logo se optimiza automáticamente con `loading="eager"` para carga rápida
- Los submenús solo funcionan hasta 2 niveles de profundidad
