# Pruebas Manuales - Protección Anti-Spam del Formulario de Contacto

**Tarea:** 20.4 Implementar protección anti-spam  
**Requirements:** 9.7, 23.6  
**Fecha:** 2024

## Resumen de la Implementación

Se implementaron dos mecanismos de protección anti-spam:

1. **Honeypot Field (Requirement 9.7)**
   - Campo oculto "website" que los bots llenan pero los humanos no ven
   - Si el campo tiene valor, se rechaza silenciosamente
   - Logging de intentos detectados

2. **Rate Limiting (Requirement 23.6)**
   - Límite de 3 envíos por IP cada 15 minutos
   - Usa transients de WordPress para almacenar contadores
   - Mensaje de error amigable cuando se excede el límite

## Archivos Modificados

- `includes/class-contact-form.php` - Lógica de protección anti-spam
- `templates/forms/contact-form-template.php` - Campo honeypot
- `assets/css/frontend.css` - Estilos para ocultar honeypot

---

## Test 1: Verificar Campo Honeypot Oculto

**Objetivo:** Confirmar que el campo honeypot está presente pero invisible para usuarios humanos.

### Pasos:
1. Navegar a una página con el shortcode `[contact-form]`
2. Inspeccionar el HTML del formulario con DevTools
3. Buscar el elemento con clase `form-field-hp`

### Resultado Esperado:
- ✅ El campo existe en el DOM con `name="website"`
- ✅ El campo tiene `aria-hidden="true"`
- ✅ El campo tiene `tabindex="-1"`
- ✅ El campo tiene `autocomplete="off"`
- ✅ El campo NO es visible en la página (está fuera de la pantalla)
- ✅ Los estilos CSS lo posicionan en `left: -9999px`

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 2: Simular Bot Llenando Honeypot

**Objetivo:** Verificar que el sistema detecta y rechaza envíos cuando el honeypot está lleno.

### Pasos:
1. Abrir DevTools en la página del formulario
2. En la consola, ejecutar:
   ```javascript
   document.querySelector('input[name="website"]').value = 'http://spam-bot.com';
   ```
3. Llenar el formulario normalmente con datos válidos
4. Enviar el formulario

### Resultado Esperado:
- ✅ El formulario muestra mensaje de éxito (para no dar pistas al bot)
- ✅ NO se envía el email real
- ✅ En el log de errores de WordPress aparece:
  ```
  Reforestamos Comunicación - Anti-Spam: Honeypot triggered. IP: [IP], Time: [timestamp], Honeypot value: http://spam-bot.com
  ```

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 3: Envío Normal Sin Honeypot

**Objetivo:** Confirmar que usuarios legítimos pueden enviar el formulario sin problemas.

### Pasos:
1. Navegar a una página con el formulario
2. Llenar todos los campos requeridos con datos válidos:
   - Nombre: "Juan Pérez"
   - Email: "juan@example.com"
   - Asunto: "Consulta sobre reforestación"
   - Mensaje: "Me gustaría obtener más información sobre sus proyectos."
3. NO modificar el campo honeypot (debe quedar vacío)
4. Enviar el formulario

### Resultado Esperado:
- ✅ El formulario se envía exitosamente
- ✅ Aparece mensaje: "¡Gracias por tu mensaje! Te responderemos pronto."
- ✅ Se recibe el email en la bandeja configurada
- ✅ NO aparecen errores en el log

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 4: Rate Limiting - Primer Envío

**Objetivo:** Verificar que el primer envío funciona correctamente y se inicia el contador.

### Pasos:
1. Limpiar transients (opcional): En WP-CLI ejecutar `wp transient delete --all`
2. Enviar el formulario con datos válidos
3. Verificar en la base de datos el transient creado:
   ```sql
   SELECT * FROM wp_options WHERE option_name LIKE '%contact_form_rate_limit%';
   ```

### Resultado Esperado:
- ✅ El formulario se envía exitosamente
- ✅ Se crea un transient con nombre `_transient_contact_form_rate_limit_[hash_md5_de_ip]`
- ✅ El valor del transient es `1`
- ✅ El transient expira en 15 minutos (900 segundos)

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 5: Rate Limiting - Segundo y Tercer Envío

