<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dustin
 * Date: 19/07/13
 * Time: 4:16 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ZF2Deploy\Executor;
use Zend\Config\Config;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;
use ZF2Deploy\Plugin\DeploymentPluginInterface;
use ZF2Deploy\Plugin\PluginEvent;
use ZF2Deploy\PluginManager;

/**
 * Class AbstractFrontend
 * @package ZF2Deploy\Frontend
 *
 * Provides a base class for the frontend.
 */
abstract class AbstractExecutor implements EventManagerAwareInterface
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PluginManager
     */
    protected $pluginManager;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * Flag to indicate that we should continue to process plugins.
     * @var boolean
     */
    protected $doRun;

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if ($this->logger == null) {
            $this->setLogger($this->createLogger());
        }

        return $this->logger;
    }

    /**
     * @param \Zend\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        if ($this->pluginManager != null) {
            $this->pluginManager->setLogger($this->logger);
        }
    }

    /**
     * Factory method to create a logger if none exists
     */
    protected function createLogger()
    {
        return new Logger();
    }

    /**
     * @param \ZF2Deploy\PluginManager $pluginManager
     */
    public function setPluginManager(PluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        $this->pluginManager->setEventManager($this->getEventManager());
    }

    /**
     * @return \ZF2Deploy\PluginManager
     */
    public function getPluginManager()
    {
        if ($this->pluginManager == null) {
            $pluginManager = new PluginManager();
            $pluginManager->setLogger($this->getLogger());
            $this->setPluginManager($pluginManager);
        }
        return $this->pluginManager;
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

        $this->eventManager->attach(PluginEvent::EVENT_OUTPUT_STRING, array(&$this, 'outputString'));
        $this->eventManager->attach(PluginEvent::EVENT_FAILURE, array(&$this, 'onFailure'));

        return $this;
    }

    public abstract function outputString(PluginEvent $event);

    public function onFailure(PluginEvent $event)
    {
        $this->doRun = false;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    /**
     * Executes the configuration in the frontend.
     * @param Config $config
     * @return void
     */
    public function run(Config $config)
    {
        $this->doRun = true;

        if ($config->offsetExists('plugins')) {
            $pm = $this->getPluginManager();

            foreach($config->get('plugins', array()) as $pluginName => $pluginConfig) {

                if (!$this->doRun)
                    break;

                /** @var DeploymentPluginInterface $plugin */
                $plugin = $pm->get($pluginName);

                $plugin->run($pluginConfig);
            }
        }
    }
}