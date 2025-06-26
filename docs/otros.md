# Constantes del Framework

Patata Framework dispone de una serie de **constantes globales** que representan rutas clave del proyecto.  
Estas constantes permiten mantener un código limpio, mantenible y desacoplado de rutas físicas.

---

## 📁 Rutas base y configuración

| Constante              | Apunta a...                                                        |
|------------------------|---------------------------------------------------------------------|
| `PATH_BASE`            | Raíz del proyecto                                                  |
| `PATH_CORE`            | Código principal del framework (`/core`)                           |
| `PATH_MODULES`         | Carpeta general de módulos (`/modules`)                            |
| `PATH_MODULES_PATATA`  | Módulos del framework Patata (`/modules/patata`)                   |

---

## 🔧 Back-End

| Constante           | Apunta a...                        |
|---------------------|-------------------------------------|
| `PATH_BACK_END`     | Carpeta raíz del back-end          |
| `PATH_CONTROLLER`   | Controladores (`/back-end/controller`) |
| `PATH_MODEL`        | Modelos (`/back-end/model`)        |
| `PATH_VIEW`         | Vistas (`/back-end/view`)          |
| `PATH_VALIDATOR`    | Validadores (`/back-end/validator`)|
| `PATH_HELPER`       | Ayudantes (`/back-end/helper`)     |

---

## 🎨 Front-End

| Constante         | Apunta a...                          |
|-------------------|---------------------------------------|
| `PATH_FRONT_END`  | Carpeta raíz del front-end           |
| `PATH_HTML`       | Plantillas HTML (`/front-end/html`)  |
| `PATH_CSS`        | Ruta web a estilos (`/front-end/css`)|
| `PATH_JS`         | Ruta web a scripts (`/front-end/js`) |
| `URL_BASE`        | URL base del proyecto (calculada automáticamente) |

---

## 📦 Recursos

| Constante                 | Apunta a...                                  |
|---------------------------|-----------------------------------------------|
| `PATH_RESOURCES_PUBLIC`   | Recursos públicos (`/resources/public`)       |
| `PATH_RESOURCES_PRIVATE`  | Recursos privados (`/resources/private`)      |
| `PATH_GENERATEDS`         | Archivos generados (`/resources/private/generateds`) |
| `PATH_TMP`                | Archivos temporales (`/resources/private/tmp`)       |

---

## ➕ Personalización

Al final del archivo de constantes se incluye:

```php
require_once(__DIR__ . '/for-custom/constants.php');
```

Puedes definir **tus propias constantes personalizadas** dentro del archivo `/for-custom/constants.php` sin necesidad de modificar las rutas internas del framework.

Por ejemplo:

```php
// for-custom/constants.php
define('PATH_EXAMPLE', PATH_BASE . DIRECTORY_SEPARATOR . 'example');
```

Esto te permite mantener las personalizaciones limpias, separadas y seguras frente a futuras actualizaciones del núcleo del sistema.

---

# ⚙️ Autoload del Framework

El autoload del framework registra múltiples rutas comunes para que las clases sean cargadas automáticamente al usarse, evitando así la necesidad de hacer `require` manual.

Se usa `spl_autoload_register()` para definir cada una de las ubicaciones clave:

---

## 🧩 Rutas soportadas por autoload

| Tipo de clase | Ruta esperada                                                        |
|---------------|----------------------------------------------------------------------|
| Modelo        | `/back-end/model/class/NombreDeClase.php`                            |
| DAO           | `/back-end/model/dao/NombreDeClase.php`                              |
| Helper        | `/back-end/helper/NombreDeClase.php`                                 |
| Vista (`*View`)| `/back-end/view/NombreDeClaseView.php` *(termina en View)*          |

---

## ✍️ Personalización

El archivo también carga un archivo adicional opcional para agregar reglas de autoload personalizadas:

```php
require_once(__DIR__ . '/for-custom/autoload.php');
```

Allí puedes definir tus propias rutas o convenciones para el cargado automático:

```php
// for-custom/autoload.php
spl_autoload_register(function($class_name){
    $fullpath = PATH_BASE . '/mi-carpeta/' . $class_name . '.php';
    if(file_exists($fullpath)) { require_once($fullpath); }
});
```

Esto mantiene el autoload principal limpio, mientras permite extenderlo sin modificar su núcleo.

---
