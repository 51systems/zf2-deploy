<?php

namespace ZF2Deploy\Executor;

use Zend\Log\Logger;
use ZF2Deploy\Log\Writer\String as StringWriter;

use ZF2Deploy\Plugin\Event\OutputString as OutputStringEvent;

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
     * @param OutputStringEvent $event
     */
    public function outputString(OutputStringEvent $event)
    {
        $this->pluginOutput .= get_class($event->getPlugin()) . ":: " . $event->getString() . PHP_EOL. "--------" . PHP_EOL . PHP_EOL;
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