# Manual Test: Contact Form Email Sending

## Objetivo
Verificar que el formulario de contacto envía emails correctamente usando PHPMailer y muestra los mensajes apropiados al usuario.

## Requirements Validados
- **9.4**: WHEN form validation passes, THE Communication_Plugin SHALL send the message using PHPMailer
- **9.5**: WHEN an email is sent successfully, THE Frontend SHALL display a success message to the user
- **9.6**: IF email sending fails, THEN THE Communication_Plugin SHALL log the error and display a user-friendly error message

## Pre-requisitos
1. Plugin "Reforestamos Comunicación" activado
2. Configuración SMTP completada en WordPress admin (Settings > Newsletter Settings)
3. Página con shortcode `[contact-form]` creada

## Configuración SMTP Requerida
Antes de ejecutar las pruebas, configurar en WordPress Admin:
- **SMTP Host**: (ej: smtp.gmail.com)
- **SMTP Port**: 587 (TLS) o 465 (SSL)
- **SMTP Username**: tu-email@dominio.com
- **SMTP Password**: tu-contraseña-de-aplicación
- **SMTP Secure**: TLS o SSL
- **From Email**: email-remitente@dominio.com
- **From Name**: Reforestamos México

## Test Cases

### Test 1: Envío Exitoso de Email (Requirement 9.4, 9.5)

**Pasos:**
1. Navegar a la página con el formulario de contacto
2. Completar todos los campos:
   - Nombre: "Juan Pérez"
   - Email: "juan.perez@example.com"
   - Asunto: "Consulta sobre reforestación"
   - Mensaje: "Me gustaría obtener más información sobre sus proyectos de reforestación en mi ciudad."
3. Hacer clic en "Enviar Mensaje"

**Resultado Esperado:**
- ✅ El formulario muestra un mensaje de éxito: "¡Gracias por tu mensaje! Te responderemos pronto."
- ✅ El mensaje de éxito aparece en color verde
- ✅ El formulario se limpia después del envío exitoso
- ✅ Se recibe un email en la dirección configurada como admin_email
- ✅ El email contiene:
  - Asunto: "Nuevo mensaje de contacto: Consulta sobre reforestación"
  - Nombre del remitente: Juan Pérez
  - Email del remitente: juan.perez@example.com
  - Asunto del mensaje: Consulta sobre reforestación
  - Mensaje completo
  - Reply-To configurado al email del usuario (juan.perez@example.com)

**Validación del Email Recibido:**
- Verificar que el email tiene formato HTML
- Verificar que el diseño es legible y profesional
- Verificar que todos los campos están presentes
- Verificar que se puede responder directamente haciendo "Reply"

---

### Test 2: Fallo en Envío de Email (Requirement 9.6)

**Pasos:**
1. Desactivar temporalmente la configuración SMTP (cambiar el host a un valor inválido)
2. Navegar a la página con el formulario de contacto
3. Completar todos los campos correctamente
4. Hacer clic en "Enviar Mensaje"

**Resultado Esperado:**
- ✅ El formulario muestra un mensaje de error: "Lo sentimos, hubo un error al enviar tu mensaje. Por favor intenta de nuevo más tarde."
- ✅ El mensaje de error aparece en color rojo
- ✅ El formulario NO se limpia (los datos permanecen)
- ✅ Se registra un error en el log de PHP con:
  - Mensaje: "Reforestamos Comunicación: Failed to send contact form email"
  - Nombre del usuario
  - Email del usuario
  - Timestamp

**Validación del Log:**
```bash
# En el servidor, revisar el log de errores de PHP
tail -f /path/to/php-error.log | grep "Reforestamos Comunicación"
```

---

### Test 3: Validación Antes del Envío

**Pasos:**
1. Navegar a la página con el formulario de contacto
2. Dejar campos vacíos o con datos inválidos
3. Hacer clic en "Enviar Mensaje"

**Resultado Esperado:**
- ✅ El formulario muestra mensajes de validación apropiados
- ✅ NO se intenta enviar el email
- ✅ NO se registra ningún error en el log

---

### Test 4: Caracteres Especiales y HTML

**Pasos:**
1. Completar el formulario con:
   - Nombre: "María José O'Connor"
   - Email: "maria.jose@example.com"
   - Asunto: "Pregunta sobre <script>alert('test')</script>"
   - Mensaje: "Hola, tengo una pregunta:\n\n¿Cómo puedo participar?\n\nGracias & saludos"
