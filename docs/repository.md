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
        $validator->addSource(__DIR__ . '/for-custom/MyOtherRule.php', 'MyOtherRule');
        return $validator;
    }
}
```

---

## Ejemplo 2: usar una configuración distinta en la base de datos

También puedes personalizar el acceso a la base de datos, por ejemplo, usando otro archivo `.ini` de configuración:

```php
<?php
abstract class Repository extends PatataRepository
{
    public static function getDB()
    {
        return parent::getDB(__DIR__ . '/for-custom/db-config.ini');
    }
}
```

Esto permite tener múltiples entornos o conexiones alternativas sin modificar el núcleo del framework.

---

## Ejemplo de uso

Una vez personalizados los métodos, se siguen utilizando de forma transparente:

```php
$db = Repository::getDB();
$validator = Repository::getValidator();
```
