# Cards Enlaces Block

Grid de tarjetas con enlaces para navegación o destacar secciones importantes del sitio.

## Características

- Grid responsive de tarjetas con enlaces
- Soporte para 2, 3 o 4 columnas
- Cada tarjeta puede incluir:
  - Título
  - Descripción
  - URL de enlace
  - Icono (emoji o texto)
  - Imagen destacada (opcional)
- 3 estilos de tarjeta: default, bordered, shadow
- Efectos hover en tarjetas con enlace
- Responsive: se apila en una columna en móviles
- Lazy loading de imágenes

## Atributos

### cards (array)
Array de objetos de tarjeta, cada uno con:
- `title` (string): Título de la tarjeta
- `description` (string): Descripción de la tarjeta
- `url` (string): URL del enlace
- `icon` (string): Emoji o texto para usar como icono
- `image` (object): Objeto con url, alt, id de la imagen

### columns (number)
Número de columnas en el grid (2, 3 o 4). Default: 3

### cardStyle (string)
Estilo visual de las tarjetas:
- `default`: Borde simple gris
- `bordered`: Borde verde destacado
- `shadow`: Sin borde, con sombra

## Uso

1. Insertar el bloque "Cards Enlaces" en el editor
2. Configurar columnas y estilo en el panel de inspección
3. Agregar tarjetas con el botón "Add Card"
4. Para cada tarjeta:
   - Expandir con el botón de editar
   - Completar título, descripción y URL
   - Opcionalmente agregar icono e imagen
5. Vista previa se muestra debajo del editor

## Ejemplos de uso

### Navegación a secciones principales
```
Columnas: 3
Estilo: shadow
Tarjetas:
- Título: "Nuestros Proyectos"
  Icono: 🌳
  URL: /proyectos
- Título: "Únete"
  Icono: 🤝
  URL: /unete
- Título: "Donar"
  Icono: 💚
  URL: /donar
```

### Destacar iniciativas con imágenes
```
Columnas: 2
Estilo: bordered
Tarjetas con imágenes destacadas y descripciones
```

## Responsive

- Desktop (>992px): Muestra el número de columnas configurado
- Tablet (768-992px): 4 columnas se reducen a 2
- Móvil (<768px): Todas las tarjetas se apilan en 1 columna

## Accesibilidad

- Imágenes con atributos alt
- Enlaces con rel="noopener noreferrer"
- Lazy loading de imágenes para performance
- Estructura semántica HTML5
