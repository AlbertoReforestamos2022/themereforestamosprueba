# Lista Personalizada Block

Bloque Gutenberg personalizado para crear listas con iconos y diferentes estilos visuales.

## Características

- **Iconos personalizables**: 5 tipos de iconos (check, arrow, star, leaf, circle)
- **Estilos de lista**: 3 estilos diferentes (default, bordered, minimal)
- **Colores de iconos**: Basados en la paleta del tema (primary, secondary, accent, dark, black)
- **Gestión de items**: Agregar, editar y eliminar items de lista fácilmente
- **Vista previa en editor**: Visualización en tiempo real de cómo se verá la lista
- **Diseño responsive**: Optimizado para móvil, tablet y desktop

## Atributos

### items
- **Tipo**: `array`
- **Default**: `[]`
- **Descripción**: Array de strings con los textos de cada item de la lista

### icon
- **Tipo**: `string`
- **Default**: `'check'`
- **Opciones**: `'check'`, `'arrow'`, `'star'`, `'leaf'`, `'circle'`
- **Descripción**: Tipo de icono a mostrar antes de cada item

### listStyle
- **Tipo**: `string`
- **Default**: `'default'`
- **Opciones**: `'default'`, `'bordered'`, `'minimal'`
- **Descripción**: Estilo visual de la lista
  - `default`: Lista estándar con espaciado normal
  - `bordered`: Lista con borde y fondo
  - `minimal`: Lista compacta con iconos más pequeños

### iconColor
- **Tipo**: `string`
- **Default**: `'primary'`
- **Opciones**: `'primary'`, `'secondary'`, `'accent'`, `'dark'`, `'black'`
- **Descripción**: Color del icono basado en la paleta del tema

## Uso

### En el Editor Gutenberg

1. Agregar el bloque "Lista Personalizada" desde el inserter
2. Usar el campo de texto para agregar nuevos items
3. Presionar "Add Item" o Enter para agregar el item
4. Editar items existentes directamente en el campo de texto
5. Eliminar items con el botón de basura
6. Configurar icono, estilo y color desde el panel de Inspector Controls

### Configuración en Inspector Controls

- **Icon Type**: Seleccionar el tipo de icono
- **List Style**: Seleccionar el estilo visual
- **Icon Color**: Seleccionar el color del icono

## Estructura de Archivos

```
blocks/list/
├── block.json       # Metadata y configuración del bloque
├── index.js         # Registro del bloque
├── edit.js          # Interfaz del editor
├── save.js          # Renderizado frontend
├── style.scss       # Estilos del bloque
└── README.md        # Documentación
```

## Estilos

### Clases CSS

- `.reforestamos-list`: Contenedor principal
- `.reforestamos-list--default`: Estilo default
- `.reforestamos-list--bordered`: Estilo bordered
- `.reforestamos-list--minimal`: Estilo minimal
- `.reforestamos-list--icon-{color}`: Color del icono
- `.reforestamos-list__items`: Contenedor de items
- `.reforestamos-list__item`: Item individual
- `.reforestamos-list__icon`: Contenedor del icono SVG
- `.reforestamos-list__text`: Texto del item

### Responsive Breakpoints

- **Desktop**: > 768px - Diseño completo
- **Tablet**: 481px - 768px - Iconos y texto reducidos
- **Mobile**: ≤ 480px - Diseño compacto optimizado

## Iconos

Los iconos son SVG inline de Material Design:

- **Check**: ✓ Marca de verificación
- **Arrow**: → Flecha derecha
- **Star**: ★ Estrella
- **Leaf**: 🍃 Hoja (ideal para temas ambientales)
- **Circle**: ● Círculo relleno

## Accesibilidad

- Estructura semántica con `<ul>` y `<li>`
- Iconos decorativos (no requieren alt text)
- Contraste de colores cumple WCAG 2.1 AA
- Navegación por teclado en el editor

## Compatibilidad

- WordPress 6.0+
- Gutenberg Editor
- Todos los navegadores modernos
- Responsive design (320px - 2560px)

## Ejemplo de Uso

```javascript
// Ejemplo de atributos
{
  "items": [
    "Reforestación de áreas degradadas",
    "Educación ambiental comunitaria",
    "Monitoreo de especies nativas",
    "Restauración de ecosistemas"
  ],
  "icon": "leaf",
  "listStyle": "bordered",
  "iconColor": "primary"
}
```

## Requisitos Cumplidos

Este bloque cumple con:
- **Requirement 2.2**: Incluye block.json, edit.js, save.js, style.scss
- **Requirement 2.3**: Incluido en la lista de Custom_Blocks del tema
- **Requirement 2.4**: Renderiza correctamente en el editor (edit.js)
- **Requirement 2.5**: Renderiza correctamente en el frontend (save.js)

## Notas de Desarrollo

- Los iconos SVG están inline para mejor performance
- El bloque usa hooks de WordPress (@wordpress/element)
- Estilos compilados con SCSS
- Soporte para RTL incluido en la compilación
