# Testing Guide - Bloque Contacto

Este documento describe cómo probar el bloque Contacto para asegurar que funciona correctamente.

## Pre-requisitos

- WordPress 6.0+ instalado y funcionando
- Tema Reforestamos Block Theme activado
- Assets compilados (`npm run build`)

## 1. Testing en el Editor

### 1.1 Inserción del Bloque

- [ ] Abrir el editor de páginas/posts
- [ ] Buscar "Contacto" en el inserter de bloques
- [ ] Verificar que el bloque aparece en la categoría "Reforestamos"
- [ ] Insertar el bloque en la página
- [ ] Verificar que se muestra el preview del editor

**Resultado esperado**: El bloque se inserta correctamente y muestra un preview con el formulario y la información de contacto.

### 1.2 Configuración del Formulario

- [ ] Abrir el panel de Inspector (sidebar derecho)
- [ ] Expandir "Contact Form Settings"
- [ ] Cambiar el "Form ID" a "contact-main"
- [ ] Cambiar el "Form Title" a "Contáctanos"
- [ ] Verificar que los cambios se reflejan en el preview

**Resultado esperado**: Los cambios se aplican inmediatamente en el preview del editor.

### 1.3 Configuración de Información de Contacto

- [ ] Expandir "Contact Information" en el Inspector
- [ ] Cambiar el "Info Section Title" a "Nuestra Información"
- [ ] Modificar el email a "info@reforestamos.org"
- [ ] Modificar el teléfono a "+52 55 9876 5432"
- [ ] Modificar la dirección a "Av. Insurgentes Sur 123, CDMX"
- [ ] Verificar que los cambios se reflejan en el preview

**Resultado esperado**: Toda la información se actualiza correctamente en el preview.

### 1.4 Toggle de Campos de Información

- [ ] Desactivar "Show Email"
- [ ] Verificar que el email desaparece del preview
- [ ] Desactivar "Show Phone"
- [ ] Verificar que el teléfono desaparece del preview
- [ ] Desactivar "Show Address"
- [ ] Verificar que la dirección desaparece del preview
- [ ] Reactivar todos los campos
- [ ] Verificar que todos reaparecen

**Resultado esperado**: Los campos se muestran/ocultan correctamente según los toggles.

### 1.5 Configuración del Mapa

- [ ] Expandir "Map Settings" en el Inspector
- [ ] Activar "Show Map"
- [ ] Pegar una URL de Google Maps embed (ejemplo abajo)
- [ ] Verificar que aparece un mensaje de placeholder en el preview

**URL de ejemplo**:
```
https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762.6179486826!2d-99.16558368509308!3d19.432607986886894!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1ff35f5bd1563%3A0x6c366f0e2de02ff7!2sMonumento%20a%20la%20Revoluci%C3%B3n!5e0!3m2!1ses!2smx!4v1234567890123!5m2!1ses!2smx
```

**Resultado esperado**: El preview muestra un mensaje indicando que el mapa se mostrará en el frontend.

### 1.6 Guardar y Publicar

- [ ] Guardar la página como borrador
- [ ] Verificar que no hay errores en la consola
- [ ] Publicar la página
- [ ] Verificar que la publicación es exitosa

**Resultado esperado**: La página se guarda y publica sin errores.

## 2. Testing en el Frontend

### 2.1 Visualización General

- [ ] Abrir la página publicada en el frontend
- [ ] Verificar que el bloque se renderiza correctamente
- [ ] Verificar que el layout es de dos columnas (formulario e info)
- [ ] Verificar que los estilos se aplican correctamente
- [ ] Verificar que los colores coinciden con theme.json

**Resultado esperado**: El bloque se ve profesional y bien diseñado.

### 2.2 Formulario - Campos

- [ ] Verificar que todos los campos están presentes:
  - [ ] Nombre
  - [ ] Email
  - [ ] Asunto
  - [ ] Mensaje
- [ ] Verificar que todos los campos tienen labels
- [ ] Verificar que los campos requeridos tienen asterisco rojo
- [ ] Verificar que los placeholders son apropiados

**Resultado esperado**: El formulario tiene todos los campos necesarios con labels claros.

### 2.3 Validación de Campos Vacíos

