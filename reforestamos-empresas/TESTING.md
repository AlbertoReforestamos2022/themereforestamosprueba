# Manual Testing Guide - Reforestamos Empresas Plugin

## Prerequisitos

- WordPress 6.0 o superior instalado
- PHP 7.4 o superior
- Plugin Reforestamos Core instalado (pero no necesariamente activo para todas las pruebas)

## Test Suite 1: Verificación de Dependencias

### Test 1.1: Activación sin Core Plugin

**Objetivo:** Verificar que el plugin no se activa si Core no está activo.

**Pasos:**
1. Asegúrate de que el plugin Reforestamos Core esté **desactivado**
2. Ve a Plugins → Plugins instalados
3. Intenta activar "Reforestamos Empresas"

**Resultado Esperado:**
- ❌ El plugin NO debe activarse
- ✓ Debe aparecer un mensaje de error con wp_die()
- ✓ El mensaje debe decir: "Reforestamos Empresas requiere que el plugin Reforestamos Core esté activo"
- ✓ Debe haber un enlace "Volver" para regresar a la página de plugins
- ✓ El plugin debe permanecer desactivado

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

### Test 1.2: Activación con Core Plugin Activo

**Objetivo:** Verificar que el plugin se activa correctamente cuando Core está activo.

**Pasos:**
1. Activa el plugin Reforestamos Core
2. Ve a Plugins → Plugins instalados
3. Activa "Reforestamos Empresas"

**Resultado Esperado:**
- ✓ El plugin debe activarse sin errores
- ✓ No debe aparecer ningún mensaje de error
- ✓ El plugin debe aparecer como "Activo" en la lista

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

### Test 1.3: Desactivación de Core con Empresas Activo

**Objetivo:** Verificar el comportamiento cuando Core se desactiva después de que Empresas está activo.

**Pasos:**
1. Con ambos plugins activos (Core y Empresas)
2. Desactiva el plugin Reforestamos Core
3. Visita cualquier página del admin (ej: Dashboard, Páginas, etc.)

**Resultado Esperado:**
- ✓ Debe aparecer un aviso de error en la parte superior del admin
- ✓ El aviso debe decir: "Reforestamos Empresas requiere que el plugin Reforestamos Core esté activo"
- ✓ El aviso debe ser de tipo "error" (fondo rojo)
- ✓ No deben aparecer errores fatales de PHP
- ✓ El plugin Empresas debe permanecer activo pero sin funcionalidad

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

## Test Suite 2: Estructura del Plugin

### Test 2.1: Verificación de Archivos

**Objetivo:** Verificar que todos los archivos necesarios existen.

**Pasos:**
1. Navega a `wp-content/plugins/reforestamos-empresas/`
2. Verifica la existencia de los siguientes archivos y directorios:

**Checklist de Archivos:**
- [ ] `reforestamos-empresas.php` (archivo principal)
- [ ] `README.md`
- [ ] `uninstall.php`
- [ ] `includes/` (directorio)
- [ ] `includes/class-reforestamos-empresas.php`
- [ ] `admin/` (directorio)
- [ ] `admin/css/admin.css`
- [ ] `admin/js/admin.js`
- [ ] `assets/` (directorio)
- [ ] `assets/css/frontend.css`
- [ ] `assets/js/frontend.js`
- [ ] `templates/` (directorio)
- [ ] `languages/` (directorio)
- [ ] `languages/reforestamos-empresas.pot`

**Resultado Real:**
- [ ] Todos los archivos existen
- [ ] Faltan archivos (listar):

---

### Test 2.2: Verificación de Constantes

**Objetivo:** Verificar que las constantes del plugin se definen correctamente.

**Pasos:**
1. Activa ambos plugins (Core y Empresas)
2. Instala y activa el plugin "Query Monitor" o similar
3. Verifica que las siguientes constantes existen:

**Checklist de Constantes:**
- [ ] `REFORESTAMOS_EMPRESAS_VERSION`
- [ ] `REFORESTAMOS_EMPRESAS_PATH`
- [ ] `REFORESTAMOS_EMPRESAS_URL`
- [ ] `REFORESTAMOS_EMPRESAS_BASENAME`

**Resultado Real:**
- [ ] Todas las constantes definidas
- [ ] Faltan constantes (listar):

---

## Test Suite 3: Funcionalidad Básica

### Test 3.1: Carga de Assets Frontend

**Objetivo:** Verificar que los assets del frontend se cargan correctamente.

**Pasos:**
1. Con ambos plugins activos
2. Visita cualquier página del frontend
3. Abre las herramientas de desarrollo del navegador (F12)
4. Ve a la pestaña "Network" o "Red"
5. Recarga la página
6. Busca los siguientes archivos:

**Checklist de Assets:**
- [ ] `frontend.css` se carga sin errores (código 200)
- [ ] `frontend.js` se carga sin errores (código 200)
- [ ] No hay errores 404 relacionados con el plugin

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

### Test 3.2: Carga de Assets Admin

**Objetivo:** Verificar que los assets del admin se cargan en páginas de empresas.

**Pasos:**
1. Con ambos plugins activos
2. Ve a Empresas → Todas las empresas (o añadir nueva)
3. Abre las herramientas de desarrollo del navegador (F12)
4. Ve a la pestaña "Network" o "Red"
5. Busca los siguientes archivos:

