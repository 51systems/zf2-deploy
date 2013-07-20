<?php

namespace ZF2Deploy\Executor;

use Zend\Log\Logger;
use ZF2Deploy\Log\Writer\String as StringWriter;
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

    /**
     * @var StringWriter
     */
    private $logWriter;

    /**
     * @inheritdoc
     */
    protected function createLogger()
    {
        $logger = parent::createLogger();

        if ($this->logWriter == null) {
            $this->logWriter = new StringWriter();
        }

        $logger->addWriter($this->logWriter);

        return $logger;
    }

    /**
     * @param PluginEvent $event
     */
    public function outputString(PluginEvent $event)
    {
        $this->pluginOutput .= get_class($event->getPlugin()) . ":: " . $event->getParam('output') . "\n--------\n\n";
    }

    /**
     * Gets the output of the log.
     * @return string
     */
    public function getLogOutput()
    {
        return $this->logWriter->getLogString();
    }

    /**
     * Gets the output of the plugins
     * @return string
     */
    public function getPluginOutput()
    {
        return $this->pluginOutput;
    }
}