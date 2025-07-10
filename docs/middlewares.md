# MiddlewareExecutor

`MiddlewareExecutor` es el componente responsable de ejecutar los middlewares registrados en el sistema.

---

## Comportamiento

- Si **REST está deshabilitado** o se trata de una clase excepción (`CLASS-EXCEPTIONS`), se ejecutan los **middlewares clásicos**.
- Si **REST está habilitado**, se ejecutan los **middlewares REST**.
- Si algún middleware retorna `false`, se **interrumpe el flujo** y no se continúa con la ejecución del controlador.

---

## Clase base `Middleware`

Todos los middlewares deben implementar la interface `core\middleware\Middleware`.

```php
namespace core\middleware;

interface Middleware
{
    public function execute();
}
```

---

## Middlewares por defecto

Dentro de la carpeta `for-custom/` se incluyen dos middlewares predefinidos que ya se encuentran registrados automáticamente en el framework:

### `MyMiddlewareClassic.php`

```php
require_once(PATH_CORE . DIRECTORY_SEPARATOR . 'middleware'. DIRECTORY_SEPARATOR . 'Middleware.php');

class MyMiddlewareClassic implements core\middleware\Middleware
{
    private $uriDecoder;

    public function __construct()
    {
        $this->uriDecoder = Repository::getURIDecoder();
    }

    public function execute()
    {
        $class = $this->uriDecoder->getClass();
        $method = $this->uriDecoder->getMethod();
        $arguments = $this->uriDecoder->getArguments();

        return true;
    }
}
```

---

### `MyMiddlewareRest.php`

```php
require_once(PATH_CORE . DIRECTORY_SEPARATOR . 'middleware'. DIRECTORY_SEPARATOR . 'Middleware.php');

class MyMiddlewareRest implements core\middleware\Middleware
{
    private $uriDecoder;

    public function __construct()
    {
        $this->uriDecoder = Repository::getURIDecoder();
    }

    public function execute()
    {
        if(Repository::getREST()->dataIsDecodable()) { return $this->evaluate(); }
        else { return $this->evaluateSpecialCases(); }
    }

    private function evaluate()
    {
        $class = $this->uriDecoder->getClass();
        $method = $this->uriDecoder->getMethod();
        $arguments = $this->uriDecoder->getArguments();

        return true;
    }

    private function evaluateSpecialCases()
    {
        $class = $this->uriDecoder->getClass();
        $method = $this->uriDecoder->getMethod();
        $arguments = $this->uriDecoder->getArguments();
        $data = Repository::getREST()->getData();

        if($data == 'CLASS_EXCEPTIONS') { return true; }

        if($data == 'SKIP_AUTH')
        {
            // Verify access to specific resources
            return true;
        }

        return false;
    }
}
```

---

## Ejemplo de restricción de un token especial

Puedes personalizar `MyMiddlewareRest` para controlar el acceso según valores del token, por ejemplo:

```php
private function evaluationBySpecialCases()
{
	$class = $this->getURIDecoder()->getClass();
	$method = $this->getURIDecoder()->getMethod();
	$arguments = $this->getURIDecoder()->getArguments();
	$data = Repository::getREST()->getData();

	if($data == 'usuario-login')
	{
		if($class == 'Usuario' && $method == 'get' && isset($_GET['user']) && isset($_GET['password']))
        {
			return true;
		}
	}

	return false;
}
```

Este ejemplo permite el acceso únicamente si:

- El token es `usuario-login`.
- El controlador es `Usuario`.
- El método solicitado es `get`.
- La petición contiene los parámetros `user` y `password` por `GET`.

---

## Ejemplo de autorización basada en tipo de usuario

Otro caso común es aplicar restricciones según el tipo de usuario que se autenticó:

```php
private function evaluateWithToken()
{
	$class = $this->getURIDecoder()->getClass();
	$method = $this->getURIDecoder()->getMethod();
	$arguments = $this->getURIDecoder()->getArguments();
	$payload = PatataHelper::getPayload();

	$user = PatataHelper::getCurrentUser(); // Función ficticia!!!

	if($user instanceof Administrador)
	{
		if($class == 'Movimiento')
		{
			if($method == 'post') { return false; }
		}

		return true;
	}
	else if($user instanceof Operador)
	{
		if($class == 'Empresa')
		{
			if(in_array($method, ['get', 'post', 'put'])) { return true; }
		}
	}

	return false;
}
```

Este fragmento controla el acceso en función del rol:

- Los administradores tienen acceso completo **excepto** para crear movimientos.
- Los operadores solo pueden acceder al controlador `Empresa` y ejecutar `get`, `post` y `put`.

---

## Registro de nuevos middlewares

Para registrar nuevos middlewares, debes sobrescribir el método `getMiddlewareExecutor()` en la clase `Repository.php`:

```php
public static function getMiddlewareExecutor()
{
	require_once(__DIR__ . DIRECTORY_SEPARATOR . 'AnotherMiddlewareClassic.php');
	require_once(__DIR__ . DIRECTORY_SEPARATOR . 'AnotherMiddlewareRest.php');

	$middlewareExecutor = parent::getMiddlewareExecutor();
	$middlewareExecutor->addMiddlewareClassic(new AnotherMiddlewareClassic());
	$middlewareExecutor->addMiddlewareRest(new AnotherMiddlewareRest());

	return $middlewareExecutor;
}
```

Este enfoque garantiza que se conserven los middlewares preexistentes definidos en `PatataRepository`.

Para más detalles sobre la clase `Repository`, consulta la [documentación de Repository](repository.md).