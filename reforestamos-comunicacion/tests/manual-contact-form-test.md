# Test Manual - Formulario de Contacto (Tarea 20.1)

## Objetivo

Verificar que el shortcode `[contact-form]` se renderiza correctamente y muestra todos los campos requeridos.

## Pre-requisitos

1. WordPress instalado y funcionando
2. Plugin "Reforestamos Comunicación" activado
3. Tema con Bootstrap 5 cargado (o el tema Reforestamos Block Theme)

## Pasos de Prueba

### Test 1: Renderizado Básico del Shortcode

**Pasos:**
1. Ir a Páginas > Añadir nueva
2. Crear una página de prueba llamada "Test Formulario Contacto"
3. En el editor de bloques, añadir un bloque "Shortcode"
4. Insertar: `[contact-form]`
5. Publicar la página
6. Visitar la página en el frontend

**Resultado Esperado:**
- ✅ El formulario se muestra correctamente
- ✅ Aparece el título "Contáctanos"
- ✅ Se muestran 4 campos: Nombre, Email, Asunto, Mensaje
- ✅ Todos los campos tienen el asterisco (*) de requerido
- ✅ El botón dice "Enviar Mensaje"
- ✅ Los campos tienen placeholders en español
- ✅ El formulario tiene estilos de Bootstrap 5

### Test 2: Shortcode con Atributos Personalizados

**Pasos:**
1. Editar la página de prueba
2. Cambiar el shortcode a: `[contact-form title="Envíanos un mensaje" button_text="Enviar Consulta"]`
3. Actualizar la página
4. Visitar la página en el frontend

**Resultado Esperado:**
- ✅ El título cambia a "Envíanos un mensaje"
- ✅ El botón cambia a "Enviar Consulta"
- ✅ Los demás elementos permanecen igual

### Test 3: Verificación de Campos HTML

**Pasos:**
1. En el frontend, inspeccionar el HTML del formulario (F12)
2. Verificar los siguientes elementos:

**Resultado Esperado:**

Campo Nombre:
```html
<input type="text" class="form-control" id="contact-nombre" name="nombre" required>
```

Campo Email:
```html
<input type="email" class="form-control" id="contact-email" name="email" required>
```

Campo Asunto:
```html
<input type="text" class="form-control" id="contact-asunto" name="asunto" required>
```

Campo Mensaje:
```html
<textarea class="form-control" id="contact-mensaje" name="mensaje" rows="5" required></textarea>
```

Nonce Field:
```html
<input type="hidden" name="contact_form_nonce" value="[token]">
```

Contenedor de mensajes:
```html
<div class="form-messages"></div>
```

### Test 4: Verificación de Clases CSS

**Pasos:**
1. Inspeccionar el contenedor principal del formulario

**Resultado Esperado:**
- ✅ El contenedor tiene la clase `reforestamos-contact-form`
- ✅ El formulario tiene la clase `contact-form`
- ✅ Los campos usan clases de Bootstrap: `form-control`, `form-label`, `mb-3`
- ✅ El botón tiene las clases `btn btn-primary`

### Test 5: Validación HTML5

**Pasos:**
1. En el frontend, intentar enviar el formulario vacío
2. Verificar que el navegador muestra mensajes de validación HTML5

**Resultado Esperado:**
- ✅ El navegador previene el envío del formulario
- ✅ Aparecen mensajes como "Por favor, rellena este campo"
- ✅ Los campos requeridos están marcados visualmente

### Test 6: Múltiples Instancias del Shortcode

**Pasos:**
1. Editar la página de prueba
2. Añadir dos shortcodes en la misma página:
   ```
   [contact-form title="Formulario 1"]
   
   [contact-form title="Formulario 2" button_text="Enviar 2"]
   ```
3. Actualizar y visitar la página

**Resultado Esperado:**
- ✅ Ambos formularios se muestran correctamente
- ✅ Cada uno tiene su título y botón personalizado
- ✅ No hay conflictos de IDs o estilos

### Test 7: Compatibilidad con el Editor de Bloques

**Pasos:**
1. En el editor de bloques, verificar que el shortcode se muestra correctamente
2. Cambiar entre vista de edición y vista previa

**Resultado Esperado:**
- ✅ El shortcode se renderiza en la vista previa del editor
- ✅ No hay errores en la consola del navegador
- ✅ El formulario es editable (se puede cambiar el shortcode)

### Test 8: Responsive Design

**Pasos:**
1. Visitar la página en diferentes tamaños de pantalla:
   - Desktop (1920px)
   - Tablet (768px)
   - Mobile (375px)

**Resultado Esperado:**
- ✅ El formulario se adapta correctamente a todos los tamaños
- ✅ Los campos ocupan el 100% del ancho en móvil
- ✅ El botón es accesible y clickeable en todos los tamaños
- ✅ Los textos son legibles en todas las resoluciones

## Verificación de Integración

### Archivos Creados

Verificar que existen los siguientes archivos:

- ✅ `includes/class-contact-form.php`
- ✅ `templates/forms/contact-form-template.php`
- ✅ `docs/CONTACT-FORM-USAGE.md`

### Integración en el Plugin Principal

Verificar en `includes/class-reforestamos-comunicacion.php`:

1. En el método `load_includes()`:
```php
require_once REFORESTAMOS_COMM_PATH . 'includes/class-contact-form.php';
```

2. En el método `init_components()`:
```php
Reforestamos_Contact_Form::get_instance();
```

### JavaScript Handler

Verificar en `assets/js/frontend.js` que existe la función:
```javascript
function initContactForms() {
    $('.reforestamos-contact-form form').on('submit', function(e) {
        // Handler code
    });
}
```

## Notas

- **IMPORTANTE**: Esta prueba solo verifica el renderizado del formulario (Tarea 20.1)
- El envío del formulario NO funcionará todavía (se implementará en Tarea 20.2 y 20.3)
- Al intentar enviar el formulario, puede aparecer un error porque el endpoint AJAX aún no está implementado
- Esto es esperado y correcto para esta fase de implementación

## Checklist de Completitud - Tarea 20.1

- [x] Clase `Reforestamos_Contact_Form` creada con patrón singleton
- [x] Shortcode `[contact-form]` registrado
- [x] Template HTML creado en `templates/forms/contact-form-template.php`
- [x] Campos implementados: nombre, email, asunto, mensaje
- [x] Campos marcados como requeridos
- [x] Nonce de seguridad incluido
- [x] Área para mensajes de respuesta incluida
- [x] Clase CSS `reforestamos-contact-form` aplicada
- [x] Estilos Bootstrap 5 aplicados
- [x] Atributos del shortcode sanitizados
- [x] Textos traducibles con text domain correcto
- [x] Integración en `class-reforestamos-comunicacion.php`
- [x] Documentación creada

## Requirements Verificados

- **Requirement 9.1**: ✅ THE Communication_Plugin SHALL provide a shortcode [contact-form] for embedding contact forms
- **Requirement 9.2**: ✅ WHEN a contact form is rendered, THE Frontend SHALL display fields for: nombre, email, asunto, mensaje

## Resultado Final

Si todos los tests pasan, la Tarea 20.1 está **COMPLETA** ✅
