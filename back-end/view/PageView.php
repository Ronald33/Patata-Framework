<?php
class PageView
{
    public function index($data = [])
	{
		$render = Repository::getRender();
		$render->setFileToTemplate(PATH_HTML . '/page/index.html');
		$render->addStyle(PATH_CSS . '/page/styles.css');
		$render->addScript(PATH_JS . '/page/scripts.js');
		echo $render->get($data);
	}

    public function s404($data = [])
	{
		$render = Repository::getRender(false);
		$render->setFileToTemplate(PATH_HTML . '/page/404.html');
		echo $render->get($data);
	}
}
