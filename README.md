# 🥔 Patata Framework

Es un framework inspirado en **Europio Engine**, un motor de frameworks desarrollado por **Eugenia Bahit**, **Patata Framework** está pensado para facilitar el desarrollo de aplicaciones que quieran hacer uso del patrón arquitectónico **MVC** de forma simple y ligera.

Esta herramienta es ideal para proyectos pequeños y medianos y ya viene configurada y lista para usarse; a su vez también permite personalizar algunas funcionalidades del framework de forma sencilla a traves de un archivo `.ini`.

Está especialmente dirigido al desarrollo de **APIs REST**, cuenta con herramientas integradas para un control preciso de acceso a los recursos, siendo posible la validación a través de **tokens preestablecidos**, **direcciones IP**, **rangos de IPs** y **dominios autorizados**, además, el framework cuenta con un **middleware configurable** que puede actuar **antes de que se invoque cualquier controlador o método**, lo que permite tener un control estricto para el acceso a los recursos (según cada caso, por más particular que éste sea).

---

## ✨ Características principales

- 🧠 Basado en el patrón **MVC**
- ⚙️ Configuración centralizada desde un único archivo `.ini`
- 🔐 Control de acceso por IP en modo REST
- 🧩 Middlewares configurables para proteger los recursos según una lógica personalizada.
- 📦 Librerías integradas:
  - **DB**: Acceso a base de datos con una sintaxis simple y segura mediante binding automático.
  - **Validator**: Librería para realizar la validacíon de datos, además permite agregarle fácilmente nuevas reglas de validación.

---

## Estructura de Carpetas

