<?php

namespace ZF2Deploy\Plugin;


use Zend\Config\Config;
use ZF2Deploy\Plugin\Event\OutputString as OutputStringEvent;

class EchoPlugin extends AbstractPlugin
{

    /**
     * @inheritdoc
     */
    function run($config)
    {
        if (!empty($config['message'])) {
            $this->info('Outputing non-empty message', array('message' => $config['message']));
            $this->getEventManager()->trigger(new OutputStringEvent($this, $config['message']));
        }
        else {
            $this->warn(get_class($this) . ':: No message specified');
        }
    }
}