# Bloque Logos Aliados

Grid responsive de logos de aliados con efectos hover y enlaces opcionales.

## Características

- **Grid Responsive**: Configurable de 2 a 6 columnas
- **Efectos Hover**: Logos en escala de grises que se colorean al pasar el mouse
- **Enlaces Opcionales**: Los logos pueden ser clicables con enlaces a sitios externos
- **Lazy Loading**: Carga diferida de imágenes para mejor performance
- **Accesibilidad**: Textos alternativos y navegación por teclado
- **Diseño Adaptativo**: Se ajusta automáticamente en dispositivos móviles

## Atributos

### `logos` (array)
Array de objetos con información de cada logo:
- `id`: Identificador único del logo
- `imageUrl`: URL de la imagen del logo
- `imageId`: ID de la imagen en la biblioteca de medios
- `alt`: Texto alternativo para accesibilidad
- `name`: Nombre del aliado
- `url`: URL del sitio web (opcional)

### `columns` (number)
Número de columnas para el grid (2-6). Default: 4

### `linkable` (boolean)
Indica si los logos son clicables. Default: true

## Uso

1. Agregar el bloque "Logos Aliados" desde el editor
2. Configurar el número de columnas en el panel de inspección
3. Activar/desactivar enlaces clicables
4. Agregar logos usando el botón "Agregar Logo"
5. Para cada logo:
   - Seleccionar imagen de la biblioteca de medios
   - Ingresar nombre del aliado
   - Ingresar texto alternativo (importante para accesibilidad)
   - Si linkable está activo, ingresar URL del sitio web

## Diseño Responsive

- **Desktop (>992px)**: Muestra el número de columnas configurado
- **Tablet (768px-991px)**: Muestra hasta 3 columnas
- **Mobile (<768px)**: Muestra 2 columnas

## Efectos Visuales

- Logos en escala de grises (70% opacidad) por defecto
- Al hover: logos a color completo (100% opacidad)
- Elevación suave de la tarjeta al hover
- Transiciones suaves de 0.3s

## Accesibilidad

- Textos alternativos en todas las imágenes
- Focus visible para navegación por teclado
- Atributos ARIA apropiados
- Enlaces con rel="noopener noreferrer" para seguridad

## Requirements Validados

- **2.2**: Incluye block.json, edit.js, save.js, style.scss ✓
- **2.3**: Grid de logos de aliados implementado ✓
- **2.4**: Atributos logos[], columns, linkable implementados ✓
- **2.5**: Diseño responsive con Bootstrap 5 ✓