- **back-end/**  
  Lógica del sistema: maneja peticiones, datos y reglas del negocio.  

- **core/**  
  Parte interna del framework. ⚠️ **No debe modificarse**.

- **for-custom/**  
  Configura el comportamiento del framework y módulos.

- **front-end/**  
  Interfaz del usuario: HTML, JS y CSS.  

- **modules/**  
  Módulos reutilizables e independientes que pueden extender la funcionalidad del proyecto.

---

## Modos de Uso

Se debe de elegir el modo de funcionamiento del framework (clásico o REST), éste se configura desde el archivo `config.ini` ubicado en la raíz del proyecto, dentro de dicho archivo, encontrarás la siguiente línea:

```
ENABLE_REST = false
```

- Si el valor es `false`, el framework usará el modo clásico (llamado directo por URL).
- Si el valor es `true`, se activará el modo REST, donde el método a ejecutar dependerá del tipo de petición HTTP.


### 1. Llamado Directo por URL

En este modo, la URL define explícitamente qué método ejecutar dentro de un controlador.

Cuando se accede a una ruta como `http://<HOST>/NombreClase/metodo`, el framework buscará la clase `NombreClaseController` dentro de `/back-end/controller` y ejecutará el método indicado.

Cuando la URL no los especifique explícitamente se utilizarán valores predeterminados para ejecutar un controlador y un método (Page e index, respectivamente), en caso que se quiera cambiar dichos valores predeterminados, ingresa al archivo /for-custom/config.ini y asigna los valores deseados:

```ini
; *************** ClassicalURIDecoder ***************
DEFAULT_CLASS = Page
DEFAULT_METHOD = index
```

Si el controlador o el método especificado en la URL no existen, el framework ejecutará una clase y un método configurados como respuesta de error.  
Por defecto, se usará el controlador `PageController` y el método `s404`.

Este comportamiento se puede personalizar desde el mismo archivo `/for-custom/config.ini`:

```ini
; *************** Caller ***************
CONTROLLER_SUFFIX = Controller
S404_CONTROLLER = Page
S404_METHOD = s404
```

Para aplicar cambios, encuentra las líneas `S404_CONTROLLER` y `S404_METHOD` y asigna los valores que desees.  
La línea `CONTROLLER_SUFFIX` define el sufijo que se agregará automáticamente al nombre de clase (por defecto, `Controller`).

#### Argumentos

Si en la URL se incluyen más segmentos después del nombre del método, estos serán enviados como parámetros al método correspondiente, por ejemplo, al acceder a la siguiente URL:

```
http://<HOST>/api/Page/index/a/b/c
```

El framework pasará los valores `'a'`, `'b'` y `'c'` como argumentos al método `index` de la clase `PageController`:

```php
public function index($x = NULL, $y = NULL, $z = NULL) {}
```

En este caso, `$x` recibirá `'a'`, `$y` recibirá `'b'` y `$z` recibirá `'c'`.

### 2. Modo REST

En este modo, el método que se ejecutará dentro del controlador depende del tipo de petición HTTP que se realice.

Cuando se accede a una ruta como `http://<HOST>/NombreClase`, el framework buscará la clase `NombreClaseController` dentro de `/back-end/controller` y llamará al método correspondiente según el verbo HTTP utilizado.

Por ejemplo, si se hace una solicitud `GET` a `http://<HOST>/Page`, se invocará el método `get()` de la clase `PageController`. Para una solicitud `POST`, se llamará al método `post()`, y así sucesivamente con `put()`, `delete()`, etc.

Si se desea personalizar el nombre de los métodos que responden a cada tipo de petición HTTP, se puede hacer desde el archivo `/for-custom/config.ini`.

Para que esta configuración tenga efecto, es obligatorio que `[METHODS]` esté descomentada junto con las claves correspondientes al verbo HTTP que se desea modificar:

```ini
[METHODS] ; For this configuration you must uncomment this line and its related keys
GET = get
POST = post
PUT = put
DELETE = delete
PATCH = patch
```

Por ejemplo, si se desea que las peticiones `GET` llamen al método `mostrar()` en lugar de `get()`, se debe de realizar la siguiente configuración:

```ini
[METHODS]
GET = mostrar
```

#### Excepciones en clases

En algunos casos, es posible que se quiera excluir ciertas clases del procesamiento automático del modo REST, por ejemplo, cuando se desea documentar la API u ofrecer respuestas personalizadas desde clases concretas, para ello, se puede usar la opción `CLASS_EXCEPTIONS` dentro del archivo `/for-custom/config.ini`, indicando una lista separada por comas con los nombres de las clases controllers a excluir:

```ini
CLASS_EXCEPTIONS = Page,AnotherPage
```

Básicamente, las clases listadas aquí serán tratadas bajo el esquema del primer modo (llamado directo por URL), ignorando el tipo de petición HTTP.

⚠️ **Nota:** El tratamiento de parámetros adicionales en la URL funciona igual que en el primer modo, por ejemplo, si se accede a `/Page/a/b/c` mediante una petición `GET`, los valores `a`, `b` y `c` serán pasados como argumentos al método `get()`.

⚠️ **Importante:** Al igual que en el primer modo, existe un controlador y un método por defecto preestablecidos y estos pueden ser modificados en el archivo `/for-custom/config.ini` (en las líneas `DEFAULT_CLASS` y `DEFAULT_METHOD`).

---

## ⚙️ Carpeta `for-custom/`: Personaliza sin romper

Todo el comportamiento del framework puede personalizarse mediante la carpeta `for-custom/`, el contenido de ésta incluye:

| Archivo / Ruta         | Descripción                                                            |
|------------------------|-------------------------------------------------------------------------|
| `config.ini`           | Configura conexión DB, zona horaria, codificación, opciones REST, etc.     |
| `constants.php`        | Archivo para definir constantes personalizadas                         |
| `MyRule.php`           | Permite crear nuevas reglas de validación mediante clases propias      |
| `MyMiddleware.php`     | Middleware base ya definido, que puede modificarse o extenderse        |
| `autoload.php`         | Define clases a cargar automáticamente (modelos, helpers, DAOs, etc.)  |

---

## Otras Documentaciones

A continuación se listan enlaces hacia documentación adicional sobre configuraciones y módulos específicos del framework:

- [Utilitario Repositorio](docs/repository.md)
- [Configuración de REST](docs/rest.md)
- [Configuración de Middlewares](docs/middlewares.md)
- [Módulo Validator](docs/validator.md)
- [Módulo DB](docs/db.md)
- [Módulo Response](docs/response.md)
- [Otras documentaciones](docs/otros.md)