# DB (`patata\db\DB`)

Esta clase proporciona una capa sencilla y segura sobre PDO, permitiendo ejecutar operaciones de base de datos con una sintaxis clara, buen manejo de errores y configuración externa.  
Está diseñada como **singleton**, y su objetivo es **mantener toda la lógica lo más simple posible**.

---

## 🧩 Instanciación

```php
use patata\db\DB;

$db = DB::getInstance(); // Usa la configuración por defecto
```

También puedes pasar una ruta de configuración adicional:

```php
$db = DB::getInstance('/ruta/a/mi_config_personal.ini');
```

---

## ⚙️ Configuración (`config.ini`)

Archivo ubicado en la misma carpeta que la clase. Ejemplo:

```ini
AUTO_ROLLBACK = TRUE
ENVIRONMENT = 'DEVELOPMENT'
TIME_ZONE = '-05:00'
DB_CHARSET = 'utf8'
FETCH_OBJECT = TRUE

[DEVELOPMENT]
HOST = mysql
USER = root
PASSWORD = toor
DB_NAME = patata

[PRODUCTION]
HOST = localhost
USER = root
PASSWORD = toor
DB_NAME = patata
```

- `ENVIRONMENT` define cuál bloque usar.
- Si ocurre una excepción durante una transacción y `AUTO_ROLLBACK = TRUE`, la transacción se revierte automáticamente.
- `FETCH_OBJECT` define si los resultados serán retornados como objetos o como arrays.

---

## 📥 Insertar

```php
$db->insert('usuarios', [
  'nombre' => 'Juan',
  'correo' => 'juan@mail.com'
]);
```

---

## ✏️ Actualizar

```php
$db->update(
  'usuarios',
  ['activo' => 1],
  'id = :id',
  ['id' => 5]
);
```

---

## 🗑️ Eliminar

```php
$db->delete('usuarios', 'id = :id', ['id' => 3]);
```

---

## 🔎 Seleccionar

### Múltiples resultados

```php
$usuarios = $db->select('usuarios', '*', 'activo = :activo', ['activo' => 1]);
```

### Resultado único

```php
$usuario = $db->selectOne('usuarios', '*', 'id = :id', ['id' => 7]);
```

### Alias de campos

```php
$db->select('productos', ['id', 'nombre' => 'nombre_publico']);
```

---

## 📚 Fetch dinámico

Los métodos `fetch()` y `fetchAll()` devuelven los resultados según la configuración definida en `config.ini`.

```php
$usuario = $db->fetch();       // uno solo (objeto o array según config)
$usuarios = $db->fetchAll();   // todos (objeto o array según config)
```

También puedes forzar el tipo:

```php
$db->fetchArray();       // uno como array
$db->fetchArrayAll();    // todos como array

$db->fetchObject();      // uno como objeto
$db->fetchObjectAll();   // todos como objeto
```

---

## 🔁 Transacciones

```php
$db->beginTransaction();

$db->insert(...);
$db->update(...);

$db->commit();
```

> Si ocurre una excepción, y `AUTO_ROLLBACK = TRUE`, el rollback se ejecuta automáticamente.

---

## 📌 Consultas avanzadas

Puedes construir consultas más elaboradas usando `select()` con joins, alias y filtros personalizados.

```php
$fields = [
  'movimientos.id AS movi_id',
  'movimientos.fecha',
  'usuarios.nombre AS usuario_nombre',
  'personas.dni AS persona_dni'
];

$join = 'JOIN usuarios ON movimientos.usua_id = usuarios.id
         JOIN personas ON usuarios.pers_id = personas.id';

$results = $db->select(
  'movimientos ' . $join,
  $fields,
  'movimientos.caja_id = :caja_id',
  ['caja_id' => 5],
  'ORDER BY movimientos.id DESC'
);
```

---

## 📒 Métodos disponibles

| Método                         | Descripción                                         |
|-------------------------------|-----------------------------------------------------|
| `query($sql, $data)`          | Ejecuta una consulta SQL manual con bind automático |
| `insert($table, $data)`       | Inserta un registro                                 |
| `update($table, $data, $where, $whereData)` | Actualiza registros       |
| `delete($table, $where, $data)`              | Elimina registros        |
| `select(...)`                 | Devuelve múltiples resultados                       |
| `selectOne(...)`              | Devuelve un solo resultado                          |
| `fetch()`, `fetchAll()`       | Devuelve resultados según configuración             |
| `rowCount()`                  | Cantidad de filas afectadas                         |
| `getLastInsertId()`           | Último ID insertado                                 |
| `beginTransaction()`          | Inicia una transacción                              |
| `commit()`                    | Confirma cambios                                    |
| `rollback()`                  | Revierte la transacción                             |
| `inTransaction()`             | Verifica si hay una transacción activa              |

---

## ✅ Ejemplo completo

```php
use patata\db\DB;

$db = DB::getInstance();

$db->beginTransaction();

$db->insert('clientes', ['nombre' => 'Ana']);
$db->update('clientes', ['email' => 'ana@correo.com'], 'id = :id', ['id' => 1]);

$cliente = $db->selectOne('clientes', '*', 'id = :id', ['id' => 1]);

$db->commit();
```

---

## 🎯 Objetivo

Esta librería fue diseñada para ofrecer un acceso directo, claro y seguro a la base de datos, sin necesidad de herramientas pesadas o estructuras complejas.  
Para casos especiales o complejos, puedes utilizar el método `query()` para ejecutar SQL personalizado directamente.

---