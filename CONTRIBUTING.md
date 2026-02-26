# Guía de Contribución - Tema Reforestamos

Esta guía proporciona información sobre cómo contribuir al desarrollo del tema de WordPress de Reforestamos México.

## Código de Conducta

Todos los contribuyentes deben mantener un ambiente de respeto y colaboración. Este proyecto está dedicado a la conservación forestal y la educación ambiental.

## Proceso de Contribución

### 1. Fork y Clone

```bash
# Hacer fork del repositorio en GitHub
# Clonar tu fork
git clone https://github.com/TU-USUARIO/themereforestamosprueba.git
cd themereforestamosprueba

# Agregar el repositorio original como remote
git remote add upstream https://github.com/AlbertoReforestamos2022/themereforestamosprueba.git
```

### 2. Crear una Rama

Usa nombres descriptivos para las ramas:

```bash
# Para nuevas funcionalidades
git checkout -b feature/nombre-funcionalidad

# Para correcciones de bugs
git checkout -b fix/descripcion-bug

# Para mejoras de rendimiento
git checkout -b perf/mejora-especifica

# Para refactorización
git checkout -b refactor/componente-a-refactorizar

# Para documentación
git checkout -b docs/actualizacion-documentacion
```

### 3. Hacer Cambios

- Escribe código limpio y bien documentado
- Sigue los estándares de código del proyecto
- Prueba tus cambios localmente
- Haz commits frecuentes y descriptivos

### 4. Commits

Usa **Conventional Commits**:

```bash
# Nuevas funcionalidades
git commit -m "feat: agrega sistema de donaciones recurrentes"

# Corrección de bugs
git commit -m "fix: corrige error en validación de formulario contacto"

# Documentación
git commit -m "docs: actualiza README con instrucciones de instalación"

# Estilos/formato (sin cambios de lógica)
git commit -m "style: formatea código según PSR-12"

# Refactorización
git commit -m "refactor: simplifica lógica de custom fields empresas"

# Rendimiento
git commit -m "perf: optimiza carga de imágenes en carousel"

# Tests
git commit -m "test: agrega pruebas para validación de emails"

# Configuración
git commit -m "chore: actualiza dependencias de composer"
```

### 5. Push y Pull Request

```bash
# Actualizar tu rama con los últimos cambios de main
git fetch upstream
git rebase upstream/main

# Subir tu rama
git push origin feature/nombre-funcionalidad

# Crear Pull Request en GitHub:
# - Título descriptivo
# - Descripción detallada de los cambios
# - Referencias a issues relacionados
# - Screenshots si hay cambios visuales
```

## 📋 Estándares de Código

### PHP

**PSR-12 Extended Coding Style**

```php
<?php
/**
 * Descripción breve de la función
 *
 * Descripción más detallada si es necesaria
 *
 * @param string $param1 Descripción del parámetro
 * @param int    $param2 Descripción del parámetro
 * @return array Descripción del retorno
 */
function nombre_funcion($param1, $param2) {
    // Lógica de la función
    return $resultado;
}

// Clases
class NombreClase {
    /**
     * Propiedad pública
     *
     * @var string
     */
    public $propiedad;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Inicialización
    }
    
    /**
     * Método público
     *
     * @return void
     */
    public function metodoPublico() {
        // Lógica
    }
}
```

**Convenciones WordPress**:
- Usar prefijo `rm_` para funciones personalizadas
- Usar `get_template_directory()` para rutas
- Escapar outputs con `esc_html()`, `esc_attr()`, `esc_url()`
- Sanitizar inputs con `sanitize_text_field()`, etc.

### JavaScript

**ES6+ con mejores prácticas**

```javascript
// Usar const/let, no var
const API_URL = 'https://api.example.com';
let contador = 0;

// Arrow functions
const sumar = (a, b) => a + b;

// Destructuring
const { nombre, edad } = usuario;

// Template literals
const mensaje = `Hola ${nombre}, tienes ${edad} años`;

// Async/await
async function obtenerDatos() {
    try {
        const response = await fetch(API_URL);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
}

// Comentarios JSDoc
/**
 * Suma dos números
 * @param {number} a - Primer número
 * @param {number} b - Segundo número
 * @returns {number} La suma de a y b
 */
function sumar(a, b) {
    return a + b;
}
```

### CSS/SCSS

**BEM (Block Element Modifier)**

```scss
// Block
.card {
    padding: 1rem;
    
    // Element
    &__title {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    &__content {
        margin-top: 1rem;
    }
    
    // Modifier
    &--destacado {
        border: 2px solid #green;
    }
}

// Variables
$color-principal: #2d5016;
$color-secundario: #8bc34a;
$espaciado-base: 1rem;

// Mixins
@mixin respuesta-movil {
    @media (max-width: 768px) {
        @content;
    }
}

.elemento {
    padding: $espaciado-base;
    
    @include respuesta-movil {
        padding: $espaciado-base / 2;
    }
}
```

## 🧪 Testing

### Pruebas Manuales

Antes de crear un PR, verifica:

