<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 20/07/13
 * Time: 3:42 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin;
use Zend\Config\Config;


/**
 * Class GlobalConfigurationAwareInterface
 * @package ZF2Deploy\Plugin
 *
 * Allows plugin to access the global configuration (not just their own config)
 */
interface GlobalConfigurationAwareInterface
{
    /**
     * Set the configuration.
     * @param array|Config $config
     * @return void
     */
    public function setGlobalConfig($config);

}