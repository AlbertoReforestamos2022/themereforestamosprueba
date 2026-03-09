# DeepL Rate Limit Handling

## Overview

El sistema de traducción DeepL incluye manejo automático de límites de tasa (rate limits) y cuotas de caracteres. Cuando se alcanza un límite, las traducciones se agregan automáticamente a una cola para procesamiento posterior.

## Características

### 1. Detección de Límites

El sistema detecta automáticamente dos tipos de límites de la API de DeepL:

- **HTTP 429 - Rate Limit**: Límite de solicitudes por período de tiempo
- **HTTP 456 - Quota Exceeded**: Cuota de caracteres mensuales excedida

### 2. Cola de Traducciones

Cuando se detecta un límite, la traducción se agrega automáticamente a una cola de procesamiento:

```
wp_reforestamos_translation_queue
├── id (bigint) - ID único de la cola
├── post_id (bigint) - ID del post a traducir
├── target_lang (varchar) - Idioma destino (EN/ES)
├── source_lang (varchar) - Idioma origen (EN/ES)
├── status (varchar) - Estado: pending, processing, completed, failed, rate_limited
├── priority (int) - Prioridad (mayor = primero)
├── retry_count (int) - Número de reintentos
├── error_message (text) - Mensaje de error si falla
├── created_at (datetime) - Fecha de creación
└── processed_at (datetime) - Fecha de procesamiento
```

### 3. Procesamiento Automático

La cola se procesa automáticamente cada hora mediante WP-Cron:

- Procesa hasta 10 traducciones por ejecución
- Respeta los límites de tasa durante el procesamiento
- Implementa reintento con backoff exponencial
- Pausa 2 segundos entre traducciones para evitar límites

### 4. Estados de la Cola

| Estado | Descripción |
|--------|-------------|
| `pending` | Esperando ser procesada |
| `processing` | Actualmente en proceso |
| `completed` | Traducción completada exitosamente |
| `failed` | Falló después de 3 reintentos |
| `rate_limited` | Esperando que se restablezca el límite |

## Uso

### Interfaz de Usuario

Cuando un usuario intenta traducir un post y se alcanza el límite:

1. Se muestra un mensaje: "⏱ Traducción agregada a la cola"
2. La traducción se procesará automáticamente cuando el límite se restablezca
3. El usuario puede continuar trabajando sin interrupciones

### Panel de Administración

En **Comunicación > DeepL** se muestra:

#### Estado de la API
- Uso de caracteres actual
- Caracteres restantes
- Porcentaje de uso
- Advertencias cuando se acerca al límite (>75%)

#### Cola de Traducciones
- Traducciones pendientes
- Traducciones en proceso
- Traducciones completadas
- Traducciones fallidas
- Traducciones esperando por límite de tasa

## Límites de DeepL

### API Gratuita
- **Caracteres**: 500,000 caracteres/mes
- **Rate Limit**: Variable según carga del servidor

### API Pro
- **Caracteres**: Según plan contratado
- **Rate Limit**: Mayor capacidad que API gratuita

## Manejo de Errores

### Reintento Automático

El sistema reintenta automáticamente las traducciones fallidas:

1. **Primer intento**: Inmediato
2. **Segundo intento**: Después de 1 hora (siguiente ejecución de cron)
3. **Tercer intento**: Después de 2 horas
4. **Después de 3 intentos**: Marcado como fallido

### Errores de Rate Limit

Las traducciones con error de rate limit:
- Se marcan con estado `rate_limited`
- Se reintentan en la siguiente ejecución de cron
- No cuentan para el límite de 3 reintentos

### Errores de Cuota

Cuando se excede la cuota mensual:
- Las traducciones permanecen en cola
- Se procesarán automáticamente cuando se restablezca la cuota
- Se muestra advertencia en el panel de administración

## Procesamiento Manual

### Forzar Procesamiento

Para procesar la cola manualmente (útil para desarrollo/testing):

```php
// Obtener instancia de DeepL
$deepl = Reforestamos_DeepL_Integration::get_instance();

// Procesar cola
$deepl->process_queue();
```

### Reintentar Traducción Fallida

```php
// Reintentar una traducción específica
$deepl->retry_failed_translation( $queue_id );
```

### Obtener Estadísticas

```php
// Obtener estadísticas de la cola
$stats = $deepl->get_queue_status();
// Retorna: ['pending' => 5, 'processing' => 1, 'completed' => 20, 'failed' => 2, 'rate_limited' => 3]
```

## Optimización

### Reducir Uso de Caracteres

1. **Traducir solo contenido necesario**: El sistema ya excluye campos no traducibles
2. **Revisar antes de traducir**: Asegurarse de que el contenido esté finalizado
3. **Usar traducciones existentes**: El sistema actualiza traducciones existentes en lugar de crear duplicados

### Priorizar Traducciones

Al agregar a la cola programáticamente, se puede especificar prioridad:

```php
$deepl->queue_translation( $post_id, 'EN', 'ES', $priority = 10 );
```

Mayor prioridad = procesado primero.

## Monitoreo

### Logs

Los errores de traducción se registran en el log de errores de WordPress:

```
DeepL Translation: Failed to translate custom field "evento_ubicacion" for post 123: Rate limit exceeded
```

### Cron Jobs

Verificar que el cron job esté programado:

```php
wp_next_scheduled( 'reforestamos_process_translation_queue' );
```

### Base de Datos

Consultar estado de la cola directamente:

```sql
SELECT status, COUNT(*) as count 
FROM wp_reforestamos_translation_queue 
GROUP BY status;
```

## Troubleshooting

### La cola no se procesa

1. Verificar que WP-Cron esté funcionando:
   ```php
   wp cron event list
   ```

2. Ejecutar manualmente:
   ```php
   do_action( 'reforestamos_process_translation_queue' );
   ```

3. Verificar configuración de DeepL en **Comunicación > DeepL**

### Traducciones fallidas

1. Revisar mensaje de error en la tabla de cola
2. Verificar API key de DeepL
3. Verificar conectividad con api.deepl.com
4. Reintentar manualmente desde el panel de administración

### Límite alcanzado frecuentemente

1. Revisar uso de caracteres en panel de DeepL
2. Considerar actualizar a plan Pro
3. Optimizar contenido antes de traducir
4. Espaciar traducciones a lo largo del mes

## Seguridad

- Las API keys se almacenan de forma segura en opciones de WordPress
- Los nonces protegen las solicitudes AJAX
- Solo usuarios con capacidad `edit_posts` pueden traducir
- La cola solo procesa posts existentes y válidos

## Compatibilidad

- WordPress 6.0+
- PHP 7.4+
- DeepL API Free o Pro
- Compatible con todos los Custom Post Types registrados

## Referencias

- [DeepL API Documentation](https://www.deepl.com/docs-api)
- [WordPress Cron API](https://developer.wordpress.org/plugins/cron/)
- [WP_Error Class](https://developer.wordpress.org/reference/classes/wp_error/)
