# Validador de Inputs (`patata\validator`)

Este módulo permite validar datos de entrada de forma sencilla, además permite agregar fácilmente nuevas reglas de validación, dichas reglas deben definirse como **métodos estáticos** dentro de clases (`Rule`, u otras que se pueden agregar), y los mensajes de error se generan automáticamente según el nombre del método.

A continuación, se muestra un fragmento simplificado de la clase base `Rule`, que contiene las validaciones más comunes y sus respectivos mensajes de error predeterminados:

```php
class Rule
{
    public static function hasContent($value) {
        return !(is_null($value) || $value === "" || (is_array($value) && count($value) === 0));
    }

    public static function minLengthIs($value, $min) {
        return is_string($value) && mb_strlen($value) >= $min;
    }

    protected $_messages = [
        'default'           => 'No es válido',
        'hasContent'        => 'No puede estar vacío',
        'minLengthIs'       => 'No cumple con la cantidad mínima de caracteres'
        // ...
    ];
}
```

---

## Uso básico

### 1. Instanciar el validador

```php
use patata\validator\Validator;

$validator = new Validator();
```

---

### 2. Agregar inputs a validar

```php
// addInput($nombreCampo, $valor, $esOpcional = false)
$validator->addInput("usuario", "Juan")
          ->addRule("hasContent")       // Se le agregará una nueva regla 
          ->addRule("minLengthIs", 3);  // Es posible concatenarle más reglas
```

Puedes marcar un campo como **opcional** pasando `true` como tercer parámetro:

```php
// addInput($nombreCampo, $valor, $esOpcional)
$validator->addInput("direccion", NULL, true);
```

> 🟡 Cuando un campo es **opcional**, **las reglas solo se aplican si el valor ingresado no está vacío**.

También puedes extraer valores desde arrays u objetos:

```php
// addInputFromArray($nombreCampo, $arrayOrigen, $clave)
$validator->addInputFromArray("usuario", $_POST, "nombre");

// addInputFromObject($nombreCampo, $objetoOrigen, $propiedad)
$validator->addInputFromObject("correo", $data, "email");
```

---

### 3. Verificar errores

```php
if ($validator->hasErrors()) {
    $errores = $validator->getInputsWithErrors();
    // manejar errores...
}
```

El resultado es una lista con cada campo y sus mensajes de error:

```php
[
  ['name' => 'usuario', 'messages' => ['No puede estar vacío', 'Debe tener al menos 3 caracteres']],
  ['name' => 'edad', 'messages' => ['Debe ser un valor positivo']]
]
```

---

### Validación con mensajes personalizados

Puedes sobrescribir el mensaje de error que aparece para una regla específica usando la forma:

```php
["nombreDeLaRegla", "mensaje personalizado"]
```

Por ejemplo:

```php
$validator->addInput("nombre", "")
          ->addRule(["hasContent", "El nombre es obligatorio"]);
```

Este campo fallará si está vacío, y mostrará el mensaje `"El nombre es obligatorio"` en lugar del mensaje genérico.

---

## Reglas disponibles

