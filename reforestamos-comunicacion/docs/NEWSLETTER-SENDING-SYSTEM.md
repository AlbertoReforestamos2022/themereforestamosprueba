# Sistema de Envío de Newsletters

## Descripción General

El sistema de envío de newsletters de Reforestamos Comunicación incluye funcionalidades avanzadas para gestionar el envío masivo de correos electrónicos de manera eficiente y confiable.

## Características Principales

### 1. Sistema de Unsubscribe

Todos los emails incluyen automáticamente un enlace seguro de un solo clic para que los suscriptores puedan darse de baja.

**Características:**
- Enlaces seguros con tokens criptográficos
- Un solo clic, sin login requerido
- Página de confirmación clara
- Cumple con GDPR y CAN-SPAM Act

**Documentación completa:** Ver [UNSUBSCRIBE-SYSTEM.md](./UNSUBSCRIBE-SYSTEM.md)

### 2. Rate Limiting Configurable

El sistema permite configurar límites de envío para evitar sobrecargar el servidor SMTP y cumplir con los límites de los proveedores de email.

**Configuración:**
- **Tamaño de Lote**: Número de emails a enviar antes de hacer una pausa (por defecto: 50)
- **Pausa entre Lotes**: Tiempo de espera en segundos entre lotes (por defecto: 2 segundos)

**Ubicación:** Boletín > Configuración

### 3. Procesamiento en Segundo Plano

Para listas grandes de suscriptores, el sistema puede programar el envío en lotes usando WP Cron, evitando timeouts del navegador.

**Funcionamiento:**
- Cuando hay más destinatarios que el tamaño de lote configurado, el sistema divide el envío en múltiples lotes
- Cada lote se programa con 1 minuto de diferencia
- Los lotes se procesan automáticamente en segundo plano

**Activación:** Boletín > Configuración > "Usar Procesamiento en Segundo Plano"

### 4. Logging de Estado de Envío

Todos los intentos de envío se registran en la base de datos con información detallada.

**Información Registrada:**
- ID del newsletter
- ID del suscriptor
- Email del destinatario
- Estado (sent, failed, pending)
- Fecha y hora de envío
- Número de reintentos
- Mensaje de error (si aplica)

**Acceso:** Boletín > Logs de Envío

### 5. Sistema de Reintentos

El sistema incluye un mecanismo robusto para reintentar envíos fallidos.

#### Reintento Manual
- Desde la página de Logs de Envío, filtra por newsletter
- Haz clic en "Reintentar Envíos Fallidos"
- El sistema reintentará todos los envíos fallidos que no hayan excedido el máximo de reintentos

#### Reintento Automático
- Configurable desde Boletín > Configuración
- Cuando está activado, el sistema reintenta automáticamente cada hora
- Solo reintenta envíos que no hayan alcanzado el máximo de reintentos (por defecto: 3)

**Configuración:**
- **Máximo de Reintentos**: Número máximo de intentos por email (por defecto: 3)
- **Reintento Automático**: Activar/desactivar reintentos automáticos cada hora

### 5. Prevención de Duplicados

El sistema verifica antes de enviar si un email ya fue enviado exitosamente a un destinatario para evitar duplicados.

## Flujo de Envío

```
1. Usuario selecciona newsletter y destinatarios
   ↓
2. Sistema verifica configuración de rate limiting
   ↓
3. ¿Usar procesamiento en segundo plano?
   ├─ SÍ → Divide en lotes y programa con WP Cron
   └─ NO → Envía inmediatamente con rate limiting
   ↓
4. Para cada destinatario:
   ├─ Verifica si ya fue enviado (evita duplicados)
   ├─ Prepara contenido personalizado
   ├─ Intenta enviar email
   └─ Registra resultado en logs
   ↓
5. Si hay fallos:
   ├─ Reintento manual desde admin
   └─ Reintento automático (si está activado)
```

## Configuración Recomendada

### Listas Pequeñas (< 500 suscriptores)
- Tamaño de lote: 50
- Pausa: 2 segundos
- Procesamiento en segundo plano: No
- Reintento automático: No

