<?php
namespace Render;
require_once(LIBRARIES . 'Render/config/config.php');
require_once(LIBRARIES . 'Render/helper/Helper.php');
require_once(LIBRARIES . 'Render/message/Message.php');
require_once(LIBRARIES . 'Render/your_dictionary/Dictionary.php');
require_once('core/Error/Error.php');
use Error\Error;

class Render
{
    // Nombre del template
	private $template = '';
    // Array asociativo con los datos a reemplazar
	private $data;
    // String concatenado de todos los estilos requeridos
	private $styles = '';
    // String concatenado de todos los scripts javascript requeridos
	private $scripts = '';
    // Booleano el cual indica si se debe agregar el header y footer en la vista
	private $include_HF;
    
	private $tag_open = TAG_OPEN; // Variable que indica el valor que debe tener el tag de apertura
	private $tag_close = TAG_CLOSE; // Variable que indica el valor que debe tener el tag de cierre
	private $tag_loop = TAG_LOOP; // Variable que indica el valor que debe tener el tag de bucle, que se encuentra a continuacion del tag de apertura
    
    // Variable que indica si se debe limpiar o no los valores que no se encuentran descritos en la variable data
	private $clear = RENDER_CLEAR;
    
    // Variable interna
	private $pieces = array('simple' => array(), 'loop' =>array());
	
	public function __construct($data = array(), $include_HF = INCLUDE_HF)
	{
		$this->data = $data;
		$this->include_HF = $include_HF;
		
		if($this->include_HF) { $this->addTemplate(HEADER); }
		
		$this->data['styles'] = &$this->styles;
		$this->data['scripts'] = &$this->scripts;
	}
	
	public function addTemplate($filename)
	{
		if(file_exists($filename)) { $this->template .= file_get_contents($filename); }
		else { Error::showMessage(Message::noFile($filename), Message::default, true); }
	}
	public function addContent($content) { $this->template .= $content; }
	public function setContent($content) { $this->template = $content; }
	public function addStyle($style) { $this->styles .= Helper::getStyle($style); }
	public function addScript($script) { $this->scripts .= Helper::getScript($script); }
	public function setTagOpen($tag) { $this->tag_open = $tag; }
	public function setTagClose($tag) { $this->tag_close = $tag; }
	public function setTagLoop($tag) { $this->tag_loop = $tag; }
	public function setClear($clear) { $this->clear = $clear; }
	
	private function getTemplate()
	{
		if($this->include_HF) { $this->addTemplate(FOOTER); }
		return $this->template;
	}
	
	private function setPieces()
	{
		$template = $this->getTemplate(); /* Sacamos una copia del template */
		$pattern = '/<!--' . $this->tag_open . $this->tag_loop . '([a-zA-Z0-9]+)' . $this->tag_close . '-->/';
		$offset = 0;
		$flag = true;
		$pieces = array();
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
					$end = $position_close + $tag_length;
					if(isset($this->data[$name]) && is_array($this->data[$name]))
					{
						$sub_template = substr($template, 0, $position_close-1);
						$pieces = explode($tag, $sub_template);
						$this->pieces['simple'][$counter++] = array_shift($pieces);
						$piece_loop = array('name' => $name, 'content' => $pieces[0]);
						$this->pieces['loop'][$counter++] = $piece_loop;
						$template = substr($template, $end);
					}
					else
					{
						/* Removemos la parte del HTML si no existe un bucle */
						$template = substr_replace($template, '', $position_open, $end - $position_open);
						$offset = $begin;
					}
				}
				else { $offset = $begin; }
			}
			else { $flag = false; }
		} while($flag);
		
		$this->pieces['simple'][$counter++] = $template;
	}
	
	private function getData()
	{
		$this->data = array_merge(Dictionary::get(), $this->data, Dictionary::getSuperData());
		$data = array('search' => array(), 'replace' => array());
		foreach($this->data as $key => $value)
		{
			if(!is_array($value))
			{
				array_push($data['search'], '/' . $this->tag_open . $key . $this->tag_close . '/');
				array_push($data['replace'], $value);
			}
		}
		array_push($data['search'], '/<!--' . $this->tag_open . $this->tag_loop . '.+' . $this->tag_close . '-->/');
		array_push($data['replace'], '');
		if($this->clear)
		{
			array_push($data['search'], '/' . $this->tag_open . '.+' . $this->tag_close . '/');
			array_push($data['replace'], '');
		}
		
		return $data;
	}
	
	private function replace()
	{
		$data = $this->getData();
		foreach($this->pieces['simple'] as &$piece) { $piece = preg_replace($data['search'], $data['replace'], $piece); }
	}
	
	public function __toString()
	{
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
				$r = new Render($d, false);
				$r->setContent($content);
				$replaced .= $r;
			}
			$piece = $replaced;
		}
		
		$pieces = $this->pieces['simple'] + $this->pieces['loop'];
		ksort($pieces);
		
		return implode($pieces);
	}
}