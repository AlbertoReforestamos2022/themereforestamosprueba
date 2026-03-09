# Security Quick Reference - Reforestamos Block Theme

## Guía Rápida de Seguridad para Desarrolladores

Esta guía proporciona ejemplos rápidos y prácticos para implementar seguridad en el tema Reforestamos.

---

## 📋 Tabla de Contenidos

1. [Sanitización de Inputs](#sanitización-de-inputs)
2. [Escape de Outputs](#escape-de-outputs)
3. [Nonces y CSRF](#nonces-y-csrf)
4. [Rate Limiting](#rate-limiting)
5. [Encriptación](#encriptación)
6. [Validación](#validación)
7. [Queries Seguras](#queries-seguras)
8. [Permisos](#permisos)
9. [Archivos](#archivos)
10. [Cheat Sheet](#cheat-sheet)

---

## Sanitización de Inputs

### Texto Simple
```php
$name = Reforestamos_Security::sanitize_text($_POST['name']);
```

### Textarea
```php
$message = Reforestamos_Security::sanitize_textarea($_POST['message']);
```

### Email
```php
$email = Reforestamos_Security::sanitize_email($_POST['email']);
```

### URL
```php
$website = Reforestamos_Security::sanitize_url($_POST['website']);
```

### HTML (permite tags seguros)
```php
$content = Reforestamos_Security::sanitize_html($_POST['content']);
```

### Array
```php
$tags = Reforestamos_Security::sanitize_array($_POST['tags']);
```

---

## Escape de Outputs

### HTML
```php
echo Reforestamos_Security::escape_html($user_name);
```

### Atributos HTML
```php
<input type="text" value="<?php echo Reforestamos_Security::escape_attr($value); ?>">
```

### URLs
```php
<a href="<?php echo Reforestamos_Security::escape_url($link); ?>">Link</a>
```

### JavaScript
```php
<script>
var userName = '<?php echo Reforestamos_Security::escape_js($name); ?>';
</script>
```

---

## Nonces y CSRF

### Crear Nonce en Formulario
```php
<form method="post">
    <?php Reforestamos_Security::nonce_field('contact_form_submit', 'contact_nonce'); ?>
    <input type="text" name="name">
    <button type="submit">Enviar</button>
</form>
```

### Verificar Nonce
```php
if (!Reforestamos_Security::verify_nonce('contact_nonce', 'contact_form_submit')) {
    wp_die('Security check failed');
}
```

### Nonce en AJAX
```javascript
// En JavaScript
jQuery.ajax({
    url: ajaxurl,
    type: 'POST',
    data: {
        action: 'reforestamos_action',
        nonce: '<?php echo Reforestamos_Security::create_nonce("reforestamos_ajax_action"); ?>',
        data: formData
    }
});
```

```php
// En PHP (handler)
if (!Reforestamos_Security::verify_nonce('reforestamos_ajax_nonce', 'reforestamos_ajax_action')) {
    wp_send_json_error(['message' => 'Security check failed']);
}
```

---

## Rate Limiting

### Implementar Rate Limit
```php
$user_ip = $_SERVER['REMOTE_ADDR'];

// 5 intentos en 5 minutos (300 segundos)
if (!Reforestamos_Security::check_rate_limit($user_ip, 'contact_form', 5, 300)) {
    wp_die('Demasiados intentos. Por favor intenta más tarde.');
}

// Procesar formulario...
```

### Verificar Estado
```php
$status = Reforestamos_Security::get_rate_limit_status($user_ip, 'contact_form');
echo "Intentos: " . $status['attempts'];
echo "Bloqueado: " . ($status['blocked'] ? 'Sí' : 'No');
```

### Reset Manual
```php
Reforestamos_Security::reset_rate_limit($user_ip, 'contact_form');
```

---

## Encriptación

### Encriptar Datos Sensibles
```php
$api_key = 'sk_live_abc123xyz';
$encrypted = Reforestamos_Security::encrypt($api_key);
update_option('deepl_api_key', $encrypted);
```

### Desencriptar Datos
```php
$encrypted = get_option('deepl_api_key');
$api_key = Reforestamos_Security::decrypt($encrypted);
```

### Ejemplo Completo: Guardar y Usar API Key
```php
// Guardar
function save_api_key($api_key) {
    $encrypted = Reforestamos_Security::encrypt($api_key);
    update_option('my_api_key', $encrypted);
}

// Usar
function get_api_key() {
    $encrypted = get_option('my_api_key');
    return Reforestamos_Security::decrypt($encrypted);
}

// Llamar API
function call_external_api() {
    $api_key = get_api_key();
    // Usar $api_key en la llamada...
}
```

---

## Validación

### Email
```php
if (!Reforestamos_Security::validate_email($email)) {
    $errors[] = 'Email inválido';
}
```

### URL
```php
if (!Reforestamos_Security::validate_url($website)) {
    $errors[] = 'URL inválida';
}
```

### Campo Requerido
```php
if (!Reforestamos_Security::validate_required($name)) {
    $errors[] = 'El nombre es requerido';
}
```

### Longitud Mínima
```php
if (!Reforestamos_Security::validate_min_length($password, 8)) {
    $errors[] = 'La contraseña debe tener al menos 8 caracteres';
}
```

### Longitud Máxima
```php
if (!Reforestamos_Security::validate_max_length($message, 1000)) {
    $errors[] = 'El mensaje no puede exceder 1000 caracteres';
}
```

### Validación Completa de Formulario
```php
$errors = [];

// Validar nombre
if (!Reforestamos_Security::validate_required($name)) {
    $errors[] = 'El nombre es requerido';
}

// Validar email
if (!Reforestamos_Security::validate_required($email)) {
    $errors[] = 'El email es requerido';
} elseif (!Reforestamos_Security::validate_email($email)) {
    $errors[] = 'Email inválido';
}

// Validar mensaje
if (!Reforestamos_Security::validate_required($message)) {
    $errors[] = 'El mensaje es requerido';
} elseif (!Reforestamos_Security::validate_min_length($message, 10)) {
    $errors[] = 'El mensaje debe tener al menos 10 caracteres';
}

if (!empty($errors)) {
    // Mostrar errores
    foreach ($errors as $error) {
        echo '<p class="error">' . esc_html($error) . '</p>';
    }
    return;
}

// Procesar formulario...
```

---

## Queries Seguras

### Query Simple
```php
global $wpdb;

$user_id = 123;
$query = Reforestamos_Security::prepare_query(
    "SELECT * FROM {$wpdb->prefix}users WHERE ID = %d",
    $user_id
);

$user = $wpdb->get_row($query);
```

### Query con Múltiples Parámetros
```php
$query = Reforestamos_Security::prepare_query(
    "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = %s AND post_status = %s",
    'post',
    'publish'
);

$posts = $wpdb->get_results($query);
```

### Query con LIKE
```php
$search = '%' . $wpdb->esc_like($search_term) . '%';
$query = Reforestamos_Security::prepare_query(
    "SELECT * FROM {$wpdb->prefix}posts WHERE post_title LIKE %s",
    $search
);

$results = $wpdb->get_results($query);
```

---

## Permisos

### Verificar Capacidad
```php
if (!Reforestamos_Security::user_can('edit_posts')) {
    wp_die('No tienes permisos para realizar esta acción');
}
```

### Verificar Login
```php
if (!Reforestamos_Security::is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}
```

### Verificar Capacidad de Usuario Específico
```php
$user_id = 123;
if (!Reforestamos_Security::user_can('publish_posts', $user_id)) {
    echo 'Usuario no puede publicar posts';
}
```

### Ejemplo Completo: Proteger Acción Admin
```php
function handle_admin_action() {
    // Verificar login
    if (!Reforestamos_Security::is_user_logged_in()) {
        wp_die('Debes iniciar sesión');
    }
    
    // Verificar permisos
    if (!Reforestamos_Security::user_can('manage_options')) {
        wp_die('No tienes permisos suficientes');
    }
    
    // Verificar nonce
    if (!Reforestamos_Security::verify_nonce('admin_nonce', 'admin_action')) {
        wp_die('Verificación de seguridad falló');
    }
    
    // Procesar acción...
}
```

---

## Archivos

### Validar Subida de Archivo
```php
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_size = 5 * 1024 * 1024; // 5MB

$validation = Reforestamos_Security::validate_file_upload(
    $_FILES['upload'],
    $allowed_types,
    $max_size
);

if (!$validation['valid']) {
    wp_die($validation['error']);
}

// Procesar archivo...
```

### Sanitizar Nombre de Archivo
```php
$filename = Reforestamos_Security::sanitize_filename($_FILES['upload']['name']);
```

### Ejemplo Completo: Subir Imagen
```php
function handle_image_upload() {
    // Verificar que hay archivo
    if (empty($_FILES['image'])) {
        return ['error' => 'No se seleccionó archivo'];
    }
    
    // Validar archivo
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    $validation = Reforestamos_Security::validate_file_upload(
        $_FILES['image'],
        $allowed_types,
        $max_size
    );
    
    if (!$validation['valid']) {
        return ['error' => $validation['error']];
    }
    
    // Sanitizar nombre
    $filename = Reforestamos_Security::sanitize_filename($_FILES['image']['name']);
    
    // Usar WordPress para manejar la subida
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    
    $upload = wp_handle_upload($_FILES['image'], ['test_form' => false]);
    
    if (isset($upload['error'])) {
        return ['error' => $upload['error']];
    }
    
    return ['success' => true, 'url' => $upload['url']];
}
```

---

## Cheat Sheet

### Formulario Completo Seguro

```php
<?php
// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verificar nonce
    if (!Reforestamos_Security::verify_nonce('contact_nonce', 'contact_form_submit')) {
        $error = 'Verificación de seguridad falló';
    } else {
        // 2. Rate limiting
        $user_ip = $_SERVER['REMOTE_ADDR'];
        if (!Reforestamos_Security::check_rate_limit($user_ip, 'contact_form', 5, 300)) {
            $error = 'Demasiados intentos. Intenta en 5 minutos.';
        } else {
            // 3. Sanitizar inputs
            $name = Reforestamos_Security::sanitize_text($_POST['name']);
            $email = Reforestamos_Security::sanitize_email($_POST['email']);
            $message = Reforestamos_Security::sanitize_textarea($_POST['message']);
            
            // 4. Validar
            $errors = [];
            
            if (!Reforestamos_Security::validate_required($name)) {
                $errors[] = 'El nombre es requerido';
            }
            
            if (!Reforestamos_Security::validate_required($email)) {
                $errors[] = 'El email es requerido';
            } elseif (!Reforestamos_Security::validate_email($email)) {
                $errors[] = 'Email inválido';
            }
            
            if (!Reforestamos_Security::validate_required($message)) {
                $errors[] = 'El mensaje es requerido';
            }
            
            // 5. Procesar si no hay errores
            if (empty($errors)) {
                // Enviar email, guardar en BD, etc.
                $success = 'Mensaje enviado correctamente';
            }
        }
    }
}
?>

<!-- Mostrar mensajes -->
<?php if (isset($error)): ?>
    <div class="error"><?php echo Reforestamos_Security::escape_html($error); ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="success"><?php echo Reforestamos_Security::escape_html($success); ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $err): ?>
            <p><?php echo Reforestamos_Security::escape_html($err); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Formulario -->
<form method="post">
    <?php Reforestamos_Security::nonce_field('contact_form_submit', 'contact_nonce'); ?>
    
    <label>
        Nombre:
        <input type="text" name="name" value="<?php echo isset($_POST['name']) ? Reforestamos_Security::escape_attr($_POST['name']) : ''; ?>" required>
    </label>
    
    <label>
        Email:
        <input type="email" name="email" value="<?php echo isset($_POST['email']) ? Reforestamos_Security::escape_attr($_POST['email']) : ''; ?>" required>
    </label>
    
    <label>
        Mensaje:
        <textarea name="message" required><?php echo isset($_POST['message']) ? Reforestamos_Security::escape_html($_POST['message']) : ''; ?></textarea>
    </label>
    
    <button type="submit">Enviar</button>
</form>
```

---

## Funciones Más Usadas

| Función | Uso |
|---------|-----|
| `sanitize_text()` | Sanitizar texto simple |
| `sanitize_email()` | Sanitizar email |
| `escape_html()` | Escapar HTML en output |
| `escape_attr()` | Escapar atributos HTML |
| `verify_nonce()` | Verificar nonce |
| `check_rate_limit()` | Verificar rate limit |
| `validate_email()` | Validar formato email |
| `validate_required()` | Validar campo requerido |
| `user_can()` | Verificar permisos |
| `prepare_query()` | Preparar query SQL |

---

## Errores Comunes a Evitar

### ❌ NO HACER

```php
// NO: Concatenar directamente en SQL
$query = "SELECT * FROM users WHERE id = " . $_GET['id'];

// NO: No escapar output
echo $_POST['name'];

// NO: No verificar nonce
if ($_POST['submit']) {
    // procesar...
}

// NO: No sanitizar input
$email = $_POST['email'];
update_option('admin_email', $email);

// NO: No validar permisos
if (isset($_POST['delete'])) {
    wp_delete_post($_POST['post_id']);
}
```

### ✅ HACER

```php
// SÍ: Usar prepared statements
$query = Reforestamos_Security::prepare_query(
    "SELECT * FROM users WHERE id = %d",
    $_GET['id']
);

// SÍ: Escapar output
echo Reforestamos_Security::escape_html($_POST['name']);

// SÍ: Verificar nonce
if (Reforestamos_Security::verify_nonce('my_nonce', 'my_action')) {
    // procesar...
}

// SÍ: Sanitizar input
$email = Reforestamos_Security::sanitize_email($_POST['email']);
update_option('admin_email', $email);

// SÍ: Validar permisos
if (Reforestamos_Security::user_can('delete_posts')) {
    wp_delete_post($_POST['post_id']);
}
```

---

## Recursos Adicionales

- [Documentación Completa](./TASK-34-IMPLEMENTATION.md)
- [Checklist de Auditoría](./SECURITY-AUDIT-CHECKLIST.md)
- [WordPress Security Handbook](https://developer.wordpress.org/apis/security/)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)

---

**Última Actualización:** 2024
**Versión:** 1.0.0
