# Bloque Eventos Próximos

Bloque Gutenberg personalizado para mostrar una lista de próximos eventos de reforestación.

## Características

- **Integración con REST API**: Carga eventos dinámicamente desde el CPT "Eventos"
- **Múltiples layouts**: Tarjetas, lista y grid
- **Configuración flexible**: Número de eventos, mostrar eventos pasados
- **Diseño responsive**: Optimizado para móvil, tablet y desktop
- **Lazy loading**: Carga de imágenes optimizada
- **Información completa**: Título, fecha, ubicación, imagen destacada, extracto

## Atributos

### count
- **Tipo**: Number
- **Default**: 3
- **Descripción**: Número de eventos a mostrar (1-12)

### showPast
- **Tipo**: Boolean
- **Default**: false
- **Descripción**: Si está activado, muestra también eventos que ya pasaron

### layout
- **Tipo**: String
- **Default**: "cards"
- **Opciones**: "cards", "list", "grid"
- **Descripción**: Tipo de diseño para mostrar los eventos

## Layouts

### Cards (Tarjetas)
Diseño de tarjetas con imagen destacada, badge de fecha, título, ubicación, extracto y botón.
- Ideal para destacar eventos importantes
- Muestra imagen destacada grande
- Badge de fecha flotante sobre la imagen
- Grid responsive (1-3 columnas según viewport)

### List (Lista)
Diseño de lista horizontal con fecha, información y botón.
- Ideal para mostrar muchos eventos de forma compacta
- Fecha destacada a la izquierda
- Información del evento en el centro
- Botón de acción a la derecha

### Grid (Cuadrícula)
Diseño de cuadrícula compacta con tarjetas pequeñas.
- Ideal para mostrar múltiples eventos
- Tarjetas más compactas que el layout "cards"
- Grid responsive (1-3 columnas según viewport)
- Imagen más pequeña

## Integración con REST API

El bloque consume la REST API de WordPress para obtener eventos:

### Endpoint esperado
```
GET /wp-json/wp/v2/eventos
```

### Parámetros de consulta
- `per_page`: Número de eventos a obtener
- `_embed`: Incluir datos embebidos (imagen destacada)
- `status`: publish
- `orderby`: meta_value (fecha_evento)
- `order`: asc
- `meta_key`: fecha_evento

### Custom fields esperados
El bloque espera que el CPT "Eventos" tenga los siguientes custom fields:
- `fecha_evento`: Fecha del evento (formato DATE)
- `ubicacion`: Ubicación del evento (texto)

### Fallback
Si el CPT "Eventos" no está disponible:
- En el editor: Muestra mensaje informativo
- En el frontend: Muestra mensaje de error amigable

## Uso en el Editor

1. Agregar el bloque "Eventos Próximos" desde el inserter
2. Configurar opciones en el panel de Inspector:
   - Ajustar número de eventos
   - Activar/desactivar eventos pasados
   - Seleccionar layout
3. Vista previa en el editor (requiere CPT "Eventos" configurado)

## Uso en Templates

```html
<!-- wp:reforestamos/eventos-proximos {"count":6,"layout":"grid"} /-->
```

## Dependencias

### WordPress
- WordPress 6.0+
- Gutenberg editor

### Plugins
- **Reforestamos Core Plugin** (futuro): Proporciona el CPT "Eventos" y custom fields

### Frontend
- Bootstrap 5 (CDN)
- Dashicons (incluido en WordPress)

## Archivos

```
eventos-proximos/
├── block.json          # Metadata del bloque
├── index.js            # Registro del bloque
├── edit.js             # Interfaz del editor
├── save.js             # Renderizado estático
├── style.scss          # Estilos del bloque
├── frontend.js         # JavaScript del frontend
└── README.md           # Documentación
```

## Estilos

Los estilos están organizados por layout:
- `.eventos-proximos-block`: Contenedor principal
- `.layout-cards`: Estilos para layout de tarjetas
- `.layout-list`: Estilos para layout de lista
- `.layout-grid`: Estilos para layout de grid

### Variables CSS utilizadas
- `--wp--preset--color--primary`: Color primario del tema
- Bootstrap 5 utility classes

## JavaScript Frontend

El archivo `frontend.js` se encarga de:
1. Detectar bloques de eventos en la página
2. Leer atributos del bloque (count, showPast, layout)
3. Hacer fetch a la REST API
4. Renderizar eventos según el layout seleccionado
5. Manejar estados de carga y error

## Requisitos cumplidos

- **Requirement 2.2**: Custom block con block.json, edit.js, save.js, style.scss
- **Requirement 2.3**: Incluido en blocks/ directory
- **Requirement 2.4**: Renderizado en editor y frontend
- **Requirement 2.5**: Compilación con Build Pipeline
- **Requirement 28.3**: Bloque para mostrar próximos eventos
- **Requirement 28.4**: Eventos ordenados por fecha

## Notas de implementación

1. **CPT "Eventos"**: El bloque está preparado para consumir el CPT cuando esté disponible (Tarea 8.2 del spec)
2. **REST API**: Usa el endpoint estándar de WordPress, pero puede adaptarse a un endpoint personalizado
3. **Performance**: Implementa lazy loading de imágenes y carga asíncrona de datos
4. **Accesibilidad**: Usa elementos semánticos y ARIA labels apropiados
5. **Responsive**: Diseño mobile-first con breakpoints de Bootstrap 5

## Próximos pasos

Una vez que el Plugin Core esté implementado (Tarea 8):
1. Verificar que el CPT "Eventos" esté registrado
2. Verificar que los custom fields estén configurados
3. Crear eventos de prueba
4. Probar el bloque en diferentes layouts
5. Verificar integración con REST API
