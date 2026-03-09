# Test Manual del Sistema de Unsubscribe

## Objetivo

Verificar que el sistema de unsubscribe funciona correctamente de principio a fin.

## Prerrequisitos

- Plugin Reforestamos Comunicación activado
- Al menos un suscriptor activo en la base de datos
- Acceso al admin de WordPress

## Pasos del Test

### Paso 1: Verificar Suscriptor Activo

1. Ir a: **Boletín > Suscriptores**
2. Verificar que existe al menos un suscriptor con estado "Activo"
3. Anotar el email del suscriptor de prueba

**Resultado Esperado:** ✅ Lista de suscriptores visible con al menos uno activo

---

### Paso 2: Crear Newsletter de Prueba

1. Ir a: **Boletín > Añadir Nuevo**
2. Título: "Test de Unsubscribe"
3. Contenido: "Este es un email de prueba para verificar el sistema de unsubscribe."
4. Publicar el newsletter

**Resultado Esperado:** ✅ Newsletter creado exitosamente

---

### Paso 3: Enviar Newsletter

1. Ir a: **Boletín > Campañas**
2. Seleccionar el newsletter "Test de Unsubscribe"
3. Tipo de destinatarios: "Todos los suscriptores activos"
4. Click en "Enviar Newsletter"

**Resultado Esperado:** ✅ Mensaje de confirmación de envío

---

### Paso 4: Verificar Email Recibido

1. Abrir el cliente de email del suscriptor de prueba
2. Buscar el email "Test de Unsubscribe"
3. Verificar que el email contiene:
   - El contenido del newsletter
   - Una línea horizontal (hr)
   - Texto: "Si no deseas recibir más correos,"
   - Enlace: "cancela tu suscripción aquí"

**Resultado Esperado:** 
- ✅ Email recibido
- ✅ Footer con enlace de unsubscribe visible
- ✅ Enlace es clickeable

**Ejemplo de Footer:**
```
─────────────────────────────────────
Si no deseas recibir más correos, cancela tu suscripción aquí
```

---

### Paso 5: Verificar URL de Unsubscribe

1. Hacer hover sobre el enlace "cancela tu suscripción aquí"
2. Verificar que la URL tiene el formato:
   ```
   https://tu-sitio.com/?action=unsubscribe&subscriber=123&token=abc123...
   ```
3. Verificar que contiene los parámetros:
   - `action=unsubscribe`
   - `subscriber=[número]`
   - `token=[hash largo]`

**Resultado Esperado:** ✅ URL contiene todos los parámetros necesarios

---

### Paso 6: Hacer Click en Unsubscribe

1. Click en el enlace "cancela tu suscripción aquí"
2. Esperar a que cargue la página

**Resultado Esperado:** 
- ✅ Página carga sin errores
- ✅ No se muestra mensaje de error de WordPress

---

### Paso 7: Verificar Página de Confirmación

Verificar que la página muestra:

1. **Título:** "Baja Confirmada"
2. **Mensaje 1:** "Tu suscripción ha sido cancelada exitosamente."
3. **Mensaje 2:** "Ya no recibirás más correos de nuestro boletín."
4. **Enlace:** "Volver al inicio"

**Resultado Esperado:** 
- ✅ Todos los elementos visibles
- ✅ Diseño centrado y legible
- ✅ Enlace "Volver al inicio" funcional

**Captura de Pantalla Esperada:**
```
┌─────────────────────────────────────┐
│                                     │
│         Baja Confirmada             │
│                                     │
│  Tu suscripción ha sido cancelada   │
│         exitosamente.               │
│                                     │
│  Ya no recibirás más correos de     │
│      nuestro boletín.               │
│                                     │
│      [Volver al inicio]             │
│                                     │
└─────────────────────────────────────┘
```

---

### Paso 8: Verificar Estado en Base de Datos

1. Ir a: **Boletín > Suscriptores**
2. Buscar el suscriptor de prueba
3. Verificar su estado

**Resultado Esperado:** 
- ✅ Estado cambió de "Activo" a "Dado de baja"
- ✅ Fecha de baja registrada

**Alternativa (phpMyAdmin):**
```sql
SELECT id, email, status, unsubscribed_at 
FROM wp_reforestamos_subscribers 
WHERE email = 'email-de-prueba@example.com';
```

**Resultado Esperado:**
```
id  | email                      | status        | unsubscribed_at
123 | email-de-prueba@example.com| unsubscribed  | 2024-01-15 10:30:00
```

