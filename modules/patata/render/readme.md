# Render

Es una motor de plantillas que permite separar la lógica de negocios (datos) de la presentación (HTML) mediante el uso de etiquetas ({{etiqueta}}).

## Dependencias

La libreria hace uso de una clase llamada Error, la cual se encarga de mostrar los errores y que debe de implementar al siguiente clase:

```php
<?php
interface IError
{
	public function showMessage($messageDevelopment, $messageProduction, $code);
}
```

## Uso de la librería

Para el uso de la libreria son necesarios 2 componentes: Un array asociativo y una plantilla.

Por ejemplo, si se desea renderizar el siguiente array: 

```php
$data = [
            'titulo' => 'Capítulo 1', 
            'descripcion' => 'Descripción del Capítulo 1'
        ]
```

La plantilla debe de contener las claves del array asignado al render, por ejemplo:

```html
<span>{{titulo}}: {{descripcion}}</span>
```

Para realizar iteraciones, será necesario delimitar la plantilla con una etiqueta similar a esta: <!–{{#capitulos}}–>, en donde capitulos, deberá ser la clave de un array en los datos asignados al render, esto puede ser visto en el ejemplo anterior

```php
$data = 
[
    'capitulos' => 
    [
        [
            'titulo' => 'Capítulo 1', 
            'descripcion' => 'Descripción del Capítulo 1'
        ], 
        [
            'titulo' => 'Capítulo 2', 
            'descripcion' => 'Descripción del Capítulo 2'
        ]
    ]
];
```

Para la plantilla con iteraciones se usará el siguiente HTML:

```html
<ul>
    <!--{{#capitulos}}-->
    <li>
        {{titulo}}: {{descripcion}}
    <li>
    <!--{{#capitulos}}-->
</ul>
```

Si se tiene un array asociativo, cuyos indices son números(['Apendice 1', 'Apendice 2', 'Apendice 3']), estos puedo ser accedidos utilizando la etiqueta {{number}}, un ejemplo de esto es mostrado a continuación:

```html
<ul class="lista-apendices">
    <!--{{#apendices}}-->
    <li>{{number}}</li>
    <!--{{#apendices}}-->
</ul>
```

## Métodos

| Método | Descripción |
| ------ | ------ |
| setError(IError $error) | Asigna la clase encargada de mostrar los errores en la ejecución de la libreria. |
| &getConfig() | Obtiene la referencia de la configuración. |
| setContent($content) | Asigna el contenido de $content a la plantilla. |
| addContent($content) | Agrega el contenido de $content a la plantilla. |
| addTemplate($filename) | Asigna el contenido del archivo $filename como plantilla. |
| get($include\_HF) | Obtiene el resultado de la mezcla de la plantilla con el array asociativo asignado, el parametro $include\_HF indica si se debe incluir el header y el footer. |
| addStyle($style) | Carga una hoja de estilos almacenado en la ruta $style en el documento renderizado. |
| addScript($script) | Carga un script almacenado en la ruta $script en el documento renderizado. |

Para mostrar las hojas de estilo y los scripts, se deberá agregar en la plantilla las etiquetas: {{styles}} y {{scripts}} respectivamente.

## Configuración

El archivo config.ini nos permite configurar algunos aspectos de la libreria, entre los cuals se encuentran:

| Método | Descripción |
| ------ | ------ |
| TAG\_OPEN | Indica los caracteres que demarcarán el inicio de un valor que será remplazado. |
| TAG\_CLOSE | Indica los caracteres que demarcarán el final de un valor que será remplazado. |
| TAG\_LOOP | Indica los caracteres que marcarán una etiqueta como inicio y fin de un bucle. |
| CLEAR | Indica si se debe limpiar en la plantilla, aquellos elementos que no hayan sido encontrados en los datos asignados al render. |
| PATH\_HEADER | Indica la ruta del header (Podría ser asignado utilizando una constante llamada PATH\_HTML). |
| PATH\_FOOTER | Indica la ruta del footer (Podría ser asignado utilizando una constante llamada PATH\_HTML). |
| INCLUDE\_HF | Indica si se agregará el header y footer a la plantilla asignada. |

Finalmente existe un apartado en donde se podrá configurar algunas etiquetas y sus respectivos valores, los cuales serán agregados en todos las instancias de Render, si existe duplicidad en las claves, tendrá prioridad las claves asignadas en el constructor del Render.

En el apartado de datos, existe una clave para la url base del proyecto (URL_BASE), este podrá ser asignado manualmente o mediante la constante URL\_BASE.