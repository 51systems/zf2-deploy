<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 18/07/13
 * Time: 9:04 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin;


use Zend\Config\Config;

interface DeploymentPluginInterface
{

    /**
     * Runs the plugin
     * @param array|Config $config
     * @return void
     */
    function run($config);
}