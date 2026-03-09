# Testing del Bloque Footer

## Checklist de Pruebas Manuales

### Funcionalidad Básica

- [ ] El bloque aparece en el insertor de bloques bajo la categoría "Reforestamos"
- [ ] El bloque se inserta correctamente en el editor
- [ ] Los valores por defecto se muestran correctamente
- [ ] El bloque se guarda correctamente
- [ ] El bloque se renderiza correctamente en el frontend

### Configuración de Columnas

- [ ] Se pueden ajustar el número de columnas (2-4)
- [ ] Los títulos de columna son editables
- [ ] El contenido de columna es editable
- [ ] Se pueden agregar enlaces a cada columna
- [ ] Se pueden editar enlaces existentes (texto y URL)
- [ ] Se pueden eliminar enlaces
- [ ] Las columnas se ajustan correctamente al cambiar el número

### Redes Sociales

- [ ] Se pueden editar las redes sociales predeterminadas
- [ ] Se pueden agregar nuevas redes sociales
- [ ] Se pueden eliminar redes sociales
- [ ] Los iconos de Font Awesome se muestran correctamente
- [ ] Los enlaces se abren en nueva pestaña
- [ ] Se puede ocultar/mostrar la sección de redes sociales

### Personalización de Colores

- [ ] El color de fondo se puede cambiar
- [ ] El color de texto se puede cambiar
- [ ] Los colores se aplican correctamente en el editor
- [ ] Los colores se aplican correctamente en el frontend

### Copyright

- [ ] El texto de copyright es editable
- [ ] El texto se muestra correctamente centrado
- [ ] El formato se preserva al guardar

### Responsive Design

#### Desktop (>768px)
- [ ] Las columnas se muestran en fila horizontal
- [ ] El espaciado entre columnas es correcto
- [ ] Los iconos sociales tienen el tamaño correcto (45px)
- [ ] Los hover effects funcionan en enlaces
- [ ] Los hover effects funcionan en iconos sociales

#### Tablet (576px - 768px)
- [ ] Las columnas se distribuyen en 2 columnas
- [ ] El contenido está centrado
- [ ] Los iconos sociales se ajustan (40px)
- [ ] El espaciado es apropiado

#### Mobile (<576px)
- [ ] Las columnas se apilan verticalmente
- [ ] Todo el contenido está centrado
- [ ] Los iconos sociales son más pequeños
- [ ] El padding se reduce apropiadamente
- [ ] Los enlaces son fáciles de tocar (touch targets)

### Accesibilidad

- [ ] Los enlaces sociales tienen aria-label
- [ ] Los enlaces externos tienen rel="noopener noreferrer"
- [ ] La navegación por teclado funciona correctamente
- [ ] El contraste de colores es suficiente (4.5:1 mínimo)
- [ ] Los screen readers pueden navegar el contenido

### Integración con Bootstrap

- [ ] El grid de Bootstrap funciona correctamente
- [ ] Las clases de columna se aplican correctamente
- [ ] El contenedor responsive funciona
- [ ] No hay conflictos de estilos

### Integración con Font Awesome

- [ ] Los iconos de Font Awesome se cargan
- [ ] Los iconos se muestran correctamente
- [ ] Los iconos comunes funcionan (facebook, twitter, instagram, etc.)
- [ ] Los iconos personalizados funcionan

### Performance

- [ ] El bloque no causa lag en el editor
- [ ] La compilación incluye el bloque correctamente
- [ ] Los estilos se cargan solo cuando el bloque está presente
- [ ] No hay errores en la consola del navegador

### Compatibilidad

- [ ] Funciona en Chrome
- [ ] Funciona en Firefox
- [ ] Funciona en Safari
- [ ] Funciona en Edge
- [ ] Funciona en dispositivos móviles

## Casos de Prueba Específicos

### Caso 1: Footer con 2 columnas
1. Insertar bloque Footer
2. Cambiar columnCount a 2
3. Editar contenido de ambas columnas
4. Agregar 3 enlaces a cada columna
5. Verificar renderizado en frontend

**Resultado esperado**: 2 columnas de igual ancho con enlaces funcionando

### Caso 2: Footer con 4 columnas
1. Insertar bloque Footer
2. Cambiar columnCount a 4
3. Editar contenido de las 4 columnas
4. Agregar enlaces variados
5. Verificar responsive en móvil

**Resultado esperado**: 4 columnas en desktop, apiladas en móvil

### Caso 3: Footer sin redes sociales
1. Insertar bloque Footer
2. Desactivar showSocial
3. Guardar y verificar frontend

**Resultado esperado**: Sección de redes sociales no se muestra

### Caso 4: Footer con colores personalizados
1. Insertar bloque Footer
2. Cambiar backgroundColor a #2E7D32
3. Cambiar textColor a #F1F8E9
4. Verificar contraste y legibilidad

**Resultado esperado**: Colores aplicados correctamente con buen contraste

### Caso 5: Footer con muchos enlaces
1. Insertar bloque Footer
2. Agregar 10 enlaces a una columna
3. Verificar scroll y layout

**Resultado esperado**: Layout se mantiene, enlaces son accesibles

### Caso 6: Footer con iconos personalizados
1. Insertar bloque Footer
2. Agregar red social con icono "linkedin"
3. Agregar red social con icono "github"
4. Verificar que los iconos se muestran

**Resultado esperado**: Iconos personalizados funcionan correctamente

## Pruebas de Regresión

Después de cambios en el código, verificar:

- [ ] Los bloques existentes siguen funcionando
- [ ] Los atributos guardados se cargan correctamente
- [ ] No hay errores de migración de datos
- [ ] Los estilos no se rompen
- [ ] La compilación sigue funcionando

## Errores Conocidos

Ninguno reportado actualmente.

## Notas de Testing

- Font Awesome debe estar cargado para que los iconos funcionen
- Bootstrap 5 debe estar cargado para el grid system
- Los colores por defecto siguen la paleta del theme.json
- El bloque usa RichText para contenido editable

## Comandos de Testing

```bash
# Compilar el bloque
npm run build

# Modo desarrollo con watch
npm run start

# Verificar sintaxis
npm run lint:js

# Verificar estilos
npm run lint:css
```

## Reportar Bugs

Si encuentras un bug, reporta:
1. Pasos para reproducir
2. Comportamiento esperado
3. Comportamiento actual
4. Screenshots si es posible
5. Navegador y versión
6. Versión de WordPress