- [ ] Hacer clic en el campo "Nombre" y salir sin escribir
- [ ] Verificar que aparece borde rojo y mensaje de error
- [ ] Repetir con "Email", "Asunto" y "Mensaje"
- [ ] Verificar que todos muestran validación

**Resultado esperado**: Los campos vacíos se marcan como inválidos al perder el foco.

### 2.4 Validación de Email

- [ ] Escribir un email inválido (ej: "test@")
- [ ] Salir del campo
- [ ] Verificar que aparece mensaje de error
- [ ] Escribir un email válido (ej: "test@example.com")
- [ ] Verificar que el error desaparece

**Resultado esperado**: La validación de email funciona correctamente.

### 2.5 Envío del Formulario (Validación)

- [ ] Intentar enviar el formulario vacío
- [ ] Verificar que todos los campos se marcan como inválidos
- [ ] Llenar todos los campos correctamente
- [ ] Hacer clic en "Send Message"
- [ ] Verificar que el botón se deshabilita
- [ ] Verificar que aparece un spinner en el botón
- [ ] Verificar que aparece mensaje de éxito
- [ ] Verificar que el formulario se resetea

**Resultado esperado**: El formulario valida correctamente y muestra mensaje de éxito (placeholder).

### 2.6 Protección Anti-Spam (Honeypot)

- [ ] Abrir las herramientas de desarrollador (F12)
- [ ] Inspeccionar el formulario
- [ ] Buscar el campo con clase "reforestamos-honeypot"
- [ ] Verificar que el campo está oculto (position: absolute, left: -9999px)
- [ ] Verificar que tiene tabindex="-1"

**Resultado esperado**: El campo honeypot está presente pero invisible para usuarios humanos.

### 2.7 Información de Contacto

- [ ] Verificar que se muestra el título de la sección
- [ ] Verificar que cada item de información tiene:
  - [ ] Icono apropiado
  - [ ] Label (Email, Phone, Address)
  - [ ] Valor correcto
- [ ] Hacer clic en el email
- [ ] Verificar que abre el cliente de correo
- [ ] Hacer clic en el teléfono
- [ ] Verificar que inicia llamada (en móvil)

**Resultado esperado**: La información de contacto se muestra correctamente con iconos y es interactiva.

### 2.8 Mapa (si está activado)

- [ ] Verificar que el mapa se muestra debajo de la información
- [ ] Verificar que el iframe carga correctamente
- [ ] Verificar que el mapa es interactivo (zoom, pan)
- [ ] Verificar que tiene altura de 300px
- [ ] Verificar que tiene bordes redondeados

**Resultado esperado**: El mapa se muestra y funciona correctamente.

## 3. Testing Responsive

### 3.1 Desktop (>992px)

- [ ] Abrir la página en desktop
- [ ] Verificar que el layout es de dos columnas
- [ ] Verificar que ambas columnas tienen el mismo alto
- [ ] Verificar que hay espacio adecuado entre columnas

**Resultado esperado**: Layout de dos columnas balanceado.

### 3.2 Tablet (768px - 991px)

- [ ] Redimensionar el navegador a ~800px
- [ ] Verificar que las columnas se apilan verticalmente
- [ ] Verificar que hay espacio entre las secciones
- [ ] Verificar que el formulario mantiene buen ancho

**Resultado esperado**: Layout apilado con buen espaciado.

### 3.3 Mobile (<768px)

- [ ] Redimensionar el navegador a ~375px
- [ ] Verificar que todo el contenido es legible
- [ ] Verificar que los campos del formulario tienen buen tamaño
- [ ] Verificar que el botón es fácil de tocar
- [ ] Verificar que el mapa se ajusta al ancho

**Resultado esperado**: Todo es usable en móvil.

## 4. Testing de Accesibilidad

### 4.1 Navegación por Teclado

- [ ] Usar Tab para navegar por el formulario
- [ ] Verificar que el orden de tabulación es lógico
- [ ] Verificar que todos los campos son accesibles
- [ ] Verificar que el botón de envío es accesible
- [ ] Verificar que los enlaces de contacto son accesibles

**Resultado esperado**: Navegación por teclado funciona perfectamente.

### 4.2 Screen Reader

- [ ] Usar un screen reader (NVDA, JAWS, VoiceOver)
- [ ] Verificar que los labels se leen correctamente
- [ ] Verificar que los mensajes de error se anuncian
- [ ] Verificar que el estado del botón se anuncia

**Resultado esperado**: El bloque es completamente accesible con screen readers.

### 4.3 Contraste de Colores

- [ ] Usar una herramienta de contraste (ej: WebAIM Contrast Checker)
- [ ] Verificar que el texto tiene contraste 4.5:1 mínimo
- [ ] Verificar que los iconos tienen buen contraste
- [ ] Verificar que los mensajes de error son legibles

**Resultado esperado**: Todos los elementos cumplen con WCAG AA.

## 5. Testing de Compatibilidad

### 5.1 Navegadores

- [ ] Chrome/Edge (últimas versiones)
- [ ] Firefox (última versión)
- [ ] Safari (última versión)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

**Resultado esperado**: Funciona correctamente en todos los navegadores modernos.

### 5.2 WordPress

- [ ] WordPress 6.0
- [ ] WordPress 6.1
- [ ] WordPress 6.2+

**Resultado esperado**: Compatible con WordPress 6.0+.

## 6. Testing de Performance

### 6.1 Carga de Assets

- [ ] Abrir DevTools > Network
- [ ] Recargar la página
- [ ] Verificar que se cargan:
  - [ ] style-index.css
  - [ ] frontend.js (contacto)
  - [ ] Bootstrap CSS/JS
- [ ] Verificar que no hay errores 404
- [ ] Verificar que los assets están minificados

**Resultado esperado**: Todos los assets se cargan correctamente.

### 6.2 Consola del Navegador

- [ ] Abrir DevTools > Console
- [ ] Recargar la página
- [ ] Verificar que no hay errores JavaScript
- [ ] Enviar el formulario
- [ ] Verificar que aparecen logs de desarrollo
- [ ] Verificar mensaje sobre Communication Plugin

**Resultado esperado**: No hay errores, solo logs informativos.

## 7. Testing de Integración Futura

### 7.1 Atributos de Datos

- [ ] Inspeccionar el formulario en el DOM
- [ ] Verificar que tiene atributo `data-form-id`
- [ ] Verificar que el valor coincide con el configurado

**Resultado esperado**: El formulario tiene el ID correcto para integración futura.

### 7.2 Variables JavaScript

- [ ] Abrir la consola del navegador
- [ ] Escribir: `console.log(reforestamosContactForm)`
- [ ] Verificar que existe el objeto
- [ ] Verificar que tiene propiedades:
  - [ ] ajaxUrl
  - [ ] nonce

**Resultado esperado**: Las variables están disponibles para el Communication Plugin.

## 8. Checklist Final

- [ ] El bloque se inserta correctamente en el editor
- [ ] Todos los atributos son configurables
- [ ] El preview del editor es preciso
- [ ] El frontend se renderiza correctamente
- [ ] La validación de formulario funciona
- [ ] El diseño es responsive
- [ ] Es accesible por teclado y screen reader
- [ ] Funciona en todos los navegadores principales
- [ ] No hay errores en la consola
- [ ] Los assets se cargan correctamente
- [ ] Está preparado para integración con Communication Plugin

## Problemas Conocidos

### Limitaciones Actuales

1. **Envío de Email**: El formulario actualmente no envía emails reales. Esto se implementará en el Communication Plugin (tarea 20).

2. **Mensaje Placeholder**: El mensaje de éxito es un placeholder. La funcionalidad real se agregará con el Communication Plugin.

3. **Almacenamiento**: Los submissions no se guardan en la base de datos. Esto se implementará en el Communication Plugin.

## Notas para Desarrollo Futuro

Cuando se implemente el Communication Plugin (tarea 20):

1. Descomentar el código AJAX en `frontend.js`
2. Crear el endpoint `reforestamos_contact_form_submit`
3. Implementar validación server-side
4. Implementar envío de emails con PHPMailer
5. Implementar almacenamiento en base de datos
6. Actualizar los mensajes de éxito/error

## Reporte de Bugs

Si encuentras algún problema durante el testing:

1. Anotar el navegador y versión
2. Anotar los pasos para reproducir
3. Capturar screenshot si es posible
4. Revisar la consola del navegador
5. Reportar en el sistema de issues del proyecto
