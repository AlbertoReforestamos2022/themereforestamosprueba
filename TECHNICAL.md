# Documentación Técnica - Tema Reforestamos

Documentación técnica detallada sobre la arquitectura y componentes del tema de WordPress de Reforestamos México.

## Flujo del Tema

### Flujo de Archivos Core

```
themereforestamos/
├── functions.php          # Punto de entrada principal
├── index.php             # Template fallback
├── header.php            # Cabecera del sitio
├── footer.php            # Pie de página
├── style.css             # Información del tema (obligatorio)
├── single.php            # Posts individuales
├── page.php              # Páginas estáticas
├── archive.php           # Archivos y taxonomías
└── 404.php               # Página de error
```

### Sistema de Includes

```php
// En functions.php
require_once get_template_directory() . '/inc/custom-fields.php';
require_once get_template_directory() . '/inc/functions.php';
require_once get_template_directory() . '/inc/config-theme/setup.php';
// ... más includes
```

## Componentes Principales

### 1. Custom Fields (CMB2)

**Ubicación**: `inc/custom-fields.php`

Registro de metaboxes y campos personalizados:

```php
// Ejemplo de metabox para Empresas
add_action('cmb2_admin_init', 'rm_empresas_metaboxes');

function rm_empresas_metaboxes() {
    $cmb = new_cmb2_box([
        'id'           => 'empresa_metabox',
        'title'        => 'Información de la Empresa',
        'object_types' => ['empresas'],
        'context'      => 'normal',
        'priority'     => 'high',
    ]);
    
    $cmb->add_field([
        'name' => 'Logo',
        'id'   => 'empresa_logo',
        'type' => 'file',
    ]);
    
    // Más campos...
}
```

### 2. Custom Post Types

#### Empresas

**Archivo**: `inc/empresas/register-cpt.php`

```php
register_post_type('empresas', [
    'labels' => [
        'name' => 'Empresas',
        'singular_name' => 'Empresa',
    ],
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail'],
    'rewrite' => ['slug' => 'empresas'],
]);
```

**Campos personalizados**:
- Logo (imagen)
- Categoría (select)
- Sitio web (URL)
- Descripción corta (textarea)
- Ubicación (text)

#### Eventos

**Archivo**: `inc/eventos/register-cpt.php`

**Campos personalizados**:
- Fecha del evento (date picker)
- Hora (time picker)
- Ubicación (text)
- Tipo de evento (taxonomy)
- Cupo (number)
- Registro activo (checkbox)

#### Integrantes RMX

**Campos personalizados**:
- Cargo (text)
- Email (email)
- Teléfono (text)
- Foto (image)
- Biografía (wysiwyg)
- Redes sociales (group de URLs)

### 3. Sistema de Traducción (DeepL)

**Ubicación**: `inc/DeepL/`

```php
// Configuración
$deepl_api_key = 'tu-api-key';
$translator = new \DeepL\Translator($deepl_api_key);

// Uso
$translation = $translator->translateText(
    $texto_original, 
    null, 
    'en-US'
);
```

**Funcionalidades**:
- Traducción automática de contenido
- Cache de traducciones
- Fallback a contenido original si falla
- Selector de idioma en frontend

### 4. Sistema de Chatbot

**Ubicación**: `inc/chat-bot/`

**Componentes**:
```
chat-bot/
├── chat-handler.php      # Lógica del chatbot
├── chat-ui.php          # Interfaz del chat
├── chat-responses.php   # Respuestas predefinidas
└── chat-ajax.php        # Endpoints AJAX
```

**Flujo**:
1. Usuario abre chat desde botón flotante
2. Mensaje enviado vía AJAX
3. Procesamiento en `chat-handler.php`
4. Respuesta basada en keywords
5. Display en UI

### 5. PHPMailer Configuration

**Ubicación**: `inc/php-mailer-config/`

```php
// Configuración SMTP
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'tu-email@gmail.com';
$mail->Password = 'tu-password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

**Usos**:
- Formulario de contacto
- Suscripción a boletín
- Notificaciones de eventos
- Sistema de donaciones

### 6. Sistema de Árboles y Ciudades

**Ubicación**: `inc/arboles_ciudades_components/`

**Funcionalidades**:
- Mapa interactivo de proyectos
- Ficha técnica de cada proyecto
- Galería de imágenes
- Indicadores de impacto
- Testimonios

**Integración con mapas**:
```javascript
// Leaflet.js o Google Maps
const mapa = L.map('mapa-arboles').setView([19.4326, -99.1332], 13);

