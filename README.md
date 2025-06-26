# 🥔 Patata Framework

Es un framework inspirado en **Europio Engine**, un motor de frameworks desarrollado por **Eugenia Bahit**, **Patata Framework** está pensado para facilitar el desarrollo de aplicaciones de arquitectura **MVC**, siendo ideal para proyectos pequeños y medianos. La herramienta ya viene completamente configurada y lista para usarse desde el primer momento, permitiendo modificar fácilmente aspectos clave a través de un único archivo `.ini`.

Está especialmente dirigido al desarrollo de **APIs REST**, y ofrece herramientas integradas para un control preciso de acceso a los recursos. Entre ellas se incluye la validación de **tokens preestablecidos**, **direcciones IP**, **rangos de IPs** y **dominios autorizados**. Además, el framework cuenta con un **middleware configurable** que puede actuar **antes de que se invoque cualquier controlador o método**, lo que permite validar la autenticación y autorización de las peticiones de forma anticipada y flexible.

Todo esto sin perder simplicidad: el framework está diseñado para ser **fácilmente extensible** mediante clases personalizadas, aprovechando los conceptos de la **programación orientada a objetos**, sin romper la estructura del sistema.

---

## ✨ Características principales

- 🧠 Basado en el patrón **MVC**
- ⚙️ Configuración centralizada desde un único archivo `.ini`
- 🔐 Control de acceso por IP en modo REST
- 🧩 Middlewares configurables para proteger recursos según lógica personalizada
- 📦 Librerías integradas:
  - **DB**: Acceso a base de datos con una sintaxis simple y segura mediante binding automático.
  - **Validator**: Validación estructurada y extensible de datos con reglas propias.

---

## Estructura de Carpetas

- **back-end/**  
  Lógica del sistema: maneja peticiones, datos y reglas del negocio.  
  Es una de las dos carpetas que el programador puede modificar.

- **core/**  
  Parte interna del framework. No debe modificarse.

- **for-custom/**  
  Configura el comportamiento del framework y módulos.

- **front-end/**  
  Interfaz del usuario: HTML, JS y CSS.  
  Es la otra carpeta que el programador puede modificar.

- **modules/**  
  Módulos reutilizables e independientes que pueden extender la funcionalidad del proyecto.

> ⚠️ **Importante:** El programador solo debe modificar archivos dentro de `back-end/`, `front-end/` y `for-custom/`.


## ⚙️ Carpeta `for-custom/`: Personaliza sin romper

Todo el comportamiento del framework puede personalizarse sin modificar el núcleo. Esta carpeta incluye:

| Archivo / Ruta         | Descripción                                                            |
|------------------------|-------------------------------------------------------------------------|
| `config.ini`           | Configura conexión DB, zona horaria, codificación, modo REST, etc.     |
| `constants.php`        | Archivo para definir constantes personalizadas                         |
| `MyRule.php`           | Permite crear nuevas reglas de validación mediante clases propias      |
| `MyMiddleware.php`     | Middleware base ya definido, que puede modificarse o extenderse        |
| `autoload.php`         | Define clases a cargar automáticamente (modelos, helpers, DAOs, etc.)  |

---

## Modos de Uso

Se debe de elegir el modo de funcionamiento del framework (clásico o REST), éste se configura desde el archivo `config.ini` ubicado en la raíz del proyecto.  

Dentro de dicho archivo, encontrarás la siguiente línea:

```
ENABLE_REST = false
```

- Si el valor es `false`, el framework usará el modo clásico (llamado directo por URL).
- Si el valor es `true`, se activará el modo REST, donde el método a ejecutar dependerá del tipo de petición HTTP.


### 1. Llamado Directo por URL

En este modo, la URL define explícitamente qué método ejecutar dentro de un controlador.

Cuando se accede a una ruta como `http://<HOST>/NombreClase/metodo`, el framework buscará la clase `NombreClaseController` dentro de `/back-end/controller` y ejecutará el método indicado.

Opcionalmente, se pueden definir valores predeterminados para el controlador y el método que se utilizarán cuando la URL no los especifique explícitamente.
Esto se configura en el archivo /for-custom/config.ini, descomentando las líneas correspondientes y asignando los valores deseados:

```ini
; *************** ClassicalURIDecoder ***************
;DEFAULT_CLASS = Page
;DEFAULT_METHOD = index
```

Por ejemplo, si se desea establecer un método predeterminado, se debe descomentar la línea DEFAULT_METHOD y definir el valor correspondiente.
Esto permite que, al acceder a la raíz del sitio (http://<HOST>/), se invoque automáticamente el controlador y método definidos por defecto.

Si el controlador o el método especificado en la URL no existen, el framework ejecutará una clase y un método configurados como respuesta de error.  
Por defecto, se usará el controlador `PageController` y el método `s404`.

Este comportamiento se puede personalizar desde el mismo archivo `/for-custom/config.ini`:

```ini
; *************** Caller ***************
;CONTROLLER_SUFFIX = Controller
;S404_CONTROLLER = Page
;S404_METHOD = s404
```

Para aplicar cambios, descomenta las líneas `S404_CONTROLLER` y `S404_METHOD` y asigna los valores que desees.  
La línea `CONTROLLER_SUFFIX` define el sufijo que se agregará automáticamente al nombre de clase (por defecto, `Controller`).

#### Argumentos

Si en la URL se incluyen más segmentos después del nombre del método, estos serán enviados como parámetros al método correspondiente, en el orden en que aparecen.

Por ejemplo, al acceder a la siguiente URL:

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

Cuando se accede a una ruta como `http://<HOST>/NombreClase`, el framework buscará la clase `NombreClaseController` dentro de `/back-end/controller`, y llamará al método correspondiente según el verbo HTTP utilizado.

Por ejemplo, si se hace una solicitud `GET` a `http://<HOST>/Page`, se invocará el método `get()` de la clase `PageController`. Para una solicitud `POST`, se llamará al método `post()`, y así sucesivamente con `put()`, `delete()`, etc.

Si se desea personalizar el nombre de los métodos que responden a cada tipo de petición HTTP, se puede hacer desde el archivo `/for-custom/config.ini`.

Para que esta configuración tenga efecto, es obligatorio descomentar la línea `[METHODS]` junto con las claves correspondientes al verbo HTTP que se desea modificar:

```ini
;[METHODS] ; For this configuration you must uncomment this line and its related keys
;GET = get
;POST = post
;PUT = put
;DELETE = delete
;OPTIONS = options
;PATCH = patch
```

Por ejemplo, si se desea que las peticiones `GET` llamen al método `mostrar()` en lugar de `get()`, se debe descomentar ambas líneas y modificar el valor así:

```ini
[METHODS]
GET = mostrar
```

#### Excepciones en clases

En algunos casos, es posible que se quiera excluir ciertas clases del procesamiento automático del modo REST, por ejemplo, cuando se desea documentar la API u ofrecer respuestas personalizadas desde métodos concretos.

Para ello, se puede usar la opción `CLASS_EXCEPTIONS` dentro del archivo `/for-custom/config.ini`, indicando una lista separada por comas con los nombres de las clases controllers a excluir:

```ini
CLASS_EXCEPTIONS = Page,AnotherPage
```

Básicamente, las clases listadas aquí serán tratadas bajo el esquema del primer modo (llamado directo por URL), ignorando el tipo de petición HTTP.

⚠️ **Nota:** El tratamiento de parámetros adicionales en la URL funciona igual que en el primer modo.  
Por ejemplo, si se accede a `/Page/a/b/c` mediante una petición `GET`, los valores `a`, `b` y `c` serán pasados como argumentos al método `get()`, en ese orden.

⚠️ **Importante:** Al igual que en el primer modo, existe un controlador y un método por defecto ya preestablecidos.  
Estos pueden modificarse (descomentando) desde el archivo `/for-custom/config.ini`, en las líneas `DEFAULT_CLASS` y `DEFAULT_METHOD`.

## Otras Documentaciones

A continuación se listan enlaces hacia documentación adicional sobre configuraciones y módulos específicos del framework:

- [Configuración de REST](docs/rest.md)
- [Configuración de Middlewares](docs/middlewares.md)
- [Módulo Validator](docs/validator.md)
- [Módulo DB](docs/db.md)
- [Módulo Response](docs/response.md)
- [Utilitario Repositorio](docs/repository.md)
- [Otras documentaciones](docs/otros.md)