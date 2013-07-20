<?php

namespace ZF2Deploy\Executor;

use Zend\Log\Logger;
use ZF2Deploy\Log\Writer\String;
use ZF2Deploy\Plugin\PluginEvent;

/**
 * Class Html
 * @package ZF2Deploy\Executor
 *
 * An executor that produces HTML output
 */
class Html extends AbstractExecutor
{
    /**
     * Plugin Output
     * @var string
     */
    private $pluginOutput = '';

    private $logWriter;

    /**
     * @inheritdoc
     */
    protected function createLogger()
    {
        $logger = parent::createLogger();

        if ($this->logWriter == null) {
            $this->logWriter = new String();
        }

        $logger->addWriter($this->logWriter);

        return $logger;
    }


    public function outputString(PluginEvent $event)
    {
        $this->pluginOutput .= get_class($event->getPlugin()) . ":: " . $event->getParam('output');
    }
}