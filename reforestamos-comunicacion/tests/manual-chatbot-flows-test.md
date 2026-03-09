# Manual Test: ChatBot Conversation Flows

## Objetivo
Verificar que el sistema de flujos de conversación del chatbot funciona correctamente.

## Prerequisitos
- Plugin Reforestamos Comunicación activado
- ChatBot habilitado en Comunicación > ChatBot

## Test 1: Respuestas Simples (Sin Flujo)

### Pasos:
1. Abrir el sitio web en el frontend
2. Hacer clic en el botón del chatbot (esquina inferior derecha)
3. Enviar mensaje: "hola"

### Resultado Esperado:
- ✓ El chatbot responde: "¡Hola! Bienvenido a Reforestamos México. ¿En qué puedo ayudarte?"

### Pasos:
4. Enviar mensaje: "gracias"

### Resultado Esperado:
- ✓ El chatbot responde: "¡De nada! Estamos aquí para ayudarte. ¿Hay algo más en lo que pueda asistirte?"

---

## Test 2: Flujo de Voluntariado - Opción Eventos

### Pasos:
1. Abrir nueva ventana de incógnito (para nueva sesión)
2. Abrir el chatbot
3. Enviar mensaje: "quiero ser voluntario"

### Resultado Esperado:
- ✓ El chatbot responde preguntando sobre tipo de participación
- ✓ Mensaje contiene opciones: eventos de reforestación o colaborar de otra manera

### Pasos:
4. Enviar mensaje: "eventos de reforestación"

### Resultado Esperado:
- ✓ El chatbot responde con información sobre eventos
- ✓ Pregunta si desea recibir información por email

### Pasos:
5. Enviar mensaje: "sí, por favor"

### Resultado Esperado:
- ✓ El chatbot responde indicando que envíe su email por formulario de contacto
- ✓ Mensaje de agradecimiento por el interés

---

## Test 3: Flujo de Voluntariado - Opción Otras Formas

### Pasos:
1. Abrir nueva ventana de incógnito
2. Abrir el chatbot
3. Enviar mensaje: "quiero colaborar"

### Resultado Esperado:
- ✓ El chatbot responde preguntando sobre tipo de participación

### Pasos:
4. Enviar mensaje: "otra forma diferente"

### Resultado Esperado:
- ✓ El chatbot responde con información sobre donaciones, difusión, voluntariado en oficina
- ✓ Referencia a página de Participación

---

## Test 4: Flujo de Donación

### Pasos:
1. Abrir nueva ventana de incógnito
2. Abrir el chatbot
3. Enviar mensaje: "quiero hacer una donación"

### Resultado Esperado:
- ✓ El chatbot responde preguntando sobre tipo de donación (única o recurrente)

### Pasos:
4. Enviar mensaje: "donación única"

### Resultado Esperado:
- ✓ El chatbot responde con información sobre donación única
- ✓ Menciona métodos de pago: tarjeta, PayPal, transferencia

### Pasos (alternativo):
4. Enviar mensaje: "donación mensual"

### Resultado Esperado:
- ✓ El chatbot responde con información sobre donaciones recurrentes
- ✓ Menciona beneficios de donaciones mensuales

### Pasos (alternativo):
4. Enviar mensaje: "más información"

### Resultado Esperado:
- ✓ El chatbot responde con información sobre uso de fondos
- ✓ Referencia a página de Transparencia

---

## Test 5: Flujo de Eventos

### Pasos:
1. Abrir nueva ventana de incógnito
2. Abrir el chatbot
3. Enviar mensaje: "cuándo son los próximos eventos"

### Resultado Esperado:
- ✓ El chatbot responde preguntando qué información necesita

### Pasos:
4. Enviar mensaje: "fechas de eventos"

### Resultado Esperado:
- ✓ El chatbot responde con referencia a sección de Eventos
- ✓ Menciona que ahí encontrará fechas, ubicaciones y registro

### Pasos (alternativo):
4. Enviar mensaje: "cómo funcionan"

### Resultado Esperado:
- ✓ El chatbot responde con información sobre las jornadas
- ✓ Menciona: herramientas, capacitación, duración (3-4 horas)

---

## Test 6: Salir de un Flujo

### Pasos:
1. Abrir nueva ventana de incógnito
2. Abrir el chatbot
3. Enviar mensaje: "quiero ser voluntario"
4. Esperar respuesta del bot
5. Enviar mensaje: "cancelar"

