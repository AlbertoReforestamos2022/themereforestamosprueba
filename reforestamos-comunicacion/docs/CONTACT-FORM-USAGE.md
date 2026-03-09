# Contact Form - Guía de Uso

## Descripción

El shortcode `[contact-form]` permite insertar un formulario de contacto en cualquier página o entrada de WordPress.

## Implementación

### Tarea 20.1 - Implementación Básica

Esta implementación cubre:
- ✅ Shortcode `[contact-form]` registrado
- ✅ Campos del formulario: nombre, email, asunto, mensaje
- ✅ Template HTML con Bootstrap 5
- ✅ Nonce de seguridad incluido
- ✅ Área para mensajes de respuesta
- ✅ Clase CSS `reforestamos-contact-form` para detección por JavaScript

### Requirements Cumplidos

- **Requirement 9.1**: THE Communication_Plugin SHALL provide a shortcode [contact-form] for embedding contact forms ✅
- **Requirement 9.2**: WHEN a contact form is rendered, THE Frontend SHALL display fields for: nombre, email, asunto, mensaje ✅

## Uso del Shortcode

### Uso Básico

```
[contact-form]
```

### Con Atributos Personalizados

```
[contact-form title="Envíanos un mensaje" button_text="Enviar"]
```

### Atributos Disponibles

| Atributo | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `title` | Título del formulario | "Contáctanos" |
| `button_text` | Texto del botón de envío | "Enviar Mensaje" |

## Estructura del Formulario

El formulario incluye los siguientes campos:

1. **Nombre** (text, requerido)
   - ID: `contact-nombre`
   - Name: `nombre`
   - Placeholder: "Tu nombre completo"

2. **Email** (email, requerido)
   - ID: `contact-email`
   - Name: `email`
   - Placeholder: "tu@email.com"

3. **Asunto** (text, requerido)
   - ID: `contact-asunto`
   - Name: `asunto`
   - Placeholder: "Asunto de tu mensaje"

4. **Mensaje** (textarea, requerido)
   - ID: `contact-mensaje`
   - Name: `mensaje`
   - Rows: 5
   - Placeholder: "Escribe tu mensaje aquí..."

## Características de Seguridad

- **Nonce Field**: Incluye `wp_nonce_field()` con el nombre `contact_form_nonce`
- **Sanitización**: Todos los atributos del shortcode son sanitizados
- **Validación HTML5**: Campos marcados como `required`

## Integración con JavaScript

El formulario tiene la clase CSS `reforestamos-contact-form` que permite al JavaScript en `assets/js/frontend.js` detectarlo y manejar el envío vía AJAX.

El handler JavaScript ya está implementado en `frontend.js`:
- Detecta formularios con la clase `.reforestamos-contact-form form`
- Previene el envío tradicional del formulario
- Envía los datos vía AJAX a `submit_contact_form`
- Muestra mensajes de éxito/error en `.form-messages`

## Próximos Pasos (Tareas Pendientes)

Las siguientes funcionalidades se implementarán en las sub-tareas posteriores:

### Tarea 20.2 - Validación
- Validación de campos requeridos en el servidor
- Validación de formato de email
- Sanitización de inputs
- Requirement 9.3, 9.9

### Tarea 20.3 - Envío de Emails
- Integración con PHPMailer
- Templates de email
- Mensajes de éxito/error
- Requirements 9.4, 9.5, 9.6

### Tarea 20.4 - Protección Anti-Spam
- Honeypot fields
- Rate limiting
- Opcional: reCAPTCHA
- Requirement 9.7

### Tarea 20.5 - Almacenamiento
- Guardar submissions en base de datos
- Interfaz de admin para ver submissions
- Requirement 9.8

## Archivos Relacionados

- **Clase Principal**: `includes/class-contact-form.php`
- **Template**: `templates/forms/contact-form-template.php`
- **JavaScript**: `assets/js/frontend.js` (handler ya existente)
- **Integración**: `includes/class-reforestamos-comunicacion.php`

## Ejemplo de Uso en una Página

```html
<!-- wp:paragraph -->
<p>¿Tienes alguna pregunta? Contáctanos usando el siguiente formulario:</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[contact-form title="Formulario de Contacto" button_text="Enviar Consulta"]
<!-- /wp:shortcode -->
```

## Estilos CSS

El formulario utiliza clases de Bootstrap 5 que ya están cargadas desde CDN:
- `.form-control` - Campos de entrada
- `.form-label` - Etiquetas
- `.mb-3` - Margen inferior
- `.btn .btn-primary` - Botón de envío
- `.alert .alert-success/.alert-danger` - Mensajes de respuesta

## Testing Manual

Para probar el formulario:

1. Activar el plugin "Reforestamos Comunicación"
2. Crear una página nueva en WordPress
3. Insertar el shortcode `[contact-form]`
4. Publicar la página
5. Visitar la página en el frontend
6. Verificar que el formulario se muestra correctamente con todos los campos

## Notas Técnicas

- El formulario usa el patrón singleton para la clase `Reforestamos_Contact_Form`
- El shortcode se registra en el hook `init` de WordPress
- El template se carga usando `include` con verificación de existencia del archivo
- Todos los textos son traducibles usando el text domain `reforestamos-comunicacion`
