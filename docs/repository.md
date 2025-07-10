# Repository

`Repository` extiende de `PatataRepository`, su función principal es **devolver instancias listas para usar**, ocultando detalles como rutas, inicialización y carga de configuraciones.

> 🧠 `PatataRepository` no debe modificarse.  
> 🛠️ `Repository` sí puede y debe personalizarse cuando se necesite comportamiento adicional.

---

## Métodos disponibles desde `Repository`

Estos son los métodos que `Repository` hereda de `PatataRepository`, listos para ser usados o sobrescritos:

| Método                           | Objeto devuelto                               | Descripción breve                                                    |
|----------------------------------|-----------------------------------------------|------------------------------------------------------------------------|
| `getDB()`                        | `patata\db\DB`                                 | Instancia singleton de base de datos con soporte para configuración. |
| `getValidator()`                 | `patata\validator\Validator`                   | Validador con reglas personalizadas por defecto.                     |
| `getREST($configPath)`          | `core\rest\REST`                               | Objeto REST con sistema de token incluido.                           |
| `getResponse($configPath)`      | `core\response\Response`                       | Instancia singleton para respuestas HTTP.                            |
| `getCaller($path, $configPath)` | `core\caller\Caller`                           | Ejecuta controladores desde rutas configurables.                     |
| `getMiddlewareExecutor()`       | `core\middleware\MiddlewareExecutor`           | Executor con middlewares clásicos y REST precargados.               |
| `getURIDecoder($configPath)`    | `ClassicalURIDecoder` o `RESTURIDecoder`       | Decodificador de URI según si REST está habilitado o no.            |

> ⚠️ Puedes sobrescribir cualquiera de estos métodos en tu clase `Repository` para adaptarlos a tus necesidades.

---

## Entonces, ¿Para qué sirve Repository.php?

- Para sobrescribir métodos como `getDB`, `getValidator`, `getREST`, etc., y lograr configuraciones personalizadas.
- Para definir nuevos métodos que devuelvan tus propias instancias o servicios.
- Permite mantener el código desacoplado y centralizado.

---

## Ejemplo 1: sobrescribir la instancia de `Validator`

Puedes personalizar la instancia generada por `getValidator`, por ejemplo, para incluir nuevas reglas:

```php
<?php
abstract class Repository extends PatataRepository
{
    public static function getValidator()
    {
        $validator = parent::getValidator();
        $validator->addSource(__DIR__ . '/MyOtherRule.php', 'MyOtherRule');
        return $validator;
    }
}
```
---

## Ejemplo 2: crear un `MiddlewareExecutor` sin los middlewares por defecto

Si deseas eliminar los middlewares precargados y usar otros completamente nuevos:

```php
public static function getMiddlewareExecutor()
{
    require_once(PATH_CORE . '/middleware/MiddlewareExecutor.php');
    require_once(__DIR__ . '/AnotherMiddlewareClassic.php');
    require_once(__DIR__ . '/AnotherMiddlewareRest.php');

    $middlewareExecutor = new core\middleware\MiddlewareExecutor();
    $middlewareExecutor->addMiddlewareClassic(new AnotherMiddlewareClassic());
    $middlewareExecutor->addMiddlewareRest(new AnotherMiddlewareRest());

    return $middlewareExecutor;
}
```

> Si prefieres conservar los middlewares originales y agregar nuevos, utiliza `parent::getMiddlewareExecutor()` como base.

---

## Ejemplo de uso

Una vez definidos, puedes seguir utilizando los métodos de `Repository` de manera transparente:

```php
$db = Repository::getDB();
$validator = Repository::getValidator();
```