# Patata-Framework
Patata Framework es una herramienta dirigida para los desarrolladores que desean empezar a comprender el patrón arquitectónico MVC desde un punto de vista simple y adaptable, teniendo una curva de aprendizaje extremadamente corta, dicho Framework esta inspirado en Europio Engine un motor de frameworks desarrollado por Eugenia Bahit.  Para un apoyo extra este framework cuenta con 3 librerías removibles y editables las cuales facilitan la conexion con bases de datos MySql, Validaciones de campos y el trabajo con plantillas.  Desarrollado por: Ronald Darwin Apaza Veliz.

<p>Para un mejor control este framework sugiere la división de trabajo en 2 grupos que se ven reflejados en 2 carpetas distintas</p>

<ol>
  <li><b>Front-End:</b> En este segmento se deberán de ir archivos con la extensión: html, css, js, etc.</li>
  <li><b>Back-End:</b> En este segmento irán todos los archivos PHP, para un mejor orden no se recomienda incrustar HTML en las vistas.</li>
</ol>

<p>A continuación se describe algunos usos de las 3 librerías agregadas en este versión del framework.</p>

<h2>DB</h2>

<ol>
  <li>Primero introduzca los datos de conexion en el archivo config.php, 
  	que se encuentra dentro de la carpeta back-end/libraries/DB/user</li>
  <li>Exportamos el archivo: require_once(LIBRARIES . 'DB/DB.php');</li>
  <li>Usamos: use DB\DB;</li>
  <li>Cree un bloque try - catch en su controlador</li>
  <li>Creamos un objeto DB</li>
  <li>Dentro de su MODELO, realice las consultas disponibles:
  	query, insert, select, update, delete, beginTransaction, commit, rollback, fetchObject, etc.</li>
  <li>Si ocurre un error capturelo dentro de bloque catch.</li>
</ol>

Ejemplo:

<pre class="lang:php decode:true">
&lt;?php
require_once(LIBRARIES . 'DB/DB.php'); /* Esto deberia estar en su modelo */
use DB\DB; /* Esto deberia estar en su modelo */
try /* Recomendamos que todo lo que esta dentro de este bloque deba estar en su MODELO */
{
	$db = new DB(); /* Creamos el objeto DB */
	/* SELECT */
	$fields = array('usua_id' => 'id', 'nombre'); 	// SELECT 	usuario_id AS id, nombre 
	$table = 'usuarios';							// FROM		usuarios
	$where = 'usua_id = :id';						// WHERE 	usua_id = :id 
	$limit = array(1);								// LIMIT	1
	$replacements = array('id' => '4');
	$resultset = $db->select($table, $fields, $where, $replacements, $limit);
}
catch(Exception $e)
{
	echo $e->getMessage();
}
</pre>

<h2>Render de Plantillas</h2>

Se recomienda usar esta libreria en las vistas

<ol>
<li>Exportamos el archivo: require_once(LIBRARIES . 'Render/Render.php');</li>
<li>Usamos: use Render\Render;</li>
<li>Creamos un objeto Render asignandole el diccionario, debera agregar o no el header y footer</li>
<li>Agregamos los templates necesarios con: $render->addTemplate('file.html');</li>
<li>Agregamos los styles necesarios con: $render->addStyle('styles.css');</li>
<li>Agregamos los scripts necesarios con: $render->addScript('scripts.css');</li>
<li>Mostramos el resultado con: echo $render;</li>
</ol>

Ejemplo:

<pre class="lang:php decode:true">&lt;?php
require_once(LIBRARIES . 'Render/Render.php');
use Render\Render;

$actors = array
(
	array('name' => 'Paulo', 'age' => '22'), 
	array('name' => 'Nick, 'age' => '28')
);
$data = array('actors' => $actors);
$render = new Render($data);
$render->addTemplate('template.html');
$render->addStyle('styles.css');
$render->addScript('scripts.css');
echo $render;
</pre>

Luego para su uso simplemente bastaría con colocar en nuestro archivo HTML:

<b>template.html</b>
<pre>
&lt;!--{{#actors}}--&gt;
  &lt;div class="name"&gt;{{name}}&lt;/div&gt;
  &lt;div class="age"&gt;{{age}}&lt;/div&gt;
&lt;!--{{#actors}}--&gt;
</pre>

Donde: 
<b>&lt;!--{{#actors}}--&gt;</b>: Representa el inicio y el cierre de un bucle


<h5>Este algoritmo es recursivo por lo cual, si existen subitems no existira ningún problema.</h5>
<h2>Validator</h2>

<ol>
<li>Exportamos el archivo: require_once(LIBRARIES . 'Validate/Form.php');</li>
<li>Usamos: use Validate\Form;</li>
<li>Creamos un objeto Form: $form = new Form();</li>
<li>Empezamos a agregar valores (addValue, addPost, addGet), y seguidamente agregamos sus reglas</li>
<li>Verificamos si el form es valido con: $form->isValid()</li>
</ol>

Ejemplo:

<pre class="lang:php decode:true">&lt;?php
require_once(LIBRARIES . 'Validate/Form.php');
use Validate\Form;

$form = new Form();
$form->addValue('age', '25')->addRule('isInt')->addRule('isPositive');
if($form->isValid())
{
	
}
else
{
	$messages = $form->getMessages();
}
</pre>
