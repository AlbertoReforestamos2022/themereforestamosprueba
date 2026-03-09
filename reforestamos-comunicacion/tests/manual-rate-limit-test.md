# Manual Test Plan: DeepL Rate Limit Handling

## Test Environment Setup

### Prerequisites
- WordPress 6.0+ instalado
- Plugin Reforestamos Comunicación activado
- DeepL API key configurada (preferiblemente API gratuita para testing de límites)
- Al menos 2-3 posts de prueba con contenido en español

### Configuration
1. Navegar a **Comunicación > DeepL**
2. Configurar API key de DeepL
3. Guardar configuración
4. Verificar conexión con botón "Probar Conexión"

---

## Test Case 1: Verificar Creación de Tabla de Cola

**Objetivo**: Confirmar que la tabla de cola se crea correctamente al activar el plugin.

### Steps:
1. Desactivar el plugin Reforestamos Comunicación
2. Reactivar el plugin
3. Acceder a la base de datos (phpMyAdmin o similar)
4. Buscar tabla `wp_reforestamos_translation_queue`

### Expected Result:
- ✅ La tabla existe con la estructura correcta:
  - Columnas: id, post_id, target_lang, source_lang, status, priority, retry_count, error_message, created_at, processed_at
  - Índices en post_id y status

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 2: Verificar Programación de Cron Job

**Objetivo**: Confirmar que el cron job se programa correctamente.

### Steps:
1. Instalar plugin "WP Crontrol" (opcional, para visualización)
2. Navegar a **Herramientas > Cron Events**
3. Buscar evento `reforestamos_process_translation_queue`

### Expected Result:
- ✅ El evento existe
- ✅ Está programado para ejecutarse cada hora (hourly)
- ✅ Tiene el hook correcto

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 3: Traducción Normal (Sin Límites)

**Objetivo**: Verificar que las traducciones funcionan normalmente cuando no hay límites.

### Steps:
1. Crear o editar un post en español
2. En el metabox "Traducción Automática", hacer clic en "Translate to English"
3. Esperar respuesta

### Expected Result:
- ✅ Mensaje de éxito: "✓ Post traducido exitosamente a English"
- ✅ Link para ver el post traducido
- ✅ El post traducido se crea correctamente
- ✅ Título, contenido y excerpt están traducidos
- ✅ NO se agrega a la cola

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 4: Visualización de Uso de API

**Objetivo**: Verificar que se muestra correctamente el uso de caracteres.

### Steps:
1. Navegar a **Comunicación > DeepL**
2. Observar sección "Estado de la API"

### Expected Result:
- ✅ Se muestra "Uso de caracteres: X / Y caracteres (Z%)"
- ✅ Se muestra "Caracteres restantes: N"
- ✅ El porcentaje es correcto
- ✅ Si uso > 75%, se muestra en amarillo (warning)
- ✅ Si uso > 90%, se muestra en rojo (error)

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 5: Simulación de Rate Limit (429)

**Objetivo**: Verificar el comportamiento cuando se alcanza el límite de tasa.

### Steps:
**Nota**: Este test requiere alcanzar el límite real o modificar temporalmente el código para simular el error.

#### Opción A: Alcanzar límite real
1. Traducir múltiples posts grandes rápidamente hasta alcanzar el límite

#### Opción B: Simular error (para desarrollo)
1. Modificar temporalmente `translate_text()` para retornar:
   ```php
   return new WP_Error('deepl_rate_limit', 'Rate limit', array('status' => 429, 'retry_after' => 3600));
   ```
2. Intentar traducir un post

### Expected Result:
- ✅ Mensaje: "⏱ Traducción agregada a la cola"
- ✅ NO se muestra error al usuario
- ✅ La traducción se agrega a la tabla de cola con status='pending'
- ✅ En **Comunicación > DeepL** aparece en "Cola de Traducciones"

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 6: Simulación de Quota Exceeded (456)

**Objetivo**: Verificar el comportamiento cuando se excede la cuota mensual.

### Steps:
**Nota**: Similar al Test Case 5, requiere alcanzar el límite real o simulación.