- ✅ El código funciona en navegadores modernos (Chrome, Firefox, Safari, Edge)
- ✅ Responsive design funciona correctamente
- ✅ No hay errores en la consola
- ✅ No hay warnings de PHP
- ✅ Los formularios validan correctamente
- ✅ Las imágenes cargan correctamente
- ✅ No hay problemas de accesibilidad evidentes

### Testing Local

```bash
# Activar WP_DEBUG en wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

# Revisar logs en wp-content/debug.log
```

## 🎨 Guía de Diseño

### Colores

```css
/* Primarios */
--verde-bosque: #2d5016;
--verde-claro: #8bc34a;
--verde-oscuro: #1b3409;

/* Secundarios */
--cafe-tierra: #5d4037;
--azul-cielo: #4fc3f7;

/* Neutrales */
--gris-claro: #f5f5f5;
--gris-medio: #9e9e9e;
--gris-oscuro: #424242;
```

### Tipografía

```css
/* Fuentes principales */
font-family: 'Open Sans', sans-serif;  /* Cuerpo */
font-family: 'Montserrat', sans-serif; /* Títulos */

/* Tamaños */
--text-xs: 0.75rem;   /* 12px */
--text-sm: 0.875rem;  /* 14px */
--text-base: 1rem;    /* 16px */
--text-lg: 1.125rem;  /* 18px */
--text-xl: 1.25rem;   /* 20px */
--text-2xl: 1.5rem;   /* 24px */
--text-3xl: 1.875rem; /* 30px */
--text-4xl: 2.25rem;  /* 36px */
```

### Espaciado

Usar múltiplos de 8px para consistencia:
- 8px, 16px, 24px, 32px, 40px, 48px, etc.

## 📝 Documentación

### Comentarios en Código

```php
/**
 * Breve descripción de lo que hace la función
 *
 * Descripción más detallada si es necesaria. Explica casos
 * de uso, consideraciones especiales, etc.
 *
 * @since 1.0.0
 * @param string $parametro Descripción del parámetro
 * @return bool True si exitoso, false si falla
 */
function rm_funcion_ejemplo($parametro) {
    // Implementación
}
```

### README y Documentación

- Mantener README.md actualizado
- Documentar nuevas funcionalidades
- Incluir ejemplos de uso
- Actualizar changelog

## 🔍 Code Review

### Checklist del Revisor

- [ ] El código sigue los estándares establecidos
- [ ] Hay comentarios donde es necesario
- [ ] No hay código duplicado
- [ ] Las funciones tienen un propósito único y claro
- [ ] Se manejan errores apropiadamente
- [ ] No hay vulnerabilidades de seguridad evidentes
- [ ] El código es eficiente
- [ ] Los nombres de variables/funciones son descriptivos
- [ ] Se actualizó la documentación si es necesario

### Checklist del Autor

Antes de crear el PR:

- [ ] He probado los cambios localmente
- [ ] El código sigue los estándares del proyecto
- [ ] He agregado comentarios donde es necesario
- [ ] He actualizado la documentación
- [ ] No hay console.logs o código de debug
- [ ] Los commits siguen Conventional Commits
- [ ] La rama está actualizada con main
- [ ] He probado en diferentes navegadores
- [ ] He verificado responsive design

## 🐛 Reporte de Bugs

### Template para Issues

```markdown
**Descripción del bug**
Una descripción clara del problema.

**Pasos para reproducir**
1. Ir a '...'
2. Hacer clic en '...'
3. Ver error

**Comportamiento esperado**
Qué debería suceder.

**Comportamiento actual**
Qué está sucediendo.

**Screenshots**
Si aplica, agregar screenshots.

**Entorno**
- WordPress Version: [ej. 6.4]
- PHP Version: [ej. 8.0]
- Navegador: [ej. Chrome 120]
- Dispositivo: [ej. Desktop, iPhone 12]

**Información adicional**
Cualquier otro contexto relevante.
```

## 💡 Sugerencias de Funcionalidades

### Template para Feature Requests

```markdown
**¿Es relacionado a un problema? Describe.**
Una descripción clara del problema que resolvería.

**Describe la solución que te gustaría**
Una descripción clara de lo que quieres que suceda.

**Describe alternativas que has considerado**
Otras soluciones o funcionalidades alternativas.

**Contexto adicional**
Cualquier otro contexto o screenshots.
```

## 📚 Recursos Útiles

### WordPress
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)

### PHP
- [PSR-12](https://www.php-fig.org/psr/psr-12/)
- [PHP The Right Way](https://phptherightway.com/)

### JavaScript
- [MDN JavaScript Guide](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide)
- [JavaScript.info](https://javascript.info/)

### CSS/SCSS
- [BEM Methodology](https://en.bem.info/methodology/)
- [Sass Documentation](https://sass-lang.com/documentation)

## ❓ ¿Preguntas?

Si tienes preguntas sobre cómo contribuir:
1. Revisa esta guía y el README
2. Busca en issues existentes
3. Crea un nuevo issue con la etiqueta "question"
4. Contacta al equipo de desarrollo

---

¡Gracias por contribuir a Reforestamos México! 🌳
