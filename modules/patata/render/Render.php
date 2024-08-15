<?php
namespace modules\patata\render;

class Render
{
    private $config;
    private $pathHeader;
    private $pathFooter;
    private $template;
    private $pieces;
    private $styles;
    private $scripts;

    public function __construct($extra_configuration_path = NULL, $path_header = NULL, $path_footer = NULL)
    {
		$extra_config = $extra_configuration_path !== NULL ? parse_ini_file($extra_configuration_path, true) : [];
        $this->config = array_merge(parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true), $extra_config);

		$this->checkConfigAsserts();

		if($path_header != NULL) { $this->pathHeader = $path_header; }
		if($path_header != NULL) { $this->pathFooter = $path_footer; }

		$this->template = '';
		
		$this->pieces = [];
		$this->pieces['simple'] = [];
		$this->pieces['loop'] = [];

        $this->styles = '';
        $this->scripts = '';
    }

	private function checkConfigAsserts()
	{
		assert(is_string($this->config['TAG_OPEN']), 'In Render, TAG_OPEN is invalid');
		assert(is_string($this->config['TAG_CLOSE']), 'In Render, TAG_CLOSE is invalid');
		assert(is_string($this->config['TAG_LOOP']), 'In Render, TAG_LOOP is invalid');
	}

	public function setContentToTemplate($content) { $this->template = $content; }
	public function prependContentToTemplate($content) { $this->template = $this->template . $content; }
	public function appendContentToTemplate($content) { $this->template .= $content; }

	public function setFileToTemplate($filename)
	{
		assert(file_exists($filename), 'El archivo: ' . $filename . ' no existe');
		$this->template = file_get_contents($filename);
    }
	public function prependFileToTemplate($filename)
	{
		assert(file_exists($filename), 'El archivo: ' . $filename . ' no existe');
		$this->template = file_get_contents($filename) . $this->template;
    }
	public function appendFileToTemplate($filename)
	{
		assert(file_exists($filename), 'El archivo : ' . $filename . ' no existe');
		$this->template .= file_get_contents($filename);
    }

    private function getTemplate()
	{
		if($this->pathHeader != NULL) { $this->prependFileToTemplate($this->pathHeader); }
		if($this->pathFooter != NULL) { $this->appendFileToTemplate($this->pathFooter); }
		return $this->template;
	}
	
	private function setPieces($replacements)
	{
		$template = $this->getTemplate();
		$pattern = '/<!--' . $this->config['TAG_OPEN'] . $this->config['TAG_LOOP'] . '(.+)' . $this->config['TAG_CLOSE'] . '-->/';
		$offset = 0;
		$flag = true;
		$counter = 0;
		
		do
		{
			$has_loop = preg_match($pattern, $template, $match, PREG_OFFSET_CAPTURE, $offset);
			if($has_loop) /* Verificamos que exista un tag de bucle de inicio */
			{
				$tag = $match[0][0]; /* Guardamos el tag encontrado */
				$tag_length = strlen($tag); /* Guardamos el size del tag */
				$position_open = $match[0][1]; /* Guardamos la posicion encontrada */
				$begin = $position_open + $tag_length;
				$name = $match[1][0]; /* Guardamos el nombre identificador del bucle */
				$position_close = strpos($template, $tag, $begin); /* Ahora buscamos el tag de cierre */

				if($position_close) /* Si existe el tag de cierre */
				{
					if(isset($replacements[$name]) && is_array($replacements[$name]))
					{
						if($position_open > 0) { $this->pieces['simple'][$counter++] = substr($template, 0, $position_open); }
						$this->pieces['loop'][$counter++] = ['name' => $name, 'content' => substr($template, $begin, $position_close - $begin)];
						$template = substr($template, $position_close + $tag_length);
					}
					else{ $template = substr_replace($template, '', $position_open, $position_close + $tag_length - $position_open); }
				}
				else { $offset = $begin; }
			}
			else { $flag = false; }
		} while($flag);
		
		if($template) { $this->pieces['simple'][$counter++] = $template; }
	}
	
	private function getReplacements($replacements)
	{
		if(isset($this->config['REPLACEMENTS']) && is_array($this->config['REPLACEMENTS'])) { $replacements = array_merge($this->config['REPLACEMENTS'], $replacements); }

		$replacements['styles'] = $this->styles;
		$replacements['scripts'] = $this->scripts;

		$results = [
			'search' => [], 
			'replace' => []
		];

		foreach($replacements as $key => $value)
		{
			if(!is_array($value))
			{
				array_push($results['search'], '/' . $this->config['TAG_OPEN'] . $key . $this->config['TAG_CLOSE'] . '/');
				array_push($results['replace'], $value);
			}
		}
		
		array_push($results['search'], '/<!--' . $this->config['TAG_OPEN'] . $this->config['TAG_LOOP'] . '.+' . $this->config['TAG_CLOSE'] . '-->/');
		array_push($results['replace'], '');
		
		if($this->config['CLEAR'])
		{
			array_push($results['search'], '/' . $this->config['TAG_OPEN'] . '.+' . $this->config['TAG_CLOSE'] . '/');
			array_push($results['replace'], '');
		}

		return $results;
	}
	
	private function replace($replacements)
	{
		$data = $this->getReplacements($replacements);
		foreach($this->pieces['simple'] as &$piece) { $piece = preg_replace($data['search'], $data['replace'], $piece); }
	}
	
	public function get($replacements = [])
	{
		assert(is_array($replacements), 'La plantilla necesita un array de datos');
		$this->setPieces($replacements);
		$this->replace($replacements); /* Reemplazamos las piezas planas (sin loops) */

		foreach($this->pieces['loop'] as &$piece)
		{
			$content = $piece['content'];
			$data = $replacements[$piece['name']];
			$piece = '';
			$replaced = '';
			
			foreach($data as $d)
			{
				if(!is_array($d)) { $d = array('number' => $d); }
				$r = new Render();
				$r->setContentToTemplate($content);
				$replaced .= $r->get($d);
			}
			$piece = $replaced;
		}
		
		$pieces = $this->pieces['simple'] + $this->pieces['loop'];
		ksort($pieces);
		
		return implode($pieces);
	}
    
    public function addStyle($style) { $this->styles .= '<link rel="stylesheet" type="text/css" href="' . $style . '" media="screen" />'; }
    public function addScript($script) { $this->scripts .= '<script type="text/javascript" src="' . $script . '" charset="UTF-8"></script>'; }
}
