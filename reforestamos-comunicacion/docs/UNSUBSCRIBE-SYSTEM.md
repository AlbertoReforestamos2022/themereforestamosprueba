# Sistema de Unsubscribe (Baja de Suscripción)

## Descripción General

El sistema de unsubscribe permite a los suscriptores darse de baja del boletín de noticias mediante un enlace seguro de un solo clic incluido en cada email enviado.

## Requisitos Satisfechos

- **Requirement 8.8**: ✅ Soporte de funcionalidad de unsubscribe con enlaces de un solo clic

## Arquitectura

### Componentes Principales

1. **Generación de Enlaces Seguros**
   - Método: `get_unsubscribe_url($subscriber_id)`
   - Genera URL con token de seguridad único
   - Formato: `/?action=unsubscribe&subscriber={id}&token={hash}`

2. **Validación de Tokens**
   - Método: `generate_unsubscribe_token($subscriber_id)`
   - Método: `verify_unsubscribe_token($subscriber_id, $token)`
   - Usa `wp_hash()` para generar tokens seguros
   - Previene manipulación de URLs

3. **Procesamiento de Bajas**
   - Método: `handle_unsubscribe()`
   - Hooked a: `init` action
   - Valida token y actualiza estado del suscriptor

4. **Página de Confirmación**
   - Método: `show_unsubscribe_confirmation()`
   - Muestra mensaje de confirmación al usuario
   - Incluye enlace para volver al sitio

### Flujo de Datos

```
Newsletter Email
    ↓
[Enlace Unsubscribe con Token]
    ↓
Usuario hace clic
    ↓
WordPress init action
    ↓
handle_unsubscribe()
    ↓
Validar token
    ↓
Actualizar estado en DB
    ↓
Mostrar página de confirmación
```

## Implementación Técnica

### 1. Generación de URL de Unsubscribe

```php
private function get_unsubscribe_url($subscriber_id) {
    $token = $this->generate_unsubscribe_token($subscriber_id);
    return add_query_arg([
        'action' => 'unsubscribe',
        'subscriber' => $subscriber_id,
        'token' => $token
    ], home_url('/'));
}
```

**Características:**
- Token único por suscriptor
- URL absoluta con dominio completo
- Parámetros codificados correctamente

### 2. Generación y Verificación de Tokens

```php
private function generate_unsubscribe_token($subscriber_id) {
    return wp_hash($subscriber_id . 'unsubscribe_salt');
}

private function verify_unsubscribe_token($subscriber_id, $token) {
    return hash_equals($this->generate_unsubscribe_token($subscriber_id), $token);
}
```

**Seguridad:**
- Usa `wp_hash()` que incluye las sales de WordPress
- `hash_equals()` previene timing attacks
- Token no puede ser falsificado sin acceso a las sales

### 3. Inclusión en Emails

El enlace de unsubscribe se agrega automáticamente a todos los emails en `prepare_email_content()`:

```php
private function prepare_email_content($newsletter, $recipient) {
    $content = apply_filters('the_content', $newsletter->post_content);
    
    // Add unsubscribe link
    $unsubscribe_url = $this->get_unsubscribe_url($recipient['id']);
    
    // Add footer with unsubscribe link
    $footer = sprintf(
        '<hr><p style="font-size: 12px; color: #666;">%s <a href="%s">%s</a></p>',
        __('Si no deseas recibir más correos,', 'reforestamos-comunicacion'),
        esc_url($unsubscribe_url),
        __('cancela tu suscripción aquí', 'reforestamos-comunicacion')
    );
    
    $content .= $footer;
    
    return $content;
}
```

**Características:**
- Se agrega automáticamente a todos los emails
- Texto traducible (i18n)
- URL escapada para seguridad
- Estilo discreto en el footer

### 4. Procesamiento de Solicitudes

```php
public function handle_unsubscribe() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'unsubscribe') {
        return;
    }
    
    $subscriber_id = isset($_GET['subscriber']) ? intval($_GET['subscriber']) : 0;
    $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
    
    if (!$subscriber_id || !$token) {
        wp_die(__('Enlace de baja inválido', 'reforestamos-comunicacion'));
    }
    
    // Verify token
    if (!$this->verify_unsubscribe_token($subscriber_id, $token)) {
        wp_die(__('Token de seguridad inválido', 'reforestamos-comunicacion'));
    }
    
    // Unsubscribe user
    global $wpdb;
    $table_name = $wpdb->prefix . 'reforestamos_subscribers';
    
    $updated = $wpdb->update(
        $table_name,
        [
            'status' => 'unsubscribed',
            'unsubscribed_at' => current_time('mysql')
        ],
        ['id' => $subscriber_id],
        ['%s', '%s'],
        ['%d']
    );
    
    if ($updated) {
        $this->show_unsubscribe_confirmation();
    } else {
        wp_die(__('Error al procesar la baja', 'reforestamos-comunicacion'));
    }
}
```

**Validaciones:**
- Verifica que la acción sea 'unsubscribe'
- Valida que subscriber_id sea un entero
- Sanitiza el token
- Verifica el token de seguridad
- Actualiza estado en base de datos
- Registra timestamp de baja

### 5. Página de Confirmación

```php
private function show_unsubscribe_confirmation() {
    wp_head();
    ?>
    <div style="max-width: 600px; margin: 100px auto; padding: 40px; text-align: center; font-family: Arial, sans-serif;">
        <h1><?php _e('Baja Confirmada', 'reforestamos-comunicacion'); ?></h1>
        <p><?php _e('Tu suscripción ha sido cancelada exitosamente.', 'reforestamos-comunicacion'); ?></p>
        <p><?php _e('Ya no recibirás más correos de nuestro boletín.', 'reforestamos-comunicacion'); ?></p>
        <p><a href="<?php echo home_url('/'); ?>" class="button"><?php _e('Volver al inicio', 'reforestamos-comunicacion'); ?></a></p>
    </div>
    <?php
    wp_footer();
    exit;
}
```

