<?php

chdir(__DIR__);
ini_set('display_errors', 1);
// Setup autoloading
require 'vendor/autoload.php';

$httpExecutor = new \ZF2Deploy\Executor\Html();

if (!file_exists('config.php')) {
    throw new Exception('config.php must exist at the same level as install.php');
}

$configArray = include_once 'config.php';


$config = new \Zend\Config\Config($configArray, true);

$httpExecutor->run($config);

echo "<h1>Plugin Output</h1>";
echo nl2br($httpExecutor->getPluginOutput());

echo "<h1>Log</h1>";
echo nl2br($httpExecutor->getLogOutput());