| Regla                         | Parámetros adicionales         | Descripción                                        |
|------------------------------|--------------------------------|----------------------------------------------------|
| `hasContent`                 | —                              | No puede estar vacío                               |
| `minLengthIs`                | `int $size`                    | Longitud mínima permitida                          |
| `maxLengthIs`                | `int $size`                    | Longitud máxima permitida                          |
| `isWord`                     | —                              | Solo se permiten letras                            |
| `isWords`                    | —                              | Letras y espacios                                  |
| `isAlphanumeric`             | —                              | Caracteres alfanuméricos                           |
| `isAlphanumericAndSpaces`    | —                              | Caracteres alfanuméricos y espacios                |
| `isEmail`                    | —                              | Email válido                                       |
| `isUrl`                      | —                              | URL válida                                         |
| `isInt`                      | —                              | Número entero                                      |
| `isFloat`                    | —                              | Número flotante válido                             |
| `isPositive`                 | —                              | Valor mayor que cero                               |
| `isPositiveOrZero`           | —                              | Valor mayor o igual a cero                         |
| `isBetween`                  | `float $min`, `float $max`     | Valor dentro del rango permitido                   |
| `isDate`                     | —                              | Fecha con formato `Y-m-d`                          |
| `isDateTime`                 | —                              | Fecha y hora con formato `Y-m-d H:i:s`             |
| `isTimestamp`                | —                              | Timestamp válido                                   |
| `isArray`                    | —                              | Es un arreglo                                      |
| `hasElements`                | —                              | Arreglo con al menos un valor                      |
| `hasUniqueValues`            | —                              | Arreglo sin elementos duplicados                   |
| `isIn`                       | `array $valores`               | Valor incluido en las opciones permitidas          |
| `isDifferentTo`              | `mixed $otroValor`             | Valor distinto al especificado                     |
| `isStdClass`                 | —                              | Es un objeto `stdClass`                            |
| `isNotNull`                  | —                              | No debe ser `NULL`                                 |
| `isBoolean`                  | —                              | Valor booleano (`true`, `false`, `'true'`, `'false'`) |
| `isInputText`                | —                              | Texto o número (útil como base para otras reglas)  |
| `isRegex`                    | `string $pattern`              | Coincide con una expresión regular                 |
| `isDNI`                      | —                              | Cadena de 8 dígitos                                |
| `isRUC`                      | —                              | Cadena de 11 dígitos                               |
| `isUnique`                   | —                              | El valor no debe estar registrado (personalizable) |

> 💡 Algunas reglas como `isRegex`, `isInputText` e `isUnique` están pensadas para ser usadas como base o personalizadas según tus necesidades.

---

## Reglas personalizadas

```php
$validator->addInput("x", 5)
          ->addCustomRule(5 > 10, "El valor debe ser mayor a 10");
```

---

## Ejemplo de validación real

```php
$validator = Repository::getValidator();

$validator->addInputFromObject('Razón Social', $data, 'razonSocial')
          ->addRule('minLengthIs', 2)
          ->addRule('maxLengthIs', 128);

$validator->addInputFromObject('Ruc', $data, 'ruc')
          ->addRule('isRUC');

$validator->addInputFromObject('Email', $data, 'email', true)
          ->addRule('isEmail')
          ->addRule('minLengthIs', 2)
          ->addRule('maxLengthIs', 64);

$validator->addInputFromObject('Dirección', $data, 'direccion')
          ->addRule('minLengthIs', 2)
          ->addRule('maxLengthIs', 128);

$validator->addInputFromObject('Telefono', $data, 'telefono', true)
          ->addRule('minLengthIs', 2)
          ->addRule('maxLengthIs', 32);

if ($validator->hasErrors()) {
    print_r($validator->getInputsWithErrors());
}
```

---

## 🧩 Extender el sistema de validación

El framework ya tiene agregado la siguiente clase:

```php
for-custom/MyRule.php
```

Ahí puedes agregar tus propias reglas personalizadas.

### ➕ Crear una nueva regla

```php
// for-custom/MyRule.php
class MyRule
{
    public static function startsWith($value, $prefix)
    {
        return is_string($value) && str_starts_with($value, $prefix);
    }

    public function getMessages()
    {
        return [
            'startsWith' => 'Debe comenzar con el prefijo indicado',
        ];
    }
}
```

- Define métodos estáticos que devuelvan `true` o `false`.
- `getMessages()` retorna los textos de error para cada método.

---

### ✅ Usar la nueva regla

```php
$validator->addInput('código', 'ABC123')
          ->addRule('startsWith', 'ABC');
```

---

### 🧩 Registrar clases adicionales

Si necesitas definir reglas en otras clases, puedes registrarlas así (en donde el primer parámetro es la ubicación de la clase y el segundo, el nombre de la clase):

```php
$validator->addSource(__DIR__ . DIRECTORY_SEPARATOR . 'MisOtrasReglas.php', 'MisOtrasReglas');
```

Estas clases también deben tener métodos estáticos y el método opcional `getMessages()` (pues en caso que una regla no cuenta con un mensaje definido se mostrará un mensaje por defecto).