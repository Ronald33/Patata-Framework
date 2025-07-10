<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'configurator' . DIRECTORY_SEPARATOR . 'Configurator.php');

use core\configurator\Configurator;

$configurator = new Configurator(IN_PRODUCTION);
$configurator->enableAssertions();
if(SHOW_ALL_ERRORS) { $configurator->showAllErrors(); }
if(ENABLE_CUSTOM_ERRORS) { $configurator->enableCustomErrors(); }