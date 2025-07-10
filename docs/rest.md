# Configuración de REST

El módulo REST incluye varias configuraciones que permiten personalizar su comportamiento, todas estas opciones se encuentran en el archivo `/for-custom/config.ini`.

---

## 1. Acceso sin autenticación (sin token)

Este conjunto de configuraciones permite que ciertas IPs, rangos o dominios accedan a la API **sin necesidad de enviar un token**.

Es útil en escenarios donde **no se requiere con un mecanismo de autenticación**, como:

- Servicios internos que se comunican por red local.
- Aplicaciones en desarrollo o pruebas.

### Exclusión de autenticación por IP, rango o dominio

```ini
SKIP_AUTH = /0
```

Esta opción define una lista de IPs, rangos de red (CIDR) o **dominios** desde los que se puede acceder **sin token**.

- Se puede ingresar múltiples valores, basta con que estos estén separados con **comas**.
- Puedes usar:
  - IPs individuales (`127.0.0.1`).
  - Rangos (`192.168.0.0/24`, `10.0.0.0/8`).
  - Dominios (`localhost`, `api.midominio.com`).
- El valor especial `/0` permite acceso desde **cualquier IP o dominio**.

> ℹ️ El sufijo `/0` es una máscara CIDR que abarca todas las IPs. También puedes usar `/8`, `/16`, etc., para definir rangos más limitados.

#### Ejemplos:

```ini
SKIP_AUTH = 127.0.0.1
```
Solo `localhost` accede sin token.

```ini
SKIP_AUTH = 192.168.1.0/24,10.0.0.5
```
Permite acceso al rango `192.168.1.0 – 192.168.1.255` y a `10.0.0.5`.

```ini
SKIP_AUTH = 127.0.0.1,miapp.local,api.ejemplo.com
```
Acceso sin token desde IP local, dominio personalizado y dominio externo.

```ini
SKIP_AUTH = /0
```
Permite acceso libre desde cualquier origen.

```ini
SKIP_AUTH =
```
No se permite acceso sin token, se requiere autenticación en todos los casos.

---

## 2. Acceso autenticado (con token no decodificable)

En este caso, los tokens son simples **strings** enviados en una cabecera HTTP. No contienen información encriptada (como un JWT), pero permiten controlar el acceso según reglas definidas.

> **Nota:** Por defecto, el token esperado debe enviarse en la cabecera llamada `patata-authorization`.



Este enfoque es ideal para:

- Apps con inicio de sesión que todavía **no emiten tokens firmados**.
- Casos donde el backend define una lista fija de tokens válidos.
- Entornos donde se desea una autenticación simple y directa.

### Tokens especiales con permisos controlados

```ini
;[SPECIAL_TOKENS]  ; Descomenta esta línea y las claves que definas
;usuario-login = 0.0.0.0/0
```

Permite definir **tokens específicos** que serán válidos **solo si provienen de ciertas IPs, rangos o dominios**.

- Cada clave representa un token literal (ej. `usuario-login`).
- Su valor define desde qué IPs se aceptará ese token.
- Se debe descomentar la sección `[SPECIAL_TOKENS]` y cada clave a usar.

#### Ejemplo:

```ini
[SPECIAL_TOKENS]
usuario-login = 0.0.0.0/0
```

Con esto, cualquier cliente que envíe el token `usuario-login` podrá acceder, sin importar su IP.

> 🧠 Este sistema es útil para el login de apps, los cuales aún no contendrán un token codificado.

Para implementar lógica personalizada según el token recibido, se recomienda que estos tokens se usen junto con **middlewares del framework**.

> 📘 Para más detalles, consulta la [documentación de Middlewares](middlewares.md).

---

## 3. Autenticación con tokens decodificables

Además de tokens simples, el sistema permite trabajar con **tokens que contienen información serializada** (como los JSON Web Tokens), este tipo de tokens permite transportar datos como el ID del usuario, su rol o su fecha de expiración.

### Cómo generar un token con datos

Para generar un token, se puede utilizar el siguiente método: `REST::getInstance()->getToken()->encode()` (el cual usará internamente JWT), a continuación se muestra un ejemplo de su uso:

```php
$token = Repository::getREST()->encode([
    'id' => 5,
    'rol' => 'admin',
    'exp' => time() + 3600
]);
```

Este token generado puede ser enviado por la aplicación cliente mediante una cabecera HTTP (por defecto `patata-authorization`):

```
patata-authorization: <aquí_el_token_generado>
```

---

### Cómo acceder a los datos del token recibido

Cuando se recibe una petición con un token válido y decodificable, el framework lo decodifica automáticamente y esté se encontrará disponible mediante:

```php
$data = $rest->getData(); // Contiene el payload original
```

Debido a que también se puede enviar tokens con valores de strings (no decodificables), habitualmente es necesario verificar si dicho token puede ser decodificado, para ello se puede usar:

```php
$rest->dataIsDecodable(); // true si el token fue decodificado correctamente
```

Cuando se trata de tokens no decodificables, el valor de `getData()`, será SKIP_AUTH en caso esté activado o el valor del token (en caso de que se trate de un SPECIAL_TOKEN), lo cual sólo ocurrirá, siempre y cuando el IP del cliente se encuentre autorizado en la configuración establecida.

---

### Personalización: reemplazar el sistema JWT por uno propio

El sistema de autenticación por defecto utiliza `PatataJWT`, una implementación interna básica que genera y verifica tokens JWT con el algoritmo **HS256**, esta versión **no maneja expiraciones** ni otros claims estándar.

Si deseas utilizar una librería externa (como `firebase/php-jwt`) o implementar tu propio sistema, puedes hacerlo creando una clase personalizada que implemente la interfaz `IToken`.

#### Ejemplo usando la librería `firebase/php-jwt`

Primero, asegúrate de instalar la librería ejecutando en la raíz del proyecto:

```bash
composer require firebase/php-jwt
```

Luego, puedes usar el siguiente ejemplo ubicado en `for-custom/MyJWT.php`:

```php
<?php
require_once(PATH_CORE . '/rest/token/IToken.php');

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class MyJWT implements \core\rest\token\IToken
{
    private $config;
    private $key;
    private $alg;

    public function __construct($config_path = NULL)
    {
        $this->config = parse_ini_file($config_path);

        assert(is_string($this->config['KEY']), 'In MyJWT, KEY is invalid');
        assert(is_string($this->config['ALG']), 'In MyJWT, ALG is invalid');

        $this->key = $this->config['KEY'];
        $this->alg = $this->config['ALG'];
    }

    public function encode($payload)
    {
        return JWT::encode($payload, $this->key, $this->alg);
    }

    public function decode($token)
    {
        return (array) JWT::decode($token, new Key($this->key, $this->alg));
    }
}
```

Para activar esta clase personalizada, **descomenta el siguiente método en `Repository.php`**:

```php
public static function getREST($extra_configuration_path = CUSTOM_CONFIG_PATH)
{
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'MyJWT.php');
    $rest = parent::getREST($extra_configuration_path);
    $rest->setToken(new MyJWT($extra_configuration_path));
    return $rest;
}
```

Esto reemplazará completamente el sistema de codificación/decodificación de tokens por tu propia implementación.

---

### Cambiar el nombre de la cabecera del token

Es posible cambiar el nombre de la cabecera HTTP en la que se debe enviar el token de autenticación.

Para hacerlo, edita el archivo `/for-custom/config.ini` y modifica (o define) el valor de la siguiente línea:

```ini
AUTH_TOKEN_HEADER_NAME = patata-authorization
```
