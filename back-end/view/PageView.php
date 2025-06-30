<?php
class PageView
{
    public function index($data = [])
	{
		$template = file_get_contents(PATH_HTML . '/page/index.html');
		echo Helper::getTemplateRendered($template, $data);
	}

    public function s404($data = [])
	{
		$template = file_get_contents(PATH_HTML . '/page/404.html');
		echo Helper::getTemplateRendered($template, $data);
	}
}
