<?php
use Render\Render;

class RenderView
{
    public function render($data = array())
	{
		$render = new Render($data, true);
		$render->addTemplate(HTML . 'render/template.html');
		$render->addStyle(CSS . 'render/styles.css');
        $render->addScript(JS . 'render/scripts.js');
        $render->setClear(false);
		echo $render;
	}
}
