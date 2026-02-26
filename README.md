# Tema WordPress - Reforestamos México

Tema personalizado de WordPress desarrollado para la organización Reforestamos México, enfocado en la gestión de contenido relacionado con reforestación, conservación forestal y educación ambiental.

## Descripción

Este tema de WordPress proporciona una plataforma completa para la gestión de contenido de Reforestamos México, incluyendo:
- Gestión de eventos forestales
- Directorio de empresas colaboradoras
- Sistema de adopción de árboles
- Red de Observatorios de Jóvenes en Acción (Red OJA)
- Árboles y Ciudades
- Blog multiidioma (Español/Inglés)
- Sistema de chatbot integrado
- Gestión de documentos e informes anuales

## Características Principales

### Custom Post Types
- **Empresas**: Directorio de empresas colaboradoras con campos personalizados
- **Eventos**: Sistema de calendario y gestión de eventos forestales (No terminado)
- **Integrantes RMX**: Directorio de miembros de la organización
- **Árboles y Ciudades**: Gestión de proyectos de arbolado urbano
- **Red OJA**: Sistema de observatorios juveniles

### Funcionalidades Especiales
- **Multiidioma**: Integración con DeepL para traducción ES/EN
- **Chatbot**: Sistema de chat integrado para atención a usuarios
- **Custom Fields**: Uso de CMB2 para campos personalizados avanzados
- **PHPMailer**: Sistema de contacto y suscripción a boletín
- **Bootstrap 5**: Framework CSS moderno y responsivo
- **Adopta un Árbol**: Sistema de donaciones y adopción de árboles
- **Buscador Avanzado**: Sistema de búsqueda personalizado
- **Infografías**: Gestión de contenido visual educativo

## Tecnologías Utilizadas

### Backend
- **WordPress**: CMS base
- **PHP 7.4+**: Lenguaje de programación
- **CMB2**: Framework para custom fields
- **Composer**: Gestión de dependencias PHP
- **PHPMailer**: Envío de correos

### Frontend
- **Bootstrap 5**: Framework CSS
- **SCSS/Sass**: Preprocesador CSS
- **JavaScript ES6**: Interactividad
- **Bootstrap Icons**: Iconografía

### Librerías y Dependencias
- **DeepL API**: Traducción automática
- **Voku HTML Dom Parser**: Procesamiento de HTML
- **PSR HTTP**: Implementación de estándares HTTP

## Instalación

