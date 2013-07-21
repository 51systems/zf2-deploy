<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 18/07/13
 * Time: 9:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin;
use Traversable;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Log\LoggerInterface;

/**
 * Class AbstractPlugin
 * @package ZF2Deploy\Plugin
 *
 * Abstract base class for plugins.
 */
abstract class AbstractPlugin implements DeploymentPluginInterface, EventManagerAwareInterface, LoggerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @param \Zend\Log\LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return \Zend\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param EventManagerInterface $events
     * @return $this
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->eventManager = $events;
        return $this;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    //region LoggingMethods

    /**
     * Formats the log message to include the class name automatically
     * @param string $message
     * @return string
     */
    private function formatMessage($message)
    {
        return sprintf('%s:: %s', get_class($this), $message);
    }

    /**
     * @inheritdoc
     */
    public function emerg($message, $extra = array())
    {
        $this->getLogger()->emerg($this->formatMessage($message), $extra);
    }

    /**
     * @inheritdoc
     */
    public function alert($message, $extra = array())
    {
        $this->getLogger()->alert($this->formatMessage($message), $extra);
    }

    /**
     * @inheritdoc
     */
    public function crit($message, $extra = array())
    {
        $this->getLogger()->crit($this->formatMessage($message), $extra);
    }

    /**
     * @inheritdoc
     */
    public function err($message, $extra = array())
    {
        $this->getLogger()->err($this->formatMessage($message), $extra);
    }

    /**
     * @inheritdoc
     */
    public function warn($message, $extra = array())
    {
        $this->getLogger()->warn($this->formatMessage($message), $extra);
    }

    /**
     * @inheritdoc
     */
    public function notice($message, $extra = array())
    {
        $this->getLogger()->notice($this->formatMessage($message), $extra);
    }

    /**
     * @inheritdoc
     */
    public function info($message, $extra = array())
    {
        $this->getLogger()->info($this->formatMessage($message), $extra);
    }

    /**
     * @inheritdoc
     */
    public function debug($message, $extra = array())
    {
        $this->getLogger()->debug($this->formatMessage($message), $extra);
    }
    //endregion

}