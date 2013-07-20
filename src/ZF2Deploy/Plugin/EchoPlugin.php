<?php

namespace ZF2Deploy\Plugin;


use Zend\Config\Config;

class EchoPlugin extends AbstractPlugin
{

    /**
     * @inheritdoc
     */
    function run($config)
    {
        if (!empty($config['message'])) {
            $this->getLogger()->info(get_class($this) . ':: Outputing non-empty message', array('message' => $config['message']));
            $this->getEventManager()->trigger(PluginEvent::OutputStringFactory($this, $config['message']));
        }
        else {
            $this->getLogger()->warn(get_class($this) . ':: No message specified');
        }
    }
}