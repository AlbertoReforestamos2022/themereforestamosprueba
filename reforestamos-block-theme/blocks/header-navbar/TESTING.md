# Testing Guide - Header Navbar Block

## Pruebas Manuales

### 1. Pruebas en el Editor

#### 1.1 Inserción del Bloque
- [ ] Abrir el editor de WordPress
- [ ] Buscar "Header Navbar" en el inserter
- [ ] Verificar que el bloque aparece en la categoría "Reforestamos"
- [ ] Insertar el bloque
- [ ] Verificar que se muestra el preview del editor

#### 1.2 Configuración del Menú
- [ ] Abrir el panel de Inspector Controls
- [ ] Verificar que aparece el selector de menú
- [ ] Seleccionar un menú existente
- [ ] Verificar que el preview muestra los items del menú
- [ ] Cambiar a otro menú
- [ ] Verificar que el preview se actualiza

#### 1.3 Configuración del Logo
- [ ] Hacer clic en "Select Logo"
- [ ] Seleccionar una imagen de la biblioteca de medios
- [ ] Verificar que el logo se muestra en el preview
- [ ] Hacer clic en "Change Logo"
- [ ] Seleccionar otra imagen
- [ ] Verificar que el logo se actualiza
- [ ] Hacer clic en "Remove"
- [ ] Verificar que el logo se elimina

#### 1.4 Configuración de Apariencia
- [ ] Cambiar el color de fondo (white, light, primary, dark)
- [ ] Verificar que el preview refleja el cambio
- [ ] Cambiar el color de texto (dark, light)
- [ ] Verificar que el texto cambia de color
- [ ] Activar "Sticky Header"
- [ ] Verificar que aparece el badge "Sticky" en el preview
- [ ] Activar "Transparent on Top"
- [ ] Verificar que aparece el badge "Transparent on Top"

#### 1.5 Language Switcher
- [ ] Desactivar "Show Language Switcher"
- [ ] Verificar que los botones ES/EN desaparecen del preview
- [ ] Activar "Show Language Switcher"
- [ ] Verificar que los botones ES/EN aparecen

### 2. Pruebas en el Frontend

#### 2.1 Renderizado Básico
- [ ] Guardar la página con el bloque
- [ ] Ver la página en el frontend
- [ ] Verificar que el header se renderiza correctamente
- [ ] Verificar que el logo se muestra
- [ ] Verificar que el menú se muestra con todos los items

#### 2.2 Navegación
- [ ] Hacer clic en cada item del menú
- [ ] Verificar que la navegación funciona
- [ ] Verificar que el item activo tiene el estilo correcto
- [ ] Si hay submenús, hacer hover sobre items con hijos
- [ ] Verificar que los submenús se despliegan

#### 2.3 Sticky Header
- [ ] Con sticky activado, hacer scroll hacia abajo
- [ ] Verificar que el header se mantiene fijo en la parte superior
- [ ] Verificar que tiene sombra cuando está sticky
- [ ] Hacer scroll hacia arriba
- [ ] Verificar que el header sigue fijo

#### 2.4 Transparent on Top
- [ ] Con transparent on top activado, verificar que el header es transparente al inicio
- [ ] Hacer scroll hacia abajo
- [ ] Verificar que el header se vuelve sólido
- [ ] Hacer scroll hacia arriba
- [ ] Verificar que el header vuelve a ser transparente

#### 2.5 Language Switcher
- [ ] Hacer clic en el botón "ES"
- [ ] Verificar que se marca como activo
- [ ] Abrir la consola del navegador
- [ ] Hacer clic en el botón "EN"
- [ ] Verificar que se marca como activo
- [ ] Verificar en la consola que se dispara el evento
- [ ] Verificar en localStorage que se guarda la preferencia

### 3. Pruebas Responsive

#### 3.1 Desktop (>991px)
- [ ] Abrir en pantalla de escritorio
- [ ] Verificar que el menú se muestra horizontalmente
- [ ] Verificar que el hamburger menu NO se muestra
- [ ] Verificar que los submenús se despliegan al hacer hover

#### 3.2 Tablet (768px - 991px)
- [ ] Redimensionar a 768px
- [ ] Verificar que el hamburger menu aparece
- [ ] Hacer clic en el hamburger menu
- [ ] Verificar que el menú se despliega
- [ ] Verificar que el menú es vertical
- [ ] Hacer clic fuera del menú
- [ ] Verificar que el menú se cierra