proyectos.forEach(proyecto => {
    L.marker([proyecto.lat, proyecto.lng])
     .bindPopup(proyecto.nombre)
     .addTo(mapa);
});
```

### 7. Red OJA (Observatorios de Jóvenes en Acción)

**Ubicación**: `inc/red_oja_components/`

**Estructura**:
```
red_oja_components/
├── observatorios-cpt.php    # Custom post type
├── mapa-observatorios.php   # Mapa interactivo
├── ficha-observatorio.php   # Template individual
└── estadisticas.php         # Dashboard de métricas
```

**Datos de cada observatorio**:
- Nombre
- Ubicación (coordenadas)
- Responsable
- Contacto
- Proyectos activos
- Participantes
- Recursos

## 🎨 Sistema de Estilos

### Estructura SCSS

```
src/scss/
├── abstracts/
│   ├── _variables.scss    # Variables globales
│   ├── _mixins.scss       # Mixins reutilizables
│   └── _functions.scss    # Funciones SCSS
├── base/
│   ├── _reset.scss        # Reset CSS
│   ├── _typography.scss   # Tipografía
│   └── _utilities.scss    # Clases utilitarias
├── components/
│   ├── _buttons.scss      # Botones
│   ├── _cards.scss        # Cards
│   ├── _forms.scss        # Formularios
│   └── _navbar.scss       # Navegación
├── layout/
│   ├── _header.scss       # Header
│   ├── _footer.scss       # Footer
│   ├── _grid.scss         # Sistema de grid
│   └── _sidebar.scss      # Sidebar
├── pages/
│   ├── _home.scss         # Página inicio
│   ├── _blog.scss         # Blog
│   └── _contacto.scss     # Contacto
└── main.scss              # Import principal
```

### Variables Principales

```scss
// Colores
$color-verde-principal: #2d5016;
$color-verde-claro: #8bc34a;
$color-cafe: #5d4037;

// Breakpoints
$breakpoint-sm: 576px;
$breakpoint-md: 768px;
$breakpoint-lg: 992px;
$breakpoint-xl: 1200px;

// Espaciado
$spacing-unit: 8px;
$spacing-xs: $spacing-unit;
$spacing-sm: $spacing-unit * 2;
$spacing-md: $spacing-unit * 3;
$spacing-lg: $spacing-unit * 4;
$spacing-xl: $spacing-unit * 5;

// Tipografía
$font-family-base: 'Open Sans', sans-serif;
$font-family-headings: 'Montserrat', sans-serif;
```

## 📱 JavaScript Architecture

### Estructura de Scripts

```
src/js/
├── main.js              # Entry point
├── components/
│   ├── carousel.js      # Carousels
│   ├── modal.js         # Modals
│   └── forms.js         # Form validation
├── pages/
│   ├── home.js          # Página inicio
│   └── blog.js          # Blog scripts
└── utils/
    ├── ajax.js          # AJAX helpers
    └── helpers.js       # Utility functions
```

### AJAX en WordPress

```javascript
// Ejemplo: Cargar más posts
function cargarMasPosts() {
    jQuery.ajax({
        url: ajax_params.ajax_url,
        type: 'POST',
        data: {
            action: 'cargar_mas_posts',
            nonce: ajax_params.nonce,
            page: currentPage
        },
        success: function(response) {
            if(response.success) {
                jQuery('#posts-container').append(response.data.html);
                currentPage++;
            }
        }
    });
}

// En functions.php
add_action('wp_ajax_cargar_mas_posts', 'handle_cargar_mas_posts');
add_action('wp_ajax_nopriv_cargar_mas_posts', 'handle_cargar_mas_posts');

function handle_cargar_mas_posts() {
    check_ajax_referer('ajax-nonce', 'nonce');
    
    $page = $_POST['page'];
    $posts = get_posts([
        'posts_per_page' => 6,
        'paged' => $page
    ]);
    
    ob_start();
    foreach($posts as $post) {
        // Template
    }
    $html = ob_get_clean();
    
    wp_send_json_success(['html' => $html]);
}
```

## Seguridad

### Sanitización y Validación

```php
// Input sanitization
$nombre = sanitize_text_field($_POST['nombre']);
$email = sanitize_email($_POST['email']);
$url = esc_url_raw($_POST['url']);
$html = wp_kses_post($_POST['contenido']);

// Output escaping
echo esc_html($texto);
echo esc_attr($atributo);
echo esc_url($url);

// Nonces
wp_nonce_field('formulario_contacto', 'contacto_nonce');

