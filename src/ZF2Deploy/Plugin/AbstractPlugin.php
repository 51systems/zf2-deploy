<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 18/07/13
 * Time: 9:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Plugin;
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
abstract class AbstractPlugin implements DeploymentPluginInterface, EventManagerAwareInterface
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
}