<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/Caller.php');
use core\caller\Caller;

$caller = Caller::getInstance('./controller');
$caller->execute('Page', 'method', ['Method called in Page/method']);