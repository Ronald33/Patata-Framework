<?php
namespace URIDecoder;
require_once('core/Helper/Helper.php');
use Helper\Helper as CoreHelper;

class Helper
{
	public static function getFolderLength() { return strlen(CoreHelper::getFolder()); }
}