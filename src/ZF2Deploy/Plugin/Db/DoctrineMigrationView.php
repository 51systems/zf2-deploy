<?php

namespace ZF2Deploy\Plugin\Db;


use Clinner\Command\Command;
use Zend\Config\Config;
use ZF2Deploy\Plugin\AbstractPlugin;
use ZF2Deploy\Plugin\PluginEvent;

/**
 * Class DoctrineMigrationView
 * @package ZF2Deploy\Plugin\Db
 *
 * Plugin to display the SQL that is required to upgrade the database.
 * Uses the doctrine command line utility to generate the SQL.
 */
class DoctrineMigrationView extends AbstractPlugin
{
    /**
     * Runs the plugin
     * @param array|Config $config
     * @return void
     */
    function run($config)
    {
        if (!isset($config['doctrine-console-path'])) {
            throw new Exception('doctrine-console-path must be set');
        }

        $sql = Command::create($config['doctrine-console-path'], array('orm:schema-tool:update', '--dump-sql'))
            ->run()
            ->getOutput();

        $this->getEventManager()->trigger(PluginEvent::OutputStringFactory($this, $sql));
    }
}