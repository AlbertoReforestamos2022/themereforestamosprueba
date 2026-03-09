# Texto con Imagen Block

Layout de dos columnas con texto e imagen, ideal para presentar contenido con soporte visual.

## Características

- **Layout flexible**: Imagen a la izquierda o derecha del texto
- **Ancho ajustable**: Control del ancho de la columna de imagen (30%-70%)
- **Editor de texto enriquecido**: Soporte completo para formato de texto
- **Responsive**: Se adapta automáticamente a dispositivos móviles (columnas apiladas)
- **Lazy loading**: Las imágenes se cargan de forma diferida para mejor rendimiento

## Atributos

### content (string)
Contenido de texto enriquecido. Soporta:
- Párrafos
- Encabezados (h2, h3, h4)
- Listas (ordenadas y no ordenadas)
- Enlaces
- Formato de texto (negrita, cursiva, etc.)

**Default**: `""`

### image (object)
Objeto con información de la imagen:
- `url` (string): URL de la imagen
- `alt` (string): Texto alternativo
- `id` (number): ID de la imagen en la biblioteca de medios

**Default**: `{ url: "", alt: "", id: null }`

### imagePosition (string)
Posición de la imagen respecto al texto.

**Valores**: `"left"` | `"right"`  
**Default**: `"right"`

### imageWidth (string)
Ancho de la columna de imagen como porcentaje.

**Rango**: `"30%"` a `"70%"` (incrementos de 5%)  
**Default**: `"50%"`

## Uso

### En el Editor

1. Añade el bloque "Texto con Imagen" desde el inserter
2. Escribe o pega tu contenido en el área de texto
3. Haz clic en "Select Image" para elegir una imagen
4. Usa el panel de configuración (Inspector) para:
   - Cambiar la posición de la imagen (izquierda/derecha)
   - Ajustar el ancho de la imagen

### Configuración Recomendada

**Para contenido con mucho texto**:
- imageWidth: 40%
- Permite más espacio para el texto

**Para destacar la imagen**:
- imageWidth: 60%
- La imagen tiene más protagonismo

**Para balance 50/50**:
- imageWidth: 50% (default)
- Equilibrio perfecto entre texto e imagen

## Responsive

En dispositivos móviles (< 768px):
- Las columnas se apilan verticalmente
- La imagen siempre aparece debajo del texto
- Ambas columnas ocupan el 100% del ancho

## Accesibilidad

- Asegúrate de proporcionar texto alternativo descriptivo para las imágenes
- El contenido de texto mantiene jerarquía semántica correcta
- Los enlaces tienen color distintivo y subrayado

## Ejemplo de Código

```html
<!-- wp:reforestamos/texto-imagen {"imagePosition":"left","imageWidth":"40%"} -->
<div class="wp-block-reforestamos-texto-imagen reforestamos-texto-imagen image-left">
    <div class="texto-imagen-container">
        <div class="texto-imagen-content" style="width:60%">
            <div class="content-text">
                <h2>Nuestro Impacto</h2>
                <p>Desde 2012, hemos plantado más de 1 millón de árboles...</p>
            </div>
        </div>
        <div class="texto-imagen-image" style="width:40%">
            <img src="..." alt="Voluntarios plantando árboles" loading="lazy">
        </div>
    </div>
</div>
<!-- /wp:reforestamos/texto-imagen -->
```

## Estilos Personalizados

El bloque utiliza variables CSS de WordPress para colores:
- `--wp--preset--color--primary`: Color de enlaces y encabezados
- `--wp--preset--color--secondary`: Color de hover en enlaces

Puedes personalizar estos colores en `theme.json`.

## Requisitos

- WordPress 6.0+
- Bootstrap 5 (para grid responsive)
- Tema Reforestamos Block Theme

## Validación de Requisitos

Este bloque cumple con:
- **Requirement 2.2**: Incluye block.json, edit.js, save.js, style.scss
- **Requirement 2.3**: Incluido en la lista de Custom_Blocks del tema
- **Requirement 2.4**: Renderiza correctamente en el editor (edit.js)
- **Requirement 2.5**: Renderiza correctamente en el frontend (save.js)
