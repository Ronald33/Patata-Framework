# Configuración de REST

El módulo REST incluye varias configuraciones que permiten personalizar su comportamiento. Todas las opciones se encuentran en el archivo `/for-custom/config.ini`.

---

## 1. Acceso sin autenticación (sin token)

Este conjunto de configuraciones permite que ciertas IPs, rangos o dominios accedan a la API **sin necesidad de enviar un token**.

Es útil en escenarios donde **no se cuenta con un mecanismo de autenticación**, como:

- Servicios internos que se comunican por red local.
- Aplicaciones en desarrollo o pruebas.

### Exclusión de autenticación por IP, rango o dominio

```ini
SKIP_AUTH = /0
```

Esta opción define una lista de IPs, rangos de red (CIDR) o **dominios** desde los que se puede acceder **sin token**.

- Separar los valores con **comas**.
- Puedes usar:
  - IPs individuales (`127.0.0.1`)
  - Rangos CIDR (`192.168.0.0/24`, `10.0.0.0/8`)
  - Dominios (`localhost`, `api.midominio.com`)
- El valor especial `/0` permite acceso desde **cualquier IP o dominio** (solo recomendable en desarrollo).

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
Permite acceso libre desde cualquier origen. **No usar en producción.**

```ini
SKIP_AUTH =
```
No se permite acceso sin token. Se requiere autenticación en todos los casos.

---

## 2. Acceso autenticado (con token)

Cuando las aplicaciones **sí manejan tokens** (por ejemplo, apps móviles o frontends con login), se pueden aplicar configuraciones más detalladas.

> **Nota:** Por defecto, el token esperado debe enviarse en la cabecera llamada `patata-authorization`.

Los tokens aquí son simples **strings** enviados en una cabecera HTTP. No contienen información encriptada (como un JWT), pero permiten controlar el acceso según reglas definidas.

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

> 🧠 Este sistema es útil para apps de login o integraciones simples que aún no generan tokens complejos, pero necesitan autenticarse con un valor estático.

Se recomienda que estos tokens se usen junto con **middlewares del framework** para:

- Restringir su uso a ciertos métodos o rutas.
- Implementar lógica personalizada según el token recibido.

> 📘 Para más detalles, consulta la [documentación de Middlewares](middlewares.md).

---

## 3. Autenticación con tokens decodificables (ej. JWT)

Además de tokens simples, el sistema permite trabajar con **tokens que contienen información serializada**, como los JSON Web Tokens (JWT).  
Este tipo de tokens permite transportar datos como el ID del usuario, su rol o su fecha de expiración dentro del propio token, sin necesidad de almacenar sesión en el servidor.

### Cómo generar un token con datos

La clase utilizada por defecto implementa JWT, por lo que puedes crear un token que incluya cualquier estructura de datos (por ejemplo, un arreglo asociativo):

```php
$rest = REST::getInstance();
$token = $rest->getToken()->encode([
    'id' => 5,
    'rol' => 'admin',
    'exp' => time() + 3600
]);
```

Este token generado puede ser enviado en la cabecera HTTP definida (por defecto `patata-authorization`) en cada petición:

```
patata-authorization: <aquí_el_token_generado>
```

---

### Cómo acceder a los datos del token recibido

Cuando se recibe una petición con un token válido y decodificable, el framework lo decodifica automáticamente.  
Los datos contenidos estarán disponibles usando:

```php
$data = $rest->getData(); // Contiene el payload original
```

Y si deseas comprobar si el token fue efectivamente decodificado:

```php
$rest->dataIsDecodable(); // true si el token fue decodificado correctamente
```

Esto permite trabajar directamente con los datos embebidos en el token, sin necesidad de consultar una base de datos o almacenar sesión.

---

### Personalización: reemplazar el sistema JWT

Si deseas utilizar un sistema distinto a JWT (por ejemplo, tokens firmados con otra librería, cifrados, o con estructura propia), puedes crear tu propia clase implementando la interfaz `IToken`.

#### Ejemplo: clase personalizada con `base64_encode`

```php
use core\rest\token\IToken;

class MiTokenPersonalizado implements IToken
{
    public function encode($payload)
    {
        return ...
    }

    public function decode($token)
    {
        return ...;
    }
}
```

Luego, para usar tu clase personalizada:

```php
$rest = REST::getInstance();
$rest->setToken(new MiTokenPersonalizado());
```

Esto reemplaza completamente el sistema de codificación/decodificación por el tuyo propio.

---

### Cambiar el nombre de la cabecera del token

Es posible cambiar el nombre de la cabecera HTTP en la que se debe enviar el token de autenticación.

Para hacerlo, edita el archivo `/for-custom/config.ini` y modifica (o define) el valor de la siguiente línea:

```ini
AUTH_TOKEN_HEADER_NAME = patata-authorization
```