#### Opción B: Simular error (para desarrollo)
1. Modificar temporalmente `translate_text()` para retornar:
   ```php
   return new WP_Error('deepl_quota_exceeded', 'Quota exceeded', array('status' => 456));
   ```
2. Intentar traducir un post

### Expected Result:
- ✅ Mensaje: "⏱ Traducción agregada a la cola"
- ✅ La traducción se agrega a la cola
- ✅ En panel de DeepL se muestra advertencia de cuota excedida

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 7: Procesamiento de Cola (Cron)

**Objetivo**: Verificar que la cola se procesa correctamente.

### Steps:
1. Asegurarse de que hay traducciones en cola (status='pending')
2. Ejecutar manualmente el cron:
   - Opción A: Usar WP-CLI: `wp cron event run reforestamos_process_translation_queue`
   - Opción B: Usar WP Crontrol: Click en "Run Now"
   - Opción C: Código PHP:
     ```php
     do_action('reforestamos_process_translation_queue');
     ```
3. Verificar estado de la cola

### Expected Result:
- ✅ Las traducciones pendientes se procesan (hasta 10 por ejecución)
- ✅ Status cambia de 'pending' a 'processing' y luego a 'completed'
- ✅ Los posts traducidos se crean correctamente
- ✅ processed_at se actualiza con la fecha/hora
- ✅ En panel de DeepL, los contadores se actualizan

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 8: Reintento de Traducciones Fallidas

**Objetivo**: Verificar el sistema de reintentos.

### Steps:
1. Simular un error temporal (ej: desconectar internet brevemente)
2. Intentar traducir un post (fallará)
3. Verificar que se agregó a la cola
4. Restaurar conexión
5. Ejecutar cron manualmente
6. Verificar que se reintenta

### Expected Result:
- ✅ Primera falla: retry_count = 1, status = 'pending'
- ✅ Segunda falla: retry_count = 2, status = 'pending'
- ✅ Tercera falla: retry_count = 3, status = 'failed'
- ✅ Después de 3 fallos, no se reintenta más
- ✅ error_message contiene el mensaje de error

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 9: Estadísticas de Cola

**Objetivo**: Verificar que las estadísticas se muestran correctamente.

### Steps:
1. Crear traducciones en diferentes estados:
   - 2 pendientes
   - 1 completada
   - 1 fallida
2. Navegar a **Comunicación > DeepL**
3. Observar sección "Cola de Traducciones"

### Expected Result:
- ✅ Tabla muestra todos los estados con cantidades correctas
- ✅ Emojis correctos para cada estado:
  - ⏳ Pendientes
  - ⚙️ Procesando
  - ⏱️ Límite de tasa
  - ✓ Completadas
  - ✗ Fallidas
- ✅ Descripción sobre procesamiento automático

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 10: Advertencia de Límite Cercano

**Objetivo**: Verificar advertencias cuando se acerca al límite.

### Steps:
1. Usar una API key que esté cerca del límite (>75% usado)
2. Navegar a **Comunicación > DeepL**
3. Observar sección "Estado de la API"

### Expected Result:
- ✅ Si uso > 75%: Mensaje en amarillo (warning)
- ✅ Si uso > 90%: Mensaje en rojo (error) + advertencia adicional
- ✅ Advertencia dice: "⚠️ Advertencia: Está cerca de alcanzar su límite..."

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 11: Delay Entre Traducciones

**Objetivo**: Verificar que hay delay entre traducciones para evitar rate limits.

### Steps:
1. Agregar 5 traducciones a la cola
2. Ejecutar procesamiento de cola
3. Medir tiempo de ejecución

### Expected Result:
- ✅ Hay un delay de ~2 segundos entre cada traducción
- ✅ Tiempo total ≈ (número de traducciones × 2 segundos) + tiempo de API

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 12: Límite de 10 Traducciones por Ejecución

**Objetivo**: Verificar que solo se procesan 10 traducciones por ejecución de cron.

### Steps:
1. Agregar 15 traducciones a la cola (status='pending')
2. Ejecutar procesamiento de cola una vez
3. Verificar cuántas se procesaron

