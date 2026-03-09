# Reforestamos Comunicación Plugin

Plugin de comunicación para Reforestamos México que gestiona newsletter, formularios de contacto, chatbot y traducción automática con DeepL.

## Descripción

Este plugin proporciona funcionalidades de comunicación para el sitio web de Reforestamos México:

- **Newsletter**: Sistema de gestión de boletines y suscriptores
- **Formularios de Contacto**: Formularios personalizables con protección anti-spam
- **ChatBot**: Widget de chat interactivo para respuestas automáticas
- **Traducción DeepL**: Integración con DeepL API para traducción automática de contenido

## Requisitos

- WordPress 6.0 o superior
- PHP 7.4 o superior
- Servidor SMTP configurado (opcional, para envío de emails)

## Instalación

1. Sube la carpeta `reforestamos-comunicacion` al directorio `/wp-content/plugins/`
2. Activa el plugin desde el menú 'Plugins' en WordPress
3. Configura los ajustes SMTP en Comunicación > Configuración

## Configuración

### SMTP Settings

Para enviar emails de forma confiable, configura los ajustes SMTP:

1. Ve a **Comunicación > Configuración**
2. Ingresa los datos de tu servidor SMTP:
   - Host SMTP
   - Puerto (587 para TLS, 465 para SSL)
   - Usuario SMTP
   - Contraseña SMTP
   - Email remitente
   - Nombre remitente

### DeepL API

Para habilitar la traducción automática:

1. Obtén una API key de [DeepL](https://www.deepl.com/pro-api)
2. Ve a **Comunicación > Configuración**
3. Ingresa tu API key de DeepL

## Uso

### Formulario de Contacto

Usa el shortcode `[contact-form]` para insertar un formulario de contacto:

```
[contact-form title="Contáctanos" show_phone="yes" show_address="yes"]
```

**Atributos:**
- `title`: Título del formulario (default: "Contáctanos")
- `show_phone`: Mostrar campo de teléfono (yes/no, default: yes)
- `show_address`: Mostrar información de dirección (yes/no, default: yes)

### Newsletter

Usa el shortcode `[newsletter-subscribe]` para insertar un formulario de suscripción:

```
[newsletter-subscribe]
```

### ChatBot

El chatbot se muestra automáticamente en todas las páginas. Puedes configurar las respuestas en **Comunicación > ChatBot**.

## Estructura del Plugin

```
reforestamos-comunicacion/
├── includes/              # Clases PHP principales
│   ├── class-reforestamos-comunicacion.php
│   ├── class-mailer.php
│   ├── class-newsletter.php (próximamente)
│   ├── class-contact-form.php (próximamente)
│   ├── class-chatbot.php (próximamente)
│   └── class-deepl-integration.php (próximamente)
├── admin/                 # Archivos del área de administración
│   ├── css/
│   ├── js/
│   └── views/
├── assets/                # Assets del frontend
│   ├── css/
│   └── js/
├── templates/             # Plantillas de email y formularios
└── languages/             # Archivos de traducción
```

## Base de Datos

El plugin crea las siguientes tablas:

- `wp_reforestamos_subscribers`: Suscriptores del newsletter
- `wp_reforestamos_submissions`: Envíos de formularios de contacto
- `wp_reforestamos_chatbot_logs`: Logs de conversaciones del chatbot

## Hooks y Filtros

### Actions

- `reforestamos_comm_before_send_email`: Antes de enviar un email
- `reforestamos_comm_after_send_email`: Después de enviar un email
- `reforestamos_comm_form_submitted`: Cuando se envía un formulario

### Filters

- `reforestamos_comm_email_content`: Filtrar contenido del email
- `reforestamos_comm_form_fields`: Filtrar campos del formulario
- `reforestamos_comm_chatbot_response`: Filtrar respuesta del chatbot

## Desarrollo

### Requisitos de Desarrollo

- Node.js y npm (para compilar assets si es necesario)
- Composer (para dependencias PHP)

### Testing

```bash
# Ejecutar tests PHP
composer test

# Ejecutar tests JavaScript
npm test
```

## Seguridad

- Todos los inputs son sanitizados usando funciones de WordPress
- Protección anti-spam con honeypot en formularios
- Verificación de nonce en todas las peticiones AJAX
- Contraseñas SMTP almacenadas de forma segura

## Soporte

Para soporte y reportar bugs, contacta a [soporte@reforestamos.org](mailto:soporte@reforestamos.org)

## Changelog

### 1.0.0
- Versión inicial
- Estructura base del plugin
- Configuración de PHPMailer/SMTP
- Sistema de base de datos

## Licencia

GPL v2 or later

## Créditos

Desarrollado por Reforestamos México
