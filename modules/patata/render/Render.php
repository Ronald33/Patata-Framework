<?php
namespace Modules\Patata\Render;

require_once(__DIR__ . '/Message.php');
require_once(PATH_BASE . '/core/IError.php');

use \core\IError;

class Render
{
    private $config;
    private $data;
    private $template;
    private $pieces;
    private $styles;
    private $scripts;
    private $error;

    public function __construct($data = [])
    {
		$this->config = parse_ini_file(__DIR__ . '/config.ini', true);
		$this->config['data']['scripts'] = &$this->scripts;
		$this->config['data']['styles'] = &$this->styles;

		$this->data = $data;
        
		$this->pieces = [];
		$this->pieces['simple'] = [];
		$this->pieces['loop'] = [];
		
        $this->template = '';
        $this->styles = '';
        $this->scripts = '';
    }

    public function setError(IError $error) { $this->error = $error; }
	
	public function &getConfig() { return $this->config; }

    public function addTemplate($filename)
	{
		if(file_exists($filename)) { $this->template .= file_get_contents($filename); }
		else { $this->error->showMessage(Message::noFile($filename), Message::notFound()); }
    }
    
    public function addContent($content) { $this->template .= $content; }
    public function setContent($content) { $this->template = $content; }
	
	private function setPieces()
	{
		$template = $this->getTemplate(); /* Sacamos una copia del template */
		$pattern = '/<!--' . $this->config['TAG_OPEN'] . $this->config['TAG_LOOP'] . '([a-zA-Z0-9]+)' . $this->config['TAG_CLOSE'] . '-->/';
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
					if(isset($this->data[$name]) && is_array($this->data[$name]))
					{
						$end = $position_close - 1;
						if($position_open > 0) { $this->pieces['simple'][$counter++] = substr($template, 0, $position_open); }
						$this->pieces['loop'][$counter++] = ['name' => $name, 'content' => substr($template, $begin, $position_close - $begin)];
						$template = substr($template, $position_close + $tag_length);
					}
					else { $template = substr_replace($template, '', $position_open, $position_close + $tag_length - $position_open); }
				}
				else { $offset = $begin; }
			}
			else { $flag = false; }
		} while($flag);
		
		if($template) { $this->pieces['simple'][$counter++] = $template; }
	}
	
	private function getData()
	{
		$merged = array_merge($this->config['data'], $this->data);
		$data = [
			'search' => [], 
			'replace' => []
		];
		
		foreach($merged as $key => $value)
		{
			if(!is_array($value))
			{
				array_push($data['search'], '/' . $this->config['TAG_OPEN'] . $key . $this->config['TAG_CLOSE'] . '/');
				array_push($data['replace'], $value);
			}
		}
		
		array_push($data['search'], '/<!--' . $this->config['TAG_OPEN'] . $this->config['TAG_LOOP'] . '.+' . $this->config['TAG_CLOSE'] . '-->/');
		array_push($data['replace'], '');
		
		if($this->config['CLEAR'])
		{
			array_push($data['search'], '/' . $this->config['TAG_OPEN'] . '.+' . $this->config['TAG_CLOSE'] . '/');
			array_push($data['replace'], '');
		}
		
		return $data;
	}
	
	private function replace()
	{
		$data = $this->getData();
		foreach($this->pieces['simple'] as &$piece) { $piece = preg_replace($data['search'], $data['replace'], $piece); }
	}
	
	public function get($include_HF = NULL)
	{
		if($include_HF === NULL) { $this->include_HF = $this->config['INCLUDE_HF']; }
        else { $this->include_HF = $include_HF; }

		$this->setPieces();
		$this->replace(); /* Reemplazamos las piezas planas (sin loops) */
		
		foreach($this->pieces['loop'] as &$piece)
		{
			$name = $piece['name'];
			$content = $piece['content'];
			
			$data = $this->data[$name];
			$replaced = '';
			foreach($data as $d)
			{
				if(!is_array($d)) { $d = array('number' => $d); }
				$r = new Render($d);
				$r->setContent($content);
				$replaced .= $r->get(false);
			}
			$piece = $replaced;
		}
		
		$pieces = $this->pieces['simple'] + $this->pieces['loop'];
		ksort($pieces);
		
		return implode($pieces);
	}
    
    public function addStyle($style) { $this->styles .= self::getStyle($style); }
    public function addScript($script) { $this->scripts .= self::getScript($script); }
    
    private function addTemplateToStart($filename)
	{
		if(file_exists($filename)) { $this->template = file_get_contents($filename) . $this->template; }
		else { $this->error->showMessage(Message::noFile($filename), Message::notFound()); }
    }

    private function getTemplate()
	{
		if($this->include_HF)
		{
			$this->addTemplateToStart($this->config['PATH_HEADER']);
			$this->addTemplate($this->config['PATH_FOOTER']);
		}
		return $this->template;
	}
    
    private static function getStyle($style)
	{
		return '<link rel="stylesheet" type="text/css" href="' . $style . '" media="screen" />';
	}
	
	private static function getScript($script)
	{
		return '<script type="text/javascript" src="' . $script . '" charset="UTF-8"></script>';
	}
}