**Checklist de Assets:**
- [ ] `admin.css` se carga sin errores (código 200)
- [ ] `admin.js` se carga sin errores (código 200)
- [ ] Los assets solo se cargan en páginas de empresas, no en otras páginas del admin

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

## Test Suite 4: Base de Datos

### Test 4.1: Creación de Tabla en Activación

**Objetivo:** Verificar que la tabla de analytics se crea al activar el plugin.

**Pasos:**
1. Desactiva el plugin Reforestamos Empresas
2. Accede a phpMyAdmin o similar
3. Verifica si existe la tabla `wp_reforestamos_empresas_analytics`
4. Si existe, elimínala
5. Activa el plugin Reforestamos Empresas
6. Verifica nuevamente la existencia de la tabla

**Resultado Esperado:**
- ✓ La tabla `wp_reforestamos_empresas_analytics` debe existir
- ✓ La tabla debe tener las siguientes columnas:
  - `id` (bigint, primary key, auto_increment)
  - `company_id` (bigint)
  - `click_type` (varchar)
  - `user_ip` (varchar)
  - `user_agent` (text)
  - `referrer` (text)
  - `clicked_at` (datetime)

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

### Test 4.2: Creación de Opciones en Activación

**Objetivo:** Verificar que las opciones por defecto se crean al activar el plugin.

**Pasos:**
1. Desactiva el plugin Reforestamos Empresas
2. En phpMyAdmin, elimina las siguientes opciones de la tabla `wp_options`:
   - `reforestamos_empresas_enable_analytics`
   - `reforestamos_empresas_enable_galleries`
   - `reforestamos_empresas_grid_columns`
3. Activa el plugin Reforestamos Empresas
4. Verifica que las opciones existen con valores por defecto

**Resultado Esperado:**
- ✓ `reforestamos_empresas_enable_analytics` = '1'
- ✓ `reforestamos_empresas_enable_galleries` = '1'
- ✓ `reforestamos_empresas_grid_columns` = '3'

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

## Test Suite 5: Desinstalación

### Test 5.1: Limpieza en Desinstalación

**Objetivo:** Verificar que el plugin limpia sus datos al desinstalarse.

**Pasos:**
1. Con el plugin activo, verifica que existen:
   - Tabla `wp_reforestamos_empresas_analytics`
   - Opciones del plugin en `wp_options`
2. Desactiva el plugin
3. Elimina el plugin (no solo desactivar)
4. Verifica que se han eliminado:
   - La tabla de analytics
   - Las opciones del plugin

**Resultado Esperado:**
- ✓ La tabla debe ser eliminada
- ✓ Las opciones deben ser eliminadas
- ✓ No deben quedar datos huérfanos

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

## Test Suite 6: Compatibilidad

### Test 6.1: Compatibilidad con Core Plugin

**Objetivo:** Verificar que el plugin no interfiere con la funcionalidad del Core.

**Pasos:**
1. Con ambos plugins activos
2. Ve a Empresas → Añadir nueva
3. Crea una nueva empresa con todos los campos del Core
4. Guarda la empresa
5. Verifica que se guardó correctamente

**Resultado Esperado:**
- ✓ La empresa se crea sin errores
- ✓ Todos los campos del Core funcionan normalmente
- ✓ No hay conflictos de JavaScript o CSS

**Resultado Real:**
- [ ] Pasó
- [ ] Falló (describir el problema):

---

## Resumen de Resultados

**Total de Tests:** 11
**Tests Pasados:** ___
**Tests Fallados:** ___
**Tests No Ejecutados:** ___

**Fecha de Testing:** _______________
**Testeado por:** _______________
**Versión del Plugin:** 1.0.0
**Versión de WordPress:** _______________
**Versión de PHP:** _______________

## Notas Adicionales

(Espacio para notas, observaciones o problemas encontrados durante el testing)

---

## Criterios de Aceptación

Para que el Task 24 se considere completado, deben cumplirse:

✅ **Requirement 20.1:** Plugin modular para funcionalidad de empresas
- [ ] Plugin instalado y activable

✅ **Requirement 20.3:** Dependencia del Core Plugin
- [ ] Test 1.1 pasado (no activa sin Core)
- [ ] Test 1.2 pasado (activa con Core)
- [ ] Test 1.3 pasado (aviso cuando Core se desactiva)

✅ **Requirement 20.4:** Verificación de dependencias en activación
- [ ] Test 1.1 pasado
- [ ] Mensaje de error claro y útil

✅ **Requirement 20.5:** Estructura de directorios estándar
- [ ] Test 2.1 pasado (todos los archivos existen)

✅ **Requirement 20.6:** Clase principal del plugin
- [ ] Clase `Reforestamos_Empresas` existe
- [ ] Patrón Singleton implementado
- [ ] Hooks inicializados correctamente

✅ **Requirement 20.7:** Hooks de activación/desactivación
- [ ] Test 4.1 pasado (tabla creada en activación)
- [ ] Test 4.2 pasado (opciones creadas en activación)

✅ **Requirement 20.8:** Mensajes de error si dependencias no están satisfechas
- [ ] Test 1.1 pasado (mensaje en activación)
- [ ] Test 1.3 pasado (aviso en admin)
