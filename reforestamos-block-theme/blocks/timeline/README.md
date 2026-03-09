# Timeline Block

Bloque de línea de tiempo vertical para mostrar eventos cronológicos de forma visual y atractiva.

## Características

- **Línea de tiempo vertical**: Eventos alternados a izquierda y derecha en desktop
- **Diseño responsive**: En móvil, todos los eventos se alinean a un lado
- **Marcadores personalizables**: Soporte para iconos Dashicons o puntos simples
- **Imágenes opcionales**: Cada evento puede incluir una imagen
- **Animaciones**: Efectos de entrada suaves para cada evento
- **Orientación configurable**: Vertical (principal) u horizontal (futuro)

## Atributos

### events (array)
Array de objetos de eventos. Cada evento contiene:
- `date` (string): Fecha del evento (ej: "2020", "Enero 2021")
- `title` (string): Título del evento
- `description` (string): Descripción del evento
- `icon` (string): Clase de Dashicon (opcional, ej: "dashicons-calendar")
- `image` (object): Objeto con url, alt, id de la imagen (opcional)

### orientation (string)
Orientación de la línea de tiempo:
- `vertical` (default): Línea vertical con eventos alternados
- `horizontal`: Línea horizontal (implementación futura)

## Uso en el Editor

1. Agregar el bloque "Timeline" desde el inserter
2. Hacer clic en "Add Event" para agregar eventos
3. Para cada evento:
   - Ingresar la fecha
   - Escribir el título
   - Escribir la descripción
   - (Opcional) Agregar un icono Dashicon
   - (Opcional) Seleccionar una imagen
4. Configurar la orientación en el panel de Inspector Controls

## Ejemplo de Uso

```html
<!-- wp:reforestamos/timeline {"events":[{"date":"2015","title":"Fundación","description":"Inicio de Reforestamos México","icon":"dashicons-flag"},{"date":"2018","title":"Primer Millón","description":"Plantamos nuestro árbol número 1,000,000","icon":"dashicons-awards"}],"orientation":"vertical"} /-->
```

## Estilos

El bloque incluye:
- Diseño responsive con breakpoints en 768px
- Animaciones de entrada con fadeInUp
- Efectos hover en tarjetas de eventos
- Línea central con gradiente verde
- Marcadores circulares con iconos
- Sombras y transiciones suaves

## Personalización

### Colores
Los colores principales se pueden personalizar en `style.scss`:
- `#2E7D32`: Verde principal (marcadores, fechas)
- `#66BB6A`: Verde claro (gradiente)
- `#1B5E20`: Verde oscuro (títulos)

### Iconos
Se pueden usar cualquier icono de Dashicons:
- `dashicons-calendar`
- `dashicons-flag`
- `dashicons-awards`
- `dashicons-heart`
- Ver más en: https://developer.wordpress.org/resource/dashicons/

## Requisitos

- WordPress 6.0+
- Bootstrap 5 (cargado desde CDN en el tema)
- Dashicons (incluido en WordPress)

## Validación de Requisitos

Este bloque cumple con los siguientes requisitos del spec:
- **2.2**: Incluye block.json, edit.js, save.js, style.scss
- **2.3**: Atributos events[] y orientation
- **2.4**: Diseño responsive con Bootstrap
- **2.5**: Usa paleta de colores del theme.json