### Expected Result:
- ✅ Solo 10 traducciones se procesan
- ✅ 5 permanecen con status='pending'
- ✅ En la siguiente ejecución, se procesan las 5 restantes

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 13: Prioridad de Procesamiento

**Objetivo**: Verificar que las traducciones se procesan por prioridad.

### Steps:
1. Agregar traducciones con diferentes prioridades:
   - Post A: priority = 0
   - Post B: priority = 10
   - Post C: priority = 5
2. Ejecutar procesamiento de cola
3. Verificar orden de procesamiento

### Expected Result:
- ✅ Se procesan en orden: B (10), C (5), A (0)
- ✅ Mayor prioridad = procesado primero

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 14: Desactivación del Plugin

**Objetivo**: Verificar limpieza al desactivar el plugin.

### Steps:
1. Verificar que el cron job existe
2. Desactivar el plugin
3. Verificar que el cron job se eliminó

### Expected Result:
- ✅ El cron job `reforestamos_process_translation_queue` se elimina
- ✅ La tabla de cola permanece (para no perder datos)

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Case 15: Traducción de Custom Fields con Rate Limit

**Objetivo**: Verificar que los custom fields también se manejan correctamente con rate limits.

### Steps:
1. Crear un post de tipo "eventos" con campo "ubicacion"
2. Simular rate limit
3. Intentar traducir
4. Procesar cola cuando el límite se restablezca

### Expected Result:
- ✅ La traducción se agrega a la cola
- ✅ Al procesarse, tanto el contenido como los custom fields se traducen
- ✅ El campo "ubicacion" se traduce correctamente

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Performance Tests

### Test P1: Tiempo de Respuesta con Rate Limit

**Objetivo**: Verificar que la respuesta es rápida incluso cuando se alcanza el límite.

### Steps:
1. Simular rate limit
2. Intentar traducir un post
3. Medir tiempo de respuesta

### Expected Result:
- ✅ Respuesta en < 2 segundos
- ✅ Usuario recibe feedback inmediato
- ✅ No hay timeout

### Actual Result:
- [ ] Pass
- [ ] Fail

**Time**: _______ seconds

---

### Test P2: Procesamiento de Cola Grande

**Objetivo**: Verificar performance con muchas traducciones en cola.

### Steps:
1. Agregar 50 traducciones a la cola
2. Ejecutar procesamiento múltiples veces
3. Medir tiempo por ejecución

### Expected Result:
- ✅ Cada ejecución procesa 10 traducciones
- ✅ Tiempo por ejecución < 60 segundos
- ✅ No hay errores de memoria o timeout

### Actual Result:
- [ ] Pass
- [ ] Fail

**Time per execution**: _______ seconds

---

## Security Tests

### Test S1: Permisos de Usuario

**Objetivo**: Verificar que solo usuarios autorizados pueden traducir.

### Steps:
1. Cerrar sesión
2. Intentar acceder a AJAX endpoint de traducción directamente
3. Iniciar sesión como Subscriber
4. Intentar traducir un post

### Expected Result:
- ✅ Usuario no autenticado: Error de seguridad
- ✅ Subscriber: Error de permisos
- ✅ Editor/Admin: Puede traducir

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

### Test S2: Nonce Verification

**Objetivo**: Verificar protección CSRF.

### Steps:
1. Intentar llamar AJAX sin nonce
2. Intentar llamar AJAX con nonce inválido

### Expected Result:
- ✅ Sin nonce: Error de seguridad
- ✅ Nonce inválido: Error de seguridad
- ✅ Nonce válido: Funciona correctamente

### Actual Result:
- [ ] Pass
- [ ] Fail

**Notes**: _______________________________________________

---

## Test Summary

**Total Tests**: 17 (15 functional + 2 performance + 2 security)

**Passed**: _____ / 17

**Failed**: _____ / 17

**Blocked**: _____ / 17

**Critical Issues Found**: _____________________________

**Notes**: _______________________________________________

---

## Sign-off

**Tester Name**: _____________________________

**Date**: _____________________________

**Environment**: _____________________________

**WordPress Version**: _____________________________

**PHP Version**: _____________________________

**DeepL API Type**: [ ] Free [ ] Pro