2. Enviar el formulario

**Resultado Esperado:**
- ✅ El email se envía correctamente
- ✅ Los caracteres especiales se muestran correctamente (tildes, ñ, apóstrofes)
- ✅ Los tags HTML son escapados y no se ejecutan
- ✅ Los saltos de línea se preservan en el email
- ✅ Los caracteres especiales (&) se muestran correctamente

---

### Test 5: Email con Diferentes Longitudes

**Pasos:**
1. Enviar un mensaje muy corto (10 caracteres)
2. Enviar un mensaje de longitud media (500 caracteres)
3. Enviar un mensaje largo (4000 caracteres)

**Resultado Esperado:**
- ✅ Todos los mensajes se envían correctamente
- ✅ El formato del email se mantiene legible en todos los casos
- ✅ Los mensajes largos no se truncan

---

### Test 6: Múltiples Envíos Consecutivos

**Pasos:**
1. Enviar 3 formularios de contacto consecutivamente con diferentes datos
2. Esperar 1 minuto entre cada envío

**Resultado Esperado:**
- ✅ Todos los emails se envían correctamente
- ✅ Se reciben 3 emails separados en el admin_email
- ✅ Cada email contiene los datos correctos
- ✅ No hay confusión entre los datos de diferentes envíos

---

## Verificación de Integración

### Verificar que class-mailer.php se usa correctamente:
```php
// En class-contact-form.php, línea ~145
$mailer = Reforestamos_Mailer::get_instance();
$sent   = $mailer->send_contact_notification( $email_data );
```

### Verificar estructura de $email_data:
```php
array(
    'name'    => 'Nombre del usuario',
    'email'   => 'email@usuario.com',
    'subject' => 'Asunto del mensaje',
    'message' => 'Contenido del mensaje',
    'phone'   => '', // Opcional, vacío por ahora
)
```

### Verificar que el template HTML se genera correctamente:
- El método `build_contact_email_body()` en class-mailer.php debe recibir el array correcto
- El email debe tener el formato HTML definido en el template

---

## Troubleshooting

### Si no se reciben emails:
1. Verificar configuración SMTP en WordPress admin
2. Verificar que el servidor permite conexiones SMTP salientes
3. Revisar el log de errores de PHP
4. Probar con el botón "Send Test Email" en la configuración del plugin

### Si los emails van a spam:
1. Verificar que el dominio del "From Email" coincide con el dominio del servidor
2. Configurar SPF, DKIM y DMARC en el DNS del dominio
3. Usar un servicio SMTP confiable (SendGrid, Mailgun, etc.)

### Si hay errores de sintaxis:
1. Verificar que class-mailer.php está cargado correctamente
2. Verificar que no hay errores de PHP en el log
3. Ejecutar `php -l` en el archivo para verificar sintaxis

---

## Checklist de Validación

- [ ] Test 1: Envío exitoso completado
- [ ] Test 2: Manejo de errores completado
- [ ] Test 3: Validación previa completada
- [ ] Test 4: Caracteres especiales completado
- [ ] Test 5: Diferentes longitudes completado
- [ ] Test 6: Múltiples envíos completado
- [ ] Email recibido con formato correcto
- [ ] Reply-To funciona correctamente
- [ ] Logs de error se generan cuando corresponde
- [ ] Mensajes de éxito/error son claros y traducibles

---

## Notas de Implementación

### Cambios Realizados en class-contact-form.php:
1. Se agregó la integración con `Reforestamos_Mailer::get_instance()`
2. Se preparó el array `$email_data` con la estructura correcta
3. Se implementó el manejo de respuesta exitosa (Requirement 9.5)
4. Se implementó el manejo de errores con logging (Requirement 9.6)
5. Los mensajes son traducibles usando `__()`

### Dependencias:
- class-mailer.php debe estar cargado antes de class-contact-form.php
- La configuración SMTP debe estar completada en las opciones de WordPress
- PHPMailer está incluido en WordPress core (no requiere instalación adicional)

---

## Resultado Final Esperado

Al completar todos los tests exitosamente:
- ✅ Requirement 9.4 validado: El sistema envía emails usando PHPMailer
- ✅ Requirement 9.5 validado: Se muestra mensaje de éxito al usuario
- ✅ Requirement 9.6 validado: Se registran errores y se muestra mensaje amigable

**Estado de la Tarea 20.3: COMPLETADA** ✅
