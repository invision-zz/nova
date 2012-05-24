<?php
use Core\Load;
use Core\Nova;

define('ROOT', realpath(__DIR__) . '/');
define('CORE', ROOT . 'core/');
define('APP', ROOT . 'app/');

require_once CORE . 'load.php';
require_once APP . 'config.php';

$load = Load::instance();
$nova = new Nova();

$nova->start();
?>
