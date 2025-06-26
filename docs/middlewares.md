# MiddlewareExecutor

`MiddlewareExecutor` es el componente responsable de ejecutar los middlewares registrados en el sistema.  
Dependiendo de si el modo REST está habilitado (`ENABLE_REST`) o si se trata de una clase de excepción (`CLASS-EXCEPTIONS`), este ejecutor decide qué conjunto de middlewares aplicar.

---

## Comportamiento

- Si **REST está deshabilitado** o se trata de una clase excepción (`CLASS-EXCEPTIONS`), se ejecutan los **middlewares clásicos**.
- Si **REST está habilitado**, se ejecutan los **middlewares REST**.
- Si algún middleware retorna `false`, se **interrumpe el flujo** y no se continúa con la ejecución del controlador.

---

## Clase base `Middleware`

Todos los middlewares deben heredar de la clase `core\middleware\Middleware`, la cual define la siguiente estructura mínima:

```php
<?php
namespace core\middleware;

abstract class Middleware
{
	abstract public function execute();
}
```

---

## Middlewares por defecto

Dentro de la carpeta `for-custom/` se incluyen dos middlewares predefinidos que ya se encuentran registrados automáticamente en el sistema:

### `MyMiddlewareClassic.php`

```php
<?php
require_once(PATH_CORE . DIRECTORY_SEPARATOR . 'middleware'. DIRECTORY_SEPARATOR . 'Middleware.php');
// require_once(PATH_ROOT . DIRECTORY_SEPARATOR . 'PatataHelper.php');

use core\middleware\Middleware;

class MyMiddlewareClassic extends Middleware
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
<?php
require_once(PATH_CORE . DIRECTORY_SEPARATOR . 'middleware'. DIRECTORY_SEPARATOR . 'Middleware.php');
// require_once(PATH_ROOT . DIRECTORY_SEPARATOR . 'PatataHelper.php');

use core\middleware\Middleware;

class MyMiddlewareRest extends Middleware
{
	private $uriDecoder;

	public function __construct()
	{
		$this->uriDecoder = Repository::getURIDecoder();
	}

	public function execute()
	{
		if(Repository::getREST()->dataIsDecodable()) {
			return $this->evaluate();
		} else {
			return $this->evaluateSpecialCases();
		}
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

		if($data == 'SKIP-AUTH') {
			// Verifica acceso a recursos concretos
			return true;
		}

		return false;
	}
}
```

---

## Ejemplo de restricción por token especial

Puedes personalizar `MyMiddlewareRest` para controlar el acceso según valores de token específicos, por ejemplo:

```php
private function evaluationBySpecialCases()
{
	$class = $this->getURIDecoder()->getClass();
	$method = $this->getURIDecoder()->getMethod();
	$arguments = $this->getURIDecoder()->getArguments();
	$data = Repository::getREST()->getData();

	if($data == 'usuario-login')
	{
		if($class == 'Usuario' && $method == 'get' && isset($_GET['user']) && isset($_GET['password'])) {
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

	$user = PatataHelper::getCurrentUser(); // Función no definida

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

Para registrar nuevos middlewares, debes sobrescribir el método `getMiddlewareExecutor()` en tu clase `Repository.php` personalizada:

```php
public static function getMiddlewareExecutor()
{
	$middlewareExecutor = parent::getMiddlewareExecutor();

	// Agrega middlewares REST
	$middlewareExecutor->addMiddlewareRest(new AnotherMiddlewareRest());

	// Agrega middlewares clásicos
	$middlewareExecutor->addMiddlewareClassic(new AnotherMiddlewareClassic());

	return $middlewareExecutor;
}
```

Este enfoque garantiza que se conserven los middlewares preexistentes definidos en `PatataRepository`.

Para más detalles sobre la clase `Repository`, consulta la [documentación de Repository](repository.md).