# Bloque Contacto

Bloque personalizado de Gutenberg para mostrar un formulario de contacto con información de contacto configurable.

## Características

- **Formulario de contacto** con campos: nombre, email, asunto, mensaje
- **Información de contacto** configurable (teléfono, dirección, email)
- **Mapa de ubicación** opcional (iframe de Google Maps)
- **Validación de campos** en el frontend con feedback visual
- **Protección anti-spam** con campo honeypot
- **Diseño responsive** (formulario e info lado a lado en desktop, apilados en móvil)
- **Preparado para integración** con Communication Plugin (tarea 20)

## Atributos

### Configuración del Formulario

- `formId` (string): Identificador único del formulario para integración futura con Communication Plugin
- `formTitle` (string): Título del formulario (default: "Envíanos un mensaje")

### Información de Contacto

- `infoTitle` (string): Título de la sección de información (default: "Información de contacto")
- `showEmail` (boolean): Mostrar/ocultar email (default: true)
- `emailAddress` (string): Dirección de email (default: "contacto@reforestamos.org")
- `showPhone` (boolean): Mostrar/ocultar teléfono (default: true)
- `phoneNumber` (string): Número de teléfono (default: "+52 55 1234 5678")
- `showAddress` (boolean): Mostrar/ocultar dirección (default: true)
- `address` (string): Dirección física (default: "Ciudad de México, México")

### Mapa

- `showMap` (boolean): Mostrar/ocultar mapa (default: false)
- `mapEmbedUrl` (string): URL del iframe de Google Maps

## Uso

### En el Editor

1. Agregar el bloque "Contacto" desde la categoría "Reforestamos"
2. Configurar los atributos en el panel de Inspector:
   - Establecer un Form ID único (opcional)
   - Personalizar el título del formulario
   - Configurar qué información de contacto mostrar
   - Agregar datos de contacto (email, teléfono, dirección)
   - Opcionalmente, agregar un mapa con la URL del iframe

### Configuración del Mapa

Para agregar un mapa de Google Maps:

1. Ir a [Google Maps](https://www.google.com/maps)
2. Buscar la ubicación deseada
3. Hacer clic en "Compartir" > "Insertar un mapa"
4. Copiar solo la URL del atributo `src` del iframe
5. Pegar la URL en el campo "Map Embed URL" del bloque

Ejemplo de URL:
```
https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762...
```

## Validación del Formulario

El bloque incluye validación frontend automática:

- **Campos requeridos**: Nombre, email, asunto, mensaje
- **Validación de email**: Formato válido de dirección de email
- **Feedback visual**: Los campos inválidos se marcan en rojo con mensajes de error
- **Validación en tiempo real**: Los campos se validan al perder el foco y al escribir

## Protección Anti-Spam

El formulario incluye un campo honeypot oculto que detecta bots:

- Campo invisible para usuarios humanos
- Si un bot llena este campo, el envío se rechaza silenciosamente
- No requiere CAPTCHA ni interacción del usuario

## Integración Futura

Este bloque está preparado para integración con el Communication Plugin (tarea 20):

- El atributo `data-form-id` identifica cada formulario
- El script frontend incluye código comentado para AJAX
- Los datos se localizan con `reforestamosContactForm.ajaxUrl` y `reforestamosContactForm.nonce`
- Por ahora, el formulario muestra un mensaje de éxito placeholder

### Implementación Futura (Communication Plugin)

Cuando se implemente el Communication Plugin, se deberá:

1. Crear un endpoint AJAX `reforestamos_contact_form_submit`
2. Validar el nonce de seguridad
3. Sanitizar y validar los datos del formulario
4. Enviar el email usando PHPMailer
5. Guardar el submission en la base de datos
6. Retornar respuesta JSON con éxito/error

## Estilos

El bloque utiliza:

- **Bootstrap 5** para el grid y componentes de formulario
- **Variables CSS** de theme.json para colores
- **Diseño responsive** con breakpoints de Bootstrap
- **Animaciones suaves** en hover y transiciones

### Personalización de Colores

Los colores se pueden personalizar en `theme.json`:

- `--wp--preset--color--primary`: Color principal (botones, iconos)
- `--wp--preset--color--dark`: Color oscuro (hover, títulos)
- `--wp--preset--color--light`: Color claro (fondo de iconos)

## Archivos

```
blocks/contacto/
├── block.json          # Metadata y configuración del bloque
├── index.js            # Registro del bloque
├── edit.js             # Interfaz del editor
├── save.js             # Renderizado frontend
├── style.scss          # Estilos del bloque
├── frontend.js         # Validación y manejo del formulario
└── README.md           # Esta documentación
```

## Requisitos Cumplidos

Este bloque cumple con los siguientes requisitos del spec:

- **2.2**: Formulario de contacto integrado
- **2.3**: Información de contacto configurable
- **2.4**: Preparado para integración con Communication Plugin
- **2.5**: Diseño responsive y accesible

## Notas de Desarrollo

- El formulario actualmente no envía emails reales (placeholder)
- La funcionalidad de envío se implementará en el Communication Plugin (tarea 20)
- El script frontend está preparado con código comentado para AJAX
- Se incluye logging en consola para desarrollo

## Testing

Para probar el bloque:

1. Compilar assets: `npm run build`
2. Agregar el bloque a una página
3. Configurar los atributos en el Inspector
4. Guardar y previsualizar la página
5. Probar el formulario en el frontend
6. Verificar validación de campos
7. Verificar mensaje de éxito (placeholder)
8. Revisar consola del navegador para logs

## Accesibilidad

El bloque incluye características de accesibilidad:

- Labels asociados a inputs con `htmlFor`
- Campos requeridos marcados con asterisco
- Mensajes de error descriptivos
- Navegación por teclado funcional
- Atributos ARIA apropiados
- Contraste de colores adecuado