### Prerrequisitos
- WordPress 5.8 o superior
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Node.js 14+ y npm (para compilación de assets)
- Composer

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/AlbertoReforestamos2022/themereforestamosprueba.git
cd themereforestamosprueba
```

2. **Instalar dependencias de Composer**
```bash
composer install
```

3. **Instalar dependencias de npm**
```bash
npm install
```

4. **Compilar assets (si es necesario)**
```bash
# Si tienes configurado un build script
npm run build
```

5. **Copiar el tema a WordPress**
```bash
# Copiar todo el contenido a wp-content/themes/
cp -r . /ruta/a/wordpress/wp-content/themes/themereforestamos
```

6. **Activar el tema**
- Ir al panel de WordPress
- Apariencia > Temas
- Activar "Tema Reforestamos"

### Configuración Inicial

1. **Variables de entorno**
Configurar las credenciales necesarias para servicios externos (DeepL, PHPMailer, etc.)

2. **Importar campos personalizados**
Los campos CMB2 se registran automáticamente al activar el tema

3. **Configurar permalinks**
Ajustes > Enlaces permanentes > Nombre de la entrada

## Estructura del Proyecto

```
themereforestamos/
├── CMB2/                              # Framework de custom fields
├── dist/                              # CSS compilado
│   ├── main.css                       # Estilos principales
│   ├── chat.css                       # Estilos del chatbot
│   └── style.css
├── html/                              # Prototipos HTML estáticos
├── img/                               # Recursos de imágenes
│   ├── acciones/
│   ├── AdoptaUnArbol/
│   ├── Carousel/
│   ├── Contacto/
│   ├── Empresas/
│   ├── Eventos/
│   └── [más carpetas de imágenes]
├── inc/                               # Funcionalidades del tema
│   ├── arboles_ciudades_components/   # Componentes Árboles y Ciudades
│   ├── chat-bot/                      # Sistema de chatbot
│   ├── config-theme/                  # Configuración del tema
│   ├── DeepL/                         # Integración con DeepL
│   ├── empresas/                      # Custom post type empresas
│   ├── eventos/                       # Custom post type eventos
│   ├── integrantes-rmx/               # Directorio de integrantes
│   ├── php-mailer-config/             # Configuración de correos
│   ├── red_oja_components/            # Componentes Red OJA
│   ├── suscripcion-boletin/           # Sistema de newsletter
│   ├── custom-fields.php              # Definición de campos personalizados
│   └── functions.php                  # Funciones auxiliares
├── node_modules/                      # Dependencias npm
├── pdf's/                             # Informes anuales PDF
├── src/                               # Assets fuente
│   ├── blog/                          # Scripts del blog
│   ├── calendario/                    # Sistema de calendario
│   ├── css/                           # CSS fuente
│   ├── header/                        # Componentes del header
│   ├── js/                            # JavaScript fuente
│   ├── scss/                          # SCSS/Sass
│   └── traductor/                     # Scripts de traducción
├── template-parts/                    # Plantillas parciales
│   ├── boletin/                       # Templates boletín
│   ├── buscador/                      # Templates buscador
│   ├── content-en/                    # Contenido en inglés
│   ├── pruebas/                       # Templates de prueba
│   ├── adopta-arbol.php
│   ├── contacto.php
│   ├── contenido-arboles-ciudades.php
│   ├── contenido-documentos.php
│   ├── contenido-red-oja.php
│   ├── donar.php
│   ├── empresas.php
│   ├── incendios-forestales.php
│   ├── infografias.php
│   ├── nosotros.php
│   └── que-hacemos.php
├── vendor/                            # Dependencias Composer
│   ├── deeplcom/                      # SDK de DeepL
│   └── [otras dependencias]
├── functions.php                      # Funciones principales del tema
├── index.php                          # Template principal
├── style.css                          # Archivo de información del tema
├── header.php                         # Header del sitio
├── footer.php                         # Footer del sitio
├── single.php                         # Template para posts individuales
├── page.php                           # Template para páginas
├── archive.php                        # Template para archivos
├── composer.json                      # Dependencias PHP
└── package.json                       # Dependencias npm
```

## Uso

### Crear una Empresa

1. Ir a **Empresas > Añadir nueva**
2. Completar los campos personalizados:
   - Nombre de la empresa
   - Logo
   - Descripción
   - Categoría
   - Enlace web
3. Publicar

### Gestionar Eventos

1. Ir a **Eventos > Añadir nuevo**
2. Completar información del evento
3. Configurar fecha y hora en campos personalizados
4. Asignar categorías
5. Publicar

### Sistema de Adopta un Árbol

El sistema permite a los usuarios donar para la adopción de árboles mediante formularios integrados en las páginas correspondientes.

### Red OJA

Gestión de observatorios juveniles con mapas interactivos y fichas de información de cada observatorio.

### Árboles y Ciudades

Sistema de gestión de proyectos de arbolado urbano con información detallada de cada iniciativa.

## Personalización

### Estilos

Los estilos se encuentran en `src/scss/` y se compilan a `dist/main.css`

Para modificar estilos:
```bash
# Editar archivos .scss en src/scss/
# Compilar (si tienes configurado)
npm run build
```

### Plantillas

Las plantillas se encuentran en:
- Raíz: Plantillas principales (index.php, single.php, page.php, etc.)
- `template-parts/`: Plantillas parciales reutilizables

### Funcionalidades

Las funcionalidades personalizadas se encuentran en:
- `functions.php`: Funciones principales
- `inc/`: Módulos organizados por funcionalidad

## Desarrollo

### Flujo de Trabajo Git

```bash
# Crear rama para nueva funcionalidad
git checkout -b feature/nombre-funcionalidad

# Hacer cambios y commits
git add .
git commit -m "feat: descripción del cambio"

# Subir rama
git push origin feature/nombre-funcionalidad

# Crear Pull Request en GitHub
# Después del merge, actualizar main local
git checkout main
git pull origin main
```

## Documentación Adicional
### Custom Fields (CMB2)
Los campos personalizados están definidos en `inc/custom-fields.php`
Documentación CMB2: https://cmb2.io/

### DeepL API

Para traducción automática de contenido ES/EN

Configuración en: `inc/DeepL/`

### PHPMailer

Sistema de envío de correos para formularios de contacto y suscripción

Configuración en: `inc/php-mailer-config/`

## Equipo de Desarrollo

- **Alberto Reforestamos** - Desarrollo principal
- Organización: Reforestamos México

## Reporte de Bugs

Para reportar bugs o solicitar nuevas funcionalidades:
1. Ir a la pestaña **Issues** en GitHub
2. Crear un nuevo issue
3. Describir el problema o funcionalidad solicitada
4. Etiquetar apropiadamente (bug, enhancement, etc.)

## Licencia

[]

## Enlaces Útiles

- [Sitio Web de Reforestamos México](https://www.reforestamos.org)
- [Documentación de WordPress](https://developer.wordpress.org/)
- [CMB2 Documentation](https://cmb2.io/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0/)

## Contacto

Para más información sobre el proyecto:
- Email: [correo de contacto]
- Website: https://www.reforestamos.org

---

**Última actualización**: Febrero 2025
**Versión del tema**: 1.0.0
