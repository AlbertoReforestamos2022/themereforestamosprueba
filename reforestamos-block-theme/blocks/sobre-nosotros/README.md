# Bloque Sobre Nosotros

Bloque complejo para mostrar información de la organización, incluyendo estadísticas destacadas y perfiles del equipo.

## Características

- **Sección de introducción**: Título y contenido descriptivo editable con RichText
- **Estadísticas destacadas**: Grid responsive de estadísticas con valores grandes, etiquetas e iconos opcionales
- **Perfiles de equipo**: Tarjetas con foto, nombre, cargo, biografía y enlaces a redes sociales
- **Layout flexible**: Permite mostrar solo estadísticas, solo equipo, o ambos
- **Diseño responsive**: Adaptado para móvil, tablet y desktop
- **Efectos hover**: Animaciones suaves en tarjetas
- **Accesibilidad**: Atributos ARIA y lazy loading de imágenes

## Atributos

### title (string)
Título principal de la sección "Sobre Nosotros"

### content (string)
Contenido descriptivo de la organización (RichText)

### team (array)
Array de objetos con información de miembros del equipo:
- `name` (string): Nombre del miembro
- `position` (string): Cargo o rol
- `bio` (string): Biografía breve
- `photo` (object): Objeto con url, alt, id de la imagen
- `social` (object): Enlaces a redes sociales
  - `facebook` (string): URL de Facebook
  - `twitter` (string): URL de Twitter
  - `linkedin` (string): URL de LinkedIn
  - `instagram` (string): URL de Instagram

### stats (array)
Array de objetos con estadísticas:
- `value` (string): Valor numérico (ej: "1000+", "50")
- `label` (string): Etiqueta descriptiva
- `icon` (string): Clase CSS del icono (ej: "dashicons-palmtree")

### showStats (boolean)
Controla si se muestran las estadísticas (default: true)

### showTeam (boolean)
Controla si se muestra el equipo (default: true)

## Uso

1. Insertar el bloque "Sobre Nosotros" en el editor
2. Editar el título y contenido directamente en el editor
3. Usar el panel de Inspector Controls para:
   - Agregar/editar estadísticas
   - Agregar/editar miembros del equipo
   - Subir fotos de los miembros
   - Configurar enlaces a redes sociales
   - Activar/desactivar secciones

## Estilos

El bloque utiliza:
- Variables CSS de theme.json para colores
- Bootstrap 5 grid system para layout responsive
- Transiciones CSS para efectos hover
- Breakpoints responsive en 768px y 992px

## Iconos de Redes Sociales

El bloque utiliza Font Awesome para los iconos de redes sociales. Asegúrate de que Font Awesome esté cargado en el tema:

```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
```

## Ejemplo de Uso

```javascript
// Ejemplo de atributos
{
  title: "Nuestro Equipo",
  content: "Somos un equipo comprometido con la reforestación de México",
  showStats: true,
  showTeam: true,
  stats: [
    { value: "1000+", label: "Árboles Plantados", icon: "dashicons-palmtree" },
    { value: "50", label: "Proyectos Activos", icon: "dashicons-portfolio" }
  ],
  team: [
    {
      name: "Juan Pérez",
      position: "Director",
      bio: "Experto en reforestación con 10 años de experiencia",
      photo: { url: "...", alt: "Juan Pérez", id: 123 },
      social: {
        facebook: "https://facebook.com/...",
        linkedin: "https://linkedin.com/in/..."
      }
    }
  ]
}
```

## Requirements Validados

- **2.2**: Incluye block.json, edit.js, save.js, style.scss ✓
- **2.3**: Atributos implementados: title, content, team[], stats[] ✓
- **2.4**: InspectorControls para configuración ✓
- **2.5**: Diseño responsive con Bootstrap 5 ✓
