# Reforestamos Micrositios Plugin

Plugin de WordPress para gestionar micrositios interactivos con mapas Leaflet.

## Descripción

Este plugin proporciona dos micrositios interactivos:

1. **Árboles y Ciudades**: Mapa interactivo que muestra ubicaciones de árboles plantados en diferentes ciudades de México.
2. **Red OJA**: Mapa y directorio de Organizaciones Juveniles Ambientales en México.

## Características

- Mapas interactivos con Leaflet
- Shortcodes para insertar micrositios en páginas
- Gestión de datos mediante archivos JSON
- Filtros y búsqueda en tiempo real
- Estadísticas de árboles y organizaciones
- Responsive y accesible

## Requisitos

- WordPress 6.0 o superior
- PHP 7.4 o superior

## Instalación

1. Sube el directorio `reforestamos-micrositios` a `/wp-content/plugins/`
2. Activa el plugin desde el menú 'Plugins' en WordPress
3. Ve a 'Micrositios' en el menú de administración para gestionar los datos

## Uso

### Shortcodes

#### Árboles y Ciudades

```
[arboles-ciudades]
```

Atributos opcionales:
- `height`: Altura del mapa (default: 600px)
- `zoom`: Nivel de zoom inicial (default: 6)
- `center_lat`: Latitud del centro del mapa (default: 23.6345)
- `center_lng`: Longitud del centro del mapa (default: -102.5528)

Ejemplo:
```
[arboles-ciudades height="500px" zoom="7"]
```

#### Red OJA

```
[red-oja]
```

Atributos opcionales:
- `height`: Altura del mapa (default: 600px)
- `zoom`: Nivel de zoom inicial (default: 5)
- `center_lat`: Latitud del centro del mapa (default: 23.6345)
- `center_lng`: Longitud del centro del mapa (default: -102.5528)
- `view`: Vista a mostrar - 'map', 'directory', o 'both' (default: map)

Ejemplo:
```
[red-oja view="both" height="700px"]
```

## Estructura de Datos

### Árboles y Ciudades (arboles-ciudades.json)

```json
{
  "version": "1.0",
  "last_updated": "2024-01-15",
  "arboles": [
    {
      "id": 1,
      "especie": "Pino",
      "nombre_cientifico": "Pinus montezumae",
      "ciudad": "Ciudad de México",
      "estado": "CDMX",
      "ubicacion": {
        "lat": 19.4326,
        "lng": -99.1332
      },
      "fecha_plantacion": "2023-06-15",
      "cantidad": 500,
      "descripcion": "Reforestación en el Ajusco"
    }
  ]
}
```

### Red OJA (red-oja.json)

```json
{
  "version": "1.0",
  "last_updated": "2024-01-15",
  "organizaciones": [
    {
      "id": 1,
      "nombre": "Organización Ejemplo",
      "estado": "Jalisco",
      "ciudad": "Guadalajara",
      "tipo": "Asociación Civil",
      "ubicacion": {
        "lat": 20.6597,
        "lng": -103.3496
      },
      "descripcion": "Descripción de la organización",
      "contacto": {
        "email": "contacto@ejemplo.org",
        "telefono": "33-1234-5678",
        "website": "https://ejemplo.org"
      },
      "miembros": 50
    }
  ]
}
```

## Desarrollo

### Estructura de Archivos

```
reforestamos-micrositios/
├── includes/                          # Clases PHP
│   ├── class-reforestamos-micrositios.php
│   ├── class-arboles-ciudades.php
│   ├── class-red-oja.php
│   ├── class-map-renderer.php
│   └── class-json-manager.php
├── assets/                            # Assets frontend
│   ├── css/
│   │   └── maps.css
│   ├── js/
│   │   └── map-handler.js
│   └── images/
│       └── markers/
├── data/                              # Archivos JSON
│   ├── arboles-ciudades.json
│   └── red-oja.json
├── admin/                             # Admin interface
│   ├── views/
│   └── templates/
├── languages/                         # Traducciones
├── reforestamos-micrositios.php      # Archivo principal
└── README.md
```

## Changelog

### 1.0.0
- Versión inicial
- Micrositio Árboles y Ciudades
- Micrositio Red OJA
- Integración con Leaflet
- Gestión de datos JSON

## Licencia

GPL v2 or later

## Autor

Reforestamos México
https://reforestamos.org
