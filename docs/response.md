# Respuestas HTTP (`core\response\Response`)

La clase `Response` permite enviar respuestas HTTP de forma uniforme en todo el framework, ya sea en **texto plano** o **formato JSON**.

Está pensada especialmente para APIs REST y se suele usar en los controladores.

---

## Instanciación

Se recomienda obtener la instancia mediante la clase `Repository`:

```php
$this->view = Repository::getResponse();
```

---

## Métodos principales

### Texto plano

```php
$this->view->respondWithText(404, 'Recurso no encontrado');
```

Envía una respuesta con `Content-Type: text/plain` y el código HTTP especificado.

---

### JSON

```php
$this->view->respondWithJSON(200, ['mensaje' => 'Éxito']);
```

Parámetros:

- `$code`: Código HTTP a enviar (ej. 200, 404).
- `$message`: Contenido de la respuesta, usualmente un array o string.
- `$apply_numeric_check`: Si es `true`, aplica `JSON_NUMERIC_CHECK` (convierte valores numéricos correctamente).

---

## Métodos abreviados por código HTTP

| Método     | Código | Descripción                                           |
|------------|--------|-------------------------------------------------------|
| `j200()`   | 200    | OK – Éxito estándar con respuesta JSON                |
| `j201()`   | 201    | Created – Recurso creado correctamente                |
| `j204()`   | 204    | No Content – Petición exitosa, sin contenido devuelto |
| `j400()`   | 400    | Bad Request – Datos inválidos                         |
| `j401()`   | 401    | Unauthorized – No autorizado (ej. token inválido)     |
| `j403()`   | 403    | Forbidden – Acceso denegado                           |
| `j404()`   | 404    | Not Found – Recurso no encontrado                     |
| `j409()`   | 409    | Conflict – Conflicto o duplicación de datos           |
| `j423()`   | 423    | Locked – Recurso bloqueado                            |
| `j500()`   | 500    | Internal Server Error – Error inesperado              |
| `j501()`   | 501    | Not Implemented – Funcionalidad aún no disponible     |

Todos los métodos finalizan el script con `die(...)`.

---

## Ejemplo completo

```php
public function __construct()
{
    $this->view = Repository::getResponse();
}

public function login()
{
    $payload = Helper::getPayload();
    $user = UsuarioDAO::findByCredentials($payload);

    if (!$user) {
        $this->view->j401('Credenciales incorrectas');
    }

    $data = Helper::getResponseLoginSuccessful($user);
    $this->view->j200($data);
}
```

---

## Configuración

La clase utiliza `config.ini` en la carpeta `/core/response/`, y puede ser extendida desde `for-custom/config.ini`.

Ejemplo:

```ini
RESPONSE_CHARSET = utf-8
```

---

## Notas adicionales

- Es una clase **singleton**, se obtiene usando `getInstance()`.
- Permite mantener centralizada la lógica de respuestas en JSON o texto.
- Se integra fácilmente con controladores REST del framework.