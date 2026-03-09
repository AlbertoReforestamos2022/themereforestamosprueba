# Manual Test Guide: ChatBot Configuration Interface

## Test Environment Setup

1. Ensure WordPress is running with the Reforestamos Comunicación plugin activated
2. Log in as an administrator
3. Navigate to **Reforestamos Comunicación > ChatBot Config**

## Test Cases

### Test 1: Enable/Disable ChatBot (Requirement 10.7)

**Steps:**
1. Uncheck "Activar ChatBot en el sitio"
2. Click "Guardar Configuración"
3. Visit the frontend of the site
4. Verify the chatbot widget does NOT appear
5. Return to config page
6. Check "Activar ChatBot en el sitio"
7. Click "Guardar Configuración"
8. Visit the frontend again
9. Verify the chatbot widget DOES appear

**Expected Result:** ✅ ChatBot appears/disappears based on the setting

---

### Test 2: Add Custom Response (Requirement 10.2)

**Steps:**
1. Click "Agregar Respuesta"
2. Enter pattern: `test|prueba`
3. Enter response: `Esta es una respuesta de prueba`
4. Click "Guardar Configuración"
5. Open chatbot on frontend
6. Type "test" and send
7. Verify bot responds with "Esta es una respuesta de prueba"
8. Type "prueba" and send
9. Verify bot responds with the same message

**Expected Result:** ✅ Custom response works for all keywords in pattern

---

### Test 3: Remove Response

**Steps:**
1. Click the trash icon on any response row
2. Confirm deletion in the dialog
3. Verify row is removed from table
4. Click "Guardar Configuración"
5. Reload the page
6. Verify the response is still removed

**Expected Result:** ✅ Response is permanently deleted

---

### Test 4: Export Responses

**Steps:**
1. Click "Exportar Respuestas"
2. Verify a JSON file downloads
3. Check filename includes current date
4. Open the file in a text editor
5. Verify it contains valid JSON with all current responses

**Expected Result:** ✅ JSON file downloads with correct format

---

### Test 5: Import Responses

**Steps:**
1. Click "Importar Respuestas"
2. Verify modal appears
3. Paste valid JSON (from previous export or sample below)
4. Click "Importar"
5. Verify success message appears
6. Verify responses table is populated with imported data
7. Click "Guardar Configuración"
8. Reload page and verify responses persist

**Sample JSON for testing:**
```json
{
  "test|prueba": "Respuesta de prueba importada",
  "ayuda|help": "¿En qué puedo ayudarte?"
}
```

**Expected Result:** ✅ Responses are imported and saved correctly

---

### Test 6: Import Invalid JSON

**Steps:**
1. Click "Importar Respuestas"
2. Paste invalid JSON: `{invalid json}`
3. Click "Importar"
4. Verify error message appears
5. Click "Cancelar"
6. Verify modal closes

**Expected Result:** ✅ Error is handled gracefully

---

### Test 7: View Flow Details

**Steps:**
1. Scroll to "Flujos de Conversación" section
2. Verify default flows are displayed (volunteer, donation, events)
3. Click "Ver detalles del flujo" on any flow
4. Verify steps expand showing:
   - Step messages
   - Options with keywords
   - Responses for each option

**Expected Result:** ✅ Flow details display correctly

---

### Test 8: Create New Flow

**Steps:**
1. Click "Crear Nuevo Flujo"
2. Enter flow name: `Test Flow`
3. Click OK
4. Enter trigger keywords: `test flow|flujo de prueba`
5. Click OK
6. Wait for page reload
7. Verify new flow card appears with entered data

**Expected Result:** ✅ New flow is created and displayed

---

### Test 9: Delete Flow

**Steps:**
1. Click trash icon on a custom flow (not default flows)
2. Confirm deletion
3. Verify flow card fades out and is removed
4. Reload page
5. Verify flow is still deleted

**Expected Result:** ✅ Flow is permanently deleted

---

### Test 10: Reset Flows to Defaults

**Steps:**
1. Create a custom flow (see Test 8)
2. Click "Restaurar Flujos Predeterminados"
3. Confirm the action
4. Wait for page reload
5. Verify only default flows remain (volunteer, donation, events)
6. Verify custom flows are removed

**Expected Result:** ✅ Flows are reset to defaults

---

### Test 11: Edit Flow (Future Feature)

**Steps:**
1. Click "Editar" button on any flow
2. Verify informational message appears
3. Click OK

**Expected Result:** ✅ Message indicates feature is coming soon

---

### Test 12: Statistics Display

**Steps:**
1. Scroll to "Estadísticas del ChatBot" section
2. Verify the following are displayed:
   - Total de Conversaciones
   - Total de Mensajes
   - Mensajes Hoy
3. Have a conversation with the chatbot on frontend
4. Reload config page
5. Verify statistics have updated

**Expected Result:** ✅ Statistics display and update correctly

---

### Test 13: Configuration Tips Display

**Steps:**
1. At the top of the page, verify the blue info box is displayed
2. Verify it contains helpful tips about:
   - Using "|" separator
   - Keyword specificity
   - Conversation flows
   - Statistics review

**Expected Result:** ✅ Tips are clearly displayed

---

### Test 14: Permissions Check

**Steps:**
1. Log out as administrator
2. Log in as a user with Editor role
3. Try to access `/wp-admin/admin.php?page=reforestamos-chatbot`
4. Verify access is denied

**Expected Result:** ✅ Only administrators can access configuration

---

### Test 15: Form Persistence

**Steps:**
1. Add several custom responses
2. Modify some existing responses
3. Enable/disable the chatbot
4. Click "Guardar Configuración"
5. Navigate away from the page
6. Return to the config page
7. Verify all changes are still present

**Expected Result:** ✅ All configuration persists correctly

---

## Integration Tests

### Test 16: Frontend Integration

**Steps:**
1. Configure a custom response: `integration|integración` → `Test de integración exitoso`
2. Save configuration
3. Open frontend in incognito/private window
4. Open chatbot
5. Type "integration"
6. Verify correct response appears

**Expected Result:** ✅ Configuration applies to frontend immediately

---

### Test 17: Flow Trigger Integration

**Steps:**
1. Ensure "volunteer" flow exists
2. Open chatbot on frontend
3. Type "quiero ser voluntario"
4. Verify flow is triggered and first step message appears
5. Follow the flow by responding to prompts
6. Verify flow completes correctly

**Expected Result:** ✅ Flows work correctly on frontend

---

## Browser Compatibility

Test the configuration interface in:
- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)

Verify all features work in each browser.

---

## Performance Tests

### Test 18: Large Configuration

**Steps:**
1. Import a JSON file with 50+ responses
2. Verify page loads without lag
3. Verify saving completes in reasonable time (<3 seconds)
4. Verify frontend chatbot responds quickly

**Expected Result:** ✅ System handles large configurations efficiently

---

## Regression Tests

After any code changes, re-run:
- Test 1 (Enable/Disable)
- Test 2 (Add Response)
- Test 5 (Import)
- Test 16 (Frontend Integration)

---

## Known Limitations

1. **Flow Editor**: Advanced flow editing requires manual JSON editing or developer assistance
2. **Flow Validation**: No validation of flow structure when creating/importing
3. **Response Ordering**: Responses are not ordered; matching uses scoring algorithm

---

## Reporting Issues

When reporting issues, include:
1. Test case number
2. Steps to reproduce
3. Expected vs actual result
4. Browser and WordPress version
5. Any console errors (F12 → Console tab)
6. Screenshots if applicable

---

## Success Criteria

All tests should pass with ✅ for the implementation to be considered complete and ready for production.
