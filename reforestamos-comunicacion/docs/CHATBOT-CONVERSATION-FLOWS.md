# Sistema de Flujos de Conversación del ChatBot

## Descripción General

El sistema de flujos de conversación permite crear diálogos interactivos guiados donde el chatbot puede hacer preguntas y responder según las opciones elegidas por el usuario.

## Estructura de Datos

### Conversación (Conversation)

Una conversación es una estructura de datos que define un flujo de diálogo completo:

```php
array(
    'name' => 'Nombre del flujo',
    'trigger_keywords' => 'palabra1|palabra2|palabra3',
    'steps' => array(
        // Array de pasos
    ),
    'completion_message' => 'Mensaje final'
)
```

### Paso (Step)

Cada paso en una conversación contiene:

```php
array(
    'message' => 'Pregunta o mensaje del bot',
    'options' => array(
        'option_key' => array(
            'keywords' => 'palabra1|palabra2',
            'response' => 'Respuesta para esta opción',
            'next_step' => 1 // Opcional: índice del siguiente paso
        )
    ),
    'help_message' => 'Mensaje si no se entiende la respuesta'
)
```

## Sistema de Matching Mejorado

### Algoritmo de Coincidencia

El sistema utiliza un algoritmo de puntuación para encontrar la mejor coincidencia:

1. **Coincidencia exacta**: Si el mensaje del usuario coincide exactamente con una palabra clave, se retorna inmediatamente
2. **Coincidencia parcial**: Se buscan palabras clave dentro del mensaje
3. **Puntuación**: 
   - Palabras clave más largas reciben mayor puntuación
   - Múltiples coincidencias aumentan la puntuación
4. **Umbral**: Solo se retorna una respuesta si la puntuación es suficientemente alta

### Ejemplo de Matching

```
Usuario: "quiero ser voluntario en eventos"
Keywords: "voluntario|participar|colaborar"
Resultado: ✓ Coincidencia (palabra "voluntario" encontrada)

Usuario: "información sobre proyectos"
Keywords: "proyecto|proyectos|iniciativa"
Resultado: ✓ Coincidencia (palabra "proyectos" encontrada)
```

## Flujos de Conversación Predefinidos

### 1. Flujo de Voluntariado

**Trigger**: `voluntario|participar|colaborar`

**Pasos**:
1. Pregunta sobre tipo de participación (eventos vs. otras formas)
2. Si elige eventos: ofrece información de registro
3. Pregunta si desea contacto por email

**Salidas**:
- Información sobre eventos
- Información sobre otras formas de colaborar
- Oferta de contacto

### 2. Flujo de Donación

**Trigger**: `donar|donación|apoyo económico`

**Pasos**:
1. Pregunta sobre tipo de donación (única vs. recurrente)

**Salidas**:
- Información sobre donación única
- Información sobre donación recurrente
- Información sobre uso de fondos

### 3. Flujo de Eventos

**Trigger**: `evento|eventos|actividad`

**Pasos**:
1. Pregunta sobre qué información necesita (próximos eventos vs. cómo funcionan)

**Salidas**:
- Referencia a página de eventos
- Información sobre cómo funcionan las jornadas

## Estado de Conversación

### Almacenamiento

El estado de la conversación se almacena usando WordPress Transients:

- **Clave**: `chatbot_state_{session_id}`
- **Duración**: 30 minutos
- **Contenido**: 
  ```php
  array(
      'flow' => 'nombre_del_flujo',
      'step' => 0, // índice del paso actual
      'data' => array() // datos recopilados
  )
  ```

### Gestión del Estado

- **Inicio**: Se crea cuando se detecta un trigger keyword
- **Actualización**: Se actualiza en cada respuesta del usuario
- **Limpieza**: Se elimina al completar el flujo o usar palabras de salida
- **Expiración**: Se elimina automáticamente después de 30 minutos de inactividad

## Palabras de Salida

El usuario puede salir de un flujo en cualquier momento usando:

- `salir`
- `cancelar`
- `no gracias`
- `atrás`
- `volver`

## Personalización

### Agregar un Nuevo Flujo

Para agregar un nuevo flujo de conversación, edita el método `get_default_flows()` en `class-chatbot.php`:

```php
'mi_flujo' => array(
    'name' => __( 'Mi Flujo Personalizado', 'reforestamos-comunicacion' ),
    'trigger_keywords' => 'palabra1|palabra2',
    'steps' => array(
        array(
            'message' => __( 'Primera pregunta', 'reforestamos-comunicacion' ),
            'options' => array(
                'opcion1' => array(
                    'keywords' => 'si|claro|ok',
                    'response' => __( 'Respuesta para opción 1', 'reforestamos-comunicacion' ),
                ),
                'opcion2' => array(
                    'keywords' => 'no|después',
                    'response' => __( 'Respuesta para opción 2', 'reforestamos-comunicacion' ),
                ),
            ),
        ),
    ),
    'completion_message' => __( 'Mensaje final', 'reforestamos-comunicacion' ),
),
```

### Modificar Respuestas Simples

Las respuestas simples (sin flujo) se pueden modificar desde el panel de administración:

**Comunicación > ChatBot > Respuestas Predefinidas**

## Logging y Análisis

Todas las conversaciones se registran en la tabla `wp_reforestamos_chatbot_logs`:

- `session_id`: Identificador único de la sesión
- `user_message`: Mensaje del usuario
- `bot_response`: Respuesta del bot
- `created_at`: Timestamp

Esto permite:
- Analizar patrones de uso
- Identificar preguntas frecuentes no cubiertas
- Mejorar las respuestas del chatbot

## Requisitos Cumplidos

- **10.4**: Sistema de matching de preguntas con algoritmo de puntuación
- **10.5**: Flujos de conversación predefinidos con múltiples pasos
- **10.6**: Logging completo de conversaciones
- **10.8**: Respuestas rápidas (< 2 segundos)

## Mejoras Futuras

1. **Editor visual de flujos**: Interfaz gráfica para crear flujos sin código
2. **Machine Learning**: Mejorar matching usando ML
3. **Análisis de sentimiento**: Detectar frustración del usuario
4. **Integración con CRM**: Guardar leads de usuarios interesados
5. **Respuestas contextuales**: Usar información de la página actual
