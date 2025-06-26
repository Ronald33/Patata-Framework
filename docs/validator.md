# Validador de Inputs (`patata\validator`)

Este módulo permite validar datos de entrada de forma flexible y extensible.  
Los validadores se definen como **métodos estáticos** dentro de clases (`Rule`, u otras que se pueden agregar), y los mensajes de error se generan automáticamente según las reglas aplicadas.

> ✅ Este sistema puede ser usado en cualquier parte del back-end, especialmente útil para validar `payloads` de peticiones REST o formularios clásicos.

---

## Uso básico

### 1. Instanciar el validador

```php
use patata\validator\Validator;

$validator = new Validator();
```

> Nota: la clase `Rule` se agrega automáticamente como fuente principal de validaciones.

---

### 2. Agregar inputs a validar

```php
// addInput($nombreCampo, $valor, $esOpcional = false, $aliasInterno = null)
$validator->addInput("usuario", "Juan") // campo requerido por defecto
          ->addRule("hasContent")       // nombre de la regla
          ->addRule("minLengthIs", 3);  // nombre de la regla + parámetro: mínimo 3 caracteres
```

Puedes marcar un campo como **opcional** pasando `true` como tercer parámetro:

```php
// addInput($nombreCampo, $valor, $esOpcional)
$validator->addInput("direccion", NULL, true);
```

> 🟡 Cuando un campo es **opcional**, **las reglas solo se aplican si el valor ingresado no está vacío**.  
> Si el valor es `NULL`, una cadena vacía (`""`) o un arreglo vacío (`[]`), **se omite toda validación** para ese campo.  
> Esto permite aceptar campos vacíos sin lanzar errores, pero validar correctamente cuando se proveen datos.

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
if($validator->hasErrors()) {
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

## Reglas disponibles

Las reglas se ejecutan desde métodos estáticos definidos en la clase `Rule`.  
Todas reciben como **primer parámetro el valor a validar**, seguido de los parámetros adicionales que correspondan.

| Regla                         | Parámetros adicionales                             | Descripción                                       |
|------------------------------|----------------------------------------------------|---------------------------------------------------|
| `hasContent`                 | —                                                  | Verifica que no esté vacío                        |
| `minLengthIs`                | `int $size`                                        | Longitud mínima (caracteres)                      |
| `maxLengthIs`                | `int $size`                                        | Longitud máxima (caracteres)                      |
| `isWord`                     | —                                                  | Solo letras                                       |
| `isWords`                    | —                                                  | Letras y espacios                                 |
| `isAlphanumeric`            | —                                                  | Letras y números                                  |
| `isAlphanumericAndSpaces`   | —                                                  | Letras, números y espacios                        |
| `isEmail`                    | —                                                  | Email válido                                      |
| `isUrl`                      | —                                                  | URL válida                                        |
| `isInt`                      | —                                                  | Número entero                                     |
| `isFloat`                    | —                                                  | Número decimal                                    |
| `isPositive`                 | —                                                  | Mayor a 0                                         |
| `isPositiveOrZero`          | —                                                  | Mayor o igual a 0                                 |
| `isBetween`                  | `float $min`, `float $max`                         | Rango numérico                                    |
| `isDate`                     | —                                                  | Formato `Y-m-d`                                   |
| `isDateTime`                 | —                                                  | Formato `Y-m-d H:i:s`                             |
| `isTimestamp`               | —                                                  | Timestamp válido                                  |
| `isArray`                    | —                                                  | Es un arreglo                                     |
| `hasElements`               | —                                                  | Arreglo con al menos un valor                    |
| `hasUniqueValues`           | —                                                  | Arreglo sin valores repetidos                    |
| `isIn`                       | `array $valores`                                   | Valor dentro del arreglo dado                    |
| `isDifferentTo`              | `mixed $otroValor`                                 | El valor no debe ser igual al dado               |
| `isStdClass`                | —                                                  | Es un objeto `stdClass`                          |
| `isNotNull`                 | —                                                  | No debe ser NULL                                  |

---

## Reglas personalizadas

Puedes definir una regla directamente con una condición booleana usando `addCustomRule()`:

```php
// addCustomRule($condicion, $mensajeError)
$validator->addInput("x", 5)
          ->addCustomRule(5 > 10, "El valor debe ser mayor a 10");
```

---

## Ejemplo completo

```php
$validator = new Validator();

$validator->addInput("correo", "prueba@correo.com")
          ->addRule("hasContent")
          ->addRule("isEmail");

$validator->addInput("edad", 20)
          ->addRule("isInt")
          ->addRule("isPositive");

if ($validator->hasErrors()) {
    print_r($validator->getInputsWithErrors());
}
```

---

## Validación con mensajes personalizados

Puedes personalizar el mensaje de error pasando un array con el nombre de la regla y el mensaje:

```php
// addRule([$nombreRegla, $mensajeError], $...parametros)
$validator->addInput("nombre", "")
          ->addRule(["hasContent", "El nombre es obligatorio"]);
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

$validator->addInputFromObject('Email', $data, 'email', true) // campo opcional
          ->addRule('isEmail')
          ->addRule('minLengthIs', 2)
          ->addRule('maxLengthIs', 64);

$validator->addInputFromObject('Dirección', $data, 'direccion')
          ->addRule('minLengthIs', 2)
          ->addRule('maxLengthIs', 128);

$validator->addInputFromObject('Telefono', $data, 'telefono', true)
          ->addRule('minLengthIs', 2)
          ->addRule('maxLengthIs', 32);

// extraer manualmente el ID del objeto distrito
$dist_id = (isset($data->distrito) && isset($data->distrito->id)) ? $data->distrito->id : NULL;

$validator->addInput('Distrito', $dist_id)
          ->addRule(['rowExists', 'Ingrese un distrito válido'], 'distritos', 'dist_id');
```

---

## Extender el sistema de validación

El framework ya incorpora automáticamente una fuente de validaciones (`Rule.php`) al instanciar el validador.  
Sin embargo, el programador puede definir sus **propias reglas personalizadas** mediante una clase ubicada en:

```
for-custom/MyRule.php
```

### Crear una nueva regla

Debes crear un método `static` con el siguiente formato:

```php
public static function nombreRegla($value, ...$otrosParametros)
{
    // lógica de validación
}
```

Por ejemplo:

```php
public static function rowExists($value, $table, $field)
{
    $extrasDao = new \ExtrasDAO();
    return $extrasDao->rowExists($value, $table, $field);
}
```

Y se usa así:

```php
$validator->addInput("Distrito", 3025)
          ->addRule(["rowExists", "Ingrese un distrito válido"], 'distritos', 'dist_id');
```

Esto ejecutará internamente:

```php
MyRule::rowExists(3025, 'distritos', 'dist_id');
```

> ⚠️ El primer parámetro **siempre debe ser el valor a validar**. Los parámetros adicionales se pasan desde `addRule`.

---

### Definir los mensajes de error

Para que tus reglas personalizadas tengan mensajes de error automáticos, define un método `getMessages()`:

```php
public function getMessages()
{
    return [
        'rowExists' => 'El valor ingresado no es válido',
        // 'miRegla' => 'Mensaje de error...'
    ];
}
```

---

## Agregar más fuentes de validación

Puedes registrar otras clases como fuente de validaciones personalizadas:

```php
// addSource($rutaArchivo, $namespaceClase)
$validator->addSource('for-custom/MiOtraRegla.php', 'for\custom\MiOtraRegla');
```

Esto te permite separar validaciones por dominio (ej. `ProductoRules`, `PersonaRules`, etc.).