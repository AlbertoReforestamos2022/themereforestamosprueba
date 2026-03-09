# Manual Test Plan - Custom Fields Translation

## Test Environment Setup

### Prerequisites
- WordPress installation with Reforestamos Core and Comunicación plugins active
- DeepL API key configured in Comunicación > DeepL settings
- Test content created for each Custom Post Type

## Test Cases

### Test 1: Eventos - Translate Location Field

**Objective:** Verify that the location field is translated while other fields are copied as-is.

**Steps:**
1. Navigate to Eventos > Add New
2. Fill in the following fields:
   - Title: `Reforestación en Chapultepec`
   - Content: `Únete a nosotros para plantar 100 árboles nativos en el Bosque de Chapultepec. Actividad familiar.`
   - Fecha del Evento: `2024-06-15 10:00`
   - Ubicación: `Bosque de Chapultepec, Primera Sección, Ciudad de México`
   - Latitud: `19.4204`
   - Longitud: `-99.1819`
   - Capacidad: `50`
   - Registro Activo: ✓ (checked)
3. Click "Publish"
4. In the Translation metabox, click "Translate to English"
5. Wait for translation to complete
6. Click "Ver post traducido →" to view the translated post

**Expected Results:**
- ✓ Title is translated to English (e.g., "Reforestation in Chapultepec")
- ✓ Content is translated to English
- ✓ Ubicación is translated (e.g., "Chapultepec Forest, First Section, Mexico City")
- ✓ Fecha del Evento remains the same: `2024-06-15 10:00`
- ✓ Latitud remains: `19.4204`
- ✓ Longitud remains: `-99.1819`
- ✓ Capacidad remains: `50`
- ✓ Registro Activo remains checked
- ✓ Success message appears
- ✓ Translation link is created between posts

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 2: Integrantes - Translate Position Field

**Objective:** Verify that the position/role field is translated while email is copied as-is.

**Steps:**
1. Navigate to Integrantes > Add New
2. Fill in the following fields:
   - Title: `María González`
   - Content: `María es una experta en silvicultura con 15 años de experiencia en proyectos de reforestación.`
   - Cargo: `Directora de Operaciones y Proyectos`
   - Email: `maria.gonzalez@reforestamos.org`
   - Add a social media link:
     - Plataforma: `LinkedIn`
     - URL: `https://linkedin.com/in/mariagonzalez`
3. Click "Publish"
4. In the Translation metabox, click "Translate to English"
5. Wait for translation to complete
6. View the translated post

**Expected Results:**
- ✓ Title is translated (e.g., "María González" - names typically stay the same)
- ✓ Content is translated to English
- ✓ Cargo is translated (e.g., "Director of Operations and Projects")
- ✓ Email remains: `maria.gonzalez@reforestamos.org`
- ✓ Social media links remain unchanged
- ✓ Success message appears

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 3: Empresas - No Translation (Copy All Fields)

**Objective:** Verify that Empresas custom fields are all copied as-is without translation.

**Steps:**
1. Navigate to Empresas > Add New
2. Fill in the following fields:
   - Title: `Grupo Modelo`
   - Content: `Grupo Modelo es una empresa cervecera mexicana comprometida con la reforestación.`
   - Logo: Upload a company logo image
   - URL del Sitio Web: `https://www.gmodelo.mx`
   - Categoría: `Corporativo`
   - Año de Colaboración: `2020`
   - Árboles Plantados: `5000`
3. Click "Publish"
4. In the Translation metabox, click "Translate to English"
5. Wait for translation to complete
6. View the translated post

**Expected Results:**
- ✓ Title is translated (e.g., "Modelo Group")
- ✓ Content is translated to English
- ✓ Logo remains the same (same image ID)
- ✓ URL remains: `https://www.gmodelo.mx`
- ✓ Categoría remains: `Corporativo`
- ✓ Año remains: `2020`
- ✓ Árboles Plantados remains: `5000`
- ✓ No custom fields are translated (all copied as-is)

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 4: Boletín - No Translation (Copy All Fields)

**Objective:** Verify that Boletín custom fields are all copied as-is without translation.

**Steps:**
1. Navigate to Boletín > Add New
2. Fill in the following fields:
   - Title: `Boletín Junio 2024`
   - Content: `Noticias y actualizaciones de nuestros proyectos de reforestación.`
   - Fecha de Envío: `2024-06-01 09:00`
   - Estado: `Programado`
   - Destinatarios: `1250`
3. Click "Publish"
4. In the Translation metabox, click "Translate to English"
5. Wait for translation to complete
6. View the translated post

**Expected Results:**
- ✓ Title is translated (e.g., "June 2024 Newsletter")
- ✓ Content is translated to English
- ✓ Fecha de Envío remains: `2024-06-01 09:00`
- ✓ Estado remains: `Programado`
- ✓ Destinatarios remains: `1250`
- ✓ No custom fields are translated (all copied as-is)

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 5: Empty Custom Fields

**Objective:** Verify that empty custom fields are handled correctly.

**Steps:**
1. Navigate to Eventos > Add New
2. Fill in only required fields:
   - Title: `Evento de Prueba`
   - Content: `Contenido mínimo para prueba.`
   - Leave Ubicación empty
   - Leave other fields empty or default
3. Click "Publish"
4. Translate to English
5. View the translated post

**Expected Results:**
- ✓ Title and content are translated
- ✓ Empty Ubicación field is not created on translated post
- ✓ No errors occur
- ✓ Translation completes successfully

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 6: Update Existing Translation

**Objective:** Verify that updating a translation also updates custom fields.

**Steps:**
1. Use the Evento created in Test 1
2. Edit the original Spanish post
3. Change Ubicación to: `Parque Nacional Desierto de los Leones, Ciudad de México`
4. Click "Update"
5. In the Translation metabox, click "Translate to English" again
6. View the translated post

**Expected Results:**
- ✓ Existing translated post is updated (not duplicated)
- ✓ Ubicación is re-translated with new value
- ✓ Other fields remain consistent
- ✓ Post link relationship is maintained

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 7: Translation Error Handling

**Objective:** Verify graceful error handling when translation fails.

**Steps:**
1. Navigate to DeepL settings
2. Temporarily change API key to an invalid value
3. Create a new Evento with Ubicación filled
4. Try to translate to English
5. Restore correct API key

**Expected Results:**
- ✓ Error message is displayed to user
- ✓ No translated post is created
- ✓ Original post remains unchanged
- ✓ Error is logged in PHP error log
- ✓ After restoring API key, translation works

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 8: Bidirectional Translation

**Objective:** Verify translation works in both directions (ES→EN and EN→ES).

**Steps:**
1. Create an Evento in Spanish with Ubicación
2. Translate to English
3. Edit the English version
4. Change the translated Ubicación to a different English location
5. Translate back to Spanish
6. Verify both versions

**Expected Results:**
- ✓ ES→EN translation works correctly
- ✓ EN→ES translation works correctly
- ✓ Both posts maintain their custom field values
- ✓ Links between posts are maintained

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 9: Special Characters in Custom Fields

**Objective:** Verify that special characters and accents are handled correctly.

**Steps:**
1. Create an Integrante with:
   - Cargo: `Coordinador de Educación Ambiental y Comunicación`
2. Translate to English
3. View translated Cargo

**Expected Results:**
- ✓ Accents and special characters are preserved in source
- ✓ Translation is accurate
- ✓ No encoding issues

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

### Test 10: Long Text in Custom Fields

**Objective:** Verify that longer text in custom fields is translated correctly.

**Steps:**
1. Create an Evento with a long Ubicación:
   - Ubicación: `Centro de Educación Ambiental Acuexcomatl, Carretera Federal México-Cuernavaca Km 24.5, San Andrés Totoltepec, Tlalpan, Ciudad de México, CP 14400`
2. Translate to English
3. View translated Ubicación

**Expected Results:**
- ✓ Long text is fully translated
- ✓ No truncation occurs
- ✓ Translation is accurate
- ✓ No API errors

**Pass/Fail:** ___________

**Notes:** ___________________________________________

---

## Test Summary

**Total Tests:** 10
**Passed:** ___________
**Failed:** ___________
**Blocked:** ___________

**Overall Status:** ___________

**Tester Name:** ___________
**Test Date:** ___________
**Environment:** ___________

## Issues Found

| Test # | Issue Description | Severity | Status |
|--------|------------------|----------|--------|
|        |                  |          |        |
|        |                  |          |        |
|        |                  |          |        |

## Additional Notes

___________________________________________
___________________________________________
___________________________________________
