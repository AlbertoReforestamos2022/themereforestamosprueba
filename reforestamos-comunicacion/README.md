# Reforestamos Comunicación

Communication plugin for Reforestamos México. Provides newsletter management, contact forms, chatbot, and DeepL translation integration.

## Requirements

- WordPress 6.0+
- PHP 7.4+
- SMTP server (for email sending)
- DeepL API key (optional, for translation features)

## Installation

1. Upload `reforestamos-comunicacion/` to `wp-content/plugins/`
2. Activate in WordPress Admin → Plugins
3. Configure SMTP settings under **Comunicación → Settings**
4. (Optional) Add DeepL API key for translation features

## Features

### Newsletter System
- Campaign creation and management
- Subscriber management with double opt-in
- Mass email sending with rate limiting
- Delivery status logging and retry
- One-click unsubscribe links

### Contact Forms
- Shortcode-based contact forms
- Server-side and client-side validation
- Honeypot anti-spam protection
- Email notifications via PHPMailer
- Form submission storage in database

### Chatbot
- Frontend chat widget
- Predefined conversation flows
- Admin configuration interface
- Conversation logging and analytics
- Global enable/disable toggle

### DeepL Translation
- Automatic content translation (ES ↔ EN)
- Post editor meta box with translate buttons
- HTML formatting preservation
- Custom field translation
- API rate limit handling with queue

## Shortcodes

### `[newsletter-subscribe]`

Subscription form with email validation and double opt-in.

```html
[newsletter-subscribe]
```

### `[contact-form]`

Contact form with fields: name, email, subject, message.

```html
[contact-form]
```

## Configuration

### SMTP Settings

Navigate to **Comunicación → Settings** to configure:
- SMTP host, port, encryption
- Authentication credentials
- From name and email address

### DeepL API

Under **Comunicación → Translation**:
- Enter your DeepL API key (stored encrypted)
- Select source/target languages
- Configure rate limit behavior

### Chatbot

Under **Comunicación → Chatbot**:
- Enable/disable chatbot globally
- Configure conversation flows and responses
- View conversation logs

## Directory Structure

```
reforestamos-comunicacion/
├── reforestamos-comunicacion.php       # Main plugin file
├── includes/
│   ├── class-reforestamos-comunicacion.php  # Main plugin class
│   ├── class-newsletter.php            # Newsletter management
│   ├── class-mailer.php                # PHPMailer wrapper
│   ├── class-contact-form.php          # Contact form handling
│   ├── class-chatbot.php               # Chatbot engine
│   ├── class-deepl-integration.php     # DeepL API integration
│   ├── class-certificate-generator.php # Certificate generation
│   ├── class-tree-adoption.php         # Tree adoption system
│   └── class-payment-gateway.php       # Payment processing
├── admin/
│   ├── views/
│   │   ├── campaigns-page.php          # Newsletter campaigns
│   │   ├── send-logs-page.php          # Email send logs
│   │   ├── newsletter-settings-page.php # Newsletter settings
│   │   ├── submissions-list.php        # Contact form submissions
│   │   ├── chatbot-config.php          # Chatbot configuration
│   │   ├── chatbot-logs.php            # Chatbot conversation logs
│   │   └── adoptions-dashboard.php     # Tree adoptions dashboard
│   ├── css/admin.css                   # Admin styles
│   └── js/                             # Admin scripts
├── assets/
│   ├── js/
│   │   ├── frontend.js                 # Newsletter/form frontend JS
│   │   └── chatbot.js                  # Chatbot widget JS
│   └── css/                            # Frontend styles
├── templates/
│   └── forms/
│       └── contact-form-template.php   # Contact form template
├── languages/
│   └── reforestamos-comunicacion.pot   # Translation template
├── tests/                              # Test files
├── docs/                               # Feature documentation
└── uninstall.php                       # Clean uninstall
```

## API & Hooks

### Actions

| Hook | Description |
|------|-------------|
| `reforestamos_comunicacion_init` | Fires after plugin initialization |
| `reforestamos_newsletter_sent` | Fires after a newsletter is sent. Args: `$campaign_id`, `$recipient_count` |
| `reforestamos_contact_form_submitted` | Fires after form submission. Args: `$form_data` |
| `reforestamos_chatbot_message` | Fires on chatbot message. Args: `$message`, `$response` |
| `reforestamos_translation_complete` | Fires after translation. Args: `$post_id`, `$target_lang` |
| `reforestamos_subscriber_added` | Fires when a subscriber is added. Args: `$email` |
| `reforestamos_subscriber_removed` | Fires when a subscriber unsubscribes. Args: `$email` |

### Filters

| Filter | Description |
|--------|-------------|
| `reforestamos_contact_form_fields` | Filter contact form fields array |
| `reforestamos_contact_form_validation` | Filter form validation rules |
| `reforestamos_newsletter_template` | Filter newsletter email template |
| `reforestamos_chatbot_response` | Filter chatbot response before sending |
| `reforestamos_deepl_content` | Filter content before sending to DeepL |
| `reforestamos_deepl_translated` | Filter translated content before saving |
| `reforestamos_mailer_headers` | Filter email headers |
| `reforestamos_spam_check` | Filter spam detection result |

### REST API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/reforestamos-comm/v1/subscribe` | POST | Newsletter subscription |
| `/reforestamos-comm/v1/unsubscribe` | POST | Newsletter unsubscription |
| `/reforestamos-comm/v1/contact` | POST | Contact form submission |
| `/reforestamos-comm/v1/chatbot` | POST | Chatbot message |
| `/reforestamos-comm/v1/translate` | POST | Trigger translation (auth required) |

## Database Tables

The plugin creates custom tables on activation:

| Table | Purpose |
|-------|---------|
| `{prefix}reforestamos_subscribers` | Newsletter subscribers |
| `{prefix}reforestamos_send_logs` | Email delivery logs |
| `{prefix}reforestamos_submissions` | Contact form submissions |
| `{prefix}reforestamos_chatbot_logs` | Chatbot conversation logs |

## Security

- All form inputs sanitized and validated
- Honeypot fields for spam protection
- Rate limiting on contact forms
- API credentials stored encrypted
- Nonce verification on all AJAX requests
- Prepared statements for all database queries

## Uninstall

Deleting the plugin via `uninstall.php` removes:
- All custom database tables
- Plugin options from `wp_options`
- Scheduled cron events

## License

GPL v2 or later
