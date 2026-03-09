# Reforestamos México - Block Theme

Tema moderno Block Theme para Reforestamos México con Full Site Editing y arquitectura modular.

## Descripción

Este tema utiliza la arquitectura de Block Theme de WordPress, permitiendo edición completa del sitio (Full Site Editing) con bloques Gutenberg personalizados.

## Características

- ✅ Block Theme con Full Site Editing
- ✅ theme.json con paleta de colores y tipografía personalizada
- ✅ Bootstrap 5 desde CDN
- ✅ Bloques personalizados reutilizables
- ✅ Pipeline de compilación con @wordpress/scripts
- ✅ Soporte multiidioma (español/inglés)
- ✅ Diseño responsive
- ✅ Optimizado para performance

## Requisitos

- WordPress 6.0 o superior
- PHP 7.4 o superior
- Node.js 16+ y npm (para desarrollo)

## Instalación

1. Clonar o descargar el tema en `wp-content/themes/`
2. Instalar dependencias de desarrollo:
   ```bash
   npm install
   ```
3. Compilar assets:
   ```bash
   npm run build
   ```
4. Activar el tema desde el panel de WordPress

## Desarrollo

### Comandos disponibles

- `npm run start` - Inicia el modo desarrollo con watch
- `npm run build` - Compila assets para producción
- `npm run format` - Formatea el código
- `npm run lint:css` - Verifica estilos CSS/SCSS
- `npm run lint:js` - Verifica código JavaScript

### Estructura de directorios

```
reforestamos-block-theme/
├── blocks/          # Bloques Gutenberg personalizados
├── templates/       # Templates HTML del tema
├── parts/           # Template parts (header, footer)
├── patterns/        # Block patterns
├── src/             # Archivos fuente (SCSS, JS)
├── build/           # Assets compilados
├── inc/             # Archivos PHP incluidos
├── languages/       # Archivos de traducción
├── functions.php    # Funciones principales del tema
├── theme.json       # Configuración del tema
└── style.css        # Hoja de estilos principal
```

## Paleta de colores

- **Verde Reforestamos** (#2E7D32) - Color primario
- **Verde Claro** (#66BB6A) - Color secundario
- **Naranja Acento** (#FFA726) - Color de acento
- **Verde Oscuro** (#1B5E20) - Color oscuro
- **Verde Muy Claro** (#F1F8E9) - Color claro

## Tipografía

- **Montserrat** - Fuente primaria (títulos)
- **Open Sans** - Fuente secundaria (cuerpo de texto)

## Bloques personalizados

Los bloques personalizados se crearán en el directorio `blocks/`. Cada bloque incluye:
- `block.json` - Metadata del bloque
- `edit.js` - Interfaz del editor
- `save.js` - Renderizado frontend
- `style.scss` - Estilos del bloque
- `index.js` - Registro del bloque

## Soporte

Para reportar problemas o solicitar características, contactar a: contacto@reforestamos.org

## Licencia

GPL v2 or later

## Créditos

Desarrollado por Reforestamos México
https://reforestamos.org