// Verificación
if(!wp_verify_nonce($_POST['contacto_nonce'], 'formulario_contacto')) {
    wp_die('Seguridad fallida');
}
```

### Permisos y Capabilities

```php
// Verificar capabilities
if(!current_user_can('edit_posts')) {
    wp_die('No tienes permisos');
}

// Verificar roles
if(!in_array('administrator', wp_get_current_user()->roles)) {
    return;
}
```

## Base de Datos

### Tablas Personalizadas

Si se necesitan tablas adicionales:

```php
global $wpdb;
$table_name = $wpdb->prefix . 'donaciones';

$sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    usuario_id bigint(20) NOT NULL,
    monto decimal(10,2) NOT NULL,
    fecha datetime DEFAULT CURRENT_TIMESTAMP,
    metodo_pago varchar(50) NOT NULL,
    PRIMARY KEY (id)
) $wpdb->get_charset_collate();";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
```

### Queries Optimizadas

```php
// Usar WP_Query apropiadamente
$args = [
    'post_type' => 'empresas',
    'posts_per_page' => 12,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => [
        [
            'key' => 'empresa_destacada',
            'value' => '1',
            'compare' => '='
        ]
    ]
];

$query = new WP_Query($args);

// Resetear después de usar
wp_reset_postdata();
```

## Optimización y Performance

### Lazy Loading

```php
// Imágenes lazy load
add_filter('wp_get_attachment_image_attributes', 'add_lazy_loading', 10, 2);

function add_lazy_loading($attr, $attachment) {
    $attr['loading'] = 'lazy';
    return $attr;
}
```

### Minificación

```bash
# Scripts de build
npm run build     # Producción (minificado)
npm run dev       # Desarrollo (sin minificar)
npm run watch     # Watch mode
```

### Caché

```php
// Usar transients
$data = get_transient('empresas_destacadas');

if(false === $data) {
    $data = get_posts([...]);
    set_transient('empresas_destacadas', $data, 12 * HOUR_IN_SECONDS);
}
```

## Testing y Debugging

### Debug Mode

```php
// En wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);

// Logging
error_log('Debug message: ' . print_r($variable, true));
```

### Query Monitor

Instalar plugin Query Monitor para:
- Ver queries de base de datos
- Detectar queries lentas
- Ver hooks ejecutados
- Monitorear AJAX requests

## 📦 Deployment

### Checklist Pre-Deploy

- [ ] Desactivar WP_DEBUG
- [ ] Compilar assets (npm run build)
- [ ] Limpiar archivos temporales
- [ ] Verificar .gitignore
- [ ] Backup de base de datos
- [ ] Probar en ambiente staging

### Comandos útiles

```bash
# Compilar para producción
npm run build

# Limpiar node_modules
rm -rf node_modules/
npm install --production

# Crear ZIP del tema
zip -r themereforestamos.zip . -x "node_modules/*" "src/*" ".git/*"
```

## 🔄 Flujos de Trabajo Comunes

### Agregar un Nuevo Custom Post Type

1. Crear archivo en `inc/nuevo-cpt/register.php`
2. Registrar el CPT
3. Crear campos CMB2 en `inc/custom-fields.php`
4. Crear template en `template-parts/`
5. Registrar en `functions.php`
6. Flush rewrite rules

### Agregar Nueva Funcionalidad AJAX

1. Crear función handler en archivo apropiado
2. Registrar action hooks (ajax y nopriv)
3. Crear script JS en `src/js/`
4. Localizar script con wp_localize_script
5. Enqueue script en functions.php

### Modificar Estilos

1. Editar archivos SCSS en `src/scss/`
2. Compilar con `npm run build`
3. Verificar en navegador
4. Commit cambios

## 📚 Referencias Técnicas

### WordPress APIs

- **Post Types API**: Custom post types
- **Taxonomy API**: Categorías y taxonomías custom
- **Options API**: Settings del tema
- **Transients API**: Cache temporal
- **HTTP API**: Requests externos
- **Rewrite API**: Pretty permalinks
- **Shortcode API**: Shortcodes personalizados

### Hooks Importantes

```php
// Theme setup
add_action('after_setup_theme', 'rm_theme_setup');

// Scripts y estilos
add_action('wp_enqueue_scripts', 'rm_enqueue_assets');

// Admin scripts
add_action('admin_enqueue_scripts', 'rm_admin_assets');

// Widgets
add_action('widgets_init', 'rm_register_widgets');

// Filters
add_filter('the_content', 'rm_modify_content');
add_filter('wp_nav_menu_items', 'rm_add_menu_items');
```

---

**Última actualización**: Febrero 2025