### Resultado Esperado:
- ✓ El chatbot responde: "Entendido. ¿Hay algo más en lo que pueda ayudarte?"
- ✓ El flujo se cancela y vuelve al modo de respuestas simples

### Pasos:
6. Enviar mensaje: "hola"

### Resultado Esperado:
- ✓ El chatbot responde con el saludo estándar (no continúa el flujo anterior)

---

## Test 7: Matching Mejorado

### Pasos:
1. Abrir nueva ventana de incógnito
2. Abrir el chatbot
3. Enviar mensaje: "me gustaría participar como voluntario en sus eventos"

### Resultado Esperado:
- ✓ El chatbot detecta "voluntario" y "participar"
- ✓ Inicia el flujo de voluntariado

### Pasos:
4. Enviar mensaje: "prefiero los eventos de plantar árboles"

### Resultado Esperado:
- ✓ El chatbot detecta "eventos" y "árboles"
- ✓ Responde con información sobre eventos de reforestación

---

## Test 8: Mensaje No Reconocido

### Pasos:
1. Abrir nueva ventana de incógnito
2. Abrir el chatbot
3. Enviar mensaje: "xyz123 mensaje aleatorio sin sentido"

### Resultado Esperado:
- ✓ El chatbot responde con mensaje por defecto
- ✓ Mensaje sugiere contactar directamente o visitar FAQ

---

## Test 9: Persistencia de Estado

### Pasos:
1. Abrir el chatbot
2. Enviar mensaje: "quiero donar"
3. Esperar respuesta
4. **NO responder, esperar 2 minutos**
5. Enviar mensaje: "donación única"

### Resultado Esperado:
- ✓ El chatbot mantiene el contexto del flujo
- ✓ Responde apropiadamente a "donación única" dentro del flujo

---

## Test 10: Logging de Conversaciones

### Pasos:
1. Realizar cualquiera de los tests anteriores
2. Ir a phpMyAdmin o usar WP-CLI
3. Consultar tabla: `wp_reforestamos_chatbot_logs`

### Resultado Esperado:
- ✓ Cada mensaje del usuario está registrado
- ✓ Cada respuesta del bot está registrada
- ✓ Los registros tienen `session_id`, `user_message`, `bot_response`, `created_at`

### Pasos:
4. Ir a WordPress Admin > Comunicación > ChatBot
5. Ver sección "Estadísticas del ChatBot"

### Resultado Esperado:
- ✓ Se muestran estadísticas: Total de Conversaciones, Total de Mensajes, Mensajes Hoy
- ✓ Los números reflejan las conversaciones realizadas

---

## Test 11: Visualización de Flujos en Admin

### Pasos:
1. Ir a WordPress Admin > Comunicación > ChatBot
2. Scroll hasta la sección "Flujos de Conversación"

### Resultado Esperado:
- ✓ Se muestran 3 flujos: Voluntariado, Donación, Eventos
- ✓ Cada flujo muestra: nombre, palabras clave, número de pasos
- ✓ Al hacer clic en "Ver detalles del flujo" se expande la información
- ✓ Se muestran todos los pasos con sus mensajes y opciones

---

## Test 12: Tiempo de Respuesta

### Pasos:
1. Abrir el chatbot
2. Enviar cualquier mensaje
3. Medir el tiempo hasta recibir respuesta

### Resultado Esperado:
- ✓ La respuesta llega en menos de 2 segundos
- ✓ Se muestra indicador de "escribiendo..." mientras procesa

---

## Criterios de Éxito

Para que el test sea exitoso, todos los resultados esperados deben cumplirse:

- [ ] Test 1: Respuestas Simples
- [ ] Test 2: Flujo de Voluntariado - Eventos
- [ ] Test 3: Flujo de Voluntariado - Otras Formas
- [ ] Test 4: Flujo de Donación
- [ ] Test 5: Flujo de Eventos
- [ ] Test 6: Salir de un Flujo
- [ ] Test 7: Matching Mejorado
- [ ] Test 8: Mensaje No Reconocido
- [ ] Test 9: Persistencia de Estado
- [ ] Test 10: Logging de Conversaciones
- [ ] Test 11: Visualización de Flujos en Admin
- [ ] Test 12: Tiempo de Respuesta

## Notas

- Los flujos de conversación usan transients de WordPress con duración de 30 minutos
- El matching es case-insensitive (no distingue mayúsculas/minúsculas)
- Las palabras de salida funcionan en cualquier momento del flujo
- El sistema de puntuación prioriza coincidencias exactas y palabras clave más largas