**Características:**
- Diseño simple y centrado
- Mensajes claros y traducibles
- Enlace para volver al sitio
- Incluye wp_head() y wp_footer() para compatibilidad con temas

## Base de Datos

### Tabla: wp_reforestamos_subscribers

**Columnas relevantes:**
- `id` (INT): ID único del suscriptor
- `email` (VARCHAR): Email del suscriptor
- `status` (VARCHAR): Estado ('active', 'unsubscribed', 'pending')
- `unsubscribed_at` (DATETIME): Timestamp de cuando se dio de baja

**Estados posibles:**
- `pending`: Esperando confirmación de double opt-in
- `active`: Suscriptor activo, recibe emails
- `unsubscribed`: Dado de baja, no recibe emails

## Seguridad

### Medidas Implementadas

1. **Tokens Seguros**
   - Generados con `wp_hash()` usando sales de WordPress
   - No pueden ser falsificados sin acceso al servidor
   - Únicos por suscriptor

2. **Validación de Entrada**
   - `intval()` para subscriber_id
   - `sanitize_text_field()` para token
   - Verificación de existencia de parámetros

3. **Comparación Segura**
   - `hash_equals()` previene timing attacks
   - No revela información sobre el token correcto

4. **Escape de Salida**
   - `esc_url()` para URLs
   - `__()` para textos traducibles
   - Previene XSS

### Consideraciones de Privacidad

- No se requiere login para darse de baja
- Un solo clic es suficiente (cumple con GDPR)
- Se registra timestamp de baja para auditoría
- Los datos del suscriptor se mantienen pero con estado 'unsubscribed'

## Uso

### Para Usuarios Finales

1. Recibir email del boletín
2. Hacer clic en "cancela tu suscripción aquí" al final del email
3. Ver página de confirmación
4. Ya no recibir más emails

### Para Administradores

**Ver suscriptores dados de baja:**
```sql
SELECT * FROM wp_reforestamos_subscribers 
WHERE status = 'unsubscribed' 
ORDER BY unsubscribed_at DESC;
```

**Reactivar un suscriptor:**
```sql
UPDATE wp_reforestamos_subscribers 
SET status = 'active', unsubscribed_at = NULL 
WHERE id = {subscriber_id};
```

## Testing

### Test Manual

1. Crear un suscriptor de prueba
2. Enviar un newsletter
3. Verificar que el email incluye el enlace de unsubscribe
4. Hacer clic en el enlace
5. Verificar que se muestra la página de confirmación
6. Verificar en la base de datos que el estado cambió a 'unsubscribed'

### Test Automatizado

Ejecutar el test de verificación:
```bash
# Acceder a:
wp-admin/admin-ajax.php?action=test_unsubscribe_system
```

Ver archivo: `tests/test-unsubscribe-system.php`

## Cumplimiento Legal

### GDPR (Reglamento General de Protección de Datos)

✅ **Cumple con:**
- Derecho a darse de baja fácilmente
- Un solo clic para unsubscribe
- Confirmación clara de la acción
- Registro de timestamp para auditoría

### CAN-SPAM Act

✅ **Cumple con:**
- Enlace de unsubscribe visible en cada email
- Procesamiento inmediato de solicitudes
- No requiere login o información adicional

## Mejoras Futuras

### Posibles Extensiones

1. **Preferencias de Suscripción**
   - Permitir elegir tipos de emails (newsletters, eventos, etc.)
   - Frecuencia de envío (diario, semanal, mensual)

2. **Resubscribe**
   - Permitir reactivar suscripción desde el sitio
   - Enviar email de confirmación de reactivación

3. **Razón de Baja**
   - Formulario opcional para indicar por qué se dan de baja
   - Analytics de razones de baja

4. **Soft Delete**
   - Opción de "pausar" suscripción temporalmente
   - Reactivación automática después de X tiempo

5. **Notificaciones Admin**
   - Email al admin cuando alguien se da de baja
   - Dashboard widget con estadísticas de bajas

## Troubleshooting

### Problema: El enlace no funciona

**Posibles causas:**
1. Token inválido o expirado
2. Subscriber_id no existe en la base de datos
3. Hook 'init' no se está ejecutando

**Solución:**
- Verificar que el plugin esté activo
- Revisar logs de errores de WordPress
- Verificar que la tabla de suscriptores existe

### Problema: No se muestra la página de confirmación

**Posibles causas:**
1. Tema interfiere con wp_head()/wp_footer()
2. Error en la actualización de base de datos

**Solución:**
- Verificar que el tema llama wp_head() y wp_footer()
- Revisar permisos de base de datos
- Verificar logs de errores

### Problema: Los enlaces no se agregan a los emails

**Posibles causas:**
1. Método prepare_email_content() no se está llamando
2. Filtro 'the_content' interfiere

**Solución:**
- Verificar que send_newsletter() llama prepare_email_content()
- Revisar filtros aplicados a 'the_content'

## Referencias

- **Archivo principal**: `includes/class-newsletter.php`
- **Métodos relacionados**:
  - `get_unsubscribe_url()`
  - `generate_unsubscribe_token()`
  - `verify_unsubscribe_token()`
  - `handle_unsubscribe()`
  - `show_unsubscribe_confirmation()`
  - `prepare_email_content()`

## Changelog

### Version 1.0.0 (2024)
- ✅ Implementación inicial del sistema de unsubscribe
- ✅ Generación de tokens seguros
- ✅ Página de confirmación
- ✅ Inclusión automática en emails
- ✅ Documentación completa