### Listas Medianas (500-2000 suscriptores)
- Tamaño de lote: 100
- Pausa: 3 segundos
- Procesamiento en segundo plano: Sí
- Reintento automático: Opcional

### Listas Grandes (> 2000 suscriptores)
- Tamaño de lote: 100
- Pausa: 5 segundos
- Procesamiento en segundo plano: Sí
- Reintento automático: Sí

## Límites de Proveedores Comunes

| Proveedor | Límite Diario | Límite por Hora | Recomendación |
|-----------|---------------|-----------------|---------------|
| Gmail (gratuito) | 500 | ~20 | Lote: 20, Pausa: 5s |
| Google Workspace | 2000 | ~80 | Lote: 50, Pausa: 3s |
| SendGrid (gratuito) | 100 | ~10 | Lote: 10, Pausa: 10s |
| Mailgun (gratuito) | ~160 | ~10 | Lote: 10, Pausa: 10s |

## Monitoreo y Troubleshooting

### Ver Estado de Envíos
1. Ir a Boletín > Logs de Envío
2. Filtrar por newsletter o estado
3. Revisar estadísticas en las tarjetas superiores

### Verificar Tareas Programadas
1. Ir a Boletín > Configuración
2. Revisar sección "Información del Sistema"
3. Ver número de lotes programados

### Solución de Problemas Comunes

**Problema: Los emails no se envían en segundo plano**
- Verificar que WP Cron no esté desactivado (DISABLE_WP_CRON)
- Verificar que el servidor ejecute WP Cron correctamente
- Considerar usar un cron real del servidor en lugar de WP Cron

**Problema: Muchos envíos fallidos**
- Verificar configuración SMTP en Configuración del plugin
- Reducir tamaño de lote y aumentar pausa
- Verificar límites del proveedor de email
- Revisar mensajes de error en los logs

**Problema: Emails duplicados**
- El sistema previene duplicados automáticamente
- Si ocurren, verificar que la tabla de logs esté funcionando correctamente

## Base de Datos

### Tabla: wp_reforestamos_newsletter_logs

```sql
CREATE TABLE wp_reforestamos_newsletter_logs (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    newsletter_id bigint(20) NOT NULL,
    subscriber_id bigint(20) NOT NULL,
    email varchar(255) NOT NULL,
    status varchar(20) DEFAULT 'pending',
    sent_at datetime DEFAULT CURRENT_TIMESTAMP,
    retry_count int(11) DEFAULT 0,
    error_message text,
    PRIMARY KEY (id),
    KEY newsletter_id (newsletter_id),
    KEY subscriber_id (subscriber_id),
    KEY status (status)
);
```

## Hooks y Filtros

### Actions

- `reforestamos_process_newsletter_batch` - Procesa un lote de newsletter (WP Cron)
- `reforestamos_retry_failed_newsletter` - Reintenta envíos fallidos (WP Cron, cada hora si está activado)

### Opciones de WordPress

- `reforestamos_newsletter_batch_size` - Tamaño de lote (default: 50)
- `reforestamos_newsletter_batch_delay` - Pausa en segundos (default: 2)
- `reforestamos_newsletter_max_retries` - Máximo de reintentos (default: 3)
- `reforestamos_newsletter_use_cron` - Usar WP Cron (default: 'yes')
- `reforestamos_newsletter_auto_retry` - Reintento automático (default: 'no')

## Requisitos

- WordPress 6.0+
- PHP 7.4+
- WP Cron activado (para procesamiento en segundo plano)
- Plugin Reforestamos Core activado (para CPT Boletín)
- Configuración SMTP válida

## Validación de Requisitos

El sistema cumple con los siguientes requisitos de la especificación:

- **Requirement 8.4**: Usa PHPMailer para envío de emails ✓
- **Requirement 8.5**: Registra estado de envío para cada destinatario ✓
- **Requirement 8.9**: Maneja fallos gracefully y reintenta envíos fallidos ✓

## Soporte

Para más información o soporte, consultar:
- README.md del plugin
- Documentación de WordPress sobre WP Cron
- Documentación del proveedor SMTP utilizado