---

### Paso 9: Verificar Prevención de Reenvío

1. Ir a: **Boletín > Campañas**
2. Crear un nuevo newsletter: "Test 2"
3. Enviar a "Todos los suscriptores activos"
4. Verificar que el suscriptor dado de baja NO recibe el email

**Resultado Esperado:** 
- ✅ Email NO recibido por el suscriptor dado de baja
- ✅ Otros suscriptores activos SÍ reciben el email

---

### Paso 10: Test de Seguridad - Token Inválido

1. Copiar la URL de unsubscribe del Paso 5
2. Modificar el token (cambiar algunos caracteres)
3. Intentar acceder a la URL modificada

**Ejemplo:**
```
Original: ?action=unsubscribe&subscriber=123&token=abc123def456
Modificado: ?action=unsubscribe&subscriber=123&token=abc123def999
```

**Resultado Esperado:** 
- ✅ Página de error de WordPress
- ✅ Mensaje: "Token de seguridad inválido"
- ✅ Estado del suscriptor NO cambia

---

### Paso 11: Test de Seguridad - Subscriber ID Inválido

1. Copiar la URL de unsubscribe del Paso 5
2. Modificar el subscriber ID a un ID que no existe (ej: 99999)
3. Intentar acceder a la URL modificada

**Resultado Esperado:** 
- ✅ Página de error o mensaje de error
- ✅ No se actualiza ningún registro en la base de datos

---

## Resumen de Resultados

| Test | Descripción | Resultado |
|------|-------------|-----------|
| 1 | Suscriptor activo existe | ⬜ |
| 2 | Newsletter creado | ⬜ |
| 3 | Newsletter enviado | ⬜ |
| 4 | Email recibido con footer | ⬜ |
| 5 | URL correcta generada | ⬜ |
| 6 | Click en unsubscribe funciona | ⬜ |
| 7 | Página de confirmación correcta | ⬜ |
| 8 | Estado actualizado en DB | ⬜ |
| 9 | No recibe emails futuros | ⬜ |
| 10 | Token inválido rechazado | ⬜ |
| 11 | ID inválido rechazado | ⬜ |

**Marcar con:**
- ✅ = Pasó
- ❌ = Falló
- ⚠️ = Parcial

---

## Troubleshooting

### Problema: Email no recibido

**Posibles causas:**
- SMTP no configurado correctamente
- Email en spam
- Suscriptor no está activo

**Solución:**
1. Verificar configuración SMTP en Boletín > Configuración
2. Revisar carpeta de spam
3. Verificar estado del suscriptor en la base de datos

---

### Problema: Enlace de unsubscribe no aparece

**Posibles causas:**
- Método `prepare_email_content()` no se está ejecutando
- Filtro de contenido interfiere

**Solución:**
1. Verificar que el plugin está actualizado
2. Revisar logs de errores de PHP
3. Desactivar otros plugins temporalmente

---

### Problema: Página de confirmación no se muestra

**Posibles causas:**
- Tema interfiere con `wp_head()` o `wp_footer()`
- Error en la actualización de base de datos

**Solución:**
1. Verificar que el tema llama `wp_head()` y `wp_footer()`
2. Revisar logs de errores de WordPress
3. Verificar permisos de base de datos

---

### Problema: Estado no se actualiza en DB

**Posibles causas:**
- Permisos de base de datos
- Tabla no existe
- Error en query SQL

**Solución:**
1. Verificar permisos del usuario de MySQL
2. Verificar que la tabla `wp_reforestamos_subscribers` existe
3. Revisar logs de errores de MySQL

---

## Checklist Final

Antes de considerar el test completo, verificar:

- [ ] Todos los 11 tests pasaron exitosamente
- [ ] No hay errores en los logs de WordPress
- [ ] No hay errores en los logs de PHP
- [ ] La experiencia de usuario es fluida
- [ ] Los mensajes son claros y en español
- [ ] El diseño de la página de confirmación es aceptable
- [ ] La seguridad funciona correctamente (tests 10 y 11)

---

## Conclusión

Si todos los tests pasaron (✅), el sistema de unsubscribe está funcionando correctamente y cumple con el Requirement 8.8.

**Fecha del Test:** _______________

**Realizado por:** _______________

**Resultado General:** ⬜ APROBADO / ⬜ RECHAZADO

**Notas adicionales:**
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
