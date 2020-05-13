<?php
define('PATH_BASE', './../../../.');
require_once(__DIR__ . '/Render.php');
require_once(__DIR__ . '/../Error/Error.php');

use modules\patata\render\Render;
use modules\patata\error\Error;

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
			'descripcion' => 'Descripción del Capítulo 2', 
			'secciones' => 
			[
				['titulo' => 'Sección 2.1', 'descripcion' => 'Decripción de la sección 2.1'], 
				['titulo' => 'Sección 2.2', 'descripcion' => 'Decripción de la sección 2.2'], 
				['titulo' => 'Sección 2.3', 'descripcion' => 'Decripción de la sección 2.3']
			]
		], 
		[
			'titulo' => 'Apendices', 
			'descripcion' => 'Listado de apendices', 
			'apendices' => ['Apendice 1', 'Apendice 2', 'Apendice 3']
		], 
	]
];

$error = new Error();
$render = new Render($data);
$config = &$render->getConfig();
$config['PATH_HEADER'] = './header.phtml';
$config['PATH_FOOTER'] = './footer.phtml';

$render->setError($error);
$content = 
<<<EOF
Indice
<ul>
<!--{{#capitulos}}-->
<li class="lista-capitulos">
	{{titulo}}: {{descripcion}}
	<ul class="lista-secciones">
		<!--{{#secciones}}-->
		<li>{{titulo}}: {{descripcion}}</li>
		<!--{{#secciones}}-->
	</ul>
	<ul class="lista-apendices">
		<!--{{#apendices}}-->
		<li>{{number}}</li>
		<!--{{#apendices}}-->
	</ul>
</li>
<!--{{#capitulos}}-->
</ul>
EOF;
$render->addContent($content);
echo $render->get();