# Manual Test: ChatBot Conversation Logs Interface

**Task:** 21.4 Implementar logging de conversaciones  
**Requirement:** 10.6 - Log all chatbot conversations for analysis  
**Date:** 2024

## Test Objective

Verify that the chatbot conversation logs interface works correctly, allowing administrators to view, filter, search, and export conversation logs.

## Prerequisites

- WordPress installation with Reforestamos Comunicación plugin active
- ChatBot enabled and configured
- Some test conversations logged in the database
- Admin user account with `manage_options` capability

## Test Cases

### Test Case 1: Access Logs Page

**Steps:**
1. Log in to WordPress admin
2. Navigate to "Comunicación" menu
3. Click on "Logs de ChatBot" submenu

**Expected Results:**
- ✓ Logs page loads successfully
- ✓ Page title shows "Logs de Conversaciones del ChatBot"
- ✓ Statistics cards are displayed showing:
  - Total Conversaciones
  - Total Mensajes
  - Mensajes Hoy
  - Promedio Mensajes/Sesión

**Status:** [ ] Pass [ ] Fail

---

### Test Case 2: View Conversation Logs

**Steps:**
1. Access the Logs page
2. Review the logs table

**Expected Results:**
- ✓ Table displays with columns: Fecha/Hora, Sesión, Mensaje del Usuario, Respuesta del Bot
- ✓ Each row shows a conversation exchange
- ✓ User messages are displayed in blue-bordered boxes
- ✓ Bot responses are displayed in green-bordered boxes
- ✓ Session ID is truncated and displayed as code
- ✓ "Ver conversación" button is available for each session

**Status:** [ ] Pass [ ] Fail

---

### Test Case 3: Search Functionality

**Steps:**
1. Enter a search term in the search box (e.g., "voluntario")
2. Click "Filtrar" button

**Expected Results:**
- ✓ Table filters to show only messages containing the search term
- ✓ Search term is highlighted or visible in results
- ✓ "Limpiar filtros" button appears
- ✓ Statistics update to reflect filtered results

**Status:** [ ] Pass [ ] Fail

---

### Test Case 4: Filter by Session

**Steps:**
1. Select a specific session from the "Todas las sesiones" dropdown
2. Click "Filtrar" button

**Expected Results:**
- ✓ Table shows only messages from the selected session
- ✓ All messages in the conversation are displayed in chronological order
- ✓ Session dropdown shows session info (ID, message count, date)
- ✓ "Limpiar filtros" button appears

**Status:** [ ] Pass [ ] Fail

---

### Test Case 5: Filter by Date Range

**Steps:**
1. Select a "Desde" (from) date
2. Select a "Hasta" (to) date
3. Click "Filtrar" button

**Expected Results:**
- ✓ Table shows only messages within the date range
- ✓ Date filters are preserved in the form
- ✓ "Limpiar filtros" button appears
- ✓ Statistics update accordingly

**Status:** [ ] Pass [ ] Fail

---

### Test Case 6: Combined Filters

**Steps:**
1. Enter a search term
2. Select a date range
3. Click "Filtrar" button

**Expected Results:**
- ✓ Table shows messages matching ALL filter criteria
- ✓ All filter values are preserved
- ✓ Results are accurate
- ✓ "Limpiar filtros" button works to reset all filters

**Status:** [ ] Pass [ ] Fail

---

### Test Case 7: Pagination

**Steps:**
1. If more than 50 logs exist, check pagination controls
2. Click on page 2
3. Navigate between pages

**Expected Results:**
- ✓ Pagination controls appear at bottom of table
- ✓ Shows "X elementos" count
- ✓ Page navigation works correctly
- ✓ Each page shows up to 50 logs
- ✓ Current page is highlighted

**Status:** [ ] Pass [ ] Fail

---

### Test Case 8: Statistics Accuracy

**Steps:**
1. Note the statistics displayed
2. Verify against database or manual count

**Expected Results:**
- ✓ "Total Conversaciones" matches unique session count
- ✓ "Total Mensajes" matches total log entries
- ✓ "Mensajes Hoy" shows only today's messages
- ✓ "Promedio Mensajes/Sesión" is calculated correctly

**Status:** [ ] Pass [ ] Fail

---

### Test Case 9: Export to CSV

**Steps:**
1. Scroll to "Exportar Datos" section
2. Ensure "Exportar todos los logs" is checked
3. Click "Exportar a CSV" button

**Expected Results:**
- ✓ CSV file downloads automatically
- ✓ Filename format: `chatbot-logs-YYYY-MM-DD-HHMMSS.csv`
- ✓ CSV contains headers: ID, Sesión, Mensaje del Usuario, Respuesta del Bot, Fecha/Hora
- ✓ All logs are included in the export
- ✓ UTF-8 encoding is correct (special characters display properly)
- ✓ CSV can be opened in Excel/Google Sheets

**Status:** [ ] Pass [ ] Fail

---

### Test Case 10: View Full Conversation

**Steps:**
1. Click "Ver conversación" button for a session
2. Review the filtered results

**Expected Results:**
- ✓ Page reloads with session filter applied
- ✓ Shows complete conversation thread
- ✓ Messages are in chronological order
- ✓ Can see the flow of the conversation

**Status:** [ ] Pass [ ] Fail

---

### Test Case 11: Empty State

**Steps:**
1. Apply filters that return no results
2. Or test on fresh installation with no logs

**Expected Results:**
- ✓ Table shows "No se encontraron conversaciones" message
- ✓ Statistics show zeros appropriately
- ✓ No errors are displayed
- ✓ Page remains functional

**Status:** [ ] Pass [ ] Fail

---

### Test Case 12: Responsive Design

**Steps:**
1. View the logs page on different screen sizes
2. Test on mobile, tablet, and desktop

**Expected Results:**
- ✓ Statistics cards stack appropriately on mobile
- ✓ Table is scrollable on small screens
- ✓ Filters wrap properly on mobile
- ✓ All functionality remains accessible

**Status:** [ ] Pass [ ] Fail

---

### Test Case 13: Security and Permissions

**Steps:**
1. Log out and try to access the logs page directly
2. Log in as a user without `manage_options` capability
3. Try to access the logs page

**Expected Results:**
- ✓ Non-logged-in users cannot access the page
- ✓ Users without proper permissions see "No tienes permisos" message
- ✓ Export functionality is protected by nonce
- ✓ No SQL injection vulnerabilities in filters

**Status:** [ ] Pass [ ] Fail

---

### Test Case 14: Performance with Large Dataset

**Steps:**
1. Generate or import a large number of logs (1000+)
2. Access the logs page
3. Apply various filters

**Expected Results:**
- ✓ Page loads in reasonable time (< 3 seconds)
- ✓ Pagination works smoothly
- ✓ Filters apply quickly
- ✓ Export completes successfully
- ✓ No timeout errors

**Status:** [ ] Pass [ ] Fail

---

## Integration Tests

### Integration Test 1: Logging from Frontend

**Steps:**
1. Open the website frontend
2. Open the chatbot widget
3. Send several messages
4. Go to admin logs page

**Expected Results:**
- ✓ All messages appear in the logs
- ✓ Session ID is consistent for the conversation
- ✓ Timestamps are accurate
- ✓ Messages are logged in correct order

**Status:** [ ] Pass [ ] Fail

---

### Integration Test 2: Multi-Session Tracking

**Steps:**
1. Have a conversation in one browser
2. Open incognito/private window
3. Have another conversation
4. Check logs page

**Expected Results:**
- ✓ Two different session IDs are created
- ✓ Both conversations are logged separately
- ✓ Can filter by each session independently
- ✓ Statistics count both sessions

**Status:** [ ] Pass [ ] Fail

---

## Notes

- Document any issues found during testing
- Note any performance concerns
- Suggest improvements if needed

## Test Summary

**Total Test Cases:** 16  
**Passed:** ___  
**Failed:** ___  
**Blocked:** ___  

**Overall Status:** [ ] Pass [ ] Fail

**Tester Name:** _______________  
**Test Date:** _______________  
**Notes:**

---

## Known Issues

(Document any known issues or limitations)

## Future Enhancements

- Add charts/graphs for conversation trends
- Add sentiment analysis
- Add conversation flow visualization
- Add ability to delete old logs
- Add automated reports
