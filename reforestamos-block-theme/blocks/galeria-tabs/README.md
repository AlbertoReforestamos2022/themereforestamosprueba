# Bloque Galería con Tabs

## Descripción

Bloque personalizado de Gutenberg que muestra una galería de imágenes organizada en pestañas (tabs) por categorías. Incluye integración con GLightbox para visualización de imágenes en lightbox.

## Características

- **Sistema de pestañas**: Organiza las imágenes en categorías con navegación por tabs
- **Grid responsive**: Las imágenes se muestran en un grid adaptable (2 columnas en móvil, 3 en tablet, 4 en desktop)
- **Lightbox integrado**: Usa GLightbox para visualización de imágenes en pantalla completa
- **Lazy loading**: Las imágenes se cargan de forma diferida para mejorar el rendimiento
- **Efectos hover**: Overlay con icono de zoom al pasar el mouse sobre las imágenes
- **Diseño responsive**: Adaptado para todos los tamaños de pantalla

## Atributos

### galleries (array)
Array de objetos que representan cada categoría/tab de la galería. Cada objeto contiene:
- `id`: Identificador único de la categoría
- `title`: Nombre de la categoría que se muestra en el tab
- `images`: Array de objetos de imagen con:
  - `id`: ID de la imagen en WordPress
  - `url`: URL de la imagen
  - `alt`: Texto alternativo
  - `caption`: Descripción de la imagen (opcional)

### defaultTab (number)
Índice (base 0) de la pestaña que se mostrará activa por defecto. Por defecto: 0

## Uso en el Editor

1. Añade el bloque "Galería con Tabs" desde el inserter de bloques
2. Haz clic en "Añadir Primera Categoría" o usa el botón en el panel lateral
3. Escribe el nombre de la categoría en el campo de texto
4. Haz clic en "Añadir Imágenes" para seleccionar imágenes de la biblioteca de medios
5. Repite los pasos 2-4 para añadir más categorías
6. En el panel lateral, selecciona qué pestaña debe estar activa por defecto

## Dependencias

### GLightbox
El bloque requiere GLightbox para la funcionalidad de lightbox. Se carga automáticamente desde CDN:

- **CSS**: `https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css`
- **JS**: `https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js`

La inicialización de GLightbox se maneja automáticamente en `src/js/frontend.js`.

### Bootstrap 5
El bloque usa los componentes de tabs de Bootstrap 5, que ya está cargado en el tema desde CDN.

## Estructura de Archivos

```
blocks/galeria-tabs/
├── block.json          # Metadata y configuración del bloque
├── index.js            # Registro del bloque
├── edit.js             # Interfaz del editor
├── save.js             # Renderizado frontend
├── style.scss          # Estilos del bloque
└── README.md           # Esta documentación
```

## Estilos Personalizables

Los estilos del bloque usan variables CSS de WordPress (theme.json):
- `--wp--preset--color--primary`: Color principal para tabs activos
- `--wp--preset--color--secondary`: Color secundario para hover

Puedes personalizar estos colores en `theme.json` o sobrescribir los estilos en tu CSS personalizado.

## Ejemplo de Uso Programático

```javascript
// Ejemplo de estructura de datos para el atributo galleries
const galleries = [
  {
    id: 1,
    title: 'Reforestación 2023',
    images: [
      {
        id: 123,
        url: 'https://example.com/image1.jpg',
        alt: 'Plantación de árboles',
        caption: 'Jornada de reforestación en la Sierra'
      },
      // ... más imágenes
    ]
  },
  {
    id: 2,
    title: 'Eventos Comunitarios',
    images: [
      // ... imágenes
    ]
  }
];
```

## Notas de Desarrollo

- El bloque genera IDs únicos para cada instancia para evitar conflictos cuando hay múltiples galerías en la misma página
- Las imágenes usan `loading="lazy"` para optimizar el rendimiento
- El grid usa clases de Bootstrap 5: `col-6 col-md-4 col-lg-3`
- Los estilos incluyen media queries para ajustar el tamaño de los tabs en móviles

## Requisitos

- WordPress 6.0+
- PHP 7.4+
- Bootstrap 5 (cargado desde CDN)
- GLightbox 3.2.0+ (cargado desde CDN)

## Soporte

Para reportar problemas o sugerencias, contacta al equipo de desarrollo de Reforestamos México.
