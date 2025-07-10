# 📌 Constantes del Framework

Patata Framework define una serie de **constantes globales** que representan rutas clave del proyecto.  

---

## 📁 Rutas base y configuración

| Constante              | Descripción                                                     |
|------------------------|-----------------------------------------------------------------|
| `PATH_ROOT`            | Raíz del proyecto                                               |
| `PATH_CORE`            | Código principal del framework (`/core`)                        |
| `PATH_MODULES`         | Carpeta general de módulos (`/modules`)                         |
| `PATH_MODULES_PATATA`  | Módulos del framework Patata (`/modules/patata`)                |
| `PATH_FOR_CUSTOM`      | Carpeta de personalizaciones (`/for-custom`)                    |
| `CUSTOM_CONFIG_PATH`   | Ruta del archivo de configuración personalizado (`/for-custom/config.ini`) |

---

## 🔧 Back-End

| Constante         | Descripción                              |
|-------------------|------------------------------------------|
| `PATH_BACK_END`   | Carpeta raíz del back-end                |
| `PATH_CONTROLLER` | Controladores (`/back-end/controller`)   |
| `PATH_MODEL`      | Modelos (`/back-end/model`)              |
| `PATH_VIEW`       | Vistas (`/back-end/view`)                |
| `PATH_VALIDATOR`  | Validadores (`/back-end/validator`)      |
| `PATH_HELPER`     | Ayudantes (`/back-end/helper`)           |

---

## 🎨 Front-End

| Constante         | Descripción                                          |
|-------------------|------------------------------------------------------|
| `PATH_FRONT_END`  | Carpeta raíz del front-end (`/front-end`)           |
| `PATH_HTML`       | Plantillas HTML (`/front-end/html`)                 |
| `PATH_CSS`        | Ruta web a estilos CSS (`/front-end/css`)           |
| `PATH_JS`         | Ruta web a scripts JS (`/front-end/js`)             |
| `URL_BASE`        | URL base del proyecto (calculada automáticamente)   |

---

## 📦 Recursos

| Constante                 | Descripción                                           |
|---------------------------|-------------------------------------------------------|
| `PATH_RESOURCES_PUBLIC`   | Recursos públicos (`/resources/public`)              |
| `PATH_RESOURCES_PRIVATE`  | Recursos privados (`/resources/private`)             |
| `PATH_GENERATEDS`         | Archivos generados (`/resources/private/generateds`) |
| `PATH_TMP`                | Archivos temporales (`/resources/private/tmp`)       |

---

## ➕ Personalización

Al final del archivo principal de constantes se incluye:

```php
require_once(__DIR__ . '/for-custom/constants.php');
```

Esto permite que definas tus **propias constantes personalizadas** dentro de `/for-custom/constants.php`, sin modificar el núcleo del framework.

Ejemplo:

```php
// for-custom/constants.php
define('PATH_EXAMPLE', PATH_BASE . DIRECTORY_SEPARATOR . 'example');
```

De este modo, tus personalizaciones estarán **separadas, limpias y seguras frente a futuras actualizaciones** del sistema.

---

# ⚙️ Autoload del Framework

El autoload del framework evita la necesidad de usar `require` manuales, registrando múltiples rutas clave desde donde se pueden cargar clases automáticamente.

Esto se implementa mediante llamadas a `spl_autoload_register()` para cada tipo de clase soportada.

---

## 🧩 Rutas soportadas por el autoload

| Tipo de clase | Ruta esperada                                                        |
|---------------|----------------------------------------------------------------------|
| Modelo        | `/back-end/model/class/NombreDeClase.php`                            |
| DAO           | `/back-end/model/dao/NombreDeClase.php`                              |
| Helper        | `/back-end/helper/NombreDeClase.php`                                 |
| Vista         | `/back-end/view/NombreDeClase.php`                                   |

*Nota:* Las vistas no requieren que el nombre de la clase termine en `View`, pero se recomienda mantener un estándar para facilitar el mantenimiento.

---

## ✍️ Extensión personalizada del autoload

El sistema también permite definir **rutas adicionales personalizadas** mediante:

```php
require_once(__DIR__ . '/for-custom/autoload.php');
```

Allí puedes registrar tus propias convenciones de carga automática, por ejemplo:

```php
// for-custom/autoload.php
spl_autoload_register(function($class_name){
    $fullpath = PATH_BASE . '/mi-carpeta/' . $class_name . '.php';
    if(file_exists($fullpath)) {
        require_once($fullpath);
    }
});
```

Esto mantiene el sistema principal **intacto y fácil de extender**.