<?php
class PageView
{
    public function index($data = array())
	{
		$render = Repository::getRender($data);
		$render->addTemplate(PATH_HTML . '/page/index.html');
		$render->addStyle(PATH_CSS . '/page/styles.css');
		$render->addScript(PATH_JS . '/page/scripts.js');
		echo $render->get(true);
	}

    public function s404($data = array())
	{
		$render = Repository::getRender($data, false);
		$render->addTemplate(PATH_HTML . '/page/404.html');
		$render->addStyle(PATH_CSS . '/page/404.css');
		echo $render->get(false);
	}
}
