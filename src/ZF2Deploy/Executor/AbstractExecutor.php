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
use Zend\Config\Processor\Token as TokenProcessor;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;
use ZF2Deploy\Plugin\DeploymentPluginInterface;
use ZF2Deploy\Plugin\Event\ReplaceToken as ReplaceTokenEvent;
use ZF2Deploy\Plugin\GlobalConfigurationAwareInterface;
use ZF2Deploy\Plugin\Event\Plugin as PluginEvent;
use ZF2Deploy\Plugin\Event\OutputString as OutputStringEvent;
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
     * Current configuration
     * @var array|Config
     */
    protected $config;

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
     * Executes the configuration in the frontend.
     * @param Config $config
     * @return void
     */
    public function run(Config $config)
    {
        $this->doRun = true;
        $this->config = $config;

        if ($this->config->offsetExists('plugins')) {
            $pm = $this->getPluginManager();

            foreach($this->config->get('plugins', array()) as $pluginName => $pluginConfigs) {
                //We assume that we have multiple configurations passed in.
                //This is required in the case where the plugin in invoked multiple times

                if (!isset($pluginConfigs[0]) || !(!is_array($pluginConfigs[0]) || $pluginConfigs[0] instanceof Config)) {
                    //this plugin is only being called once
                    $pluginConfigs = array($pluginConfigs);
                }

                foreach ($pluginConfigs as $pluginConfig) {
                    if (!$this->doRun)
                        break;

                    /** @var DeploymentPluginInterface $plugin */
                    $plugin = $pm->get($pluginName);

                    if ($plugin instanceof GlobalConfigurationAwareInterface) {
                        $plugin->setGlobalConfig($this->config);
                    }

                    $plugin->run($pluginConfig);
                }
            }
        }
    }

    //region Events
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

        $this->eventManager->attach(OutputStringEvent::EVENT_OUTPUT_STRING, array(&$this, 'outputString'));
        $this->eventManager->attach(PluginEvent::EVENT_FAILURE, array(&$this, 'onFailure'));
        $this->eventManager->attach(ReplaceTokenEvent::EVENT_REPLACE_CONFIG_TOKEN, array(&$this, 'onReplaceToken'));

        return $this;
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
     * Called when a Output String Event is emitted.
     * @param OutputStringEvent $event
     * @return void
     */
    public abstract function outputString(OutputStringEvent $event);

    /**
     * Called when a {@link PluginEvent} of type EVENT_FAILURE is emitted.
     *
     * @param PluginEvent $event
     */
    public function onFailure(PluginEvent $event)
    {
        $this->doRun = false;
    }

    /**
     * Called when a ReplaceTokenEvent is emitted
     *
     * @param ReplaceTokenEvent $event
     */
    public function onReplaceToken(ReplaceTokenEvent $event)
    {
        if ($this->config->isReadOnly()) {
            $this->getLogger()->warn('Attempted to replace token in configuration, but configuration is read-only');
            return;
        }

        $processor = new TokenProcessor(array(
            $event->getToken() => $event->getValue()
        ));

        $processor->process($this->config);
    }

    //endregion
}