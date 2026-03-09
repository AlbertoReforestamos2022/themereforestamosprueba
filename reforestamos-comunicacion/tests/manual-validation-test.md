# Manual Testing Guide - Contact Form Validation (Task 20.2)

## Overview
This document provides manual testing instructions for the contact form validation implementation.

## Prerequisites
- WordPress installation with the Reforestamos Comunicación plugin activated
- A page with the `[contact-form]` shortcode

## Test Cases

### Test Case 1: Empty Form Submission
**Steps:**
1. Navigate to a page with the contact form
2. Click "Enviar Mensaje" without filling any fields
3. **Expected Result:** Error message: "El campo nombre es requerido."

### Test Case 2: Name Validation - Too Short
**Steps:**
1. Enter "A" in the name field
2. Fill other fields with valid data
3. Submit the form
4. **Expected Result:** Error message: "El nombre debe tener al menos 2 caracteres."

### Test Case 3: Name Validation - Too Long
**Steps:**
1. Enter a name with more than 100 characters
2. Fill other fields with valid data
3. Submit the form
4. **Expected Result:** Error message: "El nombre no puede tener más de 100 caracteres."

### Test Case 4: Email Validation - Empty
**Steps:**
1. Fill name field with valid data
2. Leave email field empty
3. Fill other fields with valid data
4. Submit the form
5. **Expected Result:** Error message: "El campo email es requerido."

### Test Case 5: Email Validation - Invalid Format
**Steps:**
1. Fill name field with valid data
2. Enter "invalid-email" in email field
3. Fill other fields with valid data
4. Submit the form
5. **Expected Result:** Error message: "El formato del email no es válido."

### Test Case 6: Subject Validation - Too Short
**Steps:**
1. Fill name and email with valid data
2. Enter "AB" in subject field
3. Fill message with valid data
4. Submit the form
5. **Expected Result:** Error message: "El asunto debe tener al menos 3 caracteres."

### Test Case 7: Subject Validation - Too Long
**Steps:**
1. Fill name and email with valid data
2. Enter a subject with more than 200 characters
3. Fill message with valid data
4. Submit the form
5. **Expected Result:** Error message: "El asunto no puede tener más de 200 caracteres."

### Test Case 8: Message Validation - Too Short
**Steps:**
1. Fill name, email, and subject with valid data
2. Enter "Short" in message field
3. Submit the form
4. **Expected Result:** Error message: "El mensaje debe tener al menos 10 caracteres."

### Test Case 9: Message Validation - Too Long
**Steps:**
1. Fill name, email, and subject with valid data
2. Enter a message with more than 5000 characters
3. Submit the form
4. **Expected Result:** Error message: "El mensaje no puede tener más de 5000 caracteres."

### Test Case 10: XSS Prevention - Script Tags
**Steps:**
1. Enter `<script>alert('XSS')</script>` in name field
2. Fill other fields with valid data
3. Submit the form
4. **Expected Result:** Form submits successfully, script tags are sanitized (no alert appears)

### Test Case 11: XSS Prevention - HTML in Message
**Steps:**
1. Fill name, email, and subject with valid data
2. Enter `<b>Bold text</b> and <script>alert('XSS')</script>` in message field
3. Submit the form
4. **Expected Result:** Form submits successfully, HTML is sanitized

### Test Case 12: Valid Form Submission
**Steps:**
1. Fill all fields with valid data:
   - Name: "Juan Pérez"
   - Email: "juan@example.com"
   - Subject: "Consulta sobre reforestación"
   - Message: "Me gustaría obtener más información sobre sus programas de reforestación."
2. Submit the form
3. **Expected Result:** Success message: "¡Gracias por tu mensaje! Te responderemos pronto."
4. Form fields should be cleared

### Test Case 13: Nonce Verification
**Steps:**
1. Open browser developer tools
2. Navigate to a page with the contact form
3. In the console, try to submit the form without the nonce:
   ```javascript
   jQuery.post(reforestamosComm.ajaxUrl, {
     action: 'submit_contact_form',
     nombre: 'Test',
     email: 'test@example.com',
     asunto: 'Test',
     mensaje: 'Test message'
   });
   ```
4. **Expected Result:** Error message: "Error de seguridad. Por favor recarga la página e intenta de nuevo."

### Test Case 14: Multiple Validation Errors
**Steps:**
1. Enter "A" in name field (too short)
2. Enter "invalid-email" in email field
3. Enter "AB" in subject field (too short)
4. Enter "Short" in message field (too short)
5. Submit the form
6. **Expected Result:** Multiple error messages displayed, separated by `<br>` tags

### Test Case 15: Button State During Submission
**Steps:**
1. Fill all fields with valid data
2. Click "Enviar Mensaje"
3. **Expected Result:** 
   - Button should be disabled
   - Button text should change to "Enviando..."
   - After response, button should be re-enabled
   - Button text should return to original text

## Security Validation

### Requirement 9.3: Required Fields Validation
- ✅ All required fields (nombre, email, asunto, mensaje) are validated
- ✅ Empty fields are rejected with specific error messages

### Requirement 9.9: XSS Prevention
- ✅ All inputs are sanitized using WordPress functions:
  - `sanitize_text_field()` for nombre and asunto
  - `sanitize_email()` for email
  - `sanitize_textarea_field()` for mensaje

### Requirement 23.1: WordPress Sanitization Functions
- ✅ All user input is sanitized using WordPress core functions
- ✅ No custom sanitization functions are used

### Requirement 23.4: AJAX Request Validation
- ✅ Nonce verification is implemented
- ✅ All AJAX requests are validated before processing

## Notes
- Email sending is NOT implemented in this task (Task 20.3)
- Form submissions are NOT stored in database yet (Task 20.5)
- Anti-spam protection is NOT implemented yet (Task 20.4)