#### 3.3 Mobile (<768px)
- [ ] Redimensionar a 375px
- [ ] Verificar que el logo se ajusta al tamaño
- [ ] Verificar que el hamburger menu funciona
- [ ] Verificar que el menú ocupa todo el ancho
- [ ] Verificar que los items son fáciles de tocar

### 4. Pruebas de Accesibilidad

#### 4.1 Navegación por Teclado
- [ ] Usar Tab para navegar por el menú
- [ ] Verificar que todos los items son accesibles
- [ ] Verificar que el focus es visible
- [ ] Presionar Enter en un item
- [ ] Verificar que navega correctamente
- [ ] Presionar Escape con el menú móvil abierto
- [ ] Verificar que el menú se cierra

#### 4.2 Screen Reader
- [ ] Activar un screen reader (NVDA, JAWS, VoiceOver)
- [ ] Navegar por el header
- [ ] Verificar que los ARIA labels se leen correctamente
- [ ] Verificar que el estado del menú móvil se anuncia
- [ ] Verificar que los submenús se anuncian correctamente

#### 4.3 Contraste de Colores
- [ ] Usar una herramienta de contraste (ej: WAVE)
- [ ] Verificar que el contraste es al menos 4.5:1
- [ ] Probar con diferentes combinaciones de colores
- [ ] Verificar que el texto es legible

### 5. Pruebas de Performance

#### 5.1 Carga de Página
- [ ] Abrir DevTools > Network
- [ ] Recargar la página
- [ ] Verificar que el logo carga con `loading="eager"`
- [ ] Verificar que no hay errores de carga
- [ ] Verificar que el JavaScript se carga correctamente

#### 5.2 Scroll Performance
- [ ] Abrir DevTools > Performance
- [ ] Grabar mientras se hace scroll
- [ ] Verificar que no hay jank (saltos)
- [ ] Verificar que el scroll es suave
- [ ] Verificar que el cambio de sticky/transparent es fluido

### 6. Pruebas de Compatibilidad

#### 6.1 Navegadores
- [ ] Chrome/Edge (últimas 2 versiones)
- [ ] Firefox (últimas 2 versiones)
- [ ] Safari (últimas 2 versiones)
- [ ] Safari iOS
- [ ] Chrome Android

#### 6.2 WordPress
- [ ] WordPress 6.0
- [ ] WordPress 6.1
- [ ] WordPress 6.2+
- [ ] Verificar que el bloque se registra correctamente
- [ ] Verificar que no hay errores en la consola

### 7. Pruebas de Integración

#### 7.1 Con Otros Bloques
- [ ] Agregar el bloque Header Navbar en un template
- [ ] Agregar otros bloques debajo
- [ ] Verificar que no hay conflictos de estilos
- [ ] Verificar que el z-index funciona correctamente

#### 7.2 Con Plugins
- [ ] Activar plugins comunes (Yoast SEO, Contact Form 7, etc.)
- [ ] Verificar que no hay conflictos
- [ ] Verificar que el menú sigue funcionando

## Checklist de Requisitos

### Requirements Validados
- [x] **2.2**: Incluye block.json, index.js, edit.js, save.js, style.scss
- [x] **2.3**: Navegación principal implementada con Bootstrap 5
- [x] **2.4**: Atributos menuId, logo, sticky implementados
- [x] **2.5**: Diseño responsive con Bootstrap 5
- [x] **17.2**: Language switcher integrado
- [x] **18.2**: Menú responsive para móvil con hamburger button

### Características Implementadas
- [x] Integración con menús de WordPress
- [x] Logo personalizable
- [x] Sticky header
- [x] Transparent on top
- [x] Menú responsive
- [x] Language switcher
- [x] Submenús (2 niveles)
- [x] Accesibilidad (ARIA, keyboard navigation)
- [x] Colores personalizables
- [x] Bootstrap 5 navbar

## Problemas Conocidos

### Limitaciones
1. Los submenús solo funcionan hasta 2 niveles de profundidad
2. El language switcher es un placeholder - requiere integración con sistema multiidioma
3. El menú debe estar registrado en WordPress antes de poder seleccionarlo

### Mejoras Futuras
1. Soporte para mega menús
2. Integración completa con WPML/Polylang
3. Animaciones personalizables
4. Más opciones de estilo para el logo
5. Soporte para múltiples menús (ej: menú secundario)

## Notas de Testing

- Asegurarse de tener Bootstrap 5 JS cargado para el collapse del menú móvil
- Crear al menos un menú en WordPress antes de probar
- Probar con diferentes tamaños de logo para verificar el responsive
- Verificar que el tema tiene registrados los menús en `functions.php`
