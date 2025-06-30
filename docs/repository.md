# Repository

`Repository` extiende de `PatataRepository` su función principal es **devolver instancias listas para usar**, ocultando detalles como rutas, inicialización y carga de configuraciones, al igual que `PatataRepository`, pero brindando además la posibilidad de **modificar o reemplazar esas instancias predefinidas**.

> 🧠 `PatataRepository` no debe modificarse, pero `Repository` sí puede y debe editarse cuando se necesite personalización.

---

## ¿Para qué sirve?

- Para sobrescribir métodos como `getDB`, `getValidator`, `getREST`, etc., agregando comportamiento adicional.
- Para definir nuevos métodos que devuelvan tus propias instancias o servicios personalizados.
- Para mantener el código desacoplado y centralizado.

---

## Ejemplo 1: reemplazar la instancia de `Validator`

Supongamos que deseas modificar la instancia generada por `getValidator` para incluir reglas personalizadas.  
Puedes hacerlo sobrescribiendo el método y utilizando `parent::getValidator()` como base:

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

## Ejemplo 2: asignar un nuevo codificador y decodificador a REST

Para asignar un nuevo codificador y decodificador de tokens en REST, se pueede hacer de la siguiente forma:

```php
public static function getREST($extra_configuration_path = CUSTOM_CONFIG_PATH)
{
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'MiTokenPersonalizado.php');
    $rest = parent::getREST($extra_configuration_path);
    $rest->setToken(new MiTokenPersonalizado());
    return $rest;
}
```

---

## Ejemplo 3: asignar nuevos middlewares (excluyendo los pre-establecidos)

En este ejemplo se asigna nuevos middlewares al framework.

```php
public static function getMiddlewareExecutor()
{
    require_once(PATH_CORE . '/middleware/MiddlewareExecutor.php');
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'AnotherMiddlewareClassic.php');
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'AnotherMiddlewareRest.php');

    $middlewareExecutor = new core\middleware\MiddlewareExecutor();
    $middlewareExecutor->addMiddlewareClassic(new AnotherMiddlewareClassic());
    $middlewareExecutor->addMiddlewareRest(new AnotherMiddlewareRest());

    return $middlewareExecutor;
}
```

En el caso mostrado se crear una nueva instancia de `MiddlewareExecutor` y de esta forma logramos que el framework no tenga los middlewares asignados por defecto, en caso que se quiera agregar nuevos middlewares manteniendo los que fueron nativamente agregados, bsataría con obtener la instancia con `parent::getMiddlewareExecutor()`.

---

## Ejemplo de uso

Una vez personalizados los métodos, se siguen utilizando de forma transparente:

```php
$db = Repository::getDB();
$validator = Repository::getValidator();
```