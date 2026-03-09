# Guía de Pruebas - Bloque Eventos Próximos

## Pre-requisitos

Para probar completamente este bloque, necesitas:

1. **WordPress 6.0+** instalado y funcionando
2. **Tema Reforestamos Block Theme** activado
3. **Plugin Reforestamos Core** activado (proporciona el CPT "Eventos")
4. Al menos 3-5 eventos de prueba creados

## Preparación del Entorno

### 1. Activar el Tema

```bash
# En WordPress admin
Apariencia > Temas > Activar "Reforestamos Block Theme"
```

### 2. Verificar Compilación

```bash
cd reforestamos-block-theme
npm run build
```

Verificar que no hay errores y que se generaron los archivos:
- `build/index.js`
- `build/style-index.css`
- `blocks/eventos-proximos/frontend.js` (se carga directamente)

### 3. Crear Eventos de Prueba

Una vez que el Plugin Core esté activo:

1. Ir a **Eventos > Añadir Nuevo**
2. Crear al menos 5 eventos con:
   - Título descriptivo
   - Contenido/descripción
   - Imagen destacada
   - Custom fields:
     - `fecha_evento`: Fecha futura (ej: 2024-12-25)
     - `ubicacion`: Ubicación del evento (ej: "Bosque de Chapultepec, CDMX")

Ejemplo de eventos:
- "Reforestación Comunitaria en Xochimilco" - 2024-12-15
- "Taller de Educación Ambiental" - 2024-12-20
- "Jornada de Limpieza de Ríos" - 2025-01-10
- "Plantación de Árboles Nativos" - 2025-01-15
- "Evento Pasado de Prueba" - 2024-01-01 (para probar filtro)

## Pruebas en el Editor

### Test 1: Insertar el Bloque

1. Crear una nueva página o post
2. Hacer clic en el botón "+" para agregar bloque
3. Buscar "Eventos Próximos" o "eventos"
4. Insertar el bloque

**Resultado esperado:**
- El bloque aparece en el editor
- Se muestra el título "Próximos Eventos"
- Si hay eventos, se muestran en formato de tarjetas
- Si no hay eventos, se muestra mensaje informativo

### Test 2: Configurar Número de Eventos

1. Seleccionar el bloque
2. Abrir el panel de Inspector (sidebar derecha)
3. Ajustar "Número de eventos" de 1 a 12

**Resultado esperado:**
- El número de eventos mostrados cambia dinámicamente
- El slider funciona correctamente
- No se muestran más eventos de los disponibles

### Test 3: Cambiar Layout

1. En el panel de Inspector, cambiar "Diseño"
2. Probar cada opción:
   - Tarjetas
   - Lista
   - Grid

**Resultado esperado:**
- El diseño cambia inmediatamente en el editor
- Cada layout muestra la información correctamente
- Las imágenes se cargan (si están disponibles)

### Test 4: Mostrar Eventos Pasados

1. Activar "Mostrar eventos pasados"
2. Verificar que aparecen eventos con fechas pasadas

**Resultado esperado:**
- Se muestran eventos pasados además de futuros
- Los eventos siguen ordenados por fecha (ascendente)

## Pruebas en el Frontend

### Test 5: Renderizado Básico

1. Publicar la página con el bloque
2. Ver la página en el frontend
3. Verificar que se muestra el spinner de carga inicialmente
4. Verificar que los eventos se cargan después

**Resultado esperado:**
- Spinner de carga aparece brevemente
- Los eventos se cargan vía AJAX
- El diseño es responsive
- Las imágenes tienen lazy loading

### Test 6: Responsive Design

Probar en diferentes tamaños de pantalla:

**Desktop (>1200px):**
- Layout Cards: 3 columnas
- Layout Grid: 3 columnas
- Layout List: Horizontal con fecha, info y botón

**Tablet (768px - 1199px):**
- Layout Cards: 2 columnas
- Layout Grid: 2 columnas
- Layout List: Horizontal

**Mobile (<768px):**
- Layout Cards: 1 columna
- Layout Grid: 1 columna
- Layout List: Vertical (elementos apilados)

### Test 7: Interactividad

1. Hacer hover sobre las tarjetas
2. Hacer clic en "Ver detalles"
3. Verificar que el enlace lleva a la página del evento

**Resultado esperado:**
- Efecto hover funciona (elevación de tarjeta)
- Enlaces funcionan correctamente
- Navegación es fluida

### Test 8: Performance

1. Abrir DevTools > Network
2. Recargar la página
3. Verificar las peticiones

**Resultado esperado:**
- Solo una petición a la REST API
- Imágenes se cargan con lazy loading
- No hay errores en consola
- Tiempo de carga < 2 segundos

## Pruebas de REST API

### Test 9: Verificar Endpoint

Abrir en el navegador o usar curl:

```bash
# Endpoint estándar de WordPress
curl https://tu-sitio.com/wp-json/wp/v2/eventos?per_page=3&_embed=true

# O endpoint personalizado (si está configurado)
curl https://tu-sitio.com/wp-json/reforestamos/v1/eventos/upcoming?count=3
```

**Resultado esperado:**
- Respuesta JSON con array de eventos
- Cada evento incluye: id, title, excerpt, link, date, meta, _embedded
- Imagen destacada en _embedded['wp:featuredmedia']

### Test 10: Filtros de API

Probar diferentes parámetros:

```bash
# Solo eventos futuros
curl "https://tu-sitio.com/wp-json/wp/v2/eventos?meta_query[0][key]=fecha_evento&meta_query[0][value]=2024-12-01&meta_query[0][compare]=>="

# Ordenar por fecha
curl "https://tu-sitio.com/wp-json/wp/v2/eventos?orderby=meta_value&meta_key=fecha_evento&order=asc"
```

## Pruebas de Fallback

### Test 11: Sin Plugin Core

1. Desactivar el Plugin Core
2. Recargar la página con el bloque

**Resultado esperado:**
- En el editor: Mensaje informativo sobre CPT no disponible
- En el frontend: Mensaje de error amigable
- No hay errores de JavaScript en consola

### Test 12: Sin Eventos

1. Eliminar todos los eventos
2. Recargar la página

**Resultado esperado:**
- Mensaje: "No hay eventos disponibles en este momento"
- No hay errores
- El diseño se mantiene

### Test 13: Sin Imágenes Destacadas

1. Crear eventos sin imagen destacada
2. Verificar renderizado

**Resultado esperado:**
- Los eventos se muestran sin imagen
- El layout se adapta correctamente
- No hay espacios vacíos extraños

## Pruebas de Accesibilidad

### Test 14: Navegación por Teclado

1. Usar Tab para navegar
2. Verificar que todos los enlaces son accesibles
3. Verificar que el foco es visible

**Resultado esperado:**
- Todos los botones/enlaces son accesibles con Tab
- El foco es claramente visible
- Enter activa los enlaces

### Test 15: Screen Reader

1. Usar NVDA o JAWS
2. Navegar por el bloque

**Resultado esperado:**
- Los títulos se anuncian correctamente
- Los enlaces tienen texto descriptivo
- Las imágenes tienen alt text

## Pruebas de Compatibilidad

### Test 16: Navegadores

Probar en:
- Chrome/Edge (Chromium)
- Firefox
- Safari (si es posible)

**Resultado esperado:**
- Funciona correctamente en todos los navegadores
- Los estilos se aplican correctamente
- No hay errores de JavaScript

### Test 17: Múltiples Bloques

1. Insertar 2-3 bloques de eventos en la misma página
2. Configurar cada uno con diferentes opciones

**Resultado esperado:**
- Cada bloque funciona independientemente
- No hay conflictos entre bloques
- Cada uno carga sus propios eventos

## Checklist de Verificación

- [ ] Bloque aparece en el inserter
- [ ] Configuración en Inspector funciona
- [ ] Los 3 layouts se renderizan correctamente
- [ ] Eventos se cargan vía REST API
- [ ] Responsive design funciona en mobile/tablet/desktop
- [ ] Lazy loading de imágenes funciona
- [ ] Enlaces a eventos funcionan
- [ ] Efectos hover funcionan
- [ ] Fallback sin CPT funciona
- [ ] Fallback sin eventos funciona
- [ ] Navegación por teclado funciona
- [ ] Compatible con navegadores principales
- [ ] No hay errores en consola
- [ ] Performance es aceptable (<2s carga)

## Problemas Conocidos y Soluciones

### Problema: Eventos no se cargan

**Solución:**
1. Verificar que el Plugin Core está activo
2. Verificar que el CPT "Eventos" está registrado
3. Verificar que hay eventos publicados
4. Verificar en DevTools > Network si la petición a la API falla

### Problema: Imágenes no se muestran

**Solución:**
1. Verificar que los eventos tienen imagen destacada
2. Verificar que `_embed=true` está en la petición API
3. Verificar permisos de media library

### Problema: Estilos no se aplican

**Solución:**
1. Ejecutar `npm run build` de nuevo
2. Limpiar caché del navegador
3. Verificar que `style-index.css` se carga en el frontend

## Reporte de Bugs

Si encuentras un bug, reporta:
1. Descripción del problema
2. Pasos para reproducir
3. Resultado esperado vs resultado actual
4. Navegador y versión
5. Versión de WordPress
6. Screenshots o video (si es posible)
7. Errores de consola (si hay)
