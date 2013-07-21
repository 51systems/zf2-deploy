<?php
namespace ZF2Deploy\Tests;//Change this namespace for your test

use Composer\Autoload\ClassLoader;
use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use RuntimeException;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

class Bootstrap
{
    protected static $serviceManager;
    protected static $config;
    protected static $bootstrap;

    public static function init()
    {
        static::initAutoloader();

    }


    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');

        if (is_readable($vendorPath . '/autoload.php')) {
            /** @var ClassLoader $loader */
            $loader = include $vendorPath . '/autoload.php';

            $loader->addClassMap(array(
                'ZF2Deploy' => 'src/'
            ));

        } else {
            throw \Exception('Cannot locate vendor autoload file');
        }
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) return false;
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }
}

Bootstrap::init();