# Manual Test: DeepL Content Translation (Task 22.3)

## Test Overview
This document provides manual testing procedures for the DeepL content translation functionality implemented in task 22.3.

## Prerequisites
- WordPress installation with Reforestamos Comunicación plugin active
- DeepL API key configured in plugin settings
- Test posts/pages created with content

## Test Cases

### Test 1: Translate Post to English
**Objective:** Verify that a Spanish post can be translated to English

**Steps:**
1. Navigate to Posts → All Posts in WordPress admin
2. Create or edit a Spanish post with:
   - Title: "Reforestación en México"
   - Content: "<p>La reforestación es fundamental para el medio ambiente. Plantamos árboles nativos en todo el país.</p>"
   - Excerpt: "Información sobre nuestros proyectos de reforestación"
3. In the right sidebar, locate the "Traducción Automática" metabox
4. Click the "🇬🇧 Translate to English" button
5. Wait for the translation to complete

**Expected Results:**
- Loading spinner appears with message "Traduciendo contenido..."
- Success message appears: "✓ Post traducido exitosamente a English"
- Link to translated post appears: "Ver post traducido →"
- Page reloads after 2 seconds
- After reload, metabox shows "✓ Traducción existente" with link to English version
- Clicking the link opens the translated post in a new tab
- Translated post has:
  - Title translated to English (e.g., "Reforestation in Mexico")
  - Content translated with HTML preserved
  - Excerpt translated
  - Same post status, author, and featured image as original
  - Post meta fields set:
    - Original post: `_translated_post_id` = ID of English post
    - Translated post: `_original_post_id` = ID of Spanish post
    - Translated post: `_translation_lang` = "EN"

### Test 2: Translate Post to Spanish
**Objective:** Verify that an English post can be translated to Spanish

**Steps:**
1. Create or edit an English post with:
   - Title: "Environmental Conservation"
   - Content: "<p>We work to protect forests and plant native trees across Mexico.</p>"
   - Excerpt: "Learn about our conservation efforts"
2. In the "Traducción Automática" metabox, click "🇪🇸 Traducir a Español"
3. Wait for translation to complete

**Expected Results:**
- Translation completes successfully
- Spanish version created with translated content
- Post meta fields properly set linking both posts
- HTML formatting preserved in translated content

### Test 3: Update Existing Translation
**Objective:** Verify that translating a post again updates the existing translation

**Steps:**
1. Use a post that already has a translation (from Test 1 or 2)
2. Edit the original post and change the content
3. Click the translation button again
4. Wait for translation to complete

**Expected Results:**
- Success message indicates translation completed
- Existing translated post is updated (not a new post created)
- Translated post ID remains the same
- Content in translated post reflects the new changes
- Post meta links remain intact

### Test 4: Translate Custom Post Type (Empresas)
**Objective:** Verify translation works for custom post types

**Steps:**
1. Navigate to Empresas → Add New
2. Create a company post with:
   - Title: "Empresa Ejemplo S.A."
   - Content: "<p>Somos una empresa comprometida con el medio ambiente.</p>"
3. Click "🇬🇧 Translate to English"
4. Wait for translation

**Expected Results:**
- Translation completes successfully
- Custom post type is preserved in translated version
- Custom taxonomies are copied to translated post
- Featured image is copied to translated post

### Test 5: Translate Post with Complex HTML
**Objective:** Verify HTML preservation during translation

**Steps:**
1. Create a post with complex HTML content:
   ```html
   <h2>Título Principal</h2>
   <p>Párrafo con <strong>texto en negrita</strong> y <em>cursiva</em>.</p>
   <ul>
     <li>Elemento de lista 1</li>
     <li>Elemento de lista 2</li>
   </ul>
   <a href="https://example.com">Enlace de ejemplo</a>
   ```
2. Translate the post
3. Check the translated post content

**Expected Results:**
- All HTML tags are preserved
- Text content is translated
- HTML structure remains intact
- Links, lists, and formatting maintained

### Test 6: Error Handling - Invalid API Key
**Objective:** Verify error handling when API key is invalid

**Steps:**
1. Go to Reforestamos Comunicación → DeepL settings
2. Enter an invalid API key
3. Save settings
4. Try to translate a post

**Expected Results:**
- Error message appears: "✗ Error al traducir el título: [API error message]"
- Post is not created/updated
- User can try again after fixing the API key

### Test 7: Error Handling - Empty Post
**Objective:** Verify handling of posts with no content

**Steps:**
1. Create a post with only a title, no content or excerpt
2. Try to translate the post

**Expected Results:**
- Title is translated successfully
- Post is created with translated title
- Empty content/excerpt fields remain empty
- No errors occur

### Test 8: Security - Nonce Verification
**Objective:** Verify nonce security is working

**Steps:**
1. Open browser developer tools
2. Edit a post and open the translation metabox
3. In console, try to make AJAX request without nonce:
   ```javascript
   jQuery.post(ajaxurl, {
     action: 'translate_post',
     post_id: jQuery('#post_ID').val(),
     target_lang: 'EN'
   });
   ```

**Expected Results:**
- Request is rejected with error: "Error de seguridad. Por favor recargue la página."
- No translation is performed

### Test 9: Security - Permissions Check
**Objective:** Verify user permissions are checked

**Steps:**
1. Log in as a user with Subscriber role (no edit_posts capability)
2. Try to access a post edit page
3. If metabox is visible, try to translate

**Expected Results:**
- Either metabox is not visible, or
- Translation request is rejected with: "No tiene permisos para realizar esta acción."

### Test 10: Post Meta Linking
**Objective:** Verify post meta fields correctly link original and translated posts

**Steps:**
1. Translate a post from Spanish to English
2. In WordPress admin, go to Custom Fields for the original post
3. Check for `_translated_post_id` field
4. Go to the translated post's Custom Fields
5. Check for `_original_post_id` and `_translation_lang` fields

**Expected Results:**
- Original post has `_translated_post_id` = [translated post ID]
- Translated post has `_original_post_id` = [original post ID]
- Translated post has `_translation_lang` = "EN" (or "ES")
- Values are correct and match the actual post IDs

## Test Results

| Test Case | Status | Notes | Tester | Date |
|-----------|--------|-------|--------|------|
| Test 1: Translate to English | ⬜ Not Tested | | | |
| Test 2: Translate to Spanish | ⬜ Not Tested | | | |
| Test 3: Update Translation | ⬜ Not Tested | | | |
| Test 4: Custom Post Type | ⬜ Not Tested | | | |
| Test 5: Complex HTML | ⬜ Not Tested | | | |
| Test 6: Invalid API Key | ⬜ Not Tested | | | |
| Test 7: Empty Post | ⬜ Not Tested | | | |
| Test 8: Nonce Security | ⬜ Not Tested | | | |
| Test 9: Permissions | ⬜ Not Tested | | | |
| Test 10: Post Meta | ⬜ Not Tested | | | |

## Notes
- Replace ⬜ with ✅ (pass) or ❌ (fail) after testing
- Add any issues or observations in the Notes column
- All tests require a valid DeepL API key to function
- Translation quality depends on DeepL API and may vary