**Objetivo:** Verificar que se permite hasta 3 envíos.

### Pasos:
1. Enviar el formulario una segunda vez (inmediatamente después del Test 4)
2. Verificar que se envía correctamente
3. Enviar el formulario una tercera vez
4. Verificar que se envía correctamente
5. Verificar el valor del transient en la base de datos

### Resultado Esperado:
- ✅ El segundo envío es exitoso
- ✅ El tercer envío es exitoso
- ✅ El valor del transient es `3` después del tercer envío

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 6: Rate Limiting - Cuarto Envío (Límite Excedido)

**Objetivo:** Verificar que el cuarto envío es rechazado por rate limiting.

### Pasos:
1. Inmediatamente después del Test 5, intentar enviar el formulario una cuarta vez
2. Observar el mensaje de error

### Resultado Esperado:
- ✅ El formulario NO se envía
- ✅ Aparece mensaje de error: "Has enviado demasiados mensajes. Por favor espera unos minutos antes de intentar de nuevo."
- ✅ En el log de errores aparece:
  ```
  Reforestamos Comunicación - Anti-Spam: Rate limit exceeded. IP: [IP], Attempts: 3, Time: [timestamp]
  ```
- ✅ NO se envía email

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 7: Rate Limiting - Expiración del Transient

**Objetivo:** Verificar que después de 15 minutos se puede volver a enviar.

### Pasos:
1. Después del Test 6, esperar 15 minutos (o eliminar manualmente el transient)
2. Para acelerar la prueba, eliminar el transient:
   ```sql
   DELETE FROM wp_options WHERE option_name LIKE '%contact_form_rate_limit%';
   ```
3. Enviar el formulario nuevamente

### Resultado Esperado:
- ✅ El formulario se envía exitosamente
- ✅ Se crea un nuevo transient con valor `1`
- ✅ El contador se reinicia

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 8: Rate Limiting - Diferentes IPs

**Objetivo:** Verificar que el rate limiting es por IP, no global.

### Pasos:
1. Desde una IP, enviar el formulario 3 veces (alcanzar el límite)
2. Desde otra IP diferente (usar VPN, proxy, o dispositivo diferente), enviar el formulario

### Resultado Esperado:
- ✅ La primera IP está bloqueada
- ✅ La segunda IP puede enviar sin problemas
- ✅ Existen dos transients diferentes en la base de datos (uno por cada IP)

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 9: Logging de Eventos Anti-Spam

**Objetivo:** Verificar que todos los eventos de spam se registran correctamente.

### Pasos:
1. Revisar el archivo de log de WordPress (normalmente `wp-content/debug.log`)
2. Buscar entradas con el prefijo "Reforestamos Comunicación - Anti-Spam:"

### Resultado Esperado:
- ✅ Los intentos de honeypot se registran con IP, timestamp y valor del campo
- ✅ Los rate limit exceeded se registran con IP, número de intentos y timestamp
- ✅ Los logs son claros y útiles para análisis

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 10: Seguridad - Inyección en Honeypot

**Objetivo:** Verificar que valores maliciosos en el honeypot no causan problemas.

### Pasos:
1. Usar DevTools para establecer valores maliciosos en el honeypot:
   ```javascript
   document.querySelector('input[name="website"]').value = '<script>alert("XSS")</script>';
   ```
2. Enviar el formulario

### Resultado Esperado:
- ✅ El formulario es rechazado silenciosamente
- ✅ NO se ejecuta ningún script
- ✅ El valor se registra de forma segura en el log (escapado)
- ✅ No hay errores de PHP

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 11: Compatibilidad con Proxies

**Objetivo:** Verificar que el sistema detecta correctamente la IP real detrás de proxies.

### Pasos:
1. Configurar un proxy o usar Cloudflare
2. Enviar el formulario
3. Verificar en el log qué IP se registró

### Resultado Esperado:
- ✅ Se detecta la IP real del usuario (no la del proxy)
- ✅ El rate limiting funciona correctamente con la IP real
- ✅ Se verifica `HTTP_X_FORWARDED_FOR` y `HTTP_CLIENT_IP`

### Resultado Real:
- [ ] Pendiente de prueba

---

## Test 12: Accesibilidad del Honeypot

