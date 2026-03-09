# Manual Test: Translation Interface (Task 22.2)

## Test Overview
This document provides manual testing procedures for the DeepL translation interface metabox in the post editor.

**Feature:** Translation Interface Metabox  
**Task:** 22.2 - Implementar interfaz de traducción en editor  
**Requirements:** 11.2, 11.3  
**Date:** 2024

## Prerequisites

1. WordPress installation with reforestamos-comunicacion plugin activated
2. DeepL API key configured in plugin settings (optional for UI testing)
3. At least one post/page created for testing
4. Custom post types (Empresas, Eventos, Integrantes, Boletín) registered via Core plugin

## Test Cases

### Test 1: Metabox Visibility Without API Key

**Objective:** Verify metabox does not appear when DeepL is not configured

**Steps:**
1. Go to WordPress admin → Reforestamos Comunicación → DeepL
2. Ensure API key field is empty
3. Save settings
4. Navigate to Posts → Add New or edit an existing post
5. Check the sidebar for "Traducción Automática" metabox

**Expected Result:**
- Metabox should NOT appear in the sidebar
- No JavaScript errors in browser console

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 2: Metabox Visibility With API Key

**Objective:** Verify metabox appears when DeepL is configured

**Steps:**
1. Go to WordPress admin → Reforestamos Comunicación → DeepL
2. Enter a valid DeepL API key (format: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx:fx)
3. Save settings
4. Navigate to Posts → Add New or edit an existing post
5. Check the sidebar for "Traducción Automática" metabox

**Expected Result:**
- "Traducción Automática" metabox appears in the sidebar
- Metabox displays current language indicator
- Two translation buttons are visible:
  - "🇬🇧 Translate to English"
  - "🇪🇸 Traducir a Español"

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 3: Metabox on Different Post Types

**Objective:** Verify metabox appears on all supported post types

**Steps:**
1. Ensure DeepL API key is configured
2. Test metabox presence on each post type:
   - Posts (post)
   - Pages (page)
   - Empresas (empresas)
   - Eventos (eventos)
   - Integrantes (integrantes)
   - Boletín (boletin)

**Expected Result:**
- Metabox appears on all listed post types
- Metabox displays consistently across all post types

**Status:** [ ] Pass [ ] Fail

**Post Types Tested:**
- [ ] Posts
- [ ] Pages
- [ ] Empresas
- [ ] Eventos
- [ ] Integrantes
- [ ] Boletín

**Notes:**
_______________________________________________________________________

---

### Test 4: Current Language Indicator

**Objective:** Verify current language is displayed correctly

**Steps:**
1. Create a new post (should default to Spanish)
2. Check the "Idioma actual" field in the metabox
3. Verify it shows "🇪🇸 Español"

**Expected Result:**
- Current language displays as "🇪🇸 Español" for new posts
- Language indicator is clearly visible

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 5: Translation Status - No Translation

**Objective:** Verify status shows "Sin traducción" for posts without translations

**Steps:**
1. Create a new post without any translation
2. Check the translation status section in the metabox

**Expected Result:**
- Status shows "Sin traducción" in gray text
- No links to translated versions appear

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 6: Translation Buttons Appearance

**Objective:** Verify translation buttons are properly styled and accessible

**Steps:**
1. Open any post editor with the metabox visible
2. Inspect the translation buttons:
   - "🇬🇧 Translate to English" (primary button)
   - "🇪🇸 Traducir a Español" (secondary button)

**Expected Result:**
- Both buttons are full-width
- English button has primary styling (blue)
- Spanish button has secondary styling (gray)
- Buttons have appropriate spacing between them
- Flag emojis are visible
- Text is readable and properly aligned

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 7: Button Click Interaction (Placeholder)

**Objective:** Verify buttons respond to clicks with placeholder message

**Steps:**
1. Open any post editor with the metabox visible
2. Click "🇬🇧 Translate to English" button
3. Observe the behavior
4. Wait for response
5. Repeat with "🇪🇸 Traducir a Español" button

**Expected Result:**
- Button becomes disabled during processing
- Loading spinner appears with "Traduciendo contenido..." message
- After ~1 second, message appears: "ℹ La funcionalidad de traducción se implementará en la tarea 22.3"
- Button becomes enabled again
- No JavaScript errors in console

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 8: Help Text Display

**Objective:** Verify help text is displayed below buttons

**Steps:**
1. Open any post editor with the metabox visible
2. Scroll to the bottom of the metabox
3. Read the help text

**Expected Result:**
- Help text is visible below the buttons
- Text reads: "La traducción creará o actualizará un post vinculado en el idioma seleccionado."
- Text is styled in gray, smaller font size
- Text is readable and properly formatted

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 9: Metabox Position

**Objective:** Verify metabox appears in the correct location

**Steps:**
1. Open any post editor
2. Check the sidebar for the metabox location

**Expected Result:**
- Metabox appears in the sidebar (not main content area)
- Metabox has default priority (appears after other metaboxes)
- Metabox can be collapsed/expanded like other metaboxes

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 10: Responsive Design

**Objective:** Verify metabox displays correctly on different screen sizes

**Steps:**
1. Open post editor on desktop
2. Resize browser window to tablet size
3. Resize to mobile size
4. Check metabox appearance at each size

**Expected Result:**
- Metabox remains functional at all screen sizes
- Buttons remain full-width and clickable
- Text remains readable
- No layout breaks or overlaps

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 11: Multiple Posts Test

**Objective:** Verify metabox works correctly across multiple posts

**Steps:**
1. Open Post A in one browser tab
2. Open Post B in another browser tab
3. Verify each metabox shows correct information for its post
4. Click translation button in Post A
5. Verify Post B is not affected

**Expected Result:**
- Each metabox displays independently
- No cross-contamination between posts
- Post IDs are correctly identified

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

### Test 12: Nonce Security

**Objective:** Verify nonce field is present for security

**Steps:**
1. Open any post editor
2. Open browser developer tools
3. Inspect the metabox HTML
4. Look for hidden input field with name "translate_nonce"

**Expected Result:**
- Hidden nonce field is present
- Nonce field has a value
- Nonce field name is "translate_nonce"

**Status:** [ ] Pass [ ] Fail

**Notes:**
_______________________________________________________________________

---

## Summary

**Total Tests:** 12  
**Passed:** ___  
**Failed:** ___  
**Blocked:** ___  

## Issues Found

| Issue # | Description | Severity | Status |
|---------|-------------|----------|--------|
| 1 | | | |
| 2 | | | |
| 3 | | | |

## Recommendations

_______________________________________________________________________
_______________________________________________________________________
_______________________________________________________________________

## Sign-off

**Tester Name:** _______________________  
**Date:** _______________________  
**Signature:** _______________________

---

## Notes for Task 22.3

The following functionality needs to be implemented in task 22.3:
- AJAX handler for translation requests
- Actual translation of post content using DeepL API
- Creation/update of translated posts
- Linking between original and translated posts
- Error handling for API failures
- Rate limit handling