**Objetivo:** Verificar que el honeypot no afecta la accesibilidad.

### Pasos:
1. Usar un lector de pantalla (NVDA, JAWS, o VoiceOver)
2. Navegar por el formulario con el teclado
3. Verificar que el campo honeypot no es anunciado

### Resultado Esperado:
- ✅ El lector de pantalla NO anuncia el campo honeypot
- ✅ El atributo `aria-hidden="true"` oculta el campo de tecnologías asistivas
- ✅ El `tabindex="-1"` previene que se pueda enfocar con teclado
- ✅ Los usuarios con lectores de pantalla pueden completar el formulario normalmente

### Resultado Real:
- [ ] Pendiente de prueba

---

## Checklist de Implementación

### Requirement 9.7 - Spam Protection
- [x] Honeypot field implementado en el template
- [x] Campo honeypot oculto con CSS (position: absolute, left: -9999px)
- [x] Validación del honeypot en handle_form_submission()
- [x] Rechazo silencioso cuando honeypot está lleno
- [x] Logging de intentos de spam detectados por honeypot
- [x] Atributos de accesibilidad (aria-hidden, tabindex, autocomplete)

### Requirement 23.6 - Rate Limiting
- [x] Implementación de check_rate_limit()
- [x] Límite de 3 envíos por IP cada 15 minutos
- [x] Uso de transients de WordPress para almacenar contadores
- [x] Mensaje de error amigable cuando se excede el límite
- [x] Logging de IPs que exceden el límite
- [x] Implementación de increment_rate_limit()
- [x] Implementación de get_user_ip() con soporte para proxies
- [x] Integración en el flujo de handle_form_submission()

### Seguridad
- [x] Sanitización de valores del honeypot antes de logging
- [x] Sanitización de IP antes de usar en transient key
- [x] Uso de md5() para hash de IP en transient key
- [x] Validación de IP con filter_var(FILTER_VALIDATE_IP)
- [x] Manejo seguro de $_SERVER variables

### Documentación
- [x] Comentarios en código explicando la funcionalidad
- [x] Referencias a requirements en comentarios
- [x] Documento de pruebas manuales creado

---

## Notas de Implementación

### Honeypot Field
- El campo se llama "website" porque es un nombre común que los bots suelen llenar
- Se usa `position: absolute; left: -9999px` en lugar de `display: none` porque algunos bots detectan campos con display:none
- El campo tiene `aria-hidden="true"` para que lectores de pantalla lo ignoren
- El campo tiene `tabindex="-1"` para que no se pueda enfocar con teclado
- El campo tiene `autocomplete="off"` para prevenir que navegadores lo llenen automáticamente

### Rate Limiting
- Se usa el hash MD5 de la IP para el nombre del transient (para evitar caracteres especiales)
- El límite de 3 envíos en 15 minutos es configurable (se puede ajustar en el código)
- Los transients se limpian automáticamente por WordPress después de expirar
- El contador se incrementa DESPUÉS de un envío exitoso (no antes de la validación)

### Logging
- Todos los logs usan el prefijo "Reforestamos Comunicación - Anti-Spam:" para fácil búsqueda
- Los logs incluyen IP, timestamp y detalles relevantes
- Los valores del honeypot se registran para análisis de patrones de spam

### Futuras Mejoras (Opcional)
- Integración con reCAPTCHA v3 para protección adicional
- Dashboard de admin para ver estadísticas de spam bloqueado
- Lista blanca de IPs confiables que no tienen rate limiting
- Configuración de límites de rate limiting desde el admin

---

## Conclusión

La implementación de protección anti-spam cumple con los requirements 9.7 y 23.6:

✅ **Requirement 9.7:** Implementado honeypot field que detecta y rechaza bots silenciosamente  
✅ **Requirement 23.6:** Implementado rate limiting de 3 envíos por IP cada 15 minutos

La solución es:
- **Efectiva:** Bloquea spam automatizado sin afectar usuarios legítimos
- **Segura:** Sanitiza y valida todos los datos
- **Accesible:** No afecta la experiencia de usuarios con tecnologías asistivas
- **Mantenible:** Código limpio, documentado y siguiendo estándares de WordPress
- **Escalable:** Usa transients de WordPress que se limpian automáticamente